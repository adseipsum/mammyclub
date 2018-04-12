<div class="popup-form">
  <h1 class="title">Ваш e-mail необходимо активировать!</h1>
  <?=html_flash_message();?>
  <div class="col-wrap">
    <p>Вам выслано письмо на электронный адрес <b><?=$authEntity['auth_info']['email'];?></b>.</p>
    <?=isset($settings['email_confirm_text']) && !empty($settings['email_confirm_text'])?$settings['email_confirm_text']:'';?>
    <p>Для окончания регистрации, пожалуйста, перейдите по ссылке, которая содержится в письме.</p>
    <p>Если письма нет в вашем ящике, проверьте папку спам или попробуйте <a href="<?=site_url('переслать-подтверждение-емейла')?>">переслать его еще раз</a></p>
    <p>Если письмо долго не приходит - <a class="ajaxp-exclude" target="_blank" href="<?=site_url('связаться-с-нами')?>">сообщите нам</a> и мы постараемся оперативно вас зарегистрировать</p>
  </div>
</div>