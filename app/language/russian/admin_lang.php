<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//------------------------ GLOBAL> ADMIN ----------------------------------
$lang['admin.title'] = 'Панель Администрирования';
$lang['admin.edit'] = 'Изменить';
$lang['admin.delete'] = 'Удалить';
$lang['admin.delete_image'] = 'Удалить';
$lang['admin.enlarge_image'] = 'Увеличить';
$lang['admin.delete_all'] = 'Удалить все';
$lang['admin.save_order'] = 'Сохранить порядок';
$lang['admin.values'] = 'Значения';
$lang['admin.answers'] = 'Ответы';
$lang['admin.menu_title'] = 'Меню';
$lang['admin.total'] = 'Всего';
$lang['admin.add'] = '+ Добавить';
$lang['admin.per_page'] = 'На странице';
$lang['admin.per_page_array'] = array('25' => '25', '50' => '50', '100' => '100', 'all' => 'Все');
$lang['admin.clear_sort'] = 'Сортировка по умолчанию';
$lang['admin.search.clear_search_sort_filters'] = 'Сбросить фильтры, поиск и сортировки';
$lang['admin.logout'] = 'Выход';
$lang['admin.logged_out_message'] = 'Вы успешно разлогинились';
$lang['admin.add_edit_back'] = '&lt; Назад';
$lang['admin.add_edit_prev'] = "&lt; пред";
$lang['admin.add_edit_next'] = "след &gt";
$lang['admin.add_child'] = 'Добавить вложенную';
$lang['admin.no_children'] = 'Нет элементов.';
$lang['admin.required_description'] = 'обязательное поле';
$lang['admin.editor'] = 'редактор';
$lang['admin.html'] = 'HTML';
$lang['admin.delete_video'] = 'Удалить видео';
$lang['admin.no_items'] = 'Нет элементов.';
$lang['admin.menu.title'] = 'Меню';
$lang['admin.goto_website'] = 'Перейти на сайт';
$lang['admin.footer_copyright'] = '2013г';
$lang['admin.footer_support'] = 'Техническая поддрержка';
$lang['admin.yes'] = 'Да';
$lang['admin.no'] = 'Нет';
$lang['admin.filter.all'] = '-- Все --';
$lang['admin.not_set'] = '-- Не установлено --';
$lang['admin.need_to_login_message'] = 'Для перехода в этот раздел необходимо сначала авторизироваться';
$lang['admin.next.error.no_more'] = 'Следующий элемент отсутствует';
$lang['admin.actions_title'] = 'Действия';
$lang['admin.elements_title'] = 'Элементов';
$lang['admin.filter_title'] = 'Фильтры';
$lang['admin.show_all'] = 'Показать все';
$lang['admin.hide_all'] = 'Скрыть все';
$lang['admin.menu.batch_actions.reset'] = 'Сбросить выбранные элементы';

$lang['admin.pager.first'] = 'Первая';
$lang['admin.pager.prev'] = 'Предыдущая';
$lang['admin.pager.next'] = 'Следующая';
$lang['admin.pager.last'] = 'Последняя';

$lang['admin.export.select_all_required'] = 'Выбрать все обязательные';
$lang['admin.export.file_preview'] = 'Препросмотр файла';

$lang['admin.error.duplicate'] = 'Элемент с уникальным значением "{value}" уже существует.';

$lang['admin.export.filters_processed'] = 'Фильтры экспорта';


$lang['image.upload.messages.file_required'] = 'Необходимо выбрать файл .CSV';
$lang['admin.export.fields'] = 'Поля для экспорта';
$lang['admin.export.fields.description'] = 'Только выбранные поля будут присутствовать в файле эксопрта.';
$lang['admin.export.export'] = 'Экспорт';
$lang['admin.export.export.description'] = 'Результатом экспорта является файл .CSV';
$lang['admin.export'] = 'Экспорт';
$lang['admin.export.select_all'] = 'Выбрать все';
$lang['admin.export.deselect_all'] = 'Отменить все';
$lang['admin.export.select_all_import'] = 'Выбрать только разрешённые для импорта';
$lang['admin.change_root_order'] = 'Изменение порядка элеменов верхнего уровня';
$lang['admin.import'] = 'Импорт';

$lang['admin.messages.batch_update'] = 'Изменения успешно применены. Затронуто элементов: {count}';
$lang['admin.update'] = 'Применить';
$lang['admin.confirm.entities_delete_batch'] = 'Вы уверены, что хотите удалить выбранные элементы?';
$lang['admin.messages.many_delete'] = 'Удаление выполнено успешно';
$lang['admin.view_on_site'] = 'Просмотреть на сайте';
//------------------------ IMPORT > START -----------------------------------

