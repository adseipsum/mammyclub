<? $siteOrderCode = reset($siteOrders) ?>

<div class="title">
  Лог добавления/удаления товаров <?=$siteOrderCode?>
</div>
<?=html_flash_message();?>
<div class="inner">

  <table class="table">
    <thead>
      <tr>
        <th>Изменил</th>
        <th>Действие</th>
        <th>Название товара</th>
        <th>Название параметра</th>
        <th>Кол-во</th>
        <th>Цена товара</th>
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
          <td><?=$v['data']['action']?></td>
          <td><?=$v['data']['product_name']?></td>
          <td><?=$v['data']['parameter_name']?></td>
          <td><?=$v['data']['qty']?></td>
          <td><?=$v['data']['price']?></td>
          <td><?=$v['created_at']?></td>
        </tr>
      <? endforeach; ?>
    </tbody>
  </table>
</div>

<? if(isset($pager)):?>
  <?=$this->load->view('includes/admin/parts/pager', array('pager' => $pager), true);?>
<? endif; ?>