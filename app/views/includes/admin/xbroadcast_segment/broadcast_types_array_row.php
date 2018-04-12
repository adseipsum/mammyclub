<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>

<select class="required" name="<?=$arrayFieldName?>[broadcast_type_id]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>>
  <option value=""><?=lang('admin.add_edit.xbroadcastsegment.broadcast_types.broadcast_type_id.default')?></option>
  <? foreach ($broadcastTypesOptions as $typeId => $typeName): ?>
    <option value="<?=$typeId;?>"<?=!empty($valueArray['broadcast_type_id']) && $valueArray['broadcast_type_id'] == $typeId? ' selected="selected"' : '' ;?>><?=$typeName;?></option>
  <? endforeach; ?>
</select>

<select class="required" name="<?=$arrayFieldName?>[is_subscribed]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> style="width: 20%;">
  <option value=""><?=lang('admin.add_edit.xbroadcastsegment.broadcast_types.is_subscribed.default')?></option>
  <option value="1"<?=!empty($valueArray['broadcast_type_id']) && $valueArray['is_subscribed'] === TRUE ? ' selected="selected"' : '' ;?>>Подписан</option>
  <option value="0"<?=!empty($valueArray['broadcast_type_id']) && $valueArray['is_subscribed'] === FALSE ? ' selected="selected"' : '' ;?>>Не подписан</option>
</select>