<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$loader = require_once BASEPATH . 'nova-poshta-sdk/lib/NovaPoshta/bootstrap.php';

use NovaPoshta\ApiModels\ContactPerson;
Use NovaPoshta\Config;
use NovaPoshta\ApiModels\Address;
use NovaPoshta\ApiModels\Common;
use NovaPoshta\MethodParameters\Address_getStreet;
use NovaPoshta\MethodParameters\Address_getWarehouses;
use NovaPoshta\ApiModels\InternetDocument;
use NovaPoshta\MethodParameters\Address_getWarehouseTypes;
use NovaPoshta\MethodParameters\InternetDocument_getDocumentList;
use NovaPoshta\ApiModels\Counterparty;
use NovaPoshta\MethodParameters\Counterparty_getCounterparties;
use NovaPoshta\MethodParameters\Counterparty_getCounterpartyAddresses;
use NovaPoshta\MethodParameters\Counterparty_getCounterpartyContactPersons;
use NovaPoshta\Models\CounterpartyContact;

/**
 * New post sdk library
 * Itirra - http://itirra.com
 */
class NewPostSDK {



  /**
   * NewPostSDK constructor.
   */
  public function __construct() {
//    Config::setApiKey($key);
    Config::setFormat(Config::FORMAT_JSONRPC2);
    Config::setLanguage(Config::LANGUAGE_RU);
  }

  /**
   * @param $apiKey
   */
  public function setApiKey($apiKey) {
    Config::setApiKey($apiKey);
  }

  /**
   * Search street
   * @param $cityRef
   * @param $search
   * @return array
   */
  public function searchStreet($cityRef, $search) {
    $data = new Address_getStreet();
    $data->setCityRef($cityRef);
    $data->setFindByString($search);
    $data->setPage(1);

    $streets = Address::getStreet($data);
    $result = array('query' => $search, 'suggestions' => array());
    foreach ($streets->data as $street) {
      $value = $street->StreetsType . ' ' . $street->Description;
      $result['suggestions'][] = array('value' => $value, 'data' => array('ref' => $street->Ref, 'streets_type' => $street->StreetsType));
    }

    return $result;
  }


  /**
   * Get Warehouses
   * @param $cityRef
   * @return array
   */
  public function getWarehouses($cityRef) {
    $data = new Address_getWarehouses();
    $data->setCityRef($cityRef);

    $result = Address::getWarehouses($data);
    return $result->data;
  }

  /**
   * Get Warehouse types
   * @return array
   */
  public function getWarehouseTypes() {
    $data = new Address_getWarehouseTypes();

    $result = Address::getWarehouseTypes($data);
    return $result->data;
  }

  /**
   * Get all Warehouses
   * @return array
   */
  public function getAllWarehouses() {
    $data = new Address_getWarehouses();

    $result = Address::getWarehouses($data);
    return $result->data;
  }

  /**
   * Get Warehouses view array
   * @param $cityRef
   * @return array
   */
  public function getWarehousesViewArray($cityRef) {
    $warehouses = $this->getWarehouses($cityRef);

    $result = array();
    foreach ($warehouses as $warehouse) {
      $result[] = array('name' => $warehouse->Description, 'ref' => $warehouse->Ref);
    }

    return $result;
  }

  /**
   * Get cities view array
   */
  public function getCitiesViewArray() {
    $cities = $this->getCities();

    $result = array();
    foreach ($cities as $city) {
      $result[$city->Description . '|' . $city->Ref] = $city->Description;
    }

    return $result;
  }

  /**
   * Get cities
   */
  public function getCities() {
    $data = new \NovaPoshta\MethodParameters\Address_getCities();

    $result = Address::getCities($data);
    return $result->data;
  }

  /**
   * Get Time Intervals
   * @param $cityRef
   * @param $date
   * @return array
   */
  public function getTimeIntervals($cityRef, $date) {
    $data = new \NovaPoshta\MethodParameters\Common_getTimeIntervals();
    $data->setRecipientCityRef($cityRef);
    $data->setDateTime($date);

    $result = Common::getTimeIntervals($data);
    return $result->data;
  }

  /**
   * Create internet document
   * @return \NovaPoshta\Models\DataContainerResponse
   */
  public function createInternetDocument() {

    $internetDocument = new InternetDocument();
    $internetDocument->RecipientAddressName = 'Testooooooo';
    $internetDocument->setServiceType('WarehouseDoors');
    $internetDocument->setPayerType('Recipient');
    $internetDocument->setPaymentMethod('Cash');
    $internetDocument->setCargoType('Cargo');
    $internetDocument->setWeight('31');
    $internetDocument->setVolumeGeneral('0.002');
    $internetDocument->setSeatsAmount('2');
    $internetDocument->setCost('2');
    $internetDocument->setDescription(' fd  fsf2');
    $internetDocument->setDateTime('10.06.2015');
    $internetDocument->setPreferredDeliveryDate('20.06.2015');
    $internetDocument->setTimeInterval('CityDeliveryTimeInterval2');
    $internetDocument->setPackingNumber('55');
    $internetDocument->setInfoRegClientBarcodes('55552');
    $internetDocument->setSaturdayDelivery('true');
    $internetDocument->setNumberOfFloorsLifting('12');
    $internetDocument->setAccompanyingDocuments('Большая корзина');
    $internetDocument->setAdditionalInformation('Стекло');
//    $internetDocument->addBackwardDeliveryData($backwardDeliveryData1);
//    $internetDocument->addBackwardDeliveryData($backwardDeliveryData2);

    return $internetDocument->save();
  }

