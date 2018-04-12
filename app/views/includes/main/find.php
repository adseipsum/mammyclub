<div class="mobile-search-box search-box">
  <?
  $searchAction = site_url('поиск');
  $placeholder = 'Поиск по статьям...';
  if (is_shop()) {
    $searchAction = shop_url('поиск');
    $placeholder = 'Поиск по товарам...';
  }
  ?>
  <form method="get" action="<?=$searchAction;?>">
    <input class="search-field" type="text" name="q" placeholder="<?=$placeholder;?>" value="<?=isset($query)?$query:'';?>" />
    <input class="search-but" type="submit" />
  </form>
</div>
<style>
    .mobile-search-box {display:block;}
</style>