<div class="group" style="border-top: 1px solid #ccc; padding: 10px 0 0 0;">
<label for="<?=$id?>" class="label"><?=$label?></label>
<?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
  <? if(isset($entity[$key]) && !empty($entity[$key])): ?>
    <? $count = 1; ?>
    <ul id="<?=$id?>" class="admin-img-list">
    <? foreach($entity[$key] as $image): ?>
        <li style="height: auto; margin-top: 0px;">
          <input name="<?=$key?>_priority[]" type="hidden" value="<?=$image['image']['id'];?>" />
          <div class="img-box">
            <img src="<?=site_image_thumb_url('_admin', $image['image'])?>"/>
          </div>
          <? if (isset($params['extra_fields'])): ?>
            <? foreach ($params['extra_fields'] as $ef): ?>
              <textarea style="width: 100%; margin-top: 5px; position: relative; z-index: <?=count($entity[$key])-$count;?>" name="<?=$key;?>_<?=$ef;?>_list[<?=$image['image']['id'];?>]" placeholder="<?=lang($key . '.extra_fields.' . $ef);?>"><?=isset($image[$ef]) ? $image[$ef] : '';?></textarea>
            <? endforeach; ?>
          <? endif; ?>
          <div>
            <span>#<?=$count;?></span>&nbsp;<a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute .  '/' . $entityName . '/delete_image/' . $image['image']["id"])?>"><?=lang('admin.delete_image')?></a>&nbsp;/&nbsp;<a href="<?=site_image_url($image['image'])?>" target="_blank"><?=lang('admin.enlarge_image')?></a>
          </div>
        </li>
        <? $count++; ?>
    <? endforeach; ?>
  <? endif; ?>
  </ul>
  <div class="clear"></div>
  <div class="mt15" style="margin-top: 15px;">
    <input type="file" name="<?=$name;?>" />
    <div class="clear"></div>
    <? if (isset($params['extra_fields'])): ?>
      <? foreach ($params['extra_fields'] as $ef): ?>
        <label for="<?=$key;?>_<?=$ef;?>" class="label"><?=lang($key . '.extra_fields.' . $ef);?></label>
        <textarea style="width: 40%; margin-top: 5px;" type="text" name="<?=$key?>_<?=$ef;?>"></textarea>
        <div class="clear"></div>
      <? endforeach; ?>
    <? endif; ?>
    <button class="button" type="submit" name="save" value="1" style="margin-bottom: 10px;">
      <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
    </button>
  </div>
</div>
<div class="clear"></div>
<script type="text/javascript">
  $("#<?=$id?>").dragsort({ dragSelector: "li", placeHolderTemplate: "<li class='place-holder'></li>" });
</script>