$lang['admin.import.type'] = 'Режим Импорта';
$lang['admin.import.type.description'] = '';
$lang['admin.import.type.label'] = 'Режим: ';
$lang['admin.import.type.add_edit'] = 'Добавление и Редактирование';
$lang['admin.import.type.add_only'] = 'Только Добавление';
$lang['admin.import.type.edit_only'] = 'Только Редактирование';

$lang['admin.import.error.first_column_must_be_id'] = 'Первый столбец должен быть &quot;id&quot;';
$lang['admin.import.error.second_column_must_be_parent_id'] = 'Второй столбец должен быть &quot;Родительская категория&quot;';
$lang['image.upload.messages.file_required'] = 'Необходимо выбрать файл .CSV';
$lang['admin.import.error.on_line'] = 'Ошибка при обработке строки {line}';
$lang['admin.import.file_preview'] = 'Вид файла .CSV';
$lang['admin.import.fields'] = 'Поля для импорта';
$lang['admin.import.fields.filters'] = 'Внимание! На импорт будут наложены следующие фильтры';
$lang['admin.import.fields.filters.description'] = 'ВСЕ импортированные элементы будут иметь вышеуказанные значения.';
$lang['admin.import.file_preview.description'] = 'Поле &quot;id&quot; должно быть первым. Порядок остальных полей не имеет значения.';
$lang['admin.import.fields.description'] = 'Только выбранные поля будут обработаны в файле импорта, остальные будут проигнорированы.';
$lang['admin.import.depend.fields'] = 'Зависимые поля';
$lang['admin.import.depend.description'] = 'Значение этих полей заполнется автоматический если они пустые.';
$lang['admin.import.error.missing_required_fields'] = 'В файле импорта отсутствует обязательный столбец &quot;{fname}&quot;';
$lang['admin.import.totally_depend.fields'] = 'Автоматические поля';
$lang['admin.import.totally_depend.description'] = 'Значение этих полей формируется полностью автоматически и не может быть изменено.';

$lang['admin.import.relation.fields'] = 'Поля через запятую';
$lang['admin.import.relation.description'] = 'Значения следующих полей должны быть заданы через запятую (,) или без запятых, если значение одно.<br/>
																							<b>Если вы укажете значение, а такого элемента не существует - он будет создан автоматически.</b>';


$lang['admin.import.image.fields'] = 'Режим иморта изображений! Файл должен быть .ZIP';
$lang['admin.import.image.description'] = 'Для импорта изображений необходимо загрузить архив (.zip) в котором должен находиться 1 файл .csv и файлы с изображениями.</br>
																					 <b>В архиве не должно быть папок. В .csv файле, в колонке "Изображение" должно быть указано только название файла с картинкой, например "image.jpg".</b>';


$lang['admin.import.settings'] = 'Настройки Импорта';
$lang['admin.import.settings.description'] = '';
$lang['admin.import.ignore_errors'] = 'Игнорировать сообщения об ошибках';
$lang['admin.import.error.falied_to_refresh'] = 'Невозможно отредактировать несуществующий товар. Для добавления, поле id должно быть пустым.';

$lang['admin.import.select_all'] = 'Выбрать все';
$lang['admin.import.deselect_all'] = 'Отменить все';
$lang['admin.import.import'] = 'Импорт';
$lang['admin.import.import.description'] = '';
$lang['admin.import.message.imported'] = 'Импорт произведён успешно. Добавлено: {added}. Изменено: {edited}';
//------------------------ IMPORT > END -----------------------------------

//------------------------ WINDOW > START -----------------------------------
$lang['admin.window.title'] = 'Image Manager';
$lang['admin.window.upload_label'] = 'Upload photo';
$lang['admin.window.create_folder'] = 'Create folder';
$lang['admin.window.image_label'] = 'Image Label';
$lang['admin.window.image_name'] = 'Image Name';
$lang['admin.window.image_size'] = 'Size';
$lang['admin.window.image_width'] = 'Width';
$lang['admin.window.image_height'] = 'Height';
$lang['admin.window.image_created_date'] = 'Created';
$lang['admin.window.image_resize'] = 'Image Resize';
$lang['admin.window.image_resize_width'] = 'Resize Width';
$lang['admin.window.image_resize_height'] = 'Resize Height';
$lang['admin.window.image_delete'] = 'Image Delete';
$lang['admin.window.up'] = 'Up';
$lang['admin.window.button_ok'] = 'OK';
$lang['admin.window.folder_name'] = 'Folder Name';
$lang['admin.window.button_create'] = 'Create';
//------------------------ WINDOW > END -----------------------------------


