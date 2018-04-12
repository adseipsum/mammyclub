<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en" >
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
    <? if(isset($head_section_code)): ?>
      <?=$head_section_code;?>
    <? endif; ?>
    <?
      if (!isset($header) || !isset($header['title'])) {
        $header['title'] = '';
      }
    ?>
    <title><?=$header['title']?></title>
    <? if(isset($header['description'])): ?>
      <meta name="description" content="<?=$header['description']?>" />
    <? endif; ?>

    <? if(isset($_GET['show_all']) || (isset($pager) && $pager->haveToPaginate() && url_contains('/(' . get_prefix() . '\d+)'))): ?>
      <link href="<?=preg_replace('/\/(' . get_prefix() . '\d+)/', '', shop_url(uri_string()));?>" rel="canonical">
    <? endif;?>

    <meta name="viewport" content="format-detection=no,initial-scale=1.0,maximum-scale=1.0,user-scalable=no,width=device-width;" />
    <base href="<?=site_url();?>"/>

    <link rel="shortcut icon" href="<?=site_img('favicon.ico');?>" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=site_img('favicon_ipad.png');?>" />
    <link rel="apple-touch-icon-precomposed" href="<?=site_img('favicon_iphone.png');?>" />

    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>

    <? $css = "common/zero.css, common/messages.css, common/ajax-popup2.css, html-content.css, buttons.css, ezmark.css, flexnav.css, style.css, jquery.fancybox-1.3.4.css, category-menu.css, superfish.css, jquery.navgoco.css"; ?>

    <link type="text/css" rel="stylesheet" media="screen" href="<?=site_css($css);?>"/>

    <!--[if lt IE 9]>
  		<link href="<?=site_css('style_ie.css');?>" media="screen" rel="stylesheet" type="text/css" />
  	<![endif]-->

  	<meta name='yandex-verification' content='7ab04f9d8f42096d' />

    <? $this->view("includes/parts/scripts"); ?>
    <script type="text/javascript">
      (function(_,r,e,t,a,i,l){_['retailCRMObject']=a;_[a]=_[a]||function(){(_[a].q=_[a].q||[]).push(arguments)};_[a].l=1*new Date();l=r.getElementsByTagName(e)[0];i=r.createElement(e);i.async=!0;i.src=t;l.parentNode.insertBefore(i,l)})(window,document,'script','https://collector.retailcrm.pro/w.js','_rc');

      _rc('create', 'RC-75591707867-2', {
        <? if (!empty($authEntity)): ?>
          'customerId': '<?=$authEntity['id'];?>'
        <? endif; ?>
      });

      _rc('send', 'pageView');
    </script>
  </head>
  <body>
    <? $this->view("includes/parts/google_tag_manager"); ?>
    <div id="wrapper">
      <div id="main">
        <? $this->view("includes/parts/header"); ?>

        <? if (SUBDOMAIN === 'shop'): ?>
          <? $this->view("includes/parts/shop_sub_menu"); ?>
        <? else: ?>
          <? $this->view("includes/parts/menu"); ?>
        <? endif; ?>

        <div id="center" class="wrap">


          <?/*
          <? if(!empty($cartItems)): ?>
            <a class="mobile-basket" href="<?=shop_url('оформить-заказ')?>"><span class="numb"><?=count($cartItems)?></span></a>
          <? endif; ?>
          */?>
          <?// if ($_COOKIE['country'] == 'UA' || $_COOKIE['country'] == 'TH'): ?>

          <?=$content;?>

          <?/*
          <div class="r-part">
            <? $this->view("includes/shop/parts/right_part"); ?>
          </div>
          <div class="l-part">
            <div class="inner-b">
              <?=$content;?>
            </div>
            <div class="clear"></div>
          </div>
          */?>
          <?/* else: ?>
            <div class="intro-3">
              <?=$settings['shop_not_ukraine'];?>
            </div>
          <?  endif;*/ ?>
          <div class="clear"></div>
        </div><!-- #center -->
        <div class="clear push-box"></div>
      </div><!-- #main -->
      <? $this->view("includes/parts/footer"); ?>
      <? $this->view("includes/parts/forms"); ?>
    </div><!-- #wrapper -->

    <? $this->view("includes/parts/ajax-popup"); ?>
    <script type="text/javascript" src="<?=site_js("retina.js")?>"></script>
  </body>
</html>
