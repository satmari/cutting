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
use App\paspul_rewound;
use App\paspul_stock_u_cons;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class pcoController extends Controller {
	
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
			return view('pco.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "PCO";
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
		      ,p1.[tpa_number]

		      ,p2.[status]
		      ,p2.[location]
		      ,p2.[device]
		      ,p2.[active]
		      ,p2.[operator1]
		      ,p2.[operator2]

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
		$work_place = "PCO";
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
		return view('pco.index', compact('data','location','operators','operator'));
	}
	*/

	public function operator_login (Request $request) {
		//
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);
		if (isset($input['selected_operator'])) {
			$selected_operator = $input['selected_operator'];

			if ($selected_operator != '') {
				$operator = Session::set('operator', $selected_operator);
				return redirect('/pco1');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/pco1');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/pco1');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/pco1');
	}

	/*
	public function paspul_pco($id) {
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
			return view('pco.error',compact('msg', 'operator', 'operators'));
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

		$rewound_length_a = $data[0]->rewound_length_a;
		$kotur_actual = $data[0]->kotur_actual;
		
				
		return view('pco.paspul_pco', compact('id','paspul_roll','rewound_length_a','comment_office'
			,'material','skeda','dye_lot','color_desc','skeda_item_type','rewinding_method','priority','call_shift_manager','comment_operator','comment_office'
			,'kotur_actual'));
	}

	public function paspul_pco_confirm(Request $request) {
		
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$paspul_roll = $input['paspul_roll'];
		$kotur_actual = round((float)$input['kotur_actual'],2);
		$comment_operator = $input['comment_operator'];
		
		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pco.error',compact('msg'));
		}

		$location = "COMPLETED";
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
		$status = 'COMPLETED';
		$location = 'COMPLETED'; 
		$device = strtoupper($device); //null or insert  			//????????????????
		$active = 1;
		$operator1 = Session::get('operator');
		$operator2;
		//-----
		
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
			
			$paspul_lines_not_active = DB::connection('sqlsrv')->update(DB::raw("
				UPDATE [paspul_lines]
				SET active = 0, id_status = ''+cast([paspul_roll_id] as varchar )+'-'+[status]
				WHERE [paspul_roll_id] = '".$id."' AND active = 1
			"));

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
			$table->kotur_actual = $kotur_actual;
			// $table->rewound_length = $rewound_length;
			// $table->rewound_length_a = $rewound_length_a;

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
				 WHERE pl.[location] = 'PCO'
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
			$table_p->id_status = $table_p->paspul_roll_id.'-'.$table_p->status;
			$table_p->save();

		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd('Problem to save');
		// }

		return redirect('/');
	}
	*/

// new pco1

	public function index1() {
		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pco.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "PCO";
		// dd($location);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_rewound_roll]
		      ,p1.[rewound_length_partialy]
		      ,p1.[kotur_partialy]
		      ,p1.[paspul_roll_id]
		      ,p1.[status]

		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      -- ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      --,p1.[rewound_length_a]

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

		  FROM [paspul_rewounds] as p1
		  WHERE status = 'TO_CUT'
		  ORDER BY p1.[priority] desc, p1.[paspul_rewound_roll] asc"));
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
			// $fg_color_code = substr($prom[0]->sku,9,4);
			// dd($fg_color_code);
			
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
		$work_place = "PCO";
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
		return view('pco.index1', compact('data','location','operators','operator'));
	}

	public function paspul_pco1($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			$work_place = "PCO";
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
			return view('pco.error',compact('msg', 'operator', 'operators'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_rewound_roll]
		      ,p1.[rewound_length_partialy]
		      ,p1.[kotur_partialy]
		      ,p1.[paspul_roll_id]
		      ,p1.[paspul_roll]
		      ,p1.[status]

		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      -- ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      --,p1.[rewound_length_a]

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

		  FROM [paspul_rewounds] as p1
		  WHERE status = 'TO_CUT' and p1.[id] = '".$id."'
		  ORDER BY p1.[paspul_rewound_roll] asc "));
		   

		$paspul_rewound_roll = $data[0]->paspul_rewound_roll;
		$rewound_length_partialy = $data[0]->rewound_length_partialy;
		$kotur_partialy = $data[0]->kotur_partialy;
		$status = $data[0]->status;

		$paspul_roll_id = $data[0]->paspul_roll_id;
		$paspul_roll = $data[0]->paspul_roll;
		$sap_su = $data[0]->sap_su;
		$material = $data[0]->material;
		$color_desc = $data[0]->color_desc;
		$dye_lot = $data[0]->dye_lot;
		$paspul_type = $data[0]->paspul_type;

		$width = $data[0]->width;
		$kotur_width = $data[0]->kotur_width;
		$kotur_width_without_tension = $data[0]->kotur_width_without_tension;
		$kotur_planned = $data[0]->kotur_planned;
		$rewound_length = $data[0]->rewound_length;

		$pasbin = $data[0]->pasbin;
		$skeda_item_type = $data[0]->skeda_item_type;
		$skeda = $data[0]->skeda;
		$skeda_status = $data[0]->skeda_status;
		$rewound_roll_unit_of_measure = $data[0]->rewound_roll_unit_of_measure;
		$position = $data[0]->position;
		$priority = $data[0]->priority;
		$comment_office = $data[0]->comment_office;
		$comment_operator = $data[0]->comment_operator;
		$call_shift_manager = $data[0]->call_shift_manager;

		$rewinding_method = $data[0]->rewinding_method;
		$tpa_number = $data[0]->tpa_number;

		return view('pco.paspul_pco1', compact('id','paspul_roll','rewound_length_a','comment_office'
			,'material','skeda','dye_lot','color_desc','skeda_item_type','rewinding_method','priority','call_shift_manager','comment_operator','comment_office'
			,'kotur_partialy','paspul_rewound_roll','paspul_roll_id'));
	}

	public function paspul_pco1_confirm(Request $request) {
		
		$this->validate($request, ['kotur_partialy' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$paspul_rewound_roll = $input['paspul_rewound_roll'];

		$paspul_roll = $input['paspul_roll'];
		$paspul_roll_id = $input['paspul_roll_id'];

		$kotur_partialy = (int)$input['kotur_partialy'];
		$comment_operator = $input['comment_operator'];
		
		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pco.error',compact('msg'));
		}


		// CHECK FATHER
		$find_all_paspuls = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  p1.[id]
		      ,p1.[paspul_roll]
		      ,p2.[status]
		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id] and p2.[active] = 1 
		  WHERE p1.[id] = '".$paspul_roll_id."'"));

		$father_status = $find_all_paspuls[0]->status;
		// dd($father_status);

		if ($father_status == "TO_CUT") {
			
			// CHECK ALL CHILD if completed
			$find_all_paspuls = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					[id], [paspul_roll], [paspul_roll_id], [status] , [kotur_partialy]
				FROM [paspul_rewounds] 
				WHERE [paspul_roll_id] = '".$paspul_roll_id."' AND
				id != '".$id."' ")); // SKIP THIS CHILD
			// dd($find_all_paspuls);

			$to_completed = 0;
			for ($i=0; $i < count($find_all_paspuls); $i++) {
			
				if ($find_all_paspuls[$i]->status != "COMPLETED") {
					$to_completed = $to_completed + 1;
				} else {
					$to_completed = $to_completed;
				}
			}
			// dd($to_completed);

			if ($to_completed == 0) {
				// ALL CHILD (expt this) ARE COMPLETED
				$no_of_child = count($find_all_paspuls);
				$no_of_child = $no_of_child+1;
				// dd($no_of_child);

				$sum_of_child_kotur = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(kotur_partialy) as sum_k
					FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = '".$paspul_roll_id."' "));
				$sum_of_child_kotur = (int)$sum_of_child_kotur[0]->sum_k + $kotur_partialy;
				// dd($sum_of_child_kotur);
				
				$kotur_actual = (int)$sum_of_child_kotur / (int)$no_of_child;
				$kotur_actual = round($kotur_actual,1);
				// dd($kotur_actual);

				// COMPLETED FATHER
					$location = "COMPLETED";
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
					$status = 'COMPLETED';
					$location = 'COMPLETED'; 
					$device = strtoupper($device); //null or insert
					$active = 1;
					$operator1 = Session::get('operator');
					$operator2;
					//-----
					
					$paspul_lines_not_active = DB::connection('sqlsrv')->update(DB::raw("
						UPDATE [paspul_lines]
						SET active = 0, id_status = ''+cast([paspul_roll_id] as varchar )+'-'+[status]
						WHERE [paspul_roll_id] = '".$paspul_roll_id."' AND active = 1
					"));

					$table = paspul::findOrFail($paspul_roll_id);
					$table->kotur_actual = $kotur_actual; // CALCULATIONS
					$table->position = $position;
					$table->comment_operator = $comment_operator;
					$table->save();

					// reorder position of PRW
					$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
							p.[id], p.[paspul_roll], p.[position], 
							pl.[location], pl.[active], pl.[paspul_roll_id]
						 FROM [paspuls] as p
						 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
						 WHERE pl.[location] = 'PCO'
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
					$table_p->id_status = $table_p->paspul_roll_id.'-'.$table_p->status;
					$table_p->save();


				//

			} else {
				// ALL CHILD (expt this) ARE NOT COMPLETED
			}
		}

		// UPDATE PASPUL REWOUND 
		$rewound_roll_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		 		COUNT(p.[id]) as no
		 	FROM [paspul_rewounds] as p
		 	WHERE p.[status] = 'COMPLETED'
		 	"));

		$number = (int)$rewound_roll_position[0]->no;
		$rewound_roll_position = $number + 1;
		// dd($rewound_roll_position);

		$table_prw = paspul_rewound::findOrFail($id);
		$table_prw->kotur_partialy = $kotur_partialy;
		$table_prw->status = "COMPLETED";
		$table_prw->position = $rewound_roll_position; //new
		$table_prw->comment_operator = $comment_operator;
		$table_prw->save();


		// NEW TABLE
		
		// $pros= '';
		// $sty= '';
		// $sku_s= '';
		// $location_all= '';

		// $prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 	--ps.[pro]
		// 	--,ps.[style_size]
		// 	--,ps.[style]
		// 	ps.[sku]
		// 	--,po.[location_all]
		//   	FROM  [pro_skedas] as ps 
		//   	LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
		// 	WHERE ps.[skeda] = '".$table->skeda."' "));
		// dd($prom);

		// $fg_color_code = substr($prom[0]->sku,9,4);
		// dd($fg_color_code);

		// $table = paspul::findOrFail($paspul_roll_id);
		// $table_prw = paspul_rewound::findOrFail($id);

		$prom1 =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[pro_skedas]
		WHERE [skeda] =  '".$table_prw->skeda."' "));

		$fg_color_code = substr($prom1[0]->sku,9,4);
		// var_dump($fg_color_code);

		// take data

		

		$skeda = $table_prw->skeda;
		$paspul_type = $table_prw->paspul_type;
		$dye_lot = $table_prw->dye_lot;
		$rewound_length_partialy = round($table_prw->rewound_length_partialy,2);

		$pas_key = $skeda.'_'.$paspul_type.'_'.$dye_lot.'_'.$rewound_length_partialy;
		
		$location = 'JUST_CUT';
		$pas_key_location = $pas_key.'_'.$location;

		$kotur_qty = $kotur_partialy;
		$kotur_width = $table_prw->kotur_width;
		$material = $table_prw->material;
		$rewound_roll_unit_of_measure = $table_prw->rewound_roll_unit_of_measure;

		$fg_per_kotur;
		$status;
		$operator = 'PCO';

		// check if check_mtr_per_pcs
		$check_mtr_per_pcs = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			mtr_per_pcs
			FROM  [paspul_stock_u_cons] 
		  	WHERE [skeda] = '".$skeda."' AND [paspul_type] = '".$paspul_type."' "));

		if (isset($check_mtr_per_pcs[0]->mtr_per_pcs)) {
				
			if ($rewound_roll_unit_of_measure == 'meter') {
				
				$pcs_kotur = floor($rewound_length_partialy / $check_mtr_per_pcs[0]->mtr_per_pcs);

			} elseif ($rewound_roll_unit_of_measure == 'ploce') {

				$pcs_kotur = floor($rewound_length_partialy * $check_mtr_per_pcs[0]->mtr_per_pcs);

			} else {
				
				$pcs_kotur = NULL;
			}
		} else {
			$pcs_kotur = NULL;
		}

		// check if pas_key_location exist
		$check_if_exist = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			[id],[pas_key_location]
			FROM  [paspul_stocks] 
		  	WHERE pas_key_location = '".$pas_key_location."' "));

		if (isset($check_if_exist[0]->pas_key_location)) {
			
			// update stock
			$id = $check_if_exist[0]->id;	
			
			$table_stock = paspul_stock::findOrFail($id);
			$table_stock->kotur_qty = $table_stock->kotur_qty + $kotur_qty;
			$table_stock->save();

		} else {

			// inster new line

			$table_stock = new paspul_stock;

			$table_stock->skeda = $skeda;
			$table_stock->paspul_type = $paspul_type;
			$table_stock->dye_lot = $dye_lot;
			$table_stock->kotur_length = $rewound_length_partialy;

			$table_stock->pas_key = $pas_key;
			$table_stock->pas_key_e = strtoupper(sprintf('%08x', crc32($pas_key)));

			$table_stock->pas_key_location = $pas_key_location;
			$table_stock->location = $location;

			$table_stock->kotur_qty = $kotur_qty;
			$table_stock->kotur_width = $kotur_width;
			$table_stock->uom = $rewound_roll_unit_of_measure;
			$table_stock->material = $material;
			$table_stock->fg_color_code = $fg_color_code;
			$table_stock->pcs_kotur = $pcs_kotur;

			$table_stock->save();
		}

		//  paspul_stock_u_cons
		$skeda_paspul_type = $table_stock->skeda.'_'.$table_stock->paspul_type;

		$check_if_exist_skeda_paspul_type = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [paspul_stock_u_cons]
			WHERE skeda_paspul_type = '".$skeda_paspul_type."' "));

		if (!isset($check_if_exist_skeda_paspul_type[0])) {
			
			$table_u_cons = new paspul_stock_u_cons;
			$table_u_cons->skeda_paspul_type = $skeda_paspul_type;
			$table_u_cons->skeda = $table_stock->skeda;
			$table_u_cons->paspul_type = $table_stock->paspul_type;

			$style_1 = substr($table_stock->skeda, 0, 9);
			$style = rtrim($style_1, '0');
			$table_u_cons->style = $style;
			$table_u_cons->mtr_per_pcs;

			$table_u_cons->save();
		}

		return redirect('/');
	}

// new pco2

	public function index2() {
		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pco.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "PCO";
		// dd($location);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_rewound_roll]
		      ,p1.[rewound_length_partialy]
		      ,p1.[kotur_partialy]
		      ,p1.[paspul_roll_id]
		      ,p1.[status]

		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      -- ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      --,p1.[rewound_length_a]

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

		  FROM [paspul_rewounds] as p1
		  WHERE status = 'TO_CUT'
		  ORDER BY p1.[priority] desc, p1.[paspul_rewound_roll] asc"));
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
			// $fg_color_code = substr($prom[0]->sku,9,4);
			// dd($fg_color_code);
			
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
		$work_place = "PCO";
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
		return view('pco.index2', compact('data','location','operators','operator'));
	}

	public function paspul_pco2($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			$work_place = "PCO";
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
			return view('pco.error',compact('msg', 'operator', 'operators'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

			  p1.[id]
		      ,p1.[paspul_rewound_roll]
		      ,p1.[rewound_length_partialy]
		      ,p1.[kotur_partialy]
		      ,p1.[paspul_roll_id]
		      ,p1.[paspul_roll]
		      ,p1.[status]

		      ,p1.[sap_su]
		      ,p1.[material]
		      ,p1.[color_desc]
		      ,p1.[dye_lot]
		      ,p1.[paspul_type]

		      ,p1.[width]
		      ,p1.[kotur_width]
		      ,p1.[kotur_width_without_tension]
		      ,p1.[kotur_planned]
		      -- ,p1.[kotur_actual]
		      ,p1.[rewound_length]
		      --,p1.[rewound_length_a]

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

		  FROM [paspul_rewounds] as p1
		  WHERE status = 'TO_CUT' and p1.[id] = '".$id."'
		  ORDER BY p1.[paspul_rewound_roll] asc "));
		// dd($data);
		
		$paspul_rewound_roll_id = $data[0]->id;
		$paspul_rewound_roll = $data[0]->paspul_rewound_roll;
		$rewound_length_partialy = $data[0]->rewound_length_partialy;
		$kotur_partialy = $data[0]->kotur_partialy;
		$status = $data[0]->status;

		$paspul_roll_id = $data[0]->paspul_roll_id;
		$paspul_roll = $data[0]->paspul_roll;
		$sap_su = $data[0]->sap_su;
		$material = $data[0]->material;
		$color_desc = $data[0]->color_desc;
		$dye_lot = $data[0]->dye_lot;
		$paspul_type = $data[0]->paspul_type;

		$width = $data[0]->width;
		$kotur_width = $data[0]->kotur_width;
		$kotur_width_without_tension = $data[0]->kotur_width_without_tension;
		$kotur_planned = $data[0]->kotur_planned;
		$rewound_length = $data[0]->rewound_length;

		$pasbin = $data[0]->pasbin;
		$skeda_item_type = $data[0]->skeda_item_type;
		$skeda = $data[0]->skeda;
		$skeda_status = $data[0]->skeda_status;
		$rewound_roll_unit_of_measure = $data[0]->rewound_roll_unit_of_measure;
		$position = $data[0]->position;
		$priority = $data[0]->priority;
		$comment_office = $data[0]->comment_office;
		$comment_operator = $data[0]->comment_operator;
		$call_shift_manager = $data[0]->call_shift_manager;

		$rewinding_method = $data[0]->rewinding_method;
		$tpa_number = $data[0]->tpa_number;

		return view('pco.paspul_pco2', compact('id','paspul_roll','rewound_length_a','comment_office'
			,'material','skeda','dye_lot','color_desc','skeda_item_type','rewinding_method','priority','call_shift_manager','comment_operator','comment_office'
			,'kotur_partialy','paspul_rewound_roll','paspul_rewound_roll_id','paspul_roll_id'));
	}

	public function paspul_pco2_confirm(Request $request) {
		
		$this->validate($request, ['kotur_partialy' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$paspul_rewound_roll = $input['paspul_rewound_roll'];
		$paspul_rewound_roll_id = $input['paspul_rewound_roll_id'];
		$paspul_roll = $input['paspul_roll'];
		$paspul_roll_id = $input['paspul_roll_id'];
		$kotur_partialy = (int)$input['kotur_partialy'];
		$comment_operator = $input['comment_operator'];
		
		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pco.error',compact('msg'));
		}

		// CHECK FATHER
		$find_father_status = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  p1.[id]
		      ,p1.[paspul_roll]
		      ,p2.[status]
		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id] and p2.[active] = 1 
		  WHERE p1.[id] = '".$paspul_roll_id."'"));

		$father_status = $find_father_status[0]->status;
		// dd($father_status);

		// CHECK if all childs are completed
			$if_is_last_completed_roll = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					[id], [paspul_roll], [paspul_roll_id], [status] , [kotur_partialy]
				FROM [paspul_rewounds] 
				WHERE [paspul_roll_id] = '".$paspul_roll_id."' AND
				id != '".$paspul_rewound_roll_id."' ")); // SKIP THIS CHILD
			// dd($if_is_last_completed_roll);

			$to_completed = 0;
			for ($i=0; $i < count($if_is_last_completed_roll); $i++) {
			
				if ($if_is_last_completed_roll[$i]->status != "COMPLETED") {
					$to_completed = $to_completed + 1;
				} else {
					$to_completed = $to_completed;
				}
			}
			// dd($to_completed);
			if ($to_completed == 0) {
				$last_completed_roll == 'YES';
			} else {
				$last_completed_roll = 'NO';
			}
		//

		if (($father_status == "TO_CUT") AND ($last_completed_roll = 'YES')) {
			
			// ALL CHILDS (expt this) ARE COMPLETED
			$no_of_child = count($find_last_roll);
			$no_of_child = $no_of_child+1;
			// dd($no_of_child);

			$sum_of_child_kotur = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				SUM(kotur_partialy) as sum_k
				FROM [paspul_rewounds]
				WHERE [paspul_roll_id] = '".$paspul_roll_id."'  AND
				id != '".$paspul_rewound_roll_id."' "));
			$sum_of_child_kotur = (int)$sum_of_child_kotur[0]->sum_k + $kotur_partialy;
			// dd($sum_of_child_kotur);
			
			$kotur_actual = (int)$sum_of_child_kotur / (int)$no_of_child;
			$kotur_actual = round($kotur_actual,1);
			// dd($kotur_actual);

			// save to paspul father
				$location = "COMPLETED";
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
				$status = 'COMPLETED';
				$location = 'COMPLETED'; 
				$device = strtoupper($device); //null or insert
				$active = 1;
				$operator1 = Session::get('operator');
				$operator2;
				//-----
				
					$paspul_lines_not_active = DB::connection('sqlsrv')->update(DB::raw("
						UPDATE [paspul_lines]
						SET active = 0, id_status = ''+cast([paspul_roll_id] as varchar )+'-'+[status]
						WHERE [paspul_roll_id] = '".$paspul_roll_id."' AND active = 1
					"));


					$table = paspul::findOrFail($paspul_roll_id);
					$table->kotur_actual = $kotur_actual; // CALCULATIONS
					$table->position = $position;
					$table->comment_operator = $comment_operator;
					$table->save();

					// reorder position of PRW
					$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
							p.[id], p.[paspul_roll], p.[position], 
							pl.[location], pl.[active], pl.[paspul_roll_id]
						 FROM [paspuls] as p
						 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
						 WHERE pl.[location] = 'PCO'
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
					$table_p->id_status = $table_p->paspul_roll_id.'-'.$table_p->status;
					$table_p->save();
			//

			// save to paspul_rewound

				$rewound_roll_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				 		COUNT(p.[id]) as no
				 	FROM [paspul_rewounds] as p
				 	WHERE p.[status] = 'COMPLETED'
				 	"));

				$number = (int)$rewound_roll_position[0]->no;
				$rewound_roll_position = $number + 1;
				// dd($rewound_roll_position);

				$table_prw = paspul_rewound::findOrFail($id);
				$table_prw->kotur_partialy = $kotur_partialy;
				$table_prw->status = "COMPLETED";
				$table_prw->position = $rewound_roll_position; //new
				$table_prw->comment_operator = $comment_operator;
				$table_prw->save();
			//


		} else {

			// UPDATE PASPUL REWOUND 
				$rewound_roll_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				 		COUNT(p.[id]) as no
				 	FROM [paspul_rewounds] as p
				 	WHERE p.[status] = 'COMPLETED'
				 	"));

				$number = (int)$rewound_roll_position[0]->no;
				$rewound_roll_position = $number + 1;
				// dd($rewound_roll_position);

				$table_prw = paspul_rewound::findOrFail($id);
				$table_prw->kotur_partialy = $kotur_partialy;
				$table_prw->status = "COMPLETED";
				$table_prw->position = $rewound_roll_position; //new
				$table_prw->comment_operator = $comment_operator;
				$table_prw->save();

		}

		
		/*
		// paspul stock part 

			// $pros= '';
			// $sty= '';
			// $sku_s= '';
			// $location_all= '';

			// $prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			// 	--ps.[pro]
			// 	--,ps.[style_size]
			// 	--,ps.[style]
			// 	ps.[sku]
			// 	--,po.[location_all]
			//   	FROM  [pro_skedas] as ps 
			//   	LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
			// 	WHERE ps.[skeda] = '".$table->skeda."' "));
			// dd($prom);

			// $fg_color_code = substr($prom[0]->sku,9,4);
			// dd($fg_color_code);

			// $table = paspul::findOrFail($paspul_roll_id);
			// $table_prw = paspul_rewound::findOrFail($id);

			$prom1 =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[pro_skedas]
			WHERE [skeda] =  '".$table_prw->skeda."' "));

			$fg_color_code = substr($prom1[0]->sku,9,4);
			// var_dump($fg_color_code);

			// take data

			

			$skeda = $table_prw->skeda;
			$paspul_type = $table_prw->paspul_type;
			$dye_lot = $table_prw->dye_lot;
			$rewound_length_partialy = round($table_prw->rewound_length_partialy,2);

			$pas_key = $skeda.'_'.$paspul_type.'_'.$dye_lot.'_'.$rewound_length_partialy;
			
			$location = 'JUST_CUT';
			$pas_key_location = $pas_key.'_'.$location;

			$kotur_qty = $kotur_partialy;
			$kotur_width = $table_prw->kotur_width;
			$material = $table_prw->material;
			$rewound_roll_unit_of_measure = $table_prw->rewound_roll_unit_of_measure;

			$fg_per_kotur;
			$status;
			$operator = 'PCO';

			// check if check_mtr_per_pcs
			$check_mtr_per_pcs = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mtr_per_pcs
				FROM  [paspul_stock_u_cons] 
			  	WHERE [skeda] = '".$skeda."' AND [paspul_type] = '".$paspul_type."' "));

			if (isset($check_mtr_per_pcs[0]->mtr_per_pcs)) {
					
				if ($rewound_roll_unit_of_measure == 'meter') {
					
					$pcs_kotur = floor($rewound_length_partialy / $check_mtr_per_pcs[0]->mtr_per_pcs);

				} elseif ($rewound_roll_unit_of_measure == 'ploce') {

					$pcs_kotur = floor($rewound_length_partialy * $check_mtr_per_pcs[0]->mtr_per_pcs);

				} else {
					
					$pcs_kotur = NULL;
				}
			} else {
				$pcs_kotur = NULL;
			}

			// check if pas_key_location exist
			$check_if_exist = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				[id],[pas_key_location]
				FROM  [paspul_stocks] 
			  	WHERE pas_key_location = '".$pas_key_location."' "));

			if (isset($check_if_exist[0]->pas_key_location)) {
				
				// update stock
				$id = $check_if_exist[0]->id;	
				
				$table_stock = paspul_stock::findOrFail($id);
				$table_stock->kotur_qty = $table_stock->kotur_qty + $kotur_qty;
				$table_stock->save();

			} else {

				// inster new line

				$table_stock = new paspul_stock;

				$table_stock->skeda = $skeda;
				$table_stock->paspul_type = $paspul_type;
				$table_stock->dye_lot = $dye_lot;
				$table_stock->kotur_length = $rewound_length_partialy;

				$table_stock->pas_key = $pas_key;
				$table_stock->pas_key_e = strtoupper(sprintf('%08x', crc32($pas_key)));

				$table_stock->pas_key_location = $pas_key_location;
				$table_stock->location = $location;

				$table_stock->kotur_qty = $kotur_qty;
				$table_stock->kotur_width = $kotur_width;
				$table_stock->uom = $rewound_roll_unit_of_measure;
				$table_stock->material = $material;
				$table_stock->fg_color_code = $fg_color_code;
				$table_stock->pcs_kotur = $pcs_kotur;

				$table_stock->save();
			}

			//  paspul_stock_u_cons
			$skeda_paspul_type = $table_stock->skeda.'_'.$table_stock->paspul_type;

			$check_if_exist_skeda_paspul_type = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [paspul_stock_u_cons]
				WHERE skeda_paspul_type = '".$skeda_paspul_type."' "));

			if (!isset($check_if_exist_skeda_paspul_type[0])) {
				
				$table_u_cons = new paspul_stock_u_cons;
				$table_u_cons->skeda_paspul_type = $skeda_paspul_type;
				$table_u_cons->skeda = $table_stock->skeda;
				$table_u_cons->paspul_type = $table_stock->paspul_type;

				$style_1 = substr($table_stock->skeda, 0, 9);
				$style = rtrim($style_1, '0');
				$table_u_cons->style = $style;
				$table_u_cons->mtr_per_pcs;

				$table_u_cons->save();
			}
		//
		*/

	return redirect('/');
	}

}