<? if(empty($authEntity['pregnancyweek_id']) && $registerPopupHidden == FALSE): ?>
  <div class="thank-you-box">
    <div class="in">
      <span class="close"></span>
      <table class="info-table" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td class="td-1">
            <h2 class="name">Спасибо за регистрацию!</h2>
            <p class="desc">
              Все зарегистрированные пользователи имеют возможность бесплатно подписаться на нашу уникальную рассылку "Беременность по Неделям".
            </p>
            <a href="<?=site_url('беременность-по-неделям');?>">Узнать больше о рассылке "Беременность по Неделям"</a>
          </td>
          <td class="td-2">

            <div class="subscribe-box">
              <div class="in">
                <form method="post" id="subscribe" action="<?=site_url('подписаться-на-рассылку');?>" class="validate">
                  <label>Ваша текущая неделя беременности*:</label>
                  <div class="input-row">
                    <select name="pregnancy_week" class="required">
                      <option value="">- Выберите неделю -</option>
                      <? foreach($pregnancyWeeks as $w): ?>
                        <option value="<?=$w['id'];?>"><?=$w['number'];?> неделя</option>
                      <? endforeach; ?>
                    </select>
                  </div>
                  <div class="tac">
                    <button type="submit" class="h-but orange-but">Подписаться на рассылку</button>
                  </div>
                </form>
              </div>
            </div>
            
            <div class="mobile-pregn-link tac">
              <a href="<?=site_url('беременность-по-неделям');?>">Узнать больше о рассылке "Беременность по Неделям"</a>
            </div>

          </td>
        </tr>
      </table>
    </div>
  </div>
<? endif; ?>

<script type="text/javascript">
  $(document).ready(function() {
    $('.validate').validate();
  });
</script>