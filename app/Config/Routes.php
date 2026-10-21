<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//for SUPPLIER start ********************************************************************************************

// for paging
$routes->get('/', 'Page::menu');
$routes->get('page/menu/(:any)/(:any)', 'Page::menu/$1/$2');
// $routes->get('show_404', 'Page::show_404');

// for login
$routes->get('login', 'Login::login');
$routes->post('authenticate', 'Login::authenticate');

// for logout
$routes->get('logout', 'Logout::logout');

// for message
$routes->post('view_message', 'Message::view_message');
$routes->post('send_message', 'Message::send_message');
$routes->get('read_all_messages', 'Message::read_all_messages');

// for dtr
$routes->post('display_cutoff', 'Dtr::display_cutoff');
$routes->post('server_code', 'Dtr::server_code');
$routes->post('employee_list', 'Dtr::employee_list');
$routes->post('view_dtr', 'Dtr::view_dtr');
$routes->get('print_dtr', 'Dtr::print_dtr');
$routes->get('print_dtr_all', 'Dtr::print_dtr_all');
$routes->post('vTEntry', 'Dtr::vTEntry');
$routes->post('viewRequest', 'Dtr::viewRequest');

// for violation
$routes->post('display_supplier_cutoff', 'Violation::display_supplier_cutoff');
$routes->post('server_code_sup', 'Violation::server_code_sup');
$routes->post('server_code_emp', 'Violation::server_code_emp');
$routes->post('company_list', 'Violation::company_list');
$routes->post('violation_employee_list', 'Violation::violation_employee_list');
$routes->post('viewViolations', 'Violation::viewViolations');
$routes->post('viewViolations_emp', 'Violation::viewViolations_emp');
$routes->post('viewViolations_img', 'Violation::viewViolations_img');
$routes->get('print_violation', 'Violation::print_violation');
$routes->get('print_employee_violation', 'Violation::print_employee_violation');

// for account
$routes->post('get_form', 'Account::get_form');
$routes->post('account_update', 'Account::account_update');
$routes->post('change_photo', 'Account::change_photo');

// for masterfile
$routes->post('view_masterfile', 'Masterfile::view_masterfile');
$routes->post('schedule_pager', 'Masterfile::schedule_pager');
$routes->post('view_schedule', 'Masterfile::view_schedule');

$routes->post('submit_message', 'Contact::submit_message');

// for cms
$routes->get('show_cas', 'Concession::show_cas');
$routes->get('view_cas', 'Concession::view_cas');
$routes->get('view_vis', 'Concession::view_vis');
$routes->get('view_items', 'Concession::view_items');
$routes->get('view_deductions', 'Concession::view_deductions');
$routes->get('view_po', 'Concession::view_po');
$routes->get('view_receivedpo', 'Concession::view_received_po');
$routes->get('view_sales', 'Concession::view_sales');
$routes->get('view_check', 'Concession::view_check');
$routes->get('items_pdf/(:any)', 'Concession::items_pdf/$1');
$routes->get('vendor_po_pdf/(:any)', 'Concession::vendor_po_pdf/$1');
$routes->get('vendor_sales_pdf/(:any)', 'Concession::vendor_sales_pdf/$1');
//for SUPPLIER end ********************************************************************************************

//for ANONYMOUS start ********************************************************************************************
$routes->get('anonymous', 'Login::anonymous');
$routes->post('authenticate_anonymous', 'Login::authenticate_anonymous');
//for ANONYMOUS end ********************************************************************************************

//for ADMIN start ********************************************************************************************

// for paging
$routes->get('admins/', 'admins\Page::menu');
$routes->get('admins/page/menu/(:any)/(:any)', 'admins\Page::menu/$1/$2');
// $routes->get('admins/show_404', 'admins\Page::show_404');

// for login
$routes->get('admins/login', 'admins\Login::login');
$routes->post('admins/authenticate', 'admins\Login::authenticate');

// for logout
$routes->get('admins/logout', 'admins\Logout::logout');

// for admin module
$routes->post('admins/submit_username', 'admins\Admin::submit_username');
$routes->post('admins/submit_password', 'admins\Admin::submit_password');

