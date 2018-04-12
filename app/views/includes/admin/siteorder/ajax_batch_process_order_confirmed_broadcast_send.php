<div class="default-box">
  <div class="title">
    Отправить письмо Ваш заказ <?=$broadcastText;?> !?
  </div>
  <button value="1">Да</button><button value="0">Нет</button>
</div>
<script>
    $('button').on('click', function () {
      if ($(this).attr('value') == 1) {
        $(batchProccesForm).append('<input type="hidden" name="popup_value" value="1">')
        $(batchProccesForm).unbind('submit').submit()
      }
      if ($(this).attr('value') == 0) {
        $(batchProccesForm).append('<input type="hidden" name="popup_value" value="0">')
        $(batchProccesForm).unbind('submit').submit()
      }
    })
</script>
