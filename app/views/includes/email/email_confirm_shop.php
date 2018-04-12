<div style="padding: 20px;">

  <h1 style="color: #4DA04D; font-size: 20px; font-weight: normal; margin: 0px; padding: 0px;">
    Здравствуйте, <?=$entity['name'];?>
  </h1>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Вы зарегистрированы на сайте MammyClub.com, т.к. сделали заказ в нашем Мамином Магазине.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Буквально несколько абзацев о том кто мы.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Наша миссия - помочь женщине быть счастливой мамой.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Что мы для этого делаем?
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Во-первых, мы создали рассылку Беременность по неделям.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    В этой рассылке масса полезной и интересной информации. А так же ссылки на более чем 100 тематических статей, каждая из которых отвечает на множество вопросов волнующих будущую маму.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Если вы подпишитесь на эту рассылку, новое письмо с информацией о вашей текущей неделе беременности будет приходить на ваш e-mail каждые 7 дней.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Почитать больше о рассылке Беременность по неделям и подписаться на нее можно <a href="<?=site_url('беременность-по-неделям?' . LOGIN_KEY . '=' . $entity['login_key']);?>">здесь</a>.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Во-вторых, для тех женщин, которые недавно стали мамами у нас есть рассылка Первый год жизни малыша.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Эта рассылка – рассказ о будущем, о том, что может ожидать вас в течении каждого последующего месяца жизни ребенка. Статьи построены таким образом, чтобы вы, сталкиваясь с теми или иными ситуациями, уже были к ним морально готовы.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Почитать больше о рассылке Первый год жизни малыша и подписаться на нее можно <a href="<?=site_url('статья/рассылка-первый-год-жизни-малыша?' . LOGIN_KEY . '=' . $entity['login_key']);?>">здесь</a>.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    В-третьих, мы открыли Мамин Магазин, в котором вы сможете купить самые качественные, полезные и красивые товары для мам и малышей. При этом мы каждый день работаем не покладая рук, чтобы новинки и цены вас приятно удивляли, а сервис вызывал самые лучшие эмоции. :)
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Девиз Маминого Магазина от MammyClub - Быть заботливой мамой легко!
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Если у нас будет что-то по-настоящему интересное, мы пришлем вам письмо со специальным предложением от нашего магазина. Не чаще, чем 1 раз в месяц.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    А еще, только для наших подписчиц открыт раздел Консультации, в котором вы можете задавать волнующие вас вопросы. И наши эксперты, совместно с практикующими врачами и психологами подготовят на них максимально развернутые и понятные ответы.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Мы знаем, что материнство - это самое прекрасное, что может произойти с женщиной.<br/>
    И мы по-настоящему рады, что вы отправляетесь в это непростое, но полное непередаваемых эмоций и замечательных событий, путешествие вместе с нами. :)
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Всегда ваша,<br/>
    команда MammyClub.com
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    P.S. Ваши личные данные для входа на сайт<br/>
    Логин: <?=$entity['auth_info']['email'];?><br/>
    Пароль: <?=$entity['auth_info']['password'];?>
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Для изменения личных данных и окончания процесса регистрации перейдите, пожалуйста, по ссылке:<br/>
    <a style="color: #1155CC; text-decoration: underline; font-size: 14px;" href="<?=$url?>"><?=$url?></a>
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    P.P.S.: О нашей команде можно почитать <a style="color: #1155CC; text-decoration: underline; font-size: 14px;" href="<?=site_url('о-проекте?' . LOGIN_KEY . '=' . $entity['login_key']);?>">здесь</a>.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Мы будем рады видеть вас на наших страничках в <a style="color: #1155CC; text-decoration: underline; font-size: 14px;" href="https://www.facebook.com/MammyClub-392626450914435">Facebook</a> и <a style="color: #1155CC; text-decoration: underline; font-size: 14px;" href="https://www.instagram.com/mammy_shop_by_mammyclub/">Instagram</a>.
  </p>

  <p style="color: #555; font-size: 14px; margin: 14px 0px;">
    Если хотите задать нам вопрос, оставить предложение или просто написать, жмите <a style="color: #1155CC; text-decoration: underline; font-size: 14px;" href="<?=site_url('связаться-с-нами?' . LOGIN_KEY . '=' . $entity['login_key']);?>">сюда</a>.
  </p>
</div>