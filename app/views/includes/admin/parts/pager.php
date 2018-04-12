<? if($pager->haveToPaginate()): ?>
  <?
    // Internal Pager stuff!
    $pagerRange = $pager->getRange('Sliding', array('chunk' => 15));
    $pages = $pagerRange->rangeAroundPage();
  ?>
  <div class="pagination">
    <ul>
      
      <? if($pager->getLastPage() > 5): ?>
        <!-- The "prev page" and "first page" links -->
        <? if($pager->getPage() != $pager->getFirstPage()): ?>
          <li>
            <a href="<?= (!isset($search_pref_paginator) ? (pager_remove_from_str() . get_get_params()) 
                                                         : search_pager_remove_from_str($search_pref_paginator));?>"><?=$pager->getFirstPage();?></a>
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
          <?if ($pager->getLastPage() <= 5 || ($pager->getLastPage() > 5 && $page != $pager->getFirstPage() && $page != $pager->getLastPage())): ?>
            <? if($page == $pager->getPage()):?>
              <li class="selected"><span><?=$page?></span></li>
            <? else: ?>            
              <? if($page == $pager->getFirstPage()): ?>                           
                <li><a href="<?= (!isset($search_pref_paginator) ? (pager_remove_from_str() . get_get_params()) 
                                                                 : search_pager_remove_from_str($search_pref_paginator))?>"><?=$page?></a></li>
              <? else: ?>
               <li><a href="<?= !isset($search_pref_paginator) ? pager_url($page) 
                                                               : search_pager_url($page, $search_pref_paginator) ?>">
                      <?=$page?>
                   </a>
               </li>                                               
							<? endif; ?>              
            <? endif; ?>
          <? endif; ?>
      <? endforeach;?>
      
      <? if($pager->getLastPage() > 5): ?>
        <!-- The "next page" and "last page" links -->  
        <? if($pager->getPage() != $pager->getLastPage()): ?>
          <li>
            <span class="dotted">...</span>
          </li>   
          <li>
            <a href="<?= !isset($search_pref_paginator) ? pager_url($pager->getLastPage()) 
                                                        : search_pager_url($pager->getLastPage(), $search_pref_paginator) ?>">
            <?=$pager->getLastPage();?></a>
          </li>          
        <? else: ?>
          <li class="selected">
            <span><?=$pager->getLastPage();?></span>
          </li>
        <? endif; ?>  
      <? endif; ?>
    </ul>
  </div>
<? endif; ?>