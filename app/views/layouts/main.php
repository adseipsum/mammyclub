<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en"
  <? if (url_contains('статья')): ?>
    itemscope itemtype="http://schema.org/Article"
  <? elseif(url_contains('консультация')): ?>
    itemscope itemtype="http://schema.org/Question"
  <? endif; ?>
>


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
      <meta name="description" content="<?=$header['description']?>"
      <? if (url_contains('статья') || url_contains('консультация')): ?>
        itemprop="description"
      <? endif; ?>
      />
    <? endif; ?>

    <? if (!empty($article['date']) && isset($article['date'])):?>
      <meta itemprop="datePublished" content="<?=$article['date']?>" />
    <? endif; ?>
    <? if (!empty($question['date']) && isset($question['date'])):?>
      <meta itemprop="datePublished" content="<?=convert_date($question['date'], "Y-m-d")?>" />
    <? endif; ?>


    <meta name="viewport" content="format-detection=no,initial-scale=1.0,maximum-scale=2.0,user-scalable=yes,width=device-width;" />
    <base href="<?=site_url();?>"/>


    <link rel="shortcut icon" href="<?=site_img('favicon.ico');?>" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=site_img('favicon_ipad.png');?>" />
    <link rel="apple-touch-icon-precomposed" href="<?=site_img('favicon_iphone.png');?>" />

    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>

    <? $css = "common/zero.css, common/messages.css, common/ajax-popup2.css, html-content.css, buttons.css, ezmark.css, style.css, jquery.fancybox-1.3.4.css, admin/base/jquery-ui-all.css"; ?>
    <link type="text/css" rel="stylesheet" media="screen" href="<?=site_css($css);?>"/>

    <!--[if lt IE 9]>
  		<link href="<?=site_css('style_ie.css');?>" media="screen" rel="stylesheet" type="text/css" />
  	<![endif]-->

    <? $this->view("includes/parts/scripts"); ?>
  </head>
  <body>
    <? $this->view("includes/parts/google_tag_manager"); ?>
    <div id="wrapper">
      <div id="main">
        <? $this->view("includes/parts/header"); ?>
        <? $this->view("includes/parts/menu"); ?>
        <div id="center" class="wrap">
          <div class="r-part">
            <? $this->view("includes/parts/right_part"); ?>
          </div>

          <div class="l-part">
            <div class="inner-b">
              <?=$content;?>
            </div>
            <div class="clear"></div>
          </div>

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
