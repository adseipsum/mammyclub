<h2>Резерв</h2>

<div class="content">
  <table class="table">
    <thead>
      <tr>
        <th>Заказ</th>
        <th>Количество</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <? foreach ($reserves as $reserve) : ?>
        <tr>
          <td><a class="ajaxp-exclude" target="_blank" href="<?=admin_site_url('siteorder/add_edit/' . $reserve['siteorder_item']['siteorder_id'])?>">Заказ №<?=$siteOrders[$reserve['siteorder_item']['siteorder_id']]?></a></td>
          <td><?=$reserve['qty']?></td>
          <td><a class="ajaxp-exclude" target="_blank" href="<?=admin_site_url('siteorder/preview/' . $reserve['siteorder_item']['siteorder_id'])?>">Просмотр</a></td>
        </tr>
      <? endforeach; ?>
    </tbody>
  </table>
</div>