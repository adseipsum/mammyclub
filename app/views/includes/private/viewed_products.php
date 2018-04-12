<div class="private-area">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <? $this->view("includes/private/parts/subscribe-box"); ?>

  <div class="private-def-box">
    <h1 class="title">Просмотренные товары:</h1>
    <? if (!empty($products)): ?>
      <ul class="prod-list prod-list-main prod-list-main-3">
        <? $counter = 1; ?>
        <? foreach ($products as $p): ?>
          <li<?= $counter % 3 == 0 ? ' class="last"' : ''; ?>>
            <div class="in">
              <div class="img-box pr">
                <div class="bot-line">
                  <a class="name-3" href="<?=shop_url($p['page_url']);?>"><?=$p['name'];?></a>
                </div>
                <a class="link" href="<?=shop_url($p['page_url']);?>">
                  <? if (!empty($p['image'])): ?>
                    <img src="<?=site_image_thumb_url('_medium', $p['image']);?>" alt="<?=$p['name'];?>" title="<?=$p['name'];?>" />
                  <? else: ?>
                    <img src="<?=site_img('no_good_icon.png')?>" alt="<?=$p['name'];?>" title="<?=$p['name'];?>" />
                  <? endif; ?>
                </a>
                <div class="review-row">
                  <? if ($p['comment_count'] > 0): ?>
                    <a class="review" href="<?=shop_url($p['page_url'] . '#comments');?>">(<?=$p['comment_count'];?> <?=number_noun($p['comment_count'], 'review', FALSE)?>)</a>
                  <? endif; ?>
                </div>
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
                <td class="td-2"<?= isset($p['sale']) && !empty($p['sale']) ? '' : ''; ?>>
                  <? if (isset($p['sale']['discount']) && !empty($p['sale']['discount'])): ?>
                    <span class="old-price"><b><?=$p['price'];?></b> грн.</span>
                    <span class="real-price" style="color: red;"><b><?=round($p['price'] - $p['price'] / 100 * $p['sale']['discount']);?></b> грн.</span>
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
    <? else: ?>
      <p class="no-data">Вы еще не просмотрели ни одного товара.</p>
    <? endif; ?>
    <div class="clear"></div>
    <div class="tac bottom"><a class="def-but green-but" href="<?=shop_url('');?>">Посетите наш Магазин</a></div>
  </div>

</div>