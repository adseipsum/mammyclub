<? if($pager->haveToPaginate() && $pager->getPage() != $pager->getLastPage()): ?>
  <<?=isset($root_tag) ? $root_tag : 'div'?> class="pager-wrap">
    <div id="loading-box">
      <a id="more-ajax-content" href="<?= pager_url($pager->getNextPage()); ?>">Показать ещё</a>
      <img id="loading-ajax-content" src="<?= site_img('preloader.gif')?>"/>
    </div>
  </<?=isset($root_tag) ? $root_tag : 'div'?>> 
<? endif; ?>