<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * TransactionManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class TransactionManager extends BaseManager {

  /** Name field. */
  protected $nameField = "liqpay_transaction_id";

  /** Order by */
  protected $orderBy = "created_at DESC";

  /** Fields. */
  public $fields = array("status" => array("type" => "enum"),
                         "amount" => array("type" => "input_double"),
                         "error_code" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "sender_phone" => array("type" => "input", "attrs" => array("maxlength" => 255)),
                         "liqpay_transaction_id" => array("type" => "input_integer"),
                         "created_at" => array("type" => "datetime", "class" => "readonly", "attrs" => array("disabled" => "disabled"))
  );

  /** List params. */
  public $listParams = array("status", "amount", "liqpay_transaction_id", "created_at");

  /**
   * processLiqpayTransaction
   * (create & save Transaction)
   * @param $data
   */
  public function processLiqpayTransaction($data) {
    try {
      $tr = array();
      $tr['liqpay_transaction_id'] = $data['transaction_id'];
      $tr['status'] = $data['status'];
      $tr['sender_phone'] = isset($data['sender_phone'])?$data['sender_phone']:'';
      $tr['error_code'] = isset($data['code'])?$data['code']:'';
      $tr['amount'] = $data['amount'];
      $tr['id'] = ManagerHolder::get('Transaction')->insert($tr);
      log_message('debug', '[processLiqpayTransaction] - Processing transaction: ' . print_r($tr, TRUE));

      // add to SiteOrder - Transaction relation
      $siteOrderId = process_siteorder_id_rand($data['order_id'], TRUE);
      ManagerHolder::get('SiteOrder')->updateById($siteOrderId, 'transaction_id', $tr['id']);

      // set paid siteorder status
      if (in_array($tr['status'], array('success', 'sandbox'))) {
        ManagerHolder::get('SiteOrder')->updateById($siteOrderId, 'paid', TRUE);
        ManagerHolder::get('SiteOrder')->updateById($siteOrderId, 'paid_date', date(DOCTRINE_DATE_FORMAT));
        ManagerHolder::get('SiteOrder')->updateById($siteOrderId, 'paid_amount', $data['amount']);
        ManagerHolder::get('EmailNotice')->sendNewLiqpayCheckoutNoticeToAdmins($siteOrderId);
      }
    } catch (Exception $e) {
      log_message('error', '[processLiqpayTransaction] - Exception: ' . $e->getMessage());
    }
  }

}