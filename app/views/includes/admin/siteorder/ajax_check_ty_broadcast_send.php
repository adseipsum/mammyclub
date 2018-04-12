<div class="default-box js-ty-send-but">
  <div class="title">
    Отправить письмо Спасибо за покупку!?
  </div>
  <p>Имя получателя: <input type="text" name="name" value="<?=$siteorder['fio'];?>" /></p>
  <button value="1">Да</button><button value="0">Нет</button>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-ty-send-but button').click(function() {
      var name = $('.js-ty-send-but input[name="name"]').val();
      if ($('.js-siteorder-form').length > 0) {
        $('.js-siteorder-form').removeClass('js-not-processed-<?=SITEORDER_STATUS_DELIVERED;?>');
        if ($(this).val() == 1) {
          $('.js-siteorder-form').append('<input type="hidden" name="send_ty_broadcast" value="' + name + '" />');
          $('.js-siteorder-form').append('<input type="hidden" name="user" value="<?=$siteorder['user_id'];?>" />');
        }
        $('.js-siteorder-form').submit();
      } else {
        if ($(this).val() == 1) {
        	$.ajax({
        		type: 'POST',
            url: base_url + admin_url + '/siteorder/ajax_process_broadcast/ty',
            data: {'id': '<?=$siteorder['id']?>', 'user': '<?=$siteorder['user_id'];?>', 'send_ty_broadcast': name}
        	});
        }
        $('.close-ajaxp-modal').click();
      }
    });
  });
</script>