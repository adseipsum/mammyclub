<? if (!empty($cItems)): ?>
  <div class="js-service js-product-id-<?=implode('-', get_array_vals_by_second_key($cItems, 'product_id'));?> js-product-price-<?=array_sum(get_array_vals_by_second_key($cItems, 'price'));?>" style="display: none;"></div>
  <div class="js-service-name js-product-name-<?=implode('-', get_array_vals_by_second_key($siteOrderItem, 'product', 'name'));?> " style="display: none;"></div>
  <div class="js-service-category js-product-category-<?=implode('-', get_array_vals_by_third_key($siteOrderItem, 'product', 'category', 'name'));?>" style="display: none;"></div>
<? endif; ?>
<div class="r-part">
  <? $this->view("includes/shop/parts/right_part"); ?>
</div>
<div class="l-part">
  <div class="inner-b">
    <?=$content;?>
  </div>
  <div class="clear"></div>
</div>