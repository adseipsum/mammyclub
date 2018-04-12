<div class="breadcrumbs">
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2" itemprop="title">Главная</span><span class="s-3"></span></a></span>
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb" href="<?=site_url('статьи')?>"><span class="s-1"></span><span class="s-2" itemprop="title">Статьи</span><span class="s-3"></span></a></span>
  <? if(!empty($parentCategories)): ?>
    <? foreach ($parentCategories as $pc): ?>
      <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="crumb" href="<?=site_url($pc['page_url']);?>"><span class="s-1"></span><span class="s-2" itemprop="title"><?=$pc['name'];?></span><span class="s-3"></span></a></span>
    <? endforeach; ?>
  <? endif; ?>
  <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="crumb active"><span class="s-1"></span><span class="s-2"><?=$category['name'];?></span><span class="s-3"></span></span>
</div>

<? if(!empty($subCategories)): ?>
<div class="subcategory-box">
  <table cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td class="td-1">Подкатегории:</td>
      <td class="td-2">
        <ul class="subcategory-list">
          <? $i = 1;?>
          <? foreach ($subCategories as $c): ?>
            <li itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="<?=site_url($c['page_url']);?>"><span itemprop="title"><?=$c['name'];?></span></a><?=$i!=count($subCategories)?'&nbsp;<span class="slash">/</span>':'';?></li>
            <? $i++; ?>
          <? endforeach; ?>
        </ul>
      </td>
    </tr>
  </table>
</div>
<? endif; ?>

<? if(!empty($category['text_top'])): ?>
  <div class="intro-3 html-content">
    <?=$category['text_top'];?>
  </div>
<? endif; ?>

<?=$this->view('includes/article/parts/article_list', array('articles' => $articles))?>

<? if(!empty($category['text_bottom'])): ?>
  <div class="intro-2 html-content">
    <?=$category['text_bottom'];?>
  </div>
<? endif; ?>
