<? $langKey = 'admin.add_edit.' . strtolower($fields[$key]['relation']['entity_name']) . '.' . $extName ?>

<label class="label"><?=lang($langKey)?></label>

<input name="<?=$externalFieldGroupName . '[' . $extName . ']'?>"
       type="text"
       class="text-field"
       value="<?=htmlspecialchars($value)?>"
       <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>
/>