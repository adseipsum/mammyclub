<div class="home-1" style="background: url(<?=(isset($settings['main_page_background_image']) && !empty($settings['main_page_background_image'])) ? $settings['main_page_background_image'] : '';?>) no-repeat; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;">
  <div class="in">

    <div class="wrap">
      <h1 class="slogan"><?=$settings['home_main_title'];?></h1>

      <ul class="tile-list <?if($isLoggedIn == FALSE):?>short<?endif?>">
        <li class="a">
          <a class="js-tile-link" href="<?=site_url('статьи');?>">
            <span class="title">Статьи</span>
            <table cellspacing="0" cellpadding="0" border="0">
              <tr class="tr-1 js-tr-1">
                <td><img src="<?=site_img('tile_1_new.png');?>" alt="Статьи" title="Статьи" /></td>
              </tr>
              <tr class="tr-2 js-tr-2">
                <td>
                  <div class="text">
                    <div class="iner"><?=$settings['home_articles_block'];?></div>
                  </div>
                </td>
              </tr>
            </table>
          </a>
        </li>
        <? if($isLoggedIn == TRUE): ?>
          <li class="b">
            <a class="js-tile-link" href="<?=site_url('консультации');?>">
              <span class="title">Консультации</span>
              <table cellspacing="0" cellpadding="0" border="0">
                <tr class="tr-1 js-tr-1">
                  <td><img src="<?=site_img('tile_2_new.png');?>" alt="Консультации" title="Консультации" /></td>
                </tr>
                <tr class="tr-2 js-tr-2">
                  <td>
                    <div class="text">
                      <div class="iner"><?=$settings['home_questions_block'];?></div>
                    </div>
                  </td>
                </tr>
              </table>
            </a>
          </li>
        <? endif; ?>
        <li class="c">
          <a class="js-tile-link" href="<?=site_url('беременность-по-неделям');?>">
            <span class="title">Рассылка</span>
            <table cellspacing="0" cellpadding="0" border="0">
              <tr class="tr-1 js-tr-1">
                <td><img src="<?=site_img('tile_3_new.png');?>" alt="Рассылка" title="Рассылка" /></td>
              </tr>
              <tr class="tr-2 js-tr-2">
                <td>
                  <div class="text">
                    <div class="iner"><?=$settings['home_broadcast_block'];?></div>
                  </div>
                </td>
              </tr>
            </table>
          </a>
        </li>
        <li class="d">
          <a class="js-tile-link" href="<?=shop_url('');?>">
            <span class="title">Мамин Магазин</span>
            <table cellspacing="0" cellpadding="0" border="0">
              <tr class="tr-1 js-tr-1">
                <td><img src="<?=site_img('tile_4_new.png');?>" alt="Мамин Магазин" title="Мамин Магазин" /></td>
              </tr>
              <tr class="tr-2 js-tr-2">
                <td>
                  <div class="text">
                    <div class="iner"><?=$settings['home_shop_block'];?></div>
                  </div>
                </td>
              </tr>
            </table>
          </a>
        </li>
      </ul>

      <script type="text/javascript">
        $(document).ready(function() {
          $('.js-tile-link').hover(function() {
            $(this).find('.js-tr-1').hide();
            $(this).find('.js-tr-2').show();
          }, function() {
            $(this).find('.js-tr-1').show();
            $(this).find('.js-tr-2').hide();
          });
        });
      </script>

    </div>
    <div class="clear"></div>

    <div class="about-box">
      <div class="wrap">
        <div class="html-content">
          <h1><?=$settings['home_bottom_title'];?></h1>
          <?=$settings['home_bottom_text'];?>
        </div>
      </div>
    </div>

  </div>
</div>

<? if(!empty($team)): ?>
  <div class="home-2">
    <div class="wrap">
      <h2 class="title-line"><span>Наша команда</span></h2>

      <div class="pr">
        <div class="inner-div">
          <ul class="team-list">
            <? $i=1; ?>
            <? foreach ($team as $t): ?>
              <li<?=$i==count($team)?' class="last"':'';?>>
                <div class="in">
                  <div class="top-box">
                    <img class="round" src="<?=site_image_thumb_url('_medium', $t['image']);?>" alt="<?=$t['name'];?>" />
                    <p class="name"><?=$t['name'];?></p>
                    <p class="pos"><?=$t['place'];?></p>
                  </div>
                  <div class="desc">
                    <p><?=$t['description'];?></p>
                  </div>
                </div>
              </li>
              <? $i++; ?>
            <? endforeach; ?>
          </ul>
        </div>
      </div>

      <div class="clear"></div>
    </div>
  </div>
<? endif; ?>


