<? if (!empty($comments)): ?>
  <? foreach ($comments as $c): ?>
    <? if (isset($c['published']) && $c['published'] == false) {
	      continue;
    }?>
    <li class="item" style="padding-left: <?=$c['level']*8.44156;?>%" id="commentid_<?=$c['id'];?>">
      <table cellspacing="0" cellpadding="0" border="0" itemprop="comment" itemscope itemtype="http://schema.org/comment">
        <tr>
          <td class="td-1">
            <img class="ava" src="<?=site_image_thumb_url('_small', $c['user']['image']);?>" alt="<?=$c['user']['name'];?>" />
            <span class="date"><?=ago($c['date']);?></span>
          </td>
          <td class="td-2">
            <? if ($c['user']['status'] == "expert"): ?>
              <span itemprop="author" itemscope itemtype="http://schema.org/Person"><span class="expert-name">
              <span itemprop="name">
                <? if(!empty($c['user']['google_url'])): ?>
                  <a itemprop="sameAs" href="<?=$c['user']['google_url'];?>"><?=$c['user']['name'];?></a>
                <? else: ?>
                  <?=$c['user']['name'];?>
                <? endif; ?>
              </span>

              <span class="status">(<?=$status['expert'];?>)</span></span></span>
            <? else: ?>
              <span itemprop="author" itemscope itemtype="http://schema.org/Person"><span class="name" itemprop="name"><?=$c['user']['name'];?></span></span>
            <? endif; ?>
            <div class="text html-content" itemprop="text"><?=$c['content'];?></div>
            <span class="js-answer answer">ответить</span>
            <? if(!empty($admin)): ?>
              <span style="float: right;">
               <a target="_blank" href="<?=admin_site_url(strtolower($entityType) . 'comment/add_edit/' . $c['id']);?>">Редактировать в админке</a>
              </span>
            <? endif; ?>
          </td>
        </tr>
      </table>

      <? if($c['level'] > 0): ?>
        <span class="first"></span>
      <? endif; ?>

    </li>
  <? endforeach; ?>

  <script type="text/javascript">
    $(document).ready(function() {
        $('.comment-box .js-answer').not('.js-processed').each(function() {
          	$(this).click(function() {
          	    $('.comment-box .js-answer').show();
                $(this).hide();
                $('.js-show-comment').hide();
                removeTiny('#myText');
                $('.js-show-comment').parent().show();
                $('.js-comment-form').hide();
                $('.js-comment-form-stub input[name="parent_id"]').val($(this).parents('li:first').attr('id').replace('commentid_', ''));
                $(this).parents('li:first').after($('.js-comment-form-stub'));
                $('.js-comment-form-stub').show();
                initTiny('#myText');
                $(this).addClass('js-processed');
          	});
        });
    });
  </script>
<?endif;  ?>