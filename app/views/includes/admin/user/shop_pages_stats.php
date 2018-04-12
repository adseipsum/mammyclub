<div class="content default-box">

  <h2 class="title">
    <span class="fl">Статистика пользователя <?=$user['name'];?></span>
    <a class="link" style="float: right;" href="<?=admin_site_url('user')?>"><?=lang('admin.add_edit_back');?></a>
    <div class="clear"></div>
  </h2>
  <?=html_flash_message();?>

  <style>
    .item-table tr td {padding: 10px;}
    .item-table .mainRow td {padding: 20px 10px; font-weight: bold; font-size: 12px;}
  </style>

    <div class="search-box filter-bar">
    <form action="<?=current_url();?>" method="get" class="validate">

      <input type="hidden" name="stats_type" value="shop_pages" />

      <div class="input-row input-row-date">
        <div class="l">Дата:</div>
        <div class="r">
          <span class="date-filter-label"><?=lang('admin.filter.from');?>:&nbsp;</span>
          <input class="date-filter-input date from" type="text" name="date_from" value="<?=!empty($_GET['date_from'])?$_GET['date_from']:'';?>" />&nbsp;&nbsp;&nbsp;
          <span class="date-filter-label"><?=lang('admin.filter.to');?>:&nbsp;</span>
          <input class="date-filter-input date to" type="text" name="date_to" value="<?=!empty($_GET['date_to'])?$_GET['date_to']:'';?>"/>
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
              <label for="email-search">URL</label>
              <input id="email-search" type="text" name="url" value="<?=!empty($_GET['url'])?$_GET['url']:'';?>" tabindex="1"/><br/>
            </div>
          </div>

        </div>
        <div class="clear"></div>
      </div>

      <div class="clear"></div>


      <div class="group navform wat-cf">
        <button type="submit">Показать</button>
      </div>

    </form>

    <script type="text/javascript">
      $(document).ready(function() {
        var options, a;
        var user_id = '<?=$user['id'];?>';
        jQuery(function() {
          var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');

          $('input[name=url]').each(function() {
            options = {
                        serviceUrl: '<?=admin_site_url('user/shop_pages_stats_search_autocomplete');?>' + '?type=' + $(this).attr('name') + '&user_id=' + user_id,
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

  </div>

  <div class="inner export">

    <?=$this->view("includes/admin/user/parts/menu-top"); ?>

    <? if(!empty($viewedShopPages)): ?>

      <table class="item-table" cellspacing="0" cellpadding="0" border="1">
        <tr class="mainRow">
          <td>Дата</td>
          <td>URL</td>
          <td>Время</td>
          <td>Клики на галерею</td>
          <td>Клики на фильры</td>
          <td>Клики по вкладкам</td>
          <td>Клики по ссылкам</td>
          <td>Откуда пришел</td>
        </tr>
        <? foreach ($viewedShopPages as $p): ?>
          <tr>
            <td>
              <p><?=$p['created_at'];?></p>
            </td>
            <td>
              <p><a href="<?=shop_url($p['url']);?>"><?=$p['url'];?></a></p>
            </td>
            <td>
              <p><?=$p['time_on_page'];?></p>
            </td>
            <td>
              <p><?=!empty($p['gallery_item_click'])?'Да':'Нет';?></p>
            </td>
            <td>
              <p><?=!empty($p['filters_item_click'])?'Да':'Нет';?></p>
            </td>
            <td>
              <? if(!empty($p['tab_item_click'])): ?>
                <ul>
                  <? foreach ($p['tab_item_click'] as $tab): ?>
                    <li><?=lang('tab_item_click.' . $tab);?></li>
                  <? endforeach; ?>
                </ul>
              <? endif; ?>
            </td>
            <td>
              <? if(!empty($p['link_item_click'])): ?>
                <ul>
                  <? foreach ($p['link_item_click'] as $link): ?>
                    <li><a href="<?=$link;?>"><?=$link;?></a></li>
                  <? endforeach; ?>
                </ul>
              <? endif; ?>
            </td>
            <td>
              <p><?=$p['referrer_url'];?></p>
            </td>
          </tr>
        <? endforeach; ?>
      </table>

      <div class="clear"></div>
    <? else:?>
      <p>Нет данных..</p>
    <? endif; ?>
  </div>

</div>