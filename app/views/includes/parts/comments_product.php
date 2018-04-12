<div class="comment-box" id="comments">
  <h2 class="title-to-top"><span class="t-1"><?=is_shop() ? 'Отзывы' : 'Комментарии';?></span><span class="top-link js-go-to-top"></span></h2>
  <? if(!empty($comments)): ?>
    <?=html_flash_message();?>

    <ul class="comment-list">
      <? foreach ($comments as $c): ?>
        <li class="item" style="padding-left: <?=$c['level']*8.44156;?>%" id="commentid_<?=$c['id'];?>">
          <table cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td class="td-1">
                <img class="ava" src="<?=site_image_thumb_url('_small', $c['user']['image']);?>" alt="<?=$c['user']['name'];?>" />
                <span class="date"><?=ago($c['date']);?></span>
              </td>
              <td class="td-2">
                <? if ($c['user']['status'] == "expert"): ?>
                  <span class="expert-name"><?=$c['user']['name'];?> <span class="status">(<?=$status['expert'];?>)</span></span>
                <? else: ?>
                  <span class="name"><?=$c['user']['name'];?></span>
                <? endif; ?>
                <img style="margin: 0 0 0 5px;" src="<?=site_img('stars/star_' . ($c['rating']==0?0:$c['rating']) . '_small.png');?>" class="stars" alt="Рейтинг <?=$c['rating']==0?'0':$c['rating'];?>" />
                <div class="text html-content"><?=$c['content'];?></div>
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

        <input type="hidden" name="rating" value="">

        <textarea rows="20" cols="20" name="comment" id="myText"></textarea>

        <table class="add-product-comment-box com-pro-box" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <? if($isLoggedIn == FALSE): ?>
              <td class="td-2">
                <div>
                  <div class="input-row input-row-email pr">
                    <label for="comment-email">Ваш email<span style="color: red; font-size: 14px; line-height: 14px;">*</span>:</label>
                    <input type="text" class="text required email" placeholder="Введите свой email" value="" name="email" id="comment-email" />
                  </div>
                  <button class="def-but green-but wi" style="padding: 9px;">Зарегистрироваться и<br/>опубликовать отзыв</button>
                  <span class="a-like js-cancel">Отмена</span>
                </div>
              </td>
            <? endif; ?>

            <td class="td-1">
              <label>Оцените товар:</label>
              <div class="js-stars-select stars-select"></div>
            </td>

          </tr>

          <? if($isLoggedIn == TRUE): ?>
            <tr>
              <td class="td-2" style="padding-top: 0px;">
                <img id="js-submit-preloader" src="<?=site_img('preloader.gif')?>" style="width: 80px; padding-right: 10px;display: none;" />
                <button class="def-but green-but wsn" type="submit" style="padding: 9px;">Опубликовать отзыв</button>
                <span class="a-like js-cancel">Отмена</span>
              </td>
            </tr>
          <? endif; ?>
        </table>


        <input type="hidden" name="parent_id" value=""/>
        <input type="hidden" name="entity_id" value="<?=$entityId;?>"/>
        <input type="hidden" name="entity_type" value="<?=$entityType;?>"/>
      </form>
      <div class="clear"></div>
    </li>
  </ul>

  <link type="text/css" rel="stylesheet" media="screen" href="<?=site_js('packages/raty/jquery.raty.css');?>"/>
  <script type="text/javascript" src="<?=site_js("packages/raty/jquery.raty.js");?>"></script>
  <?
    $tinyMCEUrl = site_js("tinymce/tinymce.min.js");
    if(SUBDOMAIN == 'shop' && ENV != 'TEST') {
      $tinyMCEUrl = shop_url('web/js/tinymce/tinymce.min.js');
    }
  ?>
  <script async type="text/javascript" src="<?=$tinyMCEUrl;?>"></script>
  <script type="text/javascript">

    $(document).ready(function() {

      $('.js-stars-select').raty({
        path: '<?=site_js('packages/raty/images');?>',
        click: function(score, evt) {
          $('input[name="rating"]').val(score);
          $('.js-stars-select .error').remove();
        }
      });

      $('.comment-box .js-answer').click(function() {
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
      });

      $('.js-show-comment').click(function() {
        $('.comment-box .js-answer').show();
        removeTiny('#myText');
        $('.js-comment-form-stub input[name="parent_id"]').val('');
        $('.js-comment-form').append($('.js-comment-form-stub'));
        $('.js-comment-form-stub').show();
        initTiny('#myText');
        $('.js-comment-form').fadeIn();
        $(this).parent().hide();
      });

      $(document).on('submit','.js-nb',function() {
        $('.wsn').prop('disabled', true);
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
    	    focusCleanup: true,
    	    ignore: "",
    	    rules: {
    	      comment: "required"
    	    },
    	    errorPlacement: function(label, element) {
      //	    if (element.is("textarea")) {
      //  	    if(!$('label.error').length > 0) {
      //  	      $('p.title').append(label);
      //  	    }
      //	    }
      	    if (element.is("input[name='email']")) {
        	    $('.input-row').append(label);
      	    }
      	    if (element.is("input[name='rating']")) {
        	    $('.js-stars-select').append(label);
      	    }
    	    }
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