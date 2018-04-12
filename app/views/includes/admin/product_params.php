<?
  if (!empty($entity['product_params'])) {
    $data = unserialize($entity['product_params']);
  }
?>
<div class="group">
  <label class="label" for="param1">Поле 1</label>
  <input type="text" maxlength="255" value="<?=(isset($data['name1']) && !empty($data['name1'])) ? $data['name1'] : '';?>" class="text-field" name="param1" id="param1">
</div>
<div class="group">
  <label class="label" for="param2">Поле 2</label>
  <input type="text" maxlength="255" value="<?=(isset($data['name2']) && !empty($data['name2'])) ? $data['name2'] : '';?>" class="text-field" name="param2" id="param2">
</div>
<button<?=(isset($data['data']) && !empty($data['data'])) ? ' style="display: none;"' : '';?> type="button" class="button" id="params_button"><img alt="Сохранить" src="http://localhost/web/images/admin/icons/tick.png">Заполнить значения</button>
<div class="clear"></div>

<table<?=(isset($data['data']) && !empty($data['data'])) ? '' : ' style="display: none;"';?> id="params_table">
  <tr>
   <td id="table_header_1">
     <p id="name-1"><?=(isset($data['name1']) && !empty($data['name1'])) ? $data['name1'] : '';?></p>
   </td>
   <td id="table_header_2">
     <p id="name-2"><?=(isset($data['name2']) && !empty($data['name2'])) ? $data['name2'] : '';?></p>
   </td>
   <td>
   </td>
  </tr>
  <? if (isset($data['data']) && !empty($data['data'])): ?>
    <? foreach ($data['data'] as $key => $value): ?>
      <? foreach ($value as $v): ?>
        <tr>
         <td>
           <input type="text" maxlength="255" value="<?=$key;?>" class="text-field " name="params1[]">
         </td>
         <td>
          <input type="text" maxlength="255" value="<?=$v;?>" class="text-field " name="params2[]">
         </td>
         <td>
          <span class="a-like cp remove_param">-</span>
         </td>
        </tr>
      <? endforeach; ?>
    <? endforeach; ?>
  <? else: ?>
    <tr>
      <td>
       <input type="text" maxlength="255" value="" class="text-field " name="params1[]">
      </td>
      <td>
      <input type="text" maxlength="255" value="" class="text-field " name="params2[]">
      </td>
      <td>
        <span class="a-like cp remove_param">-</span>
      </td>
    </tr>
  <? endif; ?>
</table>

<button<?=(isset($data['data']) && !empty($data['data'])) ? ' style="margin: 10px 0;"' : ' style="display: none; margin: 10px 0;';?> type="button" class="button" id="add_param"><img alt="Сохранить" src="http://localhost/web/images/admin/icons/tick.png">Добавить еще значения</button>
<div class="clear"></div>

<script type="text/javascript">
  $(document).ready(function() {

	$('#params_button').click(function() {
      $(this).hide();
	  $('#name-1').html($('#param1').val());
      $('#name-2').html($('#param2').val());
	  $('#params_table').show();
	  $('#add_param').show();
	});

	$('#add_param').live('click', function() {
		var tr = $('#params_table tr:last').clone();
		tr.find('input[name=params2[]]').val('');
		$('#params_table').append(tr);
		return false;
	});  

	$('.remove_param').live('click', function() {
		$(this).parents('tr:first').remove();
	});  

  });
</script>