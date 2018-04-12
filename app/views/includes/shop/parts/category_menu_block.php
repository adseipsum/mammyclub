<? /*
<div class="subcategory-box">
  <table cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td class="td-1">
        Категории:
      </td>
      <td class="td-2">
        <div class="main">
          <div class="menu js-menu wrapper-demo">
            <? foreach ($categories as $category): ?>
              <div id="cat-id-<?=$category['id'];?>" class="parent-category-link wrapper-dropdown a-like">
                <? if (isset($category['__children']) && !empty($category['__children'])): ?>
                  <?=$category['name'];?>
                  <div>
                    <ul class="dropdown">
                      <? foreach ($category['__children'] as $childCategory): ?>
                        <li<?=(isset($currentCategory) && !empty($currentCategory) && url_equals($childCategory['page_url'])) ? ' class="active"': '' ;?>>
                          <a href="<?=shop_url($childCategory['page_url']);?>"><?=$childCategory['name']?></a>
                        </li>
                      <? endforeach; ?>
                    </ul>
                  </div>
                <? else:?>
                  <a class="top-level-link" href="<?=shop_url($category['page_url']);?>"><?=$category['name'];?></a>
                <? endif; ?>
              </div>
            <? endforeach; ?>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>

<script type="text/javascript">
  $(document).ready(function() {

	function DropDown(el) {
      this.dd = el;
      this.initEvents();
	}

	DropDown.prototype = {
	  initEvents : function() {
		var obj = this;

        obj.dd.on('click', function(event) {
          $('.wrapper-dropdown').removeClass('active');
		  $(this).toggleClass('active');
	      if ($('#js-tall-block').length > 0) {
	        $('#js-tall-block').height($(this).find('.dropdown').height());
	      }
		  event.stopPropagation();
        });
	  }
	}

	<? $categoriesIds = array(); ?>
	<? foreach ($categories as $category): ?>
	  <? if (isset($category['__children']) && !empty($category['__children'])): ?>
	    <? $categoriesIds[] = '#cat-id-' . $category['id']; ?>
	  <? endif; ?>
	<? endforeach; ?>

	var categoriesIds = '<?=implode(', ', $categoriesIds);?>';
	var dd = new DropDown($(categoriesIds));
	categoriesIds = categoriesIds.split(', ');

	for (i in categoriesIds) {
      var maxWidth = 0;
      $(categoriesIds[i]).find('.dropdown a').each(function() {
        var dummyHtml = '<span id="dummy-html" style="margin: 0; padding: 0; font-size: 13px;">' + $(this).text() + '</span>';
        $('body').append(dummyHtml);
        dummyHtml = $('#dummy-html');
        if (maxWidth < dummyHtml.width()) {
          maxWidth = dummyHtml.width();
        }
        dummyHtml.remove();
      });
      $(categoriesIds[i]).find('.dropdown').css('width', maxWidth + 25);
	}

	$('.subcategory-box').outside('click touchstart', function() {
      // all dropdowns
      $('.wrapper-dropdown').removeClass('active');
      if ($('#js-tall-block').length > 0) {
        $('#js-tall-block').height(0);
      }
	});

  });
</script>
*/
?>

<? 
  
  $tempcurrentCategory = array();
  if(isset($currentCategory)) {
    $tempcurrentCategory = $currentCategory;
  }

  $tempProduct = array();
  if(isset($product)) {
    $tempProduct = $product;
  }
  function create_cat_class($category, $currentCategory, $product) {
    $class = '';
    if(url_contains($category['page_url'])) {
      $class = 'active';
    }
    if(url_contains('продукт')) {
      if($category['id'] == $currentCategory['id']) {
        $class = 'active';
      }
      if($category['level'] == 0 && $category['id'] == $currentCategory['root_id'] && $product['category_id'] != $category['id']) {
        $class .= ' open';
      } elseif($category['level'] > 0 && $category['root_id'] == $currentCategory['root_id'] ) {
        if($currentCategory['id'] != $category['id'] && $currentCategory['lft'] > $category['lft'] && $currentCategory['rgt'] < $category['rgt']) {
          $class .= ' open';
        }
      }
    }
    return $class;
  } 
?>

<ul id="js-category-mm" class="nav show-on-mobile">
	<? foreach ($categories as $category): ?>
  	<? if ($category['published'] == TRUE): ?>
  	  <? $class = create_cat_class($category, $tempcurrentCategory, $tempProduct); ?>
    	<li<?=!empty($class)?' class="' . $class . '"':'';?>>
    	  <? if (isset($category['__children']) && !empty($category['__children'])): ?>
          <a>
            <?=$category['name'];?>
            <span class="js-location location-icon" href="<?=shop_url($category['page_url'])?>"></span>
          </a>
          <ul class="sub">
            <? foreach ($category['__children'] as $childCategory): ?>
              <? if ($childCategory['published'] == TRUE): ?>
                <? $class = create_cat_class($childCategory, $tempcurrentCategory, $tempProduct); ?>
                <li<?=!empty($class)?' class="' . $class . '"':'';?>>
                  <a <? if (empty($childCategory['__children'])): ?> href="<?=shop_url($childCategory['page_url']);?>" <? endif;?> >
                    <?=$childCategory['name']?>
                    <? if (!empty($childCategory['__children'])): ?>
                      <span class="js-location location-icon" href="<?=shop_url($childCategory['page_url'])?>"></span>
                    <? endif;?>
                  </a>
                  <? if (!empty($childCategory['__children'])):?>
                    <ul class="sub">
                      <? foreach ($childCategory['__children'] as $ccCategory): ?>
                        <? if ($ccCategory['published'] == TRUE): ?>
                          <li <?=url_contains($ccCategory['page_url']) ? 'class="active"' : ''?>  <? if (url_contains('продукт')): ?><?=$ccCategory['id'] == $product['category_id'] ? 'class="active"' : ''?><? endif; ?> >
                            <a href="<?=shop_url($ccCategory['page_url'])?>">
                              <?=$ccCategory['name']?>
                            </a>
                          </li>
                        <? endif;?>
                      <? endforeach;?>
                    </ul>
                  <? endif;?>
                </li>
              <? endif;?>
            <? endforeach; ?>
          </ul>
        <? else:?>
          <a href="<?=shop_url($category['page_url']);?>"><?=$category['name'];?></a>
          <span class="js-location location-icon" href="<?=shop_url($category['page_url'])?>"></span>
        <? endif; ?>
    	</li>
    <? endif; ?>
 <? endforeach; ?>
</ul>

<script type="text/javascript">
  $(document).ready(function() {
      // Initialize navgoco with default options
      $("#js-category-mm").navgoco({
          caretHtml: '',
          accordion: true,
          openClass: 'open',
          //save: true,
          cookie: {
              name: 'navgoco',
              expires: false,
              path: '/'
          },
          slide: {
              duration: 400,
              easing: 'swing'
          },
          // Add Active class to clicked menu item
          //onClickAfter: active_menu_cb,
      });

      $('.js-location').click(function(){
        window.location.href = $(this).attr("href");
      })
      
  });
</script>