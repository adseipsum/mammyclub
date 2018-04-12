<?php


/**
 * Class Liqpay
 */
class Liqpay {

  /**
   * @var array
   */
  protected $config;


  /**
   * Liqpay
   */
  public function Liqpay() {
    // load config
    $ci = & get_instance();
    $ci->load->config('payment');
    $this->config = $ci->config->item('liqpay');
  }


  /**
   * getPaymentFormData
   * @param $productId
   * @param $price
   * @param string $description
   * @param null $resultUrl
   * @return mixed
   */
  public function getPaymentFormData($productId, $price, $description = '', $resultUrl = NULL) {
    $operationXml = $this->getOperationXML($productId, $price, $description, $resultUrl);
    $data['operation_xml'] = $this->encodeXml($operationXml);
    $data['signature'] = $this->getSignature($operationXml);
    return $data;
  }


  /**
   * getTransactionDataFromPost
   * @return mixed
   */
  public function getTransactionDataFromPost() {
    $xmlStr = $_POST['operation_xml'];
    $xmlStr = $this->decodeXml($xmlStr);
    // xmlStr to array
    $xml = simplexml_load_string($xmlStr);
    $json = json_encode($xml);
    $data = json_decode($json, TRUE);
    return $data;
  }


  /**
   * transactionDataIsValid
   * @return bool
   */
  public function transactionDataIsValid(){
    $xml = $this->decodeXml($_POST['operation_xml']);
    $generatedSignature = $this->getSignature($xml);
    return $generatedSignature == $_POST['signature'];
  }


  /**
   * encodeXml
   * @param $xml
   * @return string
   */
  private function encodeXml($xml) {
    return base64_encode($xml);
  }


  /**
   * decodeXml
   * @param $xml
   * @return string
   */
  private function decodeXml($xml) {
    return base64_decode($xml);
  }


  /**
   * getOperationXML
   * @param $productId
   * @param $price
   * @param string $description
   * @param null $resultUrl
   * @return string
   */
  private function getOperationXML($productId, $price, $description = '', $resultUrl = NULL) {
// DOESN'T WORK
//    $price = number_format($price, 2);
    $xml = "
      <request>
        <version>1.2</version>
        <merchant_id>" . $this->config['merchant_id'] . "</merchant_id>
        <result_url>" . ($resultUrl ? $resultUrl : $this->config['result_url']) . "</result_url>
        <server_url>" . $this->config['server_url'] . "</server_url>
        <order_id>" . $productId ."</order_id>
        <amount>" . $price . "</amount>
        <currency>UAH</currency>
        <description>" . $description . "</description>
        <default_phone></default_phone>
        <pay_way>card</pay_way>
        <goods_id>1</goods_id>
      </request>";
    return $xml;
  }


  /**
   * getSignature
   * @param $operationXml
   * @return string
   */
  private function getSignature($operationXml) {
    return base64_encode(sha1($this->config['merchant_signature'] . $operationXml . $this->config['merchant_signature'], 1));
  }

}