<? $siteOrderCode = reset($siteOrders) ?>

<div class="title">
  Лог изменения даты отгрузки <?=$siteOrderCode?>
</div>
<?=html_flash_message();?>
<div class="inner">

  <table class="table">
    <thead>
      <tr>
        <th>Изменил</th>
        <th>Дата отгрузки</th>
        <th>Дата</th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($eventLogs as $v) : ?>
        <tr>
          <td>
            <? if ($v['change_by'] == 'admin') : ?>
              <?=$v['admin']['name']?>
            <? else : ?>
              Системно
            <? endif; ?>
          </td>
          <td><?=$v['data']['shipment_date']?></td>
          <td><?=$v['created_at']?></td>
        </tr>
      <? endforeach; ?>
    </tbody>
  </table>
</div>

<? if(isset($pager)):?>
  <?=$this->load->view('includes/admin/parts/pager', array('pager' => $pager), true);?>
<? endif; ?>