<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * UserManager
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'logic/base/BaseManager.php';

class UserManager extends BaseManager {

  /** Name field. */
  protected $nameField = "name";

  /** Order by */
  protected $orderBy = "auth_info.created_at DESC";

  /** Fields. */
  public $fields = array(
    "id" => array("type" => "input_integer", "class" => "readonly", "attrs" => array("readonly" => "readonly", "maxlength" => 20)),
    "name" => array("type" => "input", "class" => "required", "attrs" => array("maxlength" => 255)),
    "auth_info.email" => array("type" => "input", "class" => "required email", "attrs" => array("maxlength" => 255)),
    "auth_info.email_confirmed" => array("type" => "checkbox"),
    "pregnancyweek" => array("type" => "select", "relation" => array("entity_name" => "PregnancyWeek")),
    "pregnancyweek_current" => array("type" => "select", "relation" => array("entity_name" => "PregnancyWeek")),
    "newsletter_questions" => array("type" => "checkbox"),
    "newsletter_comments" => array("type" => "checkbox"),
    "newsletter" => array("type" => "checkbox"),
    "newsletter_recommended_products" => array("type" => "checkbox"),
    "newsletter_shop" => array("type" => "checkbox"),
    "newsletter_first_year" => array("type" => "checkbox"),
    "newsletter_useful_tips" => array("type" => "checkbox"),
    "buys_without_discount" => array("type" => "checkbox"),
    "name_full" => array("type" => "input", "attrs" => array("maxlength" => 255)),
    "phone" => array("type" => "input", "attrs" => array("maxlength" => 255)),
    "country" => array("type" => "input", "attrs" => array("maxlength" => 255)),
    "status" => array("type" => "select", "options" => array("user" => "Пользователь", "expert" => "эксперт Mammyclub")),
    "age_of_child" => array("type" => "input_integer"),
    "child_birth_date" => array("type" => "date"),
    "child_sex" => array("type" => "enum"),
    "child_name" => array("type" => "input"),
    "auth_info.created_at" => array("type" => "datetime", "class" => "readonly", "attrs" => array("disabled" => "disabled")),
    "auth_info.last_login" => array("type" => "datetime", "class" => "readonly", "attrs" => array("disabled" => "disabled")),
    "image" => array("type" => "image"),
    "inv_channel" => array("type" => "input"),
    "inv_channel_src" => array("type" => "input"),
    "inv_channel_mdm" => array("type" => "input"),
    "inv_channel_cmp" => array("type" => "input"),
    "inv_channel_cnt" => array("type" => "input"),
    "inv_channel_trm" => array("type" => "input")
  );

  /** List params. */
  public $listParams = array(
    "name",
    "auth_info.email",
    "auth_info.email_confirmed",
    "pregnancyweek_current.name",
    "age_of_child",
    "child_birth_date",
    "newsletter",
    "newsletter_recommended_products",
    "newsletter_shop",
    "newsletter_first_year",
    "newsletter_useful_tips",
    "auth_info.created_at",
    "country",
    "inv_channel"
  );

  /**
   * //TODO: Пока что не работает
   *  Результат фильтра "Диапазон открытых писем"
   *
   * @param $filters
   * @param $sentCount
   * @param $readCount = null
   * @return array|float|int
   */
  protected function isReadRange($filters, $sentCount, $readCount) {
    $result = false;
    if (isset($filters['is_read_range BETWEEN'])) {
      preg_match(('/-?\d+/'), $filters['is_read_range BETWEEN'], $rangeFrom);
      preg_match(('/ -?\d+/'), $filters['is_read_range BETWEEN'], $rangeTo);
      $currentRangeFrom = $sentCount * $rangeFrom[0] / 100;
      $currentRangeTo = $sentCount * $rangeTo[0] / 100;
      if ($currentRangeFrom <= $readCount && $currentRangeTo >= $readCount) {
        $result = true;
      }
    } else {
      if (isset($filters['is_read_range >='])) {
        $currentRange = ($sentCount * $filters['is_read_range >=']) / 100;
        if ($currentRange <= $readCount) {
          $result = true;
        }
      }
      if (isset($filters['is_read_range <='])) {
        $currentRange = $sentCount * $filters['is_read_range <='] / 100;
        if ($currentRange >= $readCount) {
          $result = true;
        }
      }
    }
    return $result;
  }

