<? if (!$isLoggedIn || ($isLoggedIn && empty($authEntity['pregnancyweek_id']))): ?>
  <div class="tac">
    <a data-ajaxp-url="<?=site_url('аджакс/подписаться-на-неделю-беременности');?>" class="def-but orange-but subscribe-but">Подписаться</a>
  </div>    
<? endif; ?>



