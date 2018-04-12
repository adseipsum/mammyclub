<ul class="prod-list">
  <? $counter = 1; ?>
  <? foreach ($products as $p): ?>
    <li class="js-dl-product <?= $counter % 2 == 0 ? 'last' : ''; ?> <?= $counter % 3 == 0 ? 'last-3' : ''; ?>" <?=js_data_fields_product($p, $counter);?>>
      <div class="in">
        <div class="clear"></div>
        <div class="img-box pr">
          <div class="bot-line"><a class="name-3" href="<?=shop_url($p['page_url']);?>"><?=$p['name'];?></a></div>
          <a class="link" href="<?=shop_url($p['page_url']);?>">
            <? if (!empty($p['image'])): ?>
              <img src="<?=site_image_thumb_url('_medium_list', $p['image']);?>" alt="<?=$p['name'];?>" title="<?=$p['name'];?>" />
            <? else: ?>
              <img src="<?=site_img('no_good_icon_3.png')?>" alt="<?=$p['name'];?>" title="<?=$p['name'];?>" />
            <? endif; ?>
            <? if (isset($p['sale']['discount']) && !empty($p['sale']['discount'])): ?>
              <span class="sale"><?=$p['sale']['discount']?>% скидка</span>
            <? endif; ?>
          </a>
        </div>
        <div class="clear"></div>
      </div>

      <table class="t-price" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <? if (isset($p['sale']['ends_at']) && !empty($p['sale']['ends_at'])): ?>
            <td class="td-1">
              <span class="wsn" style="color: red;">Акция действует до</span><br/><b style="color: red;"><?=date('H:i d.m.Y', strtotime($p['sale']['ends_at']));?></b>
            </td>
          <? endif; ?>

          <td class="td-2">
            <? if (isset($p['sale']['discount']) && !empty($p['sale']['discount'])): ?>
              <span class="old-price"><b><?=$p['old_price'];?></b> грн.</span>
              <span class="real-price"  style="color: red;"><b><?=$p['price'];?></b> грн.</span>
            <? else: ?>
              <span class="real-price"><b><?=$p['price'];?></b> грн.</span>
              <br/>
              <? if ( $p['not_in_stock'] == FALSE): ?>
                <span class="in-stock">В наличии</span>
              <? else: ?>
                <span class="not-in-stock">Нет в наличии</span>
              <? endif; ?>
              <? if (!empty($p['rating'])): ?>
                <img src="<?=site_img('stars/star_' . $p['rating'] . '.png');?>" class="stars" alt="Рейтинг <?=$p['rating'];?>" />
              <? endif; ?>
            <? endif; ?>
          </td>

        </tr>
      </table>
    </li>
    <? $counter++; ?>
  <? endforeach; ?>
</ul>

<div class="clear"></div>
<div class="pager-wrap"<?=!isset($_GET['show_all'])?' style="height: 56px;"':'';?>>
  <? if(!isset($_GET['show_all']) && isset($pager) && $pager->haveToPaginate()): ?>
    <?
      $url = shop_url(uri_string()) . '?show_all=1';
      if (!empty($_GET)) {
        $url .= str_replace('?', '&', get_get_params());
      }
    ?>
    <p class="tac">
      <a href="<?=$url;?>">Показать все на одной странице</a>
    </p>
  <? endif; ?>
  <? if(isset($pager)):?>
    <?=$this->load->view('includes/shop/parts/pager', array('pager' => $pager), true);?>
  <? endif; ?>
</div>






