<div class="chzn-container chzn-container-multi" style="width: 100%">
  <? if (empty($entity[$key])): ?>
    <p>Пользователи не выбраны..</p>
  <? else:  ?>
    <a href="<?=admin_site_url(strtolower($entityName) . '/remove_recipients_process/' . $entity['id']);?>" class="popup-link cp"style="top: -21px;">Удалить пользователей из сегмента</a>
    <ul class="chzn-choices">
      <? foreach ($entity[$key] as $u): ?>
        <li class="search-choice"><?=$u['auth_info']['email'];?></li>
      <? endforeach; ?>
    </ul>
    <div class="clear"></div>
  <? endif; ?>
</div>