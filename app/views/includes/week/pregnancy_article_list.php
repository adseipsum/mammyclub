<div id="center" class="wrap">

  <div class="private-def-box">
    <h1 class="title">Документация</h1>
    <? if(!empty($pregnancyArticles)): ?>
      <ul class="qa-list">
        <? foreach ($pregnancyArticles as $pa): ?>
          <li>
            <a href="<?=site_url($pa['page_url']);?>"><?=$pa['name']?></a>
          </li>
        <? endforeach; ?>
      </ul>
    <? endif; ?>
  </div>

</div>