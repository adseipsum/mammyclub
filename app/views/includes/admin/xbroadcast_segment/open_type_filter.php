<? // OPENED/NOT OPENED >=|<= Y%  BY Z TYPE ?>
<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>

<select class="required" name="<?=$arrayFieldName?>[broadcast_type_id]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>>
  <option value=""><?=lang('admin.add_edit.xbroadcastsegment.broadcast_types.broadcast_type_id.default')?></option>
  <? foreach ($broadcastTypesOptions as $typeId => $typeName): ?>
    <option value="<?=$typeId;?>"<?=!empty($valueArray['broadcast_type_id']) && $valueArray['broadcast_type_id'] == $typeId? ' selected="selected"' : '' ;?>><?=$typeName;?></option>
  <? endforeach; ?>
</select>

<select class="required" name="<?=$arrayFieldName?>[operator]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> style="width: 20%;">
  <option value=">="<?=!empty($valueArray['broadcast_type_id']) && $valueArray['operator'] == ">=" ? ' selected="selected"' : '' ;?>>>=</option>
  <option value="<="<?=!empty($valueArray['broadcast_type_id']) && $valueArray['operator'] == "<=" ? ' selected="selected"' : '' ;?>><=</option>
</select>

<input class="required" type="number" min="1" max="100" step="1" name="<?=$arrayFieldName?>[percentage]" value="<?=!empty($valueArray['percentage'])?$valueArray['percentage']:1?>" style="width: 5%" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> />
<span>%</span>