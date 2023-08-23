<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\mattress_details;
use App\mattress_markers;
use App\marker_change;
use App\paspul;
use App\paspul_line;
use App\paspul_stock;
// use App\paspul_rewound;
use App\paspul_stock_log;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class whsController extends Controller {


	public function index()	{
		//
		// dd('test whs');
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
		$location = "WHS";
		// dd($location);
		$count_ready_for_ki = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([id]) as c 
			FROM [cutting].[dbo].[paspul_stocks] 
			WHERE location = 'READY_FOR_KIKINDA'  "));
		if (isset($count_ready_for_ki[0]->c)) {
			$count_ready_for_ki = $count_ready_for_ki[0]->c;
		} else {
			$count_ready_for_ki = 0;
		}

		$count_ready_for_se = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([id]) as c 
			FROM [cutting].[dbo].[paspul_stocks] 
			WHERE location = 'READY_FOR_SENTA'  "));
		if (isset($count_ready_for_se[0]->c)) {
			$count_ready_for_se = $count_ready_for_se[0]->c;
		} else {
			$count_ready_for_se = 0;
		}

		$count_ready_for_va = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([id]) as c 
			FROM [cutting].[dbo].[paspul_stocks] 
			WHERE location = 'READY_FOR_VALY'  "));
		if (isset($count_ready_for_va[0]->c)) {
			$count_ready_for_va = $count_ready_for_va[0]->c;
		} else {
			$count_ready_for_va = 0;
		}
	
		return view('whs.index', compact('location','count_ready_for_ki','count_ready_for_se','count_ready_for_va'));
	}

// TRANSFER SU KI
	public function paspul_transfer_su_ki () {

		$location_from = 'READY_FOR_KIKINDA';

		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('whs.paspul_loc_to_loc_ki_pas', compact('location_from','pas_keys'));
	}

	public function paspul_transfer_su_ki_pas_post (Request $request) {
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
			return view('whs.paspul_loc_to_loc_ki_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('whs.paspul_loc_to_loc_ki_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('whs.paspul_loc_to_loc_ki_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_transfer_su_ki_qty_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('whs.paspul_loc_to_loc_ki_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('whs.paspul_loc_to_loc_ki_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_transfer_su_ki_op_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('whs.paspul_loc_to_loc_ki_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('whs.paspul_loc_to_loc_ki_op', compact('location_from', 'pas_one', 'qty', 'msge'));	

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('whs.paspul_loc_to_loc_ki_op', compact('location_from', 'pas_one', 'qty', 'msge'));	
			}
		}

		$op = $input['op1'];

		$location_to = "RECEIVED_IN_KIKINDA";

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

			$table_stock->location = $location_to;
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
		$table_stock->location_from = $location_from;
		$table_stock->location_to = $location_to;
		$table_stock->location_type = 'transfer';
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

// TRANSFER SU SE
	public function paspul_transfer_su_se () {

		$location_from = 'READY_FOR_SENTA';

		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('whs.paspul_loc_to_loc_se_pas', compact('location_from','pas_keys'));
	}

	public function paspul_transfer_su_se_pas_post (Request $request) {
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
			return view('whs.paspul_loc_to_loc_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('whs.paspul_loc_to_loc_se_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('whs.paspul_loc_to_loc_se_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_transfer_su_se_qty_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('whs.paspul_loc_to_loc_se_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('whs.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_transfer_su_se_op_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('whs.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('whs.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));		

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('whs.paspul_loc_to_loc_se_op', compact('location_from', 'pas_one', 'qty', 'msge'));	
			}
		}

		$op = $input['op1'];

		$location_to = "RECEIVED_IN_SENTA";

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

			$table_stock->location = $location_to;
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
		$table_stock->location_from = $location_from;
		$table_stock->location_to = $location_to;
		$table_stock->location_type = 'transfer';
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

// TRANSFER SU VA
	public function paspul_transfer_su_va () {

		$location_from = 'READY_FOR_VALY';

		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('whs.paspul_loc_to_loc_va_pas', compact('location_from','pas_keys'));
	}

	public function paspul_transfer_su_va_pas_post (Request $request) {
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
			return view('whs.paspul_loc_to_loc_va_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('whs.paspul_loc_to_loc_va_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('whs.paspul_loc_to_loc_va_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_transfer_su_va_qty_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('whs.paspul_loc_to_loc_va_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('whs.paspul_loc_to_loc_va_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_transfer_su_va_op_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('whs.paspul_loc_to_loc_va_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('whs.paspul_loc_to_loc_va_op', compact('location_from', 'pas_one', 'qty', 'msge'));	

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('whs.paspul_loc_to_loc_va_op', compact('location_from', 'pas_one', 'qty', 'msge'));	
			}
		}

		$op = $input['op1'];

		$location_to = "RECEIVED_IN_VALY";

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

			$table_stock->location = $location_to;
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
		$table_stock->location_from = $location_from;
		$table_stock->location_to = $location_to;
		$table_stock->location_type = 'transfer';
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
