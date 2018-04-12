<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * CommonManager
 *
 */
class CommonManager {

  /**
   * createViewDataPreview
   * @param array $entity
   * @return string
   */
  public function createViewDataPreview($entity) {

    // Create view data
    $viewData = array();
    $viewData['subject'] = !empty($entity['email_subject']) ? $entity['email_subject'] : '';
    $viewData['email_appeal'] = !empty($entity['email_appeal']) ? $entity['email_appeal'] : '';
    $viewData['email_intro'] = !empty($entity['email_intro']) ? $entity['email_intro'] : '';
    $viewData['email_outro'] = !empty($entity['email_outro']) ? $entity['email_outro'] : '';
    $viewData['email_main_text'] = !empty($entity['email_main_text']) ? $entity['email_main_text'] : '';

    if(isset($entity['products']) && !empty($entity['products'])) {
      foreach ($entity['products'] as &$product) {
        $url = shop_url($product['page_url']);
        if (substr($url, -1) == '/') {
          $url = substr($url, 0 , -1);
        }
        $product['url_with_login_key'] = $url;
        $product['url_for_buy_button'] = $url;
      }
    }

    foreach ($viewData as &$data) {
      if (!empty($data)) {
        if(isset($entity['products']) && !empty($entity['products'])) {
          $data = ManagerHolder::get('ProductBroadcast')->process_product_broadcast_showcase($data, $entity['products']);
        }
        if (strpos($data, '{UNSUBSCRIBE_LINK}') !== FALSE) {
          $unsubscribeLink = site_url('отписаться-от-рассылки');
          $data = str_replace('{UNSUBSCRIBE_LINK}', $unsubscribeLink, $data);
        }
        if (strpos($data, '{RETURNING_SUCCESS_PAGE}') !== FALSE) {
          $successLink = site_url(RETURNING_SUCCESS_PAGE);
          $data = str_replace('{RETURNING_SUCCESS_PAGE}', $successLink, $data);
        }
        if (strpos($data, '{DATE_AFTER_7_DAYS}') !== FALSE) {
          $weekAfterDate = date("Y-m-d", strtotime("+1 week"));
          $data = str_replace('{DATE_AFTER_7_DAYS}', $weekAfterDate, $data);
        }
      }
    }



    return $viewData;
  }

}