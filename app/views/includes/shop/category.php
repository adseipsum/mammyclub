<div class="shop-category-page">

  <? if(!empty($cartItems)): ?>
    <div class="mobile-basket-wrap"><a class="mobile-basket" href="<?=shop_url('оформить-заказ')?>"><span class="numb"><?=count($cartItems)?></span></a></div>
  <? endif; ?>
  <div class="mobile-dgp-wrap"><span class="mobile-dgp" data-ajaxp-url="<?=shop_url('аджакс/доставка-гарантия-оплата/');?>"></span></div>

  <? if(isset($filters) && !empty($filters)): ?>
    <div class="filter-icon-wrap"><span class="filter-icon js-filter-icon <?=!empty($_GET['filters'])?' active':'';?>" ></span></div>
  <? endif; ?>


  <div class="r-part">
    <? $this->view("includes/shop/parts/right_part"); ?>
  </div>
  <div class="l-part">
    <div class="inner-b">
      <div class="shop-page">

        <div class="category-breadcrumbs">
          <? $this->view("includes/shop/parts/top_block"); ?>
        </div>

        <?=html_flash_message(); ?>
        <? if(isset($_GET['product_not_in_stock'])): ?>
          <?=html_flash_message(array('error' => 'К сожалению, этого товара сейчас нет в наличии. Вы можете посмотреть аналогичные товары в категории ' . $currentCategory['name'])); ?>
        <? endif; ?>

        <? if (isset($currentCategory) && !empty($currentCategory)): ?>
          <h2 class="title-2"><?=$currentCategory['name'];?></h2>
        <? endif; ?>

        <? $this->view("includes/shop/parts/filters"); ?>

        <? if (!empty($products)): ?>
          <? $this->view("includes/shop/parts/product_block"); ?>
        <? else: ?>
          <p>Товары отсутствуют...</p>
        <? endif; ?>
        <div class="clear"></div>

        <? $this->view("includes/shop/parts/category_menu_block"); ?>

        <div>
          <? $this->view("includes/shop/parts/order_department_block"); ?>
          <div class="kick-it"></div>
        </div>

        <? $this->view("includes/shop/parts/bottom_block"); ?>

        <? if (!empty($currentCategory['content']) || isset($currentCategory['content'])): ?>
          <?=$currentCategory['content']?>
        <? endif; ?>

      </div>

    </div>
    <div class="clear"></div>
  </div>

</div>


