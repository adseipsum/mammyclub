<div class="content default-box">

  <h2 class="title">
    <span class="fl">Статистика по рассылкам</span>
    <a class="link" style="float: right;" href="<?=admin_site_url('mandrillbroadcast')?>"><?=lang('admin.add_edit_back');?></a>
    <div class="clear"></div>
  </h2>
  <?=html_flash_message();?>

  <div class="search-box filter-bar">
    <form action="<?=current_url();?>" method="get" class="validate">

      <div class="input-row input-row-date">
        <div class="l">Дата:</div>
        <div class="r">
          <span class="date-filter-label"><?=lang('admin.filter.from');?>:&nbsp;</span>
          <input class="date-filter-input date from required" type="text" name="date_from" value="<?=!empty($_GET['date_from'])?$_GET['date_from']:'';?>" />&nbsp;&nbsp;&nbsp;
          <span class="date-filter-label"><?=lang('admin.filter.to');?>:&nbsp;</span>
          <input class="date-filter-input date to required" type="text" name="date_to" value="<?=!empty($_GET['date_to'])?$_GET['date_to']:'';?>"/>
        </div>
        <div class="clear"></div>
      </div>

  		<div class="input-row input-row-new">
      	<label for="broadcast.type" class="float-left">Тип рассылки:</label>
        <div class="fl">
          <? $broadcastTypes = array('pregnancy_week_broadcast', 'pregnancy_week_broadcast_single_letter', 'recommended_products_broadcast', 'invite_to_recommended_products_broadcast', 'product_broadcast', 'first_year_broadcast', 'service', 'useful_tips_broadcast', RETURNING_BROADCAST_FIRST, RETURNING_BROADCAST_SECOND, TY_BROADCAST, ORDER_BROADCAST); ?>
          <select name="broadcast.type">
           	<option value=""><?=lang('admin.filter.all');?></option>
            <? foreach ($broadcastTypes as $type): ?>
              <option value="<?=$type;?>" <?=!empty($_GET['broadcast.type'])&&$_GET['broadcast.type']==$type?' selected="selected"':'';?>><?=lang('enum.mandrill.type.'. $type);?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>

      <div class="input-row input-row-new">
      	<label for="is_read" class="float-left">Открыл:</label>
        <div class="fl">
          <select name="is_read">
           	<option value=""><?=lang('admin.filter.all');?></option>
            <option value="yes" <?=!empty($_GET['is_read'])&&$_GET['is_read']=='yes'?' selected="selected"':'';?>>Да</option>
            <option value="no" <?=!empty($_GET['is_read'])&&$_GET['is_read']=='no'?' selected="selected"':'';?>>Нет</option>
          </select>
        </div>
      </div>

      <div class="input-row input-row-new">
      	<label for="read_more_click" class="float-left">Клик "Читать дальше":</label>
        <div class="fl">
          <select name="read_more_click">
           	<option value=""><?=lang('admin.filter.all');?></option>
            <option value="yes" <?=!empty($_GET['read_more_click'])&&$_GET['read_more_click']=='yes'?' selected="selected"':'';?>>Да</option>
            <option value="no" <?=!empty($_GET['read_more_click'])&&$_GET['read_more_click']=='no'?' selected="selected"':'';?>>Нет</option>
          </select>
        </div>
      </div>

      <div class="input-row input-row-new">
      	<label for="unsubscribe_link_click" class="float-left">Клик "Отписаться":</label>
        <div class="fl">
          <select name="unsubscribe_link_click">
           	<option value=""><?=lang('admin.filter.all');?></option>
            <option value="yes" <?=!empty($_GET['unsubscribe_link_click'])&&$_GET['unsubscribe_link_click']=='yes'?' selected="selected"':'';?>>Да</option>
            <option value="no" <?=!empty($_GET['unsubscribe_link_click'])&&$_GET['unsubscribe_link_click']=='no'?' selected="selected"':'';?>>Нет</option>
          </select>
        </div>
      </div>

      <div class="input-row input-row-new">
      	<label for="user.country" class="float-left">Страна:</label>
        <div class="fl">
          <? $countries = array('UA', 'RU'); ?>
          <select name="user.country">
           	<option value=""><?=lang('admin.filter.all');?></option>
            <? foreach ($countries as $c): ?>
              <option value="<?=$c;?>" <?=!empty($_GET['user.country'])&&$_GET['user.country']==$c?' selected="selected"':'';?>><?=$c;?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>

      <div class="input-row input-row-new">
        <div class="float-left search-container">

          <!-- Search bar -->
          <div class="float-left">
            <div class="input-row">
              <!-- Search string -->
              <img id="search_loader" class="search_loader" src="<?=site_img('admin/icons/small_back_loader.gif');?>" alt="Загружаем..." style="display: none;"/>
              <label for="email-search">Заголовок</label>
              <input id="email-search" type="text" name="subject" value="<?=!empty($_GET['subject'])?$_GET['subject']:'';?>" tabindex="1"/><br/>
            </div>
          </div>

        </div>
        <div class="clear"></div>
      </div>

      <div class="input-row input-row-new">
        <div class="float-left search-container">

          <!-- Search bar -->
          <div class="float-left">
            <div class="input-row">
              <!-- Search string -->
              <img id="search_loader" class="search_loader" src="<?=site_img('admin/icons/small_back_loader.gif');?>" alt="Загружаем..." style="display: none;"/>
              <label for="email-search">E-mail</label>
              <input id="email-search" type="text" name="email" value="<?=!empty($_GET['email'])?$_GET['email']:'';?>" tabindex="2"/><br/>
            </div>
          </div>

        </div>
        <div class="clear"></div>
      </div>

      <div class="clear"></div>


      <div class="group navform wat-cf">

        <button type="submit">Показать</button>

        <? if(!empty($_GET) && !empty($result)) :?>
          <a href="<?=admin_site_url('mandrillbroadcast/stats_dynamic_export_csv' . get_get_params());?>">Экспорт в CSV</a>
        <? endif;?>

      </div>

    </form>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      var options, a;
      jQuery(function() {
        var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');

        $('input[name=email], input[name=subject]').each(function() {
          options = {
                      serviceUrl: '<?=admin_site_url('mandrillbroadcast/stats_dynamic_search_autocomplete');?>' + '?type=' + $(this).attr('name'),
                      fnFormatResult: function(value, data, currentValue) {
                        var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
                        return value.replace(new RegExp(pattern, 'gi'), '<span class="red-color">$1<\/span>') + '<br/>' + '<span class="fade-text"></span>' + '<span class="details">' + data.replace(new RegExp(pattern, 'gi'), '<span class="red-color">$1<\/span>') + '</span>';
                      },
                      preloader: $('#search_loader')
                    };
          $(this).ajaxautocomplete(options);

        });

      });
    });
  </script>

  <? if(!empty($result)): ?>

    <style>
      .item-table tr td {padding: 10px;}
      .item-table .mainRow td {padding: 20px 10px; font-weight: bold; font-size: 14px;}
    </style>

    <div class="inner export">

      <table class="item-table" cellspacing="0" cellpadding="0" border="0">
        <tr class="mainRow">
          <td>Дата</td>
          <td>Тип</td>
          <td>Название</td>
          <td>Заголовок письма</td>
          <td>E-mail</td>
          <td>Открыл</td>
          <td>Клик "Читать дальше"</td>
          <td>Клик "Отписаться"</td>
          <td>Ссылки</td>
          <td>Страна</td>
          <td>Тело письма</td>
        </tr>
        <? foreach ($result as $item): ?>
          <tr>
            <td>
              <p><?=$item['updated_at'];?></p>
            </td>
            <td>
              <p><?=lang('enum.mandrill.type.' . $item['broadcast']['type']);?></p>
            </td>
            <td>
              <p><?=$item['broadcast']['name'];?></p>
            </td>
            <td>
              <p><?=$item['broadcast']['subject'];?></p>
            </td>
            <td>
              <p><?=$item['email'];?></p>
            </td>
            <td>
              <p><?=$item['is_read']==TRUE?'Да':'Нет';?></p>
            </td>
            <td>
              <p><?=$item['read_more_click']==TRUE?'Да':'Нет';?></p>
            </td>
            <td>
              <p><?=$item['unsubscribe_link_click']==TRUE?'Да':'Нет';?></p>
            </td>
            <td>
              <? if(!empty($item['MandrillBroadcastVisitedLink'])): ?>
                <p>
                  <? foreach ($item['MandrillBroadcastVisitedLink'] as $link): ?>
                    <a href="<?=$link['link']['url'];?>"><?=$link['link']['url'];?></a><br />
                  <? endforeach; ?>
                </p>
              <? endif; ?>
            </td>
            <td>
              <p><?=$item['user']['country'];?></p>
            </td>
            <td>
              <? if(!empty($item['mandrill_email_id'])): ?>
                <p><a target="_blank" href="<?=admin_site_url('mandrillbroadcast/view_webversion/' . $item['id']);?>">Смотреть</a></p>
              <? endif; ?>
            </td>
          </tr>
        <? endforeach; ?>
      </table>

      <div class="clear"></div>
    </div>

  <? endif; ?>

</div>