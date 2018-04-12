<link type="text/css" rel="stylesheet" media="screen" href="<?=site_css('autocomplet.css');?>"/>
<style>
  .js-preloader-input {
    background-image: url(<?=site_img('preloader.gif')?>);
    background-repeat: no-repeat;
    background-size: 50%;
    background-position: right;
  }
</style>

<? if (!empty($cartItems) && !empty($cart['total'])): ?>
  <div class="js-service js-product-id-<?=implode('-', get_array_vals_by_second_key($cartItems, 'product_id'));?> js-product-price-<?=$cart['total'];?>" style="display: none;"></div>
  <div class="js-service-name js-product-name-<?=implode('-', get_array_vals_by_second_key($cartItems, 'product', 'name'));?>" style="display: none;"></div>
  <div class="js-service-category js-product-category-<?=implode('-', get_array_vals_by_third_key($cartItems, 'product', 'category', 'name'));?>" style="display: none;"></div>
<? endif; ?>

<div class="checkout-box">

  <div class="part fr">
    <div class="head-row">
      <h2 class="main-title">Ваш заказ</h2>
    </div>

    <div class="pr order-data-wrap">
      <img class="preloader js-preloader" style="display: none;" src="<?=site_img('preloader.gif');?>"/>
      <table class="order-data js-order-data" cellspacing="0" cellpadding="0" border="0">
        <? $this->view("includes/shop/parts/order_data"); ?>
      </table>
    </div>

    <div class="kick-it"></div>

    <div class="add-new-info">
      <table cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="td-1 html-content">
            <p style="margin: 0px; color: #fff; font-size: 14px; line-height: 18px;"><?=$settings['checkout_contact_block_text'];?></p>
          </td>
          <td class="td-2 html-content">
            <p style="margin: 0px; text-align: right; font-weight: bold; font-size: 18px; color: #fff; line-height: 18px;"><?=$settings['checkout_contact_block_telephone'];?></p>
            <p style="margin: 0px; text-align: right; color: #fff; font-size: 14px; line-height: 18px;">
              <?=$settings['checkout_contact_block_working_time'];?>
            </p>
          </td>
        </tr>
      </table>
    </div>

    <div class="kick-it"></div>

    <div class="warranty-big-box">

      <h2 class="title"><?=$settings['right_part_delivery_warranty_payment_title'];?>:</h2>

      <div class="row">
        <table class="title-table-2" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-1"><h3><?=$settings['right_part_delivery_title'];?></h3></td>
          </tr>
        </table>
        <div class="html-content">
          <?=$settings['right_part_delivery_text_briefly'];?>
        </div>
        <table class="title-table-2" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-2 tar"><span class="a-like" data-ajaxp-url="<?=shop_url('аджакс/доставка');?>">подробнее</span></td>
          </tr>
        </table>
      </div>

      <div class="row border-top">
        <table class="title-table-2" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-1"><h3><?=$settings['right_part_warranty_title'];?></h3></td>
          </tr>
        </table>
        <div class="html-content">
          <?=$settings['right_part_warranty_text_briefly'];?>
        </div>
        <table class="title-table-2" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-2 tar"><span class="a-like" data-ajaxp-url="<?=shop_url('аджакс/гарантия');?>">подробнее</span></td>
          </tr>
        </table>
      </div>

      <div class="row border-top">
        <table class="title-table-2" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-1"><h3><?=$settings['right_part_payment_title'];?></h3></td>
          </tr>
        </table>
        <table class="combo" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td>
              <div class="html-content">
                <?=$settings['right_part_payment_text_briefly'];?>
              </div>
            </td>
            <td>
              <div class="tar"><img src="<?=site_img('banks_icon.png')?>" alt="Visa MasterCard" title="Visa MasterCard" /></div>
            </td>
          </tr>
        </table>
        <table class="title-table-2" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-2 tar"><span class="a-like" data-ajaxp-url="<?=shop_url('аджакс/оплата');?>">подробнее</span></td>
          </tr>
        </table>
      </div>
    </div>


  </div>



  <div class="part fl">
    <div class="head-row">
      <h2 class="main-title">Оформление заказа</h2>
      <p class="attention">Обязательные поля отмечены звездочкой *</p>
    </div>

    <div class="order-box">

      <form action="<?=shop_url('процесс-оформления')?>" method="post" class="js-validate validate" id="order_form">
        <div class="box box-1">
          <h3>Контактная информация</h3>

          <div class="input-row row-4">
            <label for="first-name-input">Имя<span class="arr">*</span>:</label>
            <input type="text" name="first_name"  value="<?=isset($pastSiteOrder['first_name']) && !empty($pastSiteOrder['first_name']) ? $pastSiteOrder['first_name'] : '';?>" class="required text" id="first-name-input"/>
            <p class="example">Для оформления доставки</p>
          </div>

          <div class="input-row row-5">
            <label for="last-name-input">Фамилия<span class="arr">*</span>:</label>
            <input type="text" name="last_name"  value="<?=isset($pastSiteOrder['last_name']) && !empty($pastSiteOrder['last_name']) ? $pastSiteOrder['last_name'] : '';?>" class="required text" id="last-name-input"/>
            <p class="example">&nbsp;</p>
          </div>

          <div class="clear"></div>

          <div class="input-row row-4">
            <label for="phone-input">Телефон<span class="arr">*</span>:</label>
            <input type="tel" id="phone-input" name="phone" value="<?=is_not_empty($pastSiteOrder['phone']) ? substr($pastSiteOrder['phone'], 2) : '';?>" class="required text"/>
            <p class="example">Для согласования сроков доставки</p>
          </div>

          <?
            $email = '';
            if (isset($pastSiteOrder['email']) && !empty($pastSiteOrder['email'])) {
              $email = $pastSiteOrder['email'];
            } elseif ($isLoggedIn && !empty($authEntity['auth_info']['email'])) {
              $email = $authEntity['auth_info']['email'];
            }
          ?>

          <div class="input-row row-5">
            <label for="email-input">Электронная почта<span class="arr">*</span>:</label>
            <input id="email-input" class="required text email" type="email" name="email" value="<?=$email;?>" />
            <p class="example">На эту почту мы вышлем Вам подтверждение заказа</p>
          </div>

          <div class="clear"></div>

          <div class="input-row">
            <div class="pr">
              <label>Способ доставки<span class="arr">*</span>:</label>
              <span class="a-like abs-right" data-ajaxp-url="<?=shop_url('аджакс/доставка');?>">подробнее</span>
            </div>

            <?
              if (isset($pastSiteOrder['delivery_type']) && !empty($pastSiteOrder['delivery_type'])) {
                $deliveryType = $pastSiteOrder['delivery_type'];
              }
            ?>

            <select class="js-delivery-select required" name="delivery_type">
              <option id="default-value" value="">-Пожалуйста, выберите-</option>
              <option class="js-delivery-to-home" <?=isset($deliveryType) && $deliveryType == 'delivery-to-home' ? 'selected ' : '';?>value="delivery-to-home">На дом</option>
              <option class="js-delivery-to-post" <?=isset($deliveryType) && $deliveryType == 'delivery-to-post' ? 'selected ' : '';?>value="delivery-to-post">На ближайший склад Новой Почты</option>
            </select>
          </div>
        </div>

        <div class="box js-input-box">
          <h3 class="js-delivery-to-home"<?=isset($deliveryType) && $deliveryType == 'delivery-to-home' ? '' : ' style="display: none;"';?>>Адрес доставки</h3>
          <h3 class="js-delivery-to-post"<?=isset($deliveryType) && $deliveryType == 'delivery-to-post' ? '' : ' style="display: none;"';?>>Склад Новой Почты</h3>

          <div class="input-row js-city">
            <label for="city-input">Город (українською мовою)<span class="arr">*</span>:</label>
            <select id="city-input" name="city" class="js-chosen required">
              <option value="">-Пожалуйста, выберите-</option>
              <? foreach ($cityOptions as $k => $v): ?>
                <?
                  $isSelected = FALSE;
                  if (isset($pastSiteOrder) && !empty($pastSiteOrder)) {
                    if (isset($pastSiteOrder['delivery_city_id']) && $pastSiteOrder['delivery_city_id'] == $k) {
                      $isSelected = TRUE;
                    }
                  }
                ?>
                <option <?=$isSelected ? 'selected="selected"' : ''?> value="<?=$k?>"><?=htmlspecialchars($v);?></option>
              <? endforeach; ?>
            </select>
            <?/*
            <input id="city-input" name="city" value="<?=isset($pastSiteOrder['delivery_city']) && !empty($pastSiteOrder['delivery_city']) ? $pastSiteOrder['delivery_city'] : '';?>" class="required text select2" dataUrl = "<?=shop_url('get_select2_np_cities_ajax');?>" defVal="-- Не выбрано --" />
            */?>
          </div>

          <div class="input-row row-1 js-delivery-to-home"<?=isset($deliveryType) && $deliveryType == 'delivery-to-home' ? '' : ' style="display: none;"';?>>
            <label for="street-input">Улица (українською)<span class="arr">*</span>:</label>
            <input id="street-input" name="street" value="<?=isset($pastSiteOrder['delivery_street']) && !empty($pastSiteOrder['delivery_street']) ? $pastSiteOrder['delivery_street'] : '';?>" class="<?=isset($deliveryType) && $deliveryType == 'delivery-to-post' ? 'ignored' : 'required';?> text js-choose-city"/>
            <input type="hidden" id="street-input-ref" name="street_ref" value="<?=isset($pastSiteOrder['delivery_street_ref']) && !empty($pastSiteOrder['delivery_street_ref']) ? $pastSiteOrder['delivery_street_ref'] : '';?>" />
            <input type="hidden" id="street-input-type" name="street_type" value="<?=isset($pastSiteOrder['delivery_street_type']) && !empty($pastSiteOrder['delivery_street_type']) ? $pastSiteOrder['delivery_street_type'] : '';?>" />

          </div>

          <div class="input-row row-2 js-delivery-to-home js-choose-city"<?=isset($deliveryType) && $deliveryType == 'delivery-to-home' ? '' : ' style="display: none;"';?>>
            <label for="house-input">Дом<span class="arr">*</span>:</label>
            <input id="house-input" name="house" value="<?=isset($pastSiteOrder['delivery_house']) && !empty($pastSiteOrder['delivery_house']) ? $pastSiteOrder['delivery_house'] : '';?>" class="<?=isset($deliveryType) && $deliveryType == 'delivery-to-post' ? 'ignored' : 'required';?> text js-choose-city"/>
          </div>

          <div class="input-row row-3 js-delivery-to-home js-choose-city"<?=isset($deliveryType) && $deliveryType == 'delivery-to-home' ? '' : ' style="display: none;"';?>>
            <label for="house-input">Квартира:</label>
            <input id="flat-input" name="flat" value="<?=isset($pastSiteOrder['delivery_flat']) && !empty($pastSiteOrder['delivery_flat']) ? $pastSiteOrder['delivery_flat'] : '';?>" class="<?=isset($deliveryType) && $deliveryType == 'delivery-to-post' ? 'ignored' : '';?> text js-choose-city"/>
          </div>

          <div class="input-row js-delivery-to-post"<?=isset($deliveryType) && $deliveryType == 'delivery-to-post' ? '' : ' style="display: none;"';?>>
            <style>
              .chzn-container {width: 100% !important;}
            </style>
            <div class="pr">
              <label for="post-input">Номер склада Новой Почты<br/> (українською мовою)<span class="arr">*</span>:</label>
              <a class="abs-right" target="_blank" href="http://novaposhta.ua/map/index/ua">карта отделений</a>
            </div>
            <select id="post-input" name="post" class="js-choose-city js-chosen<?=isset($pastSiteOrder['delivery_post']) && !empty($pastSiteOrder['delivery_post']) ? '' : ' ignored';?> required" style="width: 100%;">
              <? if (!empty($warehouses)) : ?>
                <? foreach ($warehouses as $k => $v) : ?>
                  <option <?=isset($pastSiteOrder['delivery_warehouse_id']) && $pastSiteOrder['delivery_warehouse_id'] == $k ? 'selected="selected"' : '';?> value="<?=$k?>"><?=$v?></option>
                <? endforeach; ?>
              <? else : ?>
                <option value="">-Пожалуйста, выберите город -</option>
              <? endif; ?>
            </select>
            <img id="js-preload-posts" src="<?=site_img('preloader.gif')?>" style="display: none" />
            <?/*
            <input id="post-input" name="post" value="<?=isset($pastSiteOrder['delivery_post']) && !empty($pastSiteOrder['delivery_post']) ? $pastSiteOrder['delivery_post'] : '';?>" class="required text ignored"/>
            */?>
          </div>

          <div class="clear"></div>
        </div>

        <div class="box">
          <div class="input-row">
            <div class="pr">
              <label>Способ оплаты<span class="arr">*</span>:</label>
              <span class="a-like abs-right" data-ajaxp-url="<?=shop_url('аджакс/оплата');?>">подробнее</span>
            </div>
            <select name="payment_type" class="required">
              <option value="cash">Наличными при доставке</option>
              <option value="privatbank">Банковской картой</option>
              <? /* <option value="online">Онлайн платежи</option> */ ?>
            </select>
          </div>
        </div>

        <div class="input-row">
          <label for="comment-input">Коментарий к заказу:</label>
          <textarea name="comment"></textarea>
        </div>

        <table class="last-table" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-1">
              Заполняя контактную информацию,<br/>
              Вы соглашаетесь с <a href="<?=site_url('пользовательское-соглашение')?>" target="_blank">пользовательским соглашением</a>
            </td>
            <td class="td-2">
              <img id="js-submit-preloader" src="<?=site_img('preloader.gif')?>" style="width: 80px; padding-right: 10px;display: none;" />
              <button class="h-but orange-but ttu js-submit-button" type="submit">Заказать</button>
            </td>
          </tr>
        </table>

      </form>
    </div>
  </div>



  <div class="clear"></div>

