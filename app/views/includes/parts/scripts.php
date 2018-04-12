<script type="text/javascript" src="<?=site_js("jquery/jquery-1.8.1.min.js,
                                                jquery/query.js,
                                                jquery/cookie.js,
                                                jquery/jquery.ezmark.js,
                                                common/ajax-popup2.js,
                                                common/print.js,
                                                jquery/hoverIntent.js,
                                                jquery/superfish.js,
                                                ajax.contentloader.js,
                                                admin/jquery/ui/core.min.js,
                                                admin/jquery/localization/ui.datepicker-ru.js,
                                                admin/jquery/ui/datepicker.js,
                                                project.js,
                                                ifvisible.js");?>"></script>


<script type="text/javascript" src="<?=site_js("jquery/plugins/jquery.autocomplete.min.js")?>"></script>

<script type="text/javascript" src="<?=site_js("jquery.navgoco.js,
                                                jquery/plugins/fancybox.pack.js,
                                                jquery/jquery.validate.min.js,
                                                jquery/jquery.validate.ru.js,
                                                jquery/jquery.flexnav.js,
                                                jquery/jquery.scrollTo-min.js,
                                                jquery/jquery.jcarousel.min.js,
                                                jquery/jcarousel.connected-carousels.js");?>"></script>


<script async type="text/javascript" src="<?=site_js("share42.js");?>"></script>

<script type="text/javascript">
  var base_url = '<?=site_url('/');?>';
  var shop_base_url = '<?=shop_url('/');?>';
  var current_url = '<?=str_replace(site_url(), '', current_url());?>';
  var lastClickedElemet = null;  // For comments only
  var isLoggedIn = 0;
  <? if ($isLoggedIn == TRUE): ?>
    isLoggedIn = 1;
  <? endif; ?>
  <? if(isset($pageVisitId)): ?>
    var pageVisitId = <?=$pageVisitId;?>
  <? endif; ?>

  var tiny_mce_css = '<?=site_css('html-content.css');?>';

  // Product parameters data
  var paramGroupData = {};
  <? if(is_not_empty($product['parameter_groups'])): ?>
    <? foreach ($product['parameter_groups'] as $g): ?>
      <?
        if(empty($g['price'])) {
          $g['price'] = $product['price'];
        }
        $g['values_out'] = '';
        if(!empty($g['secondary_parameter_values_out'])) {
          $g['values_out'] = implode(',', get_array_vals_by_second_key($g['secondary_parameter_values_out'], 'id'));
        }
      ?>
      paramGroupData['<?=$g['main_parameter_value_id'];?>'] = {};
      paramGroupData['<?=$g['main_parameter_value_id'];?>']['price'] = '<?=$g['price'];?>';
      paramGroupData['<?=$g['main_parameter_value_id'];?>']['values_out'] = '<?=$g['values_out'];?>';
      paramGroupData['<?=$g['main_parameter_value_id'];?>']['in_stock_status'] = '<?=$g['in_stock_status'];?>';
      <? if (isset($g['old_price'])) : ?>
        paramGroupData['<?=$g['main_parameter_value_id'];?>']['old_price'] = '<?=$g['old_price'];?>';
      <? else : ?>
        paramGroupData['<?=$g['main_parameter_value_id'];?>']['old_price'] = 0;
      <? endif; ?>
    <? endforeach;?>
  <? endif; ?>
</script>

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : 165159744058123,
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.12'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>


<script type="text/javascript" src="<?=site_js("page_visit_events.js");?>"></script>

