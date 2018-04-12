<div id="center" class="wrap">
  <?=html_flash_message();?>
  <h1>Вы успешно отписались от рассылки "Первый год жизни"</h1>
  <p>Если Вы случайно отписались от нашей рассылки, просто кликните <a href="<?=site_url(FIRST_YEAR_BROADCAST_RESUBSCRIBE_PROCESS);?>">сюда</a> и Вы снова будете подписаны на нашу рассылку "Первый год жизни".</p>
  <p>Если Вы сделали это осознанно, пожалуйста, сообщите нам причину Вашего решения:</p>
  <form action="<?=site_url(FIRST_YEAR_BROADCAST_UNSUBSCRIBE_REASON_PROCESS)?>" method="post">
    <textarea name="reason" style="width: 100%; height: 120px; margin-bottom: 15px;"></textarea>
    <button type="submit" class="def-but green-but">Отправить</button>
  </form>
</div>