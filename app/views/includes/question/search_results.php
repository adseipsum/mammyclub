<table class="main-table-info" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td class="td-1">
      <div class="breadcrumbs">
        <a class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2">Главная</span><span class="s-3"></span></a>
        <a class="crumb first" href="<?=site_url('консультации')?>"><span class="s-1"></span><span class="s-2">Консультация</span><span class="s-3"></span></a>
        <span class="crumb active"><span class="s-1"></span><span class="s-2">Результат поиска</span><span class="s-3"></span></span>
      </div>
    </td>
    <td class="tar td-2">
      <a class="def-but orange-but" href="<?=site_url('задать-вопрос');?>">Задать вопрос</a>
    </td>
  </tr>
</table>

<?=html_flash_message();?>


<? if(!empty($result)): ?>
  <ul class="qa-wide-list">
    <? $i = 1;?>
    <? foreach ($result as $q): ?>
      <li<?=$i==count($result)?' class="last"':'';?>>
        <span class="date"><?=ago($q['date']);?></span>
        <p class="desc">
          <?=highlight($q['description'], $query);?>
          <a class="read-all-link" href="<?=site_url('консультация/' . $q['id']);?>">читать полностью &rarr;</a>
        </p>
        <p class="stats">Задал: <?=$q['user']['name'];?><?=$q['comment_count']>0?' <span class="count-answer">5</span>':'';?></p>
      </li>
      <? $i++; ?>
    <? endforeach; ?>
  </ul>
<? else:?>
  <p>По вашему запросу ничего не найдено.</p>
<? endif; ?>