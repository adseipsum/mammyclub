<? if(isset($filters) && !empty($filters)): ?>

<div class="filter-box js-filter-box"<?=!empty($_GET['filters'])?' style="display: block;"':'';?>>
  <div class="i-filter-box">
    <ul class="filter-accordion">

      <? foreach ($filters as $filter): ?>
        <? if(!empty($filter['filter_values'])): ?>
          <li class="item">
            <p class="name-f close js-filter-name"><?=$filter['name'];?></p>
            <div class="in-a js-filter-values"<?=!empty($_GET['filters'][$filter['id']])?' style="display: block;"':'';?>>
              <ul class="list">
                <? foreach ($filter['filter_values'] as $fv): ?>
                  <?
                    $digits = '';
                    if($fv['count'] > 0 && (empty($_GET['filters'][$filter['id']]) || !in_array($fv['id'], $_GET['filters'][$filter['id']])) ) {
                      $digits .= '(';
                      if(!empty($_GET['filters'][$filter['id']])) {
                        $digits .= '+';
                      }
                      $digits .= $fv['count'];
                      $digits .= ')';
                    }
                  ?>
                  <li><span class="js-filters js-filtervalue-idtype-<?=$fv['id'];?>_filters_<?=$filter['id']?><?=!empty($_GET['filters'][$filter['id']])&&in_array($fv['id'], $_GET['filters'][$filter['id']])?' checked':'';?>"><?=$fv['name'];?> <?=$digits;?></span></li>
                <? endforeach; ?>
              </ul>
            </div>
          </li>
        <? endif; ?>
      <? endforeach; ?>

    </ul>

    <table class="filter-actions">
      <tr>
        <?/*
        <td class="td-1"><span class="def-but green-but">Показать товары</span></td>
        */?>
        <td class="td-2"><a class="js-clear-filter a-like" href="<?=shop_url($pageUrl);?>">ОЧИСТИТЬ ФИЛЬТР</a></td>
        <td class="td-3"><span class="js-close-filter cp"><img src="<?=site_img('filter_close_icon.png')?>"/></span></td>
      </tr>
    </table>

  </div>
</div>


<? endif; ?>


<?/*
<div class="filter-box js-filter-box">
  <div class="i-filter-box">
    <ul class="filter-accordion">
      <li class="item">
        <p class="name-f close js-filter-name">Пол</p>
        <div class="in-a js-filter-values">
          <ul class="list">
            <li><span class="js-filter-value">мальчик</span></li>
            <li><span class="js-filter-value">девочка</span></li>
          </ul>
        </div>
      </li>
      <li class="item">
        <p class="name-f close js-filter-name">Возраст ребенка</p>
        <div class="in-a js-filter-values">
          <ul class="list">
            <li><span class="js-filter-value">новорожденный</span></li>
            <li><span class="js-filter-value">0-3 мес</span></li>
            <li><span class="js-filter-value">3-6 мес</span></li>
            <li><span class="js-filter-value">6-9 мес</span></li>
            <li><span class="js-filter-value">9-12 мес</span></li>
            <li><span class="js-filter-value">12-18 мес</span></li>
            <li><span class="js-filter-value">18-24 мес</span></li>
            <li><span class="js-filter-value">2-3 года</span></li>
          </ul>
        </div>
      </li>
      <li class="item">
        <p class="name-f close js-filter-name">Бренд</p>
        <div class="in-a js-filter-values">
          <ul class="list">
            <li><span class="js-filter-value">Adidas</span></li>
            <li><span class="js-filter-value">Reebok</span></li>
            <li><span class="js-filter-value">New Ballance</span></li>
            <li><span class="js-filter-value">Lotto</span></li>
          </ul>
        </div>
      </li>
      <li class="item">
        <p class="name-f close js-filter-name">Размер обуви</p>
        <div class="in-a js-filter-values">
          <ul class="list">
            <li><span class="js-filter-value">9</span></li>
            <li><span class="js-filter-value">10</span></li>
            <li><span class="js-filter-value">11</span></li>
            <li><span class="js-filter-value">12</span></li>
            <li><span class="js-filter-value">13</span></li>
            <li><span class="js-filter-value">14</span></li>
            <li><span class="js-filter-value">15</span></li>
            <li><span class="js-filter-value">16</span></li>
          </ul>
        </div>
      </li>
    </ul>

    <table class="filter-actions">
      <tr>

        <td class="td-1"><span class="def-but green-but">Показать товары</span></td>

        <td class="td-2"><span class="js-clear-filter a-like">ОЧИСТИТЬ ФИЛЬТР</span></td>
        <td class="td-3"><span class="js-close-filter cp"><img src="<?=site_img('filter_close_icon.png')?>"/></span></td>
      </tr>
    </table>

  </div>
</div>
*/?>

<script type="text/javascript">
  $(document).ready(function() {

    // Hide/show filter-values
    $('.js-filter-name').click(function(){
      if ($(this).next().is(':visible')) {
        $(this).next().hide();
        $(this).removeClass('open');
        $(this).addClass('close');
      } else {
        $(this).next().show();
        $(this).removeClass('close');
        $(this).addClass('open');
      }
    });

    // Checked/unchecked filter-value
  //  $('.js-filter-value').click(function(){
  //   if ($(this).hasClass('checked')) {
  //      $(this).removeClass('checked');
  //    } else {
  //      $(this).addClass('checked');
  //    }
  //  });

    // Clear filter-value
  //  $('.js-clear-filter').click(function(){
  //    $('.js-filter-value').removeClass('checked');
  //  });

    
    // Close filter-box
    $('.js-close-filter').click(function(){
      $('.js-filter-box').hide();

      if ($('.js-filter-box').is(':visible')) {
        $('.js-filter-icon').hide();
      } else {
        $('.js-filter-icon').show();
      }
      
    });

    // Hide/show filter-box
    $('.js-filter-icon').click(function(){
      
    	$('html, body').animate({'scrollTop': 0}, 900, 'swing');	 
      
      if ($('.js-filter-box').is(':visible')) {
        $('.js-filter-box').hide();
        $(this).show();
      } else {
        $('.js-filter-box').show();
        $(this).hide();
      }
      
    });


    // Hide filter-icon
    if ($('.js-filter-box').is(':visible')) {
      $('.js-filter-icon').hide();
    }
      


  });
</script>