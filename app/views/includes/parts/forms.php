<? if(!empty($forms) && $isLoggedIn == FALSE): ?>
  <meta name="viewport" content="format-detection=no,initial-scale=1.0,maximum-scale=2.0,user-scalable=no,width=device-width;" />

  <? if(isset($forms[FORM_SUBSCRIBE])):?>

    <div class="js-subscribe-form register-big-box subscribe-form slide-subscribe-form visible-box">
      <div class="hack-box js-close-2-subscribe-form"></div>
      <div class="form-heading">
        <h2 class=""><?=$forms[FORM_SUBSCRIBE]['title'];?></h2>
        <span class="js-close-subscribe-form"></span>
      </div>
      <div class="cont">
        <div class="content">
          <? if(!empty($forms[FORM_SUBSCRIBE]['text'])): ?>
            <?=$forms[FORM_SUBSCRIBE]['text'];?>
          <? endif; ?>
        </div>
        <form method="post" action="<?=site_url('процесс-регистрации');?>" class="js-register-block-form validate pr">

          <table class="ib-table" cellspacing="0" cellpadding="0" border="0">
            <? if (!empty($weeks)): ?>
              <tr>
                <td class="td-1">
                  <div class="input-row">
                    <label>Ваша неделя беременности*:</label>
                    <select name="pregnancyweek_id" class="required">
                      <option value="">- Выберите неделю -</option>
                      <? foreach($weeks as $w):?>
                        <option value="<?=$w['id']?>"><?=$w['number']?> неделя</option>
                      <? endforeach; ?>
                    </select>
                    <div class="clear"></div>
                  </div>
                </td>
                <td class="td-2"></td>
              </tr>
            <? endif; ?>
            <tr>
              <td class="td-1">
                <input class="text required email" type="email" name="email" placeholder="<?=$forms[FORM_SUBSCRIBE]['placeholder_text'];?>" value="" />
              </td>
              <td class="td-2">
                <button type="submit" class="h-but orange-but">Зарегистрироваться</button>
              </td>
            </tr>
          </table>
          <button type="submit" class="h-but orange-but hidden-but">Подписаться</button>
        </form>
      </div>
    </div>

    <script type="text/javascript">

      function subscribe_form_slide_in() {
        $('.js-subscribe-form').animate({
          right: "20%"
        }, 500);
      }

      function subscribe_form_slide_out() {
        if ($(window).width() > 680) {
          $('.js-subscribe-form').animate({
            right: "-460px"
          }, 500);
        } else {
          $('.js-subscribe-form').animate({
            right: "-204px"
          }, 500);
        }
      }

      var subscribeFormWasClosed = $.cookie('mammyclub_subscribe_form_closed');

      var subscribeTimeout = 0;
      <? if(!empty($forms['subscribe']['timeout'])): ?>
        subscribeTimeout = <?=$forms['subscribe']['timeout'];?>;
      <? endif; ?>

      $(document).ready(function() {

        $('.js-register-block-form-sidebar').ajaxp2FormSubmit();

        if(!subscribeFormWasClosed) {
          setTimeout(subscribe_form_slide_in, subscribeTimeout*1000);
        } else {
          if ($(window).width() > 680) {
            $('.js-subscribe-form').css('right', '-460px');
          } else {
            $('.js-subscribe-form').css('right', '-204px');
          }
          $('.js-subscribe-form').addClass('hidden-box');
          $('.js-subscribe-form').removeClass('visible-box');
        }

        $('.js-close-subscribe-form').click(function() {

          subscribe_form_slide_out();

          setTimeout(function(){
            $('.js-subscribe-form').addClass('hidden-box');
            $('.js-subscribe-form').removeClass('visible-box');
          },500);

        	if (!subscribeFormWasClosed) {
        	  $.cookie('mammyclub_subscribe_form_closed', '1', { path: '/'});
        	}
        });

        $('.js-close-2-subscribe-form').click(function() {
          subscribe_form_slide_in();
          $('.js-subscribe-form').removeClass('hidden-box');
          $('.js-subscribe-form').addClass('visible-box');
        });

      });
    </script>

  <? endif; ?>

  <? if (isset($forms[FORM_FULLSCREEN])): ?>
    <script type="text/javascript">
      function fullscreen_form_show() {
        $('.js-full-screen-form').show();
        $('body').css('overflow', 'hidden');
        $.cookie('<?=COOKIE_FORM_FULLSCREEN_CLOSED;?>', '1', { path: '/'});
      }
      function fullscreen_form_hide() {
        $('.js-full-screen-form').hide();
        $('body').css('overflow', 'auto');
      }
      var fullscreenFormWasClosed = $.cookie('<?=COOKIE_FORM_FULLSCREEN_CLOSED;?>');
      var fullscreenTimeout = 0;
      <? if(!empty($forms[FORM_FULLSCREEN]['timeout'])): ?>
        fullscreenTimeout = <?=$forms[FORM_FULLSCREEN]['timeout'];?>;
      <? endif; ?>
      $(document).ready(function() {
        if (!fullscreenFormWasClosed) {
          $('.js-register-block-form').ajaxp2FormSubmit();
          setTimeout(fullscreen_form_show, fullscreenTimeout*1000);
          $('.js-close-fullscreen-form').click(function() {
            fullscreen_form_hide();
          });
        }
      });
    </script>
    <form method="post" action="<?=site_url('процесс-регистрации');?>" class="js-register-block-form validate pr">
      <div class="full-screen-form js-full-screen-form" style="display: none;">
        <table class="full-screen-table" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td>
              <div class="in">
                <div class="in-pad">
                  <p class="title"><?=$forms[FORM_FULLSCREEN]['title'];?></p>
                  <? if (!empty($forms[FORM_FULLSCREEN]['text'])): ?>
                    <p class="text"><?=$forms[FORM_FULLSCREEN]['text'];?></p>
                  <? endif; ?>
                  <form method="post">
                    <? if (!empty($weeks)): ?>
                      <div class="input-row">
                        <label>Ваша неделя беременности*:</label>
                        <select name="pregnancyweek_id" class="required">
                          <option value="">- Выберите неделю -</option>
                          <? foreach($weeks as $w):?>
                            <option value="<?=$w['id']?>"<?=isset($article['pregnancyweek_id'])&&$article['pregnancyweek_id']==$w['id']?' selected="selected"':'';?>><?=$w['number']?> неделя</option>
                          <? endforeach; ?>
                        </select>
                        <div class="clear"></div>
                      </div>
                    <? endif; ?>
                    <div class="input-row">
                      <input class="text required email" type="email" name="email" placeholder="E-mail">
                    </div>
                    <button class="h-but orange-but" type="submit">Подписываюсь</button>
                    <br/><br/>
                    <span class="a-like js-close-fullscreen-form">Нет, спасибо</span>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </form>
  <? endif; ?>

