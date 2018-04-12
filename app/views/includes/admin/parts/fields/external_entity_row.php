<?
   $externalFieldGroupName = $name . '[' . (isset($count) ? $count : '0') . ']';
   $valueArray = (array)$value;
   $externalFields = $fields[$key]['relation']['fields'];
?>

<? foreach ($externalFields as $extField): ?>

  <?
     $extFieldPath = 'includes/admin/parts/fields/external_entity_parts/' . $extField['type'];
     $extFieldValue = isset($valueArray[$extField['name']]) ? $valueArray[$extField['name']] : NULL;
  ?>

  <?=$this->view($extFieldPath, array('extName' => $extField['name'],
                                      'params' => $extField,
                                      'value' => $extFieldValue,
                                      'externalFieldGroupName' => $externalFieldGroupName), TRUE); ?>

<? endforeach; ?>
