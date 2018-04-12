<div class="private-area">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <? $this->view("includes/private/parts/subscribe-box"); ?>

  <div class="private-def-box">
    <h1 class="title">Мои вопросы</h1>
    <? if(!empty($questions)): ?>
      <ul class="qa-list">
        <? foreach ($questions as $q): ?>
          <li>
            <p><span class="date"><?=ago($q['date']);?></span></p>
            <a href="<?=site_url($q['page_url']);?>"><?=truncate(strip_tags($q['content']), 300, '...');?></a>
          </li>
        <? endforeach; ?>
      </ul>
    <? else: ?>
      <p class="no-data">Вы еще не задали ни одного вопроса.</p>
    <? endif; ?>
    <div class="tac bottom"><a class="def-but green-but" href="<?=site_url('задать-вопрос');?>">Задать свой вопрос</a></div>
  </div>

</div>