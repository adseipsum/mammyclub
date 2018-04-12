<div class="register-big-box js-register-big-box <?=url_contains('неделя-беременности') ? 'small' : ''?>">
  <h2 class="title">
    <? if(!empty($settings['article_bottom_register_block_header'])): ?>
      <?=$settings['article_bottom_register_block_header'];?>
    <? else: ?>
      Хотите больше полезной информации о беременности и родах?
    <? endif; ?>
  </h2>
  <div class="cont">
    <div class="content">
      <? if(!empty($settings['article_bottom_register_block_content'])): ?>
        <?=$settings['article_bottom_register_block_content'];?>
      <? endif; ?>
    </div>
    <form method="post" action="<?=url_contains('/статья/') ? site_url('процесс-регистрации?type=in_bottom_of_article') : site_url('процесс-регистрации');?>" class="js-register-block-form validate pr">
      <input class="text required email" type="text" name="email" placeholder="Введите свой email" value="" />
      <button type="submit" class="h-but orange-but">Зарегистрироваться</button>
    </form>
    <? if(!empty($settings['article_bottom_register_block_agreement'])): ?>
      <div class="content">
        <?=$settings['article_bottom_register_block_agreement'];?>
      </div>
    <? endif; ?>
  </div>
</div>

<script type="text/javascript">
  $('.js-register-block-form').ajaxp2FormSubmit();
</script>