<?
if(isset($value)) {
  $entity[$key] = $value;
}

if(!isset($entity[$key])) {
  $entity[$key] = NULL;
}
?>

<style>
  .select2-container {display: inline;}
  .select2-selection__rendered li {padding: 3px !important;}
</style>

<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <select id="<?=$id?>"
  			  name="<?=$key?>[]"
  			  defVal="--Please select an option--"
  			  dataUrl = "<?=admin_site_url($entityName . '/get_select2_field_values_ajax/' . $key);?>"
          multiple="multiple"
          class="text-field select2_multiple chosen-ignore <?=isset($params['class']) ? $params['class'] : ''?>"
          <?=$attrs?>>

    <? if(!empty($entity[$key])): ?>
    	<? foreach ($entity[$key] as $e): ?>
    		<option value="<?=$e['id'];?>" selected="selected"><?=$e['name'];?></option>
    	<? endforeach; ?>
    <? endif; ?>

  </select>
  <? if(!empty($message)): ?><span class="description"><?=$message?></span><? endif; ?>
</div>