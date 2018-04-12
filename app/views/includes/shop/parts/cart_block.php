<div class="border-box def-box small-cart-box">
  <table class="title-table" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td class="td-1"><h2 class="title"><?=$settings['right_part_cart_title'];?><?=isset($productsInCartAmount) ? " ({$productsInCartAmount}):" : ""; ?></h2></td>
      <td class="td-2"><img src="<?=site_img('cart_icon.png')?>" alt="Корзина" title="Корзина" /></td>
    </tr>
  </table>

  <? $total = 0; ?>
  <div class="cont">
    <? if (isset($cartItems) && !empty($cartItems)): ?>
      <ul class="cart-list">
        <? foreach ($cartItems as $cartItem): ?>
          <? for ($i = 1; $i <= $cartItem['qty']; $i++): ?>
            <li>
              <div class="img-box">
                <a href="<?=shop_url($cartItem['product']['page_url'])?>">
                  <? if (!empty($cartItem['product']['image'])): ?>
                    <img src="<?=site_image_thumb_url('_tiny', $cartItem['product']['image'])?>" alt="<?=$cartItem['product']['name']?>" title="<?=$cartItem['product']['name']?>" />
                  <? else: ?>
                    <img src="<?=site_img('no_good_icon_tiny.png')?>" alt="<?=$cartItem['product']['name'];?>" title="<?=$cartItem['product']['name'];?>" />
                  <? endif; ?>
                </a>
              </div>
              <div class="info">
                <a class="link" href="<?=shop_url($cartItem['product']['page_url'])?>"><?=$cartItem['product']['name'];?></a>
                <table class="snmall-price-table" cellspacing="0" cellpadding="0" border="0">
                  <tr>
                    <td class="td-1">
                      <b>
                        <? if (!empty($cartItem['product']['sale']['discount'])): ?>
                          <?=round($cartItem['price'] - $cartItem['price'] / 100 * $cartItem['product']['sale']['discount']);?>
                          <?$total += round($cartItem['price'] - $cartItem['price'] / 100 * $cartItem['product']['sale']['discount']);?>
                        <? else: ?>
                          <?=$cartItem['price'];?>
                          <?$total += $cartItem['price'];?>
                        <? endif; ?>
                      </b> грн.</td>
                    <td class="td-2"><a href="<?=shop_url('удалить-из-корзины/' . $cartItem['id']); ?>"><img src="<?=site_img('del_icon.png')?>" alt="Удалить" title="Удалить" /></a></td>
                  </tr>
                </table>
              </div>
              <div class="clear"></div>
            </li>
          <? endfor; ?>
        <? endforeach; ?>
      </ul>
    <? else: ?>
      <p style="text-align: center;">Корзина пуста</p>
    <? endif; ?>
    <? if ($total > 0): ?>
      <div class="total">Всего: <span class="price"><b><?=$total;?></b> грн.</span></div>
      <div class="tac"><a href="<?=shop_url('оформить-заказ')?>" class="def-but orange-but">Оформить заказ</a></div>
    <? endif; ?>
    <? if (!isset($cartItems) || empty($cartItems)): ?>
      <div class="distortion"><?=$settings['right_part_bottom_text'];?></div>
    <? endif; ?>

  </div>
</div>