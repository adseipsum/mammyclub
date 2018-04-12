<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "controllers/base_project_controller.php";

/**
 * Shop controller.
 * @author Itirra - http://itirra.com
 */
class Shop_Controller extends Base_Project_Controller {

  /** Cart contents. */
  protected $cart = array();


  /**
   * Constructor.
   */
  public function Shop_Controller() {
    parent::Base_Project_Controller();
    $this->layout->setLayout('shop');
    $this->layout->setModule('shop');
    $this->load->helper('common/itirra_date');
    $this->load->library('DbCart');
    $this->cart = $this->dbcart->get_contents();
    $this->layout->set('cart', $this->cart);

    // Check for redirect from product broadcast key
    $this->add_product_and_redirect_to_cart();
    $this->check_for_added_to_cart_product();
  }

  /**
   * Index page.
   */
  public function index() {

    $this->load->helper('common/itirra_pager');

    $this->setCategories();

    $products = ManagerHolder::get('Showcase')->getShowcaseProducts($this->authEntity);

    if ($this->isLoggedIn) {
      foreach ($products as &$product) {
        ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $product);
      }
    } else {
      foreach ($products as &$product) {
        ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($product);
      }
    }


    // Get cart items for cart block in sidebar
    if (isset($_COOKIE['cart_id']) && !empty($_COOKIE['cart_id'])) {
      $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $_COOKIE['cart_id']), 'e.*');
    }

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

    if (isset($cartItems)) {
      $this->layout->set('cartItems', $cartItems);
    }

    $comments = ManagerHolder::get('ShopComment')->getBySortorderWithPager(array('published' => TRUE), 1, COMMENTS_SHOP_PER_PAGE, 'e.*, user.*');

    $this->layout->set('comments', $comments->data);
    $this->layout->set('pager', $comments->pager);
    $this->layout->set('products', $products);
    $this->layout->view('index');
  }

  /**
   * Category page.
   */
  public function category() {
    $this->load->helper('common/itirra_pager');
    $page = pager_get_page_number();

    $pageUrl = surround_with_slashes(uri_string());
    if(ENV == 'TEST') {
      $pageUrl = str_replace('/shop', '', $pageUrl);
    }

    $pageUrl = pager_remove_from_str($pageUrl);
    $pageUrlArray = explode('/', $pageUrl);
    $lastUrlSegment = $pageUrlArray[count($pageUrlArray) - 2];

    $brandInUrl = ManagerHolder::get('ProductBrand')->getOneWhere(array('page_url' => '/' . $lastUrlSegment . '/'), 'e.id');
    if ($brandInUrl) {
      unset($pageUrlArray[count($pageUrlArray) - 2]);
      $pageUrl = implode('/', $pageUrlArray);
      $pageUrl = surround_with_slashes($pageUrl);

      if (isset($_GET['filters']['brand']) && !empty($_GET['filters']['brand'])) {
        foreach ($_GET['filters']['brand'] as $k => $v) {
          if ('b' . $brandInUrl['id'] == $v) {
            unset($_GET['filters']['brand'][$k]);
            $getParams = get_get_params();
            if ($getParams == '?') {
              $getParams = '';
            }
            redirect(shop_url($pageUrl) . $getParams);
          }
        }
      }

      $_GET['filters']['brand'][] = 'b' . $brandInUrl['id'];
      if (count($_GET['filters']['brand']) > 1) {
        $getParams = get_get_params();
        if ($getParams == '?') {
          $getParams = '';
        }
        redirect(shop_url($pageUrl) . $getParams);
      }
    }


    $currentCategory = ManagerHolder::get('ProductCategory')->getOneWhere(array('page_url' => $pageUrl), 'e.*, header.*, filters.*');
    if (empty($currentCategory) || (!$currentCategory['published'] && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY'])))) {
      show_404();
    }

    if (isset($_GET['filters']['brand']) && !empty($_GET['filters']['brand'])) {
      if (count($_GET['filters']['brand']) == 1 && !$brandInUrl) {
        $brandId = str_replace('b', '', reset($_GET['filters']['brand']));
        $categoryBrand = ManagerHolder::get('ProductCategoryProductBrand')->getOneWhere(array('product_category_id' => $currentCategory['id'], 'product_brand_id' => $brandId), 'product_brand.page_url');
        if ($categoryBrand) {
          unset($_GET['filters']['brand']);
          $getParams = get_get_params();
          if ($getParams == '?') {
            $getParams = '';
          }
          redirect(shop_url($pageUrl) . '/' . trim($categoryBrand['product_brand']['page_url'], '/') . $getParams);
        }
      }
    }

    if ($brandInUrl) {
      $categoryBrand = ManagerHolder::get('ProductCategoryProductBrand')->getOneWhere(array('product_category_id' => $currentCategory['id'], 'product_brand_id' => $brandInUrl['id']), 'e.*');
      if ($categoryBrand) {
        if (!empty($categoryBrand['header_title'])) {
          $currentCategory['header']['title'] = $categoryBrand['header_title'];
        }
        if (!empty($categoryBrand['header_description'])) {
          $currentCategory['header']['description'] = $categoryBrand['header_description'];
        }
        if (!empty($categoryBrand['title'])) {
          $currentCategory['name'] = $categoryBrand['title'];
        }
        if (!empty($categoryBrand['description'])) {
          $currentCategory['description'] = $categoryBrand['description'];
        }
      }
    }

    // For breadcrumb
    if ($currentCategory['level'] > 0) {
      if ($currentCategory['level'] > 1) {
        $prevCategoryUrl2 = surround_with_slashes(dirname($currentCategory['page_url']));
        $prevCategory2 = ManagerHolder::get('ProductCategory')->getOneWhere(array('page_url' => $prevCategoryUrl2), 'e.*');

        $prevCategoryUrl1 = surround_with_slashes(dirname($prevCategoryUrl2));
        $prevCategory1 = ManagerHolder::get('ProductCategory')->getOneWhere(array('page_url' => $prevCategoryUrl1), 'e.*');
      } else {
        $prevCategoryUrl2 = surround_with_slashes(dirname($currentCategory['page_url']));
        $prevCategory2 = ManagerHolder::get('ProductCategory')->getOneWhere(array('page_url' => $prevCategoryUrl2), 'e.*');

        $prevCategory1 = '';
      }
    } else {
      $prevCategory2 = '';
      $prevCategory1 = '';
    }

    $this->setCategories();

    // Get cart items for cart block in sidebar
    if (isset($_COOKIE['cart_id']) && !empty($_COOKIE['cart_id'])) {
      $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $_COOKIE['cart_id']));
    }

    // Get products and add sales if exist
    $baseWhere = array('published'    => TRUE,
      'not_in_stock' => FALSE,
      'category_id'  => $this->getChildCategoryIds($currentCategory['id']));
    $where = $this->getFiltersWhere($baseWhere);

    ManagerHolder::get('Product')->setOrderBy('sortorder ASC');

    if(isset($where['filter_values.id']) && !empty($where['filter_values.id'])) {

      // Get all products without filtering on filter_values
      $tempWhere = $where;
      unset($tempWhere['filter_values.id']);

      $products = ManagerHolder::get('Product')->getAllWhere($tempWhere, 'e.*, filter_values.*');
      foreach ($products as $k => $p) {
        if(empty($p['filter_values']) && empty($p['brand_id'])) {
          unset($products[$k]);
          continue;
        }
        $productFVIds = get_array_vals_by_second_key($p['filter_values'], 'id');

        foreach ($where['filter_values.id'] as $Fid => $FVGroup) {
          $hasMatches = FALSE;
          if ($Fid == 'brand') {
            if (in_array('b' . $p['brand_id'], $FVGroup)) {
              $hasMatches = TRUE;
            }
          } else {
            foreach ($FVGroup as $FVId) {
              if(in_array($FVId, $productFVIds)) {
                $hasMatches = TRUE;
              }
            }
          }

          if($hasMatches == FALSE) {
            unset($products[$k]);
            continue 2;
          }
        }
      }

      if(!empty($products)) {
        $filteredProductIds = get_array_vals_by_second_key($products, 'id');
        if(isset($_GET['show_all']) && !empty($_GET['show_all'])) {
          $products = new stdClass();
          $products->data = ManagerHolder::get('Product')->getAllWhere(array('id' => $filteredProductIds), 'e.*, image.*, filter_values.*, category.*, brand.*');
        } else {
          $products = ManagerHolder::get('Product')->getAllWhereWithPager(array('id' => $filteredProductIds), $page, 30, 'e.*, image.*, filter_values.*, category.*, brand.*');
          $this->layout->set('pager', $products->pager);
        }
      } else {
        $products = new stdClass();
        $products->data = array();
      }

    } else {
      if(isset($_GET['show_all']) && !empty($_GET['show_all'])) {
        $products = new stdClass();
        $products->data = ManagerHolder::get('Product')->getAllWhere($where, 'e.*, image.*, filter_values.*, category.*, brand.*');
      } else {
        $products = ManagerHolder::get('Product')->getAllWhereWithPager($where, $page, 30, 'e.*, image.*, filter_values.*, category.*, brand.*');
        $this->layout->set('pager', $products->pager);
      }
    }

    $products = $products->data;

    if ($this->isLoggedIn) {
      foreach ($products as &$product) {
        ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $product);
      }
    } else {
      foreach ($products as &$product) {
        ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($product);
      }
    }

    $this->setHeaders($currentCategory);

    // Get filter data
    $result = ManagerHolder::get('Product')->getFilterData($baseWhere, $where, $currentCategory['filters']);
    $this->layout->set('filters', $result['filters']);

    if (isset($cartItems)) {
      $this->layout->set('cartItems', $cartItems);
    }

    $this->layout->set('products', $products);

    $this->layout->set('pageUrl', $pageUrl);
    $this->layout->set('currentCategory', $currentCategory);
    $this->layout->set('prevCategory1', $prevCategory1);
    $this->layout->set('prevCategory2', $prevCategory2);
    $this->layout->view('category');
  }

  /**
   * Category page.
   */
  public function sale_category() {
    $this->load->helper('common/itirra_pager');
    $page = pager_get_page_number();

    $_GET['show_all'] = 1;

    $pageUrl = surround_with_slashes(uri_string());
    if(ENV == 'TEST') {
      $pageUrl = str_replace('/shop', '', $pageUrl);
    }

    $pageUrl = pager_remove_from_str($pageUrl);
    $pageUrlArray = explode('/', $pageUrl);
    $lastUrlSegment = $pageUrlArray[count($pageUrlArray) - 2];

    $brandInUrl = FALSE;
//    $brandInUrl = ManagerHolder::get('ProductBrand')->getOneWhere(array('page_url' => '/' . $lastUrlSegment . '/'), 'e.id');
//    if ($brandInUrl) {
//      unset($pageUrlArray[count($pageUrlArray) - 2]);
//      $pageUrl = implode('/', $pageUrlArray);
//      $pageUrl = surround_with_slashes($pageUrl);
//
//      if (isset($_GET['filters']['brand']) && !empty($_GET['filters']['brand'])) {
//        foreach ($_GET['filters']['brand'] as $k => $v) {
//          if ('b' . $brandInUrl['id'] == $v) {
//            unset($_GET['filters']['brand'][$k]);
//            $getParams = get_get_params();
//            if ($getParams == '?') {
//              $getParams = '';
//            }
//            redirect(shop_url($pageUrl) . $getParams);
//          }
//        }
//      }
//
//      $_GET['filters']['brand'][] = 'b' . $brandInUrl['id'];
//      if (count($_GET['filters']['brand']) > 1) {
//        $getParams = get_get_params();
//        if ($getParams == '?') {
//          $getParams = '';
//        }
//        redirect(shop_url($pageUrl) . $getParams);
//      }
//    }

    $currentCategory = ManagerHolder::get('ProductSaleCategory')->getOneWhere(array('page_url' => $pageUrl), 'e.*, header.*, filters.*, categories.*');
    if (empty($currentCategory) || (!$currentCategory['published'] && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY'])))) {
      show_404();
    }

    if (isset($_GET['filters']['brand']) && !empty($_GET['filters']['brand'])) {
      if (count($_GET['filters']['brand']) == 1 && !$brandInUrl) {
        $brandId = str_replace('b', '', reset($_GET['filters']['brand']));
        $categoryBrand = ManagerHolder::get('ProductCategoryProductBrand')->getOneWhere(array('product_category_id' => $currentCategory['id'], 'product_brand_id' => $brandId), 'product_brand.page_url');
        if ($categoryBrand) {
          unset($_GET['filters']['brand']);
          $getParams = get_get_params();
          if ($getParams == '?') {
            $getParams = '';
          }
          redirect(shop_url($pageUrl) . '/' . trim($categoryBrand['product_brand']['page_url'], '/') . $getParams);
        }
      }
    }

    if ($brandInUrl) {
      $categoryBrand = ManagerHolder::get('ProductCategoryProductBrand')->getOneWhere(array('product_category_id' => $currentCategory['id'], 'product_brand_id' => $brandInUrl['id']), 'e.*');
      if ($categoryBrand) {
        if (!empty($categoryBrand['header_title'])) {
          $currentCategory['header']['title'] = $categoryBrand['header_title'];
        }
        if (!empty($categoryBrand['header_description'])) {
          $currentCategory['header']['description'] = $categoryBrand['header_description'];
        }
        if (!empty($categoryBrand['title'])) {
          $currentCategory['name'] = $categoryBrand['title'];
        }
        if (!empty($categoryBrand['description'])) {
          $currentCategory['description'] = $categoryBrand['description'];
        }
      }
    }

    // For breadcrumb
    if ($currentCategory['level'] > 0) {
      if ($currentCategory['level'] > 1) {
        $prevCategoryUrl2 = surround_with_slashes(dirname($currentCategory['page_url']));
        $prevCategory2 = ManagerHolder::get('ProductSaleCategory')->getOneWhere(array('page_url' => $prevCategoryUrl2), 'e.*');

        $prevCategoryUrl1 = surround_with_slashes(dirname($prevCategoryUrl2));
        $prevCategory1 = ManagerHolder::get('ProductSaleCategory')->getOneWhere(array('page_url' => $prevCategoryUrl1), 'e.*');
      } else {
        $prevCategoryUrl2 = surround_with_slashes(dirname($currentCategory['page_url']));
        $prevCategory2 = ManagerHolder::get('ProductSaleCategory')->getOneWhere(array('page_url' => $prevCategoryUrl2), 'e.*');

        $prevCategory1 = '';
      }
    } else {
      $prevCategory2 = '';
      $prevCategory1 = '';
    }

    $this->setCategories();

    // Get cart items for cart block in sidebar
    if (isset($_COOKIE['cart_id']) && !empty($_COOKIE['cart_id'])) {
      $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $_COOKIE['cart_id']));
    }

    // Get products and add sales if exist
    $baseWhere = array('published'    => TRUE,
                       'not_in_stock' => FALSE,
                       'category_id' => array());

    if (!empty($currentCategory['categories'])) {
      foreach ($currentCategory['categories'] as $saleCat) {
        $categoriesWhere['category_id'] = $this->getChildCategoryIds($saleCat['id']);
        foreach ($categoriesWhere as $catWhere) {
          $baseWhere['category_id'] = array_merge($catWhere, $baseWhere['category_id']);
        }
      }
    } else {
      $baseWhere['category_id'] = NULL;
    }

    $childCategoriesIds = $this->getChildSaleCategoryIds($currentCategory['id']);
    $childCategories = ManagerHolder::get('ProductSaleCategory')->getAllWhere(array('id' => $childCategoriesIds), 'e.*, categories.*');
    foreach ($childCategories as $childCategory) {
      if (!empty($childCategory['__children'])) {
        $children = get_array_vals_by_second_key($childCategory['__children'], 'categories');
        foreach ($children as $v) {
          $currentCategory['categories'] = array_merge($currentCategory['categories'], $v);
        }
        foreach ($childCategory['__children'] as $ccc) {
          if (!empty($ccc['__children'])) {
            $children = get_array_vals_by_second_key($ccc['__children'], 'categories');
            foreach ($children as $v) {
              $currentCategory['categories'] = array_merge($currentCategory['categories'], $v);
            }
          }
        }
      }
    }
    $baseWhere['category_id'] = get_array_vals_by_second_key($currentCategory['categories'], 'id');

    $where = $this->getFiltersWhere($baseWhere);

    ManagerHolder::get('Product')->setOrderBy('sortorder ASC');

    if(isset($where['filter_values.id']) && !empty($where['filter_values.id'])) {

      // Get all products without filtering on filter_values
      $tempWhere = $where;
      unset($tempWhere['filter_values.id']);

      $products = ManagerHolder::get('Product')->getAllWhere($tempWhere, 'e.*, filter_values.*');
      foreach ($products as $k => $p) {
        if(empty($p['filter_values']) && empty($p['brand_id'])) {
          unset($products[$k]);
          continue;
        }
        $productFVIds = get_array_vals_by_second_key($p['filter_values'], 'id');

        foreach ($where['filter_values.id'] as $Fid => $FVGroup) {
          $hasMatches = FALSE;
          if ($Fid == 'brand') {
            if (in_array('b' . $p['brand_id'], $FVGroup)) {
              $hasMatches = TRUE;
            }
          } else {
            foreach ($FVGroup as $FVId) {
              if(in_array($FVId, $productFVIds)) {
                $hasMatches = TRUE;
              }
            }
          }

          if($hasMatches == FALSE) {
            unset($products[$k]);
            continue 2;
          }
        }
      }

      if(!empty($products)) {
        $filteredProductIds = get_array_vals_by_second_key($products, 'id');
        if(isset($_GET['show_all']) && !empty($_GET['show_all'])) {
          $products = new stdClass();
          $products->data = ManagerHolder::get('Product')->getAllWhere(array('id' => $filteredProductIds), 'e.*, image.*, filter_values.*');
        } else {
          $products = ManagerHolder::get('Product')->getAllWhereWithPager(array('id' => $filteredProductIds), $page, 30, 'e.*, image.*, filter_values.*');
          $this->layout->set('pager', $products->pager);
        }
      } else {
        $products = new stdClass();
        $products->data = array();
      }

    } else {
      if(isset($_GET['show_all']) && !empty($_GET['show_all'])) {
        $products = new stdClass();
        $products->data = ManagerHolder::get('Product')->getAllWhere($where, 'e.*, image.*, filter_values.*');
      } else {
        $products = ManagerHolder::get('Product')->getAllWhereWithPager($where, $page, 30, 'e.*, image.*, filter_values.*');
        $this->layout->set('pager', $products->pager);
      }
    }

    $products = $products->data;

    if ($this->isLoggedIn) {
      foreach ($products as &$product) {
        ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $product);
      }
    } else {
      foreach ($products as &$product) {
        ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($product);
      }
    }

    $this->setHeaders($currentCategory);

    // Get filter data
