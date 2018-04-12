<?

  $checkedIds = array();
  if(!empty($entity[$key])) {
    foreach($entity[$key] as $relatedEntity) {
      $checkedIds[] = $relatedEntity['id'];
    }
  }

  function print_tree($nodes, $nameField, $key, $checkedIds, $isEdit) {
    foreach($nodes as $node) { ?>

      <? $isChecked = in_array($node['id'], $checkedIds); ?>
      <? $isLeaf = empty($node['__children']); ?>

      <li class="<?= $isEdit ? 'expanded' : 'collapsed' ?>">
        <input type="checkbox" name="<?=$key?>[<?=$node['id']?>]" value="1" <?= $isChecked ? 'checked="checked"' : '' ?> /><span><?=$node[$nameField]?></span>
      <?
        if(!$isLeaf) { ?>
          <ul>
            <? print_tree($node['__children'], $nameField, $key, $checkedIds, $isEdit) ?>
          </ul>
      <? } ?>
      </li>
    <? }
  }
?>


<div class="group">
  <div class="tree-container">

    <label class="label"><?=lang("admin.add_edit." . $entityName . "." . $key)?></label>

    <? if(isset($fields[$key]['relation']['entity_name']) && (!isset($params['no_add_button']) || !$params['no_add_button'])): ?>
      <a class="popup-link" href="<?=admin_site_url(strtolower($fields[$key]['relation']['entity_name']) . '/add_popup?eid=' . $id)?>" target="_blank" onClick="return showPopup(this.href, 780, 320);"><?=lang('admin.add');?></a>
    <? endif; ?>

    <div class="tree">
      <ul>
        <? print_tree($fields[$key]['options'], $fields[$key]['name_field'], $key, $checkedIds, !empty($entity['id'])); ?>
      </ul>
    </div>

  </div>

  <div class="clear"></div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.tree').tree({
      onCheck: { ancestors: 'checkIfFull', descendants: 'check', node: 'expand' },
      onUncheck: {
        ancestors: 'uncheck'
      }
    });
  });
</script>
