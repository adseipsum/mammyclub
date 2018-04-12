<? if($pager->haveToPaginate()): ?>  
  <<?=isset($root_tag) ? $root_tag : 'div'?> class="pager-wrap">
    <?
      // Internal Pager stuff!
      $pagerRange = $pager->getRange('Sliding', array('chunk' => 3));
      $pages = $pagerRange->rangeAroundPage();
    ?>
    <div class="inner-div">
      <div class="pagination">
      
        <ul>
          
          <? if(pager_get_page_number() == $pager->getFirstPage()): ?>
            <li>
              <span>&lt;</span>
            </li>
          <? else:?>
            <li>
              <a href="<?=shop_url(str_replace(site_url(), '', pager_url($pager->getPreviousPage())));?>">&lt;</a>
            </li> 
          <? endif; ?>
          
          
          <? if($pager->getLastPage() > 3): ?>
            <!-- The "prev page" and "first page" links -->
            <? if($pager->getPage() != $pager->getFirstPage()): ?>
              <li>
                <a href="<?=shop_url(str_replace(site_url(), '', pager_remove_from_str() . get_get_params()));?>"><?=$pager->getFirstPage();?></a>
              </li>
              <li>
                <span class="dotted">...</span>
              </li>
            <? else:?>
              <li class="selected">
                <span><?=$pager->getFirstPage();?></span>
              </li>
            <? endif; ?>
          <? endif; ?>
          
          
          <!-- Pages links -->
          <? foreach($pages as $page): ?>
              <?if ($pager->getLastPage() <= 3 || ($pager->getLastPage() > 3 && $page != $pager->getFirstPage() && $page != $pager->getLastPage())): ?>
                <? if($page == $pager->getPage()):?>
                  <li class="selected"><span><?=$page?></span></li>
                <? else: ?>
                  <? if($page == $pager->getFirstPage()): ?>
                    <li><a href="<?=shop_url(str_replace(site_url(), '', pager_remove_from_str())) . get_get_params();?>"><?=$page?></a></li>
                  <? else: ?>
                    <li><a href="<?=shop_url(str_replace(site_url(), '', pager_url($page)));?>"><?=$page?></a></li>
                  <? endif; ?>
                  
                <? endif; ?>
              <? endif; ?>
          <? endforeach;?>
          
          
          <? if($pager->getLastPage() > 3): ?>
            <!-- The "next page" and "last page" links -->
            <? if($pager->getPage() != $pager->getLastPage()): ?>
              <li>
                <span class="dotted">...</span>
              </li>
              <li>
                <a href="<?=shop_url(str_replace(site_url(), '', pager_url($pager->getLastPage())));?>"><?=$pager->getLastPage();?></a>
              </li>
            <? else: ?>
              <li class="selected">
                <span><?=$pager->getLastPage();?></span>
              </li>
            <? endif; ?>
          <? endif; ?>
          
          
          <? if(pager_get_page_number() == $pager->getLastPage()): ?>
            <li>
              <span>&gt;</span>
            </li>
          <? else:?>
            <li>
              <a href="<?=shop_url(str_replace(site_url(), '', pager_url($pager->getNextPage())));?>">&gt;</a>
            </li> 
          <? endif; ?>
        
        
        </ul>
      </div>
    </div>
  </<?=isset($root_tag) ? $root_tag : 'div'?>>
  
  
  
  
<? endif; ?>


<?/* trace($pager->getPreviousPage())*/?>