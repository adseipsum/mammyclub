<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>
<?// $disabled = !empty($valueArray['siteorder_code']); ?>

<table class="js-not-processed" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 10px 0; width: 95%; float: left; ">
	<tr>
		<td style="width: 7%;"><b>Бренд:</b></td>
    <td style="width: 37%;"><b>Товар:</b></td>
    <td style="width: 8%;"><b>Параметр:</b></td>
    <td style="width: 5%;"><b>Количество:</b></td>
    <td style="width: 10%;"><b>№ заказа:</b></td>
    <td style="width: 10%;"><b>Дата отгрузки:</b></td>
	</tr>
	<tr>
	  <td>
			<input type="hidden" name="<?=$arrayFieldName?>[siteorder_item_id]" value="<?=isset($valueArray['siteorder_item_id']) && !empty($valueArray['siteorder_item_id']) ? $valueArray['siteorder_item_id'] : ''?>">

			<? if (!$disabled) : ?>
				<input type="hidden" name="<?=$arrayFieldName?>[brand]" value="<?=isset($valueArray['brand_id']) && !empty($valueArray['brand_id']) ? $valueArray['brand_id'] : ''?>">
			<? endif; ?>

			<select class="select <?= $disabled ? 'chosen-ignore' : ''?>" name="<?=$arrayFieldName?>[brand]" <?= !empty($valueArray['siteorder_code']) ? 'disabled="disabled"' : '' ?>>
			  <option value="">--Не выбрано--</option>
			  <? foreach ($params['brand_options'] as $k => $v): ?>
			    <option value="<?=$k;?>"<?=!empty($valueArray['brand_id']) && $valueArray['brand_id'] == $k ? ' selected="selected"' : '' ;?>><?=$v;?></option>
			  <? endforeach; ?>
			</select>
    </td>
	  <td>
			<? if (!$disabled) : ?>
				<input type="hidden" name="<?=$arrayFieldName?>[product]" value="<?=isset($valueArray['product_id']) && !empty($valueArray['product_id']) ? $valueArray['product_id'] : ''?>">
			<? endif; ?>

			<select class="select <?= $disabled ? 'chosen-ignore' : ''?>" name="<?=$arrayFieldName?>[product]" <?= !empty($valueArray['siteorder_code']) ? 'disabled="disabled"' : '' ?>>
			  <option value="">--Не выбрано--</option>
			  <? if (isset($valueArray['product_options']) && !empty($valueArray['product_options'])): ?>
				  <? foreach ($valueArray['product_options'] as $k => $v): ?>
				    <option value="<?=$k;?>"<?=!empty($valueArray['product_id']) && $valueArray['product_id'] == $k ? ' selected="selected"' : '' ;?>><?=$v;?></option>
				  <? endforeach; ?>
			  <? endif; ?>
			</select>
    </td>
	  <td>
			<? if (!$disabled) : ?>
				<input type="hidden" name="<?=$arrayFieldName?>[parameter_group]" value="<?=isset($valueArray['parameter_group_id']) && !empty($valueArray['parameter_group_id']) ? $valueArray['parameter_group_id'] : ''?>">
			<? endif; ?>

			<select class="select <?= $disabled ? 'chosen-ignore' : ''?>" name="<?=$arrayFieldName?>[parameter_group]" <?= !empty($valueArray['siteorder_code']) ? 'disabled="disabled"' : '' ?>>
			  <option value="">--Не выбрано--</option>
			  <? if (isset($valueArray['param_group_options']) && !empty($valueArray['param_group_options'])): ?>
				  <? foreach ($valueArray['param_group_options'] as $k => $v): ?>
				    <option value="<?=$k;?>"<?=!empty($valueArray['parameter_group_id']) && $valueArray['parameter_group_id'] == $k ? ' selected="selected"' : '' ;?>><?=$v;?></option>
				  <? endforeach; ?>
			  <? endif; ?>
			</select>
    </td>
    <td>
      <input type="number" value="<?=!empty($valueArray['qty'])?$valueArray['qty']:1;?>" name="<?=$arrayFieldName?>[qty]" min="1" <?= !empty($valueArray['siteorder_code']) ? 'readonly="readonly"' : '' ?> style="width: 78px;"/>
    </td>
    <td>
      <input type="text" value="<?=!empty($valueArray['siteorder_code']) && !empty($valueArray['siteorder_item_id'])?$valueArray['siteorder_code']:'';?>" name="<?=$arrayFieldName?>[siteorder_code]" readonly="readonly" />
    </td>
    <td>
      <input type="text" value="<?=!empty($valueArray['shipment_date'])?$valueArray['shipment_date']:'';?>"class="readonly" name="shipment_date" readonly="readonly">
    </td>
	</tr>
</table>