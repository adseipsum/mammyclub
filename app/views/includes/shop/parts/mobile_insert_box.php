<div class="insert-box">

  <div class="box fr">

    <? if( (!empty($product['parameter_link']) && !empty($product['parameter_product_links']) ) || !empty($product['parameter_value_link_id'])): ?>
        <div class="param-box">
        <div class="row">
          <p class="attention">Пожалуйста, выберите</p>
          <p class="title"><?=$product['parameter_link']['name']?></p>
          <select class="js-parameter-product-link">
            <? if(!empty($product['parameter_value_link_id'])): ?>
              <option value=""><?=$product['parameter_value_link']['name'];?></option>
            <? else:?>
              <option value="">- Выберите -</option>
            <? endif; ?>
            <? foreach ($product['parameter_product_links'] as $ppl): ?>
              <option value="<?=shop_url($ppl['linked_product']['page_url']);?>"><?=$ppl['parameter_value']['name'];?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    <? endif; ?>

    <? if (is_not_empty($product['possible_parameters'])): ?>

      <?
         $mainParamValueIds = array();
         if(is_not_empty($product['parameter_groups'])) {
           $mainParamValueIds = get_array_vals_by_second_key($product['parameter_groups'], 'main_parameter_value_id');
         }
      ?>

      <div class="param-box js-param-box">

        <? $paramTypes = array(1 => 'main', 2 => 'secondary'); ?>

        <? foreach ($paramTypes as $k => $type): ?>

          <? if (is_not_empty($product['possible_parameters']['parameter_' . $type]) && is_not_empty($product['possible_parameters']['possible_parameter_values'])): ?>

            <? $product['possible_parameters']['possible_parameter_values'] = array_sort($product['possible_parameters']['possible_parameter_values'], 'priority');?>

            <div class="row">
              <p class="attention">Пожалуйста, выберите</p>
              <p class="title"><?=$product['possible_parameters']['parameter_' . $type]['name']?></p>
              <select class="js-select-param<?=$k?>">
                <option value="">- Выберите -</option>
                <? foreach ($product['possible_parameters']['possible_parameter_values'] as $paramValue): ?>
                  <? if ($paramValue['parameter_id'] == $product['possible_parameters']['parameter_' . $type]['id']): ?>
                    <?
                      $onOrder = FALSE;
                      if($type == 'main' && !empty($mainParamValueIds)) {
                        $groupKey = array_search($paramValue['id'], $mainParamValueIds);
                        if($product['parameter_groups'][$groupKey]['not_in_stock'] == TRUE) {
                          continue;
                        }
//                        if(($groupKey !== FALSE && $product['parameter_groups'][$groupKey]['on_order'] == TRUE) || $product['on_order'] == TRUE) {
//                          $onOrder = TRUE;
//                        }
                      }
//                      $stockMsg = NULL;
//                      if($product['not_in_stock'] == TRUE) {
//                        $stockMsg = ' (Нет в наличии)';
////                      } elseif ($onOrder == TRUE) {
////                        $stockMsg = ' (под заказ)';
//                      }
                    ?>
                    <option value="<?=$paramValue['id'];?>"><?=$paramValue['name'];?></option>
                  <? endif; ?>
                <? endforeach; ?>
              </select>
            </div>
          <? endif; ?>

        <? endforeach; ?>

      </div>

    <? endif; ?>

  </div>

  <div class="box fl <?=(isset($product['brand']) && !empty($product['brand']['description'])) ? '' : 'false';?>">

    <div class="small-price-box">
    <? if (isset($product['sale']['ends_at']) && !empty($product['sale']['ends_at'])): ?>
      <div class="top">Акция заканчивается в <?=date('H:i d.m.Y', strtotime($product['sale']['ends_at']));?></div>
    <? endif; ?>

      <div class="middle">
        <table cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-1">
              <? if (isset($product['sale']['discount']) && !empty($product['sale']['discount'])): ?>
                <span class="old-price"><b><?=$product['old_price'];?></b> грн.</span>
                <span class="real-price"><b class="js-price"><?=$product['price'];?></b> грн.</span>
              <? else: ?>
                <span class="real-price"><b class="js-price"><?=$product['price'];?></b> грн.</span>
              <? endif; ?>
            </td>
            <td class="td-2">
              <?
                $buttonUrl = shop_url('добавить-в-корзину/' . $product['id']);
                if($product['not_in_stock'] == TRUE) {
                  $buttonUrl = shop_url(rtrim($product['category']['page_url'], '/') . '?product_not_in_stock=1');
                }
              ?>
              <a href="<?=$buttonUrl;?>" class="def-but orange-but<?=$product['not_in_stock']==FALSE?' js-add-to-cart':'';?> js-country-<?=$country;?>">Купить</a>
            </td>
          </tr>
        </table>
      </div>
      <div class="bottom js-stock-wrap">
        <? if ($product['not_in_stock'] == FALSE):?>
          <? if (empty($product['parameter_groups'])):?>
            <? if($product['zammler_inventory_qty'] > 0): ?>
                  <span class="in-stock">Есть на складе</span>
            <? else: ?>
                  <span class="in-stock"><?=$product['brand']['delivery_time'];?></span>
            <? endif; ?>
          <? else: ?>
              <span class="in-stock js-stock-status js-in-stock-default" style="">Есть в наличии</span>
              <span class="in-stock js-stock-status js-in-stock" style="display: none">Есть на складе</span>
              <span class="in-stock js-stock-status js-in-other-stock" style="display: none"><?=$product['brand']['delivery_time'];?></span>
          <? endif; ?>
        <? else:?>
          <span class="not-in-stock">Нет в наличии</span>
        <? endif;?>
      </div>
    </div>

  </div>

  <? if (isset($product['brand']) && !empty($product['brand']['description'])): ?>
    <div class="detail-info">
      <div class="clear"></div>
      <span style="padding-top: 7px; display: inline-block;" data-ajaxp-url="<?=shop_url('аджакс/информация-о-производителе/' . $product['brand']['id']);?>" class="a-like">Показать информацию о производителе</span>
    </div>
  <? endif; ?>

  <? if (isset($product['brand']) && !empty($product['brand']['description'])): ?>
    <div class="clear"></div>
  <? endif; ?>

</div>