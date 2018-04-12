<div class="popup-sgp">

  <div class="popup-form popup-sgp">
    <h1 class="title">Доставка, гарантия и оплата</h1>
  </div>
  <div class="">
    <div class="html-content">

      <div class="kick-10"></div>

      <div class="dwp-box">
        <div class="lines">
          <span class="a-like js-like shown"><?=$settings['right_part_delivery_title'];?> в любую точку Украины</span>
          <div><?=$settings['right_part_delivery_text_briefly'];?></div>
        </div>

        <div class="lines">
          <span class="a-like js-like shown"><?=$settings['right_part_warranty_title'];?> безусловная 1 месяц</span>
          <div><?=$settings['right_part_warranty_text_briefly'];?></div>
        </div>

        <div class="lines">
          <span class="a-like js-like shown"><?=$settings['right_part_payment_title'];?> наличными и карточками</span>
          <div><?=$settings['right_part_payment_text_briefly'];?></div>
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

          /*
          if ($(window).width() < 660) {
            $('#js-ajaxp-popup').addClass('top10');
          }
          */

        });
      </script>
    </div>
  </div>

</div>

<style>
  .ajaxp-modal.top10 {top: 10px !important;}
</style>