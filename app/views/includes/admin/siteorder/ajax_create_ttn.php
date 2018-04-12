<form action="<?=(admin_site_url('siteorder/create_ttn/' . $siteOrderId));?>" method="post" class="form validate" >
  <div class="default-box js-order-send-but">
    <div class="title">
      <? if (isset($siteOrder) && !empty($siteOrder['ttn_code'])) : ?>
        <span style="color: #e81e13;">В заказе уже указан ТТН: <?=$siteOrder['ttn_code']?>, при подтвержении он заменится.</span>
      <? else : ?>
        Создать ТТН
      <? endif; ?>
    </div>
    <p>Общий вес: <input type="text" name="weight" value="1"/></p>
    <p>Общий объем отправления: <input type="text" name="volume_general" value="0.002"/></p>
    <? if (!$siteOrder['paid']): ?>
        <p>Контроль оплат: <input type="checkbox" name="payment_control" value="1"/></p>
    <? endif; ?>
  </div>
  <div class="group">
    <button class="button" type="submit" name="save" value="1">Создать</button>
    <button id="cancel" class="button">Отменить</button>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('#cancel').click(function(event) {
      event.preventDefault();
      $('.close-ajaxp-modal').click();
    })
  });
</script>