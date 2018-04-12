<div class="popup-form popup-login">
  <h1 class="title"><?=$article['name'];?></h1>
</div>
<div class="view-item article-example">
  <div class="html-content">
    <?=$article['content'];?>
  </div>
</div>

<script type="text/javascript">
  if ($('.article-example .cont-small-box').length > 0) {
    $('.cont-small-box a.link').click(function() {
      var targetId = $(this).attr('href').split('#');
      targetId = targetId[1];
      $('.article-example').scrollTo('#' + targetId, {duration:500});
      return false;
    });
  }

  $('.article-example .js-go-to-top').click(function() {
    $('.article-example').scrollTo(0, 0, {duration:500});
    return false;
  });

  $('.article-example .html-content a').addClass('ajaxp-exclude');
</script>