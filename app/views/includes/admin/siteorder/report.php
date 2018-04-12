<div class="content default-box">
  <div class="title">
    Отчет по продажам
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </div>
  <?=html_flash_message();?>
  <div class="inner">

    <? $this->view("includes/admin/parts/before_entity_list.php"); ?>

    <form action="<?=site_url($processLink . get_get_params());?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
      <div class="group">
        <label class="label" for="import_file">Email</label>
        <input class="text-field required" type="email" name="email"/>
      </div>
      <div class="group navform wat-cf tac">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
      </div>
    </form>
  </div>
</div>
<div class="clear"></div>