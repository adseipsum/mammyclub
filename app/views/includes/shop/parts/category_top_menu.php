<? if (!empty($categories)): ?>
  <? foreach ($categories as $category): ?>
  	<? if ($category['published'] == TRUE): ?>
  	  <li>
    	  <? if (isset($category['__children']) && !empty($category['__children'])): ?>
          <a href="<?=shop_url($category['page_url'])?>"><?=$category['name'];?></a>
          <ul class="sub">
            <? foreach ($category['__children'] as $childCategory): ?>
              <? if ($childCategory['published'] == TRUE): ?>
                <li>
                  <a href="<?=shop_url($childCategory['page_url']);?>">
                    <?=$childCategory['name']?>
                  </a>
                  <? if (!empty($childCategory['__children'])):?>
                    <ul class="sub sub-2">
                      <? foreach ($childCategory['__children'] as $ccCategory): ?>
                        <? if ($ccCategory['published'] == TRUE): ?>
                          <li>
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
        <? endif; ?>
    	</li>
    <? endif; ?>
  <? endforeach; ?>
<? endif; ?>




<script type="text/javascript">
  $(document).ready(function() {
    $(".flexnav").flexNav();
  });
</script>