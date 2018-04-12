<table>
  <thead>
    <tr>
      <th>Товар</th>
      <th>Параметр</th>
    </tr>
  </thead>
  <tbody>
    <? foreach ($products as $product) : ?>
      <tr>
        <td><a href="<?=admin_site_url('product/add_edit/' . $product['id'])?>"><?=$product['name']?></a></td>
        <td></td>
      </tr>
    <? endforeach; ?>

    <? foreach ($parameterGroups as $parameterGroup) : ?>
      <tr>
        <td><a href="<?=admin_site_url('product/add_edit/' . $parameterGroup['product']['id'])?>"><?=$parameterGroup['product']['name']?></a></td>
        <td><?=$parameterGroup['main_parameter_value']['name']?></td>
      </tr>
    <? endforeach; ?>
  </tbody>
</table>