<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>
<div class="group">
  <? if (!empty($entity[$key])): ?>
    <div id="tabs_<?=str_replace('.', '', $key);?>">
      <ul>
        <? for($i=0; $i < count($entity[$key]); $i++): ?>
          <li><a href="#websiteTabs-<?=$i;?>-<?=$key;?>"><?='Вариант ' . ($i+1);?></a></li>
        <? endfor; ?>
      </ul>
      <? foreach ($entity[$key] as $variantIndex => $data): ?>
        <div id="websiteTabs-<?=$variantIndex;?>-<?=$key;?>">

					<div style="position: absolute; top: 11px; right: 10px;">
  					<a class="popup-link" style="position: relative;" href="<?=admin_site_url(strtolower($entityName) . '/view_variant/' . $entity['id'] . '/' . $variantIndex);?>" target="_blank">Просмотр</a>
            <? if ($variantIndex > 0): ?>
              <a class="popup-link" style="position: relative;" href="<?=admin_site_url(strtolower($entityName) . '/delete_variant/' . $entity['id'] . '/' . $variantIndex);?>" onclick="return confirm('Вы уверены?')">Удалить вариант</a>
            <? endif; ?>
					</div>

          <? foreach ($jsonFields as $f => $params): ?>

            <?
              $jsonBaseKey = $f;
              $secondKey = '';
              if (strpos($f, '.') !== FALSE) {
                list($jsonBaseKey, $secondKey) = explode('.', $f);
              }
            ?>

            <? foreach ($data as $k => $v): ?>

              <? if ($jsonBaseKey != $k) continue; ?>

              <? if (empty($secondKey)): ?>

                <?
                  if ($jsonBaseKey == 'products_variants') {
                    $params['from'] = $data['products_variants_from'];
                  }
                ?>

                <? $this->view("includes/admin/parts/fields/" . $params['type'], array('key' => $k,
                                                                                       'attrs' => '',
                                                                                       'params' => $params,
                                                                                       'entity' => $data,
                                                                                       'entityName' => $entityName,
                                                                                       'name' => $key . '['. $k . ']' . '[' . $variantIndex. ']',
                                                                                       'id' => $key . '_' . $variantIndex. '_' . $k,
                                                                                       'label' => lang("admin.add_edit.$entityName.$key.$f"),
                                                                                       'message' => '')); ?>

              <? else: ?>

                <? foreach ($v as $kk => $vv): ?>
                  <input type="hidden" name="<?=$key . '['. $k . '][' . $kk .'][id]';?>" value="<?=$vv['id'];?>" >
                  <? $this->view("includes/admin/parts/fields/" . $params['type'], array('key' => $secondKey,
                                                                                         'attrs' => '',
                                                                                         'group' => $key . '_' . $k . '_' . $variantIndex. '_' . $vv['id'],
                                                                                         'params' => $params,
                                                                                         'entity' => $vv,
                                                                                         'entityName' => $entityName,
                                                                                         'name' => $key . '['. $k . '][' . $kk .'][' . $secondKey.  '][' . $variantIndex. ']',
                                                                                         'id' => $key . '_' . $k . '_' . $variantIndex. '_' . $vv['id'],
                                                                                         'label' => $vv['name'],
                                                                                         'message' => '')); ?>
                <? endforeach; ?>

              <? endif; ?>

            <? endforeach; ?>

          <? endforeach; ?>

        </div>
      <? endforeach; ?>
    </div>
  <? endif; ?>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabs_<?=$key;?>').tabs();
    });
  </script>
  <div class="clear"></div>
</div>

<div class="group navform wat-cf">
  <button class="button" type="submit" name="save_and_add_new_variant" value="1">
    <img src="<?=site_img("admin/icons/tick.png")?>" />Сохранить и добавить новый вариант
  </button>
</div>