<? $value = $entity[$key]; ?>

<?
  if(!empty($value)) {
    $vArr = explode(' ', $value);
    $date = $vArr[0];
    $tArr = explode(':', $vArr[1]);
    $hours = $tArr[0];
    $minutes = $tArr[1];
  } else {
    $date = NULL;
    $hours = NULL;
    $minutes = NULL;
  }
?>
<div class="group group-date">
  <label class="label" for="<?=$id?>" onclick="return false;"><?=$label?></label>
  <input id="<?=$id?>"
         type="text"
         class="readonly date <?=isset($params['class']) ? $params['class'] : ''?>"
         name="<?=$name?>[]"
         value="<?=$date;?>"
         <?=$attrs;?> />
  
  <select class="chosen-ignore choose-hour" name="<?=$name?>[]" <?=$attrs;?>>
    <? for($i = 0; $i < 24; $i++): ?>
      <?
        $selected = '';
        if ($hours !== NULL && $i == intval($hours)) {
          $selected = 'selected="selected" ';
        }
      ?>
      <? if($i == 0): ?>
        <option <?=$selected;?> value="00">00</option>
      <? elseif ($i < 10): ?>
        <option <?=$selected;?> value="0<?=$i;?>">0<?=$i;?></option>
      <? else: ?>
        <option <?=$selected;?> value="<?=$i;?>"><?=$i;?></option>
      <? endif; ?>
    <? endfor; ?>
  </select>
  :
  <? $onlyHours = isset($params['only_hours']) && $params['only_hours']; ?>
  <select class="chosen-ignore choose-minutes" name="<?=$name?>[]" <?=$attrs;?> <?= $onlyHours ? 'disabled="disabled"' : ''?>>
    <? if($onlyHours): ?>
      <option <?=$selected;?> value="00">00</option>
    <? else: ?>
      <? for($i = 0; $i < 60; $i++): ?>
        <?
          $selected = '';
          if ($minutes !== NULL && $i == intval($minutes)) {
            $selected = 'selected="selected" ';
          }
        ?>
        <? if($i == 0): ?>
          <option <?=$selected;?> value="00">00</option>
        <? elseif ($i < 10): ?>
          <option <?=$selected;?> value="0<?=$i;?>">0<?=$i;?></option>
        <? else: ?>
          <option <?=$selected;?> value="<?=$i;?>"><?=$i;?></option>
        <? endif; ?>
      <? endfor; ?>
    <? endif; ?>
  </select>
         
         
  <?if ( ! empty($message)) :?><span class="description"><?=$message?></span><?endif?>
</div>