<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\paspul_stock;
use App\paspul_stock_log;
use App\paspul_label_print;
use App\paspul_stock_by_key;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class pszController extends Controller {

	public function index() {
		//
		// dd("Paspul Stock Senta");

		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('psz.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "PSZ";
		// dd($location);
		$count_received_se = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([id]) as r 
			FROM [cutting].[dbo].[paspul_stocks] 
			WHERE location = 'RECEIVED_IN_SENTA'  "));
		if (isset($count_received_se[0]->r)) {
			$count_received_se = $count_received_se[0]->r;
		} else {
			$count_received_se = 0;
		}

		$count_ready_se = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([id]) as r 
			FROM [cutting].[dbo].[paspul_stocks] 
			WHERE location = 'READY_FOR_SENTA'  "));
		if (isset($count_ready_se[0]->r)) {
			$count_ready_se = $count_ready_se[0]->r;
		} else {
			$count_ready_se = 0;
		}

		$count_sen = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Senta' and s.[location] != 'RECEIVED_IN_SENTA'    "));
		if (isset($count_sen[0]->r)) {
			$count_sen = $count_sen[0]->r;
		} else {
			$count_sen = 0;
		}


		$count_received_in_subotica = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Subotica' and s.[location] = 'RECEIVED_IN_SUBOTICA' "));
		if (isset($count_received_in_subotica[0]->r)) {
			$count_received_in_subotica = $count_received_in_subotica[0]->r;
		} else {
			$count_received_in_subotica = 0;
		}

		return view('psz.index', compact('location','count_received_se','count_ready_se','count_sen','count_received_in_subotica'));
	}

	public function paspul_table_received_in_sen () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT	s.*,
				k.[mtr_per_pcs] as [unit_cons],
				s.[fg_color_code] 
		FROM paspul_stocks as s
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE location = 'RECEIVED_IN_SENTA'
		"));
		// dd($data);
		return view('psz.table', compact('data'));
	}

	public function paspul_table_stock_se () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT	s.*,
				k.[mtr_per_pcs] as [unit_cons],
				s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE l.[plant] = 'Senta' and s.[location] != 'RECEIVED_IN_SENTA'
		"));
		// dd($data);
		return view('psz.table', compact('data'));
	}

	public function paspul_table_ready_for_sen () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT	s.*,
				k.[mtr_per_pcs] as [unit_cons],
				s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE	l.[plant] = 'Subotica' and
				s.[location] = 'READY_FOR_SENTA'
		"));
		// dd($data);
		return view('psz.table', compact('data'));
	}

// LOC TO LOC 
	public function paspul_loc_to_loc_se_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Senta'  "));

		return view('psz.paspul_loc_to_loc_se_from', compact('from'));
	}

	public function paspul_loc_to_loc_se_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE  l.[plant] = 'Senta'  "));

		if (!empty($input['location1'])) {
			$location_from = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_from = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('psz.paspul_loc_to_loc_se_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Senta' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('psz.paspul_loc_to_loc_se_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('psz.paspul_loc_to_loc_se_pas', compact('location_from', 'pas_keys'));
	}

	public function paspul_loc_to_loc_se_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = strtoupper($input['location_from']);
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('psz.paspul_loc_to_loc_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('psz.paspul_loc_to_loc_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		// dd($pas_one);

		return view('psz.paspul_loc_to_loc_se_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_loc_to_loc_se_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('psz.paspul_loc_to_loc_se_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('psz.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_loc_to_loc_se_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('psz.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('psz.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));	

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('psz.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));	
			}
		}

		$op = $input['op1'];
		// dd($op);

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Senta' and type = 'stock' and [location] != '".$location_from."' "));

		return view('psz.paspul_loc_to_loc_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to'));
	}

	public function paspul_loc_to_loc_se_to_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		$op = $input['op'];

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Senta' and type = 'stock' "));
		
		if (!empty($input['location1'])) {
			$location_to = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_to = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('psz.paspul_loc_to_loc_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
		}
		// dd($location_to);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_to."' and [plant] = 'Senta' and [type] = 'stock' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('psz.paspul_loc_to_loc_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
		}
		// dd($location_to);


		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);
		

		$pa_new = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_to."'  "));

		// FOR NEW check if exist or create
		if (isset($pa_new[0]->id)) {
			// alredy exist on location_to

			$pa1 = paspul_stock::findOrFail($pa_new[0]->id);
			$pa1->kotur_qty = $pa1->kotur_qty + $qty;
			$pa1->save();

		} else {
			// new line

			$table_stock = new paspul_stock;
			$table_stock->skeda = $pa_old->skeda;
			$table_stock->paspul_type = $pa_old->paspul_type;
			$table_stock->dye_lot = $pa_old->dye_lot;
			$table_stock->kotur_length = $pa_old->kotur_length;

			$table_stock->pas_key = $pa_old->pas_key;
			$table_stock->pas_key_e = $pa_old->pas_key_e;

			$table_stock->location = strtoupper($location_to);
			$table_stock->pas_key_location = $pa_old->pas_key.'_'.$location_to;

			$table_stock->kotur_qty = $qty;
			$table_stock->kotur_width = $pa_old->kotur_width;
			$table_stock->uom = $pa_old->uom;
			$table_stock->material = $pa_old->material;
			$table_stock->fg_color_code = $pa_old->fg_color_code;
			$table_stock->pcs_kotur = $pa_old->pcs_kotur;

			$table_stock->save();
		}

		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = strtoupper($location_from);
		$table_stock->location_to = strtoupper($location_to);
		$table_stock->location_type = 'stock';
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');

	}

