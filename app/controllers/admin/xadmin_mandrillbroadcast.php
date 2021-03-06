<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
/**
 * xAdmin_MandrillBroadcast
 * This class has been auto-generated by Itirra
 */
require_once APPPATH . 'controllers/admin/base/base_admin_controller.php';
class xAdmin_MandrillBroadcast extends Base_Admin_Controller {

  /** Search params. */
  protected $searchParams = array('subject');

  /** DateFilters. */
  protected $dateFilters = array("sent_date");

  /** Additional Actions. */
  protected $additionalActions = array('stats_dynamic');

  /**
   * Stats dynamic export csv
   */
  public function stats_dynamic_export_csv() {

    set_time_limit(0);

    $dateFrom = $_GET['date_from'] . ' 00:00:01';
    $dateTo = $_GET['date_to'] . ' 23:59:59';

    $where = array('updated_at BETWEEN' => $dateFrom . ' AND ' . $dateTo);
    if(!empty($_GET['broadcast.type'])) {
      $where['broadcast.type'] = $_GET['broadcast.type'];
    }
    if(!empty($_GET['is_read'])) {
      $where['is_read'] = $_GET['is_read'] == 'yes' ? TRUE : FALSE;
    }
    if(!empty($_GET['read_more_click'])) {
      $where['read_more_click'] = $_GET['read_more_click'] == 'yes' ? TRUE : FALSE;
    }
    if(!empty($_GET['unsubscribe_link_click'])) {
      $where['unsubscribe_link_click'] = $_GET['unsubscribe_link_click'] == 'yes' ? TRUE : FALSE;
    }
    if(!empty($_GET['user.country'])) {
      $where['user.country'] = $_GET['user.country'];
    }
    if(!empty($_GET['email'])) {
      ManagerHolder::get('User')->setSearch($_GET['email'], 'auth_info.email', 'contains');
      $users = ManagerHolder::get('User')->getAll('id');
      if(!empty($users)) {
        $where['user.id'] = get_array_vals_by_second_key($users, 'id');
      }
    }
    if(!empty($_GET['subject'])) {
      ManagerHolder::get('MandrillBroadcastRecipient')->setSearch($_GET['subject'], 'broadcast.subject', 'contains');
    }
    $result = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere($where, 'e.*, broadcast.*, user.*, MandrillBroadcastVisitedLink.*');
    if(empty($result)) {
      set_flash_error('No data');
      redirect_to_referral();
    }

    $fields = array('updated_at', 'broadcast.type', 'broadcast.name', 'broadcast.subject', 'email', 'is_read', 'read_more_click', 'unsubscribe_link_click', 'MandrillBroadcastVisitedLink', 'user.country');

    // Load CSV Library
    $this->load->library('common/csv');

    // Set separator
//     $this->csv->setSeparator(",");

    // Set headers
    $this->csv->addHeader($fields);

    // Process Rows
    $rows = array();
    foreach ($result as $r) {
      $row = array();

      foreach ($fields as $key) {
        if(strpos($key, '.') !== FALSE) {
          $keyArr = explode('.', $key);
          $row[$key] = $r[$keyArr[0]][$keyArr[1]];
        } elseif ($key == 'MandrillBroadcastVisitedLink') {
          $links = '';
          if(!empty($r[$key])) {
            foreach ($r[$key] as $k => $linkData) {
              $links .= $linkData['link']['url'];
              if($k != count($r[$key]) - 1) {
                $links .= '; ';
              }
            }
          }
          $row[$key] = $links;
        } else {
          $row[$key] = hsc($r[$key]);
        }
      }

      $rows[] = $row;
    }
    $this->csv->addRows($rows);

    // Send file to output
    $this->csv->flushFile(lang('admin.entity_list.' . strtolower($this->entityName) . '.list_title') . '.csv');
    die();
  }


  /**
   * Stats dynamic
   */
  public function stats_dynamic() {

    $result = array();

    if(!empty($_GET['date_from']) && !empty($_GET['date_to'])) {

      $dateFrom = $_GET['date_from'] . ' 00:00:01';
      $dateTo = $_GET['date_to'] . ' 23:59:59';

      $minDate = '2014-07-30 00:00:01';
      $maxDate = date('Y-m-d') . ' 23:59:59';

      // validation
      if ($dateFrom < $minDate) {
        $dateFrom = $minDate;
      }
      if ($dateTo > $maxDate) {
        $dateTo = $maxDate;
      }

      $where = array('updated_at BETWEEN' => $dateFrom . ' AND ' . $dateTo);
      if(!empty($_GET['broadcast.type'])) {
        $where['broadcast.type'] = $_GET['broadcast.type'];
      }
      if(!empty($_GET['is_read'])) {
        $where['is_read'] = $_GET['is_read'] == 'yes' ? TRUE : FALSE;
      }
      if(!empty($_GET['read_more_click'])) {
        $where['read_more_click'] = $_GET['read_more_click'] == 'yes' ? TRUE : FALSE;
      }
      if(!empty($_GET['unsubscribe_link_click'])) {
        $where['unsubscribe_link_click'] = $_GET['unsubscribe_link_click'] == 'yes' ? TRUE : FALSE;
      }
      if(!empty($_GET['user.country'])) {
        $where['user.country'] = $_GET['user.country'];
      }
      if(!empty($_GET['email'])) {
        ManagerHolder::get('User')->setSearch($_GET['email'], 'auth_info.email', 'contains');
        $users = ManagerHolder::get('User')->getAll('id');
        if(!empty($users)) {
          $where['user.id'] = get_array_vals_by_second_key($users, 'id');
        }
      }
      if(!empty($_GET['subject'])) {
        ManagerHolder::get('MandrillBroadcastRecipient')->setSearch($_GET['subject'], 'broadcast.subject', 'contains');
      }
      $result = ManagerHolder::get('MandrillBroadcastRecipient')->getAllWhere($where, 'e.*, broadcast.*, user.*, MandrillBroadcastVisitedLink.*, email_html.*');
    }

    $this->layout->set("result", $result);
    $this->layout->view('mandrillbroadcast/stats_dynamic');
  }

