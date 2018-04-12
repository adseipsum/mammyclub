<div id="footer" class="wrap js-footer">
  <table cellspacing="0" cellpadding="0" border="0">
    <tr>
      <td>2013 - <?=date('Y');?> Mammy club</td>
      <td>
        <ul class="f-list">
          <li><a href="<?=site_url('о-проекте')?>">О проекте</a></li>
          <? if (url_equals('/')): ?>
            <li><a href="<?=site_url('документация');?>">Документация</a></li>
          <? endif; ?>
          <li class="last"><a href="<?=site_url('связаться-с-нами')?>">Связаться с нами</a></li>
        </ul>
      </td>
      <? if(ENV == 'PROD'): ?>
        <? /*
        <td style="width: 88px;">
          <!-- Yandex.Metrika informer -->
          <a href="http://metrika.yandex.ru/stat/?id=23541256&amp;from=informer"
          target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/23541256/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
          style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:23541256,lang:'ru'});return false}catch(e){}"/></a>
          <!-- /Yandex.Metrika informer -->
        </td>
        */ ?>
      <? endif; ?>
    </tr>
  </table>
</div>