<label style="margin: 10px 0 0 0;" class="label"><?=lang('admin.add_edit.product.possible_parameters.values_multiple_title.' . $type);?></label>
<select class="chzn-select js-possible-parameter-values" name="possible_parameters[possible_parameter_values_<?=$type;?>][]" multiple="multiple" style="width: 100%;" data-placeholder="--Пожалуйста выберите--"<?=!isset($entity[$key]['parameter_' . $type . '_id'])?' disabled="disabled"; ':'';?>>
  <? if (!empty($entity[$key]['possible_parameter_values'])) :?>
    <? foreach($entity[$key]['possible_parameter_values'] as $ent):?>
      <? if($ent['parameter_id'] == $entity[$key]['parameter_' . $type . '_id']): ?>
    	  <option selected="selected"  value="<?=$ent['id']?>"><?=$ent['name'];?></option>
    	<? endif; ?>
    <?endforeach?>
  <? endif; ?>
  <? foreach ($params[$type . '_values_options'] as $k => $v) :?>
    <option value="<?=$k?>"><?=htmlspecialchars($v)?></option>
  <? endforeach?>
</select>