<h2>Настройки парсера</h2>

<div class="content">
  <form action="<?=admin_site_url('/storeinventory/ajax_web_parser_setting_process') . '?product_id=' . $setting['product_id'] . '&&product_group_id=' . $setting['product_group_id'] . '&&store_id=' . $setting['store_id'];?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
    <div class="group">
      <div class="input-row">
        <!-- Search string -->
        <label for="url"><b>Url:</b></label>
        <input id="url" type="text" name="url" value="<?=$setting['url']?>" style="width: 90%;"/><br/>
      </div>
    </div>
<!--    <div class="group">-->
<!--      <div class="input-row" style="padding-right: 15px;">-->
<!--        <!-- Search string -->
<!--        <label for="use_for_product">Применить для всех параметров:</label>-->
<!--        <input type="hidden" name="use_for_product" value="0">-->
<!--        <input id="use_for_product" type="checkbox" name="use_for_product" value="1" style="width: 25px;" --><?//=isset($setting['config']['use_for_product']) && $setting['config']['use_for_product'] == 1 ? 'checked="checked"' : '';?>
<!--      </div>-->
<!--    </div>-->
    <div class="group">
      <div class="input-row button-box">
        <!-- Submit -->
        <button class="button" type="submit">Сохранить</button><br/>
      </div>
    </div>
  </form>
</div>