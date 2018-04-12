<div class="private-area private-def-box">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <? $this->view("includes/private/parts/subscribe-box"); ?>

  <h1 class="title">Редактирование информации</h1>

  <div class="prof-def-box min-box one">
    <table class="t-head" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td><h2 class="name">Моя информация</h2></td>
      </tr>
    </table>
    <div class="middle middle-2">
      <form method="post" action="<?=site_url('процесс-смены-информации');?>" class="validate-3">
        <div class="input-row">
          <label for="name">Ваше имя, (ник)</label>
          <input class="text required" type="text" value="<?=$authEntity['name'];?>" name="name" id="name" />
          <p class="example">
            Это имя (ник) будет отображаться в комментариях и вопросах, которые Вы будете публиковать на сайте.
            А так же мы будем обращаться к Вам по этому имени в служебных письмах и рассылках.
          </p>
        </div>
        <div class="input-row">
          <label for="name">Ваш e-mail</label>
          <input class="text required" type="email" value="<?=$authEntity['auth_info']['email'];?>" name="email" id="email" />
          <p class="example">Впишите сюда Ваш новый e-mail если Вы хотите его поменять.</p>
        </div>
        <? if($authEntity['newsletter_first_year'] == FALSE || $authEntity['newsletter'] == TRUE): ?>
          <div class="input-row">
            <label for="pregnancy_week">Ваша текущая неделя беременности:</label>
            <select name="pregnancy_week">
              <option value="">- Выберите неделю -</option>
              <? foreach($pregnancyWeeks as $w): ?>
                <option value="<?=$w['id'];?>"<?=$authEntity['pregnancyweek_current_id']==$w['id']?' selected="selected"':'';?>><?=$w['number'];?> неделя</option>
              <? endforeach; ?>
            </select>
            <p class="example">Зная Вашу неделю беременности, мы сможем присылать Вам каждую неделю статью из<br />рассылки "Беременность по неделям". Данная рассылка написана экспертами, полностью<br />уникальна и доступна только для наших читателей.</p>
          </div>
        <? endif; ?>
        <? if($authEntity['newsletter_first_year'] == TRUE): ?>
          <div class="input-row">
            <table cellspacing="0" cellpadding="0" border="0" class="table-fyl">
              <tr>
                <td>
                  <label for="child_birth_date">Дата рождения ребенка:</label>
                  <input class="js-date" type="text" name="child_birth_date" id="child_birth_date" value="<?=!empty($authEntity['child_birth_date'])?$authEntity['child_birth_date']:'';?>" />
                </td>
                <td class="lrp">
                  <label for="child_sex">Пол ребенка:</label>
                  <select name="child_sex" id="child_sex">
                    <option value="">- Выберите пол -</option>
                    <option value="m"<?=$authEntity['child_sex']=='m'?' selected="selected"':'';?>><?=lang('enum.user.child_sex.m');?></option>
                    <option value="f"<?=$authEntity['child_sex']=='f'?' selected="selected"':'';?>><?=lang('enum.user.child_sex.f');?></option>
                  </select>
                </td>
                <td>
                  <label for="child_name">Имя ребенка:</label>
                  <input type="text" name="child_name" id="child_name" value="<?=!empty($authEntity['child_name'])?$authEntity['child_name']:'';?>" />
                </td>
              </tr>
            </table>
          </div>
        <? endif; ?>
        <div class="tac">
          <button type="submit" class="def-but green-but">Изменить информацию</button>
        </div>
      </form>
    </div>
  </div>

  <div class="prof-def-box min-box one last">
    <table class="t-head" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td><h2 class="name">Смена пароля</h2></td>
      </tr>
    </table>
    <div class="middle middle-2">
      <form method="post" action="<?=site_url('процесс-смены-пароля');?>" id="change_password" class="validate-2">
        <div class="input-row">
          <label for="old_password">Старый пароль</label>
          <input class="required text" type="password" value="" name="old_password" id="old_password" />
        </div>
        <div class="input-row" style="margin: 5px 0 20px 0;">
          <label for="new_password">Новый пароль</label>
          <input class="text required" type="password" value="" name="new_password" id="new_password" />
          <p class="example">Запомните и не говорите его никому :)</p>
        </div>
        <div class="input-row">
          <label for="confirm_password">Новый пароль ещё раз</label>
          <input class="required text" type="password" value="" name="confirm_password" id="confirm_password" />
        </div>
        <div class="tac">
          <button type="submit" class="def-but green-but">Изменить пароль</button>
        </div>
      </form>
    </div>
  </div>

  <div class="clear"></div>

  <div class="prof-def-info">
    <script type="text/javascript" src="<?=site_js("admin/checkboxes.js");?>"></script>
    <? if (!empty($settings['privat_edit_info_email_settings_text'])): ?>
      <div class="html-content">
        <?=$settings['privat_edit_info_email_settings_text'];?>
      </div>
    <? endif; ?>
    <form method="post" action="<?=site_url('процесс-смены-рассылок');?>" class="validate-1" id="change_broadcast">
      <table class="check-table" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
            <div class="input-row">
              <input id="e-1" class="checkbox js-checkbox cp" type="checkbox" name="newsletter" value="1"<?=$authEntity['newsletter'] ? ' checked="checked"' : '';?> />
              <label for="e-1" class="cp">Получать рассылку "Беременность по неделям"</label>
              <select name="pregnancy_week" class="required" style="display: none; margin: 0 0 0 5px;" disabled="disabled">
                <option value="">- Выберите неделю -</option>
                <? foreach($pregnancyWeeks as $w): ?>
                  <option value="<?=$w['id'];?>"<?=$authEntity['pregnancyweek_current_id']==$w['id']?' selected="selected"':'';?>><?=$w['number'];?> неделя</option>
                <? endforeach; ?>
              </select>
            </div>
          </td>
          <td>
            <input id="e-2" class="checkbox js-checkbox cp" type="checkbox" name="newsletter_questions" value="1"<?=$authEntity['newsletter_questions'] ? ' checked="checked"' : '';?> />
            <label for="e-2" class="cp">Получать email-уведомления об ответах на мои вопросы</label>
          </td>
        </tr>
        <tr>
          <td>
            <input id="e-3" class="checkbox js-checkbox cp" type="checkbox" name="newsletter_shop" value="1"<?=$authEntity['newsletter_shop'] ? ' checked="checked"' : '';?> />
            <label for="e-3" class="cp">Получать рассылку "Акции на товары в магазине"</label>
          </td>
          <td>
            <input id="e-4" class="checkbox js-checkbox cp" type="checkbox" name="newsletter_comments" value="1"<?=$authEntity['newsletter_comments'] ? ' checked="checked"' : '';?> />
            <label for="e-4" class="cp">Получать email-уведомления об ответах на мои комментарии</label>
          </td>
        </tr>
        <tr>
          <td>
            <input id="e-5" class="checkbox js-checkbox cp" type="checkbox" name="newsletter_recommended_products" value="1"<?=$authEntity['newsletter_recommended_products'] ? ' checked="checked"' : '';?> />
            <label for="e-5" class="cp">Получать рассылку "Полезные покупки для беременных"</label>
          </td>
          <? if(!empty($authEntity['age_of_child'])): ?>
            <td>
              <input id="e-6" class="checkbox js-checkbox cp" type="checkbox" name="newsletter_first_year" value="1"<?=$authEntity['newsletter_first_year'] ? ' checked="checked"' : '';?> />
              <label for="e-6" class="cp">Получать рассылку "Первый год жизни малыша"</label>
            </td>
          <? endif; ?>
        </tr>
        <tr>
          <td>
            <input id="e-7" class="checkbox js-checkbox cp" type="checkbox" name="newsletter_useful_tips" value="1"<?=$authEntity['newsletter_useful_tips'] ? ' checked="checked"' : '';?> />
            <label for="e-7" class="cp">Получать рассылку "Полезные советы от MammyClub"</label>
          </td>
          <td>
          </td>
        </tr>
      </table>
      <div class="tac">
        <button type="submit" class="h-but orange-but">Изменить настройки получения рассылок</button>
      </div>
    </form>
  </div>

  <? if(in_array($_SERVER['REMOTE_ADDR'], array('93.72.171.149', '127.0.0.1'))): ?>
    <div class="kick-it"></div>
    <div class="prof-def-info">
      <form method="post" action="<?=site_url('процесс-смены-доставки-рассылок');?>" class="validate-1" id="change_broadcast">
        <table class="check-table" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <? foreach (ManagerHolder::get('XBroadcast')->getPossibleBroadcastChannels() as $i => $channel): ?>
              <?
                $checked = false;
                if ($authEntity['broadcast_channels']) {
                  $channels = json_decode($authEntity['broadcast_channels'], true);
                  if (isset($channels[$channel]) && !empty($channels[$channel])) {
                    $checked = true;
                  }
                }
              ?>
              <td>
                <div class="input-row">
                  <input id="d-<?=$i;?>"
                         class="checkbox js-checkbox cp"
                         type="checkbox"
                         name="broadcast_channels[<?=$channel;?>]"
                         value="1"<?=$checked ? ' checked="checked"' : '';?> />
                  <label for="d-<?=$i;?>" class="cp"><?=lang('edit_profile.change_broadcast_channel.checkbox_name.' . $channel);?></label>
                  <? if ($channel == XBroadcastManager::BROADCAST_CHANNEL_FACEBOOK): ?>
                    <div class="fb-messenger-checkbox"
                         origin="mammyclub.com"
                         page_id="392626450914435"
                         messenger_app_id="165159744058123"
                         user_ref="<?=ENV . '_' . $authEntity['id'];?>"
                         prechecked="true"
                         allow_login="true"
                         size="small"
                         skin="light"
                         center_align="false">
                    </div>
                  <? endif; ?>
                </div>
              </td>
            <? endforeach; ?>
          </tr>
        </table>
        <div class="tac">
          <button type="submit" class="h-but orange-but">Изменить настройки доставки рассылок</button>
        </div>
      </form>
    </div>
  <? endif; ?>

  <div class="kick-it"></div>
  <div class="prof-def-info">
    <form method="post" action="<?=site_url('процесс-смены-аватарки');?>" class="validate-1" id="js-change-avatar-form">
      <? if (!empty($settings['privat_edit_info_avatar_text'])): ?>
        <div class="html-content">
          <?=$settings['privat_edit_info_avatar_text'];?>
        </div>
      <? endif; ?>
      <ul class="avatar-list">
        <? foreach ($avatars as $avatar): ?>
          <li>
            <img class="js-avatars<?=$currentAvatar == $avatar['image_id'] ? ' picked' : ''; ?>" id="<?=$avatar['image_id']?>" src="<?=site_image_thumb_url('_medium', $avatar['image']);?>"/>
          </li>
        <? endforeach; ?>
      </ul>
      <div class="clear"></div>
      <div class="tac">
        <button id="js-change-avatar" class="h-but orange-but">Изменить аватарку</button>
      </div>
    </form>
  </div>

