<? if (!url_contains('статья') && !url_contains('консультация')): ?>
  <div class="js-broadcast-scroll-max"></div>
  <div class="border-box distribution-box numeric-box js-broadcast-block">
    <div class="cont">
      <div class="theme html-content"><?=$settings['right_part_broadcast_title'];?></div>
      <div class="html-content">
        <?=$settings['right_part_broadcast'];?>
      </div>
      <div class="tac">
        <a href="<?=site_url('беременность-по-неделям');?>" class="def-but orange-but">Узнать подробнее</a>
      </div>
    </div>
    <div class="bottom-row"></div>
  </div>
<? endif; ?>