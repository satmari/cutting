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
use App\paspul_rewound;
use App\material_requests;
use App\material_request_phases;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class prwController extends Controller {
	/*
	public function index() {
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
		$location = "PRW";
		// dd($location);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_roll]
		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      ,p1.[rewound_length_a]

		      ,p1.[pasbin]
		      ,p1.[skeda_item_type]
		      ,p1.[skeda]
		      ,p1.[skeda_status]

		      ,p1.[rewound_roll_unit_of_measure]
		      ,p1.[position]
		      ,p1.[priority]
		      ,p1.[comment_office]
		      ,p1.[comment_operator]
		      ,p1.[call_shift_manager]
		      ,p1.[rewinding_method]
		      ,p1.[created_at]
		      ,p1.[updated_at]

		      ,p2.[status]
		      ,p2.[location]
		      ,p2.[device]
		      ,p2.[active]
		      ,p2.[operator1]
		      ,p2.[operator2]

		      ,(SELECT SUM([rewound_length_partialy]) FROM [cutting].[dbo].[paspul_rewounds]
				WHERE [paspul_roll_id] = p1.[id]) as rewound_sum

		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id]
		  WHERE p2.[location] = '".$location."' AND p2.[active] = '1' 
		  ORDER BY p1.[position] asc"));
		// dd($data);

		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';
		
		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			
			$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				ps.pro
				,ps.style_size
				,ps.sku
				,po.[location_all]
				--,*
			  FROM  [pro_skedas] as ps 
			  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
			WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
			// dd($prom);

			for ($x=0; $x < count($prom); $x++) { 

				$pros .= $prom[$x]->pro." ";
				$skus .= $prom[$x]->style_size." ";
				$test = str_replace(' ', '&nbsp;' , $prom[$x]->sku);
				$sku_s .= $test." ";
				// $sku_s .= $prom[$x]->sku." ";
				if ($prom[$x]->location_all == 'Valy') {
					$location_all .= $prom[$x]->location_all.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'." ";
				} else {
					$location_all .= $prom[$x]->location_all." ";
				}
			}

			$data[$i]->pro = trim($pros);
			$data[$i]->style_size = trim($skus);
			$data[$i]->sku = trim($sku_s);
			$data[$i]->location_all = trim($location_all);
			$pros = '';
			$skus = '';
			$sku_s = '';
			$location_all = '';
		}

		// $work_place = substr($device, 0,2);
		$work_place = "PRW";
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
		return view('prw.index', compact('data','location','operators','operator'));
	}
	*/

	public function operator_login(Request $request) {
		//
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);
		if (isset($input['selected_operator'])) {
			$selected_operator = $input['selected_operator'];

			if ($selected_operator != '') {
				$operator = Session::set('operator', $selected_operator);
				return redirect('/prw1');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/prw1');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/prw1');
		}
	}

	public function operator_logout() {
		
		$operator = Session::set('operator', NULL);
		return redirect('/prw1');
	}

	/*
	public function paspul_prw($id) {
		// dd($id);

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
			return view('prw.error',compact('msg', 'operator', 'operators'));
		}
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_roll]
		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      ,p1.[rewound_length_a]

		      ,p1.[pasbin]
		      ,p1.[skeda_item_type]
		      ,p1.[skeda]
		      ,p1.[skeda_status]

		      ,p1.[rewound_roll_unit_of_measure]
		      ,p1.[position]
		      ,p1.[priority]
		      ,p1.[comment_office]
		      ,p1.[comment_operator]
		      ,p1.[call_shift_manager]
		      ,p1.[rewinding_method]

		      ,p1.[created_at]
		      ,p1.[updated_at]
		      
		      ,p2.[paspul_roll_id]
		      ,p2.[status]
		      ,p2.[location]
		      ,p2.[device]
		      ,p2.[active]
		      ,p2.[operator1]
		      ,p2.[operator2]

		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id] and p2.[active] = 1 
		  WHERE p1.[id] = '".$id."' "));

		$paspul_roll = $data[0]->paspul_roll;
		$pasbin = $data[0]->pasbin;
		$skeda_item_type = $data[0]->skeda_item_type;
		$priority = $data[0]->priority;
		$comment_office = $data[0]->comment_office;
		$comment_operator = $data[0]->comment_operator;
		$call_shift_manager = $data[0]->call_shift_manager;
		$rewinding_method = $data[0]->rewinding_method;

		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$paspul_roll_id = $data[0]->paspul_roll_id;

		$rewound_length_a = $data[0]->rewound_length_a;

		return view('prw.paspul_prw', compact('id','paspul_roll','rewound_length_a','comment_office'
			,'material','skeda','dye_lot','color_desc','skeda_item_type','rewinding_method','priority','call_shift_manager','comment_operator','comment_office'));
	}

	public function paspul_prw_confirm(Request $request) {
		
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$paspul_roll = $input['paspul_roll'];
		$rewound_length_a = round((float)$input['rewound_length_a'],2);
		$comment_operator = $input['comment_operator'];
		
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



		$location = "PCO";
		//$position = 0; // auto ??????????????????????????????????????
		$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
		FROM 
		(
		SELECT position 
		FROM [paspuls] as p
		JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND active = '1'
		WHERE pl.[location] = '".$location."'
		) SQ
		ORDER BY position desc"));

		if (isset($position[0])) {
			$position = (int)$position[0]->position;
		} else {
			$position = 0;
		}
		$position = $position + 1;

		// $priority = 0; // 0
		// $comment_office = ''; // ''
		// $comment_operator = ''; // ''
		// $call_shift_manager = 0; // default 0
		// $rewinding_method = $row['rewinding_method'];

		//-----
		$status = 'TO_CUT';
		$location = 'PCO'; 
		$device = strtoupper($device); //null or insert  			//????????????????
		$active = 1;
		$operator1 = Session::get('operator');
		$operator2;
		//-----
		// dd("Stop");
		// try {

			$find_all_papul_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				[id], [paspul_roll], [paspul_roll_id]
			FROM [paspul_lines] WHERE [paspul_roll_id] = '".$id."' AND active = 1"));
		
			if (isset($find_all_papul_lines[0])) {
				$paspul_roll = $find_all_papul_lines[0]->paspul_roll;
				// dd($find_all_papul_lines);

				for ($i=0; $i < count($find_all_papul_lines); $i++) {

					$table3 = paspul_line::findOrFail($find_all_papul_lines[$i]->id);
					$table3->active = 0;
					$table3->save();
				}
			}

			$table = paspul::findOrFail($id);

			// $table->paspul_roll = $paspul_roll;
			// $table->sap_su = $sap_su;
			// $table->material = $material;
			// $table->color_desc = $color_desc;
			// $table->dye_lot = $dye_lot;
			// $table->paspul_type = $paspul_type;
			// $table->width = $width;
			// $table->kotur_width = $kotur_width;
			// $table->kotur_width_without_tension = $kotur_width_without_tension;
			// $table->kotur_planned = $koturi_planned;
			// $table->kotur_actual;
			// $table->rewound_length = $rewound_length;
			$table->rewound_length_a = $rewound_length_a;

			// $table->pasbin = $pas_bin;
			// $table->skeda_item_type = $skeda_item_type;
			// $table->skeda = $skeda;
			// $table->skeda_status = $skeda_status;
			// $table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
			$table->position = $position;
			// $table->priority = $priority;
			// $table->comment_office = $comment_office;
			$table->comment_operator = $comment_operator;
			// $table->call_shift_manager = $call_shift_manager;
			// $table->rewinding_method = $rewinding_method;
			$table->save();

			// reorder position of PRW
			$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					p.[id], p.[paspul_roll], p.[position], 
					pl.[location], pl.[active], pl.[paspul_roll_id]
				 FROM [paspuls] as p
				 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
				 WHERE pl.[location] = 'PRW'
				 ORDER BY p.[position] asc"));
			// dd($reorder_position);

			if (isset($reorder_position[0])) {
				for ($i=0; $i < count($reorder_position); $i++) { 

					$table1 = paspul::findOrFail($reorder_position[$i]->id);
					$table1->position = $i+1;
					$table1->save();
				}
			}

			if ((date('H') >= 0) AND (date('H') < 6)) {
			   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
			} else {
				$date = date('Y-m-d H:i:s');
			}

			$table_p = new paspul_line;

			$table_p->paspul_roll_id = $table->id;
			$table_p->paspul_roll = $table->paspul_roll;
			$table_p->status = $status;
			$table_p->location = $location;
			$table_p->device = $device;
			$table_p->active = $active;
			$table_p->operator1 = Session::get('operator');
			$table_p->operator2;
			$table_p->date = $date;
			$table_p->save();

		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd('Problem to save');
		// }

		return redirect('/');
	}
	*/

	//new prw

	public function index1() {
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
		$location = "PRW";
		// dd($location);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_roll]
		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      ,p1.[rewound_length_a]

		      ,p1.[pasbin]
		      ,p1.[skeda_item_type]
		      ,p1.[skeda]
		      ,p1.[skeda_status]

		      ,p1.[rewound_roll_unit_of_measure]
		      ,p1.[position]
		      ,p1.[priority]
		      ,p1.[comment_office]
		      ,p1.[comment_operator]
		      ,p1.[call_shift_manager]
		      ,p1.[rewinding_method]
		      ,p1.[tpa_number]
		      ,p1.[created_at]
		      ,p1.[updated_at]

		      ,p2.[status]
		      ,p2.[location]
		      ,p2.[device]
		      ,p2.[active]
		      ,p2.[operator1]
		      ,p2.[operator2]

		      ,(SELECT SUM([rewound_length_partialy]) FROM [cutting].[dbo].[paspul_rewounds]
				WHERE [paspul_roll_id] = p1.[id]) as rewound_sum

		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id]
		  WHERE p2.[location] = '".$location."' AND p2.[active] = '1' 
		  ORDER BY p1.[position] asc"));
		// dd($data);

		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';
		
		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			
			$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				ps.[pro]
				,ps.[style_size]
				,ps.[sku]
				,po.[location_all]
				--,*
			  FROM  [pro_skedas] as ps 
			  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
			WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
			// dd($prom);

			for ($x=0; $x < count($prom); $x++) { 

				$pros .= $prom[$x]->pro." ";
				$skus .= $prom[$x]->style_size." ";
				$test = str_replace(' ', '&nbsp;' , $prom[$x]->sku);
				$sku_s .= $test." ";
				// $sku_s .= $prom[$x]->sku." ";
				if ($prom[$x]->location_all == 'Valy') {
					$location_all .= $prom[$x]->location_all.'&nbsp;'.'&nbsp;'.'&nbsp;'.'&nbsp;'." ";
				} else {
					$location_all .= $prom[$x]->location_all." ";
				}
			}

			$data[$i]->pro = trim($pros);
			$data[$i]->style_size = trim($skus);
			$data[$i]->sku = trim($sku_s);
			$data[$i]->location_all = trim($location_all);
			$pros = '';
			$skus = '';
			$sku_s = '';
			$location_all = '';
		}

		// $work_place = substr($device, 0,2);
		$work_place = "PRW";
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
		return view('prw.index1', compact('data','location','operators','operator'));
	}

	public function paspul_prw1($id) {
		// dd($id);

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
			return view('prw.error',compact('msg', 'operator', 'operators'));
		}
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_roll]
		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      ,p1.[rewound_length_a]

		      ,p1.[pasbin]
		      ,p1.[skeda_item_type]
		      ,p1.[skeda]
		      ,p1.[skeda_status]

		      ,p1.[rewound_roll_unit_of_measure]
		      ,p1.[position]
		      ,p1.[priority]
		      ,p1.[comment_office]
		      ,p1.[comment_operator]
		      ,p1.[call_shift_manager]
		      ,p1.[rewinding_method]

		      ,p1.[created_at]
		      ,p1.[updated_at]
		      --,'|'
		      ,p2.[paspul_roll_id]
		      ,p2.[status]
		      ,p2.[location]
		      ,p2.[device]
		      ,p2.[active]
		      ,p2.[operator1]
		      ,p2.[operator2]

		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id] and p2.[active] = 1 
		  WHERE p1.[id] = '".$id."' "));

		$paspul_roll = $data[0]->paspul_roll;
		$pasbin = $data[0]->pasbin;
		$skeda_item_type = $data[0]->skeda_item_type;
		$priority = $data[0]->priority;
		$comment_office = $data[0]->comment_office;
		$comment_operator = $data[0]->comment_operator;
		$call_shift_manager = $data[0]->call_shift_manager;
		$rewinding_method = $data[0]->rewinding_method;

		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$paspul_roll_id = $data[0]->paspul_roll_id;

		$rewound_length = $data[0]->rewound_length;
		$rewound_length_a = $data[0]->rewound_length_a;

		$rewound_length_done = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([rewound_length_partialy]) as no
		FROM [cutting].[dbo].[paspul_rewounds]
		WHERE [paspul_roll_id] = '".$id."' "));
		$rewound_length_done = round((float)$rewound_length_done[0]->no,2);
		// dd($rewound_length_done);

		$no_of_rewound_rolls = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([paspul_rewound_roll]) as no
		FROM [cutting].[dbo].[paspul_rewounds]
		WHERE [paspul_roll_id] = '".$id."' "));

		$no_of_rewound_rolls = (int)$no_of_rewound_rolls[0]->no;

				
		return view('prw.paspul_prw1', compact('id','paspul_roll','rewound_length','rewound_length_a','comment_office'
			,'material','skeda','dye_lot','color_desc','skeda_item_type','rewinding_method','priority','call_shift_manager','comment_operator','comment_office','rewound_length_done','no_of_rewound_rolls'));
	}

	public function paspul_prw1_confirm(Request $request) {
		
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$paspul_roll = $input['paspul_roll'];
		//$rewound_length_a = round((float)$input['rewound_length_a'],2);
		$rewound_length_partialy = round((float)$input['rewound_length_partialy'],2);
		$comment_operator = $input['comment_operator'];
		if (isset($input['last_roll'])) {
			if ($input['last_roll'] == "YES") {
				$last_roll = "YES";
			}
		} else {
			$last_roll = "NO";
		}
		// dd($last_roll);

		if ($rewound_length_partialy <= 0) {
			dd("rewound_length_partialy must be <= 0");
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('prw.error',compact('msg'));
		}

		$no_of_rewound_rolls = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([paspul_rewound_roll]) as no
		FROM [cutting].[dbo].[paspul_rewounds]
		WHERE [paspul_roll_id] = '".$id."' "));

		$number = (int)$no_of_rewound_rolls[0]->no;
		$no = $number + 1;
		$no = str_pad($no, 2, '0', STR_PAD_LEFT);
		// dd($no);

		$find_rewound_length_partialy = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([rewound_length_partialy]) as rewound_sum
		FROM [cutting].[dbo].[paspul_rewounds]
		WHERE [paspul_roll_id] = '".$id."' "));
		$rewound_length_a = round($find_rewound_length_partialy[0]->rewound_sum,2) + round($rewound_length_partialy,2);
		// dd($rewound_length_a);


		$find_rewound_length_planned = DB::connection('sqlsrv')->select(DB::raw("SELECT rewound_length
		FROM [cutting].[dbo].[paspuls]
		WHERE [id] = '".$id."' "));
		$rewound_length_planned = round($find_rewound_length_planned[0]->rewound_length,2);
		// dd($rewound_length_planned);

		if ($last_roll == "YES" OR ($rewound_length_a >= $rewound_length_planned)) {

				$location = "PCO";
				//$position = 0; // auto ??????????????????????????????????????
				$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
				FROM 
				(
				SELECT position 
				FROM [paspuls] as p
				JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND active = '1'
				WHERE pl.[location] = '".$location."'
				) SQ
				ORDER BY position desc"));

				if (isset($position[0])) {
					$position = (int)$position[0]->position;
				} else {
					$position = 0;
				}
				$position = $position + 1;

				//-----
				$status = 'TO_CUT';
				$location = 'PCO'; 
				$device = strtoupper($device); //null or insert  
				$active = 1;
				$operator1 = Session::get('operator');
				$operator2;
				//-----
				
					$find_all_papul_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						[id], [paspul_roll], [paspul_roll_id]
					FROM [paspul_lines] WHERE [paspul_roll_id] = '".$id."' AND active = 1"));
				
					if (isset($find_all_papul_lines[0])) {
						$paspul_roll = $find_all_papul_lines[0]->paspul_roll;
						// dd($find_all_papul_lines);

						for ($i=0; $i < count($find_all_papul_lines); $i++) {

							$table3 = paspul_line::findOrFail($find_all_papul_lines[$i]->id);
							$table3->active = 0;
							$table3->save();
						}
					}

					// sum rewound_length_partialy for all rols
					$find_rewound_length_partialy = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([rewound_length_partialy]) as rewound_sum
					FROM [cutting].[dbo].[paspul_rewounds]
					WHERE [paspul_roll_id] = '".$id."' "));
					$rewound_length_a = (int)$find_rewound_length_partialy[0]->rewound_sum + (int)$rewound_length_partialy;

					$table = paspul::findOrFail($id);

					// $table->paspul_roll = $paspul_roll;
					// $table->sap_su = $sap_su;
					// $table->material = $material;
					// $table->color_desc = $color_desc;
					// $table->dye_lot = $dye_lot;
					// $table->paspul_type = $paspul_type;
					// $table->width = $width;
					// $table->kotur_width = $kotur_width;
					// $table->kotur_width_without_tension = $kotur_width_without_tension;
					// $table->kotur_planned = $koturi_planned;
					// $table->kotur_actual;
					// $table->rewound_length = $rewound_length;
					$table->rewound_length_a = $rewound_length_a;

					// $table->pasbin = $pas_bin;
					// $table->skeda_item_type = $skeda_item_type;
					// $table->skeda = $skeda;
					// $table->skeda_status = $skeda_status;
					// $table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
					$table->position = $position;
					// $table->priority = $priority;
					// $table->comment_office = $comment_office;
					$table->comment_operator = $comment_operator;
					// $table->call_shift_manager = $call_shift_manager;
					// $table->rewinding_method = $rewinding_method;
					$table->save();

					// reorder position of PRW
					$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
							p.[id], p.[paspul_roll], p.[position], 
							pl.[location], pl.[active], pl.[paspul_roll_id]
						 FROM [paspuls] as p
						 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
						 WHERE pl.[location] = 'PRW'
						 ORDER BY p.[position] asc"));
					// dd($reorder_position);

					if (isset($reorder_position[0])) {
						for ($i=0; $i < count($reorder_position); $i++) { 

							$table1 = paspul::findOrFail($reorder_position[$i]->id);
							$table1->position = $i+1;
							$table1->save();
						}
					}

					if ((date('H') >= 0) AND (date('H') < 6)) {
					   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
					} else {
						$date = date('Y-m-d H:i:s');
					}

					$table_p = new paspul_line;

					$table_p->paspul_roll_id = $table->id;
					$table_p->paspul_roll = $table->paspul_roll;
					$table_p->status = $status;
					$table_p->location = $location;
					$table_p->device = $device;
					$table_p->active = $active;
					$table_p->operator1 = Session::get('operator');
					$table_p->operator2;
					$table_p->date = $date;
					$table_p->save();

					
				$table_r = new paspul_rewound;

				$table_r->paspul_rewound_roll = $table->paspul_roll."-".$no;
				$table_r->rewound_length_partialy = $rewound_length_partialy;
				$table_r->kotur_partialy;
				
				$table_r->status = "TO_CUT";

				$table_r->paspul_roll_id = $id;
				$table_r->paspul_roll = $table->paspul_roll;
				$table_r->sap_su = $table->sap_su;
				$table_r->material = $table->material;
				$table_r->color_desc = $table->color_desc;
				$table_r->dye_lot = $table->dye_lot;
				$table_r->paspul_type = $table->paspul_type;

				$table_r->width = $table->width;
				$table_r->kotur_width = $table->kotur_width;
				$table_r->kotur_width_without_tension = $table->kotur_width_without_tension;
				$table_r->kotur_planned = $table->kotur_planned;
				// $table_r->kotur_actual = $table->kotur_actual;
				$table_r->rewound_length = $table->rewound_length;
				// $table_r->rewound_length_a = $table->rewound_length_a;

				$table_r->pasbin = $table->pasbin;
				$table_r->skeda_item_type = $table->skeda_item_type;
				$table_r->skeda = $table->skeda;
				$table_r->skeda_status = $table->skeda_status;
				$table_r->rewound_roll_unit_of_measure = $table->rewound_roll_unit_of_measure;
				$table_r->position = $table->position;
				$table_r->priority = $table->priority;
				$table_r->comment_office = $table->comment_office;
				$table_r->comment_operator = $comment_operator;
				$table_r->call_shift_manager = $table->call_shift_manager;

				$table_r->rewinding_method = $table->rewinding_method;
				$table_r->tpa_number = $table->tpa_number;

				$table_r->save();

		} else {

			$table = paspul::findOrFail($id);
		
			$table_r = new paspul_rewound;

			$table_r->paspul_rewound_roll = $table->paspul_roll."-".$no;
			$table_r->rewound_length_partialy = $rewound_length_partialy;
			$table_r->kotur_partialy;
			
			$table_r->status = "TO_CUT";

			$table_r->paspul_roll_id = $id;
			$table_r->paspul_roll = $table->paspul_roll;
			$table_r->sap_su = $table->sap_su;
			$table_r->material = $table->material;
			$table_r->color_desc = $table->color_desc;
			$table_r->dye_lot = $table->dye_lot;
			$table_r->paspul_type = $table->paspul_type;

			$table_r->width = $table->width;
			$table_r->kotur_width = $table->kotur_width;
			$table_r->kotur_width_without_tension = $table->kotur_width_without_tension;
			$table_r->kotur_planned = $table->kotur_planned;
			// $table_r->kotur_actual = $table->kotur_actual;
			$table_r->rewound_length = $table->rewound_length;
			// $table_r->rewound_length_a = $table->rewound_length_a;

			$table_r->pasbin = $table->pasbin;
			$table_r->skeda_item_type = $table->skeda_item_type;
			$table_r->skeda = $table->skeda;
			$table_r->skeda_status = $table->skeda_status;
			$table_r->rewound_roll_unit_of_measure = $table->rewound_roll_unit_of_measure;
			$table_r->position = $table->position;
			$table_r->priority = $table->priority;
			$table_r->comment_office = $table->comment_office;
			$table_r->comment_operator = $comment_operator;
			$table_r->call_shift_manager = $table->call_shift_manager;

			$table_r->rewinding_method = $table->rewinding_method;
			$table_r->tpa_number = $table->tpa_number;

			$table_r->save();
		}

		return redirect('/');
	}

	public function finish_rewound ($id){
		
		// dd($id);

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('prw.error',compact('msg'));
		}

		$location = "PCO";
		//$position = 0; // auto ??????????????????????????????????????
		$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
		FROM 
		(
		SELECT position 
		FROM [paspuls] as p
		JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND active = '1'
		WHERE pl.[location] = '".$location."'
		) SQ
		ORDER BY position desc"));

		if (isset($position[0])) {
			$position = (int)$position[0]->position;
		} else {
			$position = 0;
		}
		$position = $position + 1;

		//-----
		$status = 'TO_CUT';
		$location = 'PCO'; 
		$device = strtoupper($device); //null or insert  
		$active = 1;
		$operator1 = Session::get('operator');
		$operator2;
		//-----
		
		$find_all_papul_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			[id], [paspul_roll], [paspul_roll_id]
		FROM [paspul_lines] WHERE [paspul_roll_id] = '".$id."' AND active = 1"));
	
		if (isset($find_all_papul_lines[0])) {
			$paspul_roll = $find_all_papul_lines[0]->paspul_roll;
			// dd($find_all_papul_lines);

			for ($i=0; $i < count($find_all_papul_lines); $i++) {

				$table3 = paspul_line::findOrFail($find_all_papul_lines[$i]->id);
				$table3->active = 0;
				$table3->save();
			}
		}

		// sum rewound_length_partialy for all rols
		$find_rewound_length_partialy = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		SUM([rewound_length_partialy]) as rewound_sum
		FROM [cutting].[dbo].[paspul_rewounds]
		WHERE [paspul_roll_id] = '".$id."' "));
		$rewound_length_a = (int)$find_rewound_length_partialy[0]->rewound_sum;

		$table = paspul::findOrFail($id);

		// $table->paspul_roll = $paspul_roll;
		// $table->sap_su = $sap_su;
		// $table->material = $material;
		// $table->color_desc = $color_desc;
		// $table->dye_lot = $dye_lot;
		// $table->paspul_type = $paspul_type;
		// $table->width = $width;
		// $table->kotur_width = $kotur_width;
		// $table->kotur_width_without_tension = $kotur_width_without_tension;
		// $table->kotur_planned = $koturi_planned;
		// $table->kotur_actual;
		// $table->rewound_length = $rewound_length;
		$table->rewound_length_a = $rewound_length_a;

		// $table->pasbin = $pas_bin;
		// $table->skeda_item_type = $skeda_item_type;
		// $table->skeda = $skeda;
		// $table->skeda_status = $skeda_status;
		// $table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
		$table->position = $position;
		// $table->priority = $priority;
		// $table->comment_office = $comment_office;
		// $table->comment_operator = $comment_operator;
		// $table->call_shift_manager = $call_shift_manager;
		// $table->rewinding_method = $rewinding_method;
		$table->save();

		// reorder position of PRW
		$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				p.[id], p.[paspul_roll], p.[position], 
				pl.[location], pl.[active], pl.[paspul_roll_id]
			 FROM [paspuls] as p
			 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
			 WHERE pl.[location] = 'PRW'
			 ORDER BY p.[position] asc"));
		// dd($reorder_position);

		if (isset($reorder_position[0])) {
			for ($i=0; $i < count($reorder_position); $i++) { 

				$table1 = paspul::findOrFail($reorder_position[$i]->id);
				$table1->position = $i+1;
				$table1->save();
			}
		}

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		$table_p = new paspul_line;

		$table_p->paspul_roll_id = $table->id;
		$table_p->paspul_roll = $table->paspul_roll;
		$table_p->status = $status;
		$table_p->location = $location;
		$table_p->device = $device;
		$table_p->active = $active;
		$table_p->operator1 = Session::get('operator');
		$table_p->operator2;
		$table_p->date = $date;
		$table_p->save();

		return redirect('/');
	}

	public function request_material($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('prw.error',compact('msg'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			      p.[material]
				  ,p.[dye_lot]
			FROM [cutting].[dbo].[paspuls] as p
			INNER JOIN [cutting].[dbo].[paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] and pl.[active] = 1
			WHERE p.[id] = '".$id."' "));
		// dd($data);

		$material = $data[0]->material;
		$dye_lot  = $data[0]->dye_lot;

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('prw.error',compact('msg'));
		}
		$location = substr($device, 0,3);
		$sap_locations = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM sap_locations "));

		return view('prw.request_material',compact('material','dye_lot','location','device','sap_locations'));
	}

	public function request_material_insert(Request $request) {
		//
		// $this->validate($request, ['sap_location1' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$material = $input['material'];
		$dye_lot = $input['dye_lot'];
		$location = $input['location'];
		$device = $input['device'];

		$sap_location1 = NULL;
		$sap_location2 = NULL;
		$sap_location3 = NULL;
		$sap_location4 = NULL;
		$sap_location5 = NULL;
		$sap_location6 = NULL;

		if ((int)$input['required_qty'] == 0 ) {
			$msg ='For PRW user, required qty is mandatory, please add.';
			return view('prw.error',compact('msg'));
		} else {
			$required_qty = (int)$input['required_qty'];
		}

		if ($input['comment'] == '') {
			$comment = NULL;
		} else {
			$comment = $input['comment'];
		}
		
		$operator = Session::get('operator');

		$table = new material_requests;
		$table->material = $material;
		$table->dye_lot = $dye_lot;
		$table->sap_location1;
		$table->sap_location2;
		$table->sap_location3;
		$table->sap_location4;
		$table->sap_location5;
		$table->sap_location6;
		$table->required_qty = $required_qty;
		$table->comment = $comment;
		$table->save();

		$status = 'CREATED';

		$table_phases = material_request_phases::firstOrNew(['id_status' => $table->id.'-'.$status]);
		$table_phases->material_request_id = $table->id;
		$table_phases->status = $status;
		$table_phases->location = $location;
		$table_phases->device = $device;
		$table_phases->active = 1;
		$table_phases->operator1 = $operator;
		$table_phases->operator2;
		$table_phases->id_status = $table->id."-".$status;
		$table_phases->save();

		return redirect('request_material_table');
	}

	

}