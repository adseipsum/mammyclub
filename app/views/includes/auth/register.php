<? $savedPost = get_saved_post(); ?>
<div class="popup-form popup-register">
  <form method="post" action="<?=site_url('процесс-регистрации');?>" class="validate">
    <h1 class="title">Зарегистрируйтесь и получите:</h1>
    <?=html_flash_message();?>
    <div class="col-wrap">
      <div class="html-content">
        <ul>
          <li>Ответы экспертов на Ваши вопросы в разделе Консультации;</li>
          <li>Доступ к циклу статей "Беременность по неделям";</li>
          <li>Скидки на товары в нашем магазине;</li>
        </ul>
      </div>
      <div class="input-row">
        <input type="email" class="text required email" placeholder="Введите свой email" value="<?=!empty($savedPost['email'])?hsc($savedPost['email']):'';?>" name="email" />
        <button type="submit" class="def-but orange-but">Зарегистрироваться</button>
        <span>или</span>
        <a href="<?=site_url('вход')?>">Войти</a>
      </div>
      <p class="small">Мы гарантируем вам соблюдение условий <a target="_blank" class="ajaxp-exclude" href="<?=site_url('пользовательское-соглашение');?>">пользовательского соглашения и правил безопасного хранения данных</a>.</p>
    </div>
  </form>
</div>


