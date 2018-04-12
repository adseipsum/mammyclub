<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------ GLOBAL> ADMIN ----------------------------------
$lang['admin.title'] = 'Administration panel';
$lang['admin.edit'] = 'Edit';
$lang['admin.delete'] = 'delete';
$lang['admin.delete_image'] = 'delete';
$lang['admin.enlarge_image'] = 'Click to enlarge';
$lang['admin.delete_all'] = 'Delete all';
$lang['admin.save_order'] = 'Save order';
$lang['admin.values'] = 'value';
$lang['admin.answers'] = 'Answers';
$lang['admin.menu_title'] = 'Menu';
$lang['admin.total'] = 'Total';
$lang['admin.add'] = '+ Add';
$lang['admin.per_page'] = 'page';
$lang['admin.per_page_array'] = array ('25 '=> '25', '50 '=> '50', '100 '=> '100', 'all' => 'all');
$lang['admin.clear_sort'] = 'Sort by Default';
$lang['admin.search.clear_search_sort_filters'] = 'Reset filters, search and sort';
$lang['admin.logout'] = 'output';
$lang['admin.logged_out_message'] = 'You have successfully logged out';
$lang['admin.add_edit_back'] = '&lt; back';
$lang['admin.add_edit_prev'] = "&lt; prev";
$lang['admin.add_edit_next'] = "next &gt;";
$lang['admin.add_child'] = 'Add attachment';
$lang['admin.no_children'] = 'There are no items.';
$lang['admin.required_description'] = 'required field';
$lang['admin.editor'] = 'editor';
$lang['admin.html'] = 'HTML';
$lang['admin.delete_video'] = 'Delete the video';
$lang['admin.no_items'] = 'There are no items.';
$lang['admin.menu.title'] = 'Menu';
$lang['admin.goto_website'] = 'Site';
$lang['admin.footer_copyright'] = 'Hello 2012';
$lang['admin.footer_support'] = 'Technical poddrerzhka';
$lang['admin.yes'] = 'Yes';
$lang['admin.no'] = 'none';
$lang['admin.filter.all'] = '- All -';
$lang['admin.not_set'] = '- not identified -';
$lang['admin.need_to_login_message'] = 'To go to this section, you must first login';
$lang['admin.next.error.no_more'] = 'The next element is missing';
$lang['admin.actions_title'] = 'Actions';
$lang['admin.elements_title'] = 'element';
$lang['admin.filter_title'] = 'filter';

$lang['admin.pager.first'] = 'first';
$lang['admin.pager.prev'] = 'Previous';
$lang['admin.pager.next'] = 'Next';
$lang['admin.pager.last'] = 'last';

$lang['admin.export.select_all_required'] = 'Select all the required';
$lang['admin.export.file_preview'] = 'file, arrows';

$lang['admin.error.duplicate'] = 'element with a unique value "{value}" already exists.';

$lang['admin.export.filters_processed'] = 'Export filters';


$lang['image.upload.messages.file_required'] = 'You must select a file. CSV';
$lang['admin.export.fields'] = 'The fields for export';
$lang['admin.export.fields.description'] = 'Only the selected fields will be present in the file eksoprta.';
$lang['admin.export.export'] = 'Export';
$lang['admin.export.export.description'] = 'The result is a file export. CSV';
$lang['admin.export'] = 'Export';
$lang['admin.export.select_all'] = 'Select All';
$lang['admin.export.deselect_all'] = 'Remove all';
$lang['admin.export.select_all_import'] = 'Select only allowed to import';
$lang['admin.change_root_order'] = 'Change the order of the top-level categories';
$lang['admin.import'] = 'Import';

$lang['admin.messages.batch_update'] = 'Changes applied successfully. Affected elements: {count} ';
$lang['admin.update'] = 'Apply';
$lang['admin.confirm.entities_delete_batch'] = 'Are you sure you want to remove the selected items?';
$lang['admin.messages.many_delete'] = 'removal was successful';
$lang['admin.view_on_site'] = 'View Online';
// ------------------------ IMPORT> START -----------------------------------

$lang['admin.import.type'] = 'Import mode';
$lang['admin.import.type.description'] ='';
$lang['admin.import.type.label'] = 'mode';
$lang['admin.import.type.add_edit'] = 'Add and Edit';
$lang['admin.import.type.add_only'] = 'Only add';
$lang['admin.import.type.edit_only'] = 'Only editing';

