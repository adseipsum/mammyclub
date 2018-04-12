<div class="private-area">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <? $this->view("includes/private/parts/subscribe-box"); ?>

  <? if (isset($nextPregnancyArticleNotice) && !empty($nextPregnancyArticleNotice)): ?>
    <div class="next-letter"><?=$nextPregnancyArticleNotice;?></div>
  <? endif; ?>

  <div class="private-def-box">
    <h1 class="title">&quot;Моя беременность по неделям&quot;</h1>
    <? if ($pregnancyWeekSeted): ?>
      <? if (!empty($articles)): ?>
        <ul class="qa-list">
          <? foreach ($articles as $article): ?>
            <li>
              <a href="<?=site_url($article['page_url']);?>">Статья &quot;<?=$article['name'];?>&quot;</a>
            </li>
          <? endforeach; ?>
        </ul>
      <? endif; ?>
    <? else: ?>
      <div class="html-content private-pregn-box">
        <p>Это эксклюзивная e-mail рассылка, каждая статья которой – это абсолютно все о развитии малыша и изменениях в Вашем организме на этой неделе беременности.</p>
        <ul>
          <li>Каждая статья содержит рекомендации относительно посещения врачей, питания, физической активности.</li>
          <li>Обязательно присутствуют советы психолога, которые помогут легче перенести беременность.</li>
          <li>Вы узнаете, что переживает будущий папа и как с этим быть.</li>
        </ul>
        <p>Здесь будут собраны все статьи о неделях беременности, которые Вы уже прошли.</p>
      </div>
      <div class="tac">
        <? if (!isset($pregnancyArticles)): ?>
          <a class="def-but green-but" href="<?=site_url('беременность-по-неделям');?>">Узнать подробнее и подписаться</a>
        <? endif; ?>
      </div>
    <? endif; ?>
  </div>

</div>