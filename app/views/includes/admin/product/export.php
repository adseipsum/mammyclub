<div class="content default-box">
  <div class="title">
    <span class="fl"><?=lang('admin.export.' . $entityName . ".form_title");?></span>
    <? if ($import) :?>
     <ul class="act-list">
        <li>
          <a href="<?=site_url($importUrl);?>"><?=lang('admin.import');?></a>
        </li>
      </ul>
    <? endif;?>
    <?if ($backUrl) :?>
      <a class="link" href="<?=site_url($backUrl);?>"><?=lang('admin.add_edit_back');?></a>
    <?endif;?>
  </div>
  <?=html_flash_message();?>

  <?=$this->view("includes/admin/parts/before_entity_list.php"); ?>

  <div class="inner export">
    <form action="<?=site_url($processLink);?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">

      <div class="fields">
        <h2><?=lang('admin.export.fields');?></h2>
        <p><?=lang('admin.export.fields.description');?></p>
        <div class="checkboxes">
          <? $count = 0; ?>
          <? $nestedFields = array_keys($nestedFields); ?>
          <? foreach ($fields as $k => $params): ?>
            <div class="ex-box">
              <input class="checkbox" type="checkbox" name="<?=$nestedFields[$count]?>" id="<?=$entityName?>_<?=$k?>" value="1" checked="checked"/>
              <label class="cp" for="<?=$entityName?>_<?=$k?>"><?=lang('admin.add_edit.' . $entityName . '.' . $k);?></label>
            </div>
            <? $count++; ?>
          <? endforeach; ?>
          <div class="clear"></div>
        </div>

        <ul class="e-list">
          <li><a href="#" id="select_all"><?=lang('admin.export.select_all');?></a></li>
          <li><a href="#" id="deselect_all" ><?=lang('admin.export.deselect_all');?></a></li>
          <? if($importExcludeFields): ?>
            <script type="text/javascript">
              var importExcludeFields = [<?='"' . implode('","', $importExcludeFields) . '"'?>];
            </script>
            <li><a href="#" id="select_all_import"><?=lang('admin.export.select_all_import');?></a></li>
          <? endif; ?>
          <? if($requiredFields['simple']): ?>
            <script type="text/javascript">
              var requiredFields = [<?='"' . implode('","', $requiredFields['simple']) . '"'?>];
            </script>
            <li><a href="#" id="select_all_required"><?=lang('admin.export.select_all_required');?></a></li>
          <? endif; ?>
        </ul>
        <div class="clear"></div>
      </div>

      <div class="clear"></div>
      <div class="fields oa">
        <h2><?=lang('admin.export.file_preview');?></h2>
        <table border="1" cellpadding="5" cellspacing="0" id="table" width="100%">
          <tr>
          </tr>
          <tr>
          </tr>
        </table>
        <div class="clear"></div>
      </div>

      <div class="clear"></div>
      <div class="export">
        <h2><?=lang('admin.export.export');?></h2>
        <p><?=lang('admin.export.export.description');?></p>
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.export');?>"/><?=lang('admin.export');?>
        </button>
      </div>
      <div class="clear"></div>
    </form>
  </div>
</div>
<div class="clear"></div>

<script type="text/javascript">
  $(document).ready(function() {

    $('.e-list li:last').addClass('last');


    $('#select_all').click(function() {
      $('.checkboxes .checkbox:not(:disabled)').attr('checked', 'checked');
      refreshTable();
      return false;
    });

    $('#deselect_all').click(function() {
      $('.checkboxes .checkbox:not(:disabled)').removeAttr('checked');
      refreshTable();
      return false;
    });

    $('#select_all_import').click(function() {
      $('.checkboxes .checkbox:not(:disabled)').each(function(){
        if ($.inArray($(this).attr('name'), importExcludeFields) < 0) {
          $(this).attr('checked', 'checked');
        } else {
          $(this).removeAttr('checked');
        }
      });
      refreshTable();
      return false;
    });

    $('#select_all_required').click(function() {
      $('.checkboxes .checkbox:not(:disabled)').each(function(){
        if ($.inArray($(this).attr('name'), requiredFields) < 0) {
          $(this).removeAttr('checked');
        } else {
          $(this).attr('checked', 'checked');
        }
      });
      refreshTable();
      return false;
    });

    $('.checkboxes .checkbox:not(:disabled)').change(function(){
      refreshTable();
    });

    function refreshTable() {
      clearTable();
      drawTable();
    };

    function clearTable() {
      $('#table').find('tr:first').empty();
      $('#table').find('tr:last').empty();
    }

    function tableAddRow(value) {
      $('#table').find('tr:first').append('<td>' + value + '</td>');
      $('#table').find('tr:last').append('<td>...</td>');
    }

    function drawTable() {
      $('.checkboxes .checkbox:checked').each(function(){
        tableAddRow($(this).next('label').html());
      });
    }

    drawTable();

    $('.button').click(function(){
      if ($('.checkboxes .checkbox:not(:disabled):checked').length == 0) {
        return false;
      }
    });


  });
</script>
