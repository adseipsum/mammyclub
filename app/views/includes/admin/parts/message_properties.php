<div class="content default-box">
  <div class="title"><?=$lang->line('admin.add_edit.' . $entityName . ".form_title");?></div>
  <?=html_flash_message();?>
  <?=fill_form_with_saved_post('editForm');?>
  <div class="inner">
    <form action="<?=site_url($processLink) . get_get_params();?>" method="post" class="form validate" id="editForm" autocomplete="off" enctype="multipart/form-data">
      
      
      <div id="tabs_msg">
        
        <ul>
          <? foreach($content as $lng => $cnt): ?>
            <li><a href="#websiteTabs-<?=$lng;?>-msg"><?=$lng;?></a></li>
          <? endforeach; ?>
        </ul>
        
        <? foreach($content as $lng => $cnt): ?>
          <div id="websiteTabs-<?=$lng;?>-msg">
            <div class="group">
              <textarea class="text-area h800" name="<?=$lng?>" spellcheck="false"><?=htmlspecialchars($cnt)?></textarea>
            </div>
          </div> <!-- #websiteTabs -->
        <? endforeach; ?>
    
      </div> <!-- #tabs -->
      <div class="clear h15"></div>
      
      <div class="group navform wat-cf">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
      </div>
    </form>
  </div>
</div>
<div class="clear"></div>

<link rel="stylesheet" href="<?=site_js('codemirror/lib/codemirror.css');?>"/>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/lib/codemirror.js');?>"></script>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/lib/codemirror.js');?>"></script>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/mode/xml/xml.js');?>"></script>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/mode/javascript/javascript.js');?>"></script>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/mode/css/css.js');?>"></script>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/mode/clike/clike.js');?>"></script>
<script type="text/javascript" src="<?=site_js('admin/packages/codemirror/mode/php/php.js');?>"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var editor;
    $('.text-area').each(function() {
      var editor = CodeMirror.fromTextArea(this, {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 2,
        indentWithTabs: false,
        tabMode: "shift"
      });
    });
    $('#tabs_msg').tabs();
  });
</script>