<? if (!empty($categories)): ?>
  <div class="menu-row menu-row-shop">
    <div class="wrap">
      <ul class="sf-menu" id="js-sub-shop-menu">
      	<? foreach ($categories as $category): ?>
        	<? if ($category['published'] == TRUE): ?>
          	<li <?=url_contains($category['page_url']) ? 'class="active"' : ''?> <? if (url_contains('продукт')): ?><?=$category['id'] == $currentCategory['root_id'] ? 'class="active"' : ''?><? endif; ?>>
          	  <? if (isset($category['__children']) && !empty($category['__children'])): ?>
                <a <?=$category['name'] == 'SALE' ? 'style="color:red;"' : '' ?> href="<?=shop_url($category['page_url'])?>"><?=$category['name'];?></a>
                <ul>
                  <? foreach ($category['__children'] as $childCategory): ?>
                    <? if ($childCategory['published'] == TRUE): ?>
                      <li <?=url_contains($childCategory['page_url']) ? 'class="active"' : ''?>  <? if (url_contains('продукт')): ?><?=$childCategory['id'] == $product['category_id'] ? 'class="active"' : ''?><? endif; ?>>
                        <a href="<?=shop_url($childCategory['page_url']);?>"><?=$childCategory['name']?></a>
                        <? if (!empty($childCategory['__children'])):?>
                          <ul>
                            <? foreach ($childCategory['__children'] as $ccCategory): ?>
                              <? if ($ccCategory['published'] == TRUE): ?>
                                <li <?=url_contains($ccCategory['page_url']) ? 'class="active"' : ''?>  <? if (url_contains('продукт')): ?><?=$ccCategory['id'] == $product['category_id'] ? 'class="active"' : ''?><? endif; ?>>
                                  <a href="<?=shop_url($ccCategory['page_url'])?>"><?=$ccCategory['name']?></a>
                                </li>
                              <? endif;?>
                            <? endforeach;?>
                          </ul>
                        <? endif;?>
                      </li>
                    <? endif;?>
                  <? endforeach; ?>
                </ul>
              <? else:?>
                <a <?=$category['name'] == 'SALE' ? 'style="color:red;"' : '' ?> href="<?=shop_url($category['page_url']);?>"><?=$category['name'];?></a>
              <? endif; ?>
          	</li>
          <? endif; ?>
       <? endforeach; ?>
            <li><a target="_blank" href="<?=site_url('/')?>" style="font-weight: bold;">MammyClub</a></li>
      </ul>
    </div>
  </div>

  <script type="text/javascript">
  	$(document).ready(function(){
  		$('#js-sub-shop-menu').superfish({
  			delay: 300,
  			speed: 'fast'
  		});
  	});
  </script>
<? endif; ?>


<div class="mobile-menu-row-2 js-mobile-menu-list">
  <ul data-breakpoint="800" class="menu-list-2 flexnav">
    <? $this->view("includes/shop/parts/category_top_menu"); ?>
    <li class="d"><a target="_blank" href="<?=site_url('')?>">MammyClub</a></li>
    <li class="d"><a href="<?=shop_url('find')?>">Поиск по магазину</a></li>

    <? if($isLoggedIn == FALSE): ?>
      <li class="e"><span data-ajaxp-url="<?=site_url('регистрация?type=in_top');?>" class="">Зарегистрироваться</span></li>
      <li class="f last"><span class="" data-ajaxp-url="<?=site_url('вход');?>">Войти</span></li>
    <? else: ?>
      <li class="f last"><span class="" data-ajaxp-url="<?=site_url('выход');?>">Выход</span></li>
    <? endif; ?>

  </ul>
</div>