  public function getDocuments() {
    $data = new InternetDocument_getDocumentList();
    $data->DateTimeFrom = '01.10.2017';
    $data->DateTimeTo = '10.10.2017';

    return InternetDocument::getDocumentList($data);
  }

  /**
   * @param string $property
   * @return array
   */
  public function getCounterparties($property = "Sender") {
    $data = new Counterparty_getCounterparties();
    $data->CounterpartyProperty = $property;

    $result = Counterparty::getCounterparties($data);
    return $result->data;
  }

  /**
   * @param string $counterpartyRef
   * @param int $page
   * @return array
   */
  public function getCounterpartyAddresses($counterpartyRef, $page = 1) {
    $data = new Counterparty_getCounterpartyAddresses();
    $data->setRef($counterpartyRef);
    $data->setPage($page);

    $result = Counterparty::getCounterpartyAddresses($data);
    return $result;
  }

  /**
   * @param $counterpartyRef
   * @param int $page
   * @return array
   */
  public function getCounterpartyContactPersons($counterpartyRef, $page = 1) {
    $data = new Counterparty_getCounterpartyContactPersons();
    $data->setRef($counterpartyRef);
    $data->setPage($page);
//    $data->setRef('c09f5e03-c35d-11e6-8b12-005056887b8d');
//    $data->setRef('66915bae-cb3c-11e6-8ba8-005056881c6b');

    $result = Counterparty::getCounterpartyContactPersons($data);
    return $result;
  }

  /**
   * @param $siteOrder
   * @param $store
   * @return \NovaPoshta\Models\DataContainerResponse
   * @param $data
   */
  public function createTtn($siteOrder, $store, $data = null) {

    $paymentControl = isset($data['payment_control']) && !empty($data['payment_control']);

    $sender = new CounterpartyContact();
    $sender->setRef($store['counterparty']['ref']);

    if ($store['sender_service_type'] == 'doors') {
      $sender->setCity($store['sender_counterpartyaddress']['city']['ref']);
      $sender->setAddress($store['sender_counterpartyaddress']['ref']);
    } else {
      $sender->setCity($store['sender_city']['ref']);
      $sender->setAddress($store['sender_warehouse']['ref']);
    }
    $sender->setContact($store['counterpartycontactperson']['ref']);
    $sender->setPhone($store['counterpartycontactperson']['phones']);

    $contactPerson = new ContactPerson();
    $contactPerson->setCounterpartyRef($store['counterpartycontact']['ref']);
//    $fio = explode(' ', $siteOrder['fio']);
//    $firstName = isset($fio[1]) ? $fio[1] :'';
//    $lastName = isset($fio[0]) ? $fio[0] :'';
//    $middleName = isset($fio[2]) ? $fio[2] :'';

    $firstName = $siteOrder['first_name'];
    $lastName = $siteOrder['last_name'];
    $middleName = '';
    $firstName = str_replace('I', 'І', $firstName);
    $firstName = str_replace('i', 'і', $firstName);
    $lastName = str_replace('I', 'І', $lastName);
    $lastName = str_replace('i', 'і', $lastName);
    $middleName = str_replace('I', 'І', $middleName);
    $middleName = str_replace('i', 'і', $middleName);

    $contactPerson->setFirstName($firstName);
    $contactPerson->setLastName($lastName);
    $contactPerson->setMiddleName($middleName);
    $contactPerson->setPhone($siteOrder['phone']);
    $contactPersonData = $contactPerson->save();
    if (isset($_GET['debug']) && $_GET['debug'] == 1) {
      trace($contactPersonData);
    }
    if (!$contactPersonData->success) {
      return $contactPersonData;
    }
    $contactPersonData = $contactPersonData->data[0];

    $recipient = new CounterpartyContact();
    $recipient->setCity($siteOrder['delivery_city']['ref']);
    $recipient->setRef($store['counterpartycontact']['ref']);

    if ($siteOrder['delivery_type'] == 'delivery-to-home') {
      $address = new Address();
      $address->setCounterpartyRef($store['counterpartycontact']['ref']);
      $address->setBuildingNumber($siteOrder['delivery_house']);
      $address->setFlat($siteOrder['delivery_flat']);
      $address->setStreetRef($siteOrder['delivery_street_ref']);
      $addressData = $address->save();
      if (isset($_GET['debug']) && $_GET['debug'] == 1) {
        trace($addressData);
      }
      if (!$addressData->success) {
        return $addressData;
      }
      $addressData = $addressData->data[0];

      $recipient->setAddress($addressData->Ref);
      $recipientServiceType = 'Doors';
    } else {
      $recipient->setAddress($siteOrder['delivery_warehouse']['ref']);
      $recipientServiceType = 'Warehouse';
    }

    $recipient->setContact($contactPersonData->Ref);
    $recipient->setPhone($siteOrder['phone']);

    $internetDocument = new InternetDocument();
    $internetDocument->setSender($sender);
    $internetDocument->setRecipient($recipient);
    $serviceType = ucfirst($store['sender_service_type']) . $recipientServiceType;
    $internetDocument->setServiceType($serviceType);

    if (!empty($siteOrder['delivery_date']) && $recipientServiceType == 'Doors') {
      $date = strtotime($siteOrder['delivery_date']);
      $date = date('d.m.Y', $date);
      $internetDocument->setPreferredDeliveryDate($date);
      $internetDocument->setTimeInterval($siteOrder['delivery_interval_code']);
    }

    $internetDocument->setPayerType('Sender');
    $internetDocument->setPaymentMethod('NonCash');
    $internetDocument->setCargoType('Parcel');
	  $internetDocument->setWeight($data['weight']?$data['weight']:'1');
	  $internetDocument->setVolumeGeneral($data['volume_general']?$data['volume_general']:'0.002');
	  $internetDocument->setSeatsAmount(1);
    $internetDocument->setCost($siteOrder['total_with_discount']);
    $internetDocument->setDescription('товари');
//    $internetDocument->setDateTime('11.10.2017');
    $internetDocument->setInfoRegClientBarcodes($siteOrder['code']);

    if (!$siteOrder['paid']) {
        if (!$paymentControl) {
          $backwardDeliveryData = new \NovaPoshta\Models\BackwardDeliveryData();
          $backwardDeliveryData->setPayerType('Sender');
          $backwardDeliveryData->setCargoType('Money');
          $backwardDeliveryData->setRedeliveryString($siteOrder['total_with_discount']);
          $internetDocument->addBackwardDeliveryData($backwardDeliveryData);
        } else {
          $internetDocument->setAfterpaymentOnGoodsCost($siteOrder['total_with_discount']);
        }
    }

    $result = $internetDocument->save();
    if (isset($_GET['debug']) && $_GET['debug'] == 1) {
      traced($result);
    }

    return $result;
  }

