<div class="select-wrap" style="margin-top: 10px;">
  <label class="label" for="siteorder_add_cart_item">Добавить к заказу</label>

  <select id="new-product-select" style="width: 250px;" data-placeholder="Выберите товар">
    <option value=""></option>
    <? foreach ($products as $product): ?>
      <? 
        $discount = '';
        $discountPrice = '';
        if (isset($product['sale']['discount']) && !empty($product['sale']['discount'])) {
          $discountPrice = round($product['price'] - $product['price'] / 100 * $product['sale']['discount']);
          $discount = $product['sale']['discount'];
        }
      ?>
      <option id="<?=$product['id'];?>" 
              data-price="<?=$product['price'];?>" 
              data-discount-price="<?=$discountPrice;?>"
              data-discount="<?=$discount;?>"><?=$product['name'];?></option>
    <? endforeach; ?>
  </select>

  <input id="new-product-qty" type="number" value="1" style="display: none;" />

  <span id="product-price" style="display: none; margin: 0 10px;">
    Цена: <span></span>
  </span>
  <span id="product-discount" style="display: none; margin: 0 10px 0 0;">
    Скидка: <span></span>%
  </span>
  <span id="product-discount-price" style="display: none;">
    Цена со скидкой: <span></span>
  </span>

  <div class="new-product-params-wrap" style="margin-bottom: 15px; margin-top: 15px;">
    <div class="new-product-param1-box">
      <label class="new-product-param1-label">Label</label>
      <select style="width: 250px; margin-bottom: 15px;" class="new-product-param1-select" data-placeholder="Выберите параметр">
        <option></option>
      </select>
    </div>

    <div class="new-product-param2-box">
      <label class="new-product-param2-label">Label</label>
      <select style="width: 250px;" class="new-product-param2-select" data-placeholder="Выберите параметр">
        <option></option>
      </select>
    </div>
  </div>

  <button class="button new-product-save-params" style="display:none;">
    <img alt="Добавить" src="http://localhost/web/images/admin/icons/tick.png">
    Добавить товар
  </button>
</div>
<div class="clear"></div>