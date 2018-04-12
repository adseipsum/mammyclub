<div id="box" class="box">
  <div class="block">
    <h2><?=lang("admin.login.form_title");?></h2>
    <div class="content login">
      <?=html_flash_message();?>
      <form action="<?=site_url("$adminBaseRoute/login");?>" class="form login validate" method="post">
        <div class="group wat-cf">
          <div class="left">
            <label class="label right"><?=lang("admin.login.login_field");?></label>
          </div>
          <div class="right">
            <input type="text" class="text-field required" name="login" tabindex="1"/>
          </div>
        </div>
        <div class="group wat-cf">
          <div class="left">
            <label class="label right"><?=lang("admin.login.password");?></label>
          </div>
          <div class="right">
            <input type="password" class="text-field required" name="password" tabindex="2"/>
          </div>
        </div>

        <div class="group wat-cf">
          <div class="left">
            <label class="label right"><?=lang("admin.login.remember_me");?></label>
          </div>
          <div class="right">
            <input type="checkbox" name="remember" />
          </div>
        </div>

        <? if(isset($captcha_image)): ?>
          <div class="group wat-cf">
            <div class="left">
              <?=$captcha_image;?>
            </div>
            <div class="right">
              <input type="text" class="text-field required" name="captcha" />
            </div>
          </div>
        <? endif; ?>

        <div class="group navform wat-cf">
          <div class="right">
            <button class="button" type="submit">
              <img src="<?=site_img("admin/icons/key.png")?>" alt="<?=lang("admin.login.login_action");?>" />&nbsp;<?=lang("admin.login.login_action");?>
            </button>
            <a class="link" href="<?=site_url("$adminBaseRoute/forgot_password")?>"><?=$lang->line('admin.login.forgot_password');?></a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('form.login').find('input:first').focus();
  });
</script>