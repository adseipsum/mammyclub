<?
  $head = '';
  if (isset($templates[0]['type']) && $templates[0]['type'] == SMS_TEMPLATE_TTN) {
    $head = 'Отправка SMS номера ТТН';
    /*
     * Changes in text
     * {ttn} - номер ТТН
     * {name} - Имя
     * {order} - Номер заказа
     */
    $from = array('{ttn}', '{name}', '{order}', '  ');
    $to = array($ttn, $order['fio'], $order['code'], '');
    $select = '';
    $i = 0;
    foreach ($templates as $template){
      $templates[$i]['text'] = str_replace($from, $to, $template['text']);
      $i++;
      if ($template['is_default'] < 0) {
        $select = $select . '<option value="' . $template['id'] . '" selected="selected">' . $template['name'] . '</option>';
      } else {
        $select = $select . '<option value="' . $template['id'] . '">' . $template['name'] . '</option>';
      }
    }
  }
  if (isset($templates[0]['type']) && $templates[0]['type'] == SMS_TEMPLATE_PAYMENT) {
    $head = 'Отправка реквизитов для оплаты';
    /*
     * Changes in text
     * {sum} - Сумма заказа
     * {name} - Имя
     * {order} - Номер заказа
     */
    $from = array('{sum}', '{name}', '{order}', '  ');
    $to = array($order['total'], $order['fio'], $order['code'], '');
    $select = '';
    $i = 0;
    foreach ($templates as $template) {
      $templates[$i]['text'] = str_replace($from, $to, $template['text']);
      $i++;
      if ($template['is_default'] < 0) {
        $select = $select.'<option value="' . $template['id'] . '" selected="selected">' . $template['name'] . '</option>';
      } else{
        $select = $select.'<option value="' . $template['id'] . '">' . $template['name'] . '</option>';
      }
    }
  }
?>

<script id="sms-script">
  $('#change-template').change(function() {
  	var ajaxUrl = base_url + admin_url + '/siteorder/change_sms_template';
  	var order = $('#modal-wrap').attr('order');
  	var template = $(this).val();
  	$.ajax({
  		type: 'POST',
      url: ajaxUrl,
      data: {'order': order, 'template': template},
      success: function(data) {
      	$('#sms_text').text(data);
      }
  	});
  });

  $('#sendsms').submit(function(event) {
  	event.preventDefault();
    var form = $(this).serialize();
    var ajaxUrl = base_url + admin_url + '/siteorder/send_sms?' + form;
    $.get(ajaxUrl, function(data) {
    	$('#sms_text').html(data).css('color', '#0F0');
			if(data == 'ok'){
      	setTimeout(function() {
      		$('#modal-wrap').remove();
      		$('#sms-style').remove();
      		$('#sms-script').remove();
  		  }, 800);
			}
  	});
  });
</script>

<style media="screen" type="text/css" id="sms-style">
  .modal-wrap {
    position: fixed;
    top: 0px;
    bottom: 0px;
    right: 0px;
    left: 0px;
  }
  .modal-in{
    width: 400px;
    margin: 50px auto 0px;
    background: #FFF;
    border: 1px solid #CCC;
    padding: 20px;
  }
  .modal-in h3{
    margin: 0px 0px 10px;
    font-size: 18px;
  }
  .modal-in .modal-body{
    width: 100%;
    padding-bottom: 20px;
  }
  .modal-in select{
    width: 160px;
    float: left;
  }
  .modal-in input{
    width: 200px;
    float: right;
  }
  .modal-in textarea{
    width: 388px;
    margin-top: 10px;
    margin-bottom: 10px;
    padding: 5px;
  }
  .modal-in .remmodal{
    float: left;
  }
  .modal-in .submitsms{
    float: right;
  }
  .modal-in button:hover{
    cursor: pointer;
  }
</style>

<div class="modal-wrap" id="modal-wrap" order="<?=$order['id'];?>">
	<div class="modal-in">
		<div class="modal-head">
			<h3><?=$head;?></h3>
		</div>
		<div class="modal-body">
			<form method="post" id="sendsms">
        <select id="change-template">
          <?=$select;?>
        </select>
        <input type="text" name="phone" value="<?=$order['phone'];?>"><br>
        <textarea rows="4" id="sms_text" name="smstext"><?=$templates[0]['text'];?></textarea>
      </form>
      <button class="remmodal" id="remove-modal" onclick="$('#modal-wrap').remove(); $('#sms-style').remove(); $('#sms-script').remove();">Отменить</button>
			<button type="submit" form="sendsms" value="Submit" class="submitsms" id="submitsms">Отправить</button>
		</div>
	</div>
</div>