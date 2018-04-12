<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xml:lang="en" >
  <head>
  	<title>Upload File</title>
  	<script type="text/javascript" src="../../tiny_mce_popup.js" ></script>
  	<script type="text/javascript" src="editor_plugin.js" ></script>
    <script type="text/javascript" src="../../../../jquery/jquery.min.js"></script>
  	<base target="_self" />
  </head>
  
  <style>
    .link:hover {background-color: #c9d0f4;}
  </style>
  
  <body>
    <? if(!isset($_GET['file_path']) && !isset($_GET['file_name'])): ?>
      <div>
        <form id="form" action="#" method="post" enctype="multipart/form-data">
          <p>File to upload:</p>
          
          <input id="file_input" name="file" type="file" size="62" style="float: left; border: 1px solid #ccc;" />
          <div style="clear: both; height: 10px;"></div>
          <input id="submit" class="link" type="submit" value="Upload File" style="width: 150px; cursor: pointer; float: left;"/>
          <div style="clear: both; "></div>
          
          <div id="progress_div" style="display: none;"><img src="progress.gif" alt="wait..." style="padding-top: 5px;"/></div>
        </form>
      </div>
      <script type="text/javascript" ><!--
        $('#form').attr('action', tinyMCEPopup.editor.settings.document_base_url + tinyMCEPopup.editor.settings.file_upl_path);
        $('#form').submit(function() {
          if ($('#file_input').val() == "") {
            alert("Please choose a file");
            return false;
          } else {
            $('#submit').hide();
            $('#progress_div').fadeIn();
          }
        });
      --></script>
    <? else: ?>
     <script type="text/javascript">ClosePluginPopup('<a href="<?=$_GET['file_path'];?>"><?=$_GET['file_name'];?></a>');</script>
    <? endif; ?>
  </body>
</html>