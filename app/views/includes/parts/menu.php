<?/*
  $weekLinks = array("беременность-по-неделям", "беременность-по-неделям1", "беременность-по-неделям2");
  $randKeys = array_rand($weekLinks, 1);
*/?>

<div class="menu-row">
  <div class="wrap">
    <ul class="menu-list">
      <? if (url_equals('/статьи')): ?>
        <li class="a active"><span>Статьи</span></li>
      <? elseif (url_contains('/статьи/') || url_contains('/статья/') || !empty($_GET['type']) && $_GET['type'] == 'article'): ?>
        <li class="a active"><a href="<?=site_url('статьи');?>">Статьи</a></li>
      <? else: ?>
        <li class="a"><a href="<?=site_url('статьи');?>">Статьи</a></li>
      <? endif; ?>

      <? if($isLoggedIn == TRUE): ?>
	     <? if($authEntity['newsletter'] || $authEntity['newsletter_first_year']): ?>
		    <? if (url_equals('/консультации')): ?>
          <li class="b active"><span>Консультация</span></li>
        <? elseif (url_contains('/консультация/') || !empty($_GET['type']) && $_GET['type'] == 'question'): ?>
          <li class="b active"><a href="<?=site_url('консультации');?>">Консультация</a></li>
        <? else: ?>
          <li class="b"><a href="<?=site_url('консультации');?>">Консультация</a></li>
        <? endif; ?>
       <? endif; ?>
      <? endif; ?>


      <? if (url_equals('беременность-по-неделям')): ?>
        <li class="c active"><span>Беременность по неделям</span></li>
      <? elseif (url_contains('беременность-по-неделям')): ?>
        <li class="c active"><a href="<?=site_url('беременность-по-неделям'); ?>">Беременность по неделям</a></li>
      <? else: ?>
        <li class="c"><a href="<?=site_url('беременность-по-неделям'); ?>">Беременность по неделям</a></li>
      <? endif; ?>


      <?/*
      <!-- A/B testing -->
      <? if (url_contains('беременность-по-неделям')): ?>
        <li class="c active"><a href="<?=site_url($weekLinks[$randKeys]); ?>">Беременность по неделям</a></li>
      <? else: ?>
        <li class="c"><a href="<?=site_url($weekLinks[$randKeys]); ?>">Беременность по неделям</a></li>
      <? endif; ?>
      */?>

      <? if (is_shop()): ?>
        <li class="d active last">
          <? if(url_equals('/')): ?>
            <span>Мамин Магазин</span>
          <? else: ?>
            <a href="<?=shop_url();?>">Мамин Магазин</a>
          <? endif; ?>
        </li>
      <? else: ?>
        <li class="d last"><a target="_blank" href="<?=shop_url();?>">Мамин Магазин</a></li>
      <? endif; ?>
    </ul>
    <div class="clear"></div>
  </div>
</div>

<div class="mobile-menu-row-2 js-mobile-menu-list">
  <ul data-breakpoint="800" class="menu-list-2 flexnav">
    <li class="a <?=url_contains('/статьи') || url_contains('/статья/') || !empty($_GET['type']) && $_GET['type']=='article'?' active':'';?>"><a href="<?=site_url('статьи')?>">Статьи</a></li>
    <? if($isLoggedIn == TRUE): ?>
	    <? if($authEntity['newsletter'] || $authEntity['newsletter_first_year']): ?>
        <li class="b <?=url_contains('/консультация') || !empty($_GET['type']) && $_GET['type']=='question'?' active':'';?>"><a href="<?=site_url('/консультации')?>">Консультация</a></li>
      <? endif; ?>
    <? endif; ?>

    <li class="c <?=url_contains('беременность-по-неделям') ? 'active' : ''?>"><a href="<?=site_url('беременность-по-неделям')?>">Беременность по неделям</a></li>

    <?/*
    <!-- A/B testing -->
    <li class="c <?=url_contains('беременность-по-неделям') ? 'active' : ''?>"><a href="<?=site_url($weekLinks[$randKeys])?>">Беременность по неделям</a></li>
    */?>

    <li class="d"><a  target="_blank" href="<?=shop_url('')?>">Мамин Магазин</a></li>
    <li class="d <?=url_contains('find') ? 'active' : ''?>"><a href="<?=site_url('find')?>">Поиск по сайту</a></li>


    <? if($isLoggedIn == FALSE): ?>
      <li class="e"><span data-ajaxp-url="<?=site_url('регистрация?type=in_top');?>" class="">Зарегистрироваться</span></li>
      <li class="f last"><span class="" data-ajaxp-url="<?=site_url('вход');?>">Войти</span></li>
    <? else: ?>
      <li class="f last"><span class="" data-ajaxp-url="<?=site_url('выход');?>">Выход</span></li>
    <? endif; ?>


  </ul>
</div>
