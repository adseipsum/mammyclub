<div class="content default-box">
  <div class="title">
    Изменить приоритет
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </div>
  <?=html_flash_message();?>
  <div class="inner">

    <? $this->view("includes/admin/parts/before_entity_list.php"); ?>

    <?/* if(!empty($allParameters)): ?>
      <div class="group" style="margin-bottom: 15px;">
        <label class="label">Параметр</label>
        <select name="<?=$entityName;?>.id" class="select">
          <option value="">-- Не выбрано --</option>
          <? foreach ($allParameters as $k => $v): ?>
            <option  <?= (isset($_GET[$entityName . '.id']) && $_GET[$entityName . '.id'] == $k) ? 'selected="selected"' : ''?> value="<?=$k?>"><?=$v?></option>
          <? endforeach; ?>
        </select>
      </div>
      <script type="text/javascript">
        $(document).ready(function() {
          $('.select[name="<?=$entityName;?>.id"]').change(function() {
            window.location.href = '<?=current_url();?>?<?=$entityName;?>.id=' + $(this).val();
            return false;
          });
        });
      </script>
    <? endif; */?>

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