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


    <? if (isset($loggedInAdmin)): ?>
      <link rel="stylesheet" type="text/css" href="<?=site_css("admin/themes/" . $loggedInAdmin['theme'] . ".css");?>"/>
    <? endif; ?>


    <!--
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-chrome.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-green.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-red.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-grey.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-dark.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-orange.css");?>"/>
    <link rel="stylesheet" type="text/css" href="<?=site_css("admin/base/theme-blue.css");?>"/>
     -->
    <? if(isset($entityName) && $entityName == 'ProposalsManagement'):?>
      <link rel="stylesheet" type="text/css" href="<?=site_css("admin/mis-managment.css");?>"/>
    <? endif; ?>

    <!--[if IE 6]>
      <link href="<?=site_css("style_ie.css");?>" media="screen" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?=site_js("common/base/iepngfix_tilebg.js");?>"></script>
    <![endif]-->

    <script type="text/javascript">
      var base_url = '<?=site_url();?>';
      var admin_url = '<?=$adminBaseRoute;?>';

     	<?if(isset($entityName)): ?>
    		var entityName = "<?=$entityName?>";
    	<?endif;?>

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

    <script type="text/javascript" src="<?=site_js("admin/jquery/jquery.min.js");?>"></script>
    
    <script type="text/javascript" src="<?=site_js("admin/jquery/metadata.js,
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
                                                    admin/jquery/cookie.js,
                                                    admin/jquery/autocomplete.js,
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
    	                                              admin/packages/tiny_mce/jquery.tinymce.js");?>"></script>
        
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
                                                    admin/admin.js");?>"></script>


    <script type="text/javascript" src="<?=site_js("packages/tiny_mce/jquery.tinymce.js");?>"></script>
    <script type="text/javascript" src="<?=site_js("editor.changer.js")?>"></script>

  </head>

  <body>
    <div id="wrapper-admin" style = "min-width:0;">

      <div id="main">
        <div id="header">
          <div class="clear"></div>
        </div><!-- #header -->

        <div id="center">
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
    </div><!-- #wrapper -->
    <div class="apple_overlay" id="overlay">
      <div class="overlayContent">
        <div class="contentWrap"></div>
      </div>
    </div>
  </body>
</html>