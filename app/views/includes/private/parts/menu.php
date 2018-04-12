<div class="private-menu-box">
  <ul class="private-menu">
    <li<?=url_equals('личный-кабинет')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет')?>">Личный кабинет</a></li>
    <li<?=url_equals('личный-кабинет/мои-вопросы')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет/мои-вопросы');?>">Мои вопросы</a></li>
    <li<?=url_equals('личный-кабинет/прочитанные-статьи')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет/прочитанные-статьи');?>">Прочитанные статьи</a></li>
    <li<?=url_equals('личный-кабинет/беременность-по-неделям')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет/беременность-по-неделям')?>">Беременность по неделям</a></li>
    <? if(!empty($authEntity['age_of_child'])): ?>
      <li<?=url_equals('личный-кабинет/мой-малыш')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет/мой-малыш')?>">Мой малыш</a></li>
    <? endif; ?>
    <? if (isset($_COOKIE['country']) && ($_COOKIE['country'] == 'UA' || $_COOKIE['country'] == 'TH')): ?>
      <li<?=url_equals('личный-кабинет/просмотренные-товары')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет/просмотренные-товары');?>">Просмотренные товары</a></li>
    <? endif; ?>
    <li<?=url_equals('личный-кабинет/редактирование-информации')?' class="active"':'';?>><a href="<?=site_url('личный-кабинет/редактирование-информации')?>">Редактирование информации</a></li>
  </ul>
  <div class="clear"></div>
</div>