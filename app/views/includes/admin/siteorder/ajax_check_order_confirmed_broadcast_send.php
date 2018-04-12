<div class="default-box js-order-send-but">
  <div class="title">
    Отправить письмо Ваш заказ подтвержден!?
  </div>
  <button value="1">Да</button><button value="0">Нет</button>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-order-send-but button').click(function() {
      if ($('.js-siteorder-form').length > 0) {
        $('.js-siteorder-form').removeClass('js-not-processed-payment-pending');
        $('.js-siteorder-form').removeClass('js-not-processed-client-confirmed');
        if ($(this).val() == 1) {
          $('.js-siteorder-form').append('<input type="hidden" name="send_order_confirmed_broadcast" value="1" />');
        }
        $('.js-siteorder-form').submit();
      } else {
        if ($(this).val() == 1) {
          $.ajax({
            type: 'POST',
            url: base_url + admin_url + '/siteorder/ajax_process_broadcast/order_confirmed',
            data: {'id': '<?=$siteorder['id']?>'}
          });
        }
        $('.close-ajaxp-modal').click();
      }
    });
  });
</script>