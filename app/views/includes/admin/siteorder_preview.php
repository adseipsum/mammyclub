<div class="content default-box">
  <div class="title">
    <h3>Просмотр заказа</h3>
    <a class="link" href="<?=admin_site_url('siteorder');?>"><?=lang('admin.add_edit_back');?></a>
  </div>
  <div class="inner">
    <form class="form" id="editForm">
      <? foreach ($fields as $key => $options): ?>
        <? if ($options['type'] == 'hidden') continue; ?>
        <? if(isset($e[$key]) && !empty($e[$key])): ?>
          <? $langKey = "admin.add_edit.$entityName.$key" ?>
          <div class="group group-row">
            <label class="label"><?=lang($langKey);?></label>
            <? if($options['type'] == 'textarea'): ?>
              <div style="height: auto; border: 1px solid #AAA; background: #ebebe4; padding: 10px; line-height: 18px;"><?=$e[$key];?></div>
            <? else: ?>
              <input type="text" class="text-field" value="<?=is_array($e[$key]) ? $e[$key]['name'] : $e[$key];?>" disabled="disabled" />
            <? endif; ?>
					</div>
        <? endif; ?>
      <? endforeach; ?>
      <? if(!empty($cart)): ?>
        <div class="group group-row">
          <label class="label">Корзина</label>
          <?=$this->view('includes/admin/siteorder/cart_view', array('cart' => $cart), TRUE);?>
        </div>
      <? endif; ?>
    </form>
  </div>
</div>