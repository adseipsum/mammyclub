<form action="<?=(admin_site_url('siteorder/siteorder_restore_process'));?>" method="post" class="form validate" >
	<div class="default-box js-order-send-but">
		<div class="title">
			Восстановить заказ
		</div>
			<p>Укажите Id заказа: <input type="text" name="siteorder_id" required/></p>
	</div>
	<div class="group">
		<button class="button" type="submit" name="save" value="1">Восстановить</button>
	</div>
</form>
