<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>

<div class="group array-field js-param-links-box"<?=empty($entity['parameter_link'])?' style="display: none;"':'';?>>

  <? $rowViewPath = 'includes/admin/parameter_product_links_array_row';?>

  <ol>
    <? $i = 0; ?>
    <? if(!empty($entity[$key])): ?>
      <? foreach($entity[$key] as $value): ?>
        <li class="js-active-li">
          <div class="removable-field">
            <?=$this->view($rowViewPath, array('value' => $value, 'disabled' => FALSE, 'count' => $i), TRUE)?>
            <a class="remove-field">
              <img src="<?=site_img('admin/icons/cross.png')?>" title="<?=lang('admin.array.remove_field');?>" />
            </a>
            <div class="clear"></div>
          </div>
        </li>
        <? $i++; ?>
      <? endforeach; ?>
    <? endif; ?>
    <li class="sample" style="display:none;">
      <div class="removable-field">
        <?=$this->view($rowViewPath, array('value' => '', 'disabled' => TRUE, 'count' => $i), TRUE)?>
        <a class="remove-field">
          <img src="<?=site_img('admin/icons/cross.png')?>" title="<?=lang('admin.array.remove_field');?>" />
        </a>
        <div class="clear"></div>
      </div>
  	</li>
  </ol>

  <button class="button add-field">
    <img src="<?=site_img("admin/icons/add.png")?>"/><?=lang('admin.array.add_field');?>
  </button>

	<div class="clear"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#product_parameter_link').change(function() {

      var that = this;
      $.get( base_url + admin_url + "/" + entityName + "/ajax_possible_parameters", { pId: $(this).val() } , function( data ) {
        var html = '<option value=""><?=lang('admin.filter.result.default')?></option>';
        var arr = $.parseJSON(data);
        if(arr.length > 0) {
          for (i = 0; i < arr.length; ++i) {
            html += '<option value="' + arr[i]['id'] + '">' + arr[i]['name'] + '</option>';
          }
        }
        $(that).parents('form').find('.js-param-linked-pv-select').each(function() {
          console.log($(this));
         // Update refresh select options
          $(this).html(html).trigger("liszt:updated");
        });

      });

      if($(this).val() !== "") {
        $('.js-param-links-box').show();
        $('.js-param-links-box').find('li').each(function() {
          if(!$(this).hasClass('sample') && !$(this).hasClass('active-result')) {
            $(this).remove();
          }
        });
      } else {
        $('.js-param-links-box').find('li').each(function() {
          if(!$(this).hasClass('sample') && !$(this).hasClass('active-result')) {
            $(this).remove();
          }
        });
        $('.js-param-links-box').hide();
      }

    });
  });
</script>
