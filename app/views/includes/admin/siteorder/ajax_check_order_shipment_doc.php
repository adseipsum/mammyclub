<div class="default-box js-order-send-but">
  <div class="title">
    Загрузить форму заявки на отгрузку?
  </div>
  <button value="1">Да</button><button value="0">Нет</button>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('.js-order-send-but button').click(function() {
      if ($(this).val() == 1) {
        <? if ($siteorder['siteorder_status']['k'] == SITEORDER_STATUS_CONFIRMED_STOCK) : ?>
          window.location.href = base_url + admin_url + "/siteorder/generate_order_shipmet_doc_on_confirmed_stock/<?=$siteorder['id'];?>";
        <? else : ?>
          window.location.href = base_url + admin_url + "/siteorder/generate_order_shipmet_doc/<?=$siteorder['id'];?>";
        <? endif; ?>
      }
      $('.close-ajaxp-modal').click();
    });
  });
</script>