<? endif; ?>

<? if(isset($forms[FORM_SHARE])):?>
  <div class="js-share-form share-form">
    <div class="wrap pr">
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="td-1">
            <p><?=$forms['share']['title'];?></p>
          </td>
          <td class="td-2">
            <script type="text/javascript" src="//yandex.st/share/share.js"charset="utf-8"></script>
            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="none" data-yashareType="big" data-yashareQuickServices="vkontakte,facebook,odnoklassniki,moimir,moikrug,gplus"></div>
          </td>
        </tr>
      </table>
      <span class="js-close-share-form close-handler"></span>
    </div>
  </div>

  <script type="text/javascript">
    function share_form_slide_in() {
      $('.js-share-form').animate({
        bottom: "0"
      }, 500);
    }
    function share_form_slide_out() {
      $('.js-share-form').animate({
        bottom: "-20%"
      }, 500);
    }

    <? if(isset($forms['share'])):?>
      var shareTimeout = 0;
      <? if(!empty($forms['share']['timeout'])): ?>
        shareTimeout = <?=$forms['share']['timeout'];?>;
      <? endif; ?>
      $(document).ready(function() {
        setTimeout(share_form_slide_in, shareTimeout*1000);
        $('.js-close-share-form').click(function() {
          share_form_slide_out();
        });
      });
    <? endif; ?>
  </script>