// Действие кнопки
$lang['admin.save_and_add_new'] = 'Сохранить и добавить новый элемент ';
$lang['admin.save_return_to_list'] = 'Сохранить и вернуться к списку';
$lang['admin.save_and_next'] = "Сохранить и перейти к следующему элементу";
$lang['admin.save_permissions'] = 'Сохранить разрешения ';
$lang['admin.save'] = 'Сохранить';
$lang['admin.save_settings'] = 'Сохранить все настройки веб-сайта';
$lang['admin.cancel'] = 'Отмена';
$lang['admin.array.add_field'] = 'Добавить';
$lang['admin.entity'] = 'Сущность';
$lang['admin.view'] = 'Просмотр';

// Войти
$lang['admin.messages.login_wrong_email_password'] = 'Неправильный Логин или Пароль.';

// Изменение INFO
$lang['admin.change_info'] = 'Изменить информацию';
$lang['admin.change_info.form_title'] = 'Изменить информацию';
$lang['admin.change_info.name'] = 'Имя';
$lang['admin.change_info.email'] = 'Электронная почта';
$lang['admin.change_info.new_password'] = 'Новый пароль';
$lang['admin.change_info.confirm_password'] = 'Еще раз новый пароль';
$lang['admin.change_info.old_password'] = 'Старый пароль';
$lang['admin.change_info.default_redirect'] = 'Раздел по умолчанию';
$lang['admin.messages.please_change_password'] = 'Пожалуйста, измените ваш пароль.';
$lang['admin.messages.info_successfully_changed'] = 'Информация успешно изменена.';
$lang['admin.skip_step'] = 'Перейти к панели администрирования';
$lang['admin.skip_step_replay'] = 'Далее&rarr;';
$lang['admin.change_theme'] = 'Выберите тему:';

// Multipleselect
$lang['admin.multipleselect.please_select'] = 'Пожалуйста, выберите по крайней мере одного элемента';
$lang['admin.multipleselect.move_left'] = 'Удалить';
$lang['admin.multipleselect.move_right'] = 'Добавить';
$lang['admin.multipleselect.select_all'] = 'Выбрать все';
$lang['admin.multipleselect.deselect_all'] = 'Отменить все';

// Подтверждение
$lang['admin.confirm.information_dialog_title'] = 'Информация';
$lang['admin.confirm.dialog_title'] = 'Подтверждение';
$lang['admin.confirm.yes_button'] = 'Да';
$lang['admin.confirm.no_button'] = 'Нет';
$lang['admin.confirm.entity_delete'] = 'Вы уверены, что хотите удалить этот элемент?';
$lang['admin.confirm.entities_delete'] = 'Вы уверены, что хотите удалить все выбранные элементы?';
$lang['admin.confirm.no_items_selected'] = 'Нет элементов, выбранных';

// Удаление подтверждения
$lang['admin.add_edit.image_confirm_delete'] = 'Вы уверены, что хотите удалить это изображение?';
$lang['admin.add_edit.video_confirm_delete'] = 'Вы уверены, что хотите удалить это видео?';
$lang['admin.add_edit.file_confirm_delete'] = 'Вы уверены, что хотите удалить этот файл?';

// Забыли пароль?
$lang['admin.image_not_found'] = 'Изображение не найдено';
$lang['admin.messages.image_delete'] = 'Изображение успешно удалено.';

// E-mail Рассылка
$lang['admin.preview_broadcast'] = 'Отправка';
$lang['admin.view_results'] = 'Результаты';
$lang['admin.broadcast.view_results.not_sent'] = 'Результаты будут доступны после отправки.';
$lang['admin.broadcast.preview.title'] = 'Проверка и отправка';
$lang['admin.broadcast.preview.preview'] = 'Вид письма';
$lang['admin.broadcast.preview.recipents'] = 'Получатели';
$lang['admin.send'] = 'Отправить';
$lang['admin.email_broadcast.no_recipients'] = 'Нет получателей';
$lang['admin.email_broadcast.broadcast_email_sent'] = 'Рассылка успешно отправлена {count} получателям.';
$lang['admin.email_broadcast.cannot_edit_a_sent_broadcast'] = 'Невозможно отредактировать рассылку после отправки.';

$lang['admin.add_edit.broadcast.recipents_list'] = 'Список получателей';
$lang['admin.add_edit.broadcast.recipents_list.description'] = 'перечислите получателей построчно';

