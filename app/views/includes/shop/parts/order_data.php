<thead>
  <tr>
    <th>Товар</th>
    <th class="th-2">Цена<br/><span class="small">(грн. с НДС)</span></th>
    <th class="th-3">Кол-во<br/><span class="small">(шт.)</span></th>
    <th class="th-4 tar">Сумма</th>
  </tr>
</thead>
<? $cartTotal = 0; ?>
<? if (!empty($cartItems)): ?>
  <? foreach($cartItems as $cartItem): ?>
    <tr class="js-tr" <?=js_data_fields_product($cartItem['product']);?>>
      <td class="td-1">
        <div class="js-images">

          <?
            $mainParamValue = NULL;
            $hideMainImg = FALSE;
            if (is_not_empty($cartItem['product']['parameter_groups']) && !empty($cartItem['additional_product_params'])) {
              foreach ($cartItem['product']['parameter_groups'] as $group) {
                if($group['main_parameter_value_id'] == $cartItem['additional_product_params'][0]) {
                  $mainParamValue = $cartItem['additional_product_params'][0];
                  if(!empty($group['image'])) {
                    $hideMainImg = TRUE;
                  }
                  break;
                }
              }
            }
          ?>

          <? // Main image ?>
          <? if (isset($cartItem['product']['image']) && !empty($cartItem['product']['image'])): ?>
            <div class="in">
              <a <?= $hideMainImg ? 'style="display: none;" ' : '';?>class="js-fancy-img link js-main-img" href="<?=site_image_url($cartItem['product']['image']);?>">
                <img class="prod-img" src="<?=site_image_thumb_url('_tinycart', $cartItem['product']['image']);?>" alt="<?=$cartItem['product']['name'];?>" title="<?=$cartItem['product']['name'];?>"/>
                <span class="zoom"><img src="<?=site_img('zoom_icon.png');?>" /></span>
              </a>
            </div>
          <? else: ?>
            <a <?= $hideMainImg ? 'style="display: none;" ' : '';?>class="link js-main-img js-no-pic-link" href="">
              <img class="prod-img" src="<?=site_img('no_good_icon_cart_tiny.png');?>" alt="<?=$cartItem['product']['name'];?>" title="<?=$cartItem['product']['name'];?>"/>
            </a>
          <? endif; ?>

          <? // Params images ?>
          <? if (is_not_empty($cartItem['product']['parameter_groups'])): ?>
            <? foreach ($cartItem['product']['parameter_groups'] as $group): ?>
              <? if(!empty($group['image'])): ?>
                <div class="in">
                  <a class="link js-param-img js-fancy-img" href="<?=site_image_url($group['image']);?>" param-img="<?=$group['main_parameter_value_id'];?>"<?=$group['main_parameter_value_id']!=$mainParamValue?' style="display: none;"':'';?>>
                    <img class="prod-img" src="<?=site_image_thumb_url('_tinycart', $group['image']);?>" alt="<?=$cartItem['product']['name'];?>" title="<?=$cartItem['product']['name'];?>"/>
                    <span class="zoom"><img src="<?=site_img('zoom_icon.png');?>" /></span>
                  </a>
                </div>
              <? endif; ?>
            <? endforeach; ?>
          <? endif; ?>

        </div>

        <div class="tiny-info">
          <a class="name js-name" id="<?=$cartItem['id'];?>" href="<?=shop_url($cartItem['product']['page_url']);?>"><?=$cartItem['product']['name'];?></a>


          <? if (is_not_empty($cartItem['product']['possible_parameters'])): ?>

            <div id="<?=$cartItem['id']?>" class="js-param-box param-box">
              <?
                $paramTypes = array(1 => 'main', 2 => 'secondary');
                $groupWithSelectedMainParamValue = array();
              ?>

              <? foreach ($paramTypes as $k => $type): ?>

                <? if (is_not_empty($cartItem['product']['possible_parameters']['parameter_' . $type]) && is_not_empty($cartItem['product']['possible_parameters']['possible_parameter_values'])): ?>
                  <div class="valid-row js-valid-row">
                    <span class="attention js-attention"></span>
                    <p class="title"><?=$cartItem['product']['possible_parameters']['parameter_' . $type]['name'];?></p>
                    <select class="js-select-param<?=$k;?>">

                      <? // $cartItem['additional_product_params'][0] - element with zero index always contain name of parameter's category ?>
                      <? if (!is_not_empty($cartItem['additional_product_params'][$k-1])): ?>
                        <option value="">- Выберите -</option>
                      <? endif; ?>

                      <? foreach ($cartItem['product']['parameter_groups'] as $paramGroup): ?>
                        <? if($type == 'main'): ?>
                          <?
                            $mainParamValueSelected = FALSE;
                            if(is_not_empty($cartItem['additional_product_params'][$k-1])) {
                              if($paramGroup['main_parameter_value']['id']==$cartItem['additional_product_params'][$k-1]) {
                                $mainParamValueSelected = TRUE;
                                $groupWithSelectedMainParamValue = $paramGroup;
                              }
                            }
                          ?>
                          <option
                            value="<?=$paramGroup['main_parameter_value']['id'];?>"
                            <?=$mainParamValueSelected==TRUE?' selected="selected"':'';?>
                            class="<?=$paramGroup['id'];?>"><?=$paramGroup['main_parameter_value']['name'];?><?=$cartItem['product']['not_in_stock']==TRUE?' (под заказ)':''?></option>
                        <? else: ?>
                          <? if(!empty($groupWithSelectedMainParamValue) && !empty($paramGroup['secondary_parameter_values']) && $paramGroup['id'] == $groupWithSelectedMainParamValue['id']): ?>
                            <? foreach ($paramGroup['secondary_parameter_values'] as $secondaryParamValue): ?>
                              <option
                                value="<?=$secondaryParamValue['id'];?>"
                                <?=$secondaryParamValue['id']==$cartItem['additional_product_params'][$k-1]?' selected="selected"':'';?>
                                class="<?=$paramGroup['id'];?>"><?=$secondaryParamValue['name'];?><?=$cartItem['product']['not_in_stock']==TRUE?' (под заказ)':''?></option>
                            <? endforeach; ?>
                          <? endif; ?>
                        <? endif; ?>
                      <? endforeach; ?>
                    </select>
                  </div>
                <? endif; ?>
              <? endforeach;?>

            </div>
          <? endif; ?>
        </div>

      </td>
      <td class="td-2">
        <? if (is_not_empty($cartItem['sale'])): ?>
          <span class="old-price"><span class="js-old-price"><?=$cartItem['price'];?></span> грн.</span>
          <span class="real-price"><b class="js-real-price"><?=$cartItem['discount_price'];?></b> грн.</span>
          <span class="sale-end">Акция действует до<br /><?=date('H:i d.m.Y', strtotime($cartItem['product']['sale']['ends_at']));?></span>
        <? else: ?>
          <span class="real-price"><b class="js-real-price"><?=$cartItem['price'];?></b> грн.</span>
        <? endif; ?>
      </td>
      <td class="td-3 js-td">
        <input type="text" class="qty js-qty" value="<?=$cartItem['qty'];?>"/>
        <span class="a-like refresh js-refresh">Обновить</span>
      </td>
      <td class="td-4 tar">
        <span class="js-item-total"><?=round($cartItem['item_total']);?></span>
        <a class="js-remove-from-cart" href="<?=shop_url('удалить-товар-из-корзины/' . $cartItem['id']);?>">
          <img src="<?=site_img('delete_icon.png')?>" alt="" title=""/>
        </a>
      </td>
    </tr>
  <? endforeach; ?>
  <tr>
    <? if ($cart['total'] != 0): ?>
    <td class="td-price" colspan="4"><b><?=$delivery['name'];?>:</b> <span><?=!empty($delivery['price'])?$delivery['price']:'Бесплатная';?></span></td>
    <? endif; ?>
  </tr>
  <tr>
    <? if (empty($delivery['price']) || $cart['total'] == 0): ?>
    <td class="td-price" colspan="4"><b>Итого:</b> <span class="js-cart-total"><?=!empty($cart['total'])?$cart['total']:'';?></span></td>
		<? else: ?>
    <td class="td-price" colspan="4"><b>Итого:</b> <span class="js-cart-total"><?=!empty($cart['total'])?$cart['total']+$delivery['price']:'';?></span></td>
    <?endif; ?>
  </tr>
<? else: ?>
  <tr>
    <td colspan="4"><b>Корзина пуста, посетите наш <a href="<?=shop_url('')?>">магазин</a></b></td>
  </tr>
<? endif;?>