<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xml:lang="en" >
  <head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />

    <?if (isset($header) && $header) :?>
      <title><?=$header['title'];?></title>
    <?endif; ?>

    <link rel="shortcut icon" href="<?=site_img("favicon.ico");?>" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?=site_css("switch-select.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/jquery.chosen.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("common/zero.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/base.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/messages.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/buttons.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/forms.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/list.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/add_edit.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/multipleselect.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/fileuploader.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/jquery.alerts.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/jquery-ui-all.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("common/ajax-popup2.css");?>"/>

    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/jquery.fileupload.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("buttons.css");?>"/>

    <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

    <? if (isset($loggedInAdmin)): ?>
      <link rel="stylesheet" type="text/css" href="<?=site_css("admin/themes/" . $loggedInAdmin['theme'] . ".css");?>"/>
    <? endif; ?>

    <!--[if IE 6]>
      <link href="<?=site_css("style_ie.css");?>" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=site_js("common/base/iepngfix_tilebg.js");?>"></script>
    <![endif]-->

    <script type="text/javascript">
      var base_url = '<?=site_url();?>';
      var admin_url = '<?=$adminBaseRoute;?>';
      var current_url = '<?=str_replace(site_url(), '', pager_remove_from_str(current_url()));?>';
     	<? if (isset($entityName)): ?>
    		var entityName = "<?=$entityName?>";
    	<? endif; ?>
      var messages = {};
      messages['entity_delete_confirm'] = '<?=lang('admin.confirm.entity_delete');?>';
      messages['entities_delete_confirm'] = '<?=lang('admin.confirm.enties_delete');?>';
      messages['image_not_find'] = '<?=lang('admin.image_not_found');?>';
      messages['confirmation_dialog_title'] = '<?=lang('admin.confirm.dialog_title');?>';
      messages['information_dialog_title'] = '<?=lang('admin.confirm.information_dialog_title');?>';
      messages['delete_many_alert'] = '<?=lang('admin.confirm.no_items_selected');?>';
      messages['yes_button'] = '<?=lang('admin.confirm.yes_button');?>';
      messages['no_button'] = '<?=lang('admin.confirm.no_button');?>';
    </script>

    <script type="text/javascript" src="<?=site_js("admin/jquery/jquery-1.10.min.js");?>"></script>

    <?/*
      <script type="text/javascript" src="<?=site_js("admin/jquery/jquery.min.js");?>"></script>
      <script type="text/javascript" src="<?=site_js('admin/highcharts.js');?>"></script>
    */?>
    <script type="text/javascript" src="<?=site_js("admin/jquery/jquery-migrate.js,
                                                    admin/jquery/metadata.js,
                                                    admin/jquery/validate.js,
                                                    admin/jquery/localization/messages_ru.js,
                                                    admin/jquery/localization/ui.datepicker-ru.js,
                                                    admin/jquery/ui/core.min.js,
                                                    admin/jquery/ui/position.min.js,
                                                    admin/jquery/ui/widget.min.js,
                                                    admin/jquery/ui/mouse.js,
                                                    admin/jquery/ui/button.js,
                                                    admin/jquery/ui/sortable.js,
                                                    admin/jquery/ui/datepicker.js,
                                                    admin/jquery/ui/combobox.js,
                                                    admin/jquery/ui/tabs.js,
                                                    admin/jquery/cookie.js");?>"></script>

	<script type="text/javascript" src="<?=site_js("admin/jquery/autocomplete.js,
                                                    admin/jquery/alerts.js,
                                                    admin/jquery/dragsort.js,
                                                    admin/jquery/translit.js,
                                                    admin/jquery/bgiframe.min.js,
                                                    admin/jquery/tools.js,
                                                    admin/jquery/selectboxes.js,
                                                    admin/jquery/counter-1.0.js,
                                                    admin/jquery/query.js,
                                                    admin/jquery/placeholder.js,
                                                    admin/jquery/print.js,
                                                    admin/jquery/chosen.min.js");?>"></script>

    <script type="text/javascript" src="<?=site_js("admin/packages/codemirror/js/codemirror.js,
    	                                              admin/packages/tiny_mce/jquery.tinymce.js,
                                                    common/ajax-popup2.js");?>"></script>

    <script type="text/javascript" src="<?=site_js("admin/multiple.select.js,
                                                    admin/confirm.js,
                                                    admin/checkboxes.js,
                                                    admin/overlay.image.js,
                                                    admin/codemirror/js/codemirror.js,
                                                    admin/flowplayer.min.js,
                                                    admin/switch.select.js,
                                                    admin/password.generator.js,
																									  admin/editor.changer.js,
																									  admin/tinyimage.picker.js,
                                                    admin/admin.js,
                                                    project.admin.js");?>"></script>

    <script type="text/javascript" src="<?=site_js("fileupload/jquery.ui.widget.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("fileupload/jquery.iframe-transport.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("fileupload/jquery.fileupload.js");?>"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.full.min.js"></script>

    <? if(ENV == 'PROD'): ?>
      <script type="text/javascript">
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-46834478-1', 'auto');
      ga('send', 'pageview',{'dimension1':'employee'});
      ga('create', 'UA-46834478-2', 'auto', 'subGA');
      ga('subGA.send', 'pageview',{'dimension1':'employee'});
      </script>
    <? endif; ?>

    <!-- Admin activity tracking -->
    <script type="text/javascript">

      $(document).ready(function() {

        function send_track_request() {
          var ajaxUrl = base_url + admin_url + '/siteorder/ajax_admin_activity_tracking';
          $.getJSON(ajaxUrl);
        }
        function send_check_request() {
          var ajaxUrl = base_url + admin_url + '/siteorder/ajax_check_activity';
          $.getJSON(ajaxUrl, function(data) {
            if(data.length > 0) {
              var msg = 'Над этой странице сейчас работают: ';
              for(i = 0; i<data.length; i++) {
                msg += data[i].email;
                if(i!=data.length-1) {
                  msg += ', ';
                }
              }
              $('.js-activity-msg').show();
              $('.js-activity-msg .message').html('<p>' + msg + '</p>');
            } else {
              $('.js-activity-msg').hide();
            }
          });
        }
        <?/*
        send_track_request();
        setInterval(function() {
          send_track_request();
        }, 10000);

        if(current_url.indexOf('/siteorder/') != '-1') {
          send_check_request();
          setInterval(function() {
            send_check_request();
          }, 10000);
        }
        */?>
    	});
    </script>

  </head>

  <body>
    <div id="wrapper-admin">

      <div id="main">
        <div id="header">
          <span id="is-mobile">&nbsp;</span>
          <?if (!url_contains("login") && !url_contains("forgot_password")) :?>
            <div id="user-navigation">
              <ul>
                <li><a href="<?=site_url("$adminBaseRoute/change_info");?>"><?=lang('admin.change_info');?></a></li>
                <li><a class="logout" href="<?=site_url("$adminBaseRoute/logout");?>"><?=lang('admin.logout');?></a></li>
              </ul>
            </div>
          <?endif;?>
          <div id="main-navigation">
            <div class="admin-title"><?=lang('admin.title');?> &ndash; <?=site_url();?> <a class="go-to" target="_blank" href="<?=site_url('/');?>"><?=lang('admin.goto_website');?></a></div>
          </div>
          <div class="clear"></div>
        </div><!-- #header -->

        <div id="center">

          <div class="flash js-activity-msg" style="display: none;">
            <span class="close"></span>
            <div class="message error">
            </div>
          </div>

          <?if (isset($hasSidebar) && $hasSidebar) :?>
            <div id="sidebar">
              <?=$this->load->view('includes/admin/parts/admin_menu')?>
            </div><!-- #sidebar -->
          <?endif;?>
          <div class="admin-part" <?= (!isset($hasSidebar) || !$hasSidebar) ? "style=\"margin-left: 0px;\"" : ""?>>
            <div class="inner-b">
            	<div id="content" class="content"><?=$content?></div>
            </div>
            <div class="clear"></div>
          </div><!-- adminPart -->
          <div class="clear"></div>
        </div><!-- #center -->

        <div class="clear push-box"></div>
      </div><!-- #main -->

      <div id="footer">
        <p class="block"><?=lang('admin.footer_copyright');?>&nbsp;<a href="mailto:support@itirra.com?subject=Support (<?=current_url();?>)&body=" style="color: #fff;" title="<?=lang('admin.footer_support');?>"><?=lang('admin.footer_support');?></a></p>
      </div><!-- #footer -->

    </div><!-- #wrapper -->
    <div class="apple_overlay" id="overlay">
      <div class="overlayContent">
        <div class="contentWrap"></div>
      </div>
    </div>

    <? /* if(ENV == 'PROD'): ?>
      <!-- Analytics Employee secret page for statistics -->
      <iframe src="https://shop.mammyclub.com/analytics-employee-secret-page" style="width: 1px; height: 1px;"></iframe>
    <? endif; */?>

    <? $this->view("includes/parts/ajax-popup"); ?>
  </body>

</html>