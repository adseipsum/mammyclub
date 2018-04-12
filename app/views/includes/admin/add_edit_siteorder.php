<script type="text/javascript" src="<?=site_js("jquery/plugins/jquery.autocomplete.min.js")?>"></script>

<link type="text/css" rel="stylesheet" media="screen" href="<?=site_css('autocomplet.css');?>"/>
<style>
  .js-preloader-input {
    background-image: url(<?=site_img('preloader.gif')?>);
    background-repeat: no-repeat;
    background-size: 50%;
    background-position: right;
  }
</style>

<?
  $statuses = array();
  $statusesCheck = array(SITEORDER_STATUS_DELIVERED, SITEORDER_STATUS_SHIPPED, SITEORDER_STATUS_WAIT, 'client-confirmed', 'payment_pending');
  foreach ($statusesCheck as $sc) {
    foreach ($siteOrderStatuses as $sos) {
      if (trim($sos['k']) == $sc) {
        $statuses[$sc] = $sos['id'];
        break;
      }
    }
  }
?>
<? if(isset($entity['id']) && !empty($statuses)) :?>
  <script type="text/javascript">
    $(document).ready(function() {
      var orderForm = $('.js-siteorder-form');
      orderForm.validate({
        errorElement: 'span',
        submitHandler: function(form) {
          // Check for status cahnge via ajax
          var statusSelectVal = $('#siteorder_siteorder_status').val();
          var statusDatabaseVal = "<?=$entity['siteorder_status'];?>";

          <? if(isset($statuses[SITEORDER_STATUS_DELIVERED])): ?>
            if (statusDatabaseVal != "<?=$statuses[SITEORDER_STATUS_DELIVERED];?>" && statusSelectVal == "<?=$statuses[SITEORDER_STATUS_DELIVERED];?>" && orderForm.hasClass('js-not-processed-<?=SITEORDER_STATUS_DELIVERED;?>')) {
              $.ajaxp2OpenPopup(base_url + admin_url + "/" + entityName + "/ajax_check_broadcast_send/<?=$entity['id'];?>/ty");
              return false;
            }
          <? endif; ?>
          <? if(isset($statuses[SITEORDER_STATUS_SHIPPED])): ?>
            if (statusDatabaseVal != "<?=$statuses[SITEORDER_STATUS_SHIPPED];?>" && statusSelectVal == "<?=$statuses[SITEORDER_STATUS_SHIPPED];?>" && orderForm.hasClass('js-not-processed-<?=SITEORDER_STATUS_SHIPPED;?>')) {
              $.ajaxp2OpenPopup(base_url + admin_url + "/" + entityName + "/ajax_check_broadcast_send/<?=$entity['id'];?>/order");
              return false;
            }
          <? endif; ?>
          <? if(isset($statuses['client-confirmed'])): ?>
            if (statusDatabaseVal != "<?=$statuses['client-confirmed'];?>" && statusSelectVal == "<?=$statuses['client-confirmed'];?>" && orderForm.hasClass('js-not-processed-client-confirmed')) {
              $.ajaxp2OpenPopup(base_url + admin_url + "/" + entityName + "/ajax_check_broadcast_send/<?=$entity['id'];?>/order_confirmed");
              return false;
            }
          <? endif; ?>
          <? if(isset($statuses['payment_pending'])): ?>
            if (statusDatabaseVal != "<?=$statuses['payment_pending'];?>" && statusSelectVal == "<?=$statuses['payment_pending'];?>" && orderForm.hasClass('js-not-processed-payment-pending')) {
              $.ajaxp2OpenPopup(base_url + admin_url + "/" + entityName + "/ajax_check_broadcast_send/<?=$entity['id'];?>/order_confirmed");
              return false;
            }
          <? endif; ?>
          form.submit();
        }
      });


      var siteOrderId = '<?=$entity['id'];?>';

      var phoneEl = $('#siteorder_phone');

      $("<a id='js-show-sms-log-" + siteOrderId + "' data-ajaxp-url='" + base_url + admin_url + "/eventlog/sms/?phone=" + phoneEl.val() + "'>Лог СМС</a>").insertAfter(phoneEl);
      $("#js-show-sms-log-" + siteOrderId).live('click', function() {
        $.ajaxp2OpenPopup($(this).data().ajaxpUrl);
      });

      var statusEl = $('#siteorder_siteorder_status');
      $(statusEl).attr('data-initial-value', statusEl.val());

      $("<a id='js-show-status-log-" + siteOrderId + "' data-ajaxp-url='" + base_url + admin_url + "/eventlog/siteorder/?siteorder_id=" + siteOrderId + "'>Лог изменений</a>").insertAfter(statusEl.next('div'));
      $("#js-show-status-log-" + siteOrderId).live('click', function() {
        $.ajaxp2OpenPopup($(this).data().ajaxpUrl);
      });

			statusEl.change(function() {
				statusEl.find('select:first').attr('disabled', 'disabled');
				var vl = $(this).val();
				var that = this;
				$.get(base_url + admin_url + "/siteorder/change_status?siteOrderId=" + siteOrderId + "&vl=" + vl, function(data) {
					var resp = jQuery.parseJSON(data);
					if (resp['status'] == 'busy') {
						$(that).val($(that).data('initial-value')).trigger("liszt:updated");
						alert(resp['message']);
				  	$(that).css('borderColor', '#F00');
				  	setTimeout(function() {
					  	$(that).css('borderColor', '#AAA');
					  }, 3000);
					} else {
            $(that).attr('data-initial-value', vl);
          }
				  if (resp['status'] == '<?=SITEORDER_STATUS_WAIT;?>') {
				  	$(that).css('borderColor', '#0F0');
            $(that).attr('data-initial-value', vl);
					  setTimeout(function() {
					  	$(that).css('borderColor', '#06f');
					  }, 500);
						if (resp['sms'] != '') {
					  	if ($('#smssend').length > 0) {
						  	$('#smssend').remove();
						  	$('body').append(resp['sms']);
						  } else {
						  	$('body').append(resp['sms']);
						  }
						}
				  }
				  if (resp['status'] == '<?=SITEORDER_STATUS_CONFIRMED_SUPPLIER;?>' ||
						  resp['status'] == '<?=SITEORDER_STATUS_CONFIRMED_STOCK;?>') {
				  	$(that).data('initial-value', vl);
				  	$(that).css('borderColor', '#0F0');
					  setTimeout(function() {
					  	$(that).css('borderColor', '#06f');
					  }, 500);
            $.ajaxp2OpenPopup(base_url + admin_url + "/" + entityName + "/ajax_check_order_shipmet_doc/<?=$entity['id'];?>");
            return false;
				  }
				  if (resp['status'] == 'ok') {
				  	$(that).data('initial-value', vl);
				  	$(that).css('borderColor', '#0F0');
					  setTimeout(function() {
					  	$(that).css('borderColor', '#06f');
					  }, 500);
				  }
				  $(that).removeAttr('disabled');
				});
			});

      var shipmentStoreEl = $('#siteorder_shipment_store');
      $(shipmentStoreEl).attr('data-initial-value', shipmentStoreEl.val());

      shipmentStoreEl.change(function() {
        shipmentStoreEl.find('select:first').attr('disabled', 'disabled');
        var vl = $(this).val();
        var that = this;
        $.get(base_url + admin_url + "/siteorder/change_shipment_store?siteOrderId=" + siteOrderId + "&vl=" + vl, function(data) {
          var resp = jQuery.parseJSON(data);
          if (resp['status'] == 'busy') {
            $(that).val($(that).data('initial-value')).trigger("liszt:updated");
            alert(resp['message']);
            $(that).css('borderColor', '#F00');
            setTimeout(function() {
              $(that).css('borderColor', '#AAA');
            }, 3000);
          } else {
            $(that).attr('data-initial-value', vl);
          }
          if (resp['status'] == 'ok') {
            $(that).data('initial-value', vl);
            $(that).css('borderColor', '#0F0');
            setTimeout(function() {
              $(that).css('borderColor', '#06f');
            }, 500);
          }
          $(that).removeAttr('disabled');
        });
      });


      var shipmentDateEl = $('#siteorder_shipment_date');
      $(shipmentDateEl).attr('data-initial-value', shipmentDateEl.val());


      $("<div class='group' style='margin-top: 5px'><a id='js-show-status-log-" + siteOrderId + "' data-ajaxp-url='" + base_url + admin_url + "/eventlog/shipmentDateAction/?siteorder_id=" + siteOrderId + "' style='color: blue'> Лог изменения даты отгрузки</a></div>").insertAfter(shipmentDateEl);
      $("#js-show-status-log-" + siteOrderId).live('click', function() {
        $.ajaxp2OpenPopup($(this).data().ajaxpUrl);
      });

      shipmentDateEl.change(function() {
        $(this).attr('disabled', 'disabled');
        var vl = $(this).val();
        var that = this;
        $.get(base_url + admin_url + "/siteorder/change_shipment_date?siteOrderId=" + siteOrderId + "&vl=" + vl, function(data) {
          var resp = jQuery.parseJSON(data);
          if (resp['status'] == 'busy') {
            $(that).val($(that).data('initial-value')).trigger("liszt:updated");
            alert(resp['message']);
            $(that).css('borderColor', '#F00');
            setTimeout(function() {
              $(that).css('borderColor', '#AAA');
            }, 3000);
          } else {
            $(that).attr('data-initial-value', vl);
          }
          if (resp['status'] == 'ok') {
            $(that).data('initial-value', vl);
            $(that).css('borderColor', '#0F0');
            setTimeout(function() {
              $(that).css('borderColor', '#06f');
            }, 500);
          }
          $(that).removeAttr('disabled');
        });
      });

    });
  </script>