// for email module
$routes->get('admins/view_message', 'admins\Email::view_message');
$routes->get('admins/delete_message', 'admins\Email::delete_message');
$routes->get('admins/view_details', 'admins\Email::view_details');
$routes->get('admins/delete_this_msg', 'admins\Email::delete_this_msg');
$routes->get('admins/view_message_detail', 'admins\Email::view_message_detail');
$routes->get('admins/count_unread_msg', 'admins\Email::count_unread_msg');
$routes->get('admins/count_total_msg', 'admins\Email::count_total_msg');
$routes->get('admins/supplier_chat_messages', 'admins\Email::supplier_chat_messages');
$routes->post('admins/view_supplier_messages', 'admins\Email::view_supplier_messages');
$routes->post('admins/admin_read_message', 'admins\Email::admin_read_message');
$routes->post('admins/send_feedback', 'admins\Email::send_feedback');
$routes->post('admins/update_active_chat', 'admins\Email::update_active_chat');
$routes->get('admins/newMessageForm', 'admins\Email::newMessageForm');

// for report module
$routes->get('admins/suppliers_xls', 'admins\Report::suppliers_xls');

// for setup module
$routes->post('admins/supplier_list', 'admins\Setup::supplier_list');
$routes->get('admins/add_supplier_details', 'admins\Setup::add_supplier_details');
$routes->post('admins/create_update_supplier', 'admins\Setup::create_update_supplier');
$routes->post('admins/edit_supplier_details', 'admins\Setup::edit_supplier_details');
$routes->post('admins/supplier_status', 'admins\Setup::supplier_status');

$routes->post('admins/supplier_account_list', 'admins\Setup::supplier_account_list');
$routes->get('admins/add_supplier_account', 'admins\Setup::add_supplier_account');
$routes->post('admins/setup_supplier', 'admins\Setup::setup_supplier');
$routes->post('admins/create_supplier_account', 'admins\Setup::create_supplier_account');
$routes->post('admins/renew_contract', 'admins\Setup::renew_contract');
$routes->post('admins/renew_supplier_contract', 'admins\Setup::renew_supplier_contract');
$routes->post('admins/update_ac_code', 'admins\Setup::update_ac_code');
$routes->post('admins/update_supplier_account', 'admins\Setup::update_supplier_account');
$routes->post('admins/setup_cutoff', 'admins\Setup::setup_cutoff');
$routes->post('admins/insert_com_cut', 'admins\Setup::insert_com_cut');
$routes->post('admins/delete_com_cut', 'admins\Setup::delete_com_cut');
$routes->post('admins/insertAll_com_cut', 'admins\Setup::insertAll_com_cut');
$routes->post('admins/deleteAll_com_cut', 'admins\Setup::deleteAll_com_cut');
$routes->post('admins/supplier_reset_password', 'admins\Setup::supplier_reset_password');
$routes->post('admins/supplier_user_status', 'admins\Setup::supplier_user_status');
$routes->post('admins/supplier_user_details', 'admins\Setup::supplier_user_details');

$routes->post('admins/consignor_account_list', 'admins\Setup::consignor_account_list');
$routes->post('admins/setup_consignor', 'admins\Setup::setup_consignor');
$routes->post('admins/create_consignor_account', 'admins\Setup::create_consignor_account');
$routes->post('admins/edit_tag_vendor', 'admins\Setup::edit_tag_vendor');
$routes->post('admins/update_consignor_account', 'admins\Setup::update_consignor_account');

$routes->post('admins/fetch_users', 'admins\Setup::fetch_users');
$routes->get('admins/add_adminUser', 'admins\Setup::add_adminUser');
$routes->post('admins/submit_adminUser', 'admins\Setup::submit_adminUser');
$routes->post('admins/update_adminUser', 'admins\Setup::update_adminUser');
$routes->post('admins/submit_updated_userAdmin', 'admins\Setup::submit_updated_userAdmin');
$routes->post('admins/reset_password', 'admins\Setup::reset_password');
$routes->post('admins/user_status', 'admins\Setup::user_status');

// for violation module
$routes->post('admins/display_cutoff', 'admins\Violation::display_cutoff');
$routes->post('admins/view_company_list', 'admins\Violation::view_company_list');
$routes->post('admins/view_violation', 'admins\Violation::view_violation');
$routes->post('admins/view_image', 'admins\Violation::view_image');
$routes->post('admins/upload_violation_form', 'admins\Violation::upload_violation_form');
$routes->post('admins/upload_violation', 'admins\Violation::upload_violation');
$routes->post('admins/delete_violation', 'admins\Violation::delete_violation');

// for masterfile
$routes->post('admins/searchPromo', 'admins\Masterfile::searchPromo');
$routes->post('admins/promoData', 'admins\Masterfile::promoData');
$routes->post('admins/multSched', 'admins\Masterfile::multSched');
$routes->post('admins/promoDefSched', 'admins\Masterfile::promoDefSched');
$routes->post('admins/viewSched', 'admins\Masterfile::viewSched');
$routes->post('admins/viewSchedPromo', 'admins\Masterfile::viewSchedPromo');
$routes->post('admins/dayoff', 'admins\Masterfile::dayoff');

