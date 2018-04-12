<div class="title">
  Лог отправленных СМС
</div>
<?=html_flash_message();?>
<div class="inner">

  <?//=$this->load->view('includes/admin/parts/before_entity_list')?>


  <table class="table">
    <thead>
    <tr>
      <th>Номер</th>
      <th>Текст</th>
      <th>Отправленно успешно</th>
      <th>Дата</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($eventLogs as $v) : ?>
      <tr>
        <td><?=$v['data']['to']?></td>
        <td><?=str_replace('+', ' ', $v['data']['message'])?></td>
        <td><?=$v['is_success'] ? 'Да' : 'Нет'?></td>
        <td><?=$v['created_at']?></td>
      </tr>
    <? endforeach; ?>
    </tbody>
  </table>
</div>

<? if(isset($pager)):?>
  <?=$this->load->view('includes/admin/parts/pager', array('pager' => $pager), true);?>
<? endif; ?>