// LOC TO PROD 
	public function paspul_loc_to_prod_se_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Senta'  "));

		return view('psz.paspul_loc_to_prod_se_from', compact('from'));
	}

	public function paspul_loc_to_prod_se_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Senta'  "));

		if (!empty($input['location1'])) {
			$location_from = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_from = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('psz.paspul_loc_to_prod_se_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Senta' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('psz.paspul_loc_to_prod_se_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('psz.paspul_loc_to_prod_se_pas', compact('location_from', 'pas_keys'));
	}

	public function paspul_loc_to_prod_se_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = strtoupper($input['location_from']);
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('psz.paspul_loc_to_prod_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('psz.paspul_loc_to_prod_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('psz.paspul_loc_to_prod_se_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_loc_to_prod_se_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('psz.paspul_loc_to_prod_se_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('psz.paspul_loc_to_prod_se_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_loc_to_prod_se_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('psz.paspul_loc_to_prod_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('psz.paspul_loc_to_prod_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));	

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('psz.paspul_loc_to_prod_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));
			}
		}

		$op = $input['op1'];
		// dd($op);

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Senta' and type = 'line' "));

		return view('psz.paspul_loc_to_prod_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to'));
	}

	public function paspul_loc_to_prod_se_to_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		$op = $input['op'];

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Senta' and type = 'line' "));
		
		if (!empty($input['location1'])) {
			$location_to = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_to = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan line or bb';
			return view('psz.paspul_loc_to_prod_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
		}
		// dd($location_to);

		// check line
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id,location FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_to."' and [plant] = 'Senta' and [type] = 'line' "));
		// dd($check_location);

		// check bb
		$check_bb = DB::connection('sqlsrv2')->select(DB::raw("SELECT bb.[INTKEY] as id
					,bb.[BlueBoxNum] as bb
					,st.[StyCod] as style
			FROM dbo.CNF_BlueBox AS bb 
				LEFT JOIN dbo.CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT JOIN dbo.CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY
				LEFT JOIN dbo.CNF_STYLE AS st ON sku.STYKEY = st.INTKEY
			WHERE bb.[Status] != '99' and bb.[CREATEDATE] > '2022-01-01' and bb.[INTKEY] = '".(int)$location_to."'

			UNION ALL

			SELECT bb.[INTKEY] as id
					,bb.[BlueBoxNum] as bb
					,st.[StyCod] as style
			FROM            
				[SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_BlueBox AS bb 
				LEFT JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY
				LEFT JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_STYLE AS st ON sku.STYKEY = st.INTKEY
				
			WHERE bb.[Status] != '99' and bb.[CREATEDATE] > '2022-01-01' and bb.[INTKEY] = '".(int)$location_to."'
		"));
		// dd($check_bb);

		if ( (!isset($check_location[0]->id)) AND (!isset($check_bb[0]->id)) ) {
			$msge = 'Line or BB is not correct';
			return view('psz.paspul_loc_to_prod_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
		}
		// dd($location_to);

		if (!isset($check_location[0]->id)) {
			
			$style = substr($check_bb[0]->style, 0, 4);
			if (substr($style, 0, 4) != substr($pas_one, 0, 4)) {
				
				$msge = 'BB style is different from paspul style! ';
				return view('psz.paspul_loc_to_prod_se_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
			}

			$location_to = $check_bb[0]->bb;
			$location_type = 'bb';
		} elseif (!isset($check_bb[0]->id)) {
			$location_to = $check_location[0]->location;
			$location_type = 'line';
		}

		// dd($location_to);

		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);
		
		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = strtoupper($location_from);
		$table_stock->location_to = strtoupper($location_to);
		$table_stock->location_type = $location_type;
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');
	}

// DELETE
	public function paspul_loc_to_del_se_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Senta'  and s.[location] != 'RECEIVED_IN_SENTA' "));

		return view('psz.paspul_loc_to_del_se_from', compact('from'));
	}

	public function paspul_loc_to_del_se_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Senta'  "));

		if (!empty($input['location1'])) {
			$location_from = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_from = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('psz.paspul_loc_to_del_se_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Senta' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('psz.paspul_loc_to_del_se_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('psz.paspul_loc_to_del_se_pas', compact('location_from', 'pas_keys'));
	}

	public function paspul_loc_to_del_se_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = $input['location_from'];
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('psz.paspul_loc_to_del_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('psz.paspul_loc_to_del_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('psz.paspul_loc_to_del_se_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_loc_to_del_se_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('psz.paspul_loc_to_del_se_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('psz.paspul_loc_to_del_se_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_loc_to_del_se_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('psz.paspul_loc_to_del_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('psz.paspul_loc_to_del_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));		

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('psz.paspul_loc_to_del_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));	
			}
		}

		$op = $input['op1'];
		// dd($op);

		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);

		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = strtoupper($location_from);
		$table_stock->location_to = 'DELETED';
		$table_stock->location_type = 'delete';
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');
		
	}

// RETURN
	public function paspul_ret_se_to_su_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Senta'  "));

		return view('psz.paspul_ret_se_to_su_from', compact('from'));
	}

	public function paspul_ret_se_to_su_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE  l.[plant] = 'Senta'  "));

		if (!empty($input['location1'])) {
			$location_from = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_from = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('psz.paspul_ret_se_to_su_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Senta' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('psz.paspul_ret_se_to_su_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('psz.paspul_ret_se_to_su_pas', compact('location_from', 'pas_keys'));
	}

	public function paspul_ret_se_to_su_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = strtoupper($input['location_from']);
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('psz.paspul_ret_se_to_su_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('psz.paspul_ret_se_to_su_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		// dd($pas_one);

		return view('psz.paspul_ret_se_to_su_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_ret_se_to_su_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('psz.paspul_ret_se_to_su_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('psz.paspul_ret_se_to_su_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_ret_se_to_su_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = strtoupper($input['location_from']);
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('psz.paspul_ret_se_to_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('psz.paspul_ret_se_to_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));	

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('psz.paspul_ret_se_to_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));	
			}
		}

		$op = $input['op1'];
		// dd($op);

		$location_to = 'RECEIVED_IN_SUBOTICA';

		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);
		

		$pa_new = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_to."'  "));

		// FOR NEW check if exist or create
		if (isset($pa_new[0]->id)) {
			// alredy exist on location_to

			$pa1 = paspul_stock::findOrFail($pa_new[0]->id);
			$pa1->kotur_qty = $pa1->kotur_qty + $qty;
			// $pa1->save();

		} else {
			// new line

			$table_stock = new paspul_stock;

			$table_stock->skeda = $pa_old->skeda;
			$table_stock->paspul_type = $pa_old->paspul_type;
			$table_stock->dye_lot = $pa_old->dye_lot;
			$table_stock->kotur_length = $pa_old->kotur_length;

			$table_stock->pas_key = $pa_old->pas_key;
			$table_stock->pas_key_e = $pa_old->pas_key_e;

			$table_stock->location = strtoupper($location_to);
			$table_stock->pas_key_location = $pa_old->pas_key.'_'.$location_to;

			$table_stock->kotur_qty = $qty;
			$table_stock->kotur_width = $pa_old->kotur_width;
			$table_stock->uom = $pa_old->uom;
			$table_stock->material = $pa_old->material;
			$table_stock->fg_color_code = $pa_old->fg_color_code;
			$table_stock->pcs_kotur = $pa_old->pcs_kotur;
			
			$table_stock->save();
		}

		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = strtoupper($location_from);
		$table_stock->location_to = strtoupper($location_to);
		$table_stock->location_type = 'stock';
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');
	}

}
