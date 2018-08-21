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
Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

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

// Komesa
Route::get('po', 'poController@index');
Route::get('new_po', 'poController@new_po');
Route::post('post_new_po', 'poController@post_new_po');
Route::get('/edit_status/{id}', 'poController@edit_status');
Route::post('/edit_status/{id}', 'poController@update_status');
Route::get('/edit_po/{id}', 'poController@edit_po');
Route::post('/edit_po/{id}', 'poController@update_po');


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

	$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT TOP 10 (RIGHT([No_],6)) as po FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] WHERE [Status] = '3' AND [No_] like '%".$term."%'"));
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

