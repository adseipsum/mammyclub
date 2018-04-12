<? $baseUrl = config_item("base_url"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xml:lang="en" >
  <head>
    <title>Page not found</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
    <link rel="shortcut icon" href="<?=$baseUrl?>web/images/favicon.ico" type="image/x-icon" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?=$baseUrl?>web/css/common/zero.css"/>
    <link type="text/css" rel="stylesheet" media="screen" href="<?=$baseUrl?>web/css/style.css"/>
    <script type="text/javascript" src="<?=$baseUrl?>web/jquery/jquery.min.js"?>"></script>
    <!--[if IE 6]>
      <link href="<?=$baseUrl?>css/style_ie.css" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=$baseUrl?>js/iepngfix_tilebg.js"></script>
    <![endif]-->
  </head>
  <body class="page404">
    <div id="wrapper">

      <div id="main">
        <div id="center">

          <table class="table-404 tac" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td style="padding-top: 30px;"><a href="<?=$baseUrl;?>"><img src="<?=$baseUrl?>web/images/logo_3.png" alt="Logo"/></a></td>
            </tr>
            <tr>
              <td><a class="img-404"></a></td>
            </tr>
          </table>
          <div class="box-404">
            <p style="margin-bottom: 5px;">Вы нажали на нерабочую ссылку или неправильно набрали адрес. <br/>Вы можете:</p>
            <ul>
              <li>Вернуться на <a href="<?=$baseUrl?>">главную</a></li>
              <li>Вернуться на <a href="JavaScript:history.go(-1)">предыдущую страницу</a></li>
            </ul>
          </div>
          <div class="clear"></div>
        </div>

        <div class="clear push-box"></div>
      </div>


    </div><!-- #wrapper -->
  </body>
</html>