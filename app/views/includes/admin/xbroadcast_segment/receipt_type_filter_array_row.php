<? // WAS RECIPIENT OF X EMAILS BY Y TYPE  ?>
<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>

<select class="required" name="<?=$arrayFieldName?>[broadcast_type_id]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>>
  <option value=""><?=lang('admin.add_edit.xbroadcastsegment.broadcast_types.broadcast_type_id.default')?></option>
  <? foreach ($broadcastTypesOptions as $typeId => $typeName): ?>
    <option value="<?=$typeId;?>"<?=!empty($valueArray['broadcast_type_id']) && $valueArray['broadcast_type_id'] == $typeId? ' selected="selected"' : '' ;?>><?=$typeName;?></option>
  <? endforeach; ?>
</select>

<input class="required" type="number" min="1" step="1" name="<?=$arrayFieldName?>[receipt_count]" value="<?=!empty($valueArray['receipt_count'])?$valueArray['receipt_count']:''?>" style="width: 5%" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> />
<span>раз</span>