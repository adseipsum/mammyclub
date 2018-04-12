<? $value = $entity[$key]; ?>

<div class="group group-date">
  <label class="label" for="<?=$id?>" onclick="return false;"><?=$label?></label>
  <input id="<?=$id?>"
         type="text"
         class="readonly date <?=isset($params['class']) ? $params['class'] : ''?>"
         name="<?=$name?>"
         value="<?=$value?>"
         <?=$attrs;?> />
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>