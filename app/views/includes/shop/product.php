<div class="js-service js-product-id-<?=$product['id'];?> js-product-price-<?=$product['price'];?>" style="display: none;"></div>
<div class="js-service-name js-product-name-<?=$product['name'];?> " style="display: none;"></div>
<div class="js-service-category js-product-category-<?=$product['category']['name'];?>" style="display: none;"></div>
<div class="shop-page-wrap">
  <? if(!empty($cartItems)): ?>
    <div class="mobile-basket-wrap"><a class="mobile-basket" href="<?=shop_url('оформить-заказ')?>"><span class="numb"><?=count($cartItems)?></span></a></div>
  <? endif; ?>
  <div class="r-part">
    <? $this->view("includes/shop/parts/right_part"); ?>
  </div>
  <div class="l-part">
    <div class="inner-b">

      <div class="js-product-view product-view shop-page" <?=js_data_fields_product($product);?>>

        <div class="product-breadcrumbs">
          <? $this->view("includes/shop/parts/top_block"); ?>
        </div>

        <?=html_flash_message(); ?>
        <? if(!empty($admin)): ?>
          <div class="flash">
            <span class="close js-close-adm-msg"></span>
            <div class="message notice">
              <p>Вы вошли как администратор <strong><?=$admin['name']?></strong>. <a target="_blank" href="<?=admin_site_url('product/add_edit/' . $product['id']);?>">Редактировать эту страницу в админке</a></p>
            </div>
          </div>
        <? endif; ?>
        <div class="nac">
          <table style="width: 100%;" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="tal">
                <h1 class="name"><?=$product['name'];?></h1>
              </td>
              <td class="tar" style="vertical-align: top;">
                <img src="<?=site_img('stars/star_' . $product['rating'] . '.png');?>" class="stars" alt="Рейтинг <?=$product['rating'];?>" />
                <? if($product['comment_count'] > 0): ?>
                  <a href="<?=shop_url($product['page_url']) . '#comments';?>" class="review-link"><?=number_noun($product['comment_count'], 'review');?></a>
                <? endif; ?>
              </td>
            </tr>
          </table>
          <div class="clear"></div>
        </div>

        <?/*
        <h1 class="name name-2"><?=$product['name'];?></h1>
        */?>

        <? if (isset($product['image']) && !empty($product['image'])): ?>

          <div class="mobile-main-img-box tac">
            <img class="js-mobile-main-img" src="<?=site_image_thumb_url('_huge', $product['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
            <img class="js-mobile-param-img param-img" style="display: none;" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
          </div>
          <!-- New Gallery for Mobile  -->
          <div class="new-gallery-mobile-box">
            <div class="clear"></div>
            <ul class="gm-list js-gm-list">
              <? if (isset($product['image']) && !empty($product['image'])): ?>
                <li class="current">
                  <img class="js-mobile-track-img" src="<?=site_image_thumb_url('_medium', $product['image']);?>" rel="<?=site_image_thumb_url('_huge', $product['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                </li>
              <? endif; ?>
              <? if(!empty($product['images'])): ?>
                <? foreach ($product['images'] as $image): ?>
                  <? if (!empty($image['image'])): ?>
                    <li>
                      <img class="js-mobile-track-img" src="<?=site_image_thumb_url('_medium', $image['image']);?>" rel="<?=site_image_thumb_url('_huge', $image['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                    </li>
                  <? endif; ?>
                <? endforeach; ?>
              <? endif; ?>
            </ul>
            <script type="text/javascript">
              $(document).ready(function() {
                $('.js-mobile-track-img').click(function(){
                  $('.js-gm-list li').removeClass('current');
                  var currentImgRel = $(this).attr('rel');
                  $('.js-mobile-main-img').attr('src', currentImgRel);
                  $('.js-mobile-main-img').show();
                  $('.js-mobile-param-img').hide();
                  $(this).parent().addClass('current');
                });
              });
            </script>
          </div>
          <!-- New Gallery for Mobile  -->

        <? endif; ?>

        <div class="js-carousel-stage carousel-stage">
          <ul>

            <? // Main image for main stage ?>
            <li>
              <div class="img-box js-track-gallery">
                <div class="in">
                  <? if (isset($product['image']) && !empty($product['image'])): ?>
                    <? // Main image ?>

                    <a class="js-fancy-img js-param-img-container" style="display: none; position: absolute; top: 0; z-index: 66;" href="<?=site_img('no_good_icon.png')?>">
                      <img style="width: 100%" src="<?=site_img('no_good_icon.png')?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                    </a>

                    <a class="js-fancy-img" rel="fancy-img-group" href="<?=site_image_url($product['image']); ?>">
                      <img class="js-main-image" src="<?=site_image_thumb_url('_huge', $product['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                      <span class="zoom"><img src="<?=site_img('zoom_icon.png');?>" /></span>
                    </a>


                  <? else: ?>
                    <a class="js-fancy-img" rel="fancy-img-group" href="<?=site_img('no_good_icon.png')?>">
                      <img style="width: 100%" src="<?=site_img('no_good_icon.png')?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                    </a>
                  <? endif; ?>
                  <? if (isset($product['sale']['discount']) && !empty($product['sale']['discount'])): ?>
                    <? if ($product['sale']['discount_type'] == 'percent') : ?>
                      <span class="sale"><?=$product['sale']['discount']?>% скидка</span>
                    <? else : ?>
                      <span class="sale">скидка <?=$product['sale']['discount']?> грн.</span>
                    <? endif; ?>
                  <? endif; ?>
                </div>
              </div>
            </li>

            <? // Additional images for main stage ?>
            <? if(!empty($product['images'])): ?>
              <? foreach ($product['images'] as $image): ?>
                <? if (!empty($image['image'])): ?>
                  <li>
                    <div class="img-box js-track-gallery">
                      <div class="in">
                        <a class="js-fancy-img" rel="fancy-img-group" href="<?=site_image_url($image['image']); ?>">
                          <img src="<?=site_image_thumb_url('_huge', $image['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                          <span class="zoom"><img src="<?=site_img('zoom_icon.png');?>" /></span>
                        </a>
                        <? if (isset($product['sale']['discount']) && !empty($product['sale']['discount'])): ?>
                          <? if ($product['sale']['discount_type'] == 'percent') : ?>
                            <span class="sale"><?=$product['sale']['discount']?>% скидка</span>
                          <? else : ?>
                            <span class="sale">скидка <?=$product['sale']['discount']?> грн.</span>
                          <? endif; ?>
                        <? endif; ?>
                      </div>
                      <div class="mobile-good-price">
                        <span class="real-price"><b class="js-price"><?=$product['price'];?></b> грн.</span>
                      </div>
                    </div>
                  </li>
                <? endif; ?>
              <? endforeach; ?>
            <? endif; ?>

            <? if(!empty($product['parameter_groups'])): ?>

              <? $paramImageCount = 0; ?>

              <? foreach ($product['parameter_groups'] as $group): ?>
                <? if(!empty($group['image'])): ?>
                  <? $paramImageCount++; ?>
                  <li>
                    <div class="img-box js-track-gallery">
                      <div class="in">
                        <a class="js-fancy-img" rel="fancy-img-group" href="<?=site_image_url($group['image']); ?>" param-img="<?=$group['main_parameter_value_id'];?>">
                          <img src="<?=site_image_thumb_url('_huge', $group['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                          <span class="zoom"><img src="<?=site_img('zoom_icon.png');?>" /></span>
                        </a>
                        <? if (isset($product['sale']['discount']) && !empty($product['sale']['discount'])): ?>
                          <? if ($product['sale']['discount_type'] == 'percent') : ?>
                            <span class="sale"><?=$product['sale']['discount']?>% скидка</span>
                          <? else : ?>
                            <span class="sale">скидка <?=$product['sale']['discount']?> грн.</span>
                          <? endif; ?>
                        <? endif; ?>
                      </div>
                      <div class="mobile-good-price">
                        <span class="real-price"><b class="js-price"><?=$product['price'];?></b> грн.</span>
                      </div>
                    </div>
                  </li>
                <? endif; ?>
              <? endforeach; ?>
            <? endif; ?>
          </ul>
          <div class="clear"></div>

          <?
            $imageAmount = 0;
            if (isset($product['image']) && !empty($product['image'])) {
              $imageAmount++;
            }
            if (isset($product['images']) && !empty($product['images'])) {
              $imageAmount += count($product['images']);
            }
            if(is_not_empty($paramImageCount)) {
              $imageAmount += $paramImageCount;
            }
          ?>

          <? if ($imageAmount > 0): ?>

            <? if ($imageAmount > 6): ?>
              <div class="slider pr">

                <? // Slider plugin uses "prev-navigation" and "next-navigation" classes ?>
                <span class="prev action prev-navigation"></span>
                <span class="next action next-navigation"></span>

                <div class="slide-content js-slider">
                  <ul class="gal-list">

                    <? // Main image ?>
                    <? if (isset($product['image']) && !empty($product['image'])): ?>
                      <li class="js-track-gallery">
                        <img mainimgsrc="<?=site_image_thumb_url('_medium', $product['image']);?>" src="<?=site_image_thumb_url('_small_one', $product['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                      </li>
                    <? endif; ?>

                    <? // Additional images ?>
                    <? if (isset($product['images']) && !empty($product['images'])): ?>
                      <? foreach ($product['images'] as $image): ?>
                        <li class="js-track-gallery">
                          <img mainimgsrc="<?=site_image_thumb_url('_medium', $image['image']);?>" src="<?=site_image_thumb_url('_small_one', $image['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                        </li>
                      <? endforeach; ?>
                    <? endif; ?>

                    <? if(!empty($product['parameter_groups'])): ?>
                      <? foreach ($product['parameter_groups'] as $group): ?>
                        <? if(!empty($group['image'])): ?>
                          <li class="js-track-gallery" param-li="<?=$group['main_parameter_value_id'];?>">
                            <img mainimgsrc="<?=site_image_thumb_url('_medium', $group['image']);?>" src="<?=site_image_thumb_url('_small_one', $group['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                          </li>
                        <? endif; ?>
                      <? endforeach; ?>
                    <? endif; ?>

                  </ul>
                </div>
              </div>
            <? else: ?>
              <div class="slider-2">
                <div class="slide-content js-slider">
                  <ul class="gal-list">

                    <? // Main image ?>
                    <? if (isset($product['image']) && !empty($product['image'])): ?>
                      <li class="js-track-gallery">
                        <img mainimgsrc="<?=site_image_thumb_url('_medium', $product['image']);?>" src="<?=site_image_thumb_url('_small_one', $product['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                      </li>
                    <? endif; ?>

                    <? // Additional images ?>
                    <? if (isset($product['images']) && !empty($product['images'])): ?>
                      <? foreach ($product['images'] as $image): ?>
                        <li class="js-track-gallery">
                          <img mainimgsrc="<?=site_image_thumb_url('_medium', $image['image']);?>" src="<?=site_image_thumb_url('_small_one', $image['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                        </li>
                      <? endforeach; ?>
                    <? endif; ?>

                    <? if(!empty($product['parameter_groups'])): ?>
                      <? foreach ($product['parameter_groups'] as $group): ?>
                        <? if(!empty($group['image'])): ?>
                          <li class="js-track-gallery" param-li="<?=$group['main_parameter_value_id'];?>">
                            <img mainimgsrc="<?=site_image_thumb_url('_medium', $group['image']);?>" src="<?=site_image_thumb_url('_small_one', $group['image']);?>" alt="<?=$product['name'];?>" title="<?=$product['name'];?>" />
                          </li>
                        <? endif; ?>
                      <? endforeach; ?>
                    <? endif; ?>
                  </ul>
                </div>
              </div>
            <? endif; ?>
            <div class="clear"></div>
          <? endif; ?>

        </div>

        <div class="info">
          <div class="inner-b">

            <div class="insert-box">

              <? $this->view('includes/shop/parts/product_params'); ?>

              <div class="<?=(isset($product['brand']) && !empty($product['brand']['description'])) ? '' : 'false';?>">

                <div class="small-price-box updated-price-box">
                <? if (isset($product['sale']['ends_at']) && !empty($product['sale']['ends_at'])): ?>
                  <div class="top">Акция заканчивается в <?=date('H:i d.m.Y', strtotime($product['sale']['ends_at']));?></div>
                <? endif; ?>
                  <div class="middle">
                    <table cellspacing="0" cellpadding="0" border="0">
                      <tr>
                        <td>
                          <? if (isset($product['sale']['discount']) && !empty($product['sale']['discount'])): ?>
                            <span class="old-price" style="text-decoration: line-through; color: #777"><b class="js-old-price"><?=$product['old_price'];?></b> грн.</span>
                            <span class="real-price" style="color: red;"><b class="js-price"><?=$product['price'];?></b> грн.</span>
                          <? else: ?>
                            <span class="real-price"><b class="js-price"><?=$product['price'];?></b> грн.</span>
                          <? endif; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <div class="bottom js-stock-wrap">
                    <? if ($product['in_stock_status'] == 'not_in_stock'):?>
                      <span class="not-in-stock">Нет в наличии</span>
                    <? else:?>
                      <? if (empty($product['parameter_groups'])):?>
                        <? if($product['in_stock_status'] == 'in_stock'): ?>
                          <span class="in-stock">Есть на складе</span>
                        <? else: ?>
                          <span class="in-stock"><?=$product['brand']['delivery_time'];?></span>
                        <? endif; ?>
                      <? else: ?>
                        <span class="in-stock js-stock-status js-in-stock-default" style="">Есть в наличии</span>
                        <span class="in-stock js-stock-status js-in-stock" style="display: none">Есть на складе</span>
                        <span class="in-stock js-stock-status js-in-other-stock" style="display: none"><?=$product['brand']['delivery_time'];?></span>
                      <? endif; ?>
                    <? endif;?>
                  </div>

                  <?
                    $buttonUrl = shop_url('добавить-в-корзину/' . $product['id']);
                    if($product['not_in_stock'] == TRUE) {
                      $buttonUrl = shop_url(rtrim($product['category']['page_url'], '/') . '?product_not_in_stock=1');
                    }
                  ?>
                  <a href="<?=$buttonUrl;?>" class="def-but orange-but<?=$product['not_in_stock']==FALSE?' js-add-to-cart':'';?> js-country-<?=$country;?>">Купить</a>
                </div>

              </div>

              <? if (!empty($product['product_code'])): ?>
                <p class="phone-code">Артикул: <?=$product['product_code']?></p>
              <? endif; ?>

            </div>

            <div class="clear"></div>

          </div>
        </div>

        <div class="clear"></div>


        <? if (!empty($product['product_code'])): ?>
          <p class="mobile-phone-code">Артикул: <?=$product['product_code']?></p>
        <? endif; ?>

        <div class="mobile-rvd-box">
          <? $this->view("includes/shop/parts/mobile_rvd_box"); ?>
          <div class="clear"></div>
        </div>

        <div class="mobile-insert-box">
          <? $this->view("includes/shop/parts/mobile_insert_box"); ?>
          <div class="clear"></div>
        </div>

        <div class="mobile-phone-box">
          <? $this->view("includes/shop/parts/order_department_block"); ?>
          <div class="clear"></div>
        </div>

        <div class="mobile-dwp-box">
          <? $this->view("includes/shop/parts/mobile_dwp_box"); ?>
          <div class="clear"></div>
        </div>

        <div class="mobile-category-box">
          <? $this->view("includes/shop/parts/category_menu_block"); ?>
          <div id="js-tall-block" style="height: 0;"></div>
          <div class="clear"></div>
        </div>


        <div class="main-desc-box">
          <table class="tabs-table" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td>
                <ul class="tabs-list js-tabs-list">
                  <li class="js-tab-href-about js-track-tab-about">Описание товара</li>
                  <li class="js-tab-href-description active js-track-tab-description">Характеристики товара</li>
                  <? if (!empty($product['video'])): ?>
                    <li class="js-tab-href-video js-track-tab-video">Видео</li>
                  <? endif; ?>
                </ul>
              </td>
              <td></td>
            </tr>
          </table>

          <div class="js-tab-content" id="js-about" style="display: none;">
            <div class="html-content">
              <?=$product['description_short2'];?>
            </div>
          </div>

          <div class="js-tab-content" id="js-description">
            <div class="html-content">
              <?=$product['description'];?>
            </div>
          </div>

          <? if (!empty($product['video'])): ?>
            <div class="js-tab-content" id="js-video" style="display: none;">
              <div class="video-content js-video-content">
                <?=$product['video'];?>
              </div>
            </div>
          <? endif; ?>
        </div>

        <div class="clear" style="height: 20px;"></div>

        <? $this->view("includes/parts/comments_product", array('comments' => $comments, 'entityType' => 'Product', 'entityId' => $product['id'])); ?>

        <div class="clear" style="height: 20px;"></div>

        <? $this->view("includes/shop/parts/bottom_block"); ?>

      </div>

      <script type="text/javascript">
        $(document).ready(function() {

          $('.js-tabs-list li').click(function() {
            $('.js-tab-content').hide();
            $('.js-tabs-list li').removeClass('active');

            var tabHref = '#js-' + getClassThatStartsWith(this, 'js-tab-href-').replace('js-tab-href-', '');
            $(tabHref).show();
            $(this).addClass('active');
          });

          var videoWidth = $('#js-video').find('iframe').attr('width');
          $('.js-video-content').css('width', videoWidth);

          var url = window.location.hash;
          if (url.length > 0) {
            $('.js-reviews').click();
            gotoId('#comments');
          }

          processProductParamEvents();

          $('.js-fancy-img').fancybox();

          // Process parameter product links
          $('.js-parameter-product-link').change(function() {
            window.location.href = $(this).val();
          });

        });
      </script>
    </div>
    <div class="clear"></div>
  </div>

</div>