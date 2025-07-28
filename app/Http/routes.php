<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', 'WelcomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('test', 'HomeController@test');

Route::get('tombola', 'HomeController@tombola');

// Reservations
Route::get('reservation', 'reservationController@index');
Route::get('hu_list', 'reservationController@hu_list');
Route::get('update_reservation_table', 'reservationController@update_reservation_table');
Route::get('update_reservation_table_oposite', 'reservationController@update_reservation_table_oposite');
Route::get('reserv_mat', 'reservationController@reserv_mat');
Route::post('reserv_input', 'reservationController@reserv_input');
Route::post('reserv_all_available', 'reservationController@reserv_all_available');
Route::post('reserv_by_hu', 'reservationController@reserv_by_hu');
Route::post('reserv_cancel', 'reservationController@reserv_cancel');
Route::post('cancel_all', 'reservationController@cancel_all');
Route::post('cancel_po_imput', 'reservationController@cancel_po_imput');
Route::post('cancel_po', 'reservationController@cancel_po');
Route::post('cancel_hu_imput', 'reservationController@cancel_hu_imput');
Route::post('cancel_hu', 'reservationController@cancel_hu');
Route::post('reserv_all_available_confirm', 'reservationController@reserv_all_available_confirm');
Route::post('reserv_by_hu_insert_po', 'reservationController@reserv_by_hu_insert_po');
Route::post('reserv_by_hu_confirm', 'reservationController@reserv_by_hu_confirm');
Route::get('reserv_table', 'reservationController@reserv_table');
Route::post('reserv_table_by_po', 'reservationController@reserv_table_by_po');
Route::get('reserv_table_filter', 'reservationController@reserv_table_filter');
Route::post('reserv_filter', 'reservationController@reserv_filter');
Route::get('cancel_reservation_for_closed_po', 'reservationController@cancel_reservation_for_closed_po');
Route::get('reserv_by_po', 'reservationController@reserv_by_po');
Route::get('unreserv_mat', 'reservationController@unreserve_mat');
Route::post('unreserv_mat_input', 'reservationController@unreserv_mat_input');
Route::get('unreserv_po', 'reservationController@unreserve_po');
Route::post('unreserv_po_input', 'reservationController@unreserv_po_input');
Route::post('unreserv_po_confirm', 'reservationController@unreserv_po_confirm');

// Komesa
Route::get('po', 'poController@index');
Route::get('new_po', 'poController@new_po');
Route::post('post_new_po', 'poController@post_new_po');
Route::get('/edit_status/{id}', 'poController@edit_status');
Route::post('/edit_status/{id}', 'poController@update_status');
Route::get('/edit_po/{id}', 'poController@edit_po');
Route::post('/edit_po/{id}', 'poController@update_po');

//Cosnumption

//Route::get('cons', 'consController@index');
//Route::get('cons_table', 'consController@cons_table');
//Route::get('update_cons_table', 'consController@update_cons_table');
//Route::get('add_po_cons_table', 'consController@add_po_cons_table');
//Route::get('add_po', 'consController@add_po');
//Route::post('add_new_po_cons', 'consController@add_new_po_cons');

//Requests
Route::get('request', 'requestController@index');
Route::post('logincheck', 'requestController@logincheck');
Route::get('select', 'requestController@select');

Route::get('req_extrabb', 'requestController@req_extrabb');
Route::post('req_extrabbconfirm', 'requestController@req_extrabbconfirm');
Route::get('/req_extrabb_status/{id}', 'requestController@edit_req_extrabb_status');
Route::post('/req_extrabb_status', 'requestController@req_extrabb_status');

Route::get('req_cartonbox', 'requestController@req_cartonbox');
Route::post('req_cartonboxconfirm', 'requestController@req_cartonboxconfirm');
Route::get('/req_cartonbox_status/{id}', 'requestController@edit_req_cartonbox_status');
Route::post('/req_cartonbox_status', 'requestController@req_cartonbox_status');

Route::get('req_reprintbb', 'requestController@req_reprintbb');
Route::post('req_reprintbbconfirm', 'requestController@req_reprintbbconfirm');
Route::get('/req_reprintbb_status/{id}', 'requestController@edit_req_reprintbb_status');
Route::post('/req_reprintbb_status', 'requestController@req_reprintbb_status');

Route::get('req_padprint', 'requestController@req_padprint');
Route::post('req_padprintconfirm', 'requestController@req_padprintconfirm');
Route::get('/req_padprint_status/{id}', 'requestController@edit_req_padprint_status');
Route::post('/req_padprint_status', 'requestController@req_padprint_status');
Route::post('/req_padprint_status1', 'requestController@req_padprint_status1');

Route::get('/req_cut_part_status/{id}', 'requestController@edit_req_cut_part_status');
Route::post('/req_cut_part_status', 'requestController@req_cut_part_status');
Route::post('/req_cut_part_status_c', 'requestController@req_cut_part_status_c');
Route::post('/req_cut_part_status_p', 'requestController@req_cut_part_status_p');
// Route::post('/req_cut_part_status1', 'requestController@req_cut_part_status1');

Route::get('req_lost', 'requestController@req_lost');
Route::post('req_lostconfirm', 'requestController@req_lostconfirm');
Route::get('/req_lost_status/{id}', 'requestController@edit_req_lost_status');
Route::post('/req_lost_status', 'requestController@req_lost_status');

// Request tables
Route::get('table_select', 'requestController@table_select');
Route::get('req_extrabb_table', 'requestController@req_extrabb_table');
Route::get('req_extrabb_table_history', 'requestController@req_extrabb_table_history');
Route::get('req_cartonbox_table', 'requestController@req_cartonbox_table');
Route::get('req_cartonbox_table_history', 'requestController@req_cartonbox_table_history');
Route::get('req_reprintbb_table', 'requestController@req_reprintbb_table');
Route::get('req_reprintbb_table_history', 'requestController@req_reprintbb_table_history');
Route::get('req_padprint_table', 'requestController@req_padprint_table');
Route::get('req_padprint_table_history', 'requestController@req_padprint_table_history');
Route::get('req_cut_part_table', 'requestController@req_cut_part_table');
Route::get('req_cut_part_table_history', 'requestController@req_cut_part_table_history');
Route::get('req_paspul/{id}', 'requestController@req_paspul');
Route::post('req_paspul_post', 'requestController@req_paspul_post');

