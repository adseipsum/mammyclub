<div class="group">
  <label for="<?=str_replace('.', '_', $id)?>" class="label"><?=$label?></label>

  <?if (isset($params['class']) && strpos($params['class'], 'passwordGen') !== false) :?>
    <img alt="Generate password" class="passwordGenButton" style="cursor: pointer; position: absolute; top: 21px; right: 0px;" title="Generate password" src="<?=site_img('admin/icons/dice.png'); ?>"/>
  <?endif?>
  
  <?if (isset($params['class']) && strpos($params['class'], 'translate') !== false) :?>
    <img alt="Translate" class="translateBtn" style="cursor: pointer; position: absolute; top: 21px; right: 0px;" title="Translate" src="<?=site_img('admin/icons/key.png'); ?>"/>
    <img alt="Translate" class="translateLoader" style="cursor: pointer; position: absolute; top: 21px; right: 0px; display: none;" title="Translate" src="<?=site_img('admin/icons/small_loader.gif'); ?>"/>
  <?endif?>
  
  <?
    if (!isset($entity[$key])) {
      $entity[$key] = '';
    }
    if (isset($value)) {
      $entity[$key] = $value;
    }
  ?>

  <input id="<?=str_replace('.', '_', $id)?>"
         name="<?=$name?>"
         type="text"
         class="text-field <?=isset($params['class']) ? $params['class'] : ''?>"
         value="<?=htmlspecialchars($entity[$key])?>"
         <?=$attrs?>
  />
  <?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>