<div class="popup-form popup-login popup-added js-popup-added">
  <h1 class="title">Товар успешно добавлен в корзину!</h1>

  <div class="col-wrap" style="text-align: center;">
    <a href="<?=shop_url($product['page_url']);?>" class="h-but green-but ajaxp-exclude">Продолжить покупки</a>
    <a href="<?=shop_url('оформить-заказ')?>" class="h-but orange-but ajaxp-exclude">Оформить заказ</a>
  </div>

</div>


<script type="text/javascript">
  $(document).ready(function() {
    var redirectUrlOnClose = '<?=shop_url($product['page_url']);?>';
    $('.ajaxp-modal-bg, .close-ajaxp-modal').click(function() {
      window.location.href = redirectUrlOnClose;
      return false;
    });
  });
</script>
