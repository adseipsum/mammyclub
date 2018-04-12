<div class="popup-form popup-login">
  <h1 class="title">Информация о производителе</h1>
</div>
<div class="brand-description">
  <div class="html-content">
    <? if (isset($brand['image']) && !empty($brand['image'])): ?>
      <img class="fl" style="margin: 0 3%;" src="<?=site_image_thumb_url('_medium', $brand['image']);?>" />
    <? endif; ?>
    <?=$brand['description'];?>
  </div>
</div>

<script type="text/javascript">
  $('.article-example .html-content a').addClass('ajaxp-exclude');
</script>