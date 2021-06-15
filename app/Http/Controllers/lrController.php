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
use App\mattress_phases;
use App\mattress_markers;
use App\marker_change;
use App\o_roll;
use App\o_roll_print;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class lrController extends Controller {

	public function index()
	{
		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('prw.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		
		// $work_place = substr($device, 0,2);
		$work_place = "LR";
		// Session::set('work_place',$work_place);

		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		// $operator = 'test';

		// dd("Test");
		return view('lr.index', compact('operators','operator'));
	}

	public function operator_login (Request $request) {
		//
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);
		if (isset($input['selected_operator'])) {
			$selected_operator = $input['selected_operator'];

			if ($selected_operator != '') {
				$operator = Session::set('operator', $selected_operator);
				return redirect('/lr');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/lr');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/lr');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/lr');
	}

	public function o_roll_create()	{
		
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			$work_place = "LR";
			// Session::set('work_place',$work_place);

			$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
			      ,[operator]
			      ,[device]
			      ,[device_array]
			  FROM [operators]
			  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
			// dd($operators);

			$operator = Session::get('operator');

			$msg ='Operator must be logged!';
			return view('lr.error',compact('msg', 'operator', 'operators'));
		}

		return view('lr.o_roll_create');	
	}

	public function o_roll_gbin(Request $request) {
		//
		$this->validate($request, ['gbin' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$gbin = $input['gbin'];
		// dd($gbin);

		$check_gbin = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				m.[id] ,m.[g_bin], m.[skeda], m.[mattress], m.[material],
				md.[layers_a]
		  FROM [mattresses] as m
		  JOIN [mattress_details] as md ON md.[mattress_id] = m.[id]
		  WHERE [g_bin] = '".$gbin."' "));
		// dd($check_gbin);

		if (!isset($check_gbin[0]->id)) {
			$work_place = "LR";
			// Session::set('work_place',$work_place);

			$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
			      ,[operator]
			      ,[device]
			      ,[device_array]
			  FROM [operators]
			  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
			// dd($operators);

			$operator = Session::get('operator');

			$msg ='G bin (mattress) not found!';
			// return view('lr.o_roll_create',compact('msg', 'operator', 'operators'));
			return view('lr.index', compact('operators','operator','msg'));
		}

		$id = $check_gbin[0]->id;
		$mattress = $check_gbin[0]->mattress;
		$g_bin = $check_gbin[0]->g_bin;
		$skeda = $check_gbin[0]->skeda;
		$material = $check_gbin[0]->material;

		return view('lr.o_roll_lr_scan',compact('id', 'mattress', 'g_bin', 'skeda', 'material'));

	}

	public function o_roll_lr_scan(Request $request) {
		//
		$this->validate($request, ['o_roll' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$o_roll = $input['o_roll'];
		$id = $input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$skeda = $input['skeda'];
		$material = $input['material'];

		// dd(strlen($o_roll));
		if ((substr($o_roll , 0,2) != "LR") OR (strlen($o_roll) != 10)){
			$work_place = "LR";
			// Session::set('work_place',$work_place);

			$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
			      ,[operator]
			      ,[device]
			      ,[device_array]
			  FROM [operators]
			  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
			// dd($operators);

			$operator = Session::get('operator');

			$msg = "Scaned roll is not LR, or doesen't have 10 chars.";
			// return view('lr.o_roll_lr_scan',compact('id', 'mattress', 'g_bin', 'skeda', 'msg', 'operator', 'operators', 'material'));
			return view('lr.index', compact('operators','operator','msg'));
		}

		$check_o_roll = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			id
		  FROM [o_rolls]
		  WHERE [o_roll] = '".$o_roll."' "));

		if (isset($check_o_roll[0]->id)) {
			$work_place = "LR";
			// Session::set('work_place',$work_place);

			$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
			      ,[operator]
			      ,[device]
			      ,[device_array]
			  FROM [operators]
			  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
			// dd($operators);

			$operator = Session::get('operator');

			$msg = "This roll ".$o_roll." already exist in table.";
			// return view('lr.o_roll_lr_scan',compact('id', 'mattress', 'g_bin', 'skeda', 'msg', 'material'));
			return view('lr.index', compact('operators','operator','msg'));
		}

		return view('lr.o_roll_insert_parts',compact('id', 'mattress', 'g_bin', 'skeda','o_roll', 'material'));
	}

	public function o_roll_insert_parts(Request $request) {
		//
		// $this->validate($request, ['no_of_joinings' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$o_roll = $input['o_roll'];
		$id = $input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$skeda = $input['skeda'];
		$material = $input['material'];
		$no_of_joinings = (float)$input['no_of_joinings'];

		if ($no_of_joinings < 1) {
			$work_place = "LR";
			// Session::set('work_place',$work_place);

			$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
			      ,[operator]
			      ,[device]
			      ,[device_array]
			  FROM [operators]
			  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
			// dd($operators);

			$operator = Session::get('operator');
			// return Redirect::to('/lr');
			$msg = "Number of parts must be set!";
			// return view('lr.o_roll_insert_parts',compact('id', 'mattress', 'g_bin', 'skeda','o_roll', 'msg'));
			return view('lr.index', compact('operators','operator','msg'));
		}

		// add to mattress_phases
		$table = new o_roll;
		$table->o_roll = $o_roll;
		$table->mattress_id_orig = (int)$id;
		$table->mattress_name_orig = $mattress;
		$table->g_bin = $g_bin;
		$table->material = $material;
		$table->skeda = $skeda;

		$table->mattress_id_new;
		
		$table->status = "CREATED";
		$table->no_of_joinings = (float)$no_of_joinings;
		$table->operator = Session::get('operator');
		$table->save();

		$work_place = "LR";
		// Session::set('work_place',$work_place);

		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		// return Redirect::to('/lr');
		$success = "Roll successfuly saved";
		return view('lr.index', compact('operators','operator', 'success'));

	}

	public function o_roll_print() {

		return view('lr.o_roll_print');
	}

	public function o_roll_print_confirm(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		if ($input['printer_name'] == '' OR $input['no'] == '' OR $input['labels'] == '') {

			$msge = 'All fields should be populated';
			return view('lr.o_roll_print', compact('msge'));
		}

		$printer = $input['printer_name'];
		$labels = $input['labels'];
		$no = (int)$input['no'];
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			TOP 1 [o_roll]
		  FROM [o_roll_prints] ORDER BY o_roll desc"));	

		if (isset($data[0]->o_roll)) {
				
			$last_used = $data[0]->o_roll;
			$lu = (int)substr($last_used, 2);
			// dd($lu);
		} else {
			$lu = 0;
		}

		$from = $labels."".str_pad($lu+1, 8, 0, STR_PAD_LEFT);
		$to = $labels."".str_pad($lu+1+$no, 8, 0, STR_PAD_LEFT);
		// var_dump($labels.''.$num);

		return view('lr.o_roll_print_confirm_print', compact('printer','labels','no','lu','from','to'));
	}

	public function o_roll_print_confirm_print(Request $request) {

		$input = $request->all(); 
		// dd($input);
		
		$printer = $input['printer'];
		$labels = $input['labels'];
		$no = (int)$input['no'];
		$lu = (int)$input['lu'];

		for ($i=$lu+1; $i < $lu+$no+1; $i++) { 
			
			// dd($i);
			$num = str_pad($i, 8, 0, STR_PAD_LEFT);
			// var_dump($labels.''.$num);

			$box = new o_roll_print;
			$box->o_roll = $labels.''.$num;
			$box->printer = $printer;
			$box->printed = 1;
			$box->save();

			// var_dump($box->bag);
		}

		return Redirect::to('/');
	}
}
