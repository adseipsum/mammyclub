<? if(!empty($cartItems)): ?>
  <a class="mobile-basket" href="<?=shop_url('оформить-заказ')?>"><span class="numb"><?=count($cartItems)?></span></a>
<? endif; ?>
<div class="r-part">
  <? $this->view("includes/shop/parts/right_part"); ?>
</div>
<div class="l-part">
  <div class="inner-b">
    <div class="page" style="padding: 20px 0;">
      <h1>Оплата заказа</h1>
      <p>Для осуществления оплаты Вы будете перенаправлены на сайт <a href="https://liqpay.com">liqpay.com</a></p>
      <p style="margin: 10px 0 0 0;">Если вас автоматически не перенаправило в течении 15 секунд, пожалуйста нажмите на кнопку "Перейти и оплатить".</p>
      <form action="https://www.liqpay.com/?do=clickNbuy" method="post" id="js-liqpay-form">
        <input type="hidden" name="operation_xml" value="<?=$paymentData['operation_xml'];?>" />
        <input type="hidden" name="signature" value="<?=$paymentData['signature'];?>" />
        <div class="tac" style="margin: 20px 0 0 0;">
          <button type="submit" class="checkout_button">Перейти и оплатить</button>
        </div>
      </form>
    </div>
    <script type="text/javascript">
      $('#js-liqpay-form').submit();
    </script>
  </div>
  <div class="clear"></div>
</div>



