<table class="main-table-info" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td class="td-1">
      <div class="breadcrumbs">
        <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a></span>
        <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb" href="<?=site_url('консультации')?>"><span class="s-1"></span><span class="s-2" itemprop="title">Консультация</span><span class="s-3"></span></a></span>
      </div>
    </td>
    <td class="tar td-2">
      <a class="def-but orange-but" href="<?=site_url('задать-вопрос');?>">Задать свой вопрос</a>
    </td>
  </tr>
</table>

<?=html_flash_message();?>

<? if(!empty($admin)): ?>
  <div class="flash">
    <span class="close js-close-adm-msg"></span>
    <div class="message notice">
      <p>Вы вошли как администратор <strong><?=$admin['name']?></strong>. <a target="_blank" href="<?=admin_site_url('question/add_edit/' . $question['id']);?>">Редактировать эту страницу в админке</a></p>
    </div>
  </div>
<? endif; ?>

<div class="view-question">
  <h1 itemprop="headline"><?=$question['name'];?></h1>
  <p class="stats">
    <span itemprop="author" itemscope itemtype="http://schema.org/Person">
      <? if(!empty($question['user']['google_url'])): ?>
        <a itemprop="sameAs" href="<?=$question['user']['google_url'];?>"><span class="author" itemprop="name"><?=$question['user']['name']?></span></a> профиль в Google+,
      <? else: ?>
        <span class="author" itemprop="name"><?=$question['user']['name'];?></span>,
      <? endif; ?>
    </span>
    <span><?=ago($question['date']);?></span>
  </p>

  <? $this->view("includes/ad_slots/text_top_banner"); ?>

  <div class="html-content" itemprop="text">
    <?=$question['content'];?>
  </div>
</div>

<? $this->view("includes/ad_slots/text_bot_banner"); ?>

<? $this->view("includes/parts/comments", array('comments' => $comments, 'entityType' => 'Question', 'entityId' => $question['id']));?>

<? $this->view("includes/ad_slots/text_comments_bot_banner"); ?>

<div class="clear" style="height: 25px;"></div>
<? /*
<div class="register-big-box mini-box">
  <h2 class="title">
    Считаете эту консультацию полезной? Поделитесь ссылкой на нее через свою любимую социальную сеть:
  </h2>
  <div class="cont">
    <div class="content">
      <div class="tac">
        <script type="text/javascript" src="//yandex.st/share/share.js"charset="utf-8"></script>
        <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,odnoklassniki,moimir,moikrug,gplus"></div>
      </div>
      <p class="tac">
        Каждая ссылка на наши статьи помагает другим людям, которые нуждаются в этой информации, найти их с помощью социальных сетей и поиска Google или Яндекс
      </p>
    </div>
  </div>
</div>
*/?>

<script type="text/javascript">
  $(document).ready(function() {
    <? if($isLoggedIn == TRUE && $authEntity['auth_info']['email_confirmed'] == FALSE && isset($_GET['not_confirmed']) && $_GET['not_confirmed'] == TRUE): ?>
      $.ajaxp2OpenPopup('подтверждение-емейла');
    <? endif; ?>
  });
</script>