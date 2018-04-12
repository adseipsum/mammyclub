<div class="shop-index-page">
  <? if(!empty($cartItems)): ?>
    <div class="mobile-basket-wrap"><a class="mobile-basket" href="<?=shop_url('оформить-заказ')?>"><span class="numb"><?=count($cartItems)?></span></a></div>
  <? endif; ?>
  <div class="mobile-dgp-wrap"><span class="mobile-dgp" data-ajaxp-url="<?=shop_url('аджакс/доставка-гарантия-оплата/');?>"></span></div>

  <div class="r-part">
    <? $this->view("includes/shop/parts/right_part"); ?>
  </div>
  <div class="l-part">
    <div class="inner-b">
      <div class="shop-page">
        <? $this->view("includes/shop/parts/top_block"); ?>

        <?=html_flash_message();?>

        <? if (!empty($products)): ?>
          <h2 class="title-2">
            Выбор экспертов MammyClub в <?=lang('shop.month.' . date('n'));?>:
            <a href="<?=rtrim(shop_url(), '/') . '#comments';?>" style="float: right; font-size: 16px;">Отзывы о нашем магазине</a>
          </h2>
          <? $this->view("includes/shop/parts/product_block_index"); ?>
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

        <? $this->view("includes/shop/parts/comments_block", array('comments' => $comments, 'entityType' => 'Shop')); ?>
      </div>
    </div>
    <div class="clear"></div>
  </div>

</div>