<? endif; ?>

<div class="content default-box">
  <div class="title">
    <?=$lang->line('admin.add_edit.' . $entityName . ".form_title");?>
  </div>
  <?=html_flash_message();?>
  <?=fill_form_with_saved_post('editForm');?>
  <div class="inner">
    <form action="<?=site_url($processLink) . get_get_params();?>" method="post" class="form js-siteorder-form js-not-processed-<?=SITEORDER_STATUS_DELIVERED;?> js-not-processed-<?=SITEORDER_STATUS_SHIPPED;?> js-not-processed-payment-pending js-not-processed-client-confirmed" id="editForm" autocomplete="off" enctype="multipart/form-data">
      <div class="group navform wat-cf">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
        <button class="button" type="submit" name="save_and_return_to_list" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_return_to_list');?>"/>Сохранить и закончить работу с заказом
        </button>
        <img src="<?=site_img('admin/icons/down.png');?>" class="scroll-down">
        <? if(isset($entity['id']) && isset($print) && $print) :?>
          <a class="print" href="<?=admin_site_url($entityName . '/printpage/' . $entity['id']);?>"><img src="<?=site_img('admin/icons/print_icon.png');?>"/></a>
        <? endif;?>

        <? if(isset($entity['id'])) :?>
          <? if (isset($actions) && count($actions) > 0): ?>
            <ul class="act-list a-list">
            <? foreach($actions as $key => $value): ?>
              <li>
              <? if (isset($loggedInAdmin['permissions']) && in_array(strtolower($entityName) . '_' . $key, explode('|', $loggedInAdmin['permissions']))): ?>
                <? if($key != 'edit' && $key != 'add'): ?>
                  <? if($key == "delete" && (!isset($entity["can_be_deleted"]) || (isset($entity["can_be_deleted"]) && $entity["can_be_deleted"] == "Y"))): ?>
                    <a <? if($key == "delete"): ?>class="deleteLink confirm" title="<?=lang('admin.confirm.entity_delete')?>"<? endif; ?> href="<?=site_url($value . '/' . $entity['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a>
                  <? endif; ?>
                  <? if($key != "delete"): ?>
                    <a href="<?=site_url($value . '/' . $entity['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a>
                  <? endif;  ?>
                <? endif; ?>
              <? else: ?>
                <a href="<?=site_url($value . '/' . $entity['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a>
              <? endif; ?>
              </li>
            <? endforeach; ?>
            </ul>
          <? endif; ?>
        <? endif; ?>
      </div>

      <? if (isset($entity['id'])) :?>
        <input type="hidden" name="id" value="<?=$entity['id'];?>"/>
      <? endif;?>

      <? foreach ($fields as $key => $params) :?>

        <? if (isset($groups)): ?>
          <? foreach($groups as $gk => $gr) : ?>
            <? if (in_array($key, $gr) && array_key_by_value($key, $gr) == 0): ?>
              <div class="field-group-head"><a href="#" class="group-link" id="g<?=$gk;?>"><?=lang("admin.add_edit." . $entityName . ".group_" . $gk);?></a> <span>&darr;</span></div>
              <div class="fieldGroup" style="display: none;" id="group_<?=$gk;?>">
            <? endif; ?>
          <? endforeach; ?>
        <? endif; ?>

        <? if(!array_key_exists(0, $params)): ?>
          <? $params = array($params); ?>
        <? endif; ?>


        <? foreach ($params as $params): ?>

          <? $attrs = ""; ?>
          <? if (isset($params["attrs"])) {
              foreach($params["attrs"] as $n => $val) {
             	  $attrs .= ' '. $n ;
             	  if ($val) $attrs .= '="' . $val . '"';
              }
            }
          ?>

          <? if (strstr($key, '.') !== FALSE) {
              $tmpKey = explode('.', $key);
              $inputVal = $entity;
              foreach ($tmpKey as $tmpK) {
                $inputVal = isset($inputVal[$tmpK]) ? $inputVal[$tmpK] : '';
              }
              $entity[$key] = $inputVal;
            }
          ?>

          <? if (isset($languages) && isset($i18nFields) && in_array($key, $i18nFields)): ?>
            <? $langKey = "admin.add_edit.$entityName.$key" ?>
            <? $this->view("includes/admin/parts/lang_tabs", array('languages' => $languages,
            																											 'type' => $params['type'],
            																											 'value' => isset($params['value']) ? $params['value'] : null,
                                                                   'key' => $key,
                                                                   'attrs' => $attrs,
                                                                   'entity' => $entity,
            																											 'params' => $params,
                                                                   'entityName' => $entityName,
                                                                   'name' => $key,
                                                                   'id' => $entityName . '_' . $key,
                                                                   'label' => isset($params['label']) ? $params['label'] : lang($langKey),
                                                                   'message' => lang("$langKey.description"),
                                                                   'langKey' => $langKey))?>
          <? else: ?>
            <? $langKey = "admin.add_edit.$entityName.$key" ?>
            <? $this->view("includes/admin/parts/fields/" . $params['type'], array('key' => $key,
                                                                                   'attrs' => $attrs,
            																																			 'value' => isset($params['value']) ? $params['value'] : null,
                                                                                   'entity' => $entity,
                                                                                   'params' => $params,
                                                                                   'entityName' => $entityName,
            																																			 'name' => $key,
                                                                                   'id' => isset($params['id']) ? $params['id'] : $entityName . '_' . $key,
                                                                                   'label' => isset($params['label']) ? $params['label'] : lang($langKey),
                                                                                   'message' => isset($params['message']) ? $params['message'] : lang("$langKey.description"),
                                                                   								 'langKey' => $langKey)); ?>
          <? endif; ?>
        <? endforeach; ?>
        <? if (isset($groups)): ?>
          <? foreach($groups as $gr) : ?>
            <? if (in_array($key, $gr) && array_key_by_value($key, $gr) == count($gr) - 1): ?>
              </div>
            <? endif; ?>
          <? endforeach; ?>
        <? endif; ?>


      <?endforeach;?>

      <div class="group">
        <span class="red"><b class="rf">*</b> - <?=lang('admin.required_description');?></span>
      </div>

      <div class="group navform wat-cf">
        <button class="button" type="submit" name="save" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
        </button>
        <button class="button" type="submit" name="save_and_return_to_list" value="1">
          <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_return_to_list');?>"/>Сохранить и закончить работу с заказом
        </button>
        <?/* if (isset($nextUrl) && !empty($nextUrl) && isset($entity['id'])) :?>
          <button class="button" type="submit" name="save_and_next" value="1">
              <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_and_next');?>"/><?=lang('admin.save_and_next');?>
          </button>
        <? endif;?>
        <? if(isset($actions['add']) && !isset($entity['id'])): ?>
          <button class="button" type="submit" name="save_and_add_new" value="1">
            <img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save_and_add_new');?>"/><?=lang('admin.save_and_add_new');?>
          </button>
        <? endif; */?>
        <?/*
        <a href="<?=site_url($backUrl);?>" class="button">
          <img src="<?=site_img("admin/icons/cross.png")?>" alt="<?=lang('admin.cancel');?>"/><?=lang('admin.cancel');?>
        </a>
        */?>
        <img src="<?=site_img('admin/icons/top.png');?>" class="scroll-up">
        <? if(isset($entity['id']) && isset($print) && $print) :?>
          <a class="print" href="<?=admin_site_url($entityName . '/printpage/' . $entity['id']);?>"><img src="<?=site_img('admin/icons/print_icon.png');?>"/></a>
        <? endif;?>
        <? if(isset($entity['id'])) :?>
          <? if (isset($actions) && count($actions) > 0): ?>
            <ul class="act-list a-list">
            <? foreach($actions as $key => $value): ?>
              <li>
              <? if (isset($loggedInAdmin['permissions']) && in_array(strtolower($entityName) . '_' . $key, explode('|', $loggedInAdmin['permissions']))): ?>
                <? if($key != 'edit' && $key != 'add'): ?>
                  <? if($key == "delete" && (!isset($entity["can_be_deleted"]) || (isset($entity["can_be_deleted"]) && $entity["can_be_deleted"] == "Y"))): ?>
                    <a <? if($key == "delete"): ?>class="deleteLink confirm" title="<?=lang('admin.confirm.entity_delete')?>"<? endif; ?> href="<?=site_url($value . '/' . $entity['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a>
                  <? endif; ?>
                  <? if($key != "delete"): ?>
                    <a href="<?=site_url($value . '/' . $entity['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a>
                  <? endif;  ?>
                <? endif; ?>
              <? else: ?>
                <a href="<?=site_url($value . '/' . $entity['id']) . get_get_params()?>"><?=lang("admin." . $key)?></a>
              <? endif; ?>
              </li>
            <? endforeach; ?>
            </ul>
          <? endif; ?>
        <? endif; ?>
      </div>
    </form>
  </div>
