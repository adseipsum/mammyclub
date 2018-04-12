<?
  $tableFields = array('product.product_code',
                       'product.name',
                       'additional_product_params',
                       'price',
                       'discount_price',
                       'qty',
                       'item_total');
  $trTdStyle = 'border: 1px solid black; padding: 10px;';
?>

<table id="cart-data" style="border-collapse: collapse;">
  <tr>
    <td style="<?=$trTdStyle;?>">Код товара</td>
    <td style="<?=$trTdStyle;?>">Товар</td>
    <td style="<?=$trTdStyle;?>">Параметры</td>
    <td style="<?=$trTdStyle;?>">Цена</td>
    <td style="<?=$trTdStyle;?>">Цена с учетом скидки</td>
    <td style="<?=$trTdStyle;?>">Количество</td>
    <td style="<?=$trTdStyle;?>">Stock MC</td>
    <td style="<?=$trTdStyle;?>">ZAMMLER</td>
    <td style="<?=$trTdStyle;?>">Склад поставщика</td>
    <td style="<?=$trTdStyle;?>">Резерв</td>
    <td style="<?=$trTdStyle;?>">Срок доставки</td>
    <td style="<?=$trTdStyle;?>">Всего</td>
  </tr>

  <? if(is_not_empty($cart['items'])): ?>
   <? foreach ($cart['items'] as $cartItem): ?>
     <tr class="product-row">

       <td class="product.product_code" style="<?=$trTdStyle;?>"><?=$cartItem['product']['product_code'];?></td>
       <td class="product.name" style="<?=$trTdStyle;?>"><?=$cartItem['product']['name'];?></td>

       <td style="<?=$trTdStyle;?>">
         <? if (is_not_empty($cartItem['product']['possible_parameters'])): ?>
         <?
           $paramTypes = array(1 => 'main', 2 => 'secondary');
           $groupWithSelectedMainParamValue = array();
         ?>
         <? foreach ($paramTypes as $k => $type): ?>

           <? if (is_not_empty($cartItem['product']['possible_parameters']['parameter_' . $type]) && is_not_empty($cartItem['product']['possible_parameters']['possible_parameter_values'])): ?>
             <select data-placeholder="Выберите, пожалуйста" class="param<?=$k;?>-select" style="width: 200px;" disabled="disabled">

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
         <? endif; ?>
       </td>
       <td class="price" style="<?=$trTdStyle;?>"><?=$cartItem['price'];?></td>
       <td class="discount_price" style="<?=$trTdStyle;?>"><?=$cartItem['discount_price'] == 0 ? '' : $cartItem['discount_price'] ;?></td>
       <td style="<?=$trTdStyle;?>">
         <input data-cart-item-id="<?=$cartItem['id'];?>" type="text" value="<?=$cartItem['qty'];?>" disabled="disabled" />
       </td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['mc_inventory_qty'];?></td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['zammler_inventory_qty'];?></td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['other_stores_inventory_qty'];?></td>
       <td style="<?=$trTdStyle;?>">
         <? if (isset($cartItem['reserves']) && !empty($cartItem['reserves']) && !empty($cartItem['reserves']['qty'])) : ?>
           Забронированно (<?=$cartItem['reserves']['qty']?>)
         <? else : ?>
           Бронь отсутствует
         <? endif; ?>
       </td>
       <td style="<?=$trTdStyle;?>"><?=$cartItem['delivery_time'];?></td>
       <td class="item-total" style="<?=$trTdStyle;?>"><?=$cartItem['item_total'];?></td>
     </tr>
   <? endforeach; ?>
  <? endif; ?>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Стоимость товаров</td>
    <td class="cart-total" style="<?=$trTdStyle;?>"><?=(int)$cart['siteorder']['total'];?></td>
  </tr>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Доставка: <?=$cart['siteorder']['delivery']['name'];?></td>
    <td class="cart-total" style="<?=$trTdStyle;?>"><?=(int)$cart['siteorder']['delivery_price'];?></td>
  </tr>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Скидка по заказу</td>
    <td class="cart-total" style="<?=$trTdStyle;?>"><?=(int)$cart['siteorder']['total_discount'];?></td>
  </tr>
  <tr>
    <td colspan="11" style="<?=$trTdStyle;?>">Итого</td>
    <td class="cart-total" style="<?=$trTdStyle;?>"><?=empty($cart['siteorder']['total_with_discount']) ? (int)$cart['siteorder']['total'] : (int)$cart['siteorder']['total_with_discount'];?></td>
  </tr>
</table>