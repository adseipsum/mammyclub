<? $siteOrderCode = reset($siteOrders) ?>

<div class="title">
  Лог изменения статуса заказа <?=$siteOrderCode?>
</div>
<?=html_flash_message();?>
<div class="inner">

  <?//=$this->load->view('includes/admin/parts/before_entity_list')?>


  <table class="table">
    <thead>
      <tr>
        <th>Изменил</th>
        <th>Статус до</th>
        <th>Статус после</th>
        <th>Дата</th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($eventLogs as $v) : ?>
        <tr>
<!--          <td><a href="--><?//=admin_site_url('siteorder/add_edit/' . $v['entity_id'])?><!--" target="_blank">--><?//=$siteOrders[$v['entity_id']]?><!--</a></td>-->
          <td>
            <? if ($v['change_by'] == 'admin') : ?>
              <?=$v['admin']['name']?>
            <? else : ?>
              Системно
            <? endif; ?>
          </td>
          <td><?=$v['data']['from']['name']?></td>
          <td><?=$v['data']['to']['name']?></td>
          <td><?=$v['created_at']?></td>
        </tr>
      <? endforeach; ?>
    </tbody>
  </table>
</div>

<? if(isset($pager)):?>
  <?=$this->load->view('includes/admin/parts/pager', array('pager' => $pager), true);?>
<? endif; ?>