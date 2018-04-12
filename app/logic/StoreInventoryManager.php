<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * StoreInventoryManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class StoreInventoryManager extends BaseManager {

  /** Name field. */
  protected $nameField = "id";

  /** Order by */
  protected $orderBy = "id ASC";


  /** Fields. */
  public $fields = array("bar_code" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "product_code" => array("type" => "input", "attrs" => array("maxlength" => 20)),
                         "product" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "Product")),
                         "product_group_id" => array("type" => "input_integer"),
                         "store" => array("type" => "select", "class" => "required", "relation" => array("entity_name" => "Store")),
                         "qty" => array("type" => "input_integer"));

  /** List params. */
  public $listParams = array("product.name", "product_group_id", "store.name", "qty");

  /**
   * Update product statuses
   */
  public function updateProductStatuses() {
    log_message('debug', '[updateProductStatuses] - Started');

    $inventories = $this->executeNativeSQL('SELECT product_id, product_group_id, bar_code, SUM(qty) as qty FROM store_inventory GROUP BY product_id, product_group_id, bar_code;');
    $reserves = $this->executeNativeSQL('SELECT product_id, product_group_id, SUM(qty) as qty FROM store_reserve GROUP BY product_id, product_group_id;');
    $reservesMap = array();
    foreach ($reserves as $k => $reserve) {
      $reservesMap[$reserve['product_id']][$reserve['product_group_id']] = $reserve['qty'];
      unset($reserves[$k]);
    }

    $productZeroBarcodes = array();
    $groupZeroBarcodes = array();
    $productBarcodes = array();
    $groupBarcodes = array();
    foreach ($inventories as $inventory) {
      if (isset($reservesMap[$inventory['product_id']][$inventory['product_group_id']])) {
        $inventory['qty'] -= $reservesMap[$inventory['product_id']][$inventory['product_group_id']];
      }

      if (!empty($inventory['product_group_id'])) {
        if ($inventory['qty'] <= 0) {
          $groupZeroBarcodes[] = $inventory['bar_code'];
        } else {
          $groupBarcodes[] = $inventory['bar_code'];
        }
      } else {
        if ($inventory['qty'] <= 0) {
          $productZeroBarcodes[] = $inventory['bar_code'];
        } else {
          $productBarcodes[] = $inventory['bar_code'];
        }
      }
    }

    $emptyGroupIds = $this->executeNativeSQL('SELECT parameter_group.bar_code FROM `parameter_group` LEFT JOIN store_inventory ON parameter_group.bar_code = store_inventory.bar_code WHERE store_inventory.id IS NULL AND parameter_group.not_in_stock = FALSE;');
    if (!empty($emptyGroupIds)) {
      $emptyGroupIds = get_array_vals_by_second_key($emptyGroupIds, 'bar_code');
      $groupZeroBarcodes = array_merge($groupZeroBarcodes, $emptyGroupIds);
      $groupZeroBarcodes = array_unique($groupZeroBarcodes);
      $groupZeroBarcodes = array_values($groupZeroBarcodes);
    }

    $emptyProductIds = $this->executeNativeSQL('SELECT product.bar_code FROM `product` LEFT JOIN store_inventory ON product.bar_code = store_inventory.bar_code WHERE store_inventory.id IS NULL AND product.not_in_stock = FALSE;');
    if (!empty($emptyProductIds)) {
      $emptyProductIds = get_array_vals_by_second_key($emptyProductIds, 'bar_code');
      $productZeroBarcodes = array_merge($productZeroBarcodes, $emptyProductIds);
      $productZeroBarcodes = array_unique($productZeroBarcodes);
      $productZeroBarcodes = array_values($productZeroBarcodes);
    }

    if (!empty($productZeroBarcodes)) {
      ManagerHolder::get('Product')->updateAllWhere(array('bar_code' => $productZeroBarcodes, 'not_in_stock' => FALSE), array('not_in_stock' => TRUE));
    }
    if (!empty($productBarcodes)) {
      ManagerHolder::get('Product')->updateAllWhere(array('bar_code' => $productBarcodes, 'not_in_stock' => TRUE), array('not_in_stock' => FALSE));
    }

    if (!empty($groupZeroBarcodes)) {
      ManagerHolder::get('ParameterGroup')->updateAllWhere(array('bar_code' => $groupZeroBarcodes, 'not_in_stock' => FALSE), array('not_in_stock' => TRUE));
    }
    if (!empty($groupBarcodes)) {
      ManagerHolder::get('ParameterGroup')->updateAllWhere(array('bar_code' => $groupBarcodes, 'not_in_stock' => TRUE), array('not_in_stock' => FALSE));
    }

    $groupStatuses = $this->executeNativeSQL('SELECT product_id,GROUP_CONCAT(not_in_stock SEPARATOR \'\') as status FROM parameter_group GROUP BY product_id;');

    $productIds = array();
    $productZeroIds = array();
    foreach ($groupStatuses as $groupStatus) {
      if (strpos($groupStatus['status'], '0') !== FALSE) {
        $productIds[] = $groupStatus['product_id'];
      } else {
        $productZeroIds[] = $groupStatus['product_id'];
      }
    }

    if (!empty($productZeroIds)) {
      ManagerHolder::get('Product')->updateAllWhere(array('id' => $productZeroIds, 'not_in_stock' => FALSE), array('not_in_stock' => TRUE));
    }
    if (!empty($productIds)) {
      ManagerHolder::get('Product')->updateAllWhere(array('id' => $productIds, 'not_in_stock' => TRUE), array('not_in_stock' => FALSE));
    }

    log_message('debug', '[updateProductStatuses] - Finished');
  }

  /**
   * saveInventory
   * @param array $data
   * @param int $storeID
   * @param bool $insertOnlyMode
   */
  public function saveInventory($data, $storeID, $insertOnlyMode = FALSE) {
    $where = array('store_id' => $storeID, 'bar_code' => $data['bar_code']);

    $ci =& get_instance();
    $admin = $ci->session->userdata('LOGGED_IN_ADMIN_SESSION_KEY');
    if (empty($admin)) {
      $admin['id'] = 1;
    }

    $exists = ManagerHolder::get($this->entityName)->existsWhere($where);
    if ($exists) {
      if (!$insertOnlyMode) {
        ManagerHolder::get($this->entityName)->updateAllWhere($where, array('qty' => $data['qty'], 'update_source' => 'edit', 'update_by_admin_id' => $admin['id'], 'updated_at' => date(DOCTRINE_DATE_FORMAT)));
      }
    } else {
      $entity = array();
      $entity['bar_code'] = $data['bar_code'];

      $productGroup = ManagerHolder::get('ParameterGroup')->getOneWhere(array('bar_code' => $data['bar_code']), 'id,product_id');
      if ($productGroup) {
        $entity['product_group_id'] = $productGroup['id'];
        $entity['product_id'] = $productGroup['product_id'];
      } else {
        $product = ManagerHolder::get('Product')->getOneWhere(array('bar_code' => $data['bar_code']), 'id');
        if ($product) {
          $entity['product_id'] = $product['id'];
        } else {
          return;
        }
      }

      // Check if StoreInventory already exists with product_id and product_group_id in db in order to avoid duplicates
      $inventoryExistsWhere = $entity;
      $inventoryExistsWhere['store_id'] = $storeID;
      unset($inventoryExistsWhere['bar_code']);
      $inventoryFromDB = ManagerHolder::get($this->entityName)->getOneWhere($inventoryExistsWhere, 'id');

      $entity['store_id'] = $storeID;
      $entity['qty'] = $data['qty'];
      $entity['update_by_admin_id'] = $admin['id'];
      $entity['update_source'] = 'edit';
      $entity['updated_at'] = date(DOCTRINE_DATE_FORMAT);
      if ($inventoryFromDB) {
        $entity['id'] = $inventoryFromDB['id'];
        ManagerHolder::get($this->entityName)->update($entity);
      } else {
        ManagerHolder::get($this->entityName)->insert($entity);
      }
    }
  }

  /**
   * createDefault
   * @param string $id
   * @param $entityType
   */
  public function createDefault($id, $entityType) {
    $entity = ManagerHolder::get($entityType)->getById($id, 'bar_code, parameter_groups.*, inventories.*');
    if (empty($entity['bar_code']) || ($entityType == 'Product' && !empty($entity['parameter_groups']))) {
      return;
    }
    $inventoryExistsByCode = ManagerHolder::get($this->entityName)->existsWhere(array('bar_code' => $entity['bar_code']));
    if ($inventoryExistsByCode) {
      return;
    }
    // If barcode changed - change it for all existant inventories
    if (!empty($entity['inventories'])) {
      foreach ($entity['inventories'] as $i) {
        ManagerHolder::get($this->entityName)->updateById($i['id'], 'bar_code', $entity['bar_code']);
      }
      return;
    }
    $data = array('bar_code' => $entity['bar_code'], 'qty' => 0);
    ManagerHolder::get($this->entityName)->saveInventory($data, ZAMMLER_STORE_ID, TRUE);
  }

  /**
   * Export inventory to crm
   * @param $store
   */
  public function exportInventoryToCrm($store) {
//    $this->load->library('RetailCrmApi');
//    $offers = array();
//    $inventories = $this->getAllWhere(array('store_id' => $store['id']), 'e.*');
//
//    foreach ($result as $k => $v) {
//      $offer = array();
//      $offer['externalId'] = $k;
//      $offer['stores'][] = array(
//        'code' => $store['code'],
//        'available' => $v
//      );
//
//      $offers[] = $offer;
//      unset($result[$k]);
//      if (count($offers) == 250 || count($result) == 0) {
//        $response = $this->retailcrmapi->storeInventoriesUpload($offers);
//        $offers = array();
//        sleep(1);
//      }
//    }
  }

}