  /**
   * @param $filters
   * @return array
   */
  public function getUserEmails($filters) {
    $emails = array();
    $what = 'e.id, auth_info.email';
    $applyPaidFilter = null;
    $applyReadFilter = null;
    $readRange = null;
    if (isset($filters['site_order.paid'])) {
      $what .= ', orders.*';
      $applyPaidFilter = $filters['site_order.paid'];
      unset($filters['site_order.paid']);
    }

    $filters['auth_info.id<>'] = '';
    $users = ManagerHolder::get('User')->getAllWhere($filters, $what);
    foreach ($users as $u) {
      if (isset($u['auth_info']['email'])) {
        if ($applyPaidFilter !== null) {
          $atLeastOnePaid = false;
          if ($u['orders']) {
            foreach ($u['orders'] as $o) {
              if ($o['paid']) {
                $atLeastOnePaid = true;
                break;
              }
            }
          }
          if ($applyPaidFilter) {
            if (!$u['orders'] || !$atLeastOnePaid) {
              continue;
            }
          } else {
            if ($atLeastOnePaid) {
              continue;
            }
          }
        }
        if ($applyReadFilter !== null) {
          if ($applyReadFilter == 1) {
            if (!empty($u['MandrillBroadcastRecipient'])) {
              $atLeastOneRead = false;
              $isRangeResult = null;
              $userOpensCounter = 0;
              $userSentCounter = 0;
              foreach ($u['MandrillBroadcastRecipient'] as $recipient) {
                if (!empty($recipient['MandrillBroadcastOpen'])) {
                  $userOpensCounter++;
                }
                $userSentCounter++;
              }
              if ($userOpensCounter >= 5) {
                $atLeastOneRead = true;
              }
              if ($atLeastOneRead) {
                if ($readRange !== null) {
                  $isRangeResult = $this->isReadRange($readRange, $userSentCounter, $userOpensCounter);
                  if (!$isRangeResult) {
                    continue;
                  }
                }
              }
            } else {
              continue;
            }
          } else {
            $noOneRead = false;
            foreach ($u['MandrillBroadcastRecipient'] as $recipient) {
              if (!empty($recipient['MandrillBroadcastOpen'])) {
                $noOneRead = true;
              }
            }
            if ($noOneRead) {
              continue;
            }
          }
        }
        $emails[] = $u['auth_info']['email'];
      }
    }
    return $emails;
  }

  /**
   * @param string $filterName
   * @return array
   * @throws Exception
   */
  public function getFilterValues($filterName) {
    if ($filterName == 'age_of_child') {
      return ManagerHolder::get('AgeOfChild')->getAsViewArray(array(), 'number');
    }
    if ($filterName == 'is_read') {
      return array("1" => lang("admin.yes"), "0" => lang("admin.no"));
    }
    if ($filterName == 'is_read_range') {
      return range(0, 100);
    }
    if ($filterName == 'pregnancyweek_current.number') {
      return ManagerHolder::get('PregnancyWeek')->getAsViewArray(array(), 'number');
    }
    if ($filterName == 'site_order.paid') {
      return array("1" => lang("admin.yes"), "0" => lang("admin.no"));
    }
    return parent::getFilterValues($filterName);
  }

  /**
   * addAvailableSalestoUser
   * @param array $user
   */
  public function addAvailableSalestoUser(&$user) {
    $user['sales'] = array();
    $saleWhere = array('starts_at <=' => date(DOCTRINE_DATE_FORMAT),
      'ends_at >=' => date(DOCTRINE_DATE_FORMAT));
    $sales = ManagerHolder::get('Sale')->getAllWhere($saleWhere, 'e.*, PregnancySale.*, product_rels.*, UserSale.*');
    if (!empty($sales)) {
      foreach ($sales as $s) {
        $s['product_rels'] = get_array_vals_by_second_key($s['product_rels'], 'product_id');
        $userIds = get_array_vals_by_second_key($s['UserSale'], 'user_id');
        $pwIds = get_array_vals_by_second_key($s['PregnancySale'], 'pregnancy_week_id');
        unset($s['UserSale'], $s['PregnancySale']);
        if (isset($s['for_all']) && $s['for_all']) {
          $user['sales'][] = $s;
          continue;
        }
        if (!empty($userIds) && in_array($user['id'], $userIds)) {
          $user['sales'][] = $s;
          continue;
        }
        if (!empty($pwIds) && !empty($user['pregnancyweek_current_id'])) {
          if (in_array($user['pregnancyweek_current_id'], $pwIds)) {
            $user['sales'][] = $s;
            continue;
          }
        }
      }
    }
  }

  /**
   * PostUpdate.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param object $entity
   */
  protected function postUpdate($entity) {
    if (is_object($entity)) {
      try {
        $this->updateBroadcastSettings($entity);
      } catch (Exception $e) {
        log_message('error', 'UserManager->postUpdate exception: ' . $e->getMessage());
      }
    }
  }

  /**
   * PreProcessWhereQuery.
   * OVERRIDE THIS METHOD IN A SUBCLASS TO ADD joins and other stuff.
   *
   * @param Doctrine_Query $query
   * @return Doctrine_Query
   */
  protected function preProcessWhereQuery($query, $pref, $what = "*") {
    $query = parent::preProcessWhereQuery($query, $pref, $what);

    if (strpos($what, 'MandrillBroadcastRecipient.') !== FALSE || $what == '*') {
      $query->addSelect('MandrillBroadcastOpen.*')->leftJoin('MandrillBroadcastRecipient.MandrillBroadcastOpen MandrillBroadcastOpen');
    }

    return $query;
  }

