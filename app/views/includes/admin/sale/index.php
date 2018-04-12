<div class="content default-box">
	<h2 class="title">
		<span class="fl">Загрузить e-mail адреса через csv файл</span>
		<a class="link" style="float: right;"
		   href="<?= admin_site_url('sale/index') ?>"><?= lang('admin.add_edit_back');?></a>
		<div class="clear"></div>
	</h2>
  <?= html_flash_message(); ?>
	<div class="search-box filter-bar">
		<h3>Акция: <?= $sale['name'];?></h3>
		<form action="<?=admin_site_url('sale/add_recipients_via_csv_process')?>" enctype="multipart/form-data" method="post" class="validate">
			<input type="hidden" name="id" value="<?= $sale['id'];?>"/>
			<div class="input-row input-row-new">
				<div class="input-row">
					<label for="file">Файл .csv</label>
					<input id="file" type="file" name="file" class="required"/><br/>
				</div>
			</div>
			<div class="clear"></div>
			<div class="group navform wat-cf">
				<button type="submit">Загрузить</button>
			</div>
		</form>
	</div>
</div>