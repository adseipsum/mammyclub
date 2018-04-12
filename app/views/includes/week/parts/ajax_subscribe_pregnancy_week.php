<? if ($isLoggedIn == FALSE): ?>
  <div class="border-box distribution-box distribution-2 js-distribution-box"  style="margin-top: 35px;">
    <div class="cont">
      <div class="theme html-content">
        <p>
          Зарегистрируйтесь на нашем сайте и получите доступ к статье о Вашей неделе беременности прямо сейчас!
        </p>
      </div>
      <form method="post" class="validate" action="<?=site_url('процесс-регистрации');?>">
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
<? else: ?>
  <? if (empty($authEntity['pregnancyweek_id'])): ?>
    <div class="border-box distribution-box distribution-2 js-distribution-box" style="margin-top: 35px;">
      <div class="cont">
        <div class="theme html-content">
          <p>
            Подпишитесь и получите доступ к статье о вашей текущей неделе беременности прямо сейчас!
          </p>
        </div>
        <form method="post" class="validate js-subscribe-block-form" action="<?=site_url('беременность-по-неделям/подписаться-на-рассылку?popup=1');?>">
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