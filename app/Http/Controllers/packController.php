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

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class packController extends Controller {

	public function index()
	{
		// dd('cao');

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pack.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "PACK";
		// dd($location);

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
		      --,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
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
		  WHERE m4.[location] = '".$location."' AND m4.active = '1' AND m2.mattress_packed = '0'
		  ORDER BY m2.position asc"));
		// dd($data);

		// $work_place = substr($device, 0,2);
		$work_place = "PACK";
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
		return view('pack.index', compact('data','location','operators','operator'));
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
				return redirect('/pack');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/pack');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/pack');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/pack');
	}

	public function mattress_pack($id, $g_bin) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('pack.error',compact('msg'));
		}
		return view('pack.confirm', compact('id','g_bin'));
	}

	public function mattress_pack_m($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('pack.error',compact('msg'));
		}
		$g_bin = '';
		return view('pack.confirm', compact('id','g_bin'));
	}

	public function mattress_pack_confirm ($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('pack.error',compact('msg'));
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('pack.error',compact('msg'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				d.[mattress_id], d.[mattress], d.[id]
			FROM [mattress_details] as d
			JOIN mattresses as m ON m.[id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));
		// dd($data);
		$mattress = $data[0]->mattress;
		$mattress_id = $data[0]->mattress_id;

		// $location = substr($device, 0,3);
		$location_new = "COMPLETED";

		// position on COMPLETED location
		$find_position_on_location = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(location) as c
		 FROM [mattress_phases] 
		 WHERE location = '".$location_new."' AND active = '1' "));
		// dd($find_position_on_location_cut[0]);
		if (isset($find_position_on_location[0])) {
			$position = (int)$find_position_on_location[0]->c + 1;
		} else {
			$position = 1;
		}
		
		$table2_update = mattress_details::findOrFail($data[0]->id);
		$table2_update->mattress_packed = 1;
		$table2_update->position = $position;
		$table2_update->save();


		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
			SET NOCOUNT ON;
			UPDATE [mattress_phases]
			SET active = 0
			WHERE mattress_id = '".$id."' AND active = 1;
			SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;

		// save new mattress_phases
		$status = "COMPLETED";
		$location_new = "COMPLETED";
		$active = 1;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}		
		// save mattress_phases
		
		// $find_position = mattress_details::where('mattress_id','=',$id)->get();
		// $pre_position = $find_position[0]->position;

		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location_new;
		$table3_new->device = $device;
		$table3_new->active = $active;
		$table3_new->operator1 = $operator;
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		// $table3_new->pre_position = $pre_position;
		$table3_new->save();

		$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				md.[id], md.[mattress_id], md.[mattress], md.[position], mp.[location], mp.[active]
			 FROM [mattress_details] as md
			 INNER JOIN [mattress_phases] as mp ON (mp.[mattress_id] = md.[mattress_id]) AND (mp.[active] = 1)
			 WHERE location = 'PACK'
			 ORDER BY md.[position] asc"));

		if (isset($reorder_position[0])) {
			for ($o=0; $o < count($reorder_position); $o++) {

				$table1 = mattress_details::findOrFail($reorder_position[$o]->id);
				$table1->position = $o+1;
				$table1->save();
			}
		}

		return redirect('/pack');
	}

	public function other_functions($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/cutter');
			$msg ='Operator must be logged!';
			return view('pack.error',compact('msg'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], d.[id],
			p.[mattress], p.[status], m.[g_bin],
			mp.[pro_pcs_layer],mp.[pro_pcs_planned],
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
		$comment_operator = $data[0]->comment_operator;
		$status = $data[0]->status;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;

		// $operator = Session::get('operator');
		return view('pack.other_functions', compact('id','comment_operator','status','mattress','g_bin','data'));
	}
}
