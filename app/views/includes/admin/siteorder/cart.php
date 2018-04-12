<?
  $tableFields = array('product.product_code',
                       'product.name',
                       'additional_product_params',
                       'price',
                       'discount_price',
                       'qty',
                       'delivery_status',
                       'item_total');
  $trTdStyle = 'border: 1px solid black; padding: 10px;';
?>
<input type="hidden" id="js-siteorder-id" value="<?=$cart['siteorder']['id']?>">

<div id="js-site-order-cart-preloader" style="display: none;">
  <img src="<?=site_img('preloader.gif')?>">
</div>

<table id="cart-data" style="border-collapse: collapse;">
  <tr>
    <td style="<?=$trTdStyle;?>">Код товара</td>
    <td style="<?=$trTdStyle;?>">Товар</td>
    <td style="<?=$trTdStyle;?>">Параметры</td>
    <td style="<?=$trTdStyle;?>">Цена</td>
    <td style="<?=$trTdStyle;?>">Цена с учетом скидки</td>
    <td style="<?=$trTdStyle;?>">Количество</td>
    <td style="<?=$trTdStyle;?>">ZAMMLER</td>
    <td style="<?=$trTdStyle;?>">Stock MC</td>
    <td style="<?=$trTdStyle;?>">Склад поставщика</td>
    <td style="<?=$trTdStyle;?>">Резерв</td>
    <td style="<?=$trTdStyle;?>">Срок доставки</td>
    <td style="<?=$trTdStyle;?>">Всего</td>
    <td style="<?=$trTdStyle;?>">Действия</td>
  </tr>

  <? if(is_not_empty($cart['items'])): ?>
   <? foreach ($cart['items'] as $cartItem): ?>
     <tr class="product-row">

       <td class="product.product_code" style="<?=$trTdStyle;?>"><?=$cartItem['product']['product_code'];?></td>
       <td class="product.name" style="<?=$trTdStyle;?>"><a href="<?=shop_url($cartItem['product']['page_url']);?>" target="_blank"><?=$cartItem['product']['name'];?></a></td>

       <td style="<?=$trTdStyle;?>">
         <? if (is_not_empty($cartItem['product']['possible_parameters'])): ?>
         <?
           $paramTypes = array(1 => 'main', 2 => 'secondary');
           $groupWithSelectedMainParamValue = array();
         ?>
         <? foreach ($paramTypes as $k => $type): ?>

           <? if (is_not_empty($cartItem['product']['possible_parameters']['parameter_' . $type]) && is_not_empty($cartItem['product']['possible_parameters']['possible_parameter_values'])): ?>
             <select data-placeholder="Выберите, пожалуйста" class="param<?=$k;?>-select" style="width: 200px;"  disabled="disabled" readonly="readonly">

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
                     title="<?=!empty($paramGroup['price'])?$paramGroup['price']:$cartItem['product']['price'];?>" value="<?=$paramGroup['main_parameter_value']['id'];?>"
                     <?=$mainParamValueSelected==TRUE?' selected="selected"':'';?>
                     class="<?=$paramGroup['id'];?>"><?=$paramGroup['main_parameter_value']['name'];?></option>
                 <? else: ?>
                   <? if(!empty($groupWithSelectedMainParamValue) && !empty($paramGroup['secondary_parameter_values']) && $paramGroup['id'] == $groupWithSelectedMainParamValue['id']): ?>
                     <? foreach ($paramGroup['secondary_parameter_values'] as $secondaryParamValue): ?>
                       <option
                         value="<?=$secondaryParamValue['id'];?>"
                         <?=$secondaryParamValue['id']==$cartItem['additional_product_params'][$k-1]?' selected="selected"':'';?>
                         class="<?=$paramGroup['id'];?>"><?=$secondaryParamValue['name'];?></option>
                     <? endforeach; ?>
                   <? endif; ?>
                 <? endif; ?>
               <? endforeach; ?>
             </select>
           <? endif; ?>
         <? endforeach;?>
         <a class="js-save-params" href="<?=site_url(ADMIN_BASE_ROUTE.'/cartitem/ajax_save_cart_item_params/'.$cartItem['id']);?>" style="font-size: 10px; display: none; margin-left: 5px;">save</a>
         <? endif; ?>
       </td>


       <td class="price" style="<?=$trTdStyle;?>"><?=$cartItem['price'];?></td>
       <td class="discount_price" style="<?=$trTdStyle;?>"><?=$cartItem['discount_price'] == 0 ? '' : $cartItem['discount_price'] ;?></td>
       <td style="<?=$trTdStyle;?>">
         <input class="js-qty-input" data-cart-item-id="<?=$cartItem['id'];?>" type="number" value="<?=$cartItem['qty'];?>" readonly="readonly"/>
       </td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['zammler_inventory_qty'];?></td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['mc_inventory_qty'];?></td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['other_stores_inventory_qty'];?></td>
       <td style="<?=$trTdStyle;?>">
         <? if (isset($cartItem['reserves']) && !empty($cartItem['reserves']) && !empty($cartItem['reserves']['qty'])) : ?>
           <? $reserveTitle = 'Забронированно (' . $cartItem['reserves']['qty'] . ')'; ?>
         <? else : ?>
           <? $reserveTitle = 'Бронь отсутствует'; ?>
         <? endif; ?>
         <? if ($siteOrderStatus['is_cancel_reserve_status'] != TRUE) : ?>
           <a data-ajaxp-url="<?=site_url(ADMIN_BASE_ROUTE.'/siteorder/ajax_reserve/' . $cartItem['id']);?>"><?=$reserveTitle?></a>
         <? else : ?>
            <span style="color: #5A5858"><?=$reserveTitle?></span>
         <? endif; ?>
       </td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['delivery_time'];?></td>
       <td class="item-total" style="<?=$trTdStyle;?>"><?=$cartItem['item_total'];?></td>
       <td style="<?=$trTdStyle;?>">
         <? if (!empty($cartItem['supplier_request_item'])) : ?>
           <? if ($cartItem['supplier_request_item']['supplier_request']['status'] == 'delivered_to_zammler') : ?>
             <div class="">
               Доставленно (<a target="_blank" href="<?=admin_site_url('supplierrequest/add_edit/' . $cartItem['supplier_request_item']['supplier_request']['id'])?>"><?=isset($stores[$cartItem['supplier_request_item']['supplier_request']['store_id']]) ? $stores[$cartItem['supplier_request_item']['supplier_request']['store_id']] : ''?> №<?=$cartItem['supplier_request_item']['supplier_request']['code']?> <?=$cartItem['supplier_request_item']['supplier_request']['execution_date']?></a>)
             </div>
           <? else : ?>
             <div class="js-table-actions-remove">
               <input class="js-cart-supplier-request-id" type="hidden" name="supplier_request_id" value="<?=$cartItem['supplier_request_item']['supplier_request']['id']?>">
               <input class="js-siteorder-item-id" type="hidden" name="siteorder_item_id" value="<?=$cartItem['id']?>">
               <a class="js-show-remove-supplier-requests a-like">Удалить из заявки "<?=isset($stores[$cartItem['supplier_request_item']['supplier_request']['store_id']]) ? $stores[$cartItem['supplier_request_item']['supplier_request']['store_id']] : ''?> №<?=$cartItem['supplier_request_item']['supplier_request']['code']?> <?=$cartItem['supplier_request_item']['supplier_request']['execution_date']?>"</a>
             </div>
           <? endif; ?>
         <? endif; ?>

         <div class="js-table-actions" <?= !empty($cartItem['supplier_request_item']) ? 'style="display:none;"' : '' ?>>
           <a class="js-show-supplier-requests a-like">Добавить в заявку поставщику</a>
           <a class="js-show-supplier-create a-like">Создать заявку поставщику</a>
         </div>

         <div class="js-table-actions-create" style="display: none;">
           <input class="js-siteorder-item-id" type="hidden" name="siteorder_item_id" value="<?=$cartItem['id']?>">
           Поставщик:
           <select class="js-store-id">
             <option value="">-- Выберите поставщика --</option>
             <? foreach ($stores as $id => $name) : ?>
               <option value="<?=$id?>"><?=$name?></option>
             <? endforeach; ?>
           </select><br/>
           Дата оформления:
           <input class="js-execution-date js-date" type="text" name="execution_date" style="width: 65px;"><br>
           <a class="js-supplier-request-create-button">Создать</a>
           <br><br>
           <a class="js-hide-supplier-requests"> << Назад</a>
         </div>

         <div class="js-supplier-requests" style="display: none;">
           <input class="js-siteorder-item-id" type="hidden" name="siteorder_item_id" value="<?=$cartItem['id']?>">
           <select name="test" class="js-supplier-requests-select">
             <option value="">-- Выберите заявку --</option>
             <? foreach ($supplierNewRequests as $supplierNewRequest) : ?>
               <option value="<?=$supplierNewRequest['id']?>"><?=isset($stores[$supplierNewRequest['store_id']]) ? $stores[$supplierNewRequest['store_id']] : ''?> №<?=$supplierNewRequest['code']?> <?=$supplierNewRequest['execution_date']?></option>
             <? endforeach; ?>
           </select>

           <a class="js-hide-supplier-requests"> << Назад</a>
         </div>

         <div class="js-preloader" style="display: none;">
           <img src="<?=site_img('preloader.gif')?>">
         </div>
       </td>
       <td>
         <a class="js-delete-row" href="<?=site_url(ADMIN_BASE_ROUTE.'/siteorderitem/delete/'.$cartItem['id']);?>">
           <img style="margin-left: 10px; cursor: pointer;" src="<?=site_img('del_icon.png');?>">
         </a>
       </td>
     </tr>
   <? endforeach; ?>
  <? endif; ?>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Стоимость товаров</td>
    <td class="cart-total" style="<?=$trTdStyle;?>"><?=(int)$cart['siteorder']['total'];?></td>
  </tr>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>"><?=empty($cart['siteorder']['delivery']['name']) ? 'Бесплатная доставка' : $cart['siteorder']['delivery']['name'];?></td>
    <td class="cart-total" style="<?=$trTdStyle;?>">
      <input id="js-delivery-price" name="ajax_delivery_price" <?=($cart['siteorder']['paid'] || $cart['siteorder']['siteorder_status_id'] == 42) ? 'disabled="disabled"' : ''?> <?=(int)$cart['siteorder']['delivery_price'];?> value="<?=(int)$cart['siteorder']['delivery_price'];?>" style="width: 35px;">
      <a class="js-siteorder-save-delivery-price" style="font-size: 10px; display: none; margin-left: 5px;">save</a>
    </td>
  </tr>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Скидка по заказу</td>
    <td class="cart-total" style="<?=$trTdStyle;?>">
      <input id="js-total-discount" name="total_discount" <?=($cart['siteorder']['paid'] || $cart['siteorder']['siteorder_status_id'] == 42) ? 'disabled="disabled"' : ''?> value="<?=(int)$cart['siteorder']['total_discount'];?>" style="width: 35px;">
      <a class="js-siteorder-save-total-discount" style="font-size: 10px; display: none; margin-left: 5px;">save</a>
    </td>
  </tr>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Итого</td>
    <td class="cart-total" style="<?=$trTdStyle;?>"><?=empty($cart['siteorder']['total_with_discount']) ? (int)$cart['siteorder']['total'] : (int)$cart['siteorder']['total_with_discount'];?></td>
  </tr>
</table>
<div class="log-container" style="margin-top: 10px">
  <a style="color: blue" data-ajaxp-url="<?=admin_site_url('eventlog/itemAction/')?>?siteorder_id=<?=$cart['siteorder']['id'];?>">Log добавления/удаления товаров</a>
</div>

<script>
  $("#js-show-status-log-" + 4).live('click', function() {
    $.ajaxp2OpenPopup($(this).data().ajaxpUrl);
  });
</script>