//    $result = ManagerHolder::get('Product')->getFilterData($baseWhere, $where, $currentCategory['filters']);
    $this->layout->set('filters', array());

    if (isset($cartItems)) {
      $this->layout->set('cartItems', $cartItems);
    }

    $productsWithDiscount = array();
    foreach ($products as $pwd) {
      if (!empty($pwd['sale'])) {
        $productsWithDiscount[] = $pwd;
        unset($pwd);
      }
    }
    $this->layout->set('products', $productsWithDiscount);

    $this->layout->set('pageUrl', $pageUrl);
    $this->layout->set('currentCategory', $currentCategory);
    $this->layout->set('prevCategory1', $prevCategory1);
    $this->layout->set('prevCategory2', $prevCategory2);
    $this->layout->view('category');
  }

  /**
   * getFiltersWhere
   * return @param array
   */
  private function getFiltersWhere($where = array()) {
    if(!empty($_GET['filters'])) {
      $where['filter_values.id'] = $_GET['filters'];
    }
    return $where;
  }

  /**
   * getChildCategoryIds
   * @param int $cId
   * @return array
   */
  private function getChildCategoryIds($cId) {
    $result = array();
    $categories = ManagerHolder::get('ProductCategory')->getDescendants($cId, null, true, Doctrine_Core::HYDRATE_ARRAY);
    if(!empty($categories)) {
      $result = get_array_vals_by_second_key($categories, 'id');
    }
    return $result;
  }

  /**
   * getChildSaleCategoryIds
   * @param int $cId
   * @return array
   */
  private function getChildSaleCategoryIds($cId) {
    $result = array();
    $categories = ManagerHolder::get('ProductSaleCategory')->getDescendants($cId, null, true, Doctrine_Core::HYDRATE_ARRAY);
    if(!empty($categories)) {
      $result = get_array_vals_by_second_key($categories, 'id');
    }
    return $result;
  }

  /**
   * Product page.
   */
  public function product() {

    $pageUrl = surround_with_slashes(uri_string());
    if(ENV == 'TEST') {
      $pageUrl = str_replace('/shop', '', $pageUrl);
    }
    $product = ManagerHolder::get('Product')->getOneWhere(array('page_url' => $pageUrl), 'e.*, category.*, image.*, images.*, brand.*, header.*, parameter_link.*, parameter_value_link.*, inventories.*, reserves.*');
    if(empty($product)) {
      show_404();
    }

    $product['possible_parameters']     = ManagerHolder::get('ParameterProduct')->getById($product['possible_parameters_id'], 'e.*, parameter_main.*, parameter_secondary.*, possible_parameter_values.*');
    $product['parameter_groups']        = ManagerHolder::get('ParameterGroup')->getAllWhere(array('product_id' => $product['id']), 'e.*, main_parameter_value.*, secondary_parameter_values_out.*, image.*, reserves.*');
    $product['parameter_product_links'] = ManagerHolder::get('ParameterProductLink')->getAllWhere(array('main_product_id' => $product['id']), 'e.*, parameter_value.*, linked_product.*');

	  // Process store count in product and it's groups
	  ManagerHolder::get('Product')->addStoreCount($product, $product['inventories']);
	  if (!empty($product['parameter_groups'])) {
		  foreach ($product['parameter_groups'] as &$pg) {
			  ManagerHolder::get('Product')->addStoreCount($pg, $product['inventories']);
		  }
	  }
    $this->setCategories();


    $currentCategory = ManagerHolder::get('ProductCategory')->getOneWhere(array('id' => $product['category_id']), 'e.*');
    if ($currentCategory['published'] == FALSE && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']))) {
      show_404();
    }


    // For breadcrumb
    if ($currentCategory['level'] > 0) {
      $prevCategoryUrl = surround_with_slashes(dirname($currentCategory['page_url']));
      $prevCategory = ManagerHolder::get('ProductCategory')->getOneWhere(array('page_url' => $prevCategoryUrl), 'e.*');
    } else {
      $prevCategory = '';
    }


    if ($product['published'] == FALSE && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']))) {
      show_404();
    }

    if (empty($product)) {
      show_404();
    }

    // Get cart items for cart block in sidebar
    if (isset($_COOKIE['cart_id']) && !empty($_COOKIE['cart_id'])) {
      $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $_COOKIE['cart_id']));
      $cartItemsAmount = ManagerHolder::get('Cart')->get_cart_item_amount($cartItems);
    }

    // Get products and add sales if exist
    if ($this->isLoggedIn) {
      ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $product);
    } else {
      ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($product);
    }

    $colors = ManagerHolder::get('ProductColor')->getAllWhere(array('product_id' => $product['id']), 'e.*, image.*');


	  $where = array('entity_id' => $product['id'], 'published' => TRUE);
	  $comments = ManagerHolder::get('ProductComment')->getAllWhere($where, 'e.*, user.*');

