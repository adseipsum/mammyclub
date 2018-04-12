<div class="view-item">
  
  <table class="item-theme" cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td>
        <h1><?=$article['name'];?></h1>
        <p class="date">Дата публикации: <span><?=convert_date($article['date'], "j F Y", false, true);?></span></p>
      </td>
    </tr>
  </table>
  
  <div class="html-content">
    <div itemprop="description"><?=$article['content'];?></div>
    <? if(!empty($article['author']['name'])):?>
      <p class="author-wrap"><b>Автор:</b> 
        <? if (!empty($article['author']['google_url'])):?>
          <span itemprop="author" class="author"><a href="<?=$article['author']['google_url'];?>"><?=$article['author']['name'];?> в Google+</a></span>
        <? else: ?>  
          <span itemprop="author" class="author"><?=$article['author']['name'];?></span>
        <? endif; ?>
      </p>
    <? endif; ?>
  </div>

</div>

<script type="text/javascript">
    window.print();
</script>