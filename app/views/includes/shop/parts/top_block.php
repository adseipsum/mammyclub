<? if (url_contains('продукт')): ?>
  <div class="breadcrumbs">
    <? if (!empty($prevCategory)): ?>
      <a href="<?=shop_url($prevCategory['page_url'])?>"><?=$prevCategory['name']?></a>
    <? else: ?>
      <a href="<?=shop_url('/')?>"><img src="<?=site_img('home_icon.png')?>" /></a>
    <? endif; ?>
    <span class="next">&gt;</span>
    <a href="<?=shop_url($currentCategory['page_url'])?>"><?=$currentCategory['name']?></a>
  </div>
<? else:?>
  <? if (!url_equals('/')): ?>
    <div class="breadcrumbs">
      <? if (!empty($prevCategory1) || !empty($prevCategory2)): ?>
        <? if (!empty($prevCategory1)): ?>
          <a href="<?=shop_url($prevCategory1['page_url'])?>"><?=$prevCategory1['name']?></a>
        <? else: ?>
          <a href="<?=shop_url('/')?>"><img src="<?=site_img('home_icon.png')?>" /></a>
        <? endif; ?>
        <span class="next">&gt;</span>
        <a href="<?=shop_url($prevCategory2['page_url'])?>"><?=$prevCategory2['name']?></a>
      <? else: ?>
        <a href="<?=shop_url('/')?>"><img src="<?=site_img('home_icon.png')?>" /></a>
      <? endif; ?>
    </div>
  <? endif; ?>
<? endif; ?>                                                                                                                                                                                                                                   