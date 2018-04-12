<div class="breadcrumbs">
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a></span>
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb" href="<?=site_url('статьи')?>"><span class="s-1"></span><span class="s-2" itemprop="title">Статьи</span><span class="s-3"></span></a></span>
  <? if(!empty($parentCategories)): ?>
    <? foreach ($parentCategories as $pc): ?>
      <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb" href="<?=site_url($pc['page_url'])?>"><span class="s-1"></span><span class="s-2" itemprop="title"><?=$pc['name']?></span><span class="s-3"></span></a></span>
    <? endforeach; ?>
  <? endif; ?>
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="crumb active" itemprop="url" href="<?=site_url($article['category']['page_url']);?>"><span class="s-1"></span><span class="s-2" itemprop="title"><?=$article['category']['name'];?></span><span class="s-3"></span></a></span>
</div>

<? if (!empty($admin)): ?>
  <div class="flash">
    <span class="close js-close-adm-msg"></span>
    <div class="message notice">
      <p>Вы вошли как администратор <strong><?=$admin['name']?></strong>. <a target="_blank" href="<?=admin_site_url('article/add_edit/' . $article['id']);?>">Редактировать эту страницу в админке</a></p>
    </div>
  </div>
<? endif; ?>

<div class="view-item js-view-item">

  <table class="item-theme" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td>
        <h1 itemprop="headline"><?=$article['name'];?></h1>
      </td>
      <td class="tar">
        <a href="<?=site_url('распечатать-статью?id=' . $article['id']);?>" class="print"><span class="word">Распечатать</span></a>
      </td>
    </tr>
  </table>

  <? $this->view("includes/ad_slots/text_top_banner"); ?>

  <div class="html-content" itemprop="articleBody">
    <?=$article['content'];?>
    
    <!--     STATIC_SUBSCRIBE_PLACEHOLDER -->
    <div class="js-static-subscribe-placeholder"></div>

    <? /*
    <? if($isLoggedIn == FALSE): ?>
      <table class="share-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="td-1">Понравилась статья? Поделитесь ссылкой на нее через свою любимую социальную сеть:</td>
          <td class="td-2 tal">
            <script type="text/javascript" src="//yandex.st/share/share.js"charset="utf-8"></script>
            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareQuickServices="vkontakte,facebook,odnoklassniki,moimir,moikrug,gplus"></div>
          </td>
        </tr>
      </table>
    <? endif; ?>
    */?>
  </div>

  <? if($article['name'] == 'Рассылка Первый год жизни малыша' && (empty($authEntity) || !empty($authEntity) && $authEntity['newsletter_first_year'] == FALSE)): ?>
    <div class="register-big-box mini-box first-year-live">
      <h2 class="title">
        Подпишитесь на рассылку Первый год жизни прямо сейчас!
      </h2>
      <div class="cont">
        <form method="post" action="<?=site_url('первый-год-жизни/подписаться-на-рассылку');?>" class="js-validate pr">
          <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin: 15px 0 0 0;">
            <tr>
              <td>
                <label for="child_birth_date">Дата рождения ребенка:</label>
                <input class="js-date required" type="text" name="child_birth_date" id="child_birth_date" />
              </td>
              <td>
                <label for="child_sex">Пол ребенка:</label>
                <select name="child_sex" id="child_sex" class="required">
                  <option value="">- Пожалуйста Выберите -</option>
                  <option value="m">Мальчик</option>
                  <option value="f">Девочка</option>
                </select>
              </td>
              <td class="tar">
                <? if($isLoggedIn == FALSE): ?>
                  <span class="js-login h-but orange-but" data-ajaxp-url="<?=site_url('вход');?>" style="padding: 10px 35px;">Подписаться</span>
                <? else: ?>
                  <button type="submit" class="h-but orange-but" style="padding: 10px 35px;">Подписаться</button>
                <? endif; ?>
              </td>
            </tr>
          </table>
        </form>
        <script type="text/javascript">
          $(document).ready(function() {
            $('.js-validate').validate();
          });
        </script>
      </div>
    </div>
  <? endif; ?>

  <? if(!empty($article['author']['name'])):?>
    <p class="author-wrap aw-2">
      <span itemprop="author" itemscope itemtype="http://schema.org/Person">
        <b>Автор:</b>
        <? if (!empty($article['author']['google_url'])):?>
          <span class="author"><a itemprop="sameAs" href="<?=$article['author']['google_url'];?>"><span itemprop="name"><?=$article['author']['name'];?></span></a> в Google+</span>
        <? else: ?>
          <span itemprop="name" class="author"><?=$article['author']['name'];?></span>
        <? endif; ?>
      </span>
      <?/*&nbsp;&nbsp;&nbsp;<b>Дата публикации:</b> <time itemprop="datePublished" datetime="<?=$article['date'];?>"><?=convert_date($article['date'], "j F Y", false, true);?></time>*/?>
    </p>
  <? endif; ?>
  <? if (!empty($article['author']['additional_url'])): ?>
    <a id="js-additional-link" href="<?=$article['author']['additional_url'];?>"><?=$article['author']['name'];?></a>
  <? endif; ?>

  <? $this->view("includes/ad_slots/text_bot_banner"); ?>

  <? $this->view("includes/parts/comments", array('comments' => $comments, 'entityType' => 'Article', 'entityId' => $article['id'])); ?>

  <div class="kick-it"></div>

  <? $this->view("includes/ad_slots/text_comments_bot_banner"); ?>
</div>


<script type="text/javascript">
  $(window).load(function() {
    $('.html-content img').each(function() {
      if ($(this).attr('src').indexOf('smile') > 0)  {
        $(this).addClass('smile');
      }
    });
  });

  <? if (!empty($article['author']['additional_url'])): ?>
    $(document).ready(function() {
      $('#js-additional-link').hide();
    });
  <? endif; ?>
</script>