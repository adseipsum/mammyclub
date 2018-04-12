<div class="private-area">

  <? $this->view("includes/private/parts/menu"); ?>

  <?=html_flash_message(); ?>

  <div class="private-def-box">
    <h1 class="title">&quot;Мой малыш&quot;</h1>
    <? if (!empty($broadcasts)): ?>
      <ul class="qa-list">
        <? foreach ($broadcasts as $b): ?>
          <li>
            <a href="<?=site_url($b['article']['page_url']);?>">Статья &quot;<?=$b['name'];?>&quot;</a>
          </li>
        <? endforeach; ?>
      </ul>
    <? endif; ?>

    <style>
      .child_info {margin: 15px 0 0 0;}
      .child_info td {padding: 0 10px 0 0;}
    </style>
    <table cellpadding="0" cellspacing="0" border="0" class="child_info">
      <tr>
        <td>
          <p>Дата рождения ребенка: <?=$authEntity['child_birth_date'];?></p>
        </td>
        <td>
          <p>Пол ребенка: <?=lang('enum.user.child_sex.' . $authEntity['child_sex']);?></p>
        </td>
        <? if(is_not_empty($authEntity['child_name'])): ?>
          <td>
            <p>Имя ребенка: <?=$authEntity['child_name'];?></p>
          </td>
        <? endif; ?>
      </tr>
    </table>

  </div>

</div>