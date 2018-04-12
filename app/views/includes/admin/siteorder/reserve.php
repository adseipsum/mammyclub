<? $storeId = $cartItem['siteorder']['shipment_store_id'] ?>

<div class="default-box js-ty-send-but">
  <div class="title">
    Бронь по товару <span style="color: #ef5e67;"><?=$product['name']?> <?=!empty($productGroup) ? $productGroup['main_parameter_value']['name'] : ''?></span>
  </div>
  <?=html_flash_message()?>
  <div>
    Количество товара в заказе: <span style="color: #ef5e67;"><?=$cartItem['qty']?></span> <span style="color: #ededed;">|</span> Товара забронировано: <span style="color: #ef5e67;"><?=$currentReserve['qty']?></span>
  </div>

  <div style="margin-top: 10px;">
    <? if (!empty($storeId) && ($storeId == ZAMMLER_STORE_ID || $storeId == MC_STORE_ID)): ?>
      <form action="<?=site_url(ADMIN_BASE_ROUTE.'/siteorder/ajax_reserve_process/' . $cartItem['id']);?>" method="post">
        <table class="table">
          <thead>
            <tr>
              <th>Бронь</th>
              <th>Свободные остатки</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="number" min="0" max="<?=isset($storeInventories[$storeId]['free_qty']) ? $storeInventories[$storeId]['free_qty'] + $currentReserve['qty'] : 0?>" name="stores[<?=$storeId?>][qty]" value="<?=$currentReserve['qty']?>"></td>
              <td><?=isset($storeInventories[$storeId]['free_qty']) ? $storeInventories[$storeId]['free_qty'] : 0?></td>
            </tr>
          </tbody>
        </table>

        <input id="js-reserve-submit" type="submit" value="Сохранить">
      </form>
    <? else : ?>
      <h2>Не указан склад отгрузки</h2>
    <? endif; ?>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.close-ajaxp-modal, .ajaxp-modal-bg').click(function() {
      updateSiteOrderCart();
    });
  });
</script>