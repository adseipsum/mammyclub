<? if (!$isLoggedIn || ($isLoggedIn && empty($authEntity['pregnancyweek_id']))): ?>
  <div class="tac">    
    <? if ($isLoggedIn == FALSE): ?>
        <a class="def-but orange-but js-subscribe-scroll-link subscribe-but">Подписаться</a>
        <script type="text/javascript">
         $(document).ready(function() {
           $('.js-subscribe-scroll-link').on('click',function (e) {
             e.preventDefault();
             var target = this.hash;
             if ($(window).width() > 660) {
               $target = $('#js-register-bot-form');
             } else {
               $target = $('#js-register-bot-form-mobile');
             }
             $('html, body').stop().animate({
               'scrollTop': $target.offset().top - 10
             }, 900, 'swing', function () {
               //window.location.hash = target;
             });
           }); 
         });
        </script>
    <? else:?>
      <? if (empty($authEntity['pregnancyweek_id'])): ?>
        <a class="def-but orange-but js-subscribe-scroll-link-week subscribe-but">Подписаться</a>
        <script type="text/javascript">
         $(document).ready(function() {
           $('.js-subscribe-scroll-link-week').on('click',function (e) {
             e.preventDefault();
             var target = this.hash;
             $target = $('#js-register-bot-form-week');
             $('html, body').stop().animate({
               'scrollTop': $target.offset().top - 10
             }, 900, 'swing', function () {
               //window.location.hash = target;
             });
           }); 
         });
        </script>
      <? endif; ?>
    <? endif; ?>
  </div>
<? endif; ?>