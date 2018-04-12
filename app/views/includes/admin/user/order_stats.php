<div class="content default-box">

  <h2 class="title">
    <span class="fl">Статистика пользователя <?=$user['name'];?></span>
    <a class="link" style="float: right;" href="<?=admin_site_url('user')?>"><?=lang('admin.add_edit_back');?></a>
    <div class="clear"></div>
  </h2>
  <?=html_flash_message();?>

    <style>
      .item-table tr td {padding: 10px;}
      .item-table .mainRow td {padding: 20px 10px; font-weight: bold; font-size: 12px;}
    </style>


    <div class="inner export">

      <?=$this->view("includes/admin/user/parts/menu-top"); ?>

      <? if(!empty($user['orders'])): ?>

        <table class="item-table" cellspacing="0" cellpadding="0" border="1">
          <tr class="mainRow">
            <td>Дата</td>
            <td>Наименование</td>
            <td>Цена</td>
            <td>Количество</td>
            <td>Сумма</td>
            <td>Комментарий</td>
            <td>Адрес доставки</td>
            <td>Контактное лицо</td>
            <td>Телефон</td>
            <td>Способ оплаты</td>
            <td>Статус</td>
            <td>Оплата</td>
            <td>Запрос отзыва</td>
            <td>Канал привлечения</td>
            <td>Комментарий менеджера</td>
          </tr>
          <? foreach ($user['orders'] as $order): ?>
            <tr>
              <td>
                <p><?=$order['created_at'];?></p>
              </td>
              <td>
                <? if(!empty($order['cart']) && is_not_empty($order['cart']['items'])): ?>
                  <ul>
                    <? foreach ($order['cart']['items'] as $item): ?>
                      <li>
                        <a href="<?=site_url($item['product']['page_url']);?>" target="_blank"><?=$item['product']['name'];?> <?=!empty($item['additional_product_params'])?'(' . implode(' ', $item['additional_product_params']) . ')':'';?></a>
                      </li>
                    <? endforeach; ?>
                  </ul>
                <? endif; ?>
              </td>
              <td>
               <? if(!empty($order['cart']) && is_not_empty($order['cart']['items'])): ?>
                  <ul>
                    <? foreach ($order['cart']['items'] as $item): ?>
                      <li><?=$item['price'];?></li>
                    <? endforeach; ?>
                  </ul>
               <? endif; ?>
              </td>
              <td>
               <? if(!empty($order['cart']) && is_not_empty($order['cart']['items'])): ?>
                  <ul>
                    <? foreach ($order['cart']['items'] as $item): ?>
                      <li><?=$item['qty'];?></li>
                    <? endforeach; ?>
                  </ul>
               <? endif; ?>
              </td>
              <td><?=$order['cart']['total'];?></td>
              <td><?=$order['comment'];?></td>
              <td><?=$order['delivery_city'] . ', ' . $order['delivery_street'] . ', ' . $order['delivery_house'] . ', ' . $order['delivery_flat'] . ', ' . $order['delivery_post'];?></td>
              <td><?=$order['fio'];?></td>
              <td><?=$order['phone'];?></td>
              <td><?=$order['payment_type'];?></td>
              <td><?=lang('enum.siteorder.status.' . $order['status']);?></td>
              <td><?=!empty($order['paid'])?'Да':'Нет';?></td>
              <td><?=!empty($order['request_review'])?'Да':'Нет';?></td>
              <td><?=$order['inv_channel'];?></td>
              <td><?=$order['comment_manager'];?></td>
            </tr>
          <? endforeach; ?>
        </table>

        <div class="clear"></div>
      <? else:?>
        <p>У пользователя пока еще нет заказов..</p>
      <? endif; ?>
    </div>



</div>