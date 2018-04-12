<? if (isset($filters)) : ?>
  <div class="search-box filter-bar">
    <?
    $filtersIsset = isset($filters) && is_array($filters) && !empty($filters);
    $filtersSingle = array("newsletter" => "", "newsletter_first_year" => "", "site_order.paid" => "", "is_read" => "");
    $filtersFromTo = array("age_of_child" => "", "pregnancyweek_current.number" => "");
    $filtersFromToRange = array("is_read_range" => "");
    $filtersFromToSiteOrder = array("complete_status_date" => "", "total_with_discount" => "");
    $filterType = array("filter_type" => "");
    ?>
    <? if ($filtersIsset) : ?>

      <? if ($filtersSingle): ?>
        <div class="input-row" style="margin-left: 68px">
          <label for="" class="float-left">Тип фильтра:</label>
          <div class="l " style="padding-top: 5px">
            <select class="js-filter-type" name="filter_type" style="width:200px;">
              <option value="">-- Пожалуйста выбирите --</option>
              <option <?= isset($extendedtFilters['filter_type']) && $extendedtFilters['filter_type'] === 'user' ? ' selected="selected"' : ''; ?>
                value="user">Пользователи
              </option>
              <option <?= isset($extendedtFilters['filter_type']) && $extendedtFilters['filter_type'] === 'siteorder' ? ' selected="selected"' : ''; ?>
                value="siteorder">Заказы
              </option>
            </select>
          </div>
        </div>
      <? endif; ?>

      <!--  USER FILTERS  -->
      <div class="js-user-filters">
        <h2 style="display: none;" class="filter-title"><?= lang('admin.filter_title'); ?></h2>
        <? if ($filtersSingle): ?>
          <? foreach ($filtersSingle as $filter_name => $default_filter_value): ?>
            <? $current_filter_values = isset($filter_values[$filter_name]) ? $filter_values[$filter_name] : null; ?>
            <? if (isset($current_filter_values) && is_array($current_filter_values)) : ?>
              <div class="input-row" style="margin-left: 68px">
                <label for="<?= $filter_name ?>"
                       class="float-left"><?= lang("admin.entity_list." . $entityUrlName . ".filter." . $filter_name . "_title") ?>
                  :</label>
                <div class="l " style="padding-top: 5px">
                  <select id="js-option" class="js-read" name="<?= $filter_name ?>" style="width:100px;">
                    <option value=""><?= lang('admin.filter.all'); ?></option>
                    <? foreach ($current_filter_values as $key => $value): ?>
                      <option <?= isset($currentFiltersDB[$filter_name]) && $key == $currentFiltersDB[$filter_name] ? ' selected="selected"' : ''; ?>
                        value="<?= $key; ?>"><?= $value; ?></option>
                    <? endforeach; ?>
                  </select>
                </div>
              </div>
            <? endif; ?>
          <? endforeach; ?>
        <? endif; ?>

        <? if ($filtersFromToRange): ?>
          <? foreach ($filtersFromToRange as $name => $val): ?>
            <? $current_filter_values = isset($filter_values[$name]) ? $filter_values[$name] : null; ?>
            <div class="input-row js-read-range" style="margin-left: 50px">
              <div class="l"
                   style="padding-bottom: 5px; margin-left: 18px"><?= lang("admin.entity_list." . $entityUrlName . ".filter." . $name . "_title") ?>
                :
              </div>
              <div>
                <span class="float-left"><?= lang('admin.filter.from'); ?></span>
                <select name="<?= $name ?>_from" style="width:100px;">
                  <option value=""><?= lang('admin.filter.all'); ?></option>
                  <? foreach ($current_filter_values as $key => $value): ?>
                    <option <?= isset($currentFiltersDB[$name . '_from']) && $value == $currentFiltersDB[$name . '_from'] ? ' selected="selected"' : ''; ?>
                      value="<?= $value ?>"><?= $value; ?></option>
                  <? endforeach; ?>
                </select>
                <span class="float-left"><?= lang('admin.filter.to'); ?></span>
                <select name="<?= $name ?>_to" style="width:100px;">
                  <option value=""><?= lang('admin.filter.all'); ?></option>
                  <? foreach ($current_filter_values as $key => $value): ?>
                    <option <?= isset($currentFiltersDB[$name . '_to']) && $value == $currentFiltersDB[$name . '_to'] ? ' selected="selected"' : ''; ?>
                      value="<?= $value ?>"><?= $value; ?></option>
                  <? endforeach; ?>
                </select>
              </div>
            </div>
          <? endforeach; ?>
        <? endif; ?>

        <? if ($filtersFromTo): ?>
          <? foreach ($filtersFromTo as $name => $val): ?>
            <? $current_filter_values = isset($filter_values[$name]) ? $filter_values[$name] : null; ?>
            <div class="input-row" style="margin-left: 50px">
              <div class="l"
                   style="padding-bottom: 5px; margin-left: 18px"><?= lang("admin.entity_list." . $entityUrlName . ".filter." . $name . "_title") ?>
                :
              </div>
              <div>
                <span class="float-left"><?= lang('admin.filter.from'); ?></span>
                <select name="<?= $name ?>_from" style="width:100px;">
                  <option value=""><?= lang('admin.filter.all'); ?></option>
                  <? foreach ($current_filter_values as $key => $value): ?>
                    <option <?= isset($currentFiltersDB[$name . '_from']) && $value == $currentFiltersDB[$name . '_from'] ? ' selected="selected"' : ''; ?>
                      value="<?= $value ?>"><?= $value; ?></option>
                  <? endforeach; ?>
                </select>
                <span class="float-left"><?= lang('admin.filter.to'); ?></span>
                <select name="<?= $name ?>_to" style="width:100px;">
                  <option value=""><?= lang('admin.filter.all'); ?></option>
                  <? foreach ($current_filter_values as $key => $value): ?>
                    <option <?= isset($currentFiltersDB[$name . '_to']) && $value == $currentFiltersDB[$name . '_to'] ? ' selected="selected"' : ''; ?>
                      value="<?= $value ?>"><?= $value; ?></option>
                  <? endforeach; ?>
                </select>
              </div>
            </div>
          <? endforeach; ?>
        <? endif; ?>
      </div>
      <!--  SITEORDER FILTERS  -->
      <div class="js-siteoder-filters">
        <div class="input-row" style="margin-left: 70px">
          <label for="" class="float-left">Тип коммуникации:</label>
          <div class="l " style="padding-top: 5px">
            <select class="js-communication-type" name="communication_type" style="width:100px;">
              <option
                selected="selected" <?= isset($extendedtFilters['communication_type']) && $extendedtFilters['communication_type'] === 'email' ? ' selected="selected"' : ''; ?>
                value="email">Email
              </option>
              <option <?= isset($extendedtFilters['communication_type']) && $extendedtFilters['communication_type'] === 'phone' ? ' selected="selected"' : ''; ?>
                value="phone">Телефон
              </option>
            </select>
          </div>
        </div>
        <? if (isset($filtersFromToSiteOrder)): ?>
          <? foreach ($filtersFromToSiteOrder as $name => $val): ?>
            <? $current_filter_values = isset($filter_values[$name]) ? $filter_values[$name] : null; ?>

            <div class="input-row" style="margin-left: 50px">
              <div class="l"
                   style="padding-bottom: 5px; margin-left: 18px"><?= lang("admin.entity_list." . $entityUrlName . ".filter." . $name . "_title") ?>
                :
              </div>
              <div>
                <span class="float-left"><?= lang('admin.filter.from'); ?></span>
                <select name="<?= $name ?>_from" style="width:100px;">
                  <option value=""><?= lang('admin.filter.all'); ?></option>
                  <? foreach ($current_filter_values as $key => $value): ?>
                    <option <?= isset($currentFiltersDB[$name . '_from']) && $value == $currentFiltersDB[$name . '_from'] ? ' selected="selected"' : ''; ?>
                      value="<?= $value ?>"><?= $value; ?></option>
                  <? endforeach; ?>
                </select>
                <span class="float-left"><?= lang('admin.filter.to'); ?></span>
                <select name="<?= $name ?>_to" style="width:100px;">
                  <option value=""><?= lang('admin.filter.all'); ?></option>
                  <? foreach ($current_filter_values as $key => $value): ?>
                    <option <?= isset($currentFiltersDB[$name . '_to']) && $value == $currentFiltersDB[$name . '_to'] ? ' selected="selected"' : ''; ?>
                      value="<?= $value ?>"><?= $value; ?></option>
                  <? endforeach; ?>
                </select>
              </div>
            </div>
          <? endforeach; ?>
        <? endif; ?>
      </div>
      <div class="input-row status">
        <input type="checkbox" name="can_deleted"
               value="1"<?= $extendedtFilters['can_deleted'] ? ' checked="checked"' : ''; ?>><span
          style="color: <?= $extendedtFilters['can_deleted'] ? 'red' : ''; ?>"><?= $extendedtFilters['can_deleted'] ? 'Пользователи не удаляются' : 'Запретить удаление пользователей'; ?></span>
      </div>
      <div class="clear"></div>
      <div class="input-row status">
        <div>Добавленно: <span
            style="color: red; font-weight: bold"><?= isset($extendedtFilters['email_qty']) ? $extendedtFilters['email_qty'] : 0; ?></span>
          пользователей.
        </div>
      </div>
      <div class="clear"></div>
      <div class="input-row status">
        <div>Обновленно: <span
            style="color: red"><?= isset($extendedtFilters['updating_date']) ? $extendedtFilters['updating_date'] : '---'; ?></span>
        </div>
      </div>
    <? endif; ?>
  </div>
