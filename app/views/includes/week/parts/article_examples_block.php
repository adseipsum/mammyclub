<?/*
<? if (url_equals('беременность-по-неделям1')): ?>
  <? $this->view("includes/week/parts/subscribe_button_1"); ?>
<? endif; ?>

<? if (url_equals('беременность-по-неделям2')): ?>
  <? $this->view("includes/week/parts/subscribe_button_2"); ?>
<? endif; ?>
*/?>
<? if (url_equals('беременность-по-неделям')): ?>
  <? $this->view("includes/week/parts/subscribe_button_1"); ?>
<? endif; ?>

<? if (isset($articleExample) && !empty($articleExample)): ?>
  <? $counter = 1; ?>
  <h2 class="title-to-top"><span class="t-1">Примеры статей</span></h2>

  <ul class="example-art-list">
    <? foreach ($articleExample as $ae): ?>
      <? if (!empty($ae['image'])): ?>
        <li<?=$counter % 3 == 0 ? ' class="last"' : '';?>>
          <span data-ajaxp-url="<?=site_url(uri_string() . '/пример-статьи/' . $ae['article']['id']);?>" class="link cp">
            <img src="<?=site_image_thumb_url('_small', $ae['image'])?>" alt="" title="" />
            <span class="zoom"></span>
          </span>
        </li>
        <? $counter++; ?>
      <? endif; ?>
    <? endforeach; ?>
  </ul>
  <div class="clear"></div>
<? endif; ?>