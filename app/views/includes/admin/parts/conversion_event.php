<div class="content default-box">
  
  <div class="title">
    <span class="fl">Статистика</span>
    <a href="<?=site_url($backUrl) . get_get_params();?>" class="link">&lt; Назад</a>
  </div>
  
  
  <form action="<?=current_url();?>" method="get">
    <div class="js-input-row-date">
      <label for="from">От</label>
      <input class="js-input-row-date date js-from" id="from" name="created_at_from" value="<?= !empty($_GET['created_at_from']) ? $_GET['created_at_from'] : "";?>">
      <label for="to">До</label>
      <input class="js-input-row-date date js-to" id="to" name="created_at_to" value="<?= !empty($_GET['created_at_to']) ? $_GET['created_at_to'] : "";?>">
      <input type="submit" value="Показать">
      <div class="filter-stats">
        <div class="js-date-filter-links">
          <span class="l">За:</span>
          <a class="js-today link">сегодня</a>&nbsp;/&nbsp;
          <a class="js-this-week link">эту неделю</a>&nbsp;/&nbsp;
          <a class="js-this-month link">этот месяц</a>&nbsp;/&nbsp;
          <a class="js-cancel link">сбросить</a>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  </form>

  <div class="clear"></div>

  <?=html_flash_message(); ?>

  <? if (isset($conversions) && isset($result) && !empty($result)): ?>

    <!-- Highcharts 100% width -->
    <div class="graph-box">
      <div id="js-container" style="width: 100%; height: 400px; margin: 0 auto"></div>
    </div>
    
    <!-- TABLES... -->
    <? foreach ($conversions as $c): ?>
      <? $showButton = FALSE; ?>
      <? if (isset($tablesData[$c['id']])): ?>
        <div class="event-box">
          <h3 class="name"><?=$c['name'];?></h3>
          <? if (!empty($tablesData[$c['id']])): ?>
            <table cellspacing="0" cellpadding="0">
              <tr>
                <td class="th td-1">
                  <p>Page URL</p>
                </td>
                <td class="th td-2">
                  <p>Количество</p>
                </td>
              </tr>
              <? foreach ($tablesData[$c['id']] as $i => $td): ?>
                <tr<?= ($i > 4) ? ' id="inv" style="display: none;"' : ''; ?>>
                  <? $showButton = ($i > 4) ? TRUE : FALSE; ?>
                  <td>
                    <a target="_blank" href="<?=$td['page'];?>"><?=$td['page'];?></a>
                  </td>
                  <td>
                    <p><?=$td['count'];?></p>
                  </td>
                </tr>
              <? endforeach; ?>
            </table>
          <? else: ?>
            <h4 class="no-action">Действий не было</h4>
          <? endif; ?>
          <? if ($showButton && !empty($tablesData[$c['id']])): ?>
            <div class="js-links">
              <span class="link-more js-more">Показать все &darr;</span>
              <span style="display: none;" class="link-more js-hide">Скрыть &uarr;</span>
            </div>
          <? endif; ?>
        </div>
      <? endif; ?>
    <? endforeach; ?>
    
    <script type="text/javascript">
      Highcharts.setOptions({
        global: {
            useUTC: true
        },
        lang: {
          months: ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'],
          weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
          shortMonths: ['Янв', 'Фев', 'Мар', 'Апр', 'Мая', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек']
        }      
      });
     
      $(function () { 
        $('#js-container').highcharts({
          chart: {
              type: 'line'
          },
          credits: {
            enabled: false
          },
          title: {
            text: 'Статистика'
          },
          yAxis: {
            allowDecimals: false,
            min: 0,
  	        title: {
              text: 'Количество'
            }
  	      },
          xAxis: {
            type: 'datetime',
            dateTimeLabelFormats : {
              hour: ' ',
              minute: ' '
            }
          },	      
  	      tooltip: {
            shared: true,
            crosshairs: true
          },
          series: [
            <? 
              $convCounter = 1;
              foreach ($conversions as $c) {
                echo "\n{\nname: '{$c['name']}', \n";
                echo "data: [";
                $counter = 0;
                foreach ($result[$c['id']] as $d => $r) {
                  if ($counter < (count($result[$c['id']]))) {
                    $date = date_parse($d);
                    $date['month'] = $date['month'] - 1;
                    echo "[Date.UTC({$date['year']},{$date['month']},{$date['day']}),{$r}], ";
                  } else {
                    echo "[Date.UTC({$date['year']},{$date['month']},{$date['day']}),{$r}]";
                  }
                  $counter++;
                }
                if ($convCounter < count($conversions)) {
                  echo "]\n}, ";
                  $convCounter++;
                } else {
                  echo "]\n}";
                }
              }
            ?>
  
            ]
        });
      });

      
      $('.js-more').click(function() {
        $(this).hide();
        var buttonMore = $(this);
        var rows = $(this).parents('.event-box');
        var buttons = buttonMore.closest('.js-links');
        buttons.find('.js-hide').show();
        rows.find('#inv').each(function() {
          $(this).show();
          });
        });
    
      $('.js-hide').click(function() {
        $(this).hide();
        var buttonMore = $(this);
        var rows = $(this).parents('.event-box');
        var buttons = buttonMore.closest('.js-links');
        buttons.find('.js-more').show();
        rows.find('#inv').each(function() {
          $(this).hide();
          });
        });
      
    </script>
    
  <? else: ?>
    <p>Выберите даты.</p> 
  <? endif; ?>

</div>



<script type="text/javascript">
  $('.js-date-filter-links a').click(function() {
    var $rowDate = $(this).closest('.js-input-row-date');
    var fromInp = $rowDate.find('.js-from:first');
    var toInp = $rowDate.find('.js-to:first');
    // Today
    if ($(this).hasClass('js-today')) {
      fromInp.val(date_to_str(new Date()));
      toInp.val(date_to_str(new Date()));
      toInp.change();
    }

    // This week
    if ($(this).hasClass('js-this-week')) {
      // Date from = Monday
      var dtFrom = new Date();
      while (dtFrom.getDay() != 1) {
        dtFrom.setDate(dtFrom.getDate() - 1);
      }

      // Date to = Sunday
      var dtTo = new Date();
      while (dtTo.getDay() != 0) {
        dtTo.setDate(dtTo.getDate() + 1);
      }

      fromInp.val(date_to_str(dtFrom));
      toInp.val(date_to_str(dtTo));
      toInp.change();
    }

    // This Month
    if ($(this).hasClass('js-this-month')) {
      // Date from = Date 1
      var dtFrom = new Date();
      dtFrom.setDate(1);

      // Date to = Date 28/30/31
      var dtTo = new Date();
      var thisMonth = dtTo.getMonth();
      dtTo.setDate(1);
      dtTo.setMonth(thisMonth + 1);
      dtTo.setDate(dtTo.getDate() - 1);

      fromInp.val(date_to_str(dtFrom));
      toInp.val(date_to_str(dtTo));
      toInp.change();
    }

    // Cancel
    if ($(this).hasClass('js-cancel')) {
      fromInp.val('');
      toInp.val('');
      toInp.change();
    }

    return false;
  });

  function date_to_str(date) {
    var result = "";
    result += date.getFullYear();
    result += '-';

    var mnth = date.getMonth() + 1;
    if (mnth < 10) {
      result += '0' + mnth;
    } else {
      result += mnth;
    }

    result += '-';

    var dt = date.getDate();
    if (dt < 10) {
      result += '0' + dt;
    } else {
      result += dt;
    }
    return result;
  }
</script>
  