</div>


<script type="text/javascript">
  $(document).ready(function() {
    $('.validate-1').validate({
	    ignore: "",
	    errorPlacement: function(label, element) {
  	    if (element.is('.validate-1 select[name="pregnancy_week"]')) {
    	    $('.validate-1 select[name="pregnancy_week"]').before(label);
  	    }
	    }
	  });
    $('.validate-2').validate();
    $('.validate-3').validate();
    $('.js-checkbox').ezMark();

    $('input[name="newsletter"]').click(function() {
      var value = $('input[type="hidden"][name="newsletter"]').val();
      if(typeof (value) !== "undefined") {
        var weekVal = $('select[name="pregnancy_week"]').val();
        if(weekVal == '') {
          $('#change_broadcast select[name="pregnancy_week"]').show().removeAttr('disabled');
        }
      } else {
        $('#change_broadcast select[name="pregnancy_week"]').hide().attr('disabled', 'disabled');
      }
    });

    $('.js-avatars').click(function() {
      var clikedObj = $(this);
      $('.picked').removeClass("picked");
      clikedObj.addClass("picked");
    });

    $('#js-change-avatar').click(function() {
      var imgId = $('.picked').attr('id');
      var url = '<?=site_url('процесс-смены-аватарки');?>' + '/' + imgId;
      $('#js-change-avatar-form').attr('action', url);
    });
  });
</script>