//	  $comments = ManagerHolder::get('ProductComment')->getBySortorderWithPager($where, 1, 100, 'e.*, user.*');
//		$comments = $comments->data;

	  $status = ManagerHolder::get('User')->fields['status']['options'];

    process_brand_text($product);

    if(!empty($product['head_section_code'])) {
      $this->layout->set('head_section_code', $product['head_section_code']);
    }
    $this->setHeaders($product);

    if (isset($cartItems)) {
      $this->layout->set('cartItems', $cartItems);
      $this->layout->set('cartItemsAmount', $cartItemsAmount);
    }


    $this->layout->set('comments', $comments);
    $this->layout->set('status', $status);
    $this->layout->set('allowToAddCommentForNotLoggedIn', TRUE);
    $this->layout->set('currentCategory', $currentCategory);
    $this->layout->set('prevCategory', $prevCategory);
    $this->layout->set('colors', $colors);
    $this->layout->set('product', $product);
    $this->layout->view('product');

  }

  /**
   * Checkout page
   */
  public function checkout() {
    $this->setCategories();

    if (!isset($_COOKIE['cart_id']) || empty($_COOKIE['cart_id'])) {
      $sId = $this->session->userdata('SITE_ORDER_ID');
      if (!empty($sId)) {
        redirect(shop_url('спасибо-за-заказ'));
      }

      show_404();
    }

    $warehouses = array();
    if ($this->isLoggedIn) {
      $q = "SELECT * FROM site_order WHERE user_id = '" . $this->authEntity['id']. "' ORDER BY created_at DESC LIMIT 1";
      $pastSiteOrder = ManagerHolder::get('SiteOrder')->executeNativeSQL($q);
      if (!empty($pastSiteOrder)) {
        if (isset($pastSiteOrder[0]['delivery_city_id']) && !empty($pastSiteOrder[0]['delivery_city_id'])) {
          $warehouses = ManagerHolder::get('Warehouse')->getAsViewArray(array(), 'name', null, array('city_id' => $pastSiteOrder[0]['delivery_city_id']));
        }

        $this->layout->set("pastSiteOrder", $pastSiteOrder[0]);
      }
    }
    $this->layout->set("warehouses", $warehouses);

    if ($this->isLoggedIn) {
      ManagerHolder::get('Cart')->recountCartWithDiscount($_COOKIE['cart_id'], $this->authEntity);
    } else {
      ManagerHolder::get('Cart')->recountCartWithDiscount($_COOKIE['cart_id']);
    }

    $cart = ManagerHolder::get('Cart')->getById($_COOKIE['cart_id'], 'e.*');
    $cartItems = ManagerHolder::get('CartItem')->getAllWhere(array('cart_id' => $_COOKIE['cart_id']), 'e.*, product.*, sale.*');

    foreach ($cartItems as &$cartItem) {
      // Get additional params that user setted
      if (!empty($cartItem['additional_product_params'])) {
        $cartItem['additional_product_params'] = unserialize($cartItem['additional_product_params']);
      }

      // Get possible parameters and values for product
      if(!empty($cartItem['product']['possible_parameters_id'])) {
        $cartItem['product']['possible_parameters'] = ManagerHolder::get('ParameterProduct')->getById($cartItem['product']['possible_parameters_id'], 'e.*, parameter_main.*, parameter_secondary.*, possible_parameter_values.*');
        // Get parameter groups of the product
        $cartItem['product']['parameter_groups'] = ManagerHolder::get('ParameterGroup')->getAllWhere(array('product_id' => $cartItem['product']['id'],'not_in_stock' => FALSE), 'e.*, main_parameter_value.*, secondary_parameter_values_out.*, image.*');

        if(!empty($cartItem['product']['parameter_groups'])) {
          foreach ($cartItem['product']['parameter_groups'] as &$group) {

            $cartItem['product']['possible_parameters']['possible_parameter_values'] = array_sort($cartItem['product']['possible_parameters']['possible_parameter_values'], 'priority');
            $secondaryParamValueIds = get_array_vals_by_second_key($group['secondary_parameter_values_out'], 'id');

            $group['secondary_parameter_values'] = array();
            foreach ($cartItem['product']['possible_parameters']['possible_parameter_values'] as $possibleParamValue) {
              if($cartItem['product']['possible_parameters']['parameter_secondary_id'] == $possibleParamValue['parameter_id']) {
//                $possibleParamValue['on_order'] = FALSE;
//                if(in_array($possibleParamValue['id'], $secondaryParamValueIds) || $group['on_order'] == TRUE) {
//                  $possibleParamValue['on_order'] = TRUE;
//                }
                $group['secondary_parameter_values'][] = $possibleParamValue;
              }
            }
          }
        }
      }

      // Get products and add sales if exist
      if ($this->isLoggedIn) {
        ManagerHolder::get('Sale')->addAvailableSaleToProducts($this->authEntity, $cartItem['product']);
      } else {
        ManagerHolder::get('Sale')->addAvailableForAllSaleToProducts($cartItem['product']);
      }
    }

    unset($cartItem);

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    $this->setHeaders($page);

	  // Get delivery type of cart total amount
	  $delivery = ManagerHolder::get('Delivery')->getDeliveryOfOrderAmount($cart['total']);
	  $this->layout->set('delivery', $delivery);

    $cities = ManagerHolder::get('City')->getAsViewArray();
    $this->layout->set('cityOptions', $cities); //get_np_cities_view_array());

    $cart = ManagerHolder::get('Cart')->getOneWhere(array('id' => $_COOKIE['cart_id'], 'siteorder_id' => null));

    $this->layout->set('cart', $cart);
    $this->layout->set('cartItems', $cartItems);
    $this->layout->setLayout('shop_full_width');
    $this->layout->view('checkout');
  }

  /**
   * Checkout action
   */
  public function checkout_process() {
    if(empty($this->cart) || !isset($this->cart['id'])) {
      $sId = $this->session->userdata('SITE_ORDER_ID');
      if (!empty($sId)) {
        redirect(shop_url('спасибо-за-заказ'));
      }

      show_404();
    }

    $this->load->helper('common/itirra_validation');
    save_post();
    simple_validate_post(array('first_name', 'last_name', 'phone', 'email', 'city', 'street', 'house', 'flat', 'post', 'comment', 'delivery_type', 'payment_type', 'not_bot'));
    $city = ManagerHolder::get('City')->getById($_POST['city'], 'e.*');

    $checkoutInfo = array('first_name' => $_POST['first_name'],
                          'last_name' => $_POST['last_name'],
                          'fio' => $_POST['last_name'] . ' ' . $_POST['first_name'],
                          'code' => '',
                          'phone' => process_phone_number($_POST['phone'], $this->country),
                          'email' => $_POST['email'],
                          'delivery_city_id' => $city['id'],
                          'delivery_city_ref' => $city['ref'],
                          'payment_type' => $_POST['payment_type'],
                          'delivery_type' => $_POST['delivery_type'],
                          'comment' => $_POST['comment'],
                          'status' => SITEORDER_STATUS_NEW,
                          'siteorder_status_id' => ManagerHolder::get('SiteOrderStatus')->getIDByKey(SITEORDER_STATUS_NEW),
                          'total' => $this->cart['total'],
                          'total_with_discount' => $this->cart['total'],
                          'total_on_create' => $this->cart['total']);

	  // Check delivery price of total order amount
	  $delivery = ManagerHolder::get('Delivery')->getDeliveryOfOrderAmount($this->cart['total']);
    if (!empty($delivery['price'])){
	    $checkoutInfo['delivery_id'] = $delivery['id'];
	    $checkoutInfo['delivery_price'] = $delivery['price'];
	    $checkoutInfo['total_with_discount'] += $delivery['price'];
	    $checkoutInfo['total_on_create'] += $delivery['price'];
    }

    if ($_POST['delivery_type'] == 'delivery-to-post') {
      $warehouse = ManagerHolder::get('Warehouse')->getById($_POST['post'], 'e.*');
      if (isset($warehouse['name'])) {
        $checkoutInfo['delivery_post'] = $warehouse['name'];
      }
      if (isset($warehouse['ref'])) {
        $checkoutInfo['delivery_post_ref'] = $warehouse['ref'];
        $checkoutInfo['delivery_warehouse_id'] = $warehouse['id'];
      }
    } elseif ($_POST['delivery_type'] == 'delivery-to-home') {
      $checkoutInfo['delivery_street'] = $_POST['street'];
      $checkoutInfo['delivery_street_ref'] = $_POST['street_ref'];
      $checkoutInfo['delivery_street_type'] = $_POST['street_type'];
      $checkoutInfo['delivery_house'] = $_POST['house'];
      $checkoutInfo['delivery_flat'] = $_POST['flat'];
    }

    if ($this->isLoggedIn) {

      $checkoutInfo['user_id'] = $this->authEntity['id'];

    } else {

      $user = ManagerHolder::get('User')->getOneWhere(array('auth_info.email' => $_POST['email']), 'e.*, auth_info.*');

      if(!empty($user)) {

        $checkoutInfo['user_id'] = $user['id'];
        ManagerHolder::get('Cart')->updateById($this->cart['id'], 'user_id', $user['id']);

        // Login entity
        $this->auth->login($user, FALSE);

      } else {

        $checkoutInfo['user_id'] = NULL;

        // Let's register this user
        $regiserData = array('email'    => $checkoutInfo['email'],
                             'password' => $this->generatePassword());

         // Make new entity
        $entity = array('newsletter_questions' => TRUE,
                        'newsletter_comments' => TRUE,
                        'newsletter_shop' => TRUE,
                        'login_key' => md5(rand(0, 999999999) . time() . 'mammyclub'),
                        'country' => $this->country,
                        'auth_info' => array('email' => $regiserData['email'],
                                             'password' => $this->auth->preparePassword($regiserData['password']))
                        );

        $this->assignActivationKey($entity);

        // Insert the new Entity
        try {
          $entity['id'] = ManagerHolder::get('User')->insert($entity);
          $checkoutInfo['user_id'] = $entity['id'];

          // Login entity
          $this->auth->login($entity, FALSE);

          // Send email about automatic registration
          $explodedEmail = explode('@', $entity['auth_info']['email']);
          $entity['name'] = $explodedEmail[0];
          $entity['auth_info']['password'] = $regiserData['password'];

          $this->auth->sendEmailConfirmation($entity);

          // Set user_id to cart
          ManagerHolder::get('Cart')->updateById($this->cart['id'], 'user_id', $entity['id']);

        } catch (Exception $e) {
          log_message('error', '[checkout_process] - ' . $e->getMessage());
        }

//        $this->load->library('RetailCrmApi');
//        $customer = array();
//        $customer['externalId'] = $checkoutInfo['user_id'];
//        $customer['email'] = $entity['auth_info']['email'];
//        $customer['lastName'] = $checkoutInfo['last_name'];
//        $customer['firstName'] = $checkoutInfo['first_name'];
//        $customer['phones'] = array(substr($checkoutInfo['phone'], 2));
//
//        $response = $this->retailcrmapi->getClient()->request->customersCreate($customer);
//        ManagerHolder::get('User')->updateById($checkoutInfo['user_id'], 'is_export_to_crm', TRUE);

      }
    }

    // Check for repeat order
    if(!empty($checkoutInfo['user_id'])) {
      $checkoutInfo['is_repeat_order'] = ManagerHolder::get('SiteOrder')->existsWhere(array('user_id' => $checkoutInfo['user_id']));
    }

    $sId = ManagerHolder::get('SiteOrder')->insert($checkoutInfo);
    ManagerHolder::get('SiteOrder')->updateById($sId, 'code', date('dmy') . '-' . $sId);

    // Set siteorder_id to cart
//    ManagerHolder::get('Cart')->updateById($this->cart['id'], 'siteorder_id', $sId);
    ManagerHolder::get('SiteOrder')->addItems($this->cart['id'], $sId);
    ManagerHolder::get('Cart')->linkToSiteOrder($this->cart['id'], $sId);

    ManagerHolder::get('EmailNotice')->sendNewOrderNoticeToAdmins($sId);

    $user = ManagerHolder::get('User')->getById($checkoutInfo['user_id'], 'e.*, auth_info.*');
    ManagerHolder::get('MandrillBroadcast')->processServiceEmailData($user, 'email_your_order');

    ManagerHolder::get('EmailNotice')->sendNewOrderNoticeToUser($sId);

    ManagerHolder::get('AlphaSMS')->sendNewOrderNoticeToAdmins($sId);

    delete_cookie('cart_id');

    $this->session->set_userdata('SITE_ORDER_ID', $sId);

//    ManagerHolder::get('SiteOrder')->exportToRetailCrm($sId);

    // Online payment process
    if ($_POST['payment_type'] == 'online') {
      redirect(shop_url('процесс-оплаты/' . $sId));
    }

    redirect(shop_url('спасибо-за-заказ'));
  }

  /**
   * Thank you page
   */
  public function ty() {

    $sId = $this->session->userdata('SITE_ORDER_ID');
    $cart = ManagerHolder::get('Cart')->getOneWhere(array('siteorder_id' => $sId), 'e.*, items.*');
    if(empty($cart)) {
      show_404();
    }
    $this->layout->set('cItems', $cart['items']);

    $siteOrder = ManagerHolder::get('SiteOrder')->getById($sId, 'e.*');
    $this->layout->set('siteOrder', $siteOrder);

    $siteOrderItem = ManagerHolder::get('SiteOrderItem')->getAllWhere(array('siteorder_id' => $sId), 'e.*, product.*');
    $this->layout->set('siteOrderItem', $siteOrderItem);

    $this->setCategories();

    $page = ManagerHolder::get('Page')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())));
    if(!empty($page)) {
      $this->setHeaders($page);
    }

    $content = null;
    $settingsContentItem = ManagerHolder::get('Settings')->getOneWhere(array('k' => 'ty_page_content'), 'e.*');
    if(!empty($settingsContentItem)) {
      $content = $settingsContentItem['v'];
      if(strpos($content, '{email}') !== FALSE) {
        $replace = '';
        if(!empty($this->authEntity)) {
          $replace = $this->authEntity['auth_info']['email'];
        }
        $content = str_replace('{email}', $replace, $content);
      }
    }

    $this->layout->set('content', $content);
    $this->layout->view('ty');
  }

  /**
   * Process payment
   */
  public function process_payment($sId) {
    $siteOrder = ManagerHolder::get('SiteOrder')->getById($sId);
    if(empty($siteOrder)) {
      show_404();
    }

    $this->load->library('common/Liqpay');

    foreach ($siteOrder['Cart'] as $c) {
      if($c['siteorder_id'] == $siteOrder['id']) {
        $siteOrder['Cart'] = $c;
        break;
      }
    }

    $productsInCart = ManagerHolder::get('SiteOrderItem')->getAllWhere(array('siteorder_id' => $siteOrder['id']));

    $description = '';
    foreach ($productsInCart as $p) {
      $description .= $p['product']['name'] . ', ';
    }
    $description = rtrim($description, ' ,');

    if (!isset($siteOrder['total_with_discount']) || empty($siteOrder['total_with_discount'])) {
      $totalAmount = $siteOrder['total'];
    } else {
      $totalAmount = $siteOrder['total_with_discount'];
    }

    $paymentData = $this->liqpay->getPaymentFormData($siteOrder['id'], $totalAmount, $description);
    $this->layout->set('paymentData', $paymentData);
    $this->layout->view('process_payment');
  }

  /**
   * Liqpay payment process
   */
  public function liqpay_payment_process() {
    $this->load->library('common/Liqpay');
    $transactionData = $this->liqpay->getTransactionDataFromPost();

    if (!$this->liqpay->transactionDataIsValid()) {
      // payment data not valid

      // send email to admin
      ManagerHolder::get('Email')->send('elabor59@gmail.com', 'Invalid liqpay data', 'POST: ' . print_r($_POST, TRUE));

      // record to log
      log_message('error', 'Invalid liqpay data. POST: ' . print_r($_POST, TRUE));

    } else {
      // payment data is valid
      ManagerHolder::get('Transaction')->processLiqpayTransaction($transactionData);
    }
  }


  /*********************************************************************************
   ****************************** NEW LIQPAY API VERSION 3.0 ***********************
   *********************************************************************************/

  /**
   * Liqpay checkout process
   * @param integer $sId
   */
  public function liqpay_checkout_process($sId) {
    $siteOrder = ManagerHolder::get('SiteOrder')->getById($sId);
    if(empty($siteOrder)) {
      show_404();
    }

    // load config
    $this->load->config('payment');
    $cfg = $this->config->item('liqpay');

    $this->load->library('LiqPaySDK', array($cfg['merchant2_id'], $cfg['merchant2_signature']));

    $total = $siteOrder['total'];
    if (!empty($siteOrder['total_with_discount']) && $siteOrder['total_with_discount'] > 0) {
      $total = $siteOrder['total_with_discount'];
    }

    $params = array('action'         => 'pay',
                    'amount'         => $total,
                    'currency'       => 'UAH',
                    'description'    => 'Оплата за товары по заказу ' . $siteOrder['code'],
                    'order_id'       => process_siteorder_id_rand($siteOrder['id']),
                    'version'        => '3',
                    'server_url'     => $cfg['merchant2_server_url']);
    if (ENV != 'PROD') {
      $params['sandbox'] = '1';
    }
    $formHtml = $this->liqpaysdk->cnb_form($params, FALSE);

    $formHtml .= '<script type="text/javascript">';
    $formHtml .= 'var form = document.getElementsByTagName("form");';
    $formHtml .= 'form[0].submit();';
    $formHtml .= '</script>';

    die($formHtml);
  }

  /**
   * Liqpay checkout gate
   */
  public function liqpay_checkout_gate() {
    log_message('debug', '[liqpay_checkout_gate] - POST: ' . print_r($_POST, TRUE));

    $this->load->helper('common/itirra_validation');
    simple_validate_post(array('data', 'signature'));

    // load config
    $this->load->config('payment');
    $cfg = $this->config->item('liqpay');

    $this->load->library('LiqPaySDK', array($cfg['merchant2_id'], $cfg['merchant2_signature']));

    if (!$this->liqpaysdk->transaction_is_valid()) {
      ManagerHolder::get('Email')->send('alexeii.boyko@gmail.com', 'Invalid liqpay data', 'POST: ' . print_r($_POST, TRUE));
      log_message('error', 'Invalid liqpay data.');
      die();
    }

    $transactionData = json_decode(base64_decode($_POST['data']), TRUE);
    ManagerHolder::get('Transaction')->processLiqpayTransaction($transactionData);
    die();
  }

  /**
   * Unsubscribe from product broadcast process
   */
  public function unsubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_shop', FALSE);
    redirect('отписка-от-товарной-рассылки');
  }

  /**
   * Unsubscribe page
   */
  public function unsubscribe() {
    if($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }
    $header = array('title' => 'Вы успешно отписались от рассылки "Мамин Магазин"',
        'description' => 'Вы успешно отписались от товарной рассылки');
    $this->layout->set('header', $header);

    $this->layout->setModule('shop');
    $this->layout->set('user', $this->authEntity);
    $this->layout->view('parts/unsubscribe');
  }

  /**
   * Resubscribe process.
   */
  public function resubscribe_process() {
    ManagerHolder::get('User')->updateById($this->authEntity['id'], 'newsletter_shop', TRUE);
    set_flash_notice('Вы снова подписаны на нашу рассылку "Мамин Магазин"');
    redirect('личный-кабинет/редактирование-информации');
  }

  /**
   * Unsubscribe reason process.
   */
  public function unsubscribe_reason_process() {
    if($this->isLoggedIn == FALSE || empty($this->authEntity)) {
      show_404();
    }
    if(!isset($_POST['reason']) || empty($_POST['reason'])) {
      set_flash_error('Вы не указали причину отписки.');
      redirect_to_referral();
    }

    if(!empty($this->settings['site_email'])) {
      $message = 'Пользователь <a href="' . admin_site_url('user/add_edit/' . $this->authEntity['id']) . '">' . $this->authEntity['name'] . '</a> был отписан от рассылки по причине: <br />' . $_POST['reason'];
      ManagerHolder::get('Email')->send($this->settings['site_email'], 'Причина отписки от товарной рассылки', $message);
    }

    redirect_to_referral();
  }

  /**
   * Ajax info
   */
  public function ajax_info($type = '') {
    $content = '';
    $title = '';

    if ($type == 'гарантия') {
      $content = $this->settings['pop_up_warranty_full_text'];
      $title = $this->settings['pop_up_warranty_title'];
    } elseif ($type == 'доставка') {
      $content = $this->settings['pop_up_delivery_full_text'];
      $title = $this->settings['pop_up_delivery_title'];
    } elseif ($type == 'оплата') {
      $content = $this->settings['pop_up_payment_full_text'];
      $title = $this->settings['pop_up_payment_title'];
    } else {
      show_404();
    }

    $this->layout->set('title', $title);
    $this->layout->set('content', $content);
    $this->layout->setLayout('ajax');
    $this->layout->view('parts/ajax_info');
  }

  /**
   * Ajax brand info
   * @param string $bId
   */
  public function ajax_brand_info($bId = NULL) {
    $brand = ManagerHolder::get('ProductBrand')->getById($bId, 'e.*, image.*');

    $this->layout->setLayout('ajax');
    $this->layout->set('brand', $brand);
    $this->layout->view('parts/ajax_brand');
  }

  /**
   * Ajax shop region is not supported
   */
  public function ajax_shipping_guarantee_payment() {
    $this->layout->setLayout('ajax');
    $this->layout->view('parts/ajax_shipping_guarantee_payment');
  }


  /**
   * Ajax shop region is not supported
   */
  public function ajax_region_not_supported() {

    // Send notice to admins
    ManagerHolder::get('EmailNotice')->sendRegionNotSupportedNoticeToAdmins();

    $this->layout->setLayout('ajax');
    $this->layout->view('parts/ajax_region_not_supported');
  }

  /**
   * Check for added to cart product
   */
  private function check_for_added_to_cart_product() {
    $addedProductId = $this->session->flashdata(ADDED_TO_CART_PRODUCT_ID_SESSION_KEY);
    if (!empty($addedProductId)) {
      $this->layout->set('addedToCartProductId', $addedProductId);
    }
  }

  /**
   * Add product and redirect to cart
   */
  public function add_product_and_redirect_to_cart() {
    if (isset($_GET[REDIRECT_TO_CART_KEY]) && !empty($_GET[REDIRECT_TO_CART_KEY])) {
      $product = ManagerHolder::get('Product')->getOneWhere(array('page_url' => surround_with_slashes(uri_string())), 'e.*');
      if (!empty($product)) {
        $this->load->library('DbCart');
        $this->dbcart->add_item(array('product_id' => $product['id'], 'price' => $product['price']));
        redirect(shop_url('оформить-заказ'));
      } else {
        show_404();
      }
    }
  }

  /**
   * Show new product in cart pop up
   * @param integer $pId
   */
  public function ajax_show_new_product_in_cart_pop_up($pId) {
    $cartItem = ManagerHolder::get('Product')->getById($pId, 'e.*, image.*');

    $this->layout->setLayout('ajax');
    $this->layout->set('cartItem', $cartItem);
    $this->layout->view('parts/ajax_new_product_in_cart_pop_up');
  }

  /**
   * analytics_employee
   */
  public function analytics_employee() {
    $this->layout->view('analytics_employee');
  }

  /**
   * get_warehouse_numbers_ajax
   */
  public function get_warehouse_numbers_ajax() {
    if(!isset($_GET['city_id']) || empty($_GET['city_id'])) {
      show_404();
    }

    $warehouses = ManagerHolder::get('Warehouse')->getAsViewArray(array(), 'name', null, array('city_id' => (int)$_GET['city_id']));

    die(json_encode($warehouses));
  }

  /**
   * Get address ajax
   */
  public function get_address_ajax() {
    if(!isset($_GET['city_id']) || empty($_GET['city_id'])) {
      show_404();
    }

    $city = ManagerHolder::get('City')->getById($_GET['city_id'], 'e.*');

    $this->load->library('NewPostSdk');
    $streets = $this->newpostsdk->searchStreet($city['ref'], $_GET['query']);

    die(json_encode($streets));
  }

