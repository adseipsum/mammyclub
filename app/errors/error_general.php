<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<? $baseUrl = config_item("base_url"); ?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en" >
  <head>
    <title>Error</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>      
    <link rel="shortcut icon" href="<?=$baseUrl?>web/images/favicon.ico" type="image/x-icon" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?=$baseUrl?>web/css/common/zero.css"/>
    <link type="text/css" rel="stylesheet" media="screen" href="<?=$baseUrl?>web/css/style.css"/>  
    <!--[if IE 6]>
      <link href="<?=$baseUrl?>css/style_ie.css" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=$baseUrl?>js/iepngfix_tilebg.js"></script>
    <![endif]-->
  </head>
  
  <body class="page404">
    <div id="wrapper">
      
      <div id="main">
        <?/*
        <div id="header">
          <a href="<?=$baseUrl;?>"><img src="<?=$baseUrl?>web/images/logo.png" alt="Logo"/></a>
        </div><!-- #header -->
        */?>
        <div id="center">
          
          <table class="table-404 tac" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td style="padding-bottom: 40px;"><a href="<?=$baseUrl;?>"><img src="<?=$baseUrl?>web/images/logo.png" alt="Logo"/></a></td>
            </tr>
            <tr>
              <td><img src="<?=$baseUrl?>web/images/page_error.gif" alt="Error"/></td>
            </tr>
          </table>
          
          <h1 style="color: #ff931e;"><?php echo $heading; ?></h1>
          <?php echo $message; ?>
          <div>
            <pre>
              <?=debug_print_backtrace(); ?>
            </pre>
          </div>
          <div class="clear"></div>
        </div><!-- #center -->
        
        <div class="clear push-box"></div>
      </div><!-- #main -->
      
      
    </div><!-- #wrapper -->
  </body>

</html>
