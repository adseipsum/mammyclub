<?
$langKey = 'admin.add_edit.' . strtolower($fields[$key]['relation']['entity_name']) . '.' . $extName;
  if(isset($value)) {
    $entity[$key] = $value;
  }

  if(!isset($entity[$key])) {
    $entity[$key] = NULL;
  }
?>
<label class="label"><?=lang($langKey);?></label>
<select name="<?=$externalFieldGroupName . '[' . $extName . ']'?>"
        class="select"<?= isset($disabled) && $disabled ? ' disabled="disabled"' : '' ?>>
  <? foreach ($params['options'] as $k => $v): ?>
  	<? $langValueKey = 'enum.' . strtolower($entityName) . '.' . $key . '.' . $k; // poduct.type.PUBLISHED ?>
    <? preg_match('/-*\(([0-9]+)\).*/', $v, $matches); ?>
    <? $level = (count($matches) > 1) && isset($matches[1]) ? $matches[1] : 0; ?>
    <option <?if($k && $level):?>class="level-<?=$level?>"<?endif;?> <?=$entity[$key] == $k ? 'selected="selected"' : ''?> value="<?=$k?>" <?=$attrs?>><?=(lang_exists($langValueKey) ? lang($langValueKey) : htmlspecialchars($v))?></option>
  <? endforeach; ?>
</select>