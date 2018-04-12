<div class="group">
<label for="<?=$id?>" class="label"><?=$label?></label>
<?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
  <? if(isset($entity[$key]) && !empty($entity[$key])): ?>
    <? $count = 1; ?>
    <ul id="<?=$id?>" class="admin-img-list">
    <? foreach($entity[$key] as $image): ?>
        <li>
          <? $image = $image['image']; ?>
          <input name="<?=$key?>_priority[]" type="hidden" value="<?=$image['id'];?>" />
          <div class="img-box">
            <img src="<?=site_image_thumb_url('_admin', $image)?>"/>
          </div>
          <input style="width: 100%; margin-top: 5px;" type="text" name="<?=$key;?>_alt_list[]" value="<?=isset($image['alt']) && !empty($image['alt']) ? $image['alt'] : '';?>"/>
          <div>
            <span>#<?=$count;?></span>&nbsp;<a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute .  '/' . $entityName . '/delete_image/' . $image["id"])?>"><?=lang('admin.delete_image')?></a>&nbsp;/&nbsp;<a href="<?=site_image_url($image)?>" target="_blank"><?=lang('admin.enlarge_image')?></a>
          </div>
        </li>
        <? $count++; ?>
    <? endforeach; ?>
  <? endif; ?>
  </ul>
  <div class="clear"></div>
  <div class="mt15" style="margin-top: 15px;">
    <label for="<?=$key;?>_alt" class="label"><?=lang('admin.add_edit.' . $key . '_alt')?></label>
    <input style="width: 20%; margin-top: 5px;" type="text" name="<?=$key?>_alt"/>
    <div class="clear"></div>
    <input type="file" name="<?=$name;?>" />
    <div class="clear"></div>
    <button class="button" type="submit" name="save" value="1" style="margin-bottom: 10px;">
      <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
    </button>
  </div>
</div>
<div class="clear"></div>
<script type="text/javascript">
  $("#<?=$id?>").dragsort({ dragSelector: "li", placeHolderTemplate: "<li class='place-holder'></li>" });
</script>