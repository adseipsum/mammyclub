<div id="header" class="wrap">

  <? $this->view("includes/ad_slots/top_banner"); ?>

  <table class="h-table" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td class="td-1">
        <? if (is_shop()): ?>
          <a class="logo-shop" href="<?=shop_url();?>">Мамин Магазин - быть заботливой мамой легко!</a>
        <? else: ?>
          <a class="logo" href="<?=site_url();?>">Mammy club - делая материнство счастливым</a>
        <? endif; ?>



      </td>
      <td class="td-2">
<!--         <div class="search"> -->
<!--           <gcse:searchbox-only></gcse:searchbox-only> -->
<!--         </div> -->
<?/*
        <div class="search">
          <? if (is_shop()): ?>
          	<div class="ya-site-form ya-site-form_inited_no" onclick="return {'action':'https://shop.mammyclub.com/%D0%BF%D0%BE%D0%B8%D1%81%D0%BA','arrow':false,'bg':'transparent','fontsize':12,'fg':'#000000','language':'ru','logo':'rb','publicname':'Поиск по магазину MammyClub','suggest':true,'target':'_self','tld':'ru','type':2,'usebigdictionary':true,'searchid':2295022,'input_fg':'#000000','input_bg':'#ffffff','input_fontStyle':'normal','input_fontWeight':'normal','input_placeholder':'поиск по магазину','input_placeholderColor':'#cccccc','input_borderColor':'#7f9db9'}"><form action="https://yandex.ru/search/site/" method="get" target="_self" accept-charset="utf-8"><input type="hidden" name="searchid" value="2295022"/><input type="hidden" name="l10n" value="ru"/><input type="hidden" name="reqenc" value=""/><input type="search" name="text" value=""/><input type="submit" value="Найти"/></form></div><style type="text/css">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;if((' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1){e.className+=' ya-page_js_yes';}s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>
          <? else: ?>
            <div class="ya-site-form ya-site-form_inited_no" onclick="return {'action':'https://mammyclub.com/%D0%BF%D0%BE%D0%B8%D1%81%D0%BA','arrow':false,'bg':'transparent','fontsize':12,'fg':'#000000','language':'ru','logo':'rb','publicname':'Поиск по MammyClub','suggest':true,'target':'_self','tld':'ru','type':2,'usebigdictionary':true,'searchid':2295017,'input_fg':'#000000','input_bg':'#ffffff','input_fontStyle':'normal','input_fontWeight':'normal','input_placeholder':'поиск по сайту','input_placeholderColor':'#cccccc','input_borderColor':'#7f9db9'}"><form action="https://yandex.ru/search/site/" method="get" target="_self" accept-charset="utf-8"><input type="hidden" name="searchid" value="2295017"/><input type="hidden" name="l10n" value="ru"/><input type="hidden" name="reqenc" value=""/><input type="search" name="text" value=""/><input type="submit" value="Найти"/></form></div><style type="text/css">.ya-page_js_yes .ya-site-form_inited_no { display: none; }</style><script type="text/javascript">(function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;if((' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1){e.className+=' ya-page_js_yes';}s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');</script>
          <? endif; ?>
			  </div>
			  */?>

        <div class="search-box">
          <?
            $searchAction = site_url('поиск');
            $placeholder = 'Поиск по статьям...';
            if (is_shop()) {
              $searchAction = shop_url('поиск');
              $placeholder = 'Поиск по товарам...';
            }
          ?>
          <form method="get" action="<?=$searchAction;?>">
            <input class="search-field" type="text" name="q" placeholder="<?=$placeholder;?>" value="<?=isset($query)?$query:'';?>" />
            <input class="search-but" type="submit" />
          </form>
        </div>

      </td>
      <td class="td-3">
        <div class="h-buttons-row">
          <? if($isLoggedIn == FALSE): ?>
            <span data-ajaxp-url="<?=site_url('регистрация?type=in_top');?>" class="js-register h-but orange-but">Зарегистрироваться</span>
            <span data-ajaxp-url="<?=site_url('вход');?>" class="js-login h-but green-but">Войти</span>
          <? else: ?>
            <? if($authEntity['auth_info']['email_confirmed'] == FALSE): ?>
              <span class="attention a-like" data-ajaxp-url="<?=site_url('подтвердите-емейл');?>">Email не подтвержден!</span>
            <? endif; ?>
            <p class="hello">Здраствуйте, <span class="name"><?=$authEntity['name'];?></span><br />
            <a href="<?=site_url('личный-кабинет');?>">Личный кабинет</a> <span class="slash">/</span> <a href="<?=site_url('выход');?>">Выйти</a></p>
          <? endif; ?>
        </div>
        <div class="mobile-menu-row">
          <? if($isLoggedIn == TRUE): ?>
            <a class="mob-link a" href="<?=site_url('личный-кабинет');?>">Личный кабинет</a>
            <a class="mob-link b" href="<?=site_url('выход');?>">Выход</a>
          <? endif; ?>
          <a class="mob-link c js-menu-list">Список</a>
        </div>
      </td>
    </tr>
  </table>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.js-menu-list').toggle(function() {
      $(this).addClass('active');
      $('.js-mobile-menu-list').show();
    }, function() {
      $('.js-mobile-menu-list').hide();
      $(this).removeClass('active');
    });
  })
</script>