<? endif; ?>

<? if(isset($forms[FORM_STATIC_SUBSCRIBE]) && !$isLoggedIn):?>
  <div class="js-static-subscribe-template" style="display: none;">

    <!-- DESKTOP -->
    <div id="js-register-bot-form" class="border-box distribution-box distribution-2 distribution-3 unique-form">
      <div class="cont">
        <div class="theme html-content">
          <p>
            <?=$forms[FORM_STATIC_SUBSCRIBE]['title'];?>
          </p>
        </div>
        <table cellspacing="0" cellpadding="0" border="0" class="p-table">
          <tr>
            <td class="td-1">
              <form method="post" class="validate js-register-block-form-2" action="<?=site_url('процесс-регистрации');?>">
                <div class="input-row">
                  <label>Ваш Email*:</label>
                  <input type="text" class="text required email" name="email" />
                </div>
                <? if (isset($weeks) && !empty($weeks)): ?>
                  <div class="input-row">
                    <label>Ваша текущая неделя беременности*:</label>
                    <select name="pregnancyweek_id" class="required">
                      <option value="">- Выберите неделю -</option>
                      <? foreach($weeks as $w):?>
                        <option value="<?=$w['id']?>"><?=$w['number']?> неделя</option>
                      <? endforeach; ?>
                    </select>
                  </div>
                <? endif; ?>
                <div class="input-row">
                  <button style="width: 75%; margin: 0px auto; display: block;" type="submit" class="def-but orange-but">Зарегистрироваться</button>
                </div>
                <p class="small">
                  Нажимая "Зарегистрироваться и подписаться" вы соглашаетесь с <a href="<?=site_url('пользовательское-соглашение');?>" target="_blank">правилами хранения персональной информации</a>
                </p>
              </form>
            </td>
            <? if(!empty($forms[FORM_STATIC_SUBSCRIBE]['text'])): ?>
              <td class="td-2">
                <?=$forms[FORM_STATIC_SUBSCRIBE]['text'];?>
              </td>
            <? endif; ?>
          </tr>
        </table>
      </div>
      <div class="bottom-row"></div>
    </div>
    <!-- DESKTOP -->
  
    <!-- MOBILE -->  
    <div id="js-register-bot-form-mobile" class="mobile-subscribe-box" style="margin-bottom: 10px;">
      <div class="border-box distribution-box distribution-2">
        <div class="cont">
          <div class="theme html-content">
            <p>
              <?=$forms[FORM_STATIC_SUBSCRIBE]['title'];?>
            </p>
          </div>
          <form method="post" class="validate js-register-block-form-2" action="<?=site_url('процесс-регистрации');?>">
            <div class="input-row">
              <label>Ваш Email*:</label>
              <input type="email" class="text required email" name="email" />
            </div>
            <? if (!empty($weeks)): ?>
              <div class="input-row">
                <label>Ваша текущая неделя беременности*:</label>
                <select name="pregnancyweek_id" class="required">
                  <option value="">- Выберите неделю -</option>
                  <? foreach($weeks as $w):?>
                    <option value="<?=$w['id']?>"><?=$w['number']?> неделя</option>
                  <? endforeach; ?>
                </select>
              </div>
            <? endif; ?>
            <div class="input-row">
              <button type="submit" class="def-but orange-but">Зарегистрироваться</button>
            </div>
            <p class="small">
              Нажимая "Зарегистрироваться и подписаться" вы соглашаетесь с <a href="<?=site_url('пользовательское-соглашение');?>" target="_blank">правилами хранения персональной информации</a>
            </p>
          </form>
        </div>
        <div class="bottom-row"></div>
      </div>
    </div>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.js-register-block-form-2').ajaxp2FormSubmit();
      });
    </script> 
    <!-- MOBILE -->
  
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      var placeholder = $('.js-static-subscribe-placeholder');
      if (placeholder.length > 0) {
        var templateHtml = $('.js-static-subscribe-template').html();
        placeholder.replaceWith(templateHtml);
        $('form.validate').each(function() {
          $(this).validate();
        });
      }
    });
  </script>
  
<? endif; ?>