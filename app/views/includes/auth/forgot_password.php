<div class="popup-form popup-forgot-pass">
  <h1 class="title">Востановление пароля</h1>
  <?=html_flash_message();?>
  <div class="col-wrap">
    <p class="def-p">Введите свой E-mail для востановления пароля:</p>
    <form action="<?=site_url('востановление-пароля')?>" method="post" class="validate" autocomplete="off">
      <div class="input-row">
        <input type="text" class="text required email" value="" name="email" />
        <button type="submit" class="def-but orange-but">Выслать пароль</button>
      </div>
    </form>
    <p class="small">Вернуться к <a href="<?=site_url('вход');?>">входу</a>.</p>
  </div>
</div>