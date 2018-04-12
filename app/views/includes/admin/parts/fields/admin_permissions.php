<?
  /**
   * Print permission table.
   * @param $menuItems
   * @param $entity
   * @param string $parent
   */
  function print_permission_table($menuItems, $entity, $parent = null, $loggedInAdmin) {
    foreach ($menuItems as $key => $item) {
      if (is_array($item)) {
        if (!empty($item)) {
          print_permission_table($item, $entity, $key, $loggedInAdmin);
        }
      } else {
        print_permission_table_row($item, $entity, $parent, $loggedInAdmin);
      }
    }
  }

  /**
   * Print permission table row.
   * @param $item
   * @param $entity
   */
  function print_permission_table_row($item, $entity, $parent = null, $loggedInAdmin) {
    if (!$loggedInAdmin['is_itirra']) {
      if ($item == 'settingsgroup' || $item == 'conversion') {
        return;
      }
    }
    $prefix = isset($parent) ? lang('admin.menu.' . $parent . '.name') . "&nbsp;|&nbsp;" : "";
    
    if (!$loggedInAdmin['is_itirra'] && $item == 'settings') {
            print('<tr>
              <td class="action-inner">' . $prefix . lang('admin.menu.' . $item . '.name') . '</td>
              <td><input value="1" class="checkbox exclude" type="checkbox" ' .(strstr($entity['permissions'], $item . "_view") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_view" /></td>
              <td><input value="1" class="checkbox exclude" disabled="disabled" type="checkbox" ' .(strstr($entity['permissions'], $item . "_add") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_add" /></td>
              <td><input value="1" class="checkbox exclude" type="checkbox" ' .(strstr($entity['permissions'], $item . "_edit") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_edit" /></td>
              <td><input value="1" class="checkbox exclude" disabled="disabled" type="checkbox" ' .(strstr($entity['permissions'], $item . "_delete") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_delete" /></td>
            </tr>');
    } else {
      print('<tr>
              <td class="action-inner">' . $prefix . lang('admin.menu.' . $item . '.name') . '</td>
              <td><input value="1" class="checkbox exclude" type="checkbox" ' .(strstr($entity['permissions'], $item . "_view") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_view" /></td>
              <td><input value="1" class="checkbox exclude" type="checkbox" ' .(strstr($entity['permissions'], $item . "_add") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_add" /></td>
              <td><input value="1" class="checkbox exclude" type="checkbox" ' .(strstr($entity['permissions'], $item . "_edit") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_edit" /></td>
              <td><input value="1" class="checkbox exclude" type="checkbox" ' .(strstr($entity['permissions'], $item . "_delete") !== false ? 'checked="checked"' : '') .' name="perm_' . $item . '_delete" /></td>
            </tr>');
    }
  }
?>
<div class="group">
  <label for="<?=$id?>" class="label"><?=$label?></label>
  <table class="permission-table" id="permission-table">
    <tr>
      <td><?=lang('admin.entity')?></td>
      <td class="action"><?=lang('admin.permissions.view')?></td>
      <td class="action"><?=lang('admin.permissions.add')?></td>
      <td class="action"><?=lang('admin.permissions.edit')?></td>
      <td class="action"><?=lang('admin.permissions.delete')?></td>
    </tr>
    <?print_permission_table($menuItems, $entity, null, $loggedInAdmin)?>
  </table>
  <?if (!empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>