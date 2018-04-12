<div class="group">
  <div class="select_container">

    <label class="label"><?=lang("admin.add_edit." . $entityName . "." . $key)?></label>
    <span id="js-multiple-select-select-all-<?=$id?>" class="popup-link cp" style="right: 75px;"><?=lang('admin.add_all_clear');?></span>
    <? if(isset($fields[$key]['relation']['entity_name']) && (!isset($params['no_add_button']) || !$params['no_add_button'])): ?>
      <a class="popup-link" href="<?=admin_site_url(strtolower($fields[$key]['relation']['entity_name']) . '/add_popup?eid=' . $id)?>" target="_blank" onClick="return showPopup(this.href, 780, 320);"><?=lang('admin.add');?></a>
    <? endif; ?>

    <select class="chzn-select <?=isset($params['class']) ? $params['class'] : ''?>" id="<?=$id?>" name="<?=$name?>[]" multiple="multiple" style="width: 100%;" data-placeholder="<?=lang("admin.add_edit." . $entityName . "." . $key . ".default")?>">
    <?if (!empty($entity[$key])) :?>
      <?foreach($entity[$key] as $ent):?>
      	<?if (is_array($ent) && isset($ent['id'])) : // For a relation?>
      	  <?
      	     if(!isset($ent['name'])) {
      	       $relNameValue = isset($ent[$fields[$key]['relation']['name_field']])?$ent[$fields[$key]['relation']['name_field']]:$ent['id'];
      	     } else {
               $relNameValue = $ent['name'];
             }
      	  ?>
        	<option selected="selected"  value="<?=$ent['id']?>"><?=$relNameValue;?></option>
        <?else : // For an array?>
          <option selected="selected" value="<?=$ent?>"><?=htmlspecialchars(lang("admin.add_edit." . $entityName . "." . $key . "." . $ent));?></option>
        <?endif?>
      <?endforeach?>
    <?endif?>
    <?foreach ($params['from'] as $k => $v) :?>
      <option value="<?=$k?>"><?=htmlspecialchars($v)?></option>
    <?endforeach?>
    </select>
    <? if(!empty($message)): ?><span class="description"><?=$message?></span><? endif; ?>
  </div>

  <? if(isset($params['relation']['sort']) && $params['relation']['sort']): ?>
    <div class="ascdes_container">
      <button class="button moveup" type="button" >
        &nbsp;<img src="<?=site_img("admin/icons/top.png")?>" alt="Move up"/>
      </button>
      <button class="button movedown" type="button">
        &nbsp;<img src="<?=site_img("admin/icons/down.png")?>" alt="Move down"/>
      </button>
    </div>
  <? endif; ?>
  <div class="clear"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    //  cache selects for use later
    var selects = $('#<?=$id?>');
    // whenever the selection changes, either disable or enable the
    // option in the other selects
    selects.chosen({no_results_text: "<?=lang('admin.no_items')?>"});
    selects.chosen().change(function() {
      var selected = [];
      // add all selected options to the array in the first loop
      selects.find("option").each(function() {
          if (this.selected) {
              selected[this.value] = this;
          }
      })
      // then either disabled or enable them in the second loop:
      .each(function() {
          // if the current option is already selected in another select disable it.
          // otherwise, enable it.
          this.disabled = selected[this.value] && selected[this.value] !== this;
      });
      // trigger the change in the "chosen" selects
      //selects.trigger("liszt:updated");
    });

    $('#js-multiple-select-select-all-<?=$id?>').click(function() {

      if ($('#<?=$id?> option[selected="selected"]').length > 0) {
        $('#<?=$id?> option').each(function() {
          $(this).removeAttr('selected');
        });
        $('#<?=$id?>_chzn .search-choice').remove();
      } else {
        $('#<?=$id?> option').each(function() {
          $(this).attr('selected', 'selected');
        });
      }
      $('#<?=$id?>').trigger('liszt:updated');
    });

  });
</script>
