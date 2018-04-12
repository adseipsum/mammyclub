<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * SaleManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';

class SaleManager extends BaseManager
{

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
                         "starts_at" => array("type" => "datetime", "class" => "required"),
                         "ends_at" => array("type" => "datetime", "class" => "required"),
                         "discount" => array("type" => "input_integer", "class" => "required"),
                         "discount_type" => array("type" => "select", "options" => array("percent" => "Проценты", "cash" => "Гривны"), "class" => "required", "attrs" => array("maxlength" => 255)),
                         "for_all" => array("type" => "checkbox"),
                         "users" => array("type" => "multipleselect_chosen", "relation" => array("name_field" => "auth_info.email", "entity_name" => "User", "search" => TRUE)),
                         "pregnancyweeks" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "PregnancyWeek", "search" => TRUE)),
                         "products" => array("type" => "multipleselect_chosen", "relation" => array("entity_name" => "Product", "search" => TRUE), "class" => "required"));

  /** List params. */
  public $listParams = array("name", "starts_at", "ends_at", "discount", array("pregnancyweeks" => "name"), array("products" => "name"));

  /** Available sale */
  public $availableSales = array();

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*")
  {
    $query = parent::preProcessWhereQuery($query, $pref, $what);
    if (strpos($what, 'users.') !== FALSE || $what == '*') {
      $query->addSelect("auth_info.*")->leftJoin("users.auth_info auth_info");
    }
    return $query;
  }

  /**
   * Add available sale
   */
  public function addAvailableSaleToProducts($user, &$product) {
    if (isset($user['sales']) && !empty($user['sales'])) {
      foreach ($user['sales'] as $s) {
        if (in_array($product['id'], $s['product_rels'])) {
          $product['old_price'] = $product['price'];
          if ($s['discount_type'] == 'percent') {
            $product['price'] = round($product['price'] - $product['price'] / 100 * $s['discount']);
          } else {
            $product['price'] = round($product['price'] - $s['discount']);
          }
          $product['sale'] = $s;
          if (isset($product['parameter_groups']) && !empty($product['parameter_groups'])) {
            foreach ($product['parameter_groups'] as &$pg) {
              if (empty($pg['price'])) {
                $pg['price'] = $product['old_price'];
              }
              $pg['old_price'] = $pg['price'];
              if ($s['discount_type'] == 'percent') {
                $pg['price'] = round($pg['price'] - $pg['price'] / 100 * $s['discount']);
                $pg['sale'] = $s;
              } else {
                $pg['price'] = $pg['price'] - $s['discount'];
                $pg['sale'] = $s;
              }

            }
          }
          break;
        }
      }
    }
  }

  /**
   * Add Available For All Sale To Products
   * @param $product
   */
  public function addAvailableForAllSaleToProducts(&$product) {
    $saleWhere = array(
      'starts_at <=' => date(DOCTRINE_DATE_FORMAT),
      'ends_at >='   => date(DOCTRINE_DATE_FORMAT),
      'for_all' => TRUE
    );

    $sales = $this->availableSales;
    if(empty($sales)) {
      $sales = $this->getAllWhere($saleWhere, 'e.*, product_rels.*');
      $this->availableSales = $sales;
    }

    if (!empty($sales)) {
      foreach ($sales as $s) {
        $s['product_rels'] = get_array_vals_by_second_key($s['product_rels'], 'product_id');
        if (in_array($product['id'], $s['product_rels'])) {
          $product['old_price'] = $product['price'];
          if ($s['discount_type'] == 'percent') {
            $product['price'] = round($product['price'] - $product['price'] / 100 * $s['discount']);
          } else {
            $product['price'] = round($product['price'] - $s['discount']);
          }
          $product['sale'] = $s;
          if (isset($product['parameter_groups']) && !empty($product['parameter_groups'])) {
            foreach ($product['parameter_groups'] as &$pg) {
              if (empty($pg['price'])) {
                $pg['price'] = $product['old_price'];
              }
              $pg['old_price'] = $pg['price'];
              if ($s['discount_type'] == 'percent') {
                $pg['price'] = round($pg['price'] - $pg['price'] / 100 * $s['discount']);
                $pg['sale'] = $s;
              } else {
                $pg['price'] = round($pg['price'] - $s['discount']);
                $pg['sale'] = $s;
              }

            }
          }
          break;
        }
      }
    }

  }


  /**
   * Recount Cart with discount
   * !TODO THIS FUCKING FUNCTION WHRITTEN BY IVAN ISN'T WORKING AND ISN'T USED ON FRONT END
   */
  public function recountCartWithDiscount($userId, $cartId, $sId, $cartItem) {
    $user = ManagerHolder::get('User')->getById($userId, 'e.*');
    ManagerHolder::get('User')->addAvailableSalestoUser($user);

    $cartTotal = 0;
    // Set discount price if exists
    if (!empty($user['pregnancyweek_current_id'])) {
      ManagerHolder::get('Sale')->addAvailableSaleToProducts($user, $cartItem['product']);
      if (!empty($cartItem['product']['sale'])) {
        $discountPrice = round($cartItem['product']['price'] - $cartItem['product']['price'] / 100 * $cartItem['product']['sale']['discount']);
        $cartTotal += $cartItem['qty'] * $discountPrice;
        ManagerHolder::get('CartItem')->updateAllWhere(array('id' => $cartItem['id']),
          array('discount_price' => $discountPrice,
            'item_total' => $cartItem['qty'] * $discountPrice));
      } else {
        $cartTotal += $cartItem['qty'] * $cartItem['product']['price'];
        ManagerHolder::get('CartItem')->updateAllWhere(array('id' => $cartItem['id']),
          array('discount_price' => 0,
            'item_total' => $cartItem['qty'] * $cartItem['product']['price']));
      }
    } else {
      $cartTotal += $cartItem['qty'] * $cartItem['product']['price'];
      ManagerHolder::get('CartItem')->updateAllWhere(array('id' => $cartItem['id']),
        array('discount_price' => 0,
          'item_total' => $cartItem['qty'] * $cartItem['product']['price']));
    }

    ManagerHolder::get('Cart')->updateAllWhere(array('id' => $cartId), array('user_id' => $userId, 'siteorder_id' => $sId, 'total' => $cartTotal));
  }
}