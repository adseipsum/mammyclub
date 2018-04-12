<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>

<select class="js-product-filter" name="<?=$arrayFieldName?>[filter]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>>
  <option value=""><?=lang('admin.filter.product.filter_values.filter.default')?></option>
  <? foreach ($params['filter']['options'] as $f): ?>
    <option value="<?=$f['id'];?>"<?=!empty($valueArray['filter_id']) && $valueArray['filter_id'] == $f['id'] ? ' selected="selected"' : '' ;?>><?=$f['name'];?></option>
  <? endforeach; ?>
</select>

<select class="js-product-filtervalue" name="<?=$arrayFieldName?>[filter_value]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> style="width: 20%;">
  <option value=""><?=lang('admin.filter.product.filter_values.filter_value.default')?></option>
  <? if(!empty($valueArray['filter_id'])):?>
    <? foreach ($params['filter']['options'] as $f): ?>
      <? if($f['id'] == $valueArray['filter_id']): ?>
        <? if(!empty($f['filtervalues'])): ?>
          <? foreach($f['filtervalues'] as $fv): ?>
            <option value="<?=$fv['id'];?>"<?=!empty($valueArray['id']) && $valueArray['id'] == $fv['id'] ? ' selected="selected"' : '' ;?>><?=$fv['name'];?></option>
          <? endforeach; ?>
        <? endif; ?>
        <? break; ?>
      <? endif; ?>
    <? endforeach; ?>
  <? endif; ?>
</select>