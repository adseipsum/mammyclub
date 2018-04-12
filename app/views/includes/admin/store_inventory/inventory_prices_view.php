<h2>Обновить цену и себестоимость</h2>
<form action="<?=pager_remove_from_str(current_url()) . '/process_file_prices';?>" method="post" class="form validate" autocomplete="off" enctype="multipart/form-data">

	<div class="group">
		<b>Стандартные настройки</b>
		<p>
			Тип файла: Excel<br>
			Лист: TDSheet<br>
			Идентификация по штрих-коду в колонке: <strong>A</strong><br>
			Колонка с ценой: <strong>D</strong><br>
			Колонка с себестоимостью: <strong>C</strong><br>
		</p>
	</div>
	<input type="hidden" name="standard_store" value="standard_store" />
	<div class="group">
		<label class="label" for="import_file">Файл</label>
		<input class="text-field required" type="file" name="import_file"/>
	</div>

	<div class="group navform wat-cf tac">
		<button class="button" type="submit" name="save" value="1">
			<img src="<?=site_img("admin/icons/tick.png")?>" alt="<?=lang('admin.save');?>"/><?=lang('admin.save');?>
		</button>
	</div>
</form>