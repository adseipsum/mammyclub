<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>

<select name="<?=$arrayFieldName?>[parameter_value_id]" class="required js-param-linked-pv-select" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>>
  <option value=""><?=lang('admin.filter.result.default')?></option>
  <? if(!empty($params['pv_options'])): ?>
    <? foreach ($params['pv_options'] as $k => $v): ?>
      <option value="<?=$k;?>"<?=is_not_empty($valueArray['parameter_value_id'])&&$valueArray['parameter_value_id']==$k?' selected="selected"':'';?>><?=$v;?></option>
    <? endforeach; ?>
  <? endif; ?>
</select>

<select name="<?=$arrayFieldName?>[linked_product_id]" class="required" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>>
  <option value=""><?=lang('admin.filter.result.default')?></option>
  <? if(!empty($params['product_options'])): ?>
    <? foreach ($params['product_options'] as $k => $v): ?>
      <option value="<?=$k;?>"<?=is_not_empty($valueArray['linked_product_id'])&&$valueArray['linked_product_id']==$k?' selected="selected"':'';?>><?=$v;?></option>
    <? endforeach; ?>
  <? endif; ?>
</select>