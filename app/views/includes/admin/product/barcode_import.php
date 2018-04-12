<div class="content default-box">
  <h2 class="title">
    <span class="fl">Сверка файла со штрихкодами формата ".CSV"</span>
    <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
  </h2>
  <?=html_flash_message();?>

  <div class="inner export">

    <? $this->view("includes/admin/parts/before_entity_list.php"); ?>

    <form action="<?=site_url($processLink);?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
      <div class="group">
        <label class="label" for="import_file">Файл для сверки</label>
        <input class="input-file required" type="file" name="import_file"/>
        <span class="description">1) разделитель - ";" 2) регистр названия колонок не имеет значения 3) главное, что бы в файле были 2 колонки: штрихкод и остаток (последовательность не имеет значения)
        </span>
      </div>
      <div class="group">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.import');?>"/><?=lang('admin.import');?>
        </button>
      </div>
    <div class="clear"></div>
    </form>
  </div>
</div>