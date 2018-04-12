<div class="clear"></div>

<? if (!empty($settings['shop_header_text'])): ?>
  <div class="intro-3">
    <?=$settings['shop_header_text'];?>
  </div>
<? endif; ?>

<? if (!empty($settings['shop_header_quote'])): ?>
  <div class="quote-box">
    <p class="q-1"><?=$settings['shop_header_quote'];?><span class="q-icon-1"></span><span class="q-icon-2"></span></p>
    <p class="q-2"><span class="author"><?=$settings['shop_header_quote_author'];?></span>, <?=$settings['shop_header_quote_author_position'];?>.</p>
  </div>
<? endif; ?>