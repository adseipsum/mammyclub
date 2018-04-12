<div style="padding: 20px 0px;">
  <? if (!empty($email_appeal)):?>
    <div style="padding: 0px 20px;">
      <span style="font-size: 20px; color: #4da04d"><?=$email_appeal;?></span>
    </div>
  <? endif; ?>

  <? if (!empty($email_intro)):?>
    <div style="padding: 0px 20px; font-style: italic; font-size: 13px;">
      <?=$email_intro;?>
    </div>
  <? endif; ?>

  <? if (!empty($email_main_text)):?>
    <div style="padding: 10px 20px;">
      <?=$email_main_text;?>
    </div>
  <? endif; ?>

  <? if (!empty($email_outro)):?>
    <div style="padding: 0px 20px; font-style: italic; font-size: 13px;">
      <?=$email_outro;?>
    </div>
  <? endif; ?>
</div>