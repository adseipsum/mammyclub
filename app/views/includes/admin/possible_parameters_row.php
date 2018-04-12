<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>

<div class="group">

  <div class="js-param-container" style="<?=!isset($entity[$key]['parameter_main_id'])?'display: none; ':'';?>padding: 15px 15px 0 15px;">

    <label class="label">1) Основной параметр</label>
    <select class="js-possible-parameter-select" name="possible_parameters[parameter_main]"<?=!isset($entity[$key]['parameter_main_id'])?' disabled="disabled"':'';?>>
      <? foreach ($params['options'] as $k => $v): ?>
        <option value="<?=$k?>"<?=isset($entity[$key]['parameter_main_id']) && $entity[$key]['parameter_main_id'] == $k?' selected="selected"':'';?>><?=htmlspecialchars($v);?></option>
      <? endforeach; ?>
    </select>
    <a class="js-remove-field">
      <img src="<?=site_img('admin/icons/cross.png')?>" title="<?=lang('admin.array.remove_field');?>" />
    </a>
    <div class="clear"></div>
    <?=$this->view('includes/admin/possible_parameters_multiple_field.php', array('type' => 'main'), TRUE)?>

    <div class="js-param-container" style="<?=!isset($entity[$key]['parameter_secondary_id'])?'display: none; ':'';?>margin: 10px 0 0 0;">
      <label class="label">2) Дополнительный параметр</label>
      <select class="js-possible-parameter-select" name="possible_parameters[parameter_secondary]" class="js-secondary-param-select"<?=!isset($entity[$key]['parameter_secondary_id'])?' disabled="disabled"; ':'';?>>
        <? foreach ($params['options'] as $k => $v): ?>
          <option value="<?=$k?>"<?=isset($entity[$key]['parameter_secondary_id']) && $entity[$key]['parameter_secondary_id'] == $k?' selected="selected"':'';?>><?=htmlspecialchars($v);?></option>
        <? endforeach; ?>
      </select>
      <a class="js-remove-field">
        <img src="<?=site_img('admin/icons/cross.png')?>" title="<?=lang('admin.array.remove_field');?>" />
      </a>
      <div class="clear"></div>
      <?=$this->view('includes/admin/possible_parameters_multiple_field.php', array('type' => 'secondary'), TRUE)?>
    </div>

    <button class="button js-add-field" style="margin: 10px 0 0 0;<?=isset($entity[$key]['parameter_secondary_id'])?' display: none;':'';?>">
      <img src="<?=site_img("admin/icons/add.png")?>"/>Добавить дополнительный параметр
    </button>
  </div>

  <button class="button js-add-field"<?=isset($entity[$key]['parameter_main_id'])?' style="display: none;"':'';?>>
    <img src="<?=site_img("admin/icons/add.png")?>"/>Добавить основной параметр
  </button>

	<div class="clear"></div>
</div>

<script type="text/javascript">

  $(document).ready(function() {

    function possibleParamValuesInit() {
      $('.js-possible-parameter-select').change(function() {
        var that = this;
        $.get( base_url + admin_url + "/" + entityName + "/ajax_possible_parameters", { pId: $(this).val() } , function( data ) {
            var html = '';
            var arr = $.parseJSON(data);
            if(arr.length > 0) {
              for (i = 0; i < arr.length; ++i) {
                html += '<option value="' + arr[i]['id'] + '">' + arr[i]['name'] + '</option>';
              }
            }
            var $closestMultipleSelect = $(that).parent().find('.js-possible-parameter-values:first');
            // Update refresh select options
            $closestMultipleSelect.html(html).trigger("liszt:updated");
            // Delete selected options
            $closestMultipleSelect.next('.chzn-container').find('.search-choice').remove();
          });
      });
    }

    possibleParamValuesInit();

    $('.js-add-field').click(function() {
      var $containerEl = $(this).prev();
      var $selectEl = $containerEl.children('select').removeAttr('disabled');
      $containerEl.show();
      $selectEl.chosen();
      $(this).hide();
      return false;
    });

    $('.js-remove-field').click(function() {
      if(confirm('Удалить?')) {
        $(this).closest('.js-param-container').find('select').attr('disabled', 'disabled');
      	$(this).closest('.js-param-container').hide();
      	$(this).closest('.js-param-container').next().show();
      }
      return false;
    });

  });

</script>