</div>
<div class="clear"></div>

<script>
  $(document).ready(function() {
    if ($('#siteorder_code').length > 0) {
      $('ul.act-list').find('a:contains("Создать ТТН")').click(function (event) {
        event.preventDefault();
        $.ajaxp2OpenPopup(base_url + admin_url + "/" + "siteorder/ajax_create_ttn/" + <?=$entity['id']?>);
      });
    }

    if ($('#siteorder_delivery_city').length > 0) {

      var streetInitValue = $("#siteorder_delivery_street").val();
      var streetRefInitValue = $("#siteorder_delivery_street_ref").val();

      var streetAutoCompleteOptions = {
        serviceUrl: base_url + admin_url + '/siteorder/get_address_ajax',
        params: {city: ''},
        minChars: 3,
        deferRequestBy: 1000,
        width: 450,
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Не найдено',
        onSelect: function (suggestion) {
          $("#siteorder_delivery_street_ref").val(suggestion.data.ref);
          $("#siteorder_delivery_street_type").val(suggestion.data.streets_type);
        },
        onInvalidateSelection: function () {
          $("#siteorder_delivery_street_ref").val('');
          $("#siteorder_delivery_street_type").val('');
        },
        onSearchStart: function (query) {
          $("#siteorder_delivery_street").addClass('js-preloader-input');
        },
        onSearchComplete: function (query, suggestions) {
          $("#siteorder_delivery_street").removeClass('js-preloader-input');
        },
        onSearchError: function (query, jqXHR, textStatus, errorThrown) {
          $("#siteorder_delivery_street").removeClass('js-preloader-input');
        }
      };

      $("#siteorder_delivery_street").autocomplete(streetAutoCompleteOptions);

//      $("#siteorder_delivery_street").change(function() {
//        if ($('#siteorder_delivery_street_ref').val() == streetRefInitValue && $(this).val() != streetInitValue) {
//          alert('Выберите улицу из списка');
//        }
//      });

      var deliveryCitySelect = $('select[name="delivery_city"]');
      var deliveryWarehouseSelect = $('select[name="delivery_warehouse"]');
      var deliveryWarehouseSelectVal = deliveryWarehouseSelect.val();

      deliveryCitySelect.change(function() {
        streetAutoCompleteOptions.params.city = $(this).val();

        $.getJSON(base_url + admin_url + '/store/ajax_get_warehouse/' + $(this).val(), function(data) {
          deliveryWarehouseSelect.empty();
          var options = '<option value="">--Не выбрано--</option>';
          if (data != null) {
            for (i in data) {
              options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
            }
          }
          deliveryWarehouseSelect.html(options);
          deliveryWarehouseSelect.val(deliveryWarehouseSelectVal);
          deliveryWarehouseSelect.trigger("liszt:updated");
        });
      });

      if (deliveryCitySelect.val() != '') {
        deliveryCitySelect.change();
      }
    }

    function updateSiteOrderDeliveryType() {
      if ($('#siteorder_delivery_type').val() == 'delivery-to-post') {
        $('#siteorder_delivery_street').closest('.group').hide();
        $('#siteorder_delivery_house').closest('.group').hide();
        $('#siteorder_delivery_flat').closest('.group').hide();
        $('#siteorder_delivery_warehouse').closest('.group').show();
      } else if ($('#siteorder_delivery_type').val() == 'delivery-to-home') {
        $('#siteorder_delivery_warehouse').closest('.group').hide();
        $('#siteorder_delivery_street').closest('.group').show();
        $('#siteorder_delivery_house').closest('.group').show();
        $('#siteorder_delivery_flat').closest('.group').show();
      }
    }

    if ($('#siteorder_delivery_type').length > 0) {
      updateSiteOrderDeliveryType();

      $('#siteorder_delivery_type').change(function() {
        updateSiteOrderDeliveryType();
      });
    }

    var deliveryDate = $('#siteorder_delivery_date');
    var deliveryInterval = $('#siteorder_delivery_interval');
    var deliveryIntervalCode = $('#siteorder_delivery_interval_code');

    deliveryInterval.focus(function() {
      deliveryDate.change();
    });

    deliveryDate.change(function() {

      $.getJSON(base_url + admin_url + '/siteorder/ajax_get_delivery_interval/' + $(this).val() + '/' + deliveryCitySelect.val(), function(data) {
        deliveryInterval.hide();
        if ($('select[name="delivery_interval_code"]').length == 0) {
          $('<select name="delivery_interval_code"></select>').insertAfter(deliveryInterval);
          var initVal = deliveryIntervalCode.val();
          deliveryIntervalCode.remove();
        }
        var deliveryIntervalSelect = $('select[name="delivery_interval_code"]');
        deliveryIntervalSelect.change(function() {
          var val = $(this).find(':selected').text();
          deliveryInterval.val(val);
        });

        deliveryIntervalSelect.empty();
        var options = '<option value="">--Не выбрано--</option>';
        if (data != null) {
          for (i in data) {
            options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
          }
        }
        deliveryIntervalSelect.html(options);
        deliveryIntervalSelect.val(initVal);
        deliveryIntervalSelect.trigger("liszt:updated");
      });
    });

    if (deliveryCitySelect.val() != '') {
      deliveryCitySelect.change();
    }

  })
</script>