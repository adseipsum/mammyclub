<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>

  <ul id="<?=$id?>" class="admin-img-list js-file-container-<?=$key;?>">
  <? if(isset($entity[$key]) && !empty($entity[$key])): ?>
    <? $count = 1; ?>
    <? foreach($entity[$key] as $image): ?>
        <li>
          <? $image = $image['image']; ?>
          <input name="<?=$key?>_priority[]" type="hidden" value="<?=$image['id'];?>" />
          <div class="img-box">
            <img src="<?=site_image_thumb_url('_admin', $image)?>"/>
          </div>
          <span>#<?=$count;?></span>&nbsp;<a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute .  '/' . $entityName . '/delete_image/' . $image["id"])?>"><?=lang('admin.delete_image')?></a>&nbsp;/&nbsp;<a href="<?=site_image_url($image)?>" target="_blank"><?=lang('admin.enlarge_image')?></a>
        </li>
        <? $count++; ?>
    <? endforeach; ?>
  <? endif; ?>
  </ul>

  <div class="clear"></div>


  <!-- The fileinput-button span is used to style the file input field as button -->
  <span class="fileinput-button">
    <span class="def-but green-but">Add files...</span>
    <input class="js-fileupload-<?=$key;?>" type="file" name="<?=$key;?>[]" multiple>
  </span>

  <div class="clear"></div>

  <div class="mt15" style="margin-top: 15px;">
    <button class="button" type="submit" name="save" value="1">
      <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
    </button>
  </div>

  <div class="clear"></div>

  <script>
    $("#<?=$id?>").dragsort({ dragSelector: "li", placeHolderTemplate: "<li class='place-holder'></li>" });
    $(function () {
        var url = base_url + admin_url + '/product/ajax_file_upload/<?=$key;?>/<?=$entity['id'];?>';
        $('.js-fileupload-<?=$key;?>').fileupload({
            url: url,
            dataType: 'json',
            start: function(e) {
              $('<img class="js-preloader" />').attr('src', '<?=site_img('preloader.gif');?>').appendTo('.js-file-container-<?=$key;?>');
            },
            stop: function (e) {
              $('.js-preloader').remove();
              $("#<?=$id?>").dragsort("destroy");
              $("#<?=$id?>").dragsort({ dragSelector: "li", placeHolderTemplate: "<li class='place-holder'></li>" });
            },
            done: function (e, data) {
              var liCount = $("#<?=$id?> li").length + 1;
              var file = data.result.file;
              $('<li class="js-single-row-' + file.id + '"/>').appendTo('.js-file-container-<?=$key;?>');
              $('<input name="<?=$key;?>_priority[]" type="hidden" value="' + file.id + '" />').appendTo('.js-single-row-' + file.id);
              $('<div class="img-box">').appendTo('.js-single-row-' + file.id);
              $('<img/>').attr('src', file.thumbnailUrl).appendTo('.js-single-row-' + file.id + ' .img-box');
              $('<span>#' + liCount + '</span><span>&nbsp;</span>').appendTo('.js-single-row-' + file.id);
              $('<a class="confirm" href="' + file.deleteUrl + '" title="<?=lang("admin.add_edit.image_confirm_delete")?>"><?=lang('admin.delete_image')?><a/><span>&nbsp;/&nbsp;</span>').appendTo('.js-single-row-' + file.id);
              $('<a href="' + file.imgUrl + '"><?=lang('admin.enlarge_image')?><a/>').appendTo('.js-single-row-' + file.id);
              $('.js-single-row-' + file.id).removeAttr('class');
            }
        });
    });
  </script>
</div>