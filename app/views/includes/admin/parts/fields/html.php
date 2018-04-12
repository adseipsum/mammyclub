<div class="group">
  <label class="label" for="<?=$id?>"><?=$label?></label>
  <div id="<?=$id?>" name="<?=$name?>" class="<?=isset($params['class']) ? $params['class'] : ''?>" <?=$attrs?>>
    <?=$entity[$key];?>
    <div class="clear"></div>
  </div>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>