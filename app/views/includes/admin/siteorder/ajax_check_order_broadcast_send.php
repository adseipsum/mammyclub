<div class="default-box js-order-send-but">
  <div class="title">
    Отправить письмо Ваш заказ отправлен!?
  </div>
  <p>Введите имя клиента: <input type="text" name="name" value="<?=$siteorder['first_name'];?>" /></p>
  <p>Введите номер ТТН: <input type="text" name="ttn_code" value="<?=$siteorder['ttn_code'];?>" /></p>
  <p>Введите номер телефона: <input type="text" name="phone" value="<?=$siteorder['phone'];?>" /></p>
  <button value="1">Да</button><button value="0">Нет</button>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-order-send-but button').click(function() {
      var name = $('.js-order-send-but input[name="name"]').val();
      var ttn_code = $('.js-order-send-but input[name="ttn_code"]').val();
      var phone = $('.js-order-send-but input[name="phone"]').val();
      if ($('.js-siteorder-form').length > 0) {
        $('.js-siteorder-form').removeClass('js-not-processed-<?=SITEORDER_STATUS_SHIPPED;?>');
        if ($(this).val() == 1) {
          $('.js-siteorder-form').append('<input type="hidden" name="send_order_broadcast" value="1" />');
          $('.js-siteorder-form').append('<input type="hidden" name="order_fio" value="' + name + '" />');
          $('.js-siteorder-form').append('<input type="hidden" name="order_ttn_code" value="' + ttn_code + '" />');
          $('.js-siteorder-form').append('<input type="hidden" name="order_phone" value="' + phone + '" />');
          $('.js-siteorder-form').append('<input type="hidden" name="user" value="<?=$siteorder['user_id'];?>" />');
        }
        $('.js-siteorder-form').submit();
      } else {
        if ($(this).val() == 1) {
        	$.ajax({
        		type: 'POST',
            url: base_url + admin_url + '/siteorder/ajax_process_broadcast/order',
            data: {'id': '<?=$siteorder['id']?>', 'user': '<?=$siteorder['user_id'];?>', 'send_order_broadcast': '1', 'fio': name, 'ttn_code': ttn_code, 'phone': phone}
        	});
        }
        $('.close-ajaxp-modal').click();
      }
    });
  });
</script>