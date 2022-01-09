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

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class pcoController extends Controller {

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

	public function operator_login (Request $request) {
		//
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);
		if (isset($input['selected_operator'])) {
			$selected_operator = $input['selected_operator'];

			if ($selected_operator != '') {
				$operator = Session::set('operator', $selected_operator);
				return redirect('/pco');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/pco');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/pco');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/pco');
	}

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
			return view('prw.error',compact('msg'));
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
			$table_p->save();

		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd('Problem to save');
		// }

		return redirect('/');

	}
}