Route::get('req_lost_table', 'requestController@req_lost_table');
Route::get('req_lost_table_history', 'requestController@req_lost_table_history');
Route::get('req_lost_table_history_line', 'requestController@req_lost_table_history_line');

Route::get('/bb/{line}/{id}','cut_pcsController@req_cut_part');
Route::post('requeststore_cut_part', 'cut_pcsController@requeststore_cut_part');
Route::get('req_extrabb_table', 'requestController@req_extrabb_table');
Route::get('req_extrabb_table_history', 'requestController@req_extrabb_table_history');
Route::get('req_extrabb_line_history/{line}','cut_pcsController@req_extrabb_line_history');

// Wastage
Route::get('/wastage_cut','wastageController@index_cut');
Route::post('req_wastage_c', 'wastageController@req_wastage_c');
Route::post('req_wastage_cut', 'wastageController@req_wastage_cut');
Route::get('/wastage_cut_mm','wastageController@index_cut_mm');
Route::get('req_wastage_c_mm', 'wastageController@req_wastage_c_mm');
Route::post('req_wastage_c_mm', 'wastageController@req_wastage_c_mm');
Route::post('req_wastage_cut_mm', 'wastageController@req_wastage_cut_mm');

Route::get('/wastage_wh','wastageController@index_wh');
Route::get('/wastage_wh_scan','wastageController@wastage_wh_scan');
Route::post('req_wastage_wh', 'wastageController@req_wastage_wh');
Route::post('req_wastage_wh_insert', 'wastageController@req_wastage_wh_insert');
Route::get('wastage_table','wastageController@table');
Route::get('wastage_table_all','wastageController@table_all');

Route::get('move_sapbin_container','wastageController@move_sapbin_container');
Route::post('move_sapbin_container_post','wastageController@move_sapbin_container_post');
Route::get('move_sapbin_container_1','wastageController@move_sapbin_container_1');
Route::post('move_sapbin_container_post_1','wastageController@move_sapbin_container_post_1');

Route::get('move_container_location','wastageController@move_container_location');
Route::post('move_container_location_post','wastageController@move_container_location_post');
Route::get('move_container_location_1','wastageController@move_container_location_1');
Route::post('move_container_location_post_1','wastageController@move_container_location_post_1');

Route::get('wastage_remove_skeda','wastageController@wastage_remove_skeda');
Route::get('wastage_remove_skeda/{id}','wastageController@wastage_remove_skeda_post');

Route::get('wastage_remove_skeda_partialy/{skeda}','wastageController@wastage_remove_skeda_partialy');
Route::post('wastage_remove_skeda_partialy_post','wastageController@wastage_remove_skeda_partialy_post');

Route::post('delete_wastage_line','wastageController@delete_wastage_line');
// Route::get('delete_wastage_line_c','wastageController@delete_wastage_line_c');
Route::post('delete_wastage_line_g','wastageController@delete_wastage_line_g');

Route::get('wastage_edit/{id}','wastageController@wastage_edit');
Route::post('wastage_edit_post','wastageController@wastage_edit_post');

// W bin
Route::get('wastage_bin','wastage_binController@index');
Route::get('add_wastage_bin','wastage_binController@add');
Route::post('add_wastage_bin_post', 'wastage_binController@add_post');
Route::get('edit_wastage_bin/{id}','wastage_binController@edit');
Route::post('edit_wastage_bin_post/{id}', 'wastage_binController@edit_post');
// Route::get('remove_wastage_bin/{id}', 'wastage_binController@remove_wastage_bin');

// W locaion
Route::get('wastage_location','wastage_locationController@index');
Route::get('add_wastage_location','wastage_locationController@add');
Route::post('add_wastage_location_post', 'wastage_locationController@add_post');
Route::get('edit_wastage_location/{id}','wastage_locationController@edit');
Route::post('edit_wastage_location_post/{id}', 'wastage_locationController@edit_post');
// Route::get('remove_wastage_location/{id}', 'wastage_locationController@remove_wastage_location');

// Import
Route::get('import', 'importController@index');
Route::post('postImportConsPo', 'importController@postImportConsPo');
Route::get('postImportUpdatePass', 'importController@postImportUpdatePass');
Route::post('postImportMaterials', 'importController@postImportMaterials');
Route::post('postImport_marker_status', 'importController@postImport_marker_status');
Route::post('postImport_style_parts', 'importController@postImport_style_parts');

Route::get('import2', 'import2Controller@index');
Route::post('postImport_by', 'import2Controller@postImport_by');

Route::get('wastage_table_import','importController@index');
Route::post('postImportWastage_report','importController@postImportWastage_report');
Route::post('postImport_marker','importController@postImport_marker');
Route::post('postImport_skeda','importController@postImport_skeda');
Route::post('postImport_pas_bin','importController@postImport_pas_bin');
Route::post('postImport_consumption','importController@postImport_consumption');

Route::post('bom_cons_post', 'importController@bom_cons_post');
Route::post('skeda_ratio_post', 'importController@skeda_ratio_post');

Route::post('bom_cutting_smv_post', 'importController@bom_cutting_smv_post');
Route::post('bom_cutting_tubolare_smv_post', 'importController@bom_cutting_tubolare_smv_post');


// Cutting XML
Route::get('cutting_xml', 'cutting_xml@cutting_xml');
Route::get('cutting_xml_all', 'cutting_xml@cutting_xml_all');
Route::get('cutting_bansek_xml', 'cutting_xml@cutting_bansek_xml');
Route::get('cutting_bansek_xml_all', 'cutting_xml@cutting_bansek_xml_all');
Route::get('cutting_bansek_errors', 'cutting_xml@cutting_bansek_errors');

// Operators
Route::get('operators', 'operatorsController@index');
Route::get('operator_create', 'operatorsController@operator_create');
Route::post('operator_create_post', 'operatorsController@operator_create_post');
Route::get('operator_edit/{id}', 'operatorsController@operator_edit');
Route::post('operator_edit_post', 'operatorsController@operator_edit_post');

// Operatos others
Route::get('operator_others', 'operatorsController@operator_others');
Route::get('operator_create_others', 'operatorsController@operator_create_others');
Route::post('operator_others_create_post', 'operatorsController@operator_others_create_post');


