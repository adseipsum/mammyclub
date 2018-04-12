<? $arrayFieldName = $name . '[' . (isset($count) ? $count : '0') . ']'; ?>

<tr>
  <td>
    <input type="hidden" name="<?=$arrayFieldName;?>[id]" value="<?=$group['id'];?>"  />
    <?=$group['main_parameter_value']['name'];?>
  </td>
<!--  <td>-->
<!--    <input class="readonly" type="checkbox" name="--><?//=$arrayFieldName;?><!--[on_order]" value="1" --><?//=$group['on_order']?' checked="checked"':''?><!-- disabled="disabled"/>-->
<!--  </td>-->
  <td>
    <input type="checkbox" name="<?=$arrayFieldName;?>[not_in_stock]" value="1" <?=$group['not_in_stock']?' checked="checked"':''?> onclick="return false;" />
  </td>
<!--  <td>-->
<!--    <input class="js-product-our-stock" type="checkbox" name="--><?//=$arrayFieldName;?><!--[our_stock]" value="1" --><?//=$group['our_stock']?' checked="checked"':''?><!-- />-->
<!--  </td>-->
<!--  <td>-->
<!--    <input type="text" name="--><?//=$arrayFieldName;?><!--[count]" class="text-field" value="--><?//=!empty($group['count'])?$group['count']:'';?><!--" />-->
<!--  </td>-->
  <? if(!empty($entity['possible_parameters']['parameter_secondary_id'])) :?>
    <td>
      <?=$this->view('includes/admin/parameter_group_multiple_field.php', array('arrayFieldName' => $arrayFieldName), TRUE)?>
    </td>
  <? endif; ?>
  <td>
    <input type="text" name="<?=$arrayFieldName;?>[bar_code]" class="text-field barCodeGen js-parameter-group-bar-code" data-group-id="<?=$group['id'];?>" value="<?=!empty($group['bar_code'])?$group['bar_code']:'';?>" style="width: 85%;"/>
  </td>
  <td>
    <input type="text" name="<?=$arrayFieldName;?>[price]" class="text-field digits" value="<?=!empty($group['price'])?$group['price']:'';?>" />
  </td>
  <td>
    <?if (isset($group['image']) && !empty($group['image'])) :?>
      <a class="zoom" href="<?=site_image_url($group['image'])?>"><img src="<?=site_image_thumb_url('_admin', $group['image'])?>"/></a><br/>
      <a class="confirm" title="<?=lang("admin.add_edit.image_confirm_delete")?>" href="<?=site_url($adminBaseRoute . '/parametergroup/delete_image/' . $group['image']["id"])?>"><?=lang('admin.delete_image')?></a>
    <? else: ?>
      <input type="file" name="<?=$arrayFieldName;?>" />
    <? endif; ?>
  </td>
</tr>