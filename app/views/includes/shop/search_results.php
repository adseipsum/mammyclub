<div class="shop-category-page">
  <div class="r-part">
    <? $this->view("includes/shop/parts/right_part"); ?>
  </div>
  <div class="l-part">
    <div class="inner-b">
      <div class="shop-page">

        <div class="breadcrumbs">
          <a class="crumb first" href="<?=shop_url();?>"><span class="s-1"></span><span class="s-2">Главная</span><span class="s-3"></span></a>
          <span class="crumb active"><span class="s-1"></span><span class="s-2">Результат поиска</span><span class="s-3"></span></span>
        </div>

        <h2 class="title-2">Поиск по запросу: "<?=$query?>". Найдено результатов: <?=isset($pager)?$pager->getNumResults():count($result);?></h2>

        <? if (!empty($result)): ?>
          <ul class="prod-list">
            <? $counter = 1; ?>
            <? foreach ($result as $p): ?>
              <li class="<?= $counter % 2 == 0 ? 'last' : ''; ?> <?= $counter % 3 == 0 ? 'last-3' : ''; ?>">
                <div class="in">
                  <div class="clear"></div>
                  <div class="img-box pr">
                    <div class="bot-line"><a class="name-3" href="<?=shop_url($p['page_url']);?>"><?=highlight($p['name'], explode(' ', $query));?></a></div>
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
                    <td class="td-2">
                        <span class="real-price"><b><?=$p['price'];?></b> грн.</span>
                        <br/>
                        <? if ($p['not_in_stock'] == FALSE): ?>
                          <span class="in-stock">В наличии</span>
                        <? else: ?>
                          <span class="not-in-stock">Под заказ</span>
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

        <? else: ?>
          <p>По вашему запросу ничего не найдено.</p>
        <? endif; ?>
        <div class="clear"></div>

        <div class="pager-wrap">
          <? if(isset($pager)):?>
            <?=$this->load->view('includes/shop/parts/pager', array('pager' => $pager), true);?>
          <? endif; ?>
        </div>

      </div>

    </div>
    <div class="clear"></div>
  </div>

</div>


