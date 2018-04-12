<?
  if (!isset($entity[$key])) {
    $entity[$key] = '';
  }
?>

<? if(!empty($entity[$key])): ?>
  <div class="group array-field">

    <? $rowViewPath = 'includes/admin/parameter_group_single_row';?>

    <style>
      .parameter-groups {margin: 10px 0 0 0;}
      .parameter-groups td {padding: 3px 10px;}
    </style>

    <table class="parameter-groups" cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
      <tr>
        <td><b>Основной параметр</b></td>
<!--        <td style="width: 5%;"><b>Под заказ</b></td>-->
        <td style="width: 5%;"><b>Нет в наличии</b></td>
<!--        <td style="width: 7%;"><b>Наш склад</b></td>-->
<!--        <td style="width: 5%;"><b>Количество</b></td>-->
        <? if(!empty($entity['possible_parameters']['parameter_secondary_id'])) :?>
          <td style="width: 60%;"><b>Доп. параметры не в наличии</b></td>
        <? endif; ?>
        <td style="width: 10%;"><b>Штрих-код</b></td>
        <td style="width: 5%;"><b>Цена</b></td>
        <td><b>Изображение</b></td>
      </tr>
      <? $i = 0; ?>
      <? if(!empty($entity[$key])): ?>
        <? foreach($entity[$key] as $value): ?>
            <?=$this->view($rowViewPath, array('group' => $value, 'count' => $i), TRUE)?>
          <? $i++; ?>
        <? endforeach; ?>
      <? endif; ?>
    </table>

    <p>
      <a id="generateEmptyBarCodes">Сгенерировать штрих-коды</a>
    </p>
  	<div class="clear"></div>
  </div>
<? else: ?>

  <p>Возможные параметры не выбраны..</p>

<? endif; ?>