  /**
   * @param $supplierRequest
   * @param $senderStore
   * @param $receiverStore
   * @return \NovaPoshta\Models\DataContainerResponse
   */
  public function createSupplierRequestTtn($supplierRequest, $senderStore, $receiverStore) {
    $sender = new CounterpartyContact();
    $sender->setRef($senderStore['counterparty']['ref']);

    if ($senderStore['sender_service_type'] == 'doors') {
      $sender->setCity($senderStore['sender_counterpartyaddress']['city']['ref']);
      $sender->setAddress($senderStore['sender_counterpartyaddress']['ref']);
    } else {
      $sender->setCity($senderStore['sender_city']['ref']);
      $sender->setAddress($senderStore['sender_warehouse']['ref']);
    }
    $sender->setContact($senderStore['counterpartycontactperson']['ref']);
    $sender->setPhone($senderStore['counterpartycontactperson']['phones']);

    $recipient = new CounterpartyContact();
    $recipient->setRef($receiverStore['receiver_counterparty']['ref']);

    if ($receiverStore['receiver_service_type'] == 'doors') {
      $recipient->setCity($receiverStore['receiver_counterpartyaddress']['city']['ref']);
      $recipient->setAddress($receiverStore['receiver_counterpartyaddress']['ref']);
    } else {
      $recipient->setCity($receiverStore['receiver_city']['ref']);
      $recipient->setAddress($receiverStore['receiver_warehouse']['ref']);
    }
    $recipient->setContact($receiverStore['receiver_counterpartycontactperson']['ref']);
    $recipient->setPhone($receiverStore['receiver_counterpartycontactperson']['phones']);

    $internetDocument = new InternetDocument();
    $internetDocument->setSender($sender);
    $internetDocument->setRecipient($recipient);
    $serviceType = ucfirst($receiverStore['sender_service_type']) . ucfirst($receiverStore['receiver_service_type']);
    $internetDocument->setServiceType($serviceType);

    $internetDocument->setPayerType('Sender');
    $internetDocument->setPaymentMethod('NonCash');
    $internetDocument->setCargoType('Parcel');
    $internetDocument->setWeight('1');
    $internetDocument->setVolumeGeneral('0.002');

    $internetDocument->setSeatsAmount('1');
    $internetDocument->setCost(400);
    $internetDocument->setDescription('товари');
//    $internetDocument->setDateTime('11.10.2017');
    $internetDocument->setInfoRegClientBarcodes($supplierRequest['code']);

    $result = $internetDocument->save();
    if (isset($_GET['debug']) && $_GET['debug'] == 1) {
      traced($result);
    }

    return $result;
  }

}