// Marker
Route::get('marker', 'markerController@index');
Route::get('marker_details/{id}', 'markerController@index_line');
Route::post('marker_line_confirm', 'markerController@marker_line_confirm');
Route::get('marker_delete/{id}', 'markerController@marker_delete');
Route::post('marker_delete_confirm', 'markerController@marker_delete_confirm');
Route::get('marker_edit/{id}', 'markerController@marker_edit');
Route::post('marker_edit_confirm', 'markerController@marker_edit_confirm');


// Pro Skeda
Route::get('pro_skeda', 'pro_skedaController@index');

// Paspul
Route::get('paspul', 'paspulController@index');

// Paspul_bin
Route::get('paspul_bin', 'paspul_binController@index');

// Matress
Route::get('mattress', 'mattressController@index');

// Consumption SAP
Route::get('consumption_sap', 'consumption_sapController@index');
Route::get('mat_con_file', 'consumption_sapController@mat_con_file');
Route::post('mat_con_file_post', 'consumption_sapController@mat_con_file_post');
Route::get('mat_con_file_table', 'consumption_sapController@mat_con_file_table');

// Admin & Planner
// Route::get('plan_mattress', 'plannerController@plan_mattress');
Route::get('plan_mattress/{location}', 'plannerController@plan_mattress');
// Route::post('posts/reposition', 'plannerController@reposition');
Route::get('operator_login_planner', 'plannerController@operator_login');
Route::get('operator_logout_planner', 'plannerController@operator_logout');
Route::post('posts/reposition' , [ 'uses' => 'plannerController@reposition', 'as' => 'posts.reposition' ]);
Route::post('posts/reposition0', [ 'uses' => 'plannerController@reposition0', 'as' => 'posts.reposition0' ]);
Route::post('posts/reposition1', [ 'uses' => 'plannerController@reposition1', 'as' => 'posts.reposition1' ]);
Route::post('posts/reposition2', [ 'uses' => 'plannerController@reposition2', 'as' => 'posts.reposition2' ]);
Route::post('posts/reposition3', [ 'uses' => 'plannerController@reposition3', 'as' => 'posts.reposition3' ]);
Route::post('posts/reposition4', [ 'uses' => 'plannerController@reposition4', 'as' => 'posts.reposition4' ]);
Route::post('posts/reposition5', [ 'uses' => 'plannerController@reposition5', 'as' => 'posts.reposition5' ]);
Route::post('posts/reposition6', [ 'uses' => 'plannerController@reposition6', 'as' => 'posts.reposition6' ]);
Route::post('posts/reposition7', [ 'uses' => 'plannerController@reposition7', 'as' => 'posts.reposition7' ]);
Route::post('posts/reposition8', [ 'uses' => 'plannerController@reposition8', 'as' => 'posts.reposition8' ]);
Route::post('posts/reposition9', [ 'uses' => 'plannerController@reposition9', 'as' => 'posts.reposition9' ]);
Route::post('posts/reposition_pas', [ 'uses' => 'plannerController@reposition_pas', 'as' => 'posts.reposition_pas' ]);
Route::post('posts/reposition_p_1', [ 'uses' => 'plannerController@reposition_p_1', 'as' => 'posts.reposition_p_1' ]);
Route::post('posts/reposition_p_2', [ 'uses' => 'plannerController@reposition_p_2', 'as' => 'posts.reposition_p_2' ]);

Route::get('plan_mattress_line/{id}', 'plannerController@plan_mattress_line');
Route::post('plan_mattress_line_confirm', 'plannerController@plan_mattress_line_confirm');
Route::get('change_marker/{id}', 'plannerController@change_marker');
Route::post('change_marker_post', 'plannerController@change_marker_post');
Route::get('split_mattress/{id}', 'plannerController@split_mattress');
Route::post('split_mattress_post', 'plannerController@split_mattress_post');
Route::get('split_mattress_delete/{id}', 'plannerController@split_mattress_delete');
Route::post('split_mattress_delete_confirm', 'plannerController@split_mattress_delete_confirm');
Route::get('delete_mattress/{id}', 'plannerController@delete_mattress');
Route::post('delete_mattress_confirm', 'plannerController@delete_mattress_confirm');
Route::get('edit_mattress_line/{id}', 'plannerController@edit_mattress');
Route::get('correct_location/{id}', 'plannerController@correct_location');
Route::post('edit_mattress_line_confirm', 'plannerController@edit_mattress_confirm');
Route::get('edit_layers_a/{id}', 'plannerController@edit_layers_a');
Route::post('edit_layers_a_confirm', 'plannerController@edit_layers_a_confirm');
Route::get('change_marker_all/{id}', 'plannerController@change_marker_all');
Route::post('change_marker_all_post', 'plannerController@change_marker_all_post');
Route::post('change_marker_all_post_check', 'plannerController@change_marker_all_post_check');

Route::get('update_all_pro_actual', 'plannerController@update_all_pro_actual');

Route::get('plan_mini_marker', 'plannerController@plan_mini_marker');
Route::get('mini_marker_create', 'plannerController@mini_marker_create');
Route::post('mini_marker_create_1', 'plannerController@mini_marker_create_1');
Route::post('mini_marker_create_2', 'plannerController@mini_marker_create_2');
Route::post('mini_marker_add_pro', 'plannerController@mini_marker_add_pro');
Route::post('mini_marker_add_marker', 'plannerController@mini_marker_add_marker');
Route::post('mini_marker_add_limit', 'plannerController@mini_marker_add_limit');

Route::get('o_roll_table', 'plannerController@o_roll_table');
Route::get('o_roll_table_all', 'plannerController@o_roll_table_all');
Route::get('o_roll_delete/{id}', 'plannerController@o_roll_delete');
Route::post('o_roll_delete_confirm', 'plannerController@o_roll_delete_confirm');
Route::get('o_roll_return', 'plannerController@o_roll_return');
Route::get('o_roll_return/{id}', 'plannerController@o_roll_return');
Route::post('o_roll_return_confirm', 'plannerController@o_roll_return_confirm');
Route::get('o_roll_scan', 'plannerController@o_roll_scan');
Route::post('o_roll_scan_post', 'plannerController@o_roll_scan_post');

Route::get('plan_paspul/{location}', 'plannerController@plan_paspul');
Route::get('plan_paspul_line/{id}', 'plannerController@plan_paspul_line');
Route::post('plan_paspul_line_confirm', 'plannerController@plan_paspul_line_confirm');

