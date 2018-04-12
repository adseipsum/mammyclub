<div id="center" class="wrap">
  <?=html_flash_message();?>
  <div class="r-wide-part">
    <? if ($isLoggedIn == FALSE): ?>
      <div class="js-distribution-scroll-max"></div>
      <div class="border-box distribution-box distribution-2 js-distribution-box">
        <div class="cont">
          <div class="theme html-content">
            <p>
              Зарегистрируйтесь на нашем сайте и получите доступ к статье о Вашей неделе беременности прямо сейчас!
            </p>
          </div>
          <form method="post" class="validate js-register-block-form-sidebar" action="<?=site_url('процесс-регистрации');?>">
            <div class="input-row">
              <label>Ваш Email*:</label>
              <input type="text" class="text required email" name="email" />
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
      <script type="text/javascript">
        $(document).ready(function() {
          $('.js-register-block-form').ajaxp2FormSubmit();
        });
      </script>
    <? else: ?>
      <? if (empty($authEntity['pregnancyweek_id'])): ?>
        <div class="js-distribution-scroll-max"></div>
        <div class="border-box distribution-box distribution-2 js-distribution-box">
          <div class="cont">
            <div class="theme html-content">
              <p>
                Подпишитесь и получите доступ к статье о вашей текущей неделе беременности прямо сейчас!
              </p>
            </div>
            <form method="post" class="validate js-subscribe-block-form" action="<?=site_url('беременность-по-неделям/подписаться-на-рассылку');?>">
              <? if (!empty($weeks)): ?>
                <div class="input-row">
                  <label>Ваша текущая неделя беременности*:</label>
                  <select name="pregnancyweek_id" class="required">
                    <option value="">- Выберите неделю -</option>
                    <? foreach($weeks as $w):?>
                      <option value="<?=$w['number']?>"><?=$w['number']?> неделя</option>
                    <? endforeach; ?>
                  </select>
                </div>
              <? endif; ?>
              <button type="submit" class="def-but orange-but">Подписаться</button>
            </form>
          </div>
          <div class="bottom-row"></div>
        </div>
      <? else: ?>
        <? if (!empty($articles)): ?>
          <div class="border-box def-box numeric-box">
            <h2 class="title">Моя беременность по неделям</h2>
            <? if (isset($nextPregnancyArticleNotice) && !empty($nextPregnancyArticleNotice)): ?>
              <div class="title-2">
                <?=$nextPregnancyArticleNotice;?>
              </div>
            <? endif; ?>
            <ul class="qa-list" style="padding: 10px;">
              <? foreach ($articles as $article): ?>
                <li>
                  <a href="<?=site_url($article['page_url']);?>">Статья &quot;<?=$article['name'];?>&quot;</a>
                </li>
              <? endforeach; ?>
            </ul>
          </div>
        <? endif; ?>
      <? endif; ?>
    <? endif; ?>
  </div>
  <? /*
  <script type="text/javascript">
    $(document).ready(function() {
      var scrobllingBox = $('.js-distribution-box');
      if (scrobllingBox.length > 0) {
        sidebarBlockScrolling($('.js-distribution-box'));
      }
    });
  </script>
  */ ?>

  <div class="l-short-part"<?/*=$isLoggedIn && !empty($authEntity['pregnancyweek_id'])?' style="margin-right: 0;"':'';*/?>>
    <div class="inner-b view-item week-home-page">

      <div class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a itemprop="url" class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a>
        <span class="crumb active"><span class="s-1"></span><span class="s-2">Беременность по неделям</span><span class="s-3"></span></span>
      </div>

      <div class="html-content week-html-content">
        <?=$content;?>
      </div>

      <div class="clear"></div>
      <? if (!$isLoggedIn || ($isLoggedIn && empty($authEntity['pregnancyweek_current_id']))): ?>
          <? if ($isLoggedIn == FALSE): ?>
            <div id="js-register-bot-form-mobile" class="mobile-subscribe-box" style="margin-bottom: 10px;">
              <div class="border-box distribution-box distribution-2">
                <div class="cont">
                  <div class="theme html-content">
                    <p>
                      Зарегистрируйтесь на нашем сайте и получите доступ к статье о Вашей неделе беременности прямо сейчас!
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
          <? else: ?>
            <? if (empty($authEntity['pregnancyweek_current_id'])): ?>
              <div id="js-register-bot-form-week" class="border-box distribution-box distribution-2 js-register-bot-form-mobile" style="margin-bottom: 10px;">
                <div class="cont">
                  <div class="theme html-content">
                    <p>
                      Подпишитесь и получите доступ к статье о вашей текущей неделе беременности прямо сейчас!
                    </p>
                  </div>
                  <form method="post" class="validate js-subscribe-block-form" action="<?=site_url('беременность-по-неделям/подписаться-на-рассылку');?>">
                    <? if (!empty($weeks)): ?>
                      <div class="input-row">
                        <label>Ваша текущая неделя беременности*:</label>
                        <select name="pregnancyweek_id" class="required">
                          <option value="">- Выберите неделю -</option>
                          <? foreach($weeks as $w):?>
                            <option value="<?=$w['number']?>"><?=$w['number']?> неделя</option>
                          <? endforeach; ?>
                        </select>
                      </div>
                    <? endif; ?>
                    <button type="submit" class="def-but orange-but">Подписаться</button>
                  </form>
                </div>
                <div class="bottom-row"></div>
              </div>
            <? endif; ?>
          <? endif; ?>
      <? endif; ?>

      <? if (!$isLoggedIn): ?>
        <div id="js-register-bot-form" class="border-box distribution-box distribution-2 distribution-3">
          <div class="cont">
            <div class="theme html-content">
              <p>
                Зарегистрируйтесь на нашем сайте и получите доступ к статье о Вашей неделе беременности прямо сейчас!
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
                      <button style="width: 75%; margin: 0px auto; display: block;" type="submit" class="def-but orange-but">Зарегистрироваться</button>
                    </div>
                    <p class="small">
                      Нажимая "Зарегистрироваться и подписаться" вы соглашаетесь с <a href="<?=site_url('пользовательское-соглашение');?>" target="_blank">правилами хранения персональной информации</a>
                    </p>
                  </form>
                </td>
                <td class="td-2">
                  <h3 class="tac">Зарегистрируйтесь и получите:</h3>
                  <ul class="pluwki">
                    <li>Доступ к статье о Вашей текущей неделе беременности</li>
                    <li>Каждые 7 дней письмо на e-mail с новой статьей о Вашей новой неделе беременности</li>
                    <li>Ответы экспертов на Ваши вопросы в разделе Консультации</li>
                  </ul>
                </td>
              </tr>
            </table>


          </div>
          <div class="bottom-row"></div>
        </div>
      <? endif; ?>

      <div class="review-box">
        <h2 class="title-to-top" style="margin: 0 0 10px 0;"><span class="t-1">Отзывы наших читателей</span><span class="top-link js-go-to-top"></span></h2>
        <div id="оставить-комментарий" class="tac"><span class="def-but green-but js-review">Оставить свой отзыв</span></div>
        <ul id="написать-комментарий" class="js-review-form comment-form" style="display: none;">
          <li class="js-review-form-stub">
            <form id="reviewForm" action="<?=site_url('беременность-по-неделям/добавить-отзыв');?>" method="post">
              <textarea rows="20" cols="20" name="review" id="myText"></textarea>
              <img id="js-submit-preloader" src="<?=site_img('preloader.gif')?>" style="width: 80px; padding-right: 10px; float: left; margin-top: 20px; " />
              <button style="margin: 10px 0px 30px;" class="def-but green-but fl" type="submit">Опубликовать отзыв</button>
              <span class="a-like cancel js-cancel">Отмена</span>
            </form>
          </li>
        </ul>
        <ul class="review-list" id="js-list">
        <?=$this->view('includes/week/parts/review_list', array('reviews' => $reviews))?>
          <li class="ajaxContent tac" id="<?=site_url('аджакс-догрузка/' . uri_string() . get_get_params());?>" style="border: none; width: 100%; display: none; position: absolute; bottom: -80px; left: 0;"><img src="<?=site_img('preloader.gif');?>"  alt="loading..." title="loading..." width="160px" height="24px"/></li>
	        <? if(count($reviews) == $perPage): ?>
            <script type="text/javascript">
              $(document).ready(function() {
                $('.ajaxContent:first').contentloader({url: $('.ajaxContent:first').attr('id')});
              });
            </script>
	        <? endif; ?>

        </ul>

        <script type="text/javascript" src="<?=site_js("tinymce/tinymce.min.js");?>"></script>
        <script type="text/javascript">
          $(document).ready(function() {

            $('.js-review').click(function() {
              pregnancyWeekCommentsAction($(this));
            });

            $(document).on('submit','.js-nb',function() {
              $('.fl').prop('disabled', true);
              $('#js-submit-preloader').show();
            });

            $('.js-cancel').click(function () {
              removeTiny('#myText');
              $('.js-review-form').hide();
              $('.js-comment-form-stub').hide();
              $('.review-box .js-review').show();
              $('.js-review').parent().show();
              $('.js-review').show();
            });

          	$(function() {
          	  var validator = $("#reviewForm").submit(function() {
          	    // update underlying textarea before submit validation
          	    tinymce.triggerSave();
          	  }).validate({
          	    ignore: "",
          	    rules: {
          	      review: "required"
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
    </div>

    <div class="clear"></div>
  </div>
</div>