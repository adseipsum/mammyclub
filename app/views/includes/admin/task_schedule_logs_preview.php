<div class="content default-box">
  <div class="title">
    <h3>Просмотр логов</h3>
    <a class="link" href="<?= admin_site_url('taskschedule'); ?>"><?= lang('admin.add_edit_back'); ?></a>
  </div>
  <div class="inner">
    <div class="inner">
      <table class="table" style="text-align: center">
        <thead>
        <tr>
          <th style="text-align: center">Операция выполнена</th>
          <th style="text-align: center">Результат</th>
          <th style="text-align: center">Дата</th>
        </tr>
        </thead>
        <tbody>
				<? foreach ($eventLogs as $v) : ?>
          <tr>
            <td><?= $v['is_success'] == 1 ? 'Да' : 'Нет' ?></td>
            <td><?= $v['result'] ?></td>
            <td><?= $v['created_at'] ?></td>
          </tr>
				<? endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<? if (isset($pager)): ?>
	<?= $this->load->view('includes/admin/parts/pager', array('pager' => $pager), true); ?>
<? endif; ?>

