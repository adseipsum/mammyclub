<ul class="prod-list prod-list-short">
  <? $counter = 1; ?>
  <? foreach ($products as $p): ?>
    <li<?= $counter % 2 == 0 ? ' class="last"' : ''; ?>>
      <div class="in">
        <div class="img-box pr tac">
          <div class="bot-line"><a class="name-3" href="<?=shop_url($p['page_url']);?>"><?=$p['name'];?></a></div>
          <a class="link" href="<?=shop_url($p['page_url']);?>">
            <? if (!empty($p['image'])): ?>
              <img src="<?=site_image_thumb_url('_medium', $p['image']);?>" alt="<?=$p['name'];?>" title="<?=$p['name'];?>" />
            <? else: ?>
              <img src="<?=site_img('no_good_icon_cart_tiny.png');?>" alt="<?=$p['name'];?>" title="<?=$p['name'];?>" />
            <? endif; ?>
          </a>
        </div>
        <div class="info fixed-margin">
          <a href="<?=shop_url($p['page_url']);?>"><?=$p['name'];?></a>
        </div>
        <div class="clear"></div>
      </div>
      <table class="t-price" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="td-2">
            <? if (isset($p['sale']) && !empty($p['sale'])): ?>
              <span class="old-price"><b><?=$p['price'];?></b> грн.</span>
              <span class="real-price"><b><?=round($p['price'] - $p['price'] / 100 * $p['sale']['discount'])?></b> грн.</span>
            <? else: ?>
              <span class="real-price"><b><?=$p['price'];?></b> грн.</span>
            <? endif; ?>
          </td>
        </tr>
      </table>
    </li>
    <? $counter++; ?>
  <? endforeach; ?>
</ul>