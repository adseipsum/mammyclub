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

      <input type="hidden" name="stats_type" value="broadcast" />

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
              <label for="email-search">Заголовок</label>
              <input id="email-search" type="text" name="subject" value="<?=!empty($_GET['subject'])?$_GET['subject']:'';?>" tabindex="1"/><br/>
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
        jQuery(function() {
          var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');

          $('input[name=subject]').each(function() {
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

  </div>

  <div class="inner export">

    <?=$this->view("includes/admin/user/parts/menu-top"); ?>

    <? if(!empty($result)): ?>

      <table class="item-table" cellspacing="0" cellpadding="0" border="1">
        <tr class="mainRow">
          <td>Дата</td>
          <td>Наименование</td>
          <td>Количество открытий</td>
          <td>Дата и время открытий</td>
          <td>Ссылки, которые кликнул</td>
          <td>HTML</td>
        </tr>
        <? foreach ($result as $r): ?>
          <tr>
            <td>
              <p><?=$r['created_at'];?></p>
            </td>
            <td>
              <p><?=$r['subject'];?></p>
            </td>
            <td>
              <p><?=$r['open_count'];?></p>
            </td>
            <td>
              <? if(!empty($r['opens'])): ?>
                <ul>
                  <? foreach ($r['opens'] as $open): ?>
                    <li><?=$open;?></li>
                  <? endforeach; ?>
                </ul>
              <? endif; ?>
            </td>
            <td>
              <? if(!empty($r['visited_links'])): ?>
                <ul>
                  <? foreach ($r['visited_links'] as $vLink): ?>
                    <li><a href="<?=$vLink;?>"><?=$vLink;?></a></li>
                  <? endforeach; ?>
                </ul>
              <? endif; ?>
            </td>
            <td>
              <? if(!empty($r['html_url'])): ?>
                <p><a target="_blank" href="<?=$r['html_url'];?>">Смотреть</a></p>
              <? endif; ?>
            </td>
          </tr>
        <? endforeach; ?>
      </table>

      <div class="clear"></div>
    <? else:?>
      <p>У пользователя пока еще нет рассылок..</p>
    <? endif; ?>
  </div>

</div>