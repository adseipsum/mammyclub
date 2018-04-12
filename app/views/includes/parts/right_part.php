<? $this->view("includes/ad_slots/right_banner"); ?>

<div class="border-box def-box numeric-box">
  <h2 class="title">О нашем сайте</h2>
  <div class="cont html-content">
    <?=$settings['right_part_about'];?>
  </div>
</div>

<div class="kick-it"></div>

<?/* if(!empty($lastQuestions)): ?>
  <div class="border-box def-box">
    <h2 class="title">Последние консультации</h2>
    <div class="cont">
      <ul class="qa-list">
        <? $i = 1; ?>
        <? foreach ($lastQuestions as $q): ?>
          <li <?= $i==count($lastQuestions)?'class="last"':'';?>>
            <a href="<?=site_url($q['page_url']);?>"><?=truncate(strip_tags($q['name']), 30, '...');?></a>
            <p>Вопрос задан <?=ago($q['date']);?></p>
            <? if($q['comment_count'] > 0): ?>
              <span class="answer"><?=number_noun($q['comment_count'], 'right_part.answers')?></span>
            <? endif; ?>
          </li>
          <? $i ++; ?>
        <? endforeach; ?>
      </ul>
      <div class="tac">
        <a href="<?=site_url('задать-вопрос')?>" class="def-but green-but">Задать свой вопрос!</a>
      </div>
    </div>
  </div>
  <div class="kick-it"></div>
<? endif; */?>

<? if (isset($showBroadcastBlock) && $showBroadcastBlock): ?>
  <?=$this->view('includes/parts/broadcast_block_right');?>
<? endif; ?>