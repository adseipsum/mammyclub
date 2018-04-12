<ul style="margin: 0px; padding: 0px; list-style: none;">
  <? $counter = 1; ?>
  <? foreach ($products as $p): ?>
      <li style="width: 48.05194805%; border: 2px solid #eee; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin: 0; background: none; padding: 0; line-height: 1; margin-bottom: 10px;<?= $counter % 2 != 0 ? 'float: left;' : 'float: right;'; ?>">
        <div style="padding: 1.3% 1.3% 0;">
          <div style="width: 100%; text-align: center;">
            <a style="display: block; text-decoration: none; width: 100%;" href="<?=$p['url_with_login_key'];?>">
              <? if (!empty($p['image'])): ?>
                <img style="width: 100%;" src="<?=site_image_thumb_url('_medium', $p['image']);?>" alt="<?=htmlspecialchars($p['name']);?>" title="<?=htmlspecialchars($p['name']);?>" />
              <? else: ?>
                <img style="width: 100%;" src="<?=site_img('no_good_icon.png')?>" alt="<?=htmlspecialchars($p['name']);?>" title="<?=htmlspecialchars($p['name']);?>" />
              <? endif; ?>
            </a>
          </div>
          <div style="height: 29px; overflow: hidden; background-color: #fafafa; padding: 7px;">
            <a href="<?=$p['url_with_login_key'];?>"><?=$p['name'];?></a>
          </div>
          <table style="width: 100%; padding: 10px; background-color: #eee; margin: 0px; color: #5dba5d; margin-bottom: 4px;" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td style="border: none; padding: 0; font-size: 9px; line-height: 14px; <?= isset($p['sale']['discount']) && !empty($p['sale']['discount']) ? 'text-align: center;' : '' ?>">
                <a href="<?=$p['url_with_login_key'];?>" style="-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;
                                                                color: #fff !important;
                                                                cursor: pointer;
                                                                display: inline-block;
                                                                line-height: 14px;
                                                                text-align: center;
                                                                text-decoration: none !important;
                                                                background-color: #f0861e;
                                                                border: 1px solid #d0751a;
                                                                box-shadow: 0 0 4px 0 #d0751a inset;
                                                                text-shadow: 1px 1px 1px #d0751a;
                                                                font-size: 12px;
                                                                <?= isset($p['sale']['discount']) && !empty($p['sale']['discount']) ? 'margin: 0 auto;' : '' ?>
                                                                padding: 8px;">Посмотреть в магазине</a>
              </td>
              <? if (!isset($p['sale']['discount']) || empty($p['sale']['discount'])): ?>
                <td style="text-align: right; font-size: 14px; height: 30px;">
                  <span><b><?=$p['price'];?></b> грн.</span>
                </td>
              <? endif; ?>
            </tr>
          </table>
          <div style="clear: both; height: 0; overflow: hidden;"></div>
        </div>
        
        <? if (isset($p['sale']['discount']) && !empty($p['sale']['discount'])): ?>
          <table style="width: 100%; padding: 10px; background-color: #eee; margin: 9px 0px 3%; color: #777;" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <? if (isset($p['sale']['ends_at']) && !empty($p['sale']['ends_at'])): ?>
                <td style="border: none; padding: 0; font-size: 9px; line-height: 14px;">
                  <span class="wsn" style="color: red">Акция действует до<br/><b><?=date('H:i d.m.Y', strtotime($p['sale']['ends_at']));?></b></span>
                </td>
              <? endif; ?>
              <td style="text-align: right; font-size: 14px; height: 30px;">
                <span style="text-decoration: line-through;"><b><?=$p['old_price'];?></b> грн.</span>
                <span style="color: red;"><b><?=$p['price'];?></b> грн.</span>
              </td>
            </tr>
          </table>
        <? endif; ?>
        
      </li>
    <? $counter++; ?>
  <? endforeach; ?>
</ul>

<div style="height: 0px; clear: both; overflow: hidden;"></div>