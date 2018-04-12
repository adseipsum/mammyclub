<div class="popup-form">
  <?=html_flash_message();?>
  <h1 class="title">Спасибо за то, что подписались на нашу рассылку!</h1>
  <div class="col-wrap">

    <? if(!empty($broadcasts)): ?>

      <? if(count($broadcasts) == 1): ?>
        <p>Письмо со статьей "<?=$broadcasts[0]['subject'];?>" уже было выслано на Ваш адрес <b><?=$authEntity['auth_info']['email'];?></b></p>
      <? else: ?>
        <p>Письма со статьями: </p>
        <ul>
          <? foreach ($broadcasts as $b): ?>
            <li>"<?=$b['subject'];?>"</li>
          <? endforeach; ?>
        </ul>
        <p>уже были высланы на Ваш адрес <b><?=$authEntity['auth_info']['email'];?></b></p>
      <? endif; ?>

      <p><b>Желаем Вам приятного чтения!</b></p>
      <? if(count($broadcasts) == 1): ?>
        <p>Если письма нет в Вашем ящике, проверьте папку спам или попробуйте <a href="<?=site_url(FIRST_YEAR_BROADCAST_RESEND_PROCESS . '/' . $fullWeeks);?>">переслать его еще раз</a></p>
      <? else: ?>
        <p>Если писем нет в Вашем ящике, проверьте папку спам или попробуйте <a href="<?=site_url(FIRST_YEAR_BROADCAST_RESEND_PROCESS . '/' . $fullWeeks);?>">переслать их еще раз</a></p>
      <? endif; ?>
      <p>Если письмо долго не приходит - <a href="<?=site_url('связаться-с-нами');?>">сообщите нам</a> и мы постараемся оперативно вам помочь</p>

    <? endif; ?>

  </div>
</div>