  /**
   * @param $email
   * @return bool
   */
  public function domainBlockedInUa($email) {
    $domains = array('yandex.ru', 'yandex.ua', 'ya.ru', 'ya.ua', 'mail.ru', 'mail.ua', 'bk.ru', ' list.ru', 'inbox.ru');
    foreach ($domains as $d) {
      if (strpos($email, '@' . $d) !== FALSE) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * @param $user
   * @throws Exception
   */
  public function updateBroadcastSettings($user) {
    $brdcstSettingsMap = array(
      1 => 'newsletter',
      2 => 'newsletter_shop',
      3 => 'newsletter_recommended_products',
      4 => 'newsletter_useful_tips',
      5 => 'newsletter_first_year'
    );

    $broadcastSettings = array();
    foreach ($brdcstSettingsMap as $typeId => $v) {
      $broadcastSettings[$typeId] = (int)$user[$v];
    }
    $broadcastSettings[6] = 1; // Add returning broadcast (TRUE by default)

    $jsonString = $this->broadcastSettingsToJson($broadcastSettings);
    $updateQuery = sprintf('UPDATE user
                            SET broadcast_settings = \'%s\'
                            WHERE id = %s',
      $jsonString, $user['id']);

    $this->executeNativeSQL($updateQuery);
  }

  /**
   * @param $broadcastSettings
   * @return string
   */
  public function broadcastSettingsToJson($broadcastSettings) {
    return json_encode($broadcastSettings, JSON_FORCE_OBJECT);
  }

  /**
   * @param array $pwIds
   * @param null $aoc
   * @return array|Doctrine_Collection
   * @throws Doctrine_Query_Exception
   */
  public function getAllWhereWeeksOrAgeOfChild($pwIds = array(), $aoc = NULL) {
    $q = Doctrine_Query::create()->select('*')->from($this->entityName . ' e')->setHydrationMode($this->defaultHydration);
    if (!empty($pwIds)) {
      $q->orWhere('pregnancyweek_current_id IN (' . implode(',', $pwIds) . ')');
    }
    if (!empty($aoc)) {
      $q->orWhere('age_of_child IN (' . $aoc . ')');
    }
    $q->addSelect("auth_info.*")->leftJoin("e.auth_info auth_info");
    return $q->execute();
  }

  /**
   * @param array $entity
   * @throws Exception
   */
  protected function preInsert(&$entity) {
    if (empty($entity['name']) && !empty($entity['auth_info']['email'])) {
      $explodedEmail = explode('@', $entity['auth_info']['email']);
      $entity['name'] = $explodedEmail[0];
    }
    // Setting random avatar to user
    if (empty($entity['image_id'])) {
      $avatar = ManagerHolder::get('DefaultAvatar')->getOneRandom('e.*');
      if (!empty($avatar)) {
        $entity['image_id'] = $avatar['image_id'];
      }
    }
    if (empty($entity['login_key'])) {
      $entity['login_key'] = md5(rand(0, 999999999) . time() . 'mammyclub');
    }
  }

  /**
   * PreDelete.
   * OVERRIDE THIS METHOD IN A SUBCLASS.
   * @param array $keyValueArray
   */
  protected function preDelete($keyValueArray) {
    $user = ManagerHolder::get('User')->getById($keyValueArray['id'], 'e.*');
    ManagerHolder::get('AuthInfo')->deleteById($user['auth_info_id']);
  }

  /**
   * detectCountry
   */
  public function detectCountry() {
    $country = get_cookie('country');
    if (empty($country) && isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && !bot_detected()) {
      $CI =& get_instance();
      $CI->load->library("Maxmind");
      $this->maxmind = new Maxmind();
      $this->maxmind->setOptions(array('98359', 'TwUSChKug9Xx'));
      try {
        $remoteIp = get_ip();
        $country = $this->maxmind->detect($remoteIp, 'country');
      } catch (Exception $e) {
         log_message('debug', '[detectCountry] - maxmind failed with message: ' . $e->getMessage());
      }
      set_cookie('country', $country, 307584000);
    }
    if (empty($country)) {
      $country = 'UA';
    }
    return $country;
  }

  /**
   * @param bool $asJson
   * @return array|string
   */
  public function getDefaultBroadcastChannels($asJson = true) {
    $channels = array();
    foreach (ManagerHolder::get('XBroadcast')->getPossibleBroadcastChannels() as $channel)
    {
      $channels[$channel] = 0;
      if ($channel == XBroadcastManager::BROADCAST_CHANNEL_EMAIL) {
        $channels[$channel] = 1;
      }
    }
    if ($asJson) {
      return json_encode($channels);
    }
    return $channels;
  }
}