<div class="">

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
            <select class="js-select-param<?=$k?> js-track-filters<?=$product['not_in_stock']==TRUE?' js-not-in-stock':'';?>">
              <option value="">- Выберите -</option>
              <? foreach ($product['possible_parameters']['possible_parameter_values'] as $paramValue): ?>
                <? if ($paramValue['parameter_id'] == $product['possible_parameters']['parameter_' . $type]['id']): ?>
                  <?
//                    $onOrder = FALSE;
                    if($type == 'main' && !empty($mainParamValueIds)) {
                      $groupKey = array_search($paramValue['id'], $mainParamValueIds);
                      if($product['parameter_groups'][$groupKey]['not_in_stock'] == TRUE) {
                        continue;
                      }

                    }
//                    $stockMsg = NULL;
//                    if($product['not_in_stock'] == TRUE) {
//                      $stockMsg = ' (Нет в наличии)';
//                    }
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