$lang['admin.import.error.first_column_must_be_id'] = 'The first column should be "id"';
$lang['admin.import.error.second_column_must_be_parent_id'] = 'The second column should be "Parent Category"';
$lang['image.upload.messages.file_required'] = 'You must select a file. CSV';
$lang['admin.import.error.on_line'] = 'Error processing line {line}';
$lang['admin.import.file_preview'] = 'file type. CSV';
$lang['admin.import.fields'] = 'Fields to import';
$lang['admin.import.fields.filters'] =' Warning! Will be imposed on imports from these filters';
$lang['admin.import.fields.filters.description'] = 'all imported items will be as defined above.';
$lang['admin.import.file_preview.description'] = 'The field "id" must be the first. The order of the rest of the fields does not matter. ';
$lang['admin.import.fields.description'] = 'Only the selected fields will be processed in the import file, the rest will be ignored.';
$lang['admin.import.depend.fields'] = 'dependent field';
$lang['admin.import.depend.description'] = 'The value of these fields zapolnetsya automatic if it is empty.';
$lang['admin.import.error.missing_required_fields'] = 'The file import missing the required column "{fname}"';
$lang['admin.import.totally_depend.fields'] = 'Auto Fields';
$lang['admin.import.totally_depend.description'] = 'These fields are generated automatically and can not be changed.';

$lang['admin.import.relation.fields'] = 'fields separated by commas';
$lang['admin.import.relation.description'] = 'The values ​​of the following fields must be specified with a comma (,) or without commas, if the value of one. <br/>
<b> If you specify the value as an element does not exist - it is created automatically. </ b> ';


$lang['admin.import.image.fields'] =' mode imorta images! The file must be. ZIP ';
$lang['admin.import.image.description'] = 'To import images, you must download an archive (. Zip) which must be one. Csv file and picture files. </ Br>
<b> The archive should not be a folder. In. Csv file, under "Image" should be specified only the file name to the picture, such as "image.jpg". </ B> ';


$lang['admin.import.settings'] = 'Import Settings';
$lang['admin.import.settings.description'] ='';
$lang['admin.import.ignore_errors'] = 'Ignore errors';
$lang['admin.import.error.falied_to_refresh'] = 'You can not edit a non-existent product. To add, the id field should be blank. ';

$lang['admin.import.select_all'] = 'Select All';
$lang['admin.import.deselect_all'] = 'Remove all';
$lang['admin.import.import'] = 'Import';
$lang['admin.import.import.description'] ='';
$lang['admin.import.message.imported'] = 'Import was successful. Added: {added}. Changed: {edited} ';
// ------------------------ IMPORT> END -----------------------------------

// ------------------------ WINDOW> START -----------------------------------
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
// ------------------------ WINDOW> END -----------------------------------


// The button
$lang['admin.save_and_add_new'] = 'Save and add a new item';
$lang['admin.save_return_to_list'] = 'Save and return to the list';
$lang['admin.save_and_next'] = "Save and go to the next item";
$lang['admin.save_permissions'] = 'Save permissions';
$lang['admin.save'] = 'Save';
$lang['admin.save_settings'] = 'Save the settings web site';
$lang['admin.cancel'] = 'Cancel';
$lang['admin.entity'] = 'Entity';
$lang['admin.view'] = 'View';

// Log in
$lang['admin.messages.login_wrong_email_password'] = 'Invalid Username or Password.';

// Change INFO
$lang['admin.change_info'] = 'Edit details';
$lang['admin.change_info.form_title'] = 'Edit details';
$lang['admin.change_info.name'] = 'Name';
$lang['admin.change_info.email'] = 'E-mail';
$lang['admin.change_info.new_password'] = 'New password';
$lang['admin.change_info.confirm_password'] = 'new password again';
$lang['admin.change_info.old_password'] = 'Old password';
$lang['admin.change_info.default_redirect'] = 'Default Tab';
$lang['admin.messages.please_change_password'] = 'Please change your password.';
$lang['admin.messages.info_successfully_changed'] = 'information has been successfully changed.';
$lang['admin.skip_step'] = 'Go to the admin';
$lang['admin.skip_step_replay'] = 'Next →';
$lang['admin.change_theme'] = 'Please select:';

// Multipleselect
$lang['admin.multipleselect.please_select'] = 'Please select at least one item';
$lang['admin.multipleselect.move_left'] = 'delete';
$lang['admin.multipleselect.move_right'] = 'Add';
$lang['admin.multipleselect.select_all'] = 'Select All';
$lang['admin.multipleselect.deselect_all'] = 'Remove all';

