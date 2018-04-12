<? if(!empty($questions)): ?>
  <ul class="qa-wide-list" id="js-list">

  <? $i = 1;?>
  <? foreach ($questions as $q): ?>
    <li<?=$i==count($questions)?' class="last"':'';?>>
      <span class="date"><?=ago($q['date']);?></span>
      <p class="title">
        <a href="<?=site_url($q['page_url']);?>"><?=$q['name'];?></a></p>
      <p class="desc js-desc">
        <?=truncate(strip_tags($q['content']), 480, '...');?>
      </p>
      <p class="stats">Задал: <?=$q['user']['name'];?><?=$q['comment_count']>0?' <span class="count-answer">' . $q['comment_count'] . '</span>':'';?></p>  </li>
    <? $i++; ?>
  <? endforeach; ?>

  <li class="ajaxContent tac" id="<?=site_url('аджакс-догрузка/' . uri_string() . get_get_params());?>" style="border: none; width: 100%; display: none; position: absolute; bottom: -80px; left: 0;"><img src="<?=site_img('preloader.gif');?>"  alt="loading..." title="loading..." width="160px" height="24px"/></li>

  <? if(count($questions) == $perPage): ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.ajaxContent:first').contentloader({url: $('.ajaxContent:first').attr('id')});
      });
    </script>
  <? endif; ?>

  </ul>
<? endif; ?>

