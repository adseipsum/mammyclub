<? $this->view("includes/shop/parts/order_department_block"); ?>

<div class="kick-it"></div>

<?/* $this->view("includes/shop/parts/cart_block"); */?>
<div class="kick-it hide-on-product"></div>

<? $this->view("includes/shop/parts/delivery_warranty_payment_block"); ?>

<? if (url_contains('m-good')): ?>
  <div class="kick-it"></div>

  <div class="reminder-good-box">
    <div class="top tac">
      <p class="name">Britax автокресло Multy-Tech 2</p>
    </div>
    <div class="middle">
      <table cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="td-1">
            <img src="<?=site_img('g_3.png')?>" alt="Britax автокресло Multy-Tech 2" title="Britax автокресло Multy-Tech 2" />
          </td>
          <td class="td-2">
            <span class="old-price price"><b>290</b> грн.</span>
            <span class="real-price price"><b>275</b> грн.</span>
            <span class="def-but orange-but">Купить</span>
          </td>
        </tr>
      </table>
    </div>
    <div class="bottom tac">
      <span class="in-stock">Есть в наличии</span>
      <p class="sale">Акция заканчивается через 10 дней</p>
    </div>
  </div>
<? endif; ?>
