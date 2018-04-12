<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>

<? if(!empty($fields[$key]['relation']['fields'])): ?>
  <div class="group array-field js-external-entity-field">
  	<label class="label" for="<?=str_replace('.', '_', $id)?>"><?=$label?></label>

    <? $rowViewPath = 'includes/admin/parts/fields/external_entity_row';?>

    <ol>
      <? $i = 0; ?>
      <? if(!empty($entity[$key])): ?>
        <? foreach($entity[$key] as $value): ?>
          <li>
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
      if($('#patientadditionalfield_type').length > 0) {
        var addfieldTypes = ['select', 'multipleselect_chosen'];
        var selectedType = $('#patientadditionalfield_type').val();
        $('.js-external-entity-field').hide();
        if(addfieldTypes.indexOf(selectedType) != -1) {
          $('.js-external-entity-field').show();
        }
        $('#patientadditionalfield_type').on('change', function() {
          if(addfieldTypes.indexOf(this.value) != -1) {
            $('.js-external-entity-field').fadeIn();
          } else {
            var childLiElements = $('.js-external-entity-field ol').children('li:not(.sample)');
            if(childLiElements.length > 0) {
              childLiElements.each(function() {
                $(this).find('input,select,textarea').attr('disabled', 'disabled');
              });
            }
            $('.js-external-entity-field').fadeOut();
          }
        });
      }
    });
  </script>
<? endif; ?>