//   /**
//    * get_select2_np_cities_ajax
//    */
//   public function get_select2_np_cities_ajax() {

//     if(!isset($_GET['q']) || empty($_GET['q'])) {
//       show_404();
//     }

//     $q = mb_strtolower($_GET['q'], 'UTF-8');

//     $result = array();

//     $npJson = file_get_contents('web/np.json');
//     if(!empty($npJson)) {
//       $npArray = json_decode($npJson, TRUE);

//       $cities = array();
//       foreach ($npArray['response'] as $a) {
//         if(!isset($cities[$a['cityRu']]) && strpos(mb_strtolower($a['cityRu'], 'UTF-8'), $q) !== FALSE) {
//           $cities[$a['cityRu']] = $a['cityRu'];
//         }
//       }
//       if (!empty($cities)){
//         foreach ($cities as $k => $v) {
//           $result[] = array('id' => $k, 'text' => $v);
//         }
//       }
//     }

//     $result = json_encode($result);
//     die($result);
//   }

  /**
   * Set categories
   */
  private function setCategories() {
    $this->load->library("common/cache");
    $categories = $this->cache->get('menu_cats', 'PRODUCT_CATEGORY_CACHE_GROUP_KEY');
    if(empty($categories)) {
      $categories = ManagerHolder::get('ProductCategory')->getWhere(array(), 'e.*');
      $saleCategories = ManagerHolder::get('ProductSaleCategory')->getWhere(array(), 'e.*');
      if (!empty($saleCategories)) {
        $categories[] = $saleCategories[0];
      }

      $categories = $this->processCategoryLoop($categories);
      $this->cache->save('menu_cats', $categories, 'PRODUCT_CATEGORY_CACHE_GROUP_KEY');
    }
    $this->layout->set('categories', $categories);
  }

  /*** Private methods for simple auth ***/

  /**
   * processCategoryLoop
   * @param array $categories
   */
  private function processCategoryLoop($categories) {
    if(!empty($categories)) {
      foreach ($categories as $k => $v) {
        if (!$v['published'] && (!isset($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']) || empty($_SESSION['LOGGED_IN_ADMIN_SESSION_KEY']))) {
          unset($categories[$k]);
          continue;
        }
        if(!empty($v['__children'])) {
          $categories[$k]['__children'] = $this->processCategoryLoop($v['__children']);
        }
      }
    }
    return $categories;
  }

  /**
   * Generate password.
   * @return string
   */
  private function generatePassword() {
    $this->load->helper('string');
    return random_string('alnum', 8);
  }

  /**
   * Assign activation key.
   * @param array $entity
   */
  private function assignActivationKey(&$entity) {
    $entity['auth_info']['activation_key'] = md5(rand() . microtime());
    $entity['auth_info']['email_confirmed'] = FALSE;
  }

}