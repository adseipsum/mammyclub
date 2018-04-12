<div class="comment-box" id="comments">
  <h2 class="title-to-top"><span class="t-1"><?=is_shop() ? 'Отзывы' : 'Комментарии';?></span><span class="top-link js-go-to-top"></span></h2>
  <? if(!empty($comments)): ?>
    <?=html_flash_message();?>

    <ul class="comment-list">
      <? foreach ($comments as $c): ?>
        <?
          add_utf_params_to_shop_links($c['content'], $entityType . '_' . $entityId);
        ?>
        <li class="item" style="padding-left: <?=$c['level']*8.44156;?>%" id="commentid_<?=$c['id'];?>">
          <table cellspacing="0" cellpadding="0" border="0" itemprop="comment" itemscope itemtype="http://schema.org/comment">
            <tr>
              <td class="td-1">
                <img class="ava" src="<?=site_image_thumb_url('_small', $c['user']['image']);?>" alt="<?=$c['user']['name'];?>" />
<!--                <span class="date">--><?//=ago($c['date']);?><!--</span>-->
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
    </ul>
  <? else: ?>
    <p><?=is_shop() ? 'Отзывы' : 'Комментарии';?> отсутствуют.</p>
  <? endif; ?>

  <div class="tac">
    <span class="def-but green-but js-show-comment">Добавить <?=is_shop() ? 'отзыв' : 'комментарий';?></span>
  </div>

  <ul class="js-comment-form comment-form" style="display: none;">
    <li class="js-comment-form-stub">
      <form id="commentForm" class="js-nb" action="<?=site_url('добавить-комментарий');?>" method="post">
        <textarea rows="20" cols="20" name="comment" id="myText"></textarea>
        <img id="js-submit-preloader" src="<?=site_img('preloader.gif')?>" style="width: 80px; padding-right: 10px; float: left; margin-top: 20px; display: none" />
        <button style="margin: 10px 0px 30px;" class="def-but green-but fl" name="Submit" type="submit">Опубликовать <?=is_shop() ? 'отзыв' : 'комментарий';?></button>
        <span class="a-like cancel js-cancel">Отмена</span>
        <input type="hidden" name="parent_id" value=""/>
        <input type="hidden" name="entity_id" value="<?=$entityId;?>"/>
        <input type="hidden" name="entity_type" value="<?=$entityType;?>"/>
      </form>
      <div class="clear"></div>
    </li>
  </ul>

  <script async type="text/javascript" src="<?=site_js("tinymce/tinymce.min.js");?>"></script>
  <script type="text/javascript">

    $(document).ready(function() {

      $('.comment-box .js-answer').click(function() {
        answerToComment($(this));
      });

      $('.js-show-comment').click(function() {
        commentsAction($(this));
      });

      $(document).on('submit','.js-nb',function() {
        $('.fl').prop('disabled', true);
        $('#js-submit-preloader').show();
      });

      $('.js-cancel').click(function() {
        commentCancel();
      });

    	$(function() {
    	  var validator = $("#commentForm").submit(function() {
    	    // update underlying textarea before submit validation
    	    tinymce.triggerSave();
    	  }).validate({
    	    ignore: "",
    	    rules: {
    	      comment: "required"
    	    },
    	    errorPlacement: function(label, element) {}
    	  });
    	  validator.focusInvalid = function() {
      	  // put focus on tinymce on submit validation
      	  if( this.settings.focusInvalid ) {
        	  try {
        	    var toFocus = $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []);
        	    if (toFocus.is("textarea")) {
        	      tinyMCE.get(toFocus.attr("id")).focus();
          	  } else {
          	    toFocus.filter(":visible").focus();
          	  }
      	    } catch(e) {
      	    // ignore IE throwing errors when focusing hidden elements
      	    }
      	  }
    	  }
    	});
    });
  </script>

</div>