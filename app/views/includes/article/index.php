<div class="breadcrumbs" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
  <a itemprop="url" class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a>
  <span class="crumb active"><span class="s-1"></span><span class="s-2">Статьи</span><span class="s-3"></span></span>
</div>

<? if(!empty($settings['articles_text_top'])): ?>
  <div class="intro-3 html-content">
    <?=$settings['articles_text_top'];?>
  </div>
<? endif; ?>



<? if(!empty($categories)): ?>
  <ul class="article-bar">
    <? $k = 1; ?>
    <? foreach ($categories as $c): ?>
      <li class="item <?= $k%2==0 ? 'last' : '' ?>">
        
        <div class="shadow"></div>
        <div class="l-box">
          <table cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td>
                <a href="<?=site_url($c['page_url']);?>">
                  <img src="<?=site_image_thumb_url('_small', $c['image']);?>" alt="<?=$c['name'];?>" />
                </a>
              </td>
            </tr>
          </table>    
        </div> 
        
        <div class="r-box">
          <div class="inner-b">
            <div class="tac"><a class="main-title" href="<?=site_url($c['page_url']);?>"><?=$c['name'];?></a></div>
            <? $i = 1;?>
              
              <? if(!empty($c['articles'])): ?>
              
                <? 
                   $firstArr = array_slice($c['articles'], 0, 3);
                   $secondArr = array();
                   if(count($c['articles']) > 3) {
                     $secondArr = array_slice($c['articles'], 3);
                   }          
                ?>
                
                <div class="box-1 fl">
                  <ul class="short-article-list">  
                    <? foreach ($firstArr as $a): ?>
                      <li class="item <?=$i%2==0 ? 'item-2' : ''?>">
                        <div class="in">
                          <table cellspacing="0" cellpadding="0" border="0">
                            <tr>
                              <td>
                                <a class="link" href="<?=site_url($a['page_url'])?>"><?=$a['name']?></a>
                              </td>
                            </tr>
                          </table>
                          <? $i++; ?>
                        </div>
                      </li>
                    <? endforeach; ?>
                  </ul>
                </div>
                
                <div class="box-1 fr">
                  <? if(!empty($secondArr)): ?>
                    <ul class="short-article-list">
                      <? foreach ($secondArr as $a): ?>
                        <li class="item <?=$i%2==0 ? 'item-2' : ''?>">
                          <div class="in">
                            <table cellspacing="0" cellpadding="0" border="0">
                              <tr>
                                <td>
                                  <a class="link" href="<?=site_url($a['page_url'])?>"><?=$a['name']?></a>
                                </td>
                              </tr>
                            </table>
                            <? $i++; ?>
                          </div>
                        </li>
                      <? endforeach; ?>
                    </ul>
                  <? endif;?>
                </div>
                
                <div class="clear"></div>
                
                <? if ($i>6): ?>
                  <div class="item last no-back">
                    <div class="in">
                      <a class="link-small" href="<?=site_url($c['page_url']);?>">Все статьи из категории "<?=$c['name'];?>" &rarr;</a>
                    </div>
                  </div>
                <? endif; ?>
              <? endif; ?>
          </div>
          <div class="clear"></div>
        </div>
        
        
        
        
        
        
        
        
        
        
        
        
        <div class="clear"></div>
      </li>
      <? $k ++; ?>
    <? endforeach; ?>

    <?/* $k = 1; ?>
    <? foreach ($categories as $c): ?>
      <? if(!empty($c['image'])): ?>
        <li class="item <?= $k%2==0 ? 'last' : '' ?>">
          <table cellspacing="0" cellpadding="0" border="0" style="height: 220px;">
            <tr>
              <td style="vertical-align: middle; border: 1px solid #fff;">
                <a href="<?=site_url($c['page_url']);?>">
                  <img style="width: 100%;" src="<?=site_image_thumb_url('_medium', $c['image']);?>" alt="<?=$c['name'];?>" />
                </a>
              </td>
            </tr>
          </table>
        </li>
      <? $k++; ?>
      <? endif; ?>
    <? endforeach; */?>
  </ul>
<? endif; ?>

<? if(!empty($settings['articles_text_bottom'])): ?>
  <div class="intro-2 html-content">
    <?=$settings['articles_text_bottom'];?>
  </div>
<? endif; ?>