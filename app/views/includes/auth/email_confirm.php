<div class="popup-form">
  <h1 class="title">Спасибо за вашу регистрацию!</h1>
  <?=html_flash_message();?>
  <div class="col-wrap">
    <p>Вам выслано письмо на электронный адрес <b><?=$authEntity['auth_info']['email'];?></b>.</p>
    <?=isset($settings['email_confirm_text']) && !empty($settings['email_confirm_text'])?$settings['email_confirm_text']:'';?>
    <p>Для окончания регистрации, пожалуйста, перейдите по ссылке, которая содержится в письме.</p>
    <p>Если письма нет в вашем ящике, проверьте папку спам или попробуйте <a href="<?=site_url('переслать-подтверждение-емейла')?>">переслать его еще раз</a></p>
    <p>Если письмо долго не приходит - <a class="ajaxp-exclude" target="_blank" href="<?=site_url('связаться-с-нами')?>">сообщите нам</a> и мы постараемся оперативно вас зарегистрировать</p>
  </div>
</div>

<? /*
<script type="text/javascript">
  isLoggedIn = 1;
  if (lastClickedElemet) {
    lastClickedElemet.click();
  }

  $('.js-email-wrong').click(function() {
    window.location.href = '<?=site_url('не-правильный-емейл')?>';
  });

  $('.js-wrong-email').click(function() {
    isLoggedIn = 0;
    $('.js-comment-form').hide();
    $('.comment-list .js-comment-form-stub').hide();
    $('.comment-box .js-answer').show();
    $('.js-show-comment').parent().show();
  });

  <? if(isset($_GET['pregnancy_week']) && $_GET['pregnancy_week'] == TRUE): ?>
    // If on pregnancy week page
    $('.close-ajaxp-modal, .ajaxp-modal-bg').click(function() {
      window.location.href = '<?=site_url('статья-выслана-вам-на-почту');?>';
    });
  <? else: ?>
    $('.close-ajaxp-modal, .ajaxp-modal-bg').click(function() {
      if(strpos(window.location.href, 'not_confirmed=1')) {
        // If on add question page
        window.location = window.location.href.split("?")[0];
      } else {
        // If on article view page
        location.reload();
      }
    });
  <? endif; ?>

  function strpos (haystack, needle, offset) {
    var i = (haystack + '').indexOf(needle, (offset || 0));
    return i === -1 ? false : i;
  }

</script>
  */?>