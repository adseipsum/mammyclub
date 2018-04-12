<?php

namespace NovaPoshta\ApiModels;

use NovaPoshta\Models\BackwardDeliveryData;
use NovaPoshta\Models\Cargo;
use NovaPoshta\Models\OptionsSeat;
use NovaPoshta\Core\ApiModel;
use NovaPoshta\Models\CounterpartyContact;
use NovaPoshta\Config;
use NovaPoshta\MethodParameters\MethodParameters;
use stdClass;

/**
 * InternetDocument - Модель для оформления отправлений
 *
 *
 * Class InternetDocument
 * @package NovaPoshta\ApiModels
 */
class TrackingDocument extends ApiModel
{

  /**
   * Вызвать метод getStatusDocuments() - трекинг документов
   *
   * @param MethodParameters $data
   * @return \NovaPoshta\Models\DataContainerResponse
   */
  public static function getStatusDocuments(MethodParameters $data = null)
  {
    return self::sendData(__FUNCTION__, $data);
  }

}