<? if (ENV == 'PROD'): ?>

  <script type="text/javascript" src="<?=site_js("sourcebuster.min.js");?>"></script>
  <script type="text/javascript">
    sbjs.init({
      domain: 'mammyclub.com',
      timezone_offset: 2,
      campaign_param: 'campaign'
    });
  </script>

  <? if(!empty($authEntity) && (url_equals('успешная-подписка') || url_equals('подтверждение-емейла-и-статья') || url_equals('спасибо-за-заказ'))): ?>
    <script type="text/javascript">
      $(document).ready(function() {
        var type = "user";
        var lk = "<?=$authEntity['login_key'];?>";
        <? if(url_equals('спасибо-за-заказ')): ?>
          type = "siteorder";
          lk = "<?=$siteOrder['code'];?>";
        <? endif; ?>
        $.get( base_url + "ajax/save_inv_channel", { type: type,
                                                     inv_channel: sbjs.get.current.typ,
                                                     inv_channel_src: sbjs.get.current.src,
                                                     inv_channel_mdm: sbjs.get.current.mdm,
                                                     inv_channel_cmp: sbjs.get.current.cmp,
                                                     inv_channel_cnt: sbjs.get.current.cnt,
                                                     inv_channel_trm: sbjs.get.current.trm,
                                                     lk: lk } );
      });
    </script>
  <? endif; ?>

  <!-- Google Tag Manager get client Id and send via Ajax -->
	<? if (!empty($authEntity)):?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      var userId = '<?=$authEntity['id'];?>';
      var dataLayer = window.dataLayer || [];
      dataLayer.push({
        'userId' : userId
      });

      $(document).ready(function(){
        <? if ($existUserClientId) : ?>
          ga('create', 'UA-46834478-3');
          ga('send', 'pageview');
          ga(function(tracker) {
            var clientId = tracker.get('clientId');
            $.post( base_url + "ajax/save_ajax_client_id_ga", { clientId: clientId, entityId: userId});
          });
        <? endif;?>
      });
    </script>
	<? endif;?>

  <?/*
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-46834478-1', 'mammyclub.com');
    ga('send', 'pageview');
  </script>
  */?>

  <?/*
  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
  (function (d, w, c) {
      (w[c] = w[c] || []).push(function() {
          try {
              w.yaCounter23541256 = new Ya.Metrika({id:23541256,
                      webvisor:true,
                      clickmap:true,
                      trackLinks:true,
                      accurateTrackBounce:true});
          } catch(e) { }
      });

      var n = d.getElementsByTagName("script")[0],
          s = d.createElement("script"),
          f = function () { n.parentNode.insertBefore(s, n); };
      s.type = "text/javascript";
      s.async = true;
      s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

      if (w.opera == "[object Opera]") {
          d.addEventListener("DOMContentLoaded", f, false);
      } else { f(); }
  })(document, window, "yandex_metrika_callbacks");
  </script>
  <noscript><div><img src="//mc.yandex.ru/watch/23541256" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->
  */?>

  <? if(SUBDOMAIN == 'shop' && url_equals('analytics-employee-secret-page')): ?>
    <script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-46834478-2', 'auto');
    ga('send', 'pageview',{'dimension1':'employee'});
    </script>
  <? endif; ?>

<? endif; ?>

<? if (url_contains('консультация') || url_contains('статья')): ?>
  <?/*
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
 */?>

  <?/*
  <script type="text/javascript" src="https://vk.com/js/api/share.js?90" charset="windows-1251"></script>
  */?>
<? endif; ?>

<script>
(function() {
var cx = '009525143490456724095:srffwad5qrm';
var gcse = document.createElement('script');
gcse.type = 'text/javascript';
gcse.async = true;
gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
'//www.google.com/cse/cse.js?cx=' + cx;
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(gcse, s);
})();
</script>

<!--<!-- Facebook Pixel Code -->
<!--<script>-->
<!--!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?-->
<!--n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;-->
<!--n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;-->
<!--t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,-->
<!--document,'script','//connect.facebook.net/en_US/fbevents.js');-->
<!---->
<!--fbq('init', '157613461252240');-->
<!--fbq('track', "PageView");</script>-->
<!--<noscript><img height="1" width="1" style="display:none"-->
<!--src="https://www.facebook.com/tr?id=157613461252240&ev=PageView&noscript=1"-->
<!--/></noscript>-->
<!--<!-- End Facebook Pixel Code -->

<? $this->view("includes/ad_slots/head_section"); ?>

<? $this->view("includes/parts/ecommerce_tracking"); ?>
