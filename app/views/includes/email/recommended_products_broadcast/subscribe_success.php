<div class="popup-form">
  <?=html_flash_message();?>
  <h1 class="title">Спасибо за то, что подписались на нашу рассылку!</h1>
  <div class="col-wrap">
    <p>Письмо со статьей "<?=$broadcastSubject;?>" уже было выслано на Ваш адрес <b><?=$authEntity['auth_info']['email'];?></b></p>
    <p><b>Желаем Вам приятного чтения!</b></p>
    <p>Если письма нет в Вашем ящике, проверьте папку спам или попробуйте <a href="<?=site_url(RECOMMENDED_PRODUCTS_BROADCAST_RESEND_LETTER);?>">переслать его еще раз</a></p>
    <p>Если письмо долго не приходит - <a href="<?=site_url('связаться-с-нами');?>">сообщите нам</a> и мы постараемся оперативно вам помочь</p>
  </div>
</div>