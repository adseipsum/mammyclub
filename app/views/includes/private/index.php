<div class="private-area">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <? $this->view("includes/private/parts/subscribe-box"); ?>

  <div class="prof-def-box one">
    <table class="t-head" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td><h2 class="name">Мои вопросы</h2></td>
        <td class="tar"><a href="<?=site_url('личный-кабинет/мои-вопросы');?>">все мои вопросы</a></td>
      </tr>
    </table>
    <div class="middle">
      <? if(!empty($questions)): ?>
        <ul class="qa-list">
          <? foreach ($questions as $q): ?>
            <li>
              <p><?=ago($q['date']);?></p>
              <a href="<?=site_url($q['page_url']);?>"><?=truncate(strip_tags($q['content']), 110, '...');?></a>
            </li>
          <? endforeach; ?>
        </ul>
      <? else: ?>
        <div class="html-content">
          <p>В разделе Консультации Вы можете задавать вопросы нашим экспертам и получить на них качественные и быстрые ответы.</p>
          <ul>
            <li>Консультируют только опытные врачи и родители.</li>
            <li>Простой и понятный язык изложения ответов.</li>
            <li>Это абсолютно бесплатно.</li>
          </ul>
        </div>
      <? endif; ?>
    </div>
    <div class="tac bottom"><a class="def-but green-but" href="<?=site_url('задать-вопрос');?>">Задать свой вопрос</a></div>
  </div>

  <div class="prof-def-box one last">
    <table class="t-head" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td><h2 class="name">Прочитанные статьи</h2></td>
        <td class="tar"><a href="<?=site_url('личный-кабинет/прочитанные-статьи');?>">все прочитанные статьи</a></td>
      </tr>
    </table>
    <div class="middle">
      <? if(!empty($readArticles)): ?>
        <ul class="qa-list">
          <? foreach ($readArticles as $ra): ?>
            <li>
              <p><?=ago($ra['created_at']);?></p>
              <a href="<?=site_url($ra['Article']['page_url']);?>"><?=truncate($ra['Article']['name'], 110, '');?></a>
            </li>
          <? endforeach; ?>
        </ul>
      <? else: ?>
        <div class="html-content">
          <p>В разделе статьи собрана уникальная база знаний о беременности и родах.</p>
          <ul>
            <li>Все статьи написаны нашими сотрудниками и рецензированы практикующими врачами.</li>
            <li>Каждая статья отвечает на конкретный вопрос и дает четкие рекомендации.</li>
            <li>Вы имеете возможность комментировать и задавать вопросы по теме статьи.</li>
          </ul>
        </div>
      <? endif; ?>
    </div>
    <div class="tac bottom"><a class="def-but green-but" href="<?=site_url('статьи');?>">Перейти в раздел “Статьи”</a></div>
  </div>

  <div class="prof-def-box two">
    <table class="t-head" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td><h2 class="name">&quot;Моя беременность по неделям&quot;</h2></td>
        <td class="tar"><a href="<?=site_url('личный-кабинет/беременность-по-неделям');?>">все статьи из цикла</a></td>
      </tr>
    </table>
    <div class="middle">
      <? if (isset($pregnancyArticles) && !empty($pregnancyArticles)): ?>
        <ul class="qa-list">
          <? foreach ($pregnancyArticles as $pa): ?>
            <li>
              <a href="<?=site_url($pa['page_url']);?>">Статья &quot;<?=truncate($pa['name'], 110, '');?>&quot;</a>
            </li>
          <? endforeach; ?>
        </ul>
      <? else: ?>
        <div class="html-content">
          <p>Это эксклюзивная e-mail рассылка, каждая статья которой – это абсолютно все о развитии малыша и изменениях в Вашем организме на этой неделе беременности.</p>
          <ul>
            <li>Каждая статья содержит рекомендации относительно посещения врачей, питания, физической активности.</li>
            <li>Обязательно присутствуют советы психолога, которые помогут легче перенести беременность.</li>
            <li>Вы узнаете, что переживает будущий папа и как с этим быть.</li>
          </ul>
          <p>Здесь будут собраны все статьи о неделях беременности, которые Вы уже прошли.</p>
        </div>
      <? endif; ?>
    </div>
    <? if (!isset($pregnancyArticles)): ?>
      <div class="tac bottom">
          <a class="def-but green-but" href="<?=site_url('беременность-по-неделям');?>">Узнать подробнее и подписаться</a>
      </div>
    <? endif; ?>
  </div>
  <? if (isset($_COOKIE['country']) && $_COOKIE['country'] == 'UA'): ?>
    <div class="prof-def-box two last">
      <table class="t-head" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td><h2 class="name">Просмотренные товары</h2></td>
          <td class="tar"><a href="<?=site_url('личный-кабинет/просмотренные-товары')?>">все просмотренные товары</a></td>
        </tr>
      </table>
      <div class="middle">
        <? if(!empty($products)): ?>
          <? $this->view("includes/private/parts/product_block", array('products', $products)); ?>
        <? else: ?>
          <div class="html-content">
            <p>Команда экспертов Маминого Магазина отобрала лучшие товары для Вас и Ваших малышей.</p>
            <ul>
              <li>Только нужные товары.</li>
              <li>Бесплатная доставка.</li>
              <li>Специальные цены для зарегистрированных пользователей.</li>
            </ul>
            <p>Здесь Вы найдете все товары, которые Вас заинтересовали в нашем магазине.</p>
          </div>
        <? endif; ?>
      </div>
      <div class="tac bottom"><a class="def-but green-but" href="<?=shop_url('')?>">Перейти в магазин</a></div>
    </div>
  <? endif; ?>

</div>