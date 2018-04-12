<script type="text/javascript" src="<?=site_js('jquery/plugins/tools.tooltip.js')?>"></script>

<style>
  .active-result {color: #000;}
</style>

<div class="content default-box">
  <div class="title">
    Остатки
    <a class="link" href="<?=site_url('madmin');?>">&lt; Назад</a>
  </div>
  <?=html_flash_message();?>
<!--  admin storeinventory operation type-->
  <div class="inner js-inner">
    <div class="group" style="padding-bottom: 10px">
      <label class="label" for="js-standard-store" style="display: block; margin-bottom: 5px"><strong>Тип операции обновления</strong></label>
      <select id="js-standard-store" name="standard_store" >
        <option value="inventory" selected> Остатки </option>
        <option value="standard_store"> Нормативный склад </option>
        <option value="inventory_prices"> Цены и себестоимость </option>
      </select>
    </div>
    <div id="js-custom-standard-store-view" style="display: none"><? $this->view("includes/admin/store_inventory/standard_store_view"); ?></div>
    <div id="js-custom-inventory-prices-view" style="display: none"><? $this->view("includes/admin/store_inventory/inventory_prices_view"); ?></div>
    <!-- end admin storeinventory operation type -->

    <form  id="js-default-form" action="<?=pager_remove_from_str(current_url()) . '/process_file';?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">
      <div class="group">
        <label class="label" for="store">Склад</label>
        <select class="required" id="store" name="store">
          <option value="" >-- Выберите склад --</option>
          <? foreach ($stores as $k => $v) : ?>
            <option value="<?=$k?>"><?=$v?></option>
          <? endforeach; ?>
        </select>
      </div>

      <? $alphabet = range('A', 'Z'); ?>
      <? foreach ($config as $code => $cfg) : ?>
        <? if (isset($cfg['files']) && isset($idCodeStoreMap[$code])) : ?>
          <div class="group js-store-file-config" id="store-<?=$idCodeStoreMap[$code]?>" style="display: none;">
            <label class="label" for="store_config_file">Конфигурация</label>
            <select class="js-select-config-file-name" name="store_config_file[<?=$idCodeStoreMap[$code]?>]">
              <option value="">-- Выберите конфигурацию --</option>
              <? foreach ($cfg['files'] as $file => $fileCfg) : ?>
                <option value="<?=$file?>"><?=$file?></option>
              <? endforeach; ?>
            </select>

            <? foreach ($cfg['files'] as $file => $fileCfg) : ?>
              <div class="js-file-config-info" id="js-config-info-<?=$file?>" style="display: none;">
                <? foreach ($fileCfg['worksheets'] as $listName => $worksheetCfg) : ?>
                  <p>
                    Тип файла: Excel<br>
                    Лист: <?=$listName?><br>
                    Идентификация по <?=in_array('bar_code', $worksheetCfg['identify_columns']) ? 'штрих-коду' : 'артикулу';?> в колонке: <?=key($worksheetCfg['identify_columns'])?><br>
                    Колонка со значением: <?=implode(', ', $worksheetCfg['value_columns'])?><br>
                    <? if (isset($worksheetCfg['value_replace'])) : ?>
                      Замены значений:<br>
                      <? foreach ($worksheetCfg['value_replace'] as $word => $newValue) : ?>
                        <?=$word?> = <?=$newValue?><br>
                      <? endforeach; ?>
                    <? endif; ?>
                  </p>
                <? endforeach; ?>
              </div>
            <? endforeach; ?>
          </div>
        <? elseif(isset($idCodeStoreMap[$code])) : ?>
          <div class="group js-store-file-config" id="store-<?=$idCodeStoreMap[$code]?>" style="display: none;">
            <b>Кастомные настройки:</b><br>
            <? if (isset($cfg['csv'])): ?>
              <p>
                Тип файла: csv<br>
                Идентификация по <?=in_array('bar_code', $cfg['csv']['identify_columns']) ? 'штрих-коду' : 'артикулу';?> в колонке: <?=$alphabet[0]?><br>
                Колонка со значением: <?=$alphabet[$cfg['csv']['value_columns'][0]]?><br>
              </p>
            <? else : ?>
              <p>
                Тип файла: Excel<br>
                <? foreach ($cfg['worksheets'] as $listName => $worksheetCfg) : ?>
                  <p>
                    Лист: <?=$listName?><br>
                    Идентификация по <?=in_array('bar_code', $worksheetCfg['identify_columns']) ? 'штрих-коду' : 'артикулу';?> в колонке: <?=key($worksheetCfg['identify_columns'])?><br>
                    Колонка со значением: <?=implode(', ', $worksheetCfg['value_columns'])?><br>
                    <? if (isset($worksheetCfg['value_replace'])) : ?>
                      Замены значений:<br>
                      <? foreach ($worksheetCfg['value_replace'] as $word => $newValue) : ?>
                        <?=$word?> = <?=$newValue?><br>
                      <? endforeach; ?>
                    <? endif; ?>

                  </p>
                <? endforeach; ?>
              </p>
            <? endif; ?>
          </div>
        <? endif; ?>
      <? endforeach; ?>

      <div class="group js-store-file-config" id="store-default" style="display: none;">
        <b>Стандартные настройки</b>
        <p>
          Тип файла: Excel<br>
          Лист: TDSheet<br>
          Идентификация по штрих-коду в колонке: A<br>
          Колонка со значением: B<br>
          Себестоимость: C<br>
          Цена: D<br>
        </p>
      </div>

      <div class="group">
        <label class="label" for="import_file">Файл</label>
        <input class="text-field required" type="file" name="import_file"/>
      </div>

      <div class="group">
        <input id="set_zero_on_not_exists" class="checkbox " type="checkbox" name="set_zero_on_not_exists" value="1" checked="checked"/>
        <label class="cp" for="set_zero_on_not_exists">Обнулять остатки товоров которые отсутствуют в файле</label>
      </div>

      <div class="group">
        <input id="update_price_and_cost_price" class="checkbox " type="checkbox" name="update_price_and_cost_price" value="1"/>
        <label class="cp">Обновлять себестоимость и цену</label>
      </div>

      <div class="group navform wat-cf tac">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
      </div>
    </form>

    <div class="search-box mr100">
      <div class="float-left search-container" style="width: 100%">
        <!-- Search bar -->
        <form action="<?=pager_remove_from_str(current_url())?>" method="get" class="query-params">
          <div class="float-left">
            <div class="input-row" style="padding-right: 15px;">
              <!-- Search string -->
              <img id="search_loader" class="search_loader" src="<?=site_img('admin/icons/small_back_loader.gif');?>" alt="Загружаем..." style="display: none;"/>
              <label for="q"><?=lang('admin.search.search_string');?></label>
              <input id="q" type="text" name="q" value="" tabindex="1"/><br/>
              <span class="description"><?=lang('admin.search.product.description');?></span>
            </div>
            <div class="input-row" style="padding-right: 15px;">
              <!-- Search string -->
              <label for="bar_code">Штрих-код:</label>
              <input id="bar_code" type="text" name="bar_code" value="<?=isset($_GET['bar_code']) && !empty($_GET['bar_code']) ? $_GET['bar_code'] : ''?>" tabindex="1"/><br/>
            </div>
            <div class="input-row" style="padding-right: 15px;">
              <!-- Search string -->
              <label for="no_bar_code">Без штрих-кода:</label>
              <input type="hidden" name="no_bar_code" value="0">
              <input id="no_bar_code" type="checkbox" name="no_bar_code" value="1" style="width: 25px;" <?=isset($_GET['no_bar_code']) && $_GET['no_bar_code'] == 1 ? 'checked="checked"' : '';?>><br/>
            </div>
            <div class="input-row" style="padding-right: 15px;">
              <!-- Search string -->
              <label for="bar_code_duplicate">Дубликаты штрих-кода:</label>
              <input type="hidden" name="bar_code_duplicate" value="0">
              <input id="bar_code_duplicate" type="checkbox" name="bar_code_duplicate" value="1" style="width: 25px;" <?=isset($_GET['bar_code_duplicate']) && $_GET['bar_code_duplicate'] == 1 ? 'checked="checked"' : '';?>><br/>
            </div>
            <div class="input-row button-box">
              <!-- Submit -->
              <button class="button" type="submit"><?=lang('admin.search.search_action');?></button><br/>
            </div>
	          <div class="input-row" style="padding-top: 13px;">
		          <!-- Standard store qty proccess -->
		          <a href="<?= admin_site_url('storeinventory/standard_store_qty_order_proccess' . get_get_params());?> " style="padding-left: 15px;">Заказ на нормативный склад</a>
	          </div>
          </div>

        </form>
      </div>
      <div class="clear"></div>
    </div>

    <?=$this->load->view('includes/admin/parts/before_entity_list')?>


    <table id="inventory" class="table">
      <thead>
        <tr>
          <th>Товар</th>
          <th>Нет в наличии</th>
          <th>Штрих-код</th>
          <th>Артикул</th>
          <th>Всего</th>
          <th>Stock MC</th>
          <th>Резерв MC</th>
          <th>В пути MC</th>
          <th>Zammler</th>
          <th>Резерв</th>
          <th>В пути</th>
          <th>Нормативный склад</th>
          <th>
              <select id="js-choose-store">
                  <option value="all">Склады поставщиков</option>
                <? foreach ($stores as $id => $store) : ?>
                  <? if ((!empty($activeStores) && !in_array($id, $activeStores)) || ($id == ZAMMLER_STORE_ID || $id == MC_STORE_ID)) continue; ?>
                  <option value="<?=$id?>"><?=$store?></option>
                <? endforeach; ?>
              </select>
              <button type="button" id="js-update-inventory" class="button" style="float: right;">Обновить остатки</button>
          </th>
          <? foreach ($stores as $id => $store) : ?>
            <? if ((!empty($activeStores) && !in_array($id, $activeStores)) || ($id == ZAMMLER_STORE_ID || $id == MC_STORE_ID)) continue; ?>
            <th class="detail-store" style="display: none;"><?=$store?></th>
          <? endforeach; ?>
          <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($list as $item) : ?>
            <tr>
              <td><a target="_blank" href="<?=site_url('madmin/product/add_edit/' . $item['product_id']);?>"><?=$item['name']?></a> <span style="float: right"><a target="_blank" href="<?=$item['page_url']?>">В магазине</a></span> </td>
              <td style="width: 85px;"><input class="js-change-not-in-stock" data-product-id="<?=$item['product_id']?>" data-product-group-id="<?=$item['product_group_id']?>" type="checkbox" <?=$item['not_in_stock'] ? 'checked="checked"' : '' ?> disabled="disabled" /></td>
              <td class="js-bar_code"><?= !empty($item['bar_code']) ? $item['bar_code'] : '-----' ?></td>
              <td><?=$item['product_code']?></td>
              <td>
                <?
                  $reserved = 0;
                  $mcReserved = isset($item['reserve'][MC_STORE_ID]) ? $item['reserve'][MC_STORE_ID]['total'] : 0;
                  $mcOnWay = isset($item['on_way'][MC_STORE_ID]) ? $item['on_way'][MC_STORE_ID] : 0;
                  $zammlerReserved = isset($item['reserve'][ZAMMLER_STORE_ID]) ? $item['reserve'][ZAMMLER_STORE_ID]['total'] : 0;
                  $zammlerOnWay = isset($item['on_way'][ZAMMLER_STORE_ID]) ? $item['on_way'][ZAMMLER_STORE_ID] : 0;
                ?>
                <?=isset($item['inventory']['total']) ? $item['inventory']['total'] - $mcReserved - $zammlerReserved : 0?>
              </td>
              <td>
                <? $mcCount = isset($item['inventory'][MC_STORE_ID]) ? $item['inventory'][MC_STORE_ID]['qty'] : 0?>
                <?=empty($mcCount) ? 0 : $mcCount?>
              </td>
              <td>
                <a data-ajaxp-url="<?=site_url(ADMIN_BASE_ROUTE.'/storeinventory/reserves/' . MC_STORE_ID . '/' . $item['product_id'] . '/' . $item['product_group_id']);?>"><?=$mcReserved?></a>
              </td>
              <td>
                <?=$mcOnWay?>
              </td>
              <td>
                <? $zammlerCount = isset($item['inventory'][ZAMMLER_STORE_ID]) ? $item['inventory'][ZAMMLER_STORE_ID]['qty'] : 0?>
                <?=empty($zammlerCount) ? 0 : $zammlerCount?>
              </td>
              <td>
                <a data-ajaxp-url="<?=site_url(ADMIN_BASE_ROUTE.'/storeinventory/reserves/' . ZAMMLER_STORE_ID . '/' . $item['product_id'] . '/' . $item['product_group_id']);?>"><?=$zammlerReserved?></a>
              </td>
              <td><?=$zammlerOnWay?></td>
		          <td class="js-standard_store_qty_column">
				          <input class="js-standard_store_qty_value" type="text" pattern="^[ 0-9]+$" value="<?= !empty($item['standard_store_qty']) ? $item['standard_store_qty'] : 0;?>">
				          <div class="js-standard_store_qty_wrapper"></div>
		          </td>
              <td class="detail-store store-all">
                <?=$test = isset($item['inventory']['total']) ? $item['inventory']['total'] - $zammlerCount - $mcCount : 0?>
              </td>

              <? $codeIdStoreMap = array_flip($idCodeStoreMap) ?>
              <? foreach ($stores as $id => $store) : ?>
                <? if ((!empty($activeStores) && !in_array($id, $activeStores)) || ($id == ZAMMLER_STORE_ID || $id == MC_STORE_ID)) continue; ?>
                <td class="detail-store store-<?=$id?>"  style="display: none;">
                  <a class="editable"><?=isset($item['inventory'][$id]) ? $item['inventory'][$id]['qty'] : 0?></a>
                  <input data-product-id="<?=$item['product_id']?>"  data-bar-code="<?=$item['bar_code']?>" style="width: 200px; display: none;" class="edit-input" type="text" value="<?=isset($item['inventory'][$id]) ? $item['inventory'][$id]['qty'] : 0?>">
                  <img style="float: right; width: 15px; height: 15px;" src="<?=site_img('menu_2.png')?>"
                   <? if (isset($item['inventory'][$id])) : ?>
                       title="
                       <div class='search-box'>
                        Обновлено: <?=$item['inventory'][$id]['updated_at'] ?></br>
                        Источник: <?= !empty($item['inventory'][$id]['update_source']) ? lang('admin.enum.storeinventory.update_source.' . $item['inventory'][$id]['update_source']) : '' ?></br>
                        Админ: <?=!empty($item['inventory'][$id]['update_by_admin_id']) ? $admins[$item['inventory'][$id]['update_by_admin_id']] : ''?></br>
                        <? if ($item['inventory'][$id]['update_source'] == 'file' && !empty($item['inventory'][$id]['file'])) : ?>
                          Файл: <a href='<?=site_file_url(unserialize($item['inventory'][$id]['file']))?>'>скачать</a>
                        <? endif; ?>
                       </div>"
                    <? endif; ?>
                  />
                </td>
                <td class="detail-store store-<?=$id?>" style="display: none;">
                  <? if (isset($codeIdStoreMap[$id])) : ?>
                    <a data-ajaxp-url="<?=admin_site_url('/storeinventory/ajax_web_parser_setting?product_id=' . $item['product_id'] . '&&product_group_id=' . $item['product_group_id'] . '&&store_id=' . $id);?>">Настройки парсера</a>
                    <? $setting = NULL; ?>
                    <? if (isset($inventoriesParserSetting[$id][$item['product_id']][$item['product_group_id']])) : ?>
                      <? $setting = $inventoriesParserSetting[$id][$item['product_id']][$item['product_group_id']]; ?>
                    <? endif; ?>
                    <? if (isset($inventoriesParserSetting[$id][$item['product_id']][NULL])) : ?>
                      <? $setting = $inventoriesParserSetting[$id][$item['product_id']][NULL]; ?>
                    <? endif; ?>
                    <? if (empty($setting) || empty($setting['url'])): ?>
                      <img style="float: right; width: 15px; height: 15px;" src="<?=site_img('answ_icon.png')?>"/>
                    <? elseif (in_array($setting['last_http_code'], array(NULL, 200))): ?>
                      <img style="float: right; width: 15px; height: 15px;" src="<?=site_img('checkbox_icon.png')?>"/>
                    <? else : ?>
                      <img style="float: right; width: 15px; height: 15px;" src="<?=site_img('attention_icon_small.png')?>"/>
                    <? endif; ?>
                    <? if (isset($inventoriesParserSetting[$id][$item['product_id']][$item['product_group_id']]['url'])) : ?>
		                  <? $url =$inventoriesParserSetting[$id][$item['product_id']][$item['product_group_id']]['url']; ?>
                      <a target="_blank" href="<?=$url;?>">Перейти на сайт поставщика</a>
	                  <? endif; ?>
                  <? endif; ?>
                </td>
              <? endforeach; ?>
            </tr>
          <? endforeach; ?>
        </tbody>
    </table>
  </div>

  <? if(isset($pager)):?>
    <?=$this->load->view('includes/admin/parts/pager', array('pager' => $pager), true);?>
  <? endif; ?>
</div>
<div class="clear"></div>

<style>

.js-standard_store_qty_column {
	width: 200px;
}

.js-standard_store_qty_value {
	width: 40px;
	text-align: center;
	float: left;
}

.js-standard_store_qty_wrapper {
	float: left;
	padding: 0 3px 0 3px;
}

.js-standard_store_qty_wrapper span{
	margin: 3px 5px 5px 5px;
	display: block;
	color: limegreen;

}
</style>

<script>
  $(document).ready(function() {

  // Admin storeinventory, update products on loaded file. Show views on choosen operation type.
  // For example: Operation type "standard_store" -> load view "standard_store_view.php" and call xadmin_storeinventory/process_file_standard_store();
  $('#js-standard-store').change(function () {
    var defaultForm = $('#js-default-form');
    var standardStoreForm = $('#js-custom-standard-store-view');
    var pricesForm = $('#js-custom-inventory-prices-view');
    var selectedOption = $(this).val();
    var stadndardStoreOption = $('#js-standard-store option[value="standard_store"]').val();
    var pricesOption = $('#js-standard-store option[value="inventory_prices"]').val();
    pricesForm.hide();
    standardStoreForm.hide();
    defaultForm.hide();

    if (selectedOption === stadndardStoreOption) {
      standardStoreForm.show();
    } else if (selectedOption === pricesOption) {
      pricesForm.show();
    } else {
      defaultForm.show();
    }
  });
  // End of admin store inventory

    $("img[title]").tooltip({ position: "center left"});

    $('#js-update-inventory').hide();

    $('#store').change(function () {
      var storeId = $(this).val();
      $('.js-store-file-config').hide();

      if ($('#store-' + storeId).length > 0) {
        $('#store-' + storeId).show();
        $('#store-' + storeId + ' > .chzn-container').width(200);
      } else {
        $('#store-default').show();
      }
    });

    $('.js-select-config-file-name').change(function () {
      var fileName = $(this).val();
      $('.js-file-config-info').hide();

      $('#js-config-info-' + fileName).show();
    });

    $('#js-choose-store').change(function () {
      var val = $(this).val();
      if (val !== 'all') {
        $('#js-update-inventory').show();
      } else {
        $('#js-update-inventory').hide();
      }
      $('.detail-store').hide();
      $('.store-' + val).show();
    });

    $('.editable').click(function () {
      $(this).next('input').show();
      $(this).hide();
    });

    $('#js-update-inventory').click(function() {
      var $inputs = $('#inventory').find('input.edit-input:visible');
      var storeId = $('#js-choose-store').val();
      var request = {
          store_id : storeId,
          data : []
      };

      $inputs.each(function(key, value) {
        var productId = $(value).data('product-id');
        var barCode = $(value).data('bar-code');

        var item = {
          product_id : productId,
          bar_code : barCode,
          qty : $(value).val()
        };
        request.data.push(item);
      });

      $.post('<?=pager_remove_from_str(current_url()) . '/ajax_update_inventory'?>', request)
      .done(function( data ) {
        if (data === 'OK') {
          location.reload();
        } else {
          alert('Произошла ошибка!');
        }
      });
    });

    $('.js-change-not-in-stock').change(function() {
      var productId = $(this).data('product-id');
      var productGroupId = $(this).data('product-group-id');
      var checked = 0;
      var that = $(this);
      if (that.attr("checked")) {
        checked = 1;
      }
      that.attr('disabled', 'disabled');

      var item = {
        product_id : productId,
        product_group_id : productGroupId,
        value : checked
      };

      $.get('<?=pager_remove_from_str(current_url()) . '/ajax_update_not_in_stock'?>', item)
        .done(function( data ) {
          if (data === 'OK') {
            that.removeAttr('disabled');
          } else {
            alert('Произошла ошибка!');
          }
        });
    });
  })
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var options, a;
        jQuery(function() {
            var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
            options = {serviceUrl: '<?=site_url($adminBaseRoute . '/product/search_autocomplete');?>',
                fnFormatResult: function(value, data, currentValue) {
                    var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
                    return value.replace(new RegExp(pattern, 'gi'), '<span class="red-color">$1<\/span>') + '<br/>' + '<span class="fade-text"></span>' + '<span class="details">' + data.replace(new RegExp(pattern, 'gi'), '<span class="red-color">$1<\/span>') + '</span>';
                },
                preloader: $('#search_loader')};
            a = $('input[name=q]').ajaxautocomplete(options);
        });
    });
</script>
