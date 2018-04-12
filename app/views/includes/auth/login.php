<? $savedPost = get_saved_post(); ?>
<div class="popup-form popup-login">
  <form method="post" action="<?=site_url('входим');?>" class="validate">
    <h1 class="title">Вход</h1>
    <?=html_flash_message();?>
    <div class="col-wrap">
      <p class="def-p">Для входа введите ваш e-mail и пароль:</p>
      <div class="input-row">
        <label>E-mail:</label>
        <input type="email" class="text required" value="<?=!empty($savedPost['email'])?hsc($savedPost['email']):'';?>" name="email" />
      </div>
      <div class="input-row">
        <label>Пароль:</label>
        <input type="password" class="text required" value="" name="password" />
      </div>
      
       <? if(isset($captcha_image)): ?>
         <div class="input-row captcha-row">
           <?=$captcha_image;?><input type="text" name="captcha"/>
         </div>
       <? endif; ?>

      <table class="action-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="td-1">
            <input id="remember" type="checkbox" class="checkbox js-checkbox cp" name="remember_me" value="1"/><label class="remember cp" for="remember">Запомнить меня на этом компьютере</label>
            <p><a href="<?=site_url('забыли-пароль')?>">Забыли пароль?</a></p>
            <p>Нет пароля? <a href="<?=site_url('регистрация');?>">Зарегистрируйтесь!</a></p>
          </td>
          <td class="td-2">
            <button type="submit" class="h-but orange-but">Войти</button>
          </td>
        </tr>
      </table>

    </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-checkbox').ezMark();
  });
</script>




