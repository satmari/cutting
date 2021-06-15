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
Route::get('cons', 'consController@index');
Route::get('cons_table', 'consController@cons_table');
Route::get('update_cons_table', 'consController@update_cons_table');
Route::get('add_po_cons_table', 'consController@add_po_cons_table');
Route::get('add_po', 'consController@add_po');
Route::post('add_new_po_cons', 'consController@add_new_po_cons');

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
// Route::post('/req_cut_part_status1', 'requestController@req_cut_part_status1');

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

//http://172.27.161.171/cutting/bb/519205
Route::get('/bb/{line}/{id}','cut_pcsController@req_cut_part');
Route::post('requeststore_cut_part', 'cut_pcsController@requeststore_cut_part');
Route::get('req_extrabb_table', 'requestController@req_extrabb_table');
Route::get('req_extrabb_table_history', 'requestController@req_extrabb_table_history');

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

Route::post('delete_wastage_line','wastageController@delete_wastage_line');

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

Route::get('wastage_table_import','importController@index');
Route::post('postImportWastage_report','importController@postImportWastage_report');
Route::post('postImport_marker','importController@postImport_marker');
Route::post('postImport_skeda','importController@postImport_skeda');

// Cutting XML
Route::get('cutting_xml', 'cutting_xml@cutting_xml');
Route::get('cutting_xml_all', 'cutting_xml@cutting_xml_all');

// Operators
Route::get('operators', 'operatorsController@index');
Route::get('operator_create', 'operatorsController@operator_create');
Route::post('operator_create_post', 'operatorsController@operator_create_post');
Route::get('operator_edit/{id}', 'operatorsController@operator_edit');
Route::post('operator_edit_post', 'operatorsController@operator_edit_post');

// Marker
Route::get('marker', 'markerController@index');
Route::get('marker_details/{id}', 'markerController@index_line');
Route::post('marker_line_confirm', 'markerController@marker_line_confirm');

// Pro Skeda
Route::get('pro_skeda', 'pro_skedaController@index');

// Paspul
Route::get('paspul', 'paspulController@index');

// Paspul_bin
Route::get('paspul_bin', 'paspul_binController@index');

// Matress
Route::get('mattress', 'mattressController@index');

// Admin & Planner
// Route::get('plan_mattress', 'plannerController@plan_mattress');
Route::get('plan_mattress/{location}', 'plannerController@plan_mattress');
// Route::post('posts/reposition', 'plannerController@reposition');
Route::get('operator_login_planner', 'plannerController@operator_login');
Route::get('operator_logout_planner', 'plannerController@operator_logout');
Route::post('posts/reposition' , [ 'uses' => 'plannerController@reposition', 'as' => 'posts.reposition' ]);
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

Route::get('plan_mattress_line/{id}', 'plannerController@plan_mattress_line');
Route::post('plan_mattress_line_confirm', 'plannerController@plan_mattress_line_confirm');
Route::get('change_marker/{id}', 'plannerController@change_marker');
Route::post('change_marker_post', 'plannerController@change_marker_post');
Route::get('delete_mattress/{id}', 'plannerController@delete_mattress');
Route::post('delete_mattress_confirm', 'plannerController@delete_mattress_confirm');
Route::get('edit_mattress_line/{id}', 'plannerController@edit_mattress');
Route::post('edit_mattress_line_confirm', 'plannerController@edit_mattress_confirm');

Route::get('plan_mini_marker', 'plannerController@plan_mini_marker');
Route::get('mini_marker_create', 'plannerController@mini_marker_create');
Route::post('mini_marker_create_1', 'plannerController@mini_marker_create_1');
Route::post('mini_marker_create_2', 'plannerController@mini_marker_create_2');
Route::post('mini_marker_add_marker', 'plannerController@mini_marker_add_marker');

Route::get('o_roll_table', 'plannerController@o_roll_table');
Route::get('o_roll_table_all', 'plannerController@o_roll_table_all');
Route::get('o_roll_delete/{id}', 'plannerController@o_roll_delete');
Route::post('o_roll_delete_confirm', 'plannerController@o_roll_delete_confirm');

