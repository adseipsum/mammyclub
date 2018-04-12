<input name="<?=$name?>[]"
       type="text"
       class="text-field <?=isset($params['class']) ? $params['class'] : ''?>"
       value="<?=htmlspecialchars($value)?>"
       <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?>
/>