<? endif; ?>

<style>
  .status {
    font-size: 14px;
    /*font-weight: bolder;*/
    margin-left: 70px;
    text-align: left;
  }
</style>
<script>
    $(document).ready(function () {

        $('.js-user-filters').hide();
        $('.js-siteoder-filters').hide();

        var selectedFilters = $('.js-filter-type');

        if (selectedFilters.val() == 'user') {
            $('.js-user-filters').show();
        }
        if (selectedFilters.val() == 'siteorder') {
            $('.js-siteoder-filters').show();
        }

        selectedFilters.change(function () {
            if (selectedFilters.val() == 'user') {
                $('.js-user-filters').show();
                $('.js-siteoder-filters').hide();
                $('.js-siteoder-filters').find('select').filter(function () {
                    $(this).find('option:selected').prop("selected", false);
                });
            }
            if (selectedFilters.val() == 'siteorder') {
                $('.js-siteoder-filters').show();
                $('.js-user-filters').hide();
                $('.js-user-filters').find('select').filter(function () {
                    $(this).find('option:selected').prop("selected", false);
                });
            }
        })

        $(".js-read").change(function () {
            if ($(this).val() == 1) {
                $(".js-read-range").show();
            } else {
                $(".js-read-range").hide();
            }
        }).trigger('change');
    });
</script>