Route::get('plan_paspul/{location}', 'plannerController@plan_paspul');
Route::get('plan_paspul_line/{id}', 'plannerController@plan_paspul_line');
Route::post('plan_paspul_line_confirm', 'plannerController@plan_paspul_line_confirm');
Route::get('remove_paspul_line/{id}', 'plannerController@remove_paspul_line');
Route::post('paspul_delete_confirm', 'plannerController@paspul_delete_confirm');

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

// Spreader
Route::get('spreader', 'spreaderController@index');
Route::get('operator_login', 'spreaderController@operator_login');
Route::get('operator_logout', 'spreaderController@operator_logout');
Route::get('other_functions/{id}', 'spreaderController@other_functions');
Route::get('mattress_to_load/{id}', 'spreaderController@mattress_to_load');
Route::get('mattress_to_unload/{id}', 'spreaderController@mattress_to_unload');
Route::get('mattress_to_spread/{id}', 'spreaderController@mattress_to_spread');
Route::get('other_functions/{id}', 'spreaderController@other_functions');
Route::post('add_operator_comment', 'spreaderController@add_operator_comment');
Route::get('change_marker_request/{id}', 'spreaderController@change_marker_request');
Route::post('change_marker_request_post', 'spreaderController@change_marker_request_post');
// Route::get('create_new_mattress_request/{id}', 'spreaderController@create_new_mattress_request');
Route::get('spread_mattress_partial/{id}', 'spreaderController@spread_mattress_partial');
Route::post('spread_mattress_partial_post', 'spreaderController@spread_mattress_partial_post');
Route::get('spread_mattress_complete/{id}', 'spreaderController@spread_mattress_complete');
Route::post('spread_mattress_complete_post', 'spreaderController@spread_mattress_complete_post');

// CUT
Route::get('cutter', 'cutterController@index');
Route::get('operator_login_cut', 'cutterController@operator_login');
Route::get('operator_logout_cut', 'cutterController@operator_logout');
Route::get('mattress_to_cut/{id}', 'cutterController@mattress_to_cut');
Route::get('mattress_cut/{id}', 'cutterController@mattress_cut');
Route::post('mattress_cut_post', 'cutterController@mattress_cut_post');
Route::get('other_functions_cut/{id}', 'cutterController@other_functions');
Route::post('add_operator_comment_cut', 'cutterController@add_operator_comment');

// PACK
Route::get('pack', 'packController@index');
Route::get('operator_login_pack', 'packController@operator_login');
Route::get('operator_logout_pack', 'packController@operator_logout');
Route::get('mattress_pack/{id}', 'packController@mattress_pack');
Route::get('mattress_pack_confirm/{id}', 'packController@mattress_pack_confirm');

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
Route::get('mattress_prw/{id}', 'prwController@mattress_prw');
Route::get('mattress_prw_confirm/{id}', 'prwController@mattress_prw_confirm');
Route::get('paspul_prw/{id}', 'prwController@paspul_prw');
Route::post('paspul_prw_confirm', 'prwController@paspul_prw_confirm');

// PCO
Route::get('pco', 'pcoController@index');
Route::get('operator_login_pco', 'pcoController@operator_login');
Route::get('operator_logout_pco', 'pcoController@operator_logout');
Route::get('mattress_pco/{id}', 'pcoController@mattress_pco');
Route::get('mattress_pco_confirm/{id}', 'pcoController@mattress_pco_confirm');
Route::get('paspul_pco/{id}', 'pcoController@paspul_pco');
Route::post('paspul_pco_confirm', 'pcoController@paspul_pco_confirm');

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

	// $data = DB::connection('sqlsrv1')->select(DB::raw("SELECT TOP 10 (RIGHT([No_],6)) as po FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] WHERE [Status] = '3' AND [No_] like '%".$term."%'"));
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

