<div id="center" class="wrap">
  <?=html_flash_message();?>
  <h1>Вы успешно отписались от рассылки "Беременность по неделям"</h1>
  <p>Если Вы случайно отписались от нашей рассылки, просто кликните <a href="<?=site_url('востановить-подписку-на-рассылку');?>">сюда</a> и Вы снова будете подписаны на нашу рассылку Беременность по неделям.</p>
  <p>Если Вы сделали это осознанно, пожалуйста, сообщите нам причину Вашего решения:</p>
  <form action="<?=site_url('причина-отписки')?>" method="post">
    <textarea name="reason" style="width: 100%; height: 120px; margin-bottom: 15px;"></textarea>
    <button type="submit" class="def-but green-but">Отправить</button>
  </form>
</div>