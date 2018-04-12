<div class="private-area">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <? $this->view("includes/private/parts/subscribe-box"); ?>

  <div class="private-def-box">
    <h1 class="title">Прочитанные статьи</h1>
    <? if(!empty($articles)): ?>
      <ul class="qa-list">
        <? foreach ($articles as $a): ?>
          <li>
            <p><span class="date"><?=ago($a['created_at']);?></span></p>
            <a href="<?=site_url($a['Article']['page_url']);?>"><?=$a['Article']['name'];?></a>
          </li>
        <? endforeach; ?>
      </ul>
    <? else: ?>
      <p class="no-data">Вы еще не просмотрели ни одной статьи.</p>
    <? endif; ?>
    <div class="tac bottom"><a class="def-but green-but" href="<?=site_url('статьи');?>">Перейти в раздел “Статьи”</a></div>
  </div>

</div>