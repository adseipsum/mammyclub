<table class="main-table-info" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td class="td-1">
      <div class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a itemprop="url" class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a>
        <span class="crumb active"><span class="s-1"></span><span class="s-2">Консультация</span><span class="s-3"></span></span>
      </div>
    </td>
    <td class="tar td-2">
      <a class="def-but orange-but" href="<?=site_url('задать-вопрос');?>">Задать вопрос</a>
    </td>
  </tr>
</table>

<?=html_flash_message();?>

<? if(!empty($settings['questions_text_top'])): ?>
  <div class="intro-3 html-content">
    <?=$settings['questions_text_top'];?>
  </div>
<? endif; ?>

<?=$this->view('includes/question/parts/question_list', array('questions' => $questions))?>

<? if(!empty($settings['questions_text_bottom'])): ?>
  <div class="intro-2 html-content">
    <?=$settings['questions_text_bottom'];?>
  </div>
<? endif; ?>