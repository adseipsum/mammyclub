<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <?if (isset($entity[$key]) && ! empty($entity[$key])) :?>
    <a href="<?=site_file_url($entity[$key])?>" style="font-size: 15px"><?=lang("admin.add_edit." . $entityName . "." . $key . ".download")?></a><br/>
    <a class="confirm" title="<?=lang("admin.add_edit.file_confirm_delete")?>" href="<?=site_url($adminBaseRoute . '/' . $entityName . '/delete_file/' . $entity[$key]["id"])?>"><?=lang('admin.delete_image')?></a>
  <? else: ?>
    <input type="file"
           name="<?=$name?>"
           id="<?=$id?>"
           class="<?=isset($params['class']) ? $params['class']:""?>"
    />
  <? endif; ?>
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>