Route::get('plan_paspul_line1/{id}', 'plannerController@plan_paspul_line1');
Route::post('plan_paspul_line_confirm1', 'plannerController@plan_paspul_line_confirm1');

Route::get('remove_paspul_line/{id}', 'plannerController@remove_paspul_line');
Route::post('paspul_delete_confirm', 'plannerController@paspul_delete_confirm');

Route::get('remove_paspul_roll_line/{id}', 'plannerController@remove_paspul_roll_line');
Route::post('paspul_delete_roll_confirm', 'plannerController@paspul_delete_roll_confirm');

Route::get('edit_paspul_line/{id}', 'plannerController@edit_paspul');
Route::post('edit_paspul_line_confirm', 'plannerController@edit_paspul_confirm');

Route::get('edit_paspul_roll_line/{id}', 'plannerController@edit_paspul_roll_line');
Route::post('edit_paspul_roll_line_confirm', 'plannerController@edit_paspul_roll_line_confirm');

Route::get('paspul_change_kotur_qty/{id}', 'plannerController@paspul_change_kotur_qty');
Route::post('paspul_pco1_planner_confirm', 'plannerController@paspul_pco1_planner_confirm');
Route::get('paspul_stock', 'plannerController@paspul_stock');
Route::get('paspul_change_q/{id}', 'plannerController@paspul_change_q');
Route::get('paspul_req_list', 'plannerController@paspul_req_list');
Route::get('paspul_req_list_log', 'plannerController@paspul_req_list_log');
Route::get('req_paspul_complete/{id}', 'plannerController@req_paspul_complete');

Route::get('paspul_delete_line/{id}', 'plannerController@paspul_delete_line');
Route::post('paspul_delete_line_confirm', 'plannerController@paspul_delete_line_confirm');

Route::post('paspul_change_q_post', 'plannerController@paspul_change_q_post');
Route::get('paspul_stock_log', 'plannerController@paspul_stock_log');
Route::get('paspul_change_log_q/{id}', 'plannerController@paspul_change_log_q');
Route::post('paspul_change_log_q_post', 'plannerController@paspul_change_log_q_post');
Route::get('paspul_stock_check_fg_color', 'plannerController@paspul_stock_check_fg_color');
Route::get('paspul_stock_check_fg_color_post', 'plannerController@paspul_stock_check_fg_color_post');
Route::get('paspul_stock_update_pc_kotur_post', 'plannerController@paspul_stock_update_pc_kotur_post');

Route::get('paspul_remove_valy', 'plannerController@paspul_remove_valy');
Route::post('paspul_remove_valy_skeda', 'plannerController@paspul_remove_valy_skeda');
Route::post('paspul_remove_valy_remove', 'plannerController@paspul_remove_valy_remove');

Route::get('print_mattress/{id}', 'plannerController@print_mattress');
Route::post('print_mattress_confirm', 'plannerController@print_mattress_confirm');
Route::get('print_mattress_m/{id}', 'plannerController@print_mattress_m');
Route::post('print_mattress_confirm_m', 'plannerController@print_mattress_confirm_m');
Route::get('print_mattress_multiple', 'plannerController@print_mattress_multiple');
Route::get('print_mattress_multiple_sm', 'plannerController@print_mattress_multiple_sm');
Route::get('print_mattress_multiple_mm', 'plannerController@print_mattress_multiple_mm');
Route::post('print_mattress_multiple_sm_post', 'plannerController@print_mattress_multiple_sm_post');
Route::post('print_mattress_multiple_mm_post', 'plannerController@print_mattress_multiple_mm_post');
Route::post('print_mattress_multiple_sm_complete', 'plannerController@print_mattress_multiple_sm_complete');
Route::post('print_mattress_multiple_mm_complete', 'plannerController@print_mattress_multiple_mm_complete');

Route::get('skeda_comments', 'plannerController@skeda_comments');
Route::get('skeda_comments_add', 'plannerController@skeda_comments_add');
Route::post('skeda_comment_post', 'plannerController@skeda_comment_post');
Route::get('skeda_comment_edit/{id}', 'plannerController@skeda_comment_edit');
Route::post('skeda_comment_edit_post', 'plannerController@skeda_comment_edit_post');
Route::post('skeda_comment_delete', 'plannerController@skeda_comment_delete');


// Spreader
Route::get('spreader', 'spreaderController@index');
Route::get('operator_login', 'spreaderController@operator_login');
Route::get('operator_logout', 'spreaderController@operator_logout');
Route::get('operator_login2', 'spreaderController@operator_login2');
Route::get('operator_logout2', 'spreaderController@operator_logout2');
Route::get('other_functions/{id}', 'spreaderController@other_functions');
Route::get('mattress_to_load/{id}', 'spreaderController@mattress_to_load');
Route::get('mattress_to_unload/{id}', 'spreaderController@mattress_to_unload');
Route::get('mattress_to_spread/{id}', 'spreaderController@mattress_to_spread');

Route::post('add_operator_comment', 'spreaderController@add_operator_comment');
Route::get('change_marker_request/{id}', 'spreaderController@change_marker_request');
Route::post('change_marker_request_post', 'spreaderController@change_marker_request_post');
Route::get('split_marker_request/{id}', 'spreaderController@split_marker_request');
Route::post('split_marker_request_post', 'spreaderController@split_marker_request_post');
// Route::get('create_new_mattress_request/{id}', 'spreaderController@create_new_mattress_request');
Route::get('spread_mattress_partial/{id}', 'spreaderController@spread_mattress_partial');
Route::post('spread_mattress_partial_post', 'spreaderController@spread_mattress_partial_post');
Route::get('spread_mattress_complete/{id}', 'spreaderController@spread_mattress_complete');
Route::post('spread_mattress_complete_post', 'spreaderController@spread_mattress_complete_post');

Route::get('request_material/{id}', 'spreaderController@request_material');
Route::post('request_material_insert', 'spreaderController@request_material_insert');
Route::get('request_material_table', 'spreaderController@request_material_table');
Route::get('request_material_delete/{id}', 'spreaderController@request_material_delete');
Route::get('request_material_delete_confirm/{id}', 'spreaderController@request_material_delete_confirm');


