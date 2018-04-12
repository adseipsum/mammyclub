<div class="dwp-box" style="margin-top: 15px;">
  
  <? /*
  <div class="lines">
    <span class="a-like js-like">Отзывы<?= ($product['comment_count'] > 0) ? "(" . $product['comment_count'] . ")" : ''; ?></span>
    <div style="display: none;">
      <? $this->view("includes/parts/comments", array('comments' => $comments, 'entityType' => 'Product', 'entityId' => $product['id'])); ?>
    </div>
  </div>
  */?>

  <div class="lines">
    <span class="a-like js-like">Описание</span>
    <div style="display: none;">
      <?=$product['description_short2'];?>
    </div>
  </div>

  <div class="lines">
    <span class="a-like js-like">Характеристики товара</span>
    <div style="display: none;">
      <?=$product['description'];?>
    </div>
  </div>

</div>
