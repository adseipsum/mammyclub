<ul class="prod-list prod-list-main">
  <? $counter = 1; ?>
  <? foreach ($products as $p): ?>
	    <? if ($p['not_in_stock'] !== FALSE): ?>
		    <? continue ;?>
	    <?endif;?>
      <li class="<?= $counter % 2 == 0 ? 'last' : ''; ?> <?= $counter % 3 == 0 ? 'last-3' : ''; ?>">
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
                <span class="sale"><?=$p['sale']['discount'];?>% скидка</span>
              <? endif; ?>
            </a>
          </div>

        </div>

        <table class="t-price" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td class="td-2">
              <? if (isset($p['sale']['discount']) && !empty($p['sale']['discount'])): ?>
		            <span class="old-price"><b><?=$p['old_price'];?></b> грн.</span>
		            <span class="real-price"  style="color: red;"><b><?=$p['price'];?></b> грн.</span>
              <? else: ?>
              <span class="real-price"><b><?=$p['price'];?></b> грн.</span>
	            <? endif; ?>
              <br/>
              <? if ($p['not_in_stock'] == FALSE): ?>
                <span class="in-stock">В наличии</span>
              <? else: ?>
                <span class="not-in-stock">Нет в наличии</span>
              <? endif; ?>
              <? if (!empty($p['rating'])): ?>
                <img src="<?=site_img('stars/star_' . $p['rating'] . '.png');?>" class="stars" alt="Рейтинг <?=$p['rating'];?>" />
              <? endif; ?>
            </td>
          </tr>
        </table>
      </li>

    <? $counter++; ?>
  <? endforeach; ?>
</ul>