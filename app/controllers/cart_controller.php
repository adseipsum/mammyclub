<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Cart controller.
 * @property DbCart $dbcart
 * @author Itirra - http://itirra.com
 */
class Cart_Controller extends Base_Project_Controller {

  /** Libraries to load.*/
  protected $libraries = array('common/DoctrineLoader', 'Session', 'Auth', 'DbCart');

  /**
   * Constructor.
   */
  public function Cart_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setLayout('index');
    $this->layout->setModule('main');
  }

  /**
   * Add item action.
   */
  public function add($productId) {
    $additionalParams = NULL;
    if (is_not_empty($_GET['param1'])) {
      $additionalParams = array($_GET['param1']);
      if (is_not_empty($_GET['param2'])) {
        $additionalParams[] = $_GET['param2'];
      }
      $additionalParams = serialize($additionalParams);
    }
    $product = ManagerHolder::get('Product')->getById($productId, 'e.*, sales.*, sale_rels.*, parameter_groups.*');
    if(empty($product)) {
      show_404();
    }

    // Counting new price by parameters if needed
    $newPrice = $product['price'];
    $parameterGroupId = NULL;
    if(is_not_empty($_GET['param1']) && !empty($product['parameter_groups'])) {
      foreach ($product['parameter_groups'] as $group) {
        if($_GET['param1'] == $group['main_parameter_value_id']) {
          $parameterGroupId = $group['id'];
          if(!empty($group['price'])) {
            $newPrice = $group['price'];
          }
          break;
        }
      }
    }

    // Get products and add sales if exist
    if ($this->isLoggedIn) {
      ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $product);
    } else {
      ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($product);
    }

    $cart = array(
      'product_id' => $product['id'],
      'price' => $newPrice,
      'additional_product_params' => $additionalParams,
      'parameter_group_id' => $parameterGroupId,
    );

    if (isset($product['sale']) && !empty($product['sale'])) {
      $cart['sale_id'] = $product['sale']['id'];
      $discountPrice = $product['price'];
      if(is_not_empty($_GET['param1']) && !empty($product['parameter_groups'])) {
        foreach ($product['parameter_groups'] as $group) {
          if($_GET['param1'] == $group['main_parameter_value_id']) {
            if(!empty($group['price'])) {
              $discountPrice = $group['price'];
            }
            break;
          }
        }
      }
      $cart['discount_price'] = $discountPrice;
    }

    $this->dbcart->add_item($cart);
    $this->layout->set('product', $product);

    $this->layout->setLayout('ajax');

    $this->layout->setModule('shop');
    $this->layout->view('parts/ajax_new_product_in_cart_pop_up');
  }

  /**
   * Remove one position of item.
   * Uses to reduce item's amount
   */
  public function remove($itemId) {
    $item = $this->dbcart->get_cart_item($itemId);
    if (!empty($item)) {
      if ($item['qty'] > 1) {
        $this->dbcart->update_item(array('id' => $itemId, 'qty' => $item['qty'] - 1));
      } else {
        $this->dbcart->remove_item(array('id' => $itemId));
      }
    }
    set_flash_notice('Товар удалён из корзины');
    redirect_to_referral();
  }

  /**
   * Remove item.
   */
  public function remove_item($itemId) {
    $item = $this->dbcart->get_cart_item($itemId);
    if (!empty($item)) {
      $this->dbcart->remove_item(array('id' => $itemId));
    }
    set_flash_notice('Товар удалён из корзины');
    redirect_to_referral();
  }

  /**
   * Ajax product recount
   */
  public function ajax_product_recount() {
    $qty = $_GET['qty'];
    $productId = $_GET['productId'];
    $this->dbcart->update_item(array('id' => $productId, 'qty' => $qty));

    if ($this->isLoggedIn) {
      ManagerHolder::get('Cart')->recountCartWithDiscount($_COOKIE['cart_id'], $this->authEntity);
    } else {
      ManagerHolder::get('Cart')->recountCartWithDiscount($_COOKIE['cart_id']);
    }

    $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $_COOKIE['cart_id']));

    foreach ($cartItems as &$cartItem) {

      // Get additional params that user seted
      if (!empty($cartItem['additional_product_params'])) {
        $cartItem['additional_product_params'] = unserialize($cartItem['additional_product_params']);
      }

      // Get possible parameters and values for product
      if(!empty($cartItem['product']['possible_parameters_id'])) {
        $cartItem['product']['possible_parameters'] = ManagerHolder::get('ParameterProduct')->getById($cartItem['product']['possible_parameters_id'], 'e.*, parameter_main.*, parameter_secondary.*, possible_parameter_values.*');
        // Get parameter groups of the product
        $cartItem['product']['parameter_groups'] = ManagerHolder::get('ParameterGroup')->getAllWhere(array('product_id' => $cartItem['product']['id'],'not_in_stock' => FALSE), 'e.*, main_parameter_value.*, secondary_parameter_values_out.*, image.*');
      }

      // Get products and add sales if exist
      if ($this->isLoggedIn) {
        ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $cartItem['product']);
      } else {
        ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($cartItem['product']);
      }
    }

	  // Get delivery type of cart total amount
    $cart = ManagerHolder::get('Cart')->getOneWhere(array('id' => $_COOKIE['cart_id'], 'siteorder_id' => null));

	  $delivery = ManagerHolder::get('Delivery')->getDeliveryOfOrderAmount($cart['total']);

    $this->layout->set('cart', $cart);
    $this->layout->set('cartItems', $cartItems);
    $this->layout->set('delivery', $delivery);
    $this->layout->setLayout('ajax');
    $this->layout->setModule('shop');
    $this->layout->view('parts/order_data');
  }

  /**
   * Ajax save params process
   */
  public function ajax_save_params() {
    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('cart_item_id', 'data'));
    if (!isset($_COOKIE['cart_id']) || empty($_COOKIE['cart_id']) ) {
      show_404();
    }

    $cartItem = ManagerHolder::get('CartItem')->getOneWhere(array('cart_id' => $_COOKIE['cart_id'], 'id' => $_POST['cart_item_id']), 'e.*, product.*');

    if (empty($cartItem)) {
      show_404();
    }

    // Recounting the cart if needed
    $returnData = array();
    $cartItem['product']['parameter_groups'] = ManagerHolder::get('ParameterGroup')->getAllWhere(array('product_id' => $cartItem['product']['id']), 'e.*, image.*');

    ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $cartItem['product']);

    if(!empty($cartItem['product']['parameter_groups']) && is_not_empty($_POST['data'][0])) {
      foreach ($cartItem['product']['parameter_groups'] as $group) {
        if($_POST['data'][0] == $group['main_parameter_value_id']) {
          $newPrice = $cartItem['product']['price'];
          $parameterGroupId = $group['id'];
          if(!empty($group['price'])) {
            $newPrice = $group['price'];
          }
          if($cartItem['price'] != $newPrice || $cartItem['parameter_group_id'] != $parameterGroupId) {
            $cart = array(
              'id' => $cartItem['id'],
              'parameter_group_id' => $parameterGroupId,
            );
            $this->dbcart->update_item($cart);
            $returnData['price'] = $newPrice;
            $returnData['item_total'] = $newPrice*$cartItem['qty'];
            $returnData['cart_total'] = $this->dbcart->get_cart_total();
          }
          break;
        }
      }

      if ($this->isLoggedIn) {
        ManagerHolder::get('Cart')->recountCartWithDiscount($_COOKIE['cart_id'], $this->authEntity);
      } else {
        ManagerHolder::get('Cart')->recountCartWithDiscount($_COOKIE['cart_id']);
      }
    }
    $returnData = array();
    $cartItem = ManagerHolder::get('CartItem')->getOneWhere(array('cart_id' => $_COOKIE['cart_id'], 'id' => $_POST['cart_item_id']), 'e.*, product.*');
    $cart = ManagerHolder::get('Cart')->getById($_COOKIE['cart_id'], 'e.*');
    $returnData['old_price'] = $cartItem['price'];
    if (isset($cartItem['discount_price']) && !empty($cartItem['discount_price'])) {
      $returnData['price'] = $cartItem['discount_price'];
    } else {
      $returnData['price'] = $cartItem['price'];
    }

    $returnData['item_total'] = $cartItem['item_total'];

    $delivery = ManagerHolder::get('Delivery')->getDeliveryOfOrderAmount($cart['total']);
    $returnData['cart_total'] = $cart['total'] + $delivery['price'];

    ManagerHolder::get('CartItem')->updateById($_POST['cart_item_id'], 'additional_product_params', serialize($_POST['data']));
    if(!empty($returnData)) {
      die(json_encode($returnData));
    }
  }

}