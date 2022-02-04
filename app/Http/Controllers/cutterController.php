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
use App\mattress_eff;
use App\mattresses;
use App\mattress_pro;
use App\o_roll;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class cutterController extends Controller {

	public function index()
	{
		//
		// dd('cao');

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			dd('User is not autenticated');
			// $msg ='User is not autenticated';
			// return view('cutter.error',compact('msg'));
		}
		// dd($device);
		$location = substr($device, 0,3);
		// dd($location);

		if ($location == "LEC") {
			$location = "CUT";
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			   m2.[position]
			  ,m1.[id]
		      ,m1.[mattress]
		      ,m1.[g_bin]
		      ,m1.[material]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[width_theor_usable]
		      ,m1.[skeda]
		      ,m1.[skeda_item_type]
		      ,m1.[skeda_status]
		      ,m1.[spreading_method]
		      ,m1.[created_at]
		      ,m1.[updated_at]
		      --,'|'
		      ,m2.[layers]
		      ,m2.[layers_a]
		      ,m2.[length_mattress]
		      ,m2.[cons_planned]
		      ,m2.[extra]
		      ,m2.[pcs_bundle]
		      ,m2.[layers_partial]
		      ,m2.[position]
		      ,m2.[priority]
		      ,m2.[call_shift_manager]
		      ,m2.[test_marker]
		      ,m2.[tpp_mat_keep_wastage]
		      ,m2.[printed_marker]
		      ,m2.[mattress_packed]
		      ,m2.[all_pro_for_main_plant]
		      ,m2.[bottom_paper]
		      ,m2.[layers_a_reasons]
		      ,m2.[comment_office]
		      ,m2.[comment_operator]
		      ,m2.[minimattress_code]
		      --,'|'
		      ,m3.[marker_id]
		      ,m3.[marker_name]
		      ,m3.[marker_name_orig]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m3.[min_length]
		      --,'|'
		      ,m4.[status]
		      ,m4.[location]
		      ,m4.[device]
		      ,m4.[active]
		      ,m4.[operator1]
		      ,m4.[operator2]
		      --,'|'
		      -- ,m5.[style_size]
		      -- ,m5.[pro_id]
		      -- ,m5.[pro_pcs_layer]
		      -- ,m5.[pro_pcs_planned]
		      -- ,m5.[pro_pcs_actual]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  WHERE m4.[location] = '".$location."' AND m4.active = '1' 
		  ORDER BY m2.position asc"));
		// dd($data);

		$work_place = "CUT";
		
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
		return view('cutter.index', compact('data','location','operators','operator'));
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
				return redirect('/cutter');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/cutter');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/cutter');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/cutter');
	}

	public function mattress_to_cut($id) {
		// dd($id);
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/cutter');
			$msg ='Operator must be logged!';
			return view('cutter.error',compact('msg'));
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('cutter.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		if ($location == 'LEC') {
			$location = 'CUT';
		}
		
		// mattress_phasess
		// all mattress_phases for this mattress set to NOT ACTIVE
		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 		id, mattress 
		// 	FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1"));
		
		// if (isset($find_all_mattress_phasses[0])) {
		// 	$mattress = $find_all_mattress_phasses[0]->mattress;

		// 	// dd($find_all_mattress_phasses);
		// 	// for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
				
		// 	// 		$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
		// 	// 		$table3->active = 0;
		// 	// 		$table3->save();
		// 	// }	
		// }

		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
			SET NOCOUNT ON;
			UPDATE [mattress_phases]
			SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
			WHERE mattress_id = '".$id."' AND active = 1;
			SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;


		// save new mattress_phases
		$status = "ON_CUT";
		$active = 1;
		// $operator1;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		// save new mattress_phases
		// $table3_new = new mattress_phases;
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = $active;
		$table3_new->operator1 = $operator;
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		$table3_new->save();

		return redirect('/cutter');
	}

	public function other_functions($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/cutter');
			$msg ='Operator must be logged!';
			return view('cutter.error',compact('msg'));
		}

		$take_comment_operator = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], d.[id],
			p.[mattress], p.[status], m.[g_bin],
			mp.[pro_pcs_layer],mp.[pro_pcs_planned],mp.[pro_pcs_actual],
			ps.[sku], ps.[pro], ps.[padprint_item], ps.[padprint_color],
			po.[location_all]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			JOIN [mattress_pros] as mp ON mp.[mattress_id] = m.[id]
			JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
			LEFT JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
			WHERE m.[id] = '".$id."' "));

		// dd($take_comment_operator[0]->comment_operator);
		$comment_operator = $take_comment_operator[0]->comment_operator;
		$status = $take_comment_operator[0]->status;
		$mattress = $take_comment_operator[0]->mattress;
		$g_bin = $take_comment_operator[0]->g_bin;

		// $operator = Session::get('operator');
		return view('cutter.other_functions', compact('id','comment_operator','status','mattress','g_bin','take_comment_operator'));
	}

	public function add_operator_comment(Request $request) {
		//
		// $this->validate($request, ['comment_operator' => 'required']);
		$input = $request->all(); 
		// dd($input);
		// dd("test");
		$id = $input['id'];
		$status = $input['status'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$comment_operator = $input['comment_operator'];

		$take_comment_operator = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], d.[id],
			p.[mattress], p.[status], m.[g_bin],
			mp.[pro_pcs_layer],mp.[pro_pcs_planned],mp.[pro_pcs_actual],
			ps.[sku], ps.[pro], ps.[padprint_item], ps.[padprint_color],
			po.[location_all]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			JOIN [mattress_pros] as mp ON mp.[mattress_id] = m.[id]
			JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
			LEFT JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
			WHERE m.[id] = '".$id."' "));

		$table3 = mattress_details::findOrFail($take_comment_operator[0]->id);
		$table3->comment_operator = $comment_operator;
		$table3->save();

		$success = "Saved succesfuly";

		return view('cutter.other_functions', compact('id','comment_operator','status', 'mattress','g_bin' ,'success', 'take_comment_operator'));
	}

	public function mattress_cut($id) {

		// dd($id);
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/cutter');
			$msg ='Operator must be logged!';
			return view('cutter.error',compact('msg'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], d.[layers_a], d.[layers], d.[mattress_id],d.[layers_partial],
			p.[status], p.[mattress], 
			mf.[layers_after_cs], mf.[layers_before_cs],
			m.[g_bin],m.[skeda],
			mm.[marker_name], mm.[marker_length], mm.[marker_width]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			INNER JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			LEFT JOIN [mattress_effs] as mf ON mf.[mattress_id] = d.[mattress_id]
			LEFT JOIN [mattress_markers] as mm ON mm.[mattress_id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));
		// dd($data);

		$status = $data[0]->status;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$skeda = $data[0]->skeda;
		$comment_operator = $data[0]->comment_operator;
		$layers_a = (float)$data[0]->layers_a;
		$layers = (float)$data[0]->layers;
		$layers_partial = (float)$data[0]->layers_partial;
		$mattress_id = (int)$data[0]->mattress_id;
		$marker_name = $data[0]->marker_name;
		$marker_width = (float)$data[0]->marker_width;
		$marker_length = (float)$data[0]->marker_length;
		
		$data_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				[id], [style_size], [pro_id], [pro_pcs_layer], [pro_pcs_planned], [pro_pcs_actual]
			FROM [mattress_pros]
			WHERE [mattress_id] = '".$mattress_id."' "));
		// dd($data_pro);

		return view('cutter.mattress_cut', compact('data_pro','id','comment_operator','status','mattress','g_bin','skeda','mattress_id','layers_a','layers','layers_partial','marker_name','marker_width','marker_length'));
	}

	public function mattress_cut_post(Request $request) {
		//
		// $this->validate($request, ['layers_a' => 'required','layers_a_reasons'=>'required']);
		$input = $request->all(); 
		// dd($input);

		$id = (int)$input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$skeda = $input['skeda'];
		$mattress_id = (int)$input['mattress_id'];
		$status = $input['status'];
		$layers_a = (int)$input['layers_a'];
		$comment_operator = $input['comment_operator'];

		if (isset($input['style_size'])) {
			$style_size = $input['style_size'];
			$pro_pcs_layer = $input['pro_pcs_layer'];
			$pro_id = $input['pro_id'];
			$pro_pcs_actual = $input['pro_pcs_actual'];
			$damaged_pcs = $input['damaged_pcs'];
			$line_id = $input['line_id'];
			
		} else {
			$style_size = NULL;
			$pro_pcs_layer = NULL;
			$pro_id = NULL;
			$pro_pcs_actual = NULL;
			$damaged_pcs = NULL;
			$line_id = NULL;
		}
		// dd($damaged_pcs);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/cutter');
			$msg ='Operator must be logged!';
			return view('cutter.error',compact('msg'));
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('cutter.error',compact('msg'));
		}
		// $location = substr($device, 0,3);
		$location = "CUT";

		// find all_pro_for_main_plant ???
		if (isset($pro_id[0])) {

			$out_su = 0;
			for ($i=0; $i < count($pro_id); $i++) {
				// print_r($pro_id[$i]."<br>");

				$check_pro_skeda = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					pro
				FROM [pro_skedas]
				WHERE pro_id = '".$pro_id[$i]."' "));
				
				// print_r($check_pro_skeda[0]->pro."<br>");
					
				$check_pro_in_posummarry = DB::connection('sqlsrv6')->select(DB::raw("SELECT 
  					[location_all]
				FROM [pro]
				WHERE pro = '".$check_pro_skeda[0]->pro."' "));

				// print_r($check_pro_in_posummarry[0]->location_all."<br>");
			 	// dd($check_pro_in_posummarry);

			 	if (!isset($check_pro_in_posummarry[0]->location_all)) {
			 		dd('Fali komesa u posummary applikaciji, javiti planerima');

			 	} else {
			 		if ($check_pro_in_posummarry[0]->location_all == "Subotica") {
						$out_su = $out_su + 0; 
					} else {	
						$out_su = $out_su + 1;
					}	
			 	}
			}

			// print_r("final: ".$out_su."<br>");
			if ($out_su > 0) {
				$all_pro_for_main_plant = 0;	
			} else {
				$all_pro_for_main_plant = 1; // if is 1 COMPLETED (Subotica), 0 PACK (Kikinda/Senta)
			}
		} else {
			dd("missing pro_id");
		}
		// dd($all_pro_for_main_plant);
		// $all_pro_for_main_plant = 0;

		// dd(substr($skeda, -2));
		$skeda_last = substr($skeda, -2);

		if ($skeda_last == '-B') {

			// position in completed 
			$find_position_on_location_complited = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(location) as c
			 FROM [mattress_phases] 
			 WHERE location = 'COMPLETED' AND active = '1' "));
			// dd($find_position_on_location_complited[0]);
			if (isset($find_position_on_location_complited[0])) {
				$position_complited = (int)$find_position_on_location_complited[0]->c + 1;
			} else {
				$position_complited = 1;
			}

			$status = "COMPLETED";
			$active = 1;
			$location =  "COMPLETED";
			$position = $position_complited;

		} else {

			if ($all_pro_for_main_plant == 1) {

				// position in completed 
				$find_position_on_location_complited = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(location) as c
				 FROM [mattress_phases] 
				 WHERE location = 'COMPLETED' AND active = '1' "));
				// dd($find_position_on_location_complited[0]);
				if (isset($find_position_on_location_complited[0])) {
					$position_complited = (int)$find_position_on_location_complited[0]->c + 1;
				} else {
					$position_complited = 1;
				}

				$status = "COMPLETED";
				$active = 1;
				$location =  "COMPLETED";
				$position = $position_complited;

			} else {
				// position on PSO location
				$find_position_on_location_pack = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(location) as c
				 FROM [mattress_phases] 
				 WHERE location = 'PACK' AND active = '1' "));
				// dd($find_position_on_location_pack[0]);
				if (isset($find_position_on_location_pack[0])) {
					$position_pack = (int)$find_position_on_location_pack[0]->c + 1;
				} else {
					$position_pack = 1;
				}

				$status = "TO_PACK";
				$active = 1;
				$location =  "PACK";
				$position = $position_pack;
			}
		}
		
		// dd($status);
		// dd($position);
		// dd('Stop');

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT d.[id]
				FROM [mattress_details] as d
				JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
				WHERE m.[id] = '".$id."' "));

		// save in mattress_details (operator comment)
		$table2_update = mattress_details::findOrFail($find_details_id[0]->id);
		$table2_update->position = $position;
		$table2_update->all_pro_for_main_plant = $all_pro_for_main_plant;
		$table2_update->comment_operator = $comment_operator;
		$table2_update->save();

		// save in mattress_pro
		for ($x=0; $x < count($line_id); $x++) { 
			// dd($pro_pcs_actual[$i]);
			// dd($damaged_pcs[$i]);

			$tablepro_update = mattress_pro::findOrFail($line_id[$x]);
			$tablepro_update->pro_pcs_actual = (int)$pro_pcs_actual[$x] - (int)$damaged_pcs[$x];
			$tablepro_update->damaged_pcs = (int)$damaged_pcs[$x];
			$tablepro_update->save();
		}

		// save in mattress_phases
		// all mattress_phases for this mattress set to NOT ACTIVE
		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 		id, mattress 
		// 	FROM [mattress_phases] WHERE mattress_id = '".$mattress_id."' AND active = 1"));

		// if (isset($find_all_mattress_phasses[0])) {
		// 	$mattress = $find_all_mattress_phasses[0]->mattress;

		// 	// dd($find_all_mattress_phasses);
		// 	// for ($y=0; $y < count($find_all_mattress_phasses); $y++) { 
				
		// 	// 		$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$y]->id);
		// 	// 		$table3->active = 0;
		// 	// 		$table3->save();
		// 	// }	
		// }

		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
				SET NOCOUNT ON;
				UPDATE [mattress_phases]
				SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
				WHERE mattress_id = '".$mattress_id."' AND active = 1;
				SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$mattress_id."' ;
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;
		
		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		$date_now = date('Y-m-d H:i:s');

		// save new mattress_phases
		// $table3_new = new mattress_phases;
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = $active;
		$table3_new->operator1 = $operator;
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		$table3_new->save();

		// reorder position of CUT
		$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				md.[id], md.[mattress_id], md.[mattress], md.[position],
				mp.[location], mp.[active] 
			 FROM [mattress_details] as md
			 INNER JOIN [mattress_phases] as mp ON mp.[mattress_id] = md.[mattress_id] AND mp.[active] = 1
			 WHERE mp.[location] = 'CUT'
			 ORDER BY md.[position] asc"));

		if (isset($reorder_position[0])) {
			for ($o=0; $o < count($reorder_position); $o++) { 

				$table1 = mattress_details::findOrFail($reorder_position[$o]->id);
				$table1->position = $o+1;
				$table1->save();
			}
		}

		// $o_rolls =  DB::connection('sqlsrv')->select(DB::raw("SELECT *
		//   FROM [o_rolls]
		//   WHERE mattress_id_new = '".$mattress_id."' "));

		// if (isset($o_rolls[0])) {
		// 	for ($p=0; $p < count($o_rolls); $p++) { 
				
		// 		$table_o = o_roll::findOrFail($o_rolls[$p]->id);
		// 		$table_o->status = 'CONSUMED';
		// 		$table_o->save();
		// 	}
		// }

		$update_o_rolls = DB::connection('sqlsrv')->update(DB::raw("
				UPDATE [o_rolls]
				SET status = 'CONSUMED'
				WHERE mattress_id_new = '".$mattress_id."' 
		"));

		return redirect('/cutter');
	}

}
