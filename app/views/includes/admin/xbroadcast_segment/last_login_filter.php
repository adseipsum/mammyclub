<? // LAST LOGIN [EARLIER|NOTEARLIER] THAN X DAYS ?>

<select class="select js_last_login_filter_period" name="<?=$name?>[operator]" style="width: 20%">
  <option value=""><?=lang('admin.add_edit.xbroadcastsegment.last_login_filter.period.default')?></option>
  <option value="<?=htmlentities('>')?>"<?=!empty($entity[$name]['operator']) && $entity[$name]['operator'] == '>' ? ' selected="selected"' : '' ;?>>Раньше</option>
  <option value="<?=htmlentities('<')?>"<?=!empty($entity[$name]['operator']) && $entity[$name]['operator'] == '<' ? ' selected="selected"' : '' ;?>>Позже</option>
</select>

<div style="display:<?=empty($entity[$name]['operator'])?'none':'inline'?>;">
  <span>чем</span>
  <input class="js_last_login_filter_days" type="number" min="1" step="1" name="<?=$name?>[days]" value="<?=!empty($entity[$name]['operator'])?$entity[$name]['days']:1?>" style="width: 5%" />
  <span>дней</span>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js_last_login_filter_period').change(function() {
      $('.js_last_login_filter_days').attr('disabled', 'disabled').parent().hide();
      if ($(this).val() != '') {
        $('.js_last_login_filter_days').removeAttr('disabled').parent().css('display', 'inline');
      }
    });
  });
</script>