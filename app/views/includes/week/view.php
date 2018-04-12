<div id="center" class="wrap">
  <? //SIDE BLOCK START ?>
  <div class="r-wide-part">

    <? $this->view("includes/ad_slots/right_banner"); ?>

  </div>
  <? //SIDE BLOCK END ?>

  <div class="l-short-part">
    <? if(!empty($admin)): ?>
      <div class="flash">
        <span class="close js-close-adm-msg"></span>
        <div class="message notice">
          <p>Вы вошли как администратор <strong><?=$admin['name']?></strong>. <a target="_blank" href="<?=admin_site_url('pregnancyarticle/add_edit/' . $article['id']);?>">Редактировать эту страницу в админке</a></p>
        </div>
      </div>
    <? endif; ?>
    <div class="view-item inner-b js-view-item">
      <table class="item-theme" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td>
            <h1 itemprop="headline"><?=$article['name'];?></h1>
          </td>
          <!--
          <td class="tar">
            <a href="<?=site_url('распечатать-статью?id=' . $article['id']);?>" class="print"><span class="word">Распечатать</span></a>
          </td>
           -->
        </tr>
      </table>

      <? $this->view("includes/ad_slots/text_top_banner"); ?>

      <div class="html-content" itemprop="articleBody">
        <?=$article['content'];?>
      </div>

      <!--     STATIC_SUBSCRIBE_PLACEHOLDER -->
      <div class="js-static-subscribe-placeholder"></div>
      
      <? if(!empty($article['author']['name'])):?>
        <p class="author-wrap">
          <span itemprop="author" itemscope itemtype="http://schema.org/Person">
            <b>Автор:</b>
            <? if (!empty($article['author']['google_url'])):?>
              <span class="author"><a itemprop="sameAs" href="<?=$article['author']['google_url'];?>"><span itemprop="name"><?=$article['author']['name'];?></span></a> в Google+</span>
            <? else: ?>
              <span class="author" itemprop="name"><?=$article['author']['name'];?></span>
            <? endif; ?>
          </span>
        </p>
      <? endif; ?>

      <? $this->view("includes/ad_slots/text_bot_banner"); ?>

  </div>

</div>