// CUT
Route::get('cutter', 'cutterController@index');
Route::get('operator_login_cut', 'cutterController@operator_login');
Route::get('operator_logout_cut', 'cutterController@operator_logout');
Route::get('mattress_to_cut/{id}', 'cutterController@mattress_to_cut');
Route::get('mattress_cut/{id}', 'cutterController@mattress_cut');
Route::post('mattress_cut_post', 'cutterController@mattress_cut_post');
Route::get('other_functions_cut/{id}', 'cutterController@other_functions');
Route::post('add_operator_comment_cut', 'cutterController@add_operator_comment');
Route::get('change_all_marker_request/{id}', 'cutterController@change_all_marker_request');
Route::post('change_all_marker_request_post', 'cutterController@change_all_marker_request_post');


// PACK
Route::get('pack', 'packController@index');
Route::get('operator_login_pack', 'packController@operator_login');
Route::get('operator_logout_pack', 'packController@operator_logout');
Route::get('mattress_pack/{id}/{g_bin}', 'packController@mattress_pack');
Route::get('mattress_pack_m/{id}', 'packController@mattress_pack_m');
Route::get('mattress_pack_confirm/{id}', 'packController@mattress_pack_confirm');
Route::get('other_functions_pack/{id}', 'packController@other_functions');


// PLOT
Route::get('plot', 'plotController@index');
Route::get('operator_login_plot', 'plotController@operator_login');
Route::get('operator_logout_plot', 'plotController@operator_logout');
Route::get('mattress_plot/{id}', 'plotController@mattress_plot');
Route::get('mattress_plot_confirm/{id}', 'plotController@mattress_plot_confirm');


// PSO
Route::get('pso', 'psoController@index');
Route::get('operator_login_pso', 'psoController@operator_login');
Route::get('operator_logout_pso', 'psoController@operator_logout');
Route::get('mattress_pso/{id}', 'psoController@mattress_pso');
Route::get('mattress_pso_confirm/{id}', 'psoController@mattress_pso_confirm');


// PRW
Route::get('prw', 'prwController@index');
Route::get('operator_login_prw', 'prwController@operator_login');
Route::get('operator_logout_prw', 'prwController@operator_logout');
Route::get('paspul_prw/{id}', 'prwController@paspul_prw');
Route::post('paspul_prw_confirm', 'prwController@paspul_prw_confirm');

Route::get('prw1', 'prwController@index1');
Route::get('paspul_prw1/{id}', 'prwController@paspul_prw1');
Route::post('paspul_prw1_confirm', 'prwController@paspul_prw1_confirm');
Route::get('finish_rewound/{id}', 'prwController@finish_rewound');
Route::get('request_material_p/{id}', 'prwController@request_material');
Route::post('request_material_p_insert', 'prwController@request_material_insert');
// Route::get('request_material_p_table', 'prwController@request_material_table');

Route::get('prw2', 'prwController@index2');
Route::get('paspul_prw2/{id}', 'prwController@paspul_prw2');
Route::post('paspul_prw2_confirm', 'prwController@paspul_prw2_confirm');


// PCO
Route::get('pco', 'pcoController@index');
Route::get('operator_login_pco', 'pcoController@operator_login');
Route::get('operator_logout_pco', 'pcoController@operator_logout');
Route::get('mattress_pco/{id}', 'pcoController@mattress_pco');
Route::get('mattress_pco_confirm/{id}', 'pcoController@mattress_pco_confirm');
Route::get('paspul_pco/{id}', 'pcoController@paspul_pco');
Route::post('paspul_pco_confirm', 'pcoController@paspul_pco_confirm');

Route::get('pco1', 'pcoController@index1');
Route::get('paspul_pco1/{id}', 'pcoController@paspul_pco1');
Route::post('paspul_pco1_confirm', 'pcoController@paspul_pco1_confirm');

Route::get('pco2', 'pcoController@index2');
Route::get('paspul_pco2/{id}', 'pcoController@paspul_pco2');
Route::post('paspul_pco2_confirm', 'pcoController@paspul_pco2_confirm');



// LR
Route::get('lr', 'lrController@index');
Route::get('operator_login_lr', 'lrController@operator_login');
Route::get('operator_logout_lr', 'lrController@operator_logout');
Route::get('o_roll_create', 'lrController@o_roll_create');
Route::post('o_roll_gbin', 'lrController@o_roll_gbin');
Route::post('o_roll_lr_scan', 'lrController@o_roll_lr_scan');
Route::post('o_roll_insert_parts', 'lrController@o_roll_insert_parts');
Route::get('o_roll_print', 'lrController@o_roll_print');
Route::post('o_roll_print_confirm', 'lrController@o_roll_print_confirm');
Route::post('o_roll_print_confirm_print', 'lrController@o_roll_print_confirm_print');



// TUB
Route::get('tub', 'tubController@index');
Route::get('operator_login_tub', 'tubController@operator_login');
Route::get('operator_logout_tub', 'tubController@operator_logout');
Route::get('other_functions_tub/{id}', 'tubController@other_functions');
Route::get('mattress_to_load_tub/{id}', 'tubController@mattress_to_load');
Route::get('mattress_to_unload_tub/{id}', 'tubController@mattress_to_unload');
Route::get('mattress_to_spread_tub/{id}', 'tubController@mattress_to_spread');
Route::post('add_operator_comment_tub', 'tubController@add_operator_comment');

Route::get('spread_mattress_tub_partial/{id}', 'tubController@spread_mattress_partial');
Route::post('spread_mattress_tub_partial_post', 'tubController@spread_mattress_partial_post');
Route::get('spread_mattress_tub_complete/{id}', 'tubController@spread_mattress_complete');
Route::post('spread_mattress_tub_complete_post', 'tubController@spread_mattress_complete_post');

Route::get('request_material_t/{id}', 'tubController@request_material');
Route::post('request_material_t_insert', 'tubController@request_material_insert');

Route::get('tubolare_weight', 'tubController@tubolare_weight');
Route::post('tubolare_weight_box_post', 'tubController@tubolare_weight_box_post');
Route::post('tubolare_weight_box_weight_post', 'tubController@tubolare_weight_box_weight_post');
Route::get('tubolare_weight_table', 'tubController@tubolare_weight_table');



// PSS
Route::get('pss', 'pssController@index');

