<? $savedPost = get_saved_post(); ?>

<div class="breadcrumbs">
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb first" href="<?=site_url();?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a></span>
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb"  href="<?=site_url('консультации');?>"><span class="s-1"></span><span class="s-2" itemprop="title">Консультация</span><span class="s-3"></span></a></span>
  <span class="crumb active"><span class="s-1"></span><span class="s-2">Задать вопрос</span><span class="s-3"></span></span>
</div>

<div class="add-question-box">
  <?=html_flash_message();?>
  <? if(!empty($settings['add_question_text_top'])): ?>
    <div class="intro-2 html-content">
      <?=$settings['add_question_text_top'];?>
    </div>
  <? endif; ?>
  <? if ($_COOKIE['country'] == 'UA'): ?>
    <form id="questionForm" action="<?=site_url('добавление-вопроса');?>" method="post">
      <div class="input-row theme-row pr">
        <label>Тема вопроса<span>*</span>:</label>
        <input type="text" name="qName" value="<?=!empty($savedPost['qName'])?hsc($savedPost['qName']):'';?>" class="text required">
      </div>

      <label class="title">Ваш вопрос<span style="color: red; font-size: 14px; line-height: 14px;">*</span>:</label>
      <textarea <? /*rows="20" cols="20"*/?> style="height: 300px; width: 100%;" name="content" id="myText"><?=!empty($savedPost['content'])?hsc($savedPost['content']):'';?></textarea>

      <table class="action-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <? if(!empty($settings['add_question_text_bottom'])): ?>
            <td class="td-1">
              <div class="html-content">
                <?=$settings['add_question_text_bottom'];?>
              </div>
            </td>
          <? endif; ?>
          <? if($isLoggedIn == FALSE): ?>
            <td class="td-2">
              <div>
                <div class="input-row input-row-email pr" style="float: left;">
                  <label for="question-email">Ваш email<span style="color: red; font-size: 14px; line-height: 14px;">*</span>:</label>
                  <input type="text" class="text required email" placeholder="Введите свой email" value="" name="email" id="question-email" />
                  <p class="description">*для уведомления об ответе</p>
                </div>
                <a class="def-but orange-but js-submit fl" style="padding: 9px;">Зарегистрироваться<br />и задать вопрос</a>
                <? if(empty($settings['add_question_text_bottom'])): ?>
                  <div class="clear"></div>
                  <div class="cancel-question-3"><span class="a-like js-cancel">Отмена</span></div>
                <? else: ?>
                  <div class="clear"></div>
                  <div class="cancel-question"><span class="a-like js-cancel">Отмена</span></div>
                <? endif; ?>
              </div>
            </td>
          <? else: ?>
            <td class="td-2">
              <a class="def-but orange-but js-submit wsn fr" style="padding: 9px;">Задать свой вопрос</a>
              <div class="clear"></div>
              <div class="cancel-question-2"><span class="a-like js-cancel">Отмена</span></div>
            </td>
          <? endif; ?>
        </tr>
      </table>
    </form>
  </div>

  <script type="text/javascript" src="<?=site_js("tinymce/tinymce.min.js");?>"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      tinymce.init({
        selector: '#myText',
        language : 'ru',
        toolbar: "undo redo | bold italic | bullist numlist outdent indent blockquote | link jbimages | emoticons",
        menubar : false,
        statusbar : false,
        plugins: "emoticons, link, jbimages, paste",
        image_upload_action: "<?=site_url('загрузить-изображение');?>",
        content_css : "<?=site_css('html-content.css');?>",
        relative_urls: false,
        plugins: "emoticons, link, jbimages, paste",
        // Paste options
        paste_auto_cleanup_on_paste : true,
        paste_retain_style_properties: "all",
        paste_webkit_styles: "all",
        paste_retain_style_properties: "color font-size",
        //
        remove_script_host: false,
    		// update validation status on change
    		onchange_callback: function(editor) {
    		  tinymce.triggerSave();
    		  $("#" + editor.id).valid();
    		}
      });

      $('.js-submit').click(function() {
        $('#questionForm').submit();
      });

      $('.js-cancel').click(function() {
        window.location.replace(document.referrer);
      });

    	$(function() {
    	  var validator = $("#questionForm").submit(function() {
    	    // update underlying textarea before submit validation
    	    tinymce.triggerSave();
    	  }).validate({
    	    ignore: "",
    	    rules: {
    	      content: "required"
    	    },
    	    errorPlacement: function(label, element) {
      	    if (element.is("textarea")) {
        	    if(!$('label.error').length > 0) {
        	      $('p.title').append(label);
        	    }
      	    }
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
<? else: ?>
  <div class="html-content">
    <?=$settings['add_question_not_ukraine'];?>
  </div>
<? endif; ?>