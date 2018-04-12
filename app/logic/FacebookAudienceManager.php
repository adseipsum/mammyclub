<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * FacebookAudienceManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';
class FacebookAudienceManager extends BaseManager {

  /** Fields. */
  public $fields = array("name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
	                       "description" => array("type" => "input", "attrs" => array("maxlength" => 255)),
	                       "act_account" => array("type" => "select", "class" => "required", "options" => array("act_516395731870839" => "Mammyclub Site", "act_516445905199155" => "Mammyclub Shop")),
                         "filters" => array("type" => "include", "path" => '/includes/admin/facebook_audiaence_filters'));

  /** List params. */
  public $listParams = array("name", "filter_type","description", "act_account", "email_qty", "updating_date", "can_deleted");






	/**
	 * Decoded Json Filter Values
	 * @param string $entity
	 * @return array
	 */
	public function getDecodeJsonFilters($entity){
		$getJsonFilters = ManagerHolder::get('FacebookAudience')->getOneWhere(array('id' => $entity),'e.filters');
		$filters = json_decode($getJsonFilters['filters'],true);
		return $filters;
	}


  /**
   * @param $entityId
   * @param null $entityName
   * @param null $entityDescription
   * @param $filterType
   * @param $communicationType
   * @return mixed
   */
	public function updateUsersFromFacebookAudiences ($entityId, $entityName = null, $entityDescription = null, $filterType, $communicationType) {

    //    $audience = ManagerHolder::get('FacebookAudience')->getAllWhere(array('id' => $entityId),'e.*');
    $audience = ManagerHolder::get('FacebookAudience')->getAllWhere(array('id' => $entityId),'e.*');
    $previousCommunicationType = json_decode($audience[0]['previous_communication_type'], true);

    // Если у существующией аудитории тип коммуникации отличается от обновленной аудитории
    if (isset($previousCommunicationType['communication_type']) && $previousCommunicationType['communication_type'] != $communicationType) {
      $communicationTypeFromData = $previousCommunicationType['communication_type'];
    } else {
      $communicationTypeFromData = $communicationType;
    }

    if (isset($previousCommunicationType['filter_type']) && $previousCommunicationType['filter_type'] != $filterType) {
      $filterTypeFromData = $previousCommunicationType['filter_type'];
    } else {
      $filterTypeFromData = $filterType;
    }

    // Достаем существующие данный для удаления по старому типу коммуникации
    $phonesOrEmailsToRemove = array();
    if ($filterTypeFromData == 'user') {
      $users = ManagerHolder::get('User')->getAll('e.id, auth_info.*');
      foreach ($users as $user) {
        $phonesOrEmailsToRemove[] = $user['auth_info']['email'];
      }
    }
    if ($filterTypeFromData == 'siteorder') {
      $siteorders = ManagerHolder::get('SiteOrder')->getAll('e.*');
      foreach ($siteorders as $siteorder) {
        $phonesOrEmailsToRemove[] = $siteorder[$communicationTypeFromData];
      }
    }

    // Достаем новые данные для обновления по типу коммуникации
    $filters = $this->getDecodeJsonFilters($entityId);

    if ($filterType == 'user') {
      $phonesOrEmails = ManagerHolder::get('User')->getUserEmails($filters, $communicationType);
    } else {
      $phonesOrEmails = ManagerHolder::get('SiteOrder')->getEmailsFromSiteorder($filters, $communicationType);
    }

    $phonesOrEmails = array_values(array_unique($phonesOrEmails));

    // Обновляем данные у существующей аудитории
		ManagerHolder::get('FacebookAudience')->updateAllWhere(array('id' => $entityId), array('updating_date' => date(DOCTRINE_DATE_FORMAT),
                                                                                                     'communication_type' => $communicationType ,
                                                                                                     'filter_type' => $filterType ,
                                                                                                     'email_qty' => count($phonesOrEmails)));
		$ci =& get_instance();
		$ci->load->library('FacebookSDK');
		return $ci->facebooksdk->updateAudiences($audience, $phonesOrEmails, $phonesOrEmailsToRemove, $entityName, $entityDescription, $communicationTypeFromData, $communicationType);

	}



}