// Confirm
$lang['admin.confirm.information_dialog_title'] = 'Information';
$lang['admin.confirm.dialog_title'] = 'Confirmation';
$lang['admin.confirm.yes_button'] = 'Yes';
$lang['admin.confirm.no_button'] = 'none';
$lang['admin.confirm.entity_delete'] = 'Are you sure you want to delete this item?';
$lang['admin.confirm.entities_delete'] = 'Are you sure you want to delete the selected items?';
$lang['admin.confirm.no_items_selected'] = 'No items selected';

// Delete Confirmation
$lang['admin.add_edit.image_confirm_delete'] = 'Are you sure you want to delete this picture?';
$lang['admin.add_edit.video_confirm_delete'] = 'Are you sure you want to delete this video?';
$lang['admin.add_edit.file_confirm_delete'] = 'Are you sure you want to delete this file?';

// Forgot your password?
$lang['admin.image_not_found'] = 'Image not found';
$lang['admin.messages.image_delete'] = 'image was successfully removed.';

// E-mail Newsletter
$lang['admin.preview_broadcast'] = 'Send';
$lang['admin.view_results'] = 'results';
$lang['admin.broadcast.view_results.not_sent'] = 'The results will be available after the service.';
$lang['admin.broadcast.preview.title'] = 'check and send';
$lang['admin.broadcast.preview.preview'] = 'View message';
$lang['admin.broadcast.preview.recipents'] = 'file with the recipients';
$lang['admin.send'] = 'Send';
$lang['admin.email_broadcast.no_recipients'] = 'No recipients';
$lang['admin.email_broadcast.broadcast_email_sent'] = 'Subscribe successfully sent {count} recipients.';
$lang['admin.email_broadcast.cannot_edit_a_sent_broadcast'] = 'You can not edit the newsletter after dispatch.';

$lang['admin.add_edit.broadcast.recipents_list'] = 'Recipient List';
$lang['admin.add_edit.broadcast.recipents_list.description'] = 'list of recipients by line';

$lang['admin.menu.broadcast.name'] = 'Email list';
$lang['admin.entity_list.broadcast.list_title'] = 'Email list';
$lang['admin.search.broadcast.description'] = 'Title';
$lang['admin.entity_list.broadcast.filter.is_sent_title'] = 'Sent';
$lang['admin.entity_list.broadcast.filter.sent_date_title'] = 'Date Sent';

$lang['admin.entity_list.broadcast.subject'] = 'Title';
$lang['admin.entity_list.broadcast.recipents_count'] = 'Recipients';
$lang['admin.entity_list.broadcast.is_sent'] = 'Sent';
$lang['admin.entity_list.broadcast.sent_date'] = 'Date Sent';
$lang['admin.entity_list.broadcast.read_count'] = 'views';
$lang['admin.entity_list.broadcast.link_visited_count'] = 'transition';

$lang['admin.add_edit.broadcast.form_title'] = 'Add to edit list';
$lang['admin.add_edit.broadcast.id'] = 'id';
$lang['admin.add_edit.broadcast.subject'] = 'Title';
$lang['admin.add_edit.broadcast.subject.description'] = 'subject line';
$lang['admin.add_edit.broadcast.text'] = 'body';
$lang['admin.add_edit.broadcast.text.description'] ='';
$lang['admin.add_edit.broadcast.is_ajax_layout'] = 'blank template';
$lang['admin.add_edit.broadcast.is_ajax_layout.description'] = 'no MĀ standard template';
$lang['admin.add_edit.broadcast.bcc_email'] = 'blind carbon copy (BCC)';
$lang['admin.add_edit.broadcast.bcc_email.description'] = 'to this address will be sent a blind copy of every message from this list';
$lang['admin.add_edit.broadcast.recipents'] = 'Recipients';
$lang['admin.add_edit.broadcast.recipents.description'] = 'You need to download a CSV file with 1 column "Email"';

$lang['admin.messages.broadcast.add'] = 'Subscribe successfully added.';
$lang['admin.messages.broadcast.edit'] = 'Subscribe successfully changed.';
$lang['admin.messages.broadcast.delete'] = 'Subscribe deleted successfully.';
$lang['admin.messages.broadcast.delete_all'] = 'Subscribe deleted.';

$lang['admin.broadcast.view_results.title'] = 'results list';
$lang['admin.broadcast.view_results.recipent'] = 'recipient';
$lang['admin.broadcast.view_results.is_read'] = 'read';
$lang['admin.broadcast.view_results.link'] = 'I went back to the link:';

