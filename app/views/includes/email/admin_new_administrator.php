<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta content="text/html; charset=windows-1251" http-equiv="Content-Type">
    <title>Добавление в администраторы</title>
    <style type="text/css">
      .common {color: #666; font-family: Arial, Verdana, Tahoma; font-size: 12px; font-weight: normal;}
      .common a {border-width: 0px;}
      .content {font-size: 14px; line-height: 18px;}
      .content a {color: #4593B8; text-decoration: underline;}
      .content p {color: #666; margin: 14px 0px;}
      .content strong {color: #000;}
      .content a:hover {text-decoration: none;}
      .footer-table a:hover {text-decoration: underline !important;}
      
      /*Html-content styles*/
      .html-content {line-height: 20px; overflow: hidden; text-align: left; color: #444; font-size: 13px;}
      .html-content h1 {font-family: Arial; font-size: 24px; color: #666; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 24px; text-align: left;}
      .html-content h2 {font-family: Arial; font-size: 20px; color: #e38725; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 20px; text-align: left; font-style: italic;}
      .html-content h3 {font-family: Arial; font-size: 18px; color: #666; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 18px; font-style: italic; text-align: left;}
      .html-content h4 {font-family: Arial; font-size: 17px; color: #666; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 17px; text-align: left;}
      .html-content h4 strong {font-weight: normal;}
      .html-content h5 {font-family: Arial; font-size: 16px; color: #000; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 16px; text-align: left;}
      .html-content h6 {font-family: Arial; font-size: 14px; color: #000; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 14px; text-align: left;}
      
      .html-content ul {margin: 0px; padding: 0px; list-style: none; line-height: 14px; overflow: hidden;}
      .html-content ul li {background: url("/web/images/orange_li.gif") no-repeat 3px 6px; padding-left: 14px; margin-bottom: 8px; line-height: 16px;}
      .html-content ol {margin: 0px; padding: 0px; list-style: decimal; padding-left: 22px;}
      .html-content ol li {margin-bottom: 8px;}
      .html-content table {width: 100%; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;}
      .html-content table td {padding: 4px; border-left: 1px solid #ccc; border-top: 1px solid #ccc;}
      .html-content table td p {margin: 0px;}
      
      .html-content p {line-height: 20px;}
      .html-content blockquote {padding: 8px; border: 1px solid #e1cc89; margin: 5px; background: #FAEBBC url(/web/images/openquote1.gif) no-repeat top left; text-indent: 23px;}
      .html-content blockquote p, .html-content blockquote .text-box {display: block; background: url(/web/images/closequote1.gif) no-repeat bottom right;}
      /*************/
      
    </style>
  </head>
  <body style="margin: 0px; padding: 0px; background: #fff;" class="common">
    
     <table cellpadding="0" cellspacing="0" border="0" style="background: #fff;" background="fff">
      <tr>
        <td>
          
          <table cellpadding="0" cellspacing="0" style="border: 1px solid #fff; background: #fff;" background="#fff">
            <tr>
              <td>
          
                <table cellpadding="0" width="100%" height="28" cellspacing="0" border="0" style="border-bottom: 1px solid #fff;">
                  <tr>
                    <td style="padding: 15px 20px 0px;" height="28" align="left" width="50%"><h1 style="font-size: 22px; color: #000; margin: 0px;"><?=$subject?></h1></td>
                  </tr>
                </table>
                
                <table class="content html-content" cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 0px auto;">
                  <tr>
                    <td style="padding: 5px 20px;">
                      <p>Вас добавили в администраторы сайта <?=site_url();?></p>
                      <p>Ваш логин: <b><?=$email;?></b></p>
                      <p>Ваш пароль: <b><?=$password;?></b></p>
                      <p>Ссылка для входа в админ-панель: <a href="<?=$login_url;?>"><?=$login_url;?></a></p>
                    </td>
                  </tr>
                </table>
                
              </td>
            </tr>
          </table>
        
        
        </td>
      </tr>
    </table>
      
                
  </body>
</html>