</div>

<script src="<?=site_js('jquery/jquery.maskedinput.min.js');?>" type="text/javascript"></script>
<script src="<?=site_js('admin/jquery/chosen.min.js');?>" type="text/javascript"></script>
<?/*<script src="<?=site_js('admin/jquery/select2.min.js');?>" type="text/javascript"></script>*/?>
<script type="text/javascript">

  <?/*
  function init_select2() {
    $('input.select2:visible').not('.select2-offscreen').each(function() {
      var that = this;
      $(this).select2({
        placeholder: $(that).attr('defVal'),
        minimumInputLength: 1,
        ajax: {
          url: $(this).attr('dataUrl'),
          dataType: 'json',
          quietMillis: 100,
          data: function (term, page) {
            return {
              q: term
            };
          },
          results: function (data, page) {
            return {results: data};
          }
        },
        initSelection: function(element, callback) {
          callback({id: $(that).val(), text: $(that).attr('defVal')});
        }
      });
    });
  }
  */?>

  $(document).ready(function() {
    $('.js-chosen').chosen();

    <? if(isset($pastSiteOrder['delivery_warehouse_id']) && !empty($pastSiteOrder['delivery_warehouse_id'])) : ?>
      var preSelectedPost = '<?=$pastSiteOrder['delivery_warehouse_id'];?>';
    <? else: ?>
      var preSelectedPost = '';
    <? endif; ?>

    var streetAutoCompleteOptions = {
      serviceUrl: shop_base_url + 'get_address_ajax',
      params: {city_id: ''},
      minChars: 3,
      deferRequestBy: 1000,
      width: 450,
      showNoSuggestionNotice: true,
      noSuggestionNotice: 'Не найдено',
      onSelect: function (suggestion) {
        $("#street-input-ref").val(suggestion.data.ref);
        $("#street-input-type").val(suggestion.data.streets_type);
      },
      onInvalidateSelection: function () {
        $("#street-input-ref").val('');
        $("#street-input-type").val('');
      },
      onSearchStart: function (query) {
        $("#street-input").addClass('js-preloader-input');
      },
      onSearchComplete: function (query, suggestions) {
        $("#street-input").removeClass('js-preloader-input');
      },
      onSearchError: function (query, jqXHR, textStatus, errorThrown) {
        $("#street-input").removeClass('js-preloader-input');
      }
    };

    $("#street-input").autocomplete(streetAutoCompleteOptions);

    if ($('select[name="city"]').val() == '') {
      $('.js-choose-city').attr('readonly', true);
    }

    $('select[name="city"]').change(function() {
      streetAutoCompleteOptions.params.city_id = $(this).val();
      $('.js-choose-city').attr('readonly', false);

      var delivery = $('.js-delivery-select').val();
      if (delivery != 'delivery-to-post') {
        return;
      }
      if ($(this).val() == '') {
        return;
      }
      $('#js-preload-posts').show();

      $.get(shop_base_url + 'get_warehouse_numbers_ajax', {city_id: $(this).val()}, function( data ) {
        if (data != null && data !== undefined) {
          var optionsHtml = '<option value="">-Пожалуйста, выберите-</option>';
          $.each(data, function(index, value) {
            var selected = '';
            if (preSelectedPost != '' && preSelectedPost == index) {
              $('select[name="post"] option[value="' + preSelectedPost + '"]').prop('selected', true);
              selected = 'selected="selected"';
            }

            optionsHtml += '<option ' + selected + ' value="' + index + '">' + value + '</option>';
          });
          $('select[name="post"]').html(optionsHtml);
          $('select[name="post"]').trigger("liszt:updated");
        }
        $('#js-preload-posts').hide();
      }, "json");
    });

    <? if (isset($pastSiteOrder['delivery_city']) && !empty($pastSiteOrder['delivery_city'])) : ?>
      $('select[name="city"]').trigger('change');
    <? endif; ?>
    <?/* init_select2(); */?>

    var paramGroupData = {};
    <? foreach($cartItems as $cartItem): ?>
      <? if(is_not_empty($cartItem['product']['parameter_groups'])): ?>
        <? foreach ($cartItem['product']['parameter_groups'] as $g): ?>
          <?
            if(empty($g['price'])) {
              $g['price'] = $cartItem['product']['price'];
            }
            $g['values_out'] = '';
            if(!empty($g['secondary_parameter_values_out'])) {
              $g['values_out'] = implode(',', get_array_vals_by_second_key($g['secondary_parameter_values_out'], 'id'));
            }
//            if($cartItem['product']['on_order'] == TRUE) {
//              $g['on_order'] = '1';
//            }
          ?>
          paramGroupData['<?=$g['id'] . '_' . $g['main_parameter_value_id'];?>'] = {};
          paramGroupData['<?=$g['id'] . '_' . $g['main_parameter_value_id'];?>']['price'] = '<?=$g['price'];?>';
//          paramGroupData['<?//=$g['id'] . '_' . $g['main_parameter_value_id'];?>//']['on_order'] = '<?//=$g['on_order'];?>//';
          paramGroupData['<?=$g['id'] . '_' . $g['main_parameter_value_id'];?>']['values_out'] = '<?=$g['values_out'];?>';
        <? endforeach;?>
      <? endif; ?>
    <? endforeach;?>

    $('#phone-input').mask("+38(999) 999-99-99");

    $('.js-fancy-img').fancybox();

    $(document).ajaxStop(function() {
      $('.js-fancy-img').fancybox();
    });

    $('.js-no-pic-link').live('click', function() {
      return false;
    });

    $('.js-validate').validate({
      ignore: '.ignored',
      rules: {
        phone: {
          required: true
        }
      }
    });

    var ordersDataTable = $('.js-order-data');

    $('.js-qty').live('change', function() {
      var input = $(this);
      var pr = $(this).closest('.js-tr');

      var ajaxUrl = '<?=shop_url('аджакс/пересчитать');?>';

      ordersDataTable.css('opacity', '0.5');
      $('.js-preloader').show();
      $.get(ajaxUrl + "?productId=" + pr.find('.js-name').attr('id') + "&qty=" + input.val(), function(data) {
        ordersDataTable.html(data);
        ordersDataTable.css('opacity', '1');
        $('.js-preloader').hide();
        return false;
      });
    });

    $('.js-select-param1, .js-select-param2').live('change', function() {
      var paramBox = $(this).closest('.js-param-box');

      if ($(this).find('option:selected').val() != '') {
        $(this).find('option[value=""]').remove();
      }
      if ($(this).hasClass('error')) {
        paramBox.find('.error').each(function() {
          $(this).removeClass('error');
        });
      }
      if (paramBox.find('.js-attention:visible').length > 0) {
        paramBox.find('.js-attention:visible').each(function() {
          $(this).hide();
        });
      }

      var ajaxUrl = '<?=shop_url('аджакс/сохранить-параметры');?>';

      var paramData = new Array();
      var param1value = paramBox.find('.js-select-param1').find('option:selected').val();
      paramData.push(param1value);

      var nearestSelect2 = paramBox.find('.js-select-param2');
      if (nearestSelect2.length > 0) {
        var param2value = nearestSelect2.find('option:selected').val();
        paramData.push(param2value);

        // Process secondary params "on_order" labels
        if($(this).hasClass('js-select-param1')) {
          var paramGroupDataKey = $(this).find('option:selected').attr('class') + '_' + param1value;
          var valuesOutArr = paramGroupData[paramGroupDataKey]['values_out'].split(',');
          nearestSelect2.find('option').each(function() {
            $(this).removeAttr('style');
            var optionText = $(this).text().replace(' (под заказ)', '');
            if(paramGroupData[paramGroupDataKey]['not_in_stock'] == true || valuesOutArr.indexOf($(this).val()) != '-1') {
              if($(this).val() != '') {
                $(this).css('color', 'red');
                optionText = optionText + ' (под заказ)';
              }
            }
            $(this).text(optionText);
          });
        }

      }

      ordersDataTable.css('opacity', '0.5');
      $('.js-preloader').show();
      $.post(ajaxUrl, {cart_item_id: paramBox.attr('id'), data: paramData}, function( data ) {
        if (data != null && data !== undefined) {
          var parentTr = paramBox.parents('.js-tr');
          parentTr.find('.js-real-price').text(data.price);
          parentTr.find('.js-old-price').text(data.old_price);
          parentTr.find('.js-item-total').text(data.item_total);
          $('.js-cart-total').text(data.cart_total);
        }
        ordersDataTable.css('opacity', '1');
        $('.js-preloader').hide();
      }, "json");

      var paramImg = paramBox.closest('.js-tr').find("a[param-img='" + param1value +"']");
      if (paramImg.length > 0) {
        paramBox.closest('.js-tr').find('.js-images a:visible').hide();
        paramImg.show();
      } else {
        paramBox.closest('.js-tr').find('.js-images a:visible').hide();
        paramBox.closest('.js-tr').find('.js-main-img').show();
      }
    });

    $('.js-qty').live('keyup', function() {
      $(this).closest('.js-td').find('.js-refresh').show();
    });

    $('.js-delivery-select').change(function() {
      var selectedOption = $(this).find('option:selected');
      var inputBox = $('.js-input-box');

      // Show all
      inputBox.find('input, select').each(function() {
        $(this).closest('div').show();
        $(this).removeClass('ignored');
      });

      // Show all h3
      inputBox.find('h3').each(function() {
        $(this).show();
      });

      // Hide unnecessary
      if (selectedOption.val() == '' || selectedOption.val() == 'delivery-to-home') {
        $('select[name="city"]').trigger('change');
        inputBox.find('.js-delivery-to-post').each(function() {
          $(this).hide();
          $(this).find('input, select').addClass('ignored');
        });
        return;
      } else if (selectedOption.val() == 'delivery-to-post') {
        $('select[name="city"]').trigger('change');
    	  inputBox.find('.js-delivery-to-home').each(function() {
          $(this).hide();
          $(this).find('input, select').addClass('ignored');
        });
      }
    });

    $('.js-submit-button').click(function() {
      var defaultOptions = $('option[data="default"]');
      if (defaultOptions.length > 0) {
        defaultOptions.each(function() {
          $(this).closest('select').addClass('error');
          $(this).closest('.js-valid-row').find('.js-attention').show();
        });
        goToObject($(defaultOptions[0]).closest('.js-tr'));
        return false;
      }
    });

    $(document).on('submit','#order_form',function() {
      $('.js-submit-button').prop('disabled', true);
      $('#js-submit-preloader').show();
    });

  });
</script>