Route::get('paspul_table_just_cut', 'pssController@paspul_table_just_cut');
Route::get('paspul_table_stock_su', 'pssController@paspul_table_stock_su');
Route::get('paspul_table_received_in_subotica', 'pssController@paspul_table_received_in_subotica');
Route::get('paspul_table_stock_ready_ki', 'pssController@paspul_table_stock_ready_ki');
Route::get('paspul_table_stock_ready_se', 'pssController@paspul_table_stock_ready_se');
Route::get('paspul_table_stock_ready_va', 'pssController@paspul_table_stock_ready_va');
Route::get('paspul_table_log/{tip}', 'pssController@paspul_table_log');

Route::get('search_u_cons', 'pssController@search_u_cons');
Route::post('search_u_cons_post', 'pssController@search_u_cons_post');
Route::get('table_u_cons', 'pssController@table_u_cons');
Route::get('table_u_cons_add', 'pssController@table_u_cons_add');
Route::post('table_u_cons_add_post', 'pssController@table_u_cons_add_post');
Route::get('table_u_cons_change/{id}', 'pssController@table_u_cons_change');
Route::post('table_u_cons_change_post', 'pssController@table_u_cons_change_post');

// Route::get('print_paspul_label/{id}', 'pssController@print_paspul_label');
// Route::post('print_paspul_label_post', 'pssController@print_paspul_label_post');
Route::get('print_paspul_label1/{id}', 'pssController@print_paspul_label1');
Route::post('print_paspul_label1_post', 'pssController@print_paspul_label1_post');

Route::get('paspul_loc_to_loc_su_from', 'pssController@paspul_loc_to_loc_su_from');
Route::post('paspul_loc_to_loc_su_from_post', 'pssController@paspul_loc_to_loc_su_from_post');
Route::post('paspul_loc_to_loc_su_pas_post', 'pssController@paspul_loc_to_loc_su_pas_post');
Route::post('paspul_loc_to_loc_su_qty_post', 'pssController@paspul_loc_to_loc_su_qty_post');
Route::post('paspul_loc_to_loc_su_op_post', 'pssController@paspul_loc_to_loc_su_op_post');
Route::post('paspul_loc_to_loc_su_to_post', 'pssController@paspul_loc_to_loc_su_to_post');

Route::get('paspul_jc_to_loc_su_from', 'pssController@paspul_jc_to_loc_su_from');

Route::get('paspul_jc_to_rk_su_from', 'pssController@paspul_jc_to_rk_su_from');
Route::get('paspul_jc_to_rs_su_from', 'pssController@paspul_jc_to_rs_su_from');
Route::get('paspul_jc_to_rv_su_from', 'pssController@paspul_jc_to_rv_su_from');

Route::get('paspul_loc_to_prod_su_from', 'pssController@paspul_loc_to_prod_su_from');
Route::post('paspul_loc_to_prod_su_from_post', 'pssController@paspul_loc_to_prod_su_from_post');
Route::post('paspul_loc_to_prod_su_pas_post', 'pssController@paspul_loc_to_prod_su_pas_post');
Route::post('paspul_loc_to_prod_su_qty_post', 'pssController@paspul_loc_to_prod_su_qty_post');
Route::post('paspul_loc_to_prod_su_op_post', 'pssController@paspul_loc_to_prod_su_op_post');
Route::post('paspul_loc_to_prod_su_to_post', 'pssController@paspul_loc_to_prod_su_to_post');

Route::get('paspul_loc_to_del_su_from', 'pssController@paspul_loc_to_del_su_from');
Route::post('paspul_loc_to_del_su_from_post', 'pssController@paspul_loc_to_del_su_from_post');
Route::post('paspul_loc_to_del_su_pas_post', 'pssController@paspul_loc_to_del_su_pas_post');
Route::post('paspul_loc_to_del_su_qty_post', 'pssController@paspul_loc_to_del_su_qty_post');
Route::post('paspul_loc_to_del_su_op_post', 'pssController@paspul_loc_to_del_su_op_post');

Route::get('paspul_locations', 'plannerController@paspul_locations');
Route::get('paspul_location_new', 'plannerController@paspul_location_new');
Route::post('paspul_location_new_post', 'plannerController@paspul_location_new_post');
Route::get('paspul_location_edit/{id}', 'plannerController@paspul_location_edit');
Route::post('paspul_location_edit_post', 'plannerController@paspul_location_edit_post');

Route::post('postImport_papsul_stock', 'importController@postImport_papsul_stock');
Route::post('postReturn_papsul_stock', 'importController@postReturn_papsul_stock');



// PSK
Route::get('psk', 'pskController@index');

Route::get('paspul_table_received_in_kik', 'pskController@paspul_table_received_in_kik');
Route::get('paspul_table_ready_for_kik', 'pskController@paspul_table_ready_for_kik');
Route::get('paspul_table_stock_ki', 'pskController@paspul_table_stock_ki');

Route::get('paspul_loc_to_loc_ki_from', 'pskController@paspul_loc_to_loc_ki_from');
Route::post('paspul_loc_to_loc_ki_from_post', 'pskController@paspul_loc_to_loc_ki_from_post');
Route::post('paspul_loc_to_loc_ki_pas_post', 'pskController@paspul_loc_to_loc_ki_pas_post');
Route::post('paspul_loc_to_loc_ki_qty_post', 'pskController@paspul_loc_to_loc_ki_qty_post');
Route::post('paspul_loc_to_loc_ki_op_post', 'pskController@paspul_loc_to_loc_ki_op_post');
Route::post('paspul_loc_to_loc_ki_to_post', 'pskController@paspul_loc_to_loc_ki_to_post');

Route::get('paspul_loc_to_prod_ki_from', 'pskController@paspul_loc_to_prod_ki_from');
Route::post('paspul_loc_to_prod_ki_from_post', 'pskController@paspul_loc_to_prod_ki_from_post');
Route::post('paspul_loc_to_prod_ki_pas_post', 'pskController@paspul_loc_to_prod_ki_pas_post');
Route::post('paspul_loc_to_prod_ki_qty_post', 'pskController@paspul_loc_to_prod_ki_qty_post');
Route::post('paspul_loc_to_prod_ki_op_post', 'pskController@paspul_loc_to_prod_ki_op_post');
Route::post('paspul_loc_to_prod_ki_to_post', 'pskController@paspul_loc_to_prod_ki_to_post');

Route::get('paspul_loc_to_del_ki_from', 'pskController@paspul_loc_to_del_ki_from');
Route::post('paspul_loc_to_del_ki_from_post', 'pskController@paspul_loc_to_del_ki_from_post');
Route::post('paspul_loc_to_del_ki_pas_post', 'pskController@paspul_loc_to_del_ki_pas_post');
Route::post('paspul_loc_to_del_ki_qty_post', 'pskController@paspul_loc_to_del_ki_qty_post');
Route::post('paspul_loc_to_del_ki_op_post', 'pskController@paspul_loc_to_del_ki_op_post');

