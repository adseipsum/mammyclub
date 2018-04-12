<? if(!empty($articles)): ?>
  <ul class="art-list" id="js-list">

    <? $i = 1;?>
    <? foreach ($articles as $a): ?>
      <?
        if ($i == count($articles) && (isset($a['is_read']) && $a['is_read'] == TRUE)) {
          $css = " class=\"last read\"";
        } elseif ($i == count($articles)) {
          $css = " class=\"last\"";
        } elseif (isset($a['is_read']) && $a['is_read'] == TRUE) {
          $css = " class=\"read\"";
        } else {
          $css = "";
        }
      ?>
      <li<?=$css;?>>
        <p class="title"><a href="<?=site_url($a['page_url']);?>"><?=$a['name'];?></a></p>
        <div class="i-row">
          <? if($category['id'] != $a['category']['id']): ?>
            <span>Подкатегория: <a href="<?=site_url($a['category']['page_url'])?>"><?=$a['category']['name'];?></a></span>
          <? endif; ?>
          <? if($a['comment_count'] > 0): ?>
            <a class="comment" href="<?=site_url($a['page_url'] . '#comments')?>"><?=number_noun($a['comment_count'], 'comments');?></a>
          <? endif; ?>
        </div>
        <table class="desc desc-w-img" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <? if(!empty($a['image'])): ?>
              <td class="td-1">
                <a href="<?=site_url($a['page_url']);?>"><img src="<?=site_image_thumb_url('_medium', $a['image']);?>" alt="<?=$a['name'];?>" /></a>
              </td>
              <td class="td-2">
                <p>
                  <?=truncate(strip_tags($a['content']), 400, '...');?>
                </p>
              </td>
            <? else: ?>
              <td class="td-2">
                <p>
                  <?=truncate(strip_tags($a['content']), 600, '...');?>
                </p>
              </td>
            <? endif; ?>
          </tr>
        </table>
      </li>
      <? $i++; ?>
    <? endforeach; ?>

    <li class="ajaxContent tac" id="<?=site_url('аджакс-догрузка/' . uri_string() . get_get_params());?>" style="border: none; width: 100%; display: none; position: absolute; bottom: -80px; left: 0;"><img src="<?=site_img('preloader.gif');?>"  alt="loading..." title="loading..." width="160px" height="24px"/></li>

    <? if(count($articles) == $perPage): ?>
      <script type="text/javascript">
        $(document).ready(function() {
          $('.ajaxContent:first').contentloader({url: $('.ajaxContent:first').attr('id')});
        });
      </script>
    <? endif; ?>

  </ul>
<? else: ?>
  <div class="intro-3 html-content">
    <p>Статьи этой категории будут добавлены в скором времени... :)</p>
  </div>
<? endif; ?>