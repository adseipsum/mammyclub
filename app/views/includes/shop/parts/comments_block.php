<div class="comment-box" id="comments">
  <h2 class="title-to-top" style="margin: 0 0 10px 0;"><span class="t-1">Отзывы о нашем магазине</span><span class="top-link js-go-to-top"></span></h2>

  <div class="tac">
    <span class="def-but green-but js-show-comment">Добавить отзыв</span>
  </div>

  <ul class="js-comment-form comment-form" style="display: none;">
    <li class="js-comment-form-stub" style="margin: 0 0 15px 0;">
      <form id="commentForm" class="js-nb" action="<?=site_url('добавить-комментарий');?>" method="post">
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
        <input type="hidden" name="entity_type" value="<?=$entityType;?>"/>
      </form>
      <div class="clear"></div>
    </li>
  </ul>

  <? if(!empty($comments)): ?>
    <?=html_flash_message();?>

    <ul class="comment-list">
      <?=$this->view('includes/shop/parts/comments_list', array('comments' => $comments))?>

      <li class="ajaxContent tac" id="<?=shop_url('аджакс-догрузка-комментариев/' . get_get_params());?>" style="border: none; width: 100%; display: none; position: absolute; bottom: -80px; left: 0;"><img src="<?=site_img('preloader.gif');?>"  alt="loading..." title="loading..." width="160px" height="24px"/></li>

      <? if(isset($pager) && $pager->haveToPaginate()): ?>
        <script type="text/javascript">
          $(document).ready(function() {
            $('.ajaxContent:first').contentloader({url: $('.ajaxContent:first').attr('id')});
          });
        </script>
      <? endif; ?>

    </ul>
  <? else: ?>
    <p>Отзывы отсутствуют.</p>
  <? endif; ?>

</div>

<?
  $tinyMCEUrl = site_js("tinymce/tinymce.min.js");
  if(SUBDOMAIN == 'shop' && ENV != 'TEST') {
    $tinyMCEUrl = shop_url('web/js/tinymce/tinymce.min.js');
  }
?>
<script async type="text/javascript" src="<?=$tinyMCEUrl;?>"></script>
<script type="text/javascript">
  $(document).ready(function() {

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
    	    if (element.is("input[name='email']")) {
      	    $('.input-row').append(label);
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