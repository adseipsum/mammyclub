<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>

<div class="group array-field">
	<label class="label" for="<?=str_replace('.', '_', $id)?>"><?=$label?></label>
	
	<? if(isset($params['input-row-type']) && $params['input-row-type'] == 'custom'): ?>
	  <? $rowViewPath = $params['input-row-path'];?>
	<? else: ?>
    <? $rowViewPath = 'includes/admin/parts/fields/array_parts/input';?>
	<? endif; ?>

  <ol>
    <? $i = 0; ?>	
    <? if(!empty($entity[$key])): ?>
      <? foreach($entity[$key] as $value): ?>
        <li>
          <div class="removable-field">
            <?=$this->view($rowViewPath, array('value' => $value, 'disabled' => FALSE, 'count' => $i), TRUE)?>
            <a class="remove-field">
              <img src="<?=site_img('admin/icons/cross.png')?>" title="<?=lang('admin.array.remove_field');?>" />
            </a>
            <div class="clear"></div>
          </div>
        </li>
        <? $i++; ?>
      <? endforeach; ?>
    <? endif; ?>
    <li class="sample" style="display:none;">
      <div class="removable-field">
        <?=$this->view($rowViewPath, array('value' => '', 'disabled' => TRUE, 'count' => $i), TRUE)?>
        <a class="remove-field">
          <img src="<?=site_img('admin/icons/cross.png')?>" title="<?=lang('admin.array.remove_field');?>" />
        </a>
        <div class="clear"></div>
      </div>
  	</li>
  </ol>
  
  
  
  <button class="button add-field">
    <img src="<?=site_img("admin/icons/add.png")?>"/><?=lang('admin.array.add_field');?>
  </button>

	<div class="clear"></div>  
</div>

<? /*
<div class="group">
  <label class="label" for="<?=str_replace('.', '_', $id)?>"><?=$label?></label>     
  <textarea id="<?=str_replace('.', '_', $id)?>"
            class="text-area <?=isset($params['class']) ? $params['class'] : ""?>"
            name="<?=$name?>"
            <?=$attrs?>
  ><?=htmlspecialchars($entity[$key])?></textarea>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>              
</div>
*/?>