Route::get('paspul_ret_ki_to_su_from', 'pskController@paspul_ret_ki_to_su_from');
Route::post('paspul_ret_ki_to_su_from_post', 'pskController@paspul_ret_ki_to_su_from_post');
Route::post('paspul_ret_ki_to_su_pas_post', 'pskController@paspul_ret_ki_to_su_pas_post');
Route::post('paspul_ret_ki_to_su_qty_post', 'pskController@paspul_ret_ki_to_su_qty_post');
Route::post('paspul_ret_ki_to_su_op_post', 'pskController@paspul_ret_ki_to_su_op_post');



// PSZ
Route::get('psz', 'pszController@index');
Route::get('paspul_table_ready_for_sen', 'pszController@paspul_table_ready_for_sen');
Route::get('paspul_table_received_in_sen', 'pszController@paspul_table_received_in_sen');
Route::get('paspul_table_stock_se', 'pszController@paspul_table_stock_se');

Route::get('paspul_loc_to_loc_se_from', 'pszController@paspul_loc_to_loc_se_from');
Route::post('paspul_loc_to_loc_se_from_post', 'pszController@paspul_loc_to_loc_se_from_post');
Route::post('paspul_loc_to_loc_se_pas_post', 'pszController@paspul_loc_to_loc_se_pas_post');
Route::post('paspul_loc_to_loc_se_qty_post', 'pszController@paspul_loc_to_loc_se_qty_post');
Route::post('paspul_loc_to_loc_se_op_post', 'pszController@paspul_loc_to_loc_se_op_post');
Route::post('paspul_loc_to_loc_se_to_post', 'pszController@paspul_loc_to_loc_se_to_post');

Route::get('paspul_loc_to_prod_se_from', 'pszController@paspul_loc_to_prod_se_from');
Route::post('paspul_loc_to_prod_se_from_post', 'pszController@paspul_loc_to_prod_se_from_post');
Route::post('paspul_loc_to_prod_se_pas_post', 'pszController@paspul_loc_to_prod_se_pas_post');
Route::post('paspul_loc_to_prod_se_qty_post', 'pszController@paspul_loc_to_prod_se_qty_post');
Route::post('paspul_loc_to_prod_se_op_post', 'pszController@paspul_loc_to_prod_se_op_post');
Route::post('paspul_loc_to_prod_se_to_post', 'pszController@paspul_loc_to_prod_se_to_post');

Route::get('paspul_loc_to_del_se_from', 'pszController@paspul_loc_to_del_se_from');
Route::post('paspul_loc_to_del_se_from_post', 'pszController@paspul_loc_to_del_se_from_post');
Route::post('paspul_loc_to_del_se_pas_post', 'pszController@paspul_loc_to_del_se_pas_post');
Route::post('paspul_loc_to_del_se_qty_post', 'pszController@paspul_loc_to_del_se_qty_post');
Route::post('paspul_loc_to_del_se_op_post', 'pszController@paspul_loc_to_del_se_op_post');

Route::get('paspul_ret_se_to_su_from', 'pszController@paspul_ret_se_to_su_from');
Route::post('paspul_ret_se_to_su_from_post', 'pszController@paspul_ret_se_to_su_from_post');
Route::post('paspul_ret_se_to_su_pas_post', 'pszController@paspul_ret_se_to_su_pas_post');
Route::post('paspul_ret_se_to_su_qty_post', 'pszController@paspul_ret_se_to_su_qty_post');
Route::post('paspul_ret_se_to_su_op_post', 'pszController@paspul_ret_se_to_su_op_post');



// WHS
Route::get('whs', 'whsController@index');
Route::get('paspul_transfer_su_ki', 'whsController@paspul_transfer_su_ki');
Route::post('paspul_transfer_su_ki_pas_post', 'whsController@paspul_transfer_su_ki_pas_post');
Route::post('paspul_transfer_su_ki_qty_post', 'whsController@paspul_transfer_su_ki_qty_post');
Route::post('paspul_transfer_su_ki_op_post', 'whsController@paspul_transfer_su_ki_op_post');

Route::get('paspul_transfer_su_se', 'whsController@paspul_transfer_su_se');
Route::post('paspul_transfer_su_se_pas_post', 'whsController@paspul_transfer_su_se_pas_post');
Route::post('paspul_transfer_su_se_qty_post', 'whsController@paspul_transfer_su_se_qty_post');
Route::post('paspul_transfer_su_se_op_post', 'whsController@paspul_transfer_su_se_op_post');

Route::get('paspul_transfer_su_va', 'whsController@paspul_transfer_su_va');
Route::post('paspul_transfer_su_va_pas_post', 'whsController@paspul_transfer_su_va_pas_post');
Route::post('paspul_transfer_su_va_qty_post', 'whsController@paspul_transfer_su_va_qty_post');
Route::post('paspul_transfer_su_va_op_post', 'whsController@paspul_transfer_su_va_op_post');

// FO
Route::get('fo', 'foController@index');
Route::get('operator_login_fo', 'foController@operator_login');
Route::get('operator_logout_fo', 'foController@operator_logout');
Route::get('request_material_accept/{id}', 'foController@request_material_accept');
Route::get('request_material_accept_confirm/{id}', 'foController@request_material_accept_confirm');

Route::get('request_material_deliver/{id}', 'foController@request_material_deliver');
Route::get('request_material_deliver_confirm/{id}', 'foController@request_material_deliver_confirm');
Route::get('request_material_deliver_confirm_post/{id}', 'foController@request_material_deliver_confirm_post');

Route::get('request_material_cancel/{id}', 'foController@request_material_cancel');
Route::post('request_material_cancel_confirm/', 'foController@request_material_cancel_confirm');

Route::get('request_material_relax_confirm/{id}', 'foController@request_material_relax_confirm');
Route::get('request_material_relax_confirm_post/{id}', 'foController@request_material_relax_confirm_post');

Route::get('request_material_qc_confirm/{id}', 'foController@request_material_qc_confirm');
Route::get('request_material_qc_confirm_post/{id}', 'foController@request_material_qc_confirm_post');

