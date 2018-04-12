<div class="view-pages">
  <h2 class="title-to-top ">
    <span class="t-1">Связаться с нами</span>
  </h2>

  <div class="order-box contact-us-page">
    <form action="<?=site_url('процесс-связи')?>" method="post" class="validate js-validate">
      <?=html_flash_message();?>
      <div class="input-row">
        <label for="input-email">E-mail<span class="arr">*</span>:</label>
        <input type="text" name="email" id="input-email" class="required text email" <?=(isset($email) && !empty($email)) ? "value=\"$email\"" : "";?>/>
      </div>

      <div class="input-row">
        <label for="input-message">Сообщение<span class="arr">*</span>:</label>
        <textarea id="input-message" name="message" class="required"></textarea>
      </div>

      <button type="submit" class="h-but orange-but">Отправить</button>
    </form>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-validate').validate();
  });
</script>