$routes->post('admins/dtrSearch', 'admins\Masterfile::dtrSearch');
$routes->post('admins/dtrCutoff', 'admins\Masterfile::dtrCutoff');
$routes->post('admins/promoDtr', 'admins\Masterfile::promoDtr');
$routes->post('admins/vTEntry', 'admins\Masterfile::vTEntry');
$routes->post('admins/viewRequest', 'admins\Masterfile::viewRequest');
$routes->get('admins/print_dtr', 'admins\Masterfile::print_dtr');

// for portal Admin
$routes->post('admins/portal_users', 'admins\Portal::portal_users');
$routes->post('admins/add_user_form', 'admins\Portal::add_user_form');
$routes->post('admins/edit_user_form', 'admins\Portal::edit_user_form');
$routes->post('admins/employeeSearch', 'admins\Portal::employeeSearch');
$routes->post('admins/userUpdate', 'admins\Portal::userUpdate');

$routes->post('admins/searchContract', 'admins\Portal::searchContract');
$routes->post('admins/promoContracts', 'admins\Portal::promoContracts');
$routes->post('admins/editContractForm', 'admins\Portal::editContractForm');
$routes->post('admins/selectCompany', 'admins\Portal::selectCompany');
$routes->post('admins/selectDepartment', 'admins\Portal::selectDepartment');
$routes->post('admins/updatePromoContract', 'admins\Portal::updatePromoContract');
$routes->post('admins/addCutoffForm', 'admins\Portal::addCutoffForm');
$routes->post('admins/addCutoffSubmit', 'admins\Portal::addCutoffSubmit');
$routes->post('admins/addPromorecordForm', 'admins\Portal::addPromorecordForm');
$routes->post('admins/addPromorecordSubmit', 'admins\Portal::addPromorecordSubmit');
$routes->post('admins/uploadPromorecordForm', 'admins\Portal::uploadPromorecordForm');
$routes->post('admins/uploadPromorecord', 'admins\Portal::uploadPromorecord');
$routes->post('admins/assignSearch', 'admins\Portal::assignSearch');
$routes->post('admins/assignData', 'admins\Portal::assignData');
$routes->post('admins/editAssignForm', 'admins\Portal::editAssignForm');
$routes->post('admins/editAssignData', 'admins\Portal::editAssignData');
$routes->post('admins/updateAssignData', 'admins\Portal::updateAssignData');
$routes->post('admins/prevdtrSearch', 'admins\Portal::prevdtrSearch');
$routes->post('admins/prevperDay', 'admins\Portal::prevperDay');
$routes->post('admins/ppDayUpdate', 'admins\Portal::ppDayUpdate');

// for import
$routes->post('admins/import_data', 'admins\Import::import_data');
$routes->post('admins/import_violation_data', 'admins\Import::import_violation_data');

// for cms module
$routes->post('admins/fetch_vendor', 'admins\Cms::fetch_vendor');
$routes->post('admins/fetch_vis', 'admins\Cms::fetch_vis');
$routes->post('admins/fetch_cas', 'admins\Cms::fetch_cas');
$routes->post('admins/fetch_po', 'admins\Cms::fetch_po');
$routes->post('admins/fetch_deduction', 'admins\Cms::fetch_deduction');
$routes->post('admins/fetch_items', 'admins\Cms::fetch_items');
$routes->post('admins/fetch_check_voucher', 'admins\Cms::fetch_check_voucher');
$routes->post('admins/fetch_check_details', 'admins\Cms::fetch_check_details');
$routes->post('admins/fetch_check_monitoring', 'admins\Cms::fetch_check_monitoring');
$routes->post('admins/fetch_sales', 'admins\Cms::fetch_sales');
$routes->post('admins/fetch_sales_uploaded', 'admins\Cms::fetch_sales_uploaded');
$routes->post('admins/fetch_sales_summary', 'admins\Cms::fetch_sales_summary');
$routes->post('admins/import_csv', 'admins\Cms::import_csv');
$routes->post('admins/import_csv_direct', 'admins\Cms::import_csv_direct');
$routes->get('admins/view_item_sales', 'admins\Cms::view_item_sales');
$routes->get('admins/send_email_user_account', 'admins\Cms::testEmail');

//for ADMIN end ********************************************************************************************

$routes->get('(:any)', 'Page::menu');
