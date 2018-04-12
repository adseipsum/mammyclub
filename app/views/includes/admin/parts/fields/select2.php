<?
  if(isset($value)) {
    $entity[$key] = $value;
  }

  if(!isset($entity[$key])) {
    $entity[$key] = NULL;
  }
?>
<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>

  <input id="<?=$id?>"
         name="<?=$key?>"
         defVal="<?=htmlspecialchars($params['options'][$entity[$key]])?>"
         value="<?=$entity[$key];?>"
         dataUrl = "<?=admin_site_url($entityName . '/get_select2_field_values_ajax/' . $key);?>"
         class="text-field select2 <?=isset($params['class']) ? $params['class'] : ''?>"
          <?=$attrs?> />
  <? if(!empty($message)): ?><span class="description"><?=$message?></span><? endif; ?>
</div>