<div class="breadcrumbs">
  <a class="crumb first" href="<?=site_url()?>"><span class="s-1"></span><span class="s-2">Главная</span><span class="s-3"></span></a>
  <a class="crumb" href="<?=site_url('статьи')?>"><span class="s-1"></span><span class="s-2">Статьи</span><span class="s-3"></span></a>
  <span class="crumb active"><span class="s-1"></span><span class="s-2">Результат поиска</span><span class="s-3"></span></span>
</div>

<h2 class="title-2">Поиск по запросу: "<?=$query?>". Найдено результатов: <?=count($result);?></h2>

<? if(!empty($result)): ?>
  <ul class="art-list">
    <? $i = 1;?>
    <? foreach ($result as $a): ?>
      <li<?=$i==count($result)?' class="last"':'';?>>
        <p class="title"><a href="<?=site_url($a['page_url']);?>"><?=highlight($a['name'], $query);?></a></p>
        <div class="i-row">
          <? if($a['comment_count'] > 0): ?>
            <a class="comment js-not-implemented" href="<?=site_url()?>"><?=$a['comment_count'];?> комментариев</a>
          <? endif; ?>
        </div>
        <table class="desc desc-w-img" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <? if(!empty($a['image'])): ?>
              <td class="td-1">
                <a href="<?=site_url($a['page_url']);?>"><img src="<?=site_image_thumb_url('_medium', $a['image']);?>" alt="<?=$a['name'];?>" /></a>
              </td>
            <? endif; ?>
            <td class="td-2">
              <p>
                <?=highlight($a['description'], explode(' ', $query));?>
              </p>
            </td>
          </tr>
        </table>
      </li>
      <? $i++; ?>
    <? endforeach; ?>
  </ul>
<? else:?>
  <p>По вашему запросу ничего не найдено.</p>
<? endif; ?>