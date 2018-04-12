<div id="box" class="box change-info-box">
  <div class="block pr">
    <h2><?=$this->lang->line("admin.change_info.form_title");?></h2>
    <a class="step-replay" href="<?=site_url($skipStepUrl)?>"><?=$lang->line('admin.skip_step_replay');?></a>
    <div class="content">
      <?=html_flash_message();?>
      <form action="<?=site_url("$adminBaseRoute/change_info")?>" class="form validate" method="post" autocomplete="off">
        <div class="left-b">
          <div class="group">
            <label class="label" for="name"><?=$lang->line('admin.change_info.name');?></label>
            <input type="text" id="name" class="text-field required" name="name" value="<?=$loggedInAdmin['name'];?>"/>
          </div>
          <div class="group">
            <label class="label" for="email"><?=$lang->line('admin.change_info.email');?></label>
            <input type="text" id="email" class="text-field required" name="email" value="<?=$loggedInAdmin['email'];?>"/>
          </div>
          <div class="group">
            <label class="label" for="email"><?=$lang->line('admin.change_info.default_redirect');?></label>
            <?
              $permissions = $loggedInAdmin['permissions'];
              $defaultLandings = array();
              $permissions = explode('|', $permissions);
              foreach ($permissions as $p) {
                $p = str_replace('_add', '', str_replace('_edit', '', str_replace('_view', '', str_replace('_delete', '', $p))));
                if (!in_array($p, $defaultLandings)) {
                  $defaultLandings[] = $p;
                }
              }
            ?>
            <?
              function print_select_menu($menuItems, $permissions, $parent = null, $selected = null) {
                if (is_array($menuItems)) {
                  foreach ($menuItems as $name => $item) {
                    if (!is_array($item) && in_array($item, $permissions)) {
                      $selectedAttr = (!empty($selected) && ($selected == $item))?'selected="selected"':'';
                      echo '<option value="' . $item . '" ' . $selectedAttr . '>';
                      if ($parent) {
                        if (is_array($parent)) {
                          foreach ($parent as $p) {
                            echo lang("admin.menu." . $p . ".name") . ' - ';
                          }
                        } else {
                          echo lang("admin.menu." . $parent . ".name") . ' - ';
                        }
                      }
                      echo lang("admin.menu." . $item . ".name");
                      echo '</option>';
                    } else {
                      print_select_menu($item, $permissions, $name, $selected);
                    }
                  }
                }
              }
            ?>
            <select class="select chosen-ignore" name="default_redirect">
              <?print_select_menu($menuItems, $defaultLandings, null, $loggedInAdmin['default_redirect']);?>
            </select>
          </div>
          <div class="group">
            <label class="label" for="old_password"><?=$lang->line('admin.change_info.old_password');?></label>
            <input type="password" id="password" class="text-field" name="password"/>
          </div>
          <div class="group">
            <label class="label" for="new_password"><?=$lang->line('admin.change_info.new_password');?></label>
            <input type="password" id="new_password" class="text-field" name="new_password"/>
          </div>
          <div class="group">
            <label class="label" for="password1"><?=$lang->line('admin.change_info.confirm_password');?></label>
            <input type="password" id="password1" equalTo="#new_password" class="text-field" name="password1"/>
          </div>
          <div class="group navform wat-cf">
            <button class="button" type="submit">
              <img src="<?=site_img("admin/icons/key.png")?>" alt="<?=$lang->line('admin.save');?>"/> <?=$lang->line('admin.save');?>
            </button>
            <a class="link link-lh" href="<?=site_url($skipStepUrl)?>"><?=$lang->line('admin.skip_step');?></a>
          </div>
        </div>
        
        <div class="right-b">
          <label class="label"><?=$lang->line('admin.change_theme');?></label>
          <ul class="theme-list">
            <li class="item <?=($loggedInAdmin['theme'] == 'classic')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/classic');?>">
                <img src="<?=site_img('admin/themes/classic.png');?>" />
                <span class="tac">
                  <span for="classic">Classic</span>
                  <input id="classic" name="theme" value="classic" type="radio" <?=($loggedInAdmin['theme'] == 'classic')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            <li class="item <?=($loggedInAdmin['theme'] == 'google')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/google');?>">
                <img src="<?=site_img('admin/themes/google.png');?>" />
                <span class="tac">
                  <span for="google">Google</span>
                  <input id="google" name="theme" value="google" type="radio" <?=($loggedInAdmin['theme'] == 'google')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            <li class="item last-row <?=($loggedInAdmin['theme'] == 'green')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/green');?>">
                <img src="<?=site_img('admin/themes/green.png');?>" />
                <span class="tac">
                  <span for="green">Cyan</span>
                  <input id="green" name="theme" value="green" type="radio" <?=($loggedInAdmin['theme'] == 'green')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            
            <li class="item <?=($loggedInAdmin['theme'] == 'red')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/red');?>">
                <img src="<?=site_img('admin/themes/red.png');?>" />
                <span class="tac">
                  <span for="red">Red</span>
                  <input id="red" name="theme" value="red" type="radio" <?=($loggedInAdmin['theme'] == 'red')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            <li class="item <?=($loggedInAdmin['theme'] == 'blue')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/blue');?>">
                <img src="<?=site_img('admin/themes/blue.png');?>" />
                <span class="tac">
                  <span for="blue">Blue</span>
                  <input id="blue" name="theme" value="blue" type="radio" <?=($loggedInAdmin['theme'] == 'blue')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            <li class="item last-row <?=($loggedInAdmin['theme'] == 'orange')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/orange');?>">
                <img src="<?=site_img('admin/themes/orange.png');?>" />
                <span class="tac">
                  <span for="orange">Orange</span>
                  <input id="orange" name="theme" value="orange" type="radio" <?=($loggedInAdmin['theme'] == 'orange')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            
            <li class="item last <?=($loggedInAdmin['theme'] == 'grey')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/grey');?>">
                <img src="<?=site_img('admin/themes/grey.png');?>" />
                <span class="tac">
                  <span for="grey">Grey</span>
                  <input id="grey" name="theme" value="grey" type="radio" <?=($loggedInAdmin['theme'] == 'grey')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            <li class="item last <?=($loggedInAdmin['theme'] == 'dark')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/dark');?>">
                <img src="<?=site_img('admin/themes/dark.png');?>" />
                <span class="tac">
                  <span for="dark">Dark</span>
                  <input id="dark" name="theme" value="dark" type="radio" <?=($loggedInAdmin['theme'] == 'dark')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            <li class="item last last-row <?=($loggedInAdmin['theme'] == 'chrome')?"selected":"";?>">
              <a class="item-a" href="<?=admin_site_url('admin/change_theme/chrome');?>">
                <img src="<?=site_img('admin/themes/chrome.png');?>" />
                <span class="tac">
                  <span for="chrome">Chrome</span>
                  <input id="chrome" name="theme" value="chrome" type="radio" <?=($loggedInAdmin['theme'] == 'chrome')?'checked="checked"':"";?> />
                </span>
              </a>
            </li>
            
          </ul>
          <div class="clear"></div>
        </div>
        
        <div class="clear"></div>
        
      </form>
    </div>
  </div>
</div>