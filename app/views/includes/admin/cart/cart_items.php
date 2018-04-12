<? if(!empty($entity[$key])): ?>

  <?$trTdStyle = 'border: 1px solid black; padding: 10px;';?>

  <table style="border-collapse: collapse;">
    <tr>
      <td style="<?=$trTdStyle;?>">Название</td>
      <td style="<?=$trTdStyle;?>">Цена</td>
      <td style="<?=$trTdStyle;?>">Кол-во</td>
      <td style="<?=$trTdStyle;?>">Сумма</td>
    </tr>
    <? foreach ($entity[$key] as $cartItem): ?>
      <tr>
        <td style="<?=$trTdStyle;?>"><?=$cartItem['product']['name'];?></td>
        <td style="<?=$trTdStyle;?>"><?=$cartItem['qty'];?></td>
        <td style="<?=$trTdStyle;?>"><?=$cartItem['price'];?> грн</td>
        <td style="<?=$trTdStyle;?>"><?=$cartItem['item_total'];?> грн</td>
      </tr>
    <? endforeach; ?>
  </table>
<? else: ?>
  <p>Содержимое отсутствует...</p>
<? endif; ?>