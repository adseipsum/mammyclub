<? // URL VISITED/NOT VISITED ?>
<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>
<? $valueArray = (array)$value;?>

<input class="required" type="text" placeholder="-- Введите URL-адрес --" name="<?=$arrayFieldName?>[url]" value="<?=!empty($valueArray['url'])?$valueArray['url']:""?>" style="width: 30%" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> />

<select name="<?=$arrayFieldName?>[subdomain]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> style="width: 7%;">
  <option value="">-- Не выбрано --</option>
  <option value="shop"<?=isset($valueArray['subdomain']) && $valueArray['subdomain'] == "shop" ? ' selected="selected"' : '' ;?>>shop</option>
</select>

<select class="required" name="<?=$arrayFieldName?>[is_visited]" <?= isset($disabled) && $disabled ? 'disabled="disabled"' : '' ?> style="width: 20%;">
  <option value="1"<?=isset($valueArray['is_visited']) && $valueArray['is_visited'] == "1" ? ' selected="selected"' : '' ;?>>Посетил</option>
  <option value="0"<?=isset($valueArray['is_visited']) && $valueArray['is_visited'] == "0" ? ' selected="selected"' : '' ;?>>Не посетил</option>
</select>