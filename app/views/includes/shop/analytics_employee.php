<? if(!empty($cartItems)): ?>
  <a class="mobile-basket" href="<?=shop_url('оформить-заказ')?>"><span class="numb"><?=count($cartItems)?></span></a>
<? endif; ?>
<div class="r-part">
  <? $this->view("includes/shop/parts/right_part"); ?>
</div>
<div class="l-part">
  <div class="inner-b">
    <h1>Test Analytics Employee Page</h1>
  </div>
  <div class="clear"></div>
</div>

