<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DbCart library
 * For storing carts in the database.
 * Works with 2 tables: "Cart" and "CartItem"
 *
 *
 *
 * Itirra - http://itirra.com
 */
class DbCart {
  
  /** Options */
  private $options = array('cart_entity_name' => 'Cart',
  												 'cookie_name' => 'cart_id',
  );

  /** CI object */
  private $CI;
  
  /** Cart */
  private $cart = array('items' => array());
  
  /**
   * Constructor.
   */
  public function DbCart() {
    $this->CI = &get_instance();
    $this->CI->load->helper('cookie');
    $this->_load_cart();
  }
  
  
  /**
   * ADD Item to cart
   *
   * @param array $itemArray.
   * Array must contain:
   * - product_id
   * - price
   * - qty
   */
  public function add_item($itemArray) {
    $prIds = get_array_vals_by_second_key($this->cart['items'], 'product_id');
    if (!in_array($itemArray['product_id'], $prIds)) {
      $this->cart['items'][] = $this->_count_cart_item_total($itemArray);
      $this->_save_cart();
    } else {
      $index = array_search($itemArray['product_id'], $prIds);
      $item = $this->cart['items'][$index];
      $item['qty'] = $item['qty'] + 1; 
      $this->update_item($item);
    }
  }
  
  /**
   * UPDATE item in cart
   *
   * @param array $itemArray
   * Array must contain:
   * - product_id
   * - price
   * - qty
   */
  public function update_item($itemArray) {
    foreach ($this->cart['items'] as $i => $item) {
      if ($item['product_id'] == $itemArray['product_id']) {
        $this->cart['items'][$i] = $this->_count_cart_item_total(array_merge($this->cart['items'][$i], $itemArray));
        break;
      }
    }
    $this->_save_cart();
  }
  
  /**
   * REMOVE item from cart
   *
   * @param array $itemArray
	 * Array must contain:
   * - product_id
   */
  public function remove_item($itemArray) {
    foreach ($this->cart['items'] as $i => $item) {
      if ($item['product_id'] == $itemArray['product_id']) {
        unset($this->cart['items'][$i]);
        break;
      }
    }
    $this->_save_cart();
  }
  
  /**
   * Get cart contents
   */
  public function get_contents() {
    return $this->cart;
  }
  
  /**
   * Get cart item
   * @param integer $productId
   * @return array
   */
  public function get_cart_product($productId) {
    $result = array();
    foreach ($this->cart['items'] as $i => $item) {
      if ($item['product_id'] == $productId) {
        return $this->cart['items'][$i];
      }
    }
    return $result;
  } 
  

  /**
   * Destroy the cart
   */
  public function destroy() {
    if (!isset($this->cart['id'])) {
      ManagerHolder::get($this->options['cart_entity_name'])->deleteById($this->cart['id']);
    }
    delete_cookie($this->options['cookie_name']);
  }
  
  //------------------------------------------------------------------------------------------------------------------------
  //------------------------------------------------- INTERNAL METHODS -----------------------------------------------------
  //------------------------------------------------------------------------------------------------------------------------
  
  /**
   * Load cart from DB
   */
  private function _load_cart() {
    $cart = array();
    $cookie = get_cookie($this->options['cookie_name']);
    if($cookie) {
      $cart = ManagerHolder::get($this->options['cart_entity_name'])->getOneWhere(array('id' => $cookie, 'siteorder_id' => null));
      if (empty($cart)) {
        // Delete cookie if no record for it exists
        delete_cookie($this->options['cookie_name']);
      }
    }
    if (!empty($cart)) {
      $this->cart = $cart;
    }
  }

  /**
   * Save cart to DB
   */
  private function _save_cart() {
    $this->_count_cart_total();
    if (!isset($this->cart['id'])) {
      $this->_create_cart();
    } else {
      $this->_update_cart();
    }
  }
  
  /**
   * Update cart in DB
   */
  private function _update_cart() {
    $this->cart['updated_date'] = date(DOCTRINE_DATE_FORMAT);
    ManagerHolder::get($this->options['cart_entity_name'])->update($this->cart);
    $this->_refresh_cart();
  }
  
  /**
   * Create cart in DB and set cookie
   */
  private function _create_cart() {
    $this->cart['created_date'] = date(DOCTRINE_DATE_FORMAT);
    $this->cart['id'] = ManagerHolder::get($this->options['cart_entity_name'])->insert($this->cart);
    set_cookie($this->options['cookie_name'], $this->cart['id'], 60*60*24*30*12); // 1 year
    $this->_refresh_cart();
  }

  /**
   * Refresh local cart array from DB
   */
  private function _refresh_cart() {
    if (isset($this->cart['id'])) {
      $this->cart = ManagerHolder::get($this->options['cart_entity_name'])->getOneWhere(array('id' => $this->cart['id'], 'siteorder_id' => null));
    }
  }
  
  /**
   * Count the total for cart
   */
  private function _count_cart_total() {
    $total = 0;
    foreach ($this->cart['items'] as $item) {
      if (isset($item['item_total'])) {
        $total += $item['item_total'];
      }
    }
    $this->cart['total'] = $total;
  }

  /**
   * Count the total for a cart item
   */
  private function _count_cart_item_total($itemArray) {
    if (!isset($itemArray['qty'])) {
      $itemArray['qty'] = 1;
    }
    $itemArray['item_total'] = round($itemArray['price'] * $itemArray['qty'], 2);
    return $itemArray;
  }

}