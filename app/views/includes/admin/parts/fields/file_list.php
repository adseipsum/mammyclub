<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <? if (!empty($message)) :?><span class="description"><?=$message?></span><? endif; ?>
    <? if(isset($entity[$key]) && !empty($entity[$key])): ?>
    <? $count = 1; ?>
    <ul id="<?=$id?>" class="admin-img-list">
      <? foreach($entity[$key] as $file): ?>
        <li>
          <? $file = $file['resource']; ?>
          <input name="<?=$key?>_priority[]" type="hidden" value="<?=$file['id'];?>" />
          <div class="img-box">
            <? if($file['type'] == 'image'): ?>
              <img src="<?=site_image_thumb_url('_admin', $file, FALSE)?>"/>
            <? else: ?>
              <p><?=$file['file_name'];?></p>
            <? endif; ?>
          </div>
          <span>#<?=$count;?></span>&nbsp;<a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute .  '/' . $entityName . '/delete_resource/' . $file["id"])?>"><?=lang('admin.delete_image')?></a>&nbsp;/&nbsp;<a href="<?=site_file_url($file)?>" onclick="return showPopup(this.href, 780, 320);" target="_blank"><?=lang("admin.add_edit." . $entityName . "." . $key . ".download")?></a>
        </li>
        <? $count++; ?>
      <? endforeach; ?>
    </ul>
  <? endif; ?>
  <div class="clear"></div>
  <div class="mt15" style="margin-top: 15px;">
    <button class="button" type="submit" name="save" value="1">
      <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
    </button>
    <input type="file" name="<?=$name?>" />
  </div>
</div>
<script type="text/javascript">
  $("#<?=$id?>").dragsort({ dragSelector: "li", placeHolderTemplate: "<li class='place-holder'></li>" });
</script>