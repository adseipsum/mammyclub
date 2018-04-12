<? if (!empty($reviews)): ?>
	  <? $i = 1;?>
    <? foreach ($reviews as $r): ?>
      <li class="item <?=$i==count($reviews)?' class="last"':'';?>" id="review-id-<?= $r['id'] ?>">
        <div class="html-content">
					<?= $r['name'] ?>
        </div>
				<? if (!empty($r['user']['name'])): ?>
          <div class="info">
            <span class="name"><?= $r['user']['name'] ?></span>
						<? if (!empty($r['author_pregnancy_week']['name'])): ?>
              <span class="week">(<?= strtolower($r['author_pregnancy_week']['name']) ?>)</span>
						<? endif; ?>
						<? if (!empty($admin)): ?>
              <span style="float: right;">
                 <a target="_blank" href="<?= admin_site_url('pregnancyreview/add_edit/' . $r['id']); ?>">Редактировать в админке</a>
              </span>
						<? endif; ?>
          </div>
				<? endif; ?>
      </li>
	    <? $i++; ?>
		<? endforeach; ?>

<? else: ?>
  <p>Отзывы отсутствуют...</p>
<? endif; ?>



