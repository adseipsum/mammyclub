<div class="content default-box">
  <div class="title">
    <?=lang('admin.add_edit.' . $entityName . ".change_root_order_form_title");?>
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </div>
  <?=html_flash_message();?>
  <div class="inner">
    <form action="<?=site_url($processLink);?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
      <div class="group">
        <?if (count($roots) < 1) :?>
          <span><?=lang('admin.no_children')?></span>
        <?endif?>
        <ul class="sortable">
          <?foreach ($roots as $root) :?>
            <li class="ui-state-default">
              <span class="ui-icon ui-icon-arrowthick-2-n-s">&nbsp;</span>
              <?=strip_tags($root['name']);?>
              <input type="hidden" name="roots[]" value="<?=$root['id']?>"/>
            </li>
          <?endforeach?>
        </ul>
        <script type="text/javascript">
          $(function() {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
          });
        </script>
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