// ------------------------ GLOBAL END> ------------------------------------

// ------------------------ LOGIN> START -----------------------------------
$lang['admin.login.form_title'] = 'Login';
$lang['admin.login.login_field'] = 'Login';
$lang['admin.login.password'] = 'Password';
$lang['admin.login.login_action'] = 'Login';
$lang['admin.login.forgot_password'] = 'Forgot Password?';
// ------------------------ LOGIN> END -------------------------------------

// ------------------------ FORGOT PASSWORD> START -----------------------------------
$lang['admin.forgot_password.form_title'] = 'Forgot Password?';
$lang['admin.forgot_password.email_field'] = 'E-mail';
$lang['admin.forgot_password.back_to_login'] = 'Back to Login';
$lang['admin.forgot_password.send'] = 'Send';
$lang['admin.messages.password_successfully_sent'] = 'New password has been sent to your email.';
$lang['admin.messages.forgot_password_wrong_msg'] = 'Invalid email.';
// ------------------------ FORGOT PASSWORD> END -------------------------------------

// ------------------------ Search> START ----------------------------------
$lang['admin.search.search_action'] = 'Search';
$lang['admin.search.search_string'] = 'Search';
$lang['admin.search.search_in'] = 'Where:';
$lang['admin.search.search_type'] = 'Search Type';
$lang['admin.search.search_types']["starts_with"] = 'starts with';
$lang['admin.search.search_types']["contains"] = 'contains';
$lang['admin.search.search_types']["ends_with"] = 'and ending with';

$lang['admin.filter.from'] = 'from';
$lang['admin.filter.to'] = 'To';
$lang['admin.filter.for'] = 'In';
$lang['admin.filter.today'] = 'today';
$lang['admin.filter.this_week'] = 'This Week';
$lang['admin.filter.this_month'] = 'this month';
$lang['admin.filter.cancel'] = 'reset';

// ------------------------ Search> END ------------------------------------

// ------------------------ ADMIN> START -----------------------------------
$lang['admin.menu.admin.name'] = 'Admin';
$lang['admin.entity_list.admin.list_title'] = 'Admin';
$lang['admin.entity_list.admin.add'] = '+ Add Administrator';
$lang['admin.entity_list.admin.name'] = 'Name';
$lang['admin.entity_list.admin.email'] = 'E-mail';

$lang['admin.add_edit.admin.form_title'] = 'Add / Edit Administrator';
$lang['admin.add_edit.admin.name'] = 'Name';
$lang['admin.add_edit.admin.name.description'] ='';
$lang['admin.add_edit.admin.email'] = 'E-mail';
$lang['admin.add_edit.admin.email.description'] ='';
$lang['admin.add_edit.admin.password'] = 'Password';
$lang['admin.add_edit.admin.password.description'] ='';
$lang['admin.add_edit.admin.permissions'] = 'Permissions';
$lang['admin.add_edit.admin.permissions.description'] ='';

$lang['admin.messages.admin.add'] = 'The administrator has been added.';
$lang['admin.messages.admin.edit'] = 'Administrator changed successfully.';
$lang['admin.messages.admin.delete'] = 'Administrator deleted successfully.';
$lang['admin.messages.admin.delete_all'] = 'Administrators deleted.';
$lang['admin.messages.admin.cannot_delete_current'] = 'User Administrator can not be deleted.';
$lang['admin.permissions.view'] = 'View';
$lang['admin.permissions.add'] = 'Add';
$lang['admin.permissions.edit'] = 'Edit';
$lang['admin.permissions.delete'] = 'delete';
$lang['admin.add_edit.admin.permission_form_title'] = 'Permissions';
// ------------------------ ADMIN> END -----------------------------------


//------------------------ ### COMMON ### -----------------------------------

require_once APPPATH . "language/english/admin/common/adminlog.php";
require_once APPPATH . "language/english/admin/common/settings.php";
require_once APPPATH . "language/english/admin/common/settingsgroup.php";

//------------------------ ### PROJECT SPECIFIC ### -----------------------------------

// Require all files in generated
$results = array();
$handler = opendir(APPPATH . "language/english/admin/generated/");
while ($file = readdir($handler)) {
  if ($file != "." && $file != "..") {
    $results[] = $file;
  }
}
closedir($handler);
foreach ($results as $r) {
  require_once APPPATH . "language/english/admin/generated/" . $r;
}

