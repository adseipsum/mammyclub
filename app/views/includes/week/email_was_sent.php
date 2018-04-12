<div id="center" class="wrap">
  <?=html_flash_message();?>
  <h2>Письмо со статьей "<?=$article['name'];?>" было выслано на Ваш e-mail: <?=$user['auth_info']['email'];?></h2>
  <? if(!empty($content)): ?>
    <div class="html-content"><?=$content;?></div>
  <? endif; ?>
</div>