  /**
   * Stats dynamic search autocomplete
   */
  public function stats_dynamic_search_autocomplete() {

    if(empty($_GET['type']) || empty($_GET['query'])) {
      die();
    }

    $searchParams = array('email');
    $managerNames = array('AuthInfo');
    if($_GET['type'] == 'subject') {
      $managerNames = array('PregnancyWeek', 'RecommendedProductsBroadcast');
    }

    $result = '{"query":"%s","suggestions":[%s],"data":[%s]}';
    $suggestions = array();
    $suggestionsData = array();

    foreach ($managerNames as $managerName) {

      if($managerName == 'PregnancyWeek') {
        $searchParams = array('email_subject');
      } elseif ($managerName == 'RecommendedProductsBroadcast') {
        $searchParams = array('subject');
      }

      $manager = ManagerHolder::get($managerName);
      $search = array();
      $search["search_string"] = trim($_GET['query']);
      if (!isset($search["search_type"])) {
        $search["search_type"] = 'contains';
      }
      if (!isset($search["search_in"])) {
        $search["search_in"] = implode(',', $searchParams);
      }
      $manager->setSearch($search["search_string"], $search["search_in"], $search["search_type"]);

      $entities = $manager->getAll(implode(',', $searchParams), 5);

      foreach ($entities as $e) {
        $e = array_make_plain_with_dots($e);
        $suggestionData = "";
        foreach ($searchParams as $i => $sp) {
          if ($i == 0) {
            $suggestion = htmlspecialchars($e[$sp]);
          } else {
            $suggestionData .= htmlspecialchars($e[$sp]) . ', ';
          }
        }
        $suggestionData = trim($suggestionData);
        $suggestionData = rtrim($suggestionData, ',');
        $suggestions[] = json_encode($suggestion);
        $suggestionsData[] = json_encode($suggestionData);
      }
    }

    // Check for rpb invite
    if($_GET['type'] == 'subject' && isset($settings['invite_helpful_product_broadcast_subject'])) {

      $settings = ManagerHolder::get('Settings')->getAllKV();
      $rpbInvSubject = $settings['invite_helpful_product_broadcast_subject'];
      if(strpos($rpbInvSubject, $search["search_string"]) !== FALSE) {
        $suggestions[] = json_encode(htmlspecialchars($rpbInvSubject));
        $suggestionsData[] = json_encode("");
      }

    }

    $result = sprintf($result, $_GET["query"], implode(',', $suggestions), implode(',', $suggestionsData));
    die($result);
  }

  /**
   * View webversion
   */
  public function view_webversion($id) {

    $mbRecipient = ManagerHolder::get('MandrillBroadcastRecipient')->getById($id, 'e.*');
    if(empty($mbRecipient)) {
      show_404();
    }

    $contentData = ManagerHolder::get('EmailMandrill')->getContent($mbRecipient['mandrill_email_id']);
    if (empty($contentData['html'])) {
      set_flash_error('Не удалось получить html');
      redirect_to_referral();
    }

    die($contentData['html']);
  }

  /**
   * setViewParamsIndex
   * @param  $entities
   * @param  $pager
   * @param  $hasSidebar
   */
  protected function setViewParamsIndex(&$entities, &$pager, $hasSidebar) {
    $this->layout->set("processListUrl", $this->processListUrl);
    $this->layout->set("isDeleteAllAllowed", $this->isDeleteAllAllowed);
    $this->layout->set("isListSortable", $this->isListSortable);
    $this->layout->set("maxLines", $this->maxLines);
    unset($this->actions['add'], $this->actions['delete'], $this->actions['edit']);
    $this->layout->set("actions", $this->actions);
    $this->layout->set("hasSidebar", $hasSidebar);
    $this->layout->set("menuItems", $this->menuItems);
    $this->layout->set("defOrderBy", $this->defOrderBy);
    $this->layout->set("params", $this->listParams);
    $this->layout->set("fields", $this->fields);
    $this->layout->set("entities", $entities);
    $this->layout->set("pager", $pager);
    $this->layout->set("autocompleteEnabled", $this->autocompleteEnabled);
    $this->layout->set("listViewIgnoreLinks", $this->listViewIgnoreLinks);
    $this->layout->set("listViewLinksRewrite", $this->listViewLinksRewrite);
    $this->layout->set("listViewValuesRewrite", $this->listViewValuesRewrite);
    $this->layout->set("additionalPostParams", $this->additionalPostParams);
    $this->layout->set("additionalActions", $this->additionalActions);
    $this->layout->set("batchUpdateFields", $this->batchUpdateFields);
  }

}