$lang['admin.menu.broadcast.name'] = 'Email рассылки';
$lang['admin.entity_list.broadcast.list_title'] = 'Email рассылки';
$lang['admin.search.broadcast.description'] = 'по заголовку';
$lang['admin.entity_list.broadcast.filter.is_sent_title'] = 'Отправлена';
$lang['admin.entity_list.broadcast.filter.sent_date_title'] = 'Дата отправки';

$lang['admin.menu.newpost.name'] = 'Новая почта';
$lang['admin.sync'] = 'Синхронизировать';

$lang['admin.entity_list.broadcast.subject'] = 'Заголовок';
$lang['admin.entity_list.broadcast.recipents_count'] = 'Получателей';
$lang['admin.entity_list.broadcast.is_sent'] = 'Отправлена';
$lang['admin.entity_list.broadcast.sent_date'] = 'Дата отправки';
$lang['admin.entity_list.broadcast.read_count'] = 'Просмотров';
$lang['admin.entity_list.broadcast.link_visited_count'] = 'Переходов';

$lang['admin.add_edit.broadcast.form_title'] = 'Добавить редактировать рассылку';
$lang['admin.add_edit.broadcast.id'] = 'id';
$lang['admin.add_edit.broadcast.subject'] = 'Заголовок';
$lang['admin.add_edit.broadcast.subject.description'] = 'тема письма';
$lang['admin.add_edit.broadcast.text'] = 'Тело';
$lang['admin.add_edit.broadcast.text.description'] = '';
$lang['admin.add_edit.broadcast.is_ajax_layout'] = 'Пустой шаблон';
$lang['admin.add_edit.broadcast.is_ajax_layout.description'] = 'не использывать стандартный шаблон';
$lang['admin.add_edit.broadcast.bcc_email'] = 'Скрытая копия (BCC)';
$lang['admin.add_edit.broadcast.bcc_email.description'] = 'на этот адрес будет отправлена скрытая копия каждого письма из этой рассылки';
$lang['admin.add_edit.broadcast.recipents'] = 'Получатели';
$lang['admin.add_edit.broadcast.recipents.description'] = 'Необходимо загрузить CSV файл с 1 колонкой &quot;Email&quot;';
$lang['admin.add_edit.admin.allowed_ips'] = 'Разрешенные ip адреса';
$lang['admin.add_edit.admin.allowed_ips.description'] = '';
$lang['admin.add_edit.admin.external_crm_id'] = 'ID в RetailCRM';
$lang['admin.add_edit.admin.external_crm_id.description'] = '';

$lang['admin.messages.broadcast.add'] = 'Рассылка успешно добавлена.';
$lang['admin.messages.broadcast.edit'] = 'Рассылка успешно изменена.';
$lang['admin.messages.broadcast.delete'] = 'Рассылка успешно удалена.';
$lang['admin.messages.broadcast.delete_all'] = 'Рассылки удалены.';

$lang['admin.broadcast.view_results.title'] = 'Результаты рассылки';
$lang['admin.broadcast.view_results.recipent'] = 'Получатель';
$lang['admin.broadcast.view_results.is_read'] = 'Прочитал';
$lang['admin.broadcast.view_results.link'] = 'Перешел по ссылке: ';
$lang['admin.broadcast.view_results.link'] = 'Перешел по ссылке: ';

$lang['admin.menu.broadcastrecipent.name'] = 'Получатели';
$lang['admin.entity_list.broadcastrecipent.list_title'] = 'Получатели';
$lang['admin.search.broadcastrecipent.description'] = 'по email';

$lang['admin.entity_list.broadcastrecipent.filter.updated_at_title'] = 'Отправлено';

$lang['admin.search.broadcastrecipent.description'] = 'по email';
$lang['admin.entity_list.broadcastrecipent.updated_at'] = 'Дата отправления';
$lang['admin.entity_list.broadcastrecipent.email'] = 'Email';
$lang['admin.entity_list.broadcastrecipent.is_read'] = 'Прочитано';
$lang['admin.entity_list.broadcastrecipent.broadcast.subject'] = 'Рассылка';

//------------------------ GLOBAL END> --------------------- ---------------

//------------------------ LOGIN > START --------------------- --------------
$lang['admin.login.form_title'] = 'Вход';
$lang['admin.login.login_field'] = 'Логин';
$lang['admin.login.password'] = 'Пароль';
$lang['admin.login.login_action'] = 'Войти';
$lang['admin.login.forgot_password'] = 'Забыли пароль?';
//------------------------ LOGIN> END --------------------- ----------------

//------------------------ ЗАБЫЛИ ПАРОЛЬ> START -------------------- ---------------
$lang['admin.forgot_password.form_title'] = 'Забыли пароль?';
$lang['admin.forgot_password.email_field'] = 'Электронная почта';
$lang['admin.forgot_password.back_to_login'] = 'Вернуться к Логин';
$lang['admin.forgot_password.send'] = 'Отправить';
$lang['admin.messages.password_successfully_sent'] = 'Новый пароль был отправлен на вашу электронную почту.';
$lang['admin.messages.forgot_password_wrong_msg'] = 'Неправильный email.';
$lang['admin.messages.login_wrong_captcha'] = 'Неправильно введена каптча.';
//------------------------ ЗАБЫЛИ ПАРОЛЬ> END -------------------- -----------------

//------------------------ ПОИСК> START --------------------- -------------
$lang['admin.search.search_action'] = 'Поиск';
$lang['admin.search.search_string'] = 'Поиск:';
$lang['admin.search.search_in'] = 'Где:';
$lang['admin.search.search_type'] = 'Тип поиска:';
$lang['admin.search.search_types'] ["starts_with"] = 'начинается с';
$lang['admin.search.search_types'] ["содержит"] = 'содержит';
$lang['admin.search.search_types'] ["ends_with"] = 'и заканчивая';

$lang['admin.filter.from'] = 'От';
$lang['admin.filter.to'] = 'До';
$lang['admin.filter.for'] = 'За';
$lang['admin.filter.today'] = 'сегодня';
$lang['admin.filter.this_week'] = 'эту неделю';
$lang['admin.filter.this_month'] = 'этот месяц';
$lang['admin.filter.cancel'] = 'сбросить';

//------------------------ ПОИСК> END --------------------- ---------------

//------------------------ ADMIN> START --------------------- --------------
$lang['admin.menu.admin.name'] = 'Администраторы';
$lang['admin.entity_list.admin.list_title'] = 'Администраторы';
$lang['admin.entity_list.admin.add'] = '+ добавить администратора';
$lang['admin.entity_list.admin.name'] = 'Имя';
$lang['admin.entity_list.admin.email'] = 'Электронная почта';

$lang['admin.add_edit.admin.form_title'] = 'Добавить/Редактировать администратора';
$lang['admin.add_edit.admin.name'] = 'Имя';
$lang['admin.add_edit.admin.name.description'] = '';
$lang['admin.add_edit.admin.email'] = 'Электронная почта';
$lang['admin.add_edit.admin.email.description'] = '';
$lang['admin.add_edit.admin.password'] = 'Пароль';
$lang['admin.add_edit.admin.password.description'] = '';
$lang['admin.add_edit.admin.permissions'] = 'Права доступа';
$lang['admin.add_edit.admin.permissions.description'] = '';
$lang['admin.add_edit.admin.email_notice'] = 'Получать Email уведомления';
$lang['admin.add_edit.admin.email_notice.description'] = '';

$lang['admin.messages.admin.add'] = 'Администратор успешно добавлен.';
$lang['admin.messages.admin.edit'] = 'Администратор успешно изменен.';
$lang['admin.messages.admin.delete'] = 'Администратор успешно удален.';
$lang['admin.messages.admin.delete_all'] = 'Администраторы удалены.';
$lang['admin.messages.admin.cannot_delete_current'] = 'Текущий Администратор не может быть удален.';
$lang['admin.permissions.view'] = 'Просмотр';
$lang['admin.permissions.add'] = 'Добавить';
$lang['admin.permissions.edit'] = 'Редактировать';
$lang['admin.permissions.delete'] = 'Удалить';
$lang['admin.add_edit.admin.permission_form_title'] = 'Права доступа';
//------------------------ ADMIN> END --------------------- --------------

//------------------------ ### COMMON ### -----------------------------------

require_once APPPATH . "language/russian/admin/common/adminlog.php";
require_once APPPATH . "language/russian/admin/common/settings.php";
require_once APPPATH . "language/russian/admin/common/settingsgroup.php";
$lang['admin.menu.effect.name'] = 'Эффективность';
require_once APPPATH . "language/russian/admin/common/conversionevent.php";
require_once APPPATH . "language/russian/admin/common/conversion.php";

//------------------------ ### PROJECT SPECIFIC ### -----------------------------------

// Require all files in generated
$results = array();
$handler = opendir(APPPATH . "language/russian/admin/generated/");
while ($file = readdir($handler)) {
  if ($file != "." && $file != "..") {
    $results[] = $file;
  }
}
closedir($handler);
foreach ($results as $r) {
  require_once APPPATH . "language/russian/admin/generated/" . $r;
}

