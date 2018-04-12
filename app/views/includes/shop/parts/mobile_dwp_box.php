<div class="dwp-box">
  <div class="lines">
    <span class="a-like js-like"><?=$settings['right_part_delivery_title'];?> бесплатно в любую точку Украины</span> 
    <div style="display: none;"><?=$settings['right_part_delivery_text_briefly'];?></div>
  </div>
  
  <div class="lines">
    <span class="a-like js-like"><?=$settings['right_part_warranty_title'];?> безусловная 1 месяц</span> 
    <div style="display: none;"><?=$settings['right_part_warranty_text_briefly'];?></div>
  </div>
  
  <div class="lines">
    <span class="a-like js-like"><?=$settings['right_part_payment_title'];?> наличными и карточками</span>
    <div style="display: none;"><?=$settings['right_part_payment_text_briefly'];?></div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function() {
    $('.js-like').click(function() {
      if ($(this).hasClass('shown')) {
        $(this).next().hide();
        $(this).removeClass('shown');
      } else {
        $(this).next().show();
        $(this).addClass('shown');
      }
    });
  });
</script>