// CPO
Route::get('cpo', 'cpoController@index');
Route::get('cpo_all', 'cpoController@index_all');
Route::get('cpo_scan', 'cpoController@cpo_scan');

Route::get('operator_login_cpo', 'cpoController@operator_login');
Route::get('operator_logout_cpo', 'cpoController@operator_logout');

Route::post('cpo_header_table', 'cpoController@cpo_header_table');

Route::get('cpo_new_check/{g_bin}', 'cpoController@cpo_new_check');
Route::get('cpo_check_edit/{id}', 'cpoController@cpo_check_edit');

Route::post('cpo_insert_style_size_bundle', 'cpoController@cpo_insert_style_size_bundle');
Route::post('cpo_insert_part', 'cpoController@cpo_insert_part');

Route::post('cpo_new_check_layers', 'cpoController@cpo_new_check_layers');
Route::post('cpo_new_check_layers_post', 'cpoController@cpo_new_check_layers_post');

Route::get('cpo_edit_check_layers/{id}', 'cpoController@cpo_edit_check_layers');
Route::post('cpo_edit_check_layers_post', 'cpoController@cpo_edit_check_layers_post');

Route::get('set_status_g_bin/{g_bin}', 'cpoController@set_status_g_bin');
Route::post('set_status_g_bin_post', 'cpoController@set_status_g_bin_post');

// Search
Route::get('recap_by_skeda_mattress', 'plannerController@recap_by_skeda_mattress');
Route::post('recap_by_skeda_mattress_post', 'plannerController@recap_by_skeda_mattress_post');
Route::get('recap_by_skeda_paspul', 'plannerController@recap_by_skeda_paspul');
Route::post('recap_by_skeda_paspul_post', 'plannerController@recap_by_skeda_paspul_post');
Route::get('recap_by_g_bin_mattress', 'plannerController@recap_by_g_bin_mattress');
Route::post('recap_by_g_bin_mattress_post', 'plannerController@recap_by_g_bin_mattress_post');
Route::get('recap_by_sku_sp0', 'plannerController@recap_by_sku_sp0');
Route::post('recap_by_sku_sp0_post', 'plannerController@recap_by_sku_sp0_post');
Route::get('recap_by_sku_sp', 'plannerController@recap_by_sku_sp');
Route::post('recap_by_sku_sp_post', 'plannerController@recap_by_sku_sp_post');

// Skeda Reservation
Route::get('inbound_delivery_index', 'magacinController@inbound_delivery_index');
Route::get('inbound_delivery_table_wh', 'magacinController@inbound_delivery_table_wh');
Route::get('inbound_delivery_import', 'magacinController@inbound_delivery_import');
Route::post('postImportInbound_delivery','importController@postImportInbound_delivery');

Route::get('fabric_reservation', 'plannerController@fabric_reservation');

Route::get('inbound_delivery_table', 'plannerController@inbound_delivery_table');
Route::get('reserve_material_table', 'plannerController@reserve_material_table');
Route::get('reserve_material/{id}', 'plannerController@reserve_material');
Route::post('reserve_material_post', 'plannerController@reserve_material_post');

Route::get('fabric_reservation_table', 'plannerController@fabric_reservation_table');
Route::get('delete_reservation_q/{id}', 'plannerController@delete_reservation_q');
Route::get('delete_reservation/{id}', 'plannerController@delete_reservation');


Route::get('update_skeda_status', 'plannerController@update_skeda_status');
Route::get('declare_leftover/{id}', 'plannerController@declare_leftover');
Route::get('declare_no_leftover/{id}', 'plannerController@declare_no_leftover');
Route::post('declare_leftover_post', 'plannerController@declare_leftover_post');

Route::get('leftover_table', 'plannerController@leftover_table');

// Material comment
Route::get('material_comment_table', 'plannerController@material_comment_table');
Route::get('material_comment_new', 'plannerController@material_comment_new');
Route::post('material_comment_new_post', 'plannerController@material_comment_new_post');
Route::get('material_comment_edit/{id}', 'plannerController@material_comment_edit');
Route::post('material_comment_edit_post', 'plannerController@material_comment_edit_post');
Route::post('material_comment_delete_post', 'plannerController@material_comment_delete_post');

Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');

Route::any('getitemdata', function() {
	$term = Input::get('term');

	// $data = DB::connection('sqlsrv')->table('reservations')->distinct()->select('item')->groupBy('item')->take(10)->get();
	$data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT TOP 10 item FROM reservations WHERE item like '%".$term."%'"));
	// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 10 (RIGHT([No_],6)) as po FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] WHERE [Status] = '3' AND [No_] like '%".$term."%'"));
	// var_dump($data);
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->item);
	}
return Response::json($retun_array);
});

Route::any('getvariantdata', function() {
	$term = Input::get('term');

	// $data = DB::connection('sqlsrv')->table('reservations')->distinct()->select('variant')->groupBy('variant')->take(10)->get();
	$data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT TOP 10 variant FROM reservations WHERE variant like '%".$term."%'"));
	// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 10 (RIGHT([No_],6)) as po FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] WHERE [Status] = '3' AND [No_] like '%".$term."%'"));
	// var_dump($data);
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->variant);
	}
return Response::json($retun_array);
});

Route::any('getbatchdata', function() {
	$term = Input::get('term');

	// $data = DB::connection('sqlsrv')->table('reservations')->distinct()->select('batch')->groupBy('batch')->take(10)->get();
	$data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT TOP 10 batch FROM reservations WHERE batch like '%".$term."%'"));
	// var_dump($data);
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->batch);
	}
return Response::json($retun_array);
});

Route::any('getpodata', function() {
	$term = Input::get('term');

	
	$data = DB::connection('sqlsrv4')->select(DB::raw("SELECT DISTINCT (CASE WHEN po like '%-%' THEN substring(po, 1,6) ELSE substring (po, 4,6)	END) as po
		FROM [trebovanje].[dbo].[sap_coois] WHERE po like '%".$term."%'"));
	// var_dump($data);
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->po);
	}
return Response::json($retun_array);
});

Route::any('getpordata', function() {
	$term = Input::get('term');

	$data = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 10 po as po FROM pos WHERE status = 'OPEN' AND po like '%".$term."%'"));
	// var_dump($data);
	foreach ($data as $v) {
		$retun_array[] = array('value' => $v->po);
	}
return Response::json($retun_array);
});

