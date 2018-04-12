<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <? if (!empty($message)) :?><span class="description"><?=$message?></span><? endif; ?>
    <? if(isset($entity[$key]) && !empty($entity[$key])): ?>
    <ol>
      <? foreach($entity[$key] as $id => $file): ?>
        <li>
          <p>
            <span><?=$file['filename'];?></span>&nbsp;<a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute .  '/file/delete/' . $id)?>"><?=lang('admin.delete_image')?></a>&nbsp;/&nbsp;<a href="<?=site_url($adminBaseRoute .  '/file/download/' . $id)?>"><?=lang("admin.add_edit." . $entityName . "." . $key . ".download")?></a>
          </p>
        </li>
      <? endforeach; ?>
    </ol>
  <? endif; ?>
  <div class="clear"></div>
</div>
