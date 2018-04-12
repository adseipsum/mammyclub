<select class="chzn-select js-possible-parameter-values" name="<?=$arrayFieldName?>[secondary_parameter_values_out][]" multiple="multiple" style="width: 100%;" data-placeholder="--Пожалуйста выберите--">
  <? $secondaryParValuesOutIds = array(); ?>
  <? if(!empty($group['secondary_parameter_values_out'])): ?>
    <? foreach ($group['secondary_parameter_values_out'] as $valueOut): ?>
      <? $secondaryParValuesOutIds[] = $valueOut['id'];?>
      <option selected="selected"  value="<?=$valueOut['id']?>"><?=$valueOut['name'];?></option>
    <? endforeach; ?>
  <? endif; ?>
  <? foreach ($fields['parameter_groups']['options'] as $k => $v) :?>
    <? if(empty($secondaryParValuesOutIds) || (!empty($secondaryParValuesOutIds) && !in_array($k, $secondaryParValuesOutIds))): ?>
      <option value="<?=$k?>"><?=htmlspecialchars($v)?></option>
    <? endif; ?>
  <? endforeach?>
</select>
