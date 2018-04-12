<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta content="text/html; charset=windows-1251" http-equiv="Content-Type">
    <title>Mammyclub</title>
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
      .html-content h3 {font-family: Arial; font-size: 18px; color: #e38725; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 18px; font-style: italic; text-align: left;}
      .html-content h4 {font-family: Arial; font-size: 17px; color: #666; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 17px; text-align: left;}
      .html-content h4 strong {font-weight: normal;}
      .html-content h5 {font-family: Arial; font-size: 16px; color: #000; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 16px; text-align: left;}
      .html-content h6 {font-family: Arial; font-size: 14px; color: #000; margin: 14px 0px; padding: 0px; position: relative; font-weight: normal; line-height: 14px; text-align: left;}
      .html-content ul {margin: 0px; padding: 0px; list-style: none; line-height: 14px; overflow: hidden;}
      .html-content ul li {background: url("https://mammyclub.com/web/images/li_back.png") no-repeat 3px 6px; padding-left: 14px; margin-bottom: 8px; line-height: 20px; color: #666;}
      .html-content ol {margin: 0px; padding: 0px; list-style: decimal; padding-left: 22px;}
      .html-content ol li {margin-bottom: 8px; color: #666;}
      .html-content table {width: 100%; border-right: 1px solid #ccc; border-bottom: 1px solid #ccc;}
      .html-content table td {padding: 4px; border-left: 1px solid #ccc; border-top: 1px solid #ccc;}
      .html-content table td p {margin: 0px;}
      .html-content p {line-height: 20px;}
      .html-content blockquote {padding: 8px; border: 1px solid #e1cc89; margin: 5px; background: #FAEBBC url(/web/images/openquote1.gif) no-repeat top left; text-indent: 23px;}
      .html-content blockquote p, .html-content blockquote .text-box {display: block; background: url(/web/images/closequote1.gif) no-repeat bottom right;}
      /*************/
    </style>
  </head>
  <body style="margin: 0px; padding: 0px; background: #F5F5F5;" class="common">
     <table cellpadding="0" cellspacing="0" border="0" style="background: #F5F5F5;" background="#F5F5F5">
      <tr>
        <td>
          <table cellpadding="0" cellspacing="0" style="border: 3px solid #5dba5d; background: #fff; margin: 20px 40px; width: 680px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;" background="#fff">
            <tr>
              <td>
                <table cellpadding="0" width="100%" height="<?=isset($is_shop) ? '111' : '96'?>" cellspacing="0" border="0" style="border-bottom: 3px solid #5dba5d;">
                  <tr>
                    <td style="padding: 20px;" height="<?=isset($is_shop) ? '111' : '96'?>" align="center">
                      <? if(isset($is_shop)): ?>
                        <a style="border: 0px;" href="<?=site_url()?>" target="_blank" style="display: block; margin: 5px 0px 0px 0px; width: 245px; height: 75px;"><img border="0" style="border: 0px;" src="<?=site_url('web/images/logo_shop.png')?>"/></a>
                      <? else: ?>
                        <a style="border: 0px;" href="<?=site_url()?>" target="_blank" style="display: block; margin: 5px 0px 0px 0px; width: 253px; height: 60px;"><img border="0" style="border: 0px;" src="<?=site_url('web/images/logo_3.png')?>"/></a>
                      <? endif; ?>
                      <h1 style="font-size: 22px; color: #dc7b11; margin: 5px 20px 0px 10px; text-align: center;"><?=$subject?></h1>
                    </td>
                  </tr>
                </table>
                <table class="content html-content" cellpadding="0" width="100%" cellspacing="0" border="0" style="margin: 0px auto;">
                  <tr>
                    <td>
                      <?=$content?>
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