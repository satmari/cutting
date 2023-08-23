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
use App\mattress_pro;
use App\mattresses;
use App\mattress_split_request;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class spreaderController extends Controller {

	public function index() {
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
			// return view('spreader.error',compact('msg'));
		}
		// dd($operator1);
		$location = substr($device, 0,3);
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
		      ,m2.[cons_actual]
		      ,m2.[extra]
		      ,m2.[pcs_bundle]
		      ,m2.[layers_partial]
		      ,m2.[position]
		      ,m2.[priority]
		      ,m2.[call_shift_manager]
		      ,m2.[test_marker]
		      ,m2.[tpp_mat_keep_wastage]
		      ,m2.[tpa_number]
		      ,m2.[printed_marker]
		      ,m2.[mattress_packed]
		      ,m2.[all_pro_for_main_plant]
		      ,m2.[bottom_paper]
		      ,m2.[layers_a_reasons]
		      ,m2.[comment_office]
		      ,m2.[comment_operator]
		      ,m2.[minimattress_code]
		      ,m2.[layer_limit]
		      ,m2.[overlapping]
		      ,m2.[req_time]
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
		      ,ms.[g_bin_orig]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  LEFT JOIN [mattress_split_requests] as ms ON ms.[mattress_id_new] = m1.[id]

		  WHERE m4.[location] = '".$location."' AND m4.active = '1' 
		  ORDER BY m2.position asc"));
		

		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all = '';

		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			// dd($id);

			$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				ps.[pro]
				,ps.[style_size]
				,ps.[sku]
				,po.[location_all]
				--,*
			  FROM [mattress_pros] as mp
			  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
			  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
			WHERE mp.mattress_id = '".$id."' "));
			
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
		// dd($data);

		$work_place = substr($device, 0,2);
		// Session::set('work_place',$work_place);

		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		
		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date_to_look = date('Y-m-d', strtotime(' -1 day'));
		} else {
			$date_to_look = date('Y-m-d');
		}

		$operator = Session::get('operator');
		$operator2 = Session::get('operator2');
		// dd($operator2);
		
		if (!isset($operator) OR $operator == '') {
			$eff = 'Operator must be logged';
		} else {

			$efficiency_check = DB::connection('sqlsrv')->select(DB::raw("SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      [operator_after] as op
			      ,[stimulation_after] as eff
			      ,[date_after] as date
			      --,[operator_before]
			      --,[stimulation_before]
			      --,[date_before]
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator_after] = '".$operator."'  COLLATE Latin1_General_CI_AI
			  AND (CAST([date_after] as DATE) = '".$date_to_look."')
			  
			  UNION ALL
			  
			  SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      --[operator_after]
			      --,[stimulation_after]
			      --,[date_after]
			      [operator_before] as op
			      ,[stimulation_before] as eff
			      ,[date_before] as date
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator_before] = '".$operator."' COLLATE Latin1_General_CI_AI
			  AND (CAST([date_before] as DATE) = '".$date_to_look."')

			  UNION ALL

			   SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      [operator2_after] as op
			      ,[stimulation_after] as eff
			      ,[date_after] as date
			      --,[operator_before]
			      --,[stimulation_before]
			      --,[date_before]
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator2_after] = '".$operator."'  COLLATE Latin1_General_CI_AI
			  AND (CAST([date_after] as DATE) = '".$date_to_look."')
			  
			  UNION ALL
			  
			  SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      --[operator_after]
			      --,[stimulation_after]
			      --,[date_after]
			      [operator2_before] as op
			      ,[stimulation_before] as eff
			      ,[date_before] as date
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator2_before] = '".$operator."' COLLATE Latin1_General_CI_AI
			  AND (CAST([date_before] as DATE) = '".$date_to_look."')
			"));
			// dd($efficiency_check);

			$eff_sum = 0;
			foreach ($efficiency_check as $line) {
				// dd($line->eff);
				$eff_sum += $line->eff;
			}
			// dd($eff_sum);
			$eff = round($eff_sum,0).' m';	
		}


		$operator2 = Session::get('operator2');

		if (!isset($operator2) OR $operator2 == '') {
			$eff2 = 'Operator2 must be logged';
		} else {

			$efficiency_check2 = DB::connection('sqlsrv')->select(DB::raw("SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      [operator_after] as op
			      ,[stimulation_after] as eff
			      ,[date_after] as date
			      --,[operator_before]
			      --,[stimulation_before]
			      --,[date_before]
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator_after] = '".$operator2."'  COLLATE Latin1_General_CI_AI
			  AND (CAST([date_after] as DATE) = '".$date_to_look."')
			  
			  UNION ALL
			  
			  SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      --[operator_after]
			      --,[stimulation_after]
			      --,[date_after]
			      [operator_before] as op
			      ,[stimulation_before] as eff
			      ,[date_before] as date
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator_before] = '".$operator2."' COLLATE Latin1_General_CI_AI
			  AND (CAST([date_before] as DATE) = '".$date_to_look."')

			  UNION ALL

			   SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      [operator2_after] as op
			      ,[stimulation_after] as eff
			      ,[date_after] as date
			      --,[operator_before]
			      --,[stimulation_before]
			      --,[date_before]
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator2_after] = '".$operator2."'  COLLATE Latin1_General_CI_AI
			  AND (CAST([date_after] as DATE) = '".$date_to_look."')
			  
			  UNION ALL
			  
			  SELECT
				  --,convert(varchar, getdate(), 23)
				  --,CAST([date_after] as DATE)
			      --[operator_after]
			      --,[stimulation_after]
			      --,[date_after]
			      [operator2_before] as op
			      ,[stimulation_before] as eff
			      ,[date_before] as date
			      --,[created_at]
			      --,[updated_at]
			  FROM [cutting].[dbo].[mattress_effs]
			  WHERE [operator2_before] = '".$operator2."' COLLATE Latin1_General_CI_AI
			  AND (CAST([date_before] as DATE) = '".$date_to_look."')
			  
			"));
			// dd($efficiency_check);

			$eff_sum2 = 0;
			foreach ($efficiency_check2 as $line2) {
				// dd($line->eff);
				$eff_sum2 += $line2->eff;
			}
			// dd($eff_sum);
			$eff2 = round($eff_sum2,0).' m';	

			// if ($eff == 'Operator must be logged') {
			// 	$eff2 = 'Operator2 must be logged';
			// }
		}

		return view('spreader.index', compact('data','location','operators','operator','operator2','eff','eff2'));
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
				return redirect('/spreader');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/spreader');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/spreader');
		}
	}

	public function operator_login2 (Request $request) {
		//
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);
		if (isset($input['selected_operator2'])) {
			$selected_operator = $input['selected_operator2'];

			if ($selected_operator != '') {
				$operator = Session::set('operator2', $selected_operator);
				return redirect('/spreader');
			} else {
				$operator = Session::set('operator2', NULL);
				return redirect('/spreader');
			}
		} else {
			$operator = Session::get('operator2');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/spreader');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/spreader');
	}

	public function operator_logout2 () {
		
		$operator = Session::set('operator2', NULL);
		return redirect('/spreader');
	}

	public function mattress_to_load($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%SP%' OR [device] like '%MM%' OR [device] like '%MS%')"));
		if (!isset($check_op)) {
			dd("Wrong operator, call IT or Sonja !");
		}


		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);
		
		// mattress_phasess
		// all mattress_phases for this mattress set to NOT ACTIVE
		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 		id, mattress 
		// 	FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1"));
		
		// if (isset($find_all_mattress_phasses[0])) {
		// 	$mattress = $find_all_mattress_phasses[0]->mattress;

		// 	// dd($find_all_mattress_phasses);
		// 	for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 

		// 			$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
		// 			$table3->active = 0;
		// 			$table3->save();
		// 	}	
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
		$status = "TO_SPREAD";
		$active = 1;
		// $operator1;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		// $table3_new = new mattress_phases;
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = $active;
		$table3_new->operator1 = $operator;
		$table3_new->operator2 = $operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		$table3_new->save();


		return redirect('/spreader');
	}

	public function other_functions($id) {
		// dd($id);
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);
		// dd($location);

		$take_comment_operator = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], p.[status], 
			p.[mattress], m.[g_bin]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			LEFT JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			WHERE m.[id] = '".$id."' "));
		// dd($take_comment_operator);

		// dd($take_comment_operator[0]->comment_operator);
		$comment_operator = $take_comment_operator[0]->comment_operator;
		$status = $take_comment_operator[0]->status;
		$mattress = $take_comment_operator[0]->mattress;
		$g_bin = $take_comment_operator[0]->g_bin;

		
		if ($location == 'MM1') {
			$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[no_of_joinings]
				FROM  [o_rolls]
				WHERE mattress_id_new = '".$id."' "));
			// dd($data2);
			return view('spreader.other_functions', compact('id','comment_operator','status','mattress','g_bin','data2'));
		}
		
		return view('spreader.other_functions', compact('id','comment_operator','status','mattress','g_bin'));
	}

	public function mattress_to_unload($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%SP%' OR [device] like '%MM%' OR [device] like '%MS%')"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		// mattress_phasess
		// all mattress_phases for this mattress set to NOT ACTIVE
		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT
		//  	mp.[id], mp.[mattress], mp.[status], m.[g_bin]
		//  	FROM [mattress_phases] as mp 
		//  	JOIN [mattresses] as m ON m.[id] = mp.[mattress_id]
		//  	WHERE mp.[mattress_id] = '".$id."' AND mp.[active] = 1  "));
		
		// if (isset($find_all_mattress_phasses[0])) {
		// 	$mattress = $find_all_mattress_phasses[0]->mattress;

		// 	// dd($find_all_mattress_phasses);
		// 	for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 

		// 			$table3_update = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
		// 			$table3_update->active = 0;
		// 			$table3_update->save();
		// 	}
		// }

		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
			SET NOCOUNT ON;
			UPDATE [mattress_phases]
			SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
			WHERE mattress_id = '".$id."' AND active = 1;
			SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;


		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}
		$status = "TO_LOAD";

		// add to mattress_phases
		// $table3_new = new mattress_phases;
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = 1;
		$table3_new->operator1 = $operator;
		$table3_new->operator2 = $operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		$table3_new->save();

		return redirect('/spreader');
	}

	public function change_marker_request($id) {

		$find_mattress = DB::connection('sqlsrv')->select(DB::raw("SELECT
		 	md.[mattress_id], md.[mattress], md.[comment_operator], md.[requested_width],md.[id] as md_id,
		 	mm.[marker_name], mm.[marker_width],
		 	p.[status],
		 	m.[width_theor_usable], m.[g_bin]
		 	FROM [mattress_details] as md
			JOIN [mattresses] as m ON m.[id] = md.[mattress_id]
		 	JOIN [mattress_markers] as mm ON mm.[mattress_id] = md.[mattress_id]
		 	LEFT JOIN [mattress_phases] as p ON p.[mattress_id] = md.[mattress_id] AND p.[active] = 1
		 	WHERE m.[id] = '".$id."' "));
		// dd($find_mattress);

		$mattress = $find_mattress[0]->mattress;
		$g_bin = $find_mattress[0]->g_bin;
		$comment_operator = $find_mattress[0]->comment_operator;
		$requested_width = $find_mattress[0]->requested_width;
		$marker_width = $find_mattress[0]->marker_width;
		$status = $find_mattress[0]->status;
		$md_id = $find_mattress[0]->md_id;
		$width_theor_usable = $find_mattress[0]->width_theor_usable;

		if (($find_mattress[0]->marker_name == '') OR (is_null($find_mattress[0]->marker_name))) {
			
			$danger = "Mattress doesn't have marker, you can't send request to change width for this mattress!";
			return view('spreader.other_functions', compact('id','comment_operator','status','mattress', 'g_bin', 'danger'));
		}

		return view('spreader.change_marker_request', compact('id', 'mattress', 'g_bin','comment_operator','requested_width','marker_width', 'status', 'md_id','width_theor_usable'));
	}

	public function change_marker_request_post(Request $request) {
		//
		$this->validate($request, ['requested_width' => 'required']);
		$input = $request->all(); 
		// dd($input);
		// dd("test");
		$id = $input['id'];
		$md_id = $input['md_id'];
		$status = $input['status'];
		$requested_width = (int)$input['requested_width'];

		$cons_planned; // recalulate cons_planned ?????????????????????????????????????????????????????????

		$table3 = mattress_details::findOrFail($md_id);
		$table3->requested_width = $requested_width;
		// $table3->cons_planned = $cons_planned;
		$table3->save();

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%SP%' OR [device] like '%MM%' OR [device] like '%MS%')"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);
		
		// mattress_phasess
		// all mattress_phases for this mattress set to NOT ACTIVE
		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT
		//  	id, mattress, status 
		//  	FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1 "));
		// // dd($find_all_mattress_phasses);

		// if (isset($find_all_mattress_phasses[0])) {
		// 	$mattress = $find_all_mattress_phasses[0]->mattress;

		// 	// dd($find_all_mattress_phasses);
		// 	for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 

		// 		$table3_update = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
		// 		$table3_update->active = 0;
		// 		$table3_update->save();
		// 	}
		// }

		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
			SET NOCOUNT ON;
			UPDATE [mattress_phases]
			SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
			WHERE mattress_id = '".$id."' AND active = 1;
			SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}
		$status = 'ON_HOLD';

		// $table3_new = new mattress_phases;
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = 1;
		$table3_new->operator1 = $operator;
		$table3_new->operator2 = $operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		$table3_new->save();
	
		// if is partialy spreaded to delete line in eff ???????????????????????????????????

		return redirect('/spreader');
	}

	public function split_marker_request($id) {
		
		$find_mattress = DB::connection('sqlsrv')->select(DB::raw("SELECT
		 	md.[mattress_id], md.[mattress], md.[comment_operator], md.[requested_width],md.[id] as md_id,
		 	mm.[marker_name], mm.[marker_width], mm.[marker_length], mm.[id] as mm_id, 
		 	p.[status],
		 	m.[width_theor_usable], m.[g_bin]
		 	FROM [mattress_details] as md
			JOIN [mattresses] as m ON m.[id] = md.[mattress_id]
		 	JOIN [mattress_markers] as mm ON mm.[mattress_id] = md.[mattress_id]
		 	LEFT JOIN [mattress_phases] as p ON p.[mattress_id] = md.[mattress_id] AND p.[active] = 1
		 	WHERE m.[id] = '".$id."' "));
		// dd($find_mattress);

		$mattress = $find_mattress[0]->mattress;
		$g_bin = $find_mattress[0]->g_bin;
		$comment_operator = $find_mattress[0]->comment_operator;
		$requested_width = $find_mattress[0]->requested_width;
		$marker_width = round($find_mattress[0]->marker_width,0);
		$marker_length = round($find_mattress[0]->marker_length,2);
		$status = $find_mattress[0]->status;
		$md_id = $find_mattress[0]->md_id;
		$marker_name = $find_mattress[0]->marker_name;
		$mm_id = $find_mattress[0]->mm_id;
		$width_theor_usable = $find_mattress[0]->width_theor_usable;

		if (($find_mattress[0]->marker_name == '') OR (is_null($find_mattress[0]->marker_name))) {
			
			$danger = "Mattress doesn't have marker, you can't send request to change width for this mattress!";
			return view('spreader.other_functions', compact('id','comment_operator','status','mattress', 'g_bin', 'danger'));
		}

		return view('spreader.split_marker_request', compact('id', 'mattress', 'g_bin','comment_operator','requested_width','marker_width','marker_length','status', 'md_id','width_theor_usable', 'marker_name', 'mm_id'));
	}

	public function split_marker_request_post(Request $request) {

		//
		$this->validate($request, ['requested_width' => 'required']);
		$input = $request->all(); 
		// dd($input);
		// dd("test");

		$operator1 = Session::get('operator');
		if (!isset($operator1) OR $operator1 == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator1."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%SP%' OR [device] like '%MM%' OR [device] like '%MS%')"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}
		// dd($operator1);

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);
		// dd($location);

		$mattress_id_orig = $input['id'];
		// $md_id_orig = $input['md_id'];
		$mattress_orig = $input['mattress'];

		$requested_width = (int)$input['requested_width'];
		$requested_length = (float)$input['requested_length'];
		$comment_operator = $input['comment_operator'];
		$g_bin_orig = $input['g_bin'];
		$marker_name = $input['marker_name'];
		$mm_id = $input['mm_id'];
		$marker_width = (int)$input['marker_width'];
		$marker_length = (float)$input['marker_length'];

		$table = new mattress_split_request;
		$table->mattress_id_orig = $mattress_id_orig;
		$table->mattress_orig = $mattress_orig;
		$table->g_bin_orig = $g_bin_orig;
		$table->marker_name_orig = $marker_name;
		$table->marker_id_orig = $mm_id;
		$table->marker_width = $marker_width;
		$table->marker_length = $marker_length;
		$table->requested_width = $requested_width;
		$table->requested_length = $requested_length;
		$table->comment_operator = $comment_operator;
		$table->status = "TO_SPLIT";
		$table->operator1 = $operator1;
		$table->location = $location;
		$table->layers;
		$table->mattress_id_new;
		$table->mattress_new;
		$table->save();

		return redirect('/spreader');	
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

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT d.[id]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));

		$table3_c = mattress_details::findOrFail($data[0]->id);
		$table3_c->comment_operator = $comment_operator;
		$table3_c->save();

		$success = "Saved succesfuly";
		return view('spreader.other_functions', compact('id','comment_operator','status', 'mattress', 'g_bin','success'));
	}

	public function mattress_to_spread($id) {
		// dd($id);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], d.[layers_a], 
			m.[skeda_item_type], m.[g_bin],
			p.[status], p.[mattress], 
			mf.[layers_after_cs], mf.[layers_before_cs]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			INNER JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			LEFT JOIN [mattress_effs] as mf ON mf.[mattress_id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));
		// dd($data[0]->comment_operator);

		$status = $data[0]->status;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$comment_operator = $data[0]->comment_operator;
		$layers_a = (float)$data[0]->layers_a;
		$layers_before_cs = (float)$data[0]->layers_before_cs;
		$layers_after_cs = (float)$data[0]->layers_after_cs;
		// dd((float)$layers_before_cs);
		
		if ((float)$layers_before_cs > 0) {
			$already_partialy_spreaded = (float)$layers_before_cs;
		} else {
			$already_partialy_spreaded = 0;
		}

		return view('spreader.spread_mattress', compact('id','comment_operator','status','mattress','g_bin','layers_a','already_partialy_spreaded'));
	}

	public function spread_mattress_partial($id) {

		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			d.[comment_operator], d.[layers_a], d.[layers_a_reasons],
			p.[status], p.[mattress],
			m.[g_bin]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			INNER JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			WHERE m.[id] = '".$id."' "));
		// dd($take_comment_operator[0]->comment_operator);
		$status = $data[0]->status;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$comment_operator = $data[0]->comment_operator;
		$layers_a = (float)$data[0]->layers_a;
		// $layers_a_reasons = $data[0]->layers_a_reasons;
		
		return view('spreader.spread_mattress_partial', compact('id','comment_operator','status','mattress','g_bin','layers_a'/*,'layers_a_reasons'*/));
	}

	public function spread_mattress_partial_post(Request $request) {
		//
		$this->validate($request, ['layers_a' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$status = $input['status'];
		$layers_a = $input['layers_a'];
		// $layers_a_reasons = $input['layers_a_reasons'];
		$comment_operator = $input['comment_operator'];
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%SP%' OR [device] like '%MM%' OR [device] like '%MS%')"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				d.[comment_operator], d.[layers_a] ,d.[layers_a_reasons], d.[extra], d.[layers_a], d.[id],
				p.[status], p.[mattress], 
				m.[spreading_method], m.[g_bin],
				mm.[marker_length]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			INNER JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			INNER JOIN [mattress_markers] as mm ON mm.[mattress_id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));
		// dd($data);

		if (isset($data[0])) {

			if ($data[0]->layers_a == $input['layers_a']) {
				$danger = "Partial layers qty should be less then planned layer qty!";
				return view('spreader.spread_mattress_partial', compact('id','comment_operator','status','mattress','g_bin','layers_a','layers_a_reasons', 'danger'));
			}

			if ($data[0]->spreading_method == "FACE UP") {
				$stimulation_before = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100)) * 1.00 ;
			} else {
				$stimulation_before = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100));
			}
			// dd($data[0]->id);

			if ((date('H') >= 0) AND (date('H') < 6)) {
			   	$date_before = date('Y-m-d H:i:s', strtotime(' -1 day'));
			} else {
				$date_before = date('Y-m-d H:i:s');
			}

			$table1_new = new mattress_eff;
			$table1_new->mattress_id = $id;
			$table1_new->mattress = $mattress;
			$table1_new->layers_after_cs = 0;
			$table1_new->operator_after = '';
			$table1_new->operator2_after = '';
			$table1_new->stimulation_after = 0;
			$table1_new->date_after = NULL;
			$table1_new->location_after = NULL;
			$table1_new->layers_before_cs = (float)$layers_a;
			$table1_new->operator_before = $operator;
			$table1_new->operator2_before = $operator2;
			$table1_new->stimulation_before = (float)$stimulation_before;
			$table1_new->date_before = $date_before;
			$table1_new->location_before = $location;
			$table1_new->save();

			$table2_update = mattress_details::findOrFail($data[0]->id);
			$table2_update->layers_partial;
			$table2_update->comment_operator = $comment_operator;
			$table2_update->save();
		}
		return redirect('/spreader');
	}

	public function spread_mattress_complete($id) {

		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				d.[comment_operator], d.[layers_a] ,d.[layers_a_reasons], d.[mattress_id], d.[layers_partial],
				p.[status], p.[mattress],
				m.[id], m.[skeda_item_type], m.[g_bin]
			FROM [mattress_details] as d
			JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			INNER JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			WHERE m.[id] = '".$id."' "));
		// dd($data[0]->comment_operator);
		$status = $data[0]->status;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$mattress_id = $data[0]->mattress_id;
		$skeda_item_type = $data[0]->skeda_item_type;
		// $id_details = $data[0]->id;
		// dd($mattress_id);
		$comment_operator = $data[0]->comment_operator;
		$layers_a = $data[0]->layers_a;
		$layers_a_reasons = $data[0]->layers_a_reasons;
		// $layers_partial;
		// dd($layers_partial);

		// if ($layers_partial == NULL){
			$layers_partial = 0;
		// }

		return view('spreader.spread_mattress_complete', compact('id','mattress_id','comment_operator','status','mattress','g_bin','layers_a','layers_a_reasons','skeda_item_type','layers_partial'));
	}

	public function spread_mattress_complete_post(Request $request) {
		//
		$this->validate($request, ['layers_a' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$mattress_id = $input['mattress_id'];
		$status = $input['status'];
		$layers_a = (float)$input['layers_a'];
		$comment_operator = $input['comment_operator'];
		$layers_a_reasons = $input['layers_a_reasons'];
		$layers_partial = (float)$input['layers_partial'];	
		$num_of_cut_problems = (int)$input['num_of_cut_problems'];
		
		if($layers_a < 1) {
			$msg ='Layers actual must be > 1';
			return view('spreader.error',compact('msg'));
		}

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%SP%' OR [device] like '%MM%' OR [device] like '%MS%')"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('spreader.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		// position on CUT location
		$find_position_on_location_cut = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(location) as c
		 FROM [mattress_phases] 
		 WHERE location = 'CUT' AND active = '1' "));
		// dd($find_position_on_location_cut[0]);
		if (isset($find_position_on_location_cut[0])) {
			$position_cut = (int)$find_position_on_location_cut[0]->c + 1;
		} else {
			$position_cut = 1;
		}

		// position on PSO location
		$find_position_on_location_join = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(location) as c
		 FROM [mattress_phases] 
		 WHERE location = 'PSO' AND active = '1' "));
		// dd($find_position_on_location_join[0]);
		if (isset($find_position_on_location_join[0])) {
			$position_join = (int)$find_position_on_location_join[0]->c + 1;
		} else {
			$position_join = 1;
		}

		// check if mattress have marker_name
		$check_if_is_ploce = DB::connection('sqlsrv')->select(DB::raw("SELECT skeda_item_type 
			FROM [mattresses]
			WHERE [id] = '".$mattress_id."' "));
		// dd($check_if_is_ploce[0]->marker_name);

		if (($check_if_is_ploce[0]->skeda_item_type == "MW") OR ($check_if_is_ploce[0]->skeda_item_type == "MB")) {
			// PLOCE
			$ploce = 1;
			$position = $position_join;

		} else {
			// MATTRESS
			$ploce = 0;
			$position = $position_cut;
		}
		// dd($ploce);

		// find all_pro_for_main_plant ???
		$data_location = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				m.[g_bin]
				--,mp.[pro_id]
				,s.[pro]
				,p.[location_all]
			FROM [mattresses] as m
			JOIN [mattress_pros] as mp ON mp.[mattress_id] = m.[id]
			JOIN [pro_skedas] as s ON s.[pro_id] = mp.[pro_id]
			LEFT JOIN [posummary].[dbo].[pro] as p ON p.[pro] = s.[pro]
			WHERE m.[id] = '".$id."'
			"));
		// dd($data_location);

		$out_su = 0;
		if (isset($data_location[0]->location_all)) {

			for ($i=0; $i < count($data_location); $i++) { 

				if ($data_location[$i]->location_all == "Subotica") {
					$out_su = $out_su + 0; 
				} else {	
					$out_su = $out_su + 1;
				}					
			}
			// dd($out_su);

			// print_r("final: ".$out_su."<br>");
			if ($out_su > 0) {
				$all_pro_for_main_plant = 0;	
			} else {
				$all_pro_for_main_plant = 1; // if is 1 COMPLETED (Subotica), 0 PACK (Kikinda/Senta)
			}
		} else {
			$all_pro_for_main_plant = 0;
		}
		// dd($all_pro_for_main_plant);
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				d.[comment_operator], d.[layers_a] ,d.[layers_a_reasons], d.[extra], d.[id],
				p.[status], p.[mattress], 
				m.[spreading_method], m.[g_bin],
				mm.[marker_length], 
				mf.[layers_after_cs], mf.[layers_before_cs], mf.[id] as effid
			FROM [mattress_details] as d
			INNER JOIN [mattress_phases] as p ON p.[mattress_id] = d.[mattress_id] AND p.[active] = 1
			INNER JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			INNER JOIN [mattress_markers] as mm ON mm.[mattress_id] = d.[mattress_id]
			LEFT JOIN [mattress_effs] as mf ON mf.[mattress_id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));
		// dd($data);

		if (isset($data[0])) {

			if ((!is_null($data[0]->layers_before_cs)) OR ((float)$data[0]->layers_before_cs >= 1)) {

				// partialy spreaded
				
				$layers_after = (float)$layers_a - (float)$data[0]->layers_before_cs;

				if ($layers_after <= 0) {
					$msg = "Layers actual should be higher then partialy layer qty (".(float)$data[0]->layers_before_cs.") ! Kompletan broj slojeva treba biti veci od potvrdjenog parcijalnog broja slojeva koji je (".(float)$data[0]->layers_before_cs.") !  ";
					return view('spreader.error',compact('msg'));
					// dd($danger);
					// return view('spreader.spread_mattress_complete', compact('id','mattress_id','g_bin','comment_operator','status','mattress','layers_a','layers_a_reasons','danger','skeda_item_type' ));
					// return view('spreader.spread_mattress_complete', compact('id','mattress_id','comment_operator','status','mattress','g_bin','layers_a','layers_a_reasons','skeda_item_type','layers_partial','danger'));
				}

				if ($data[0]->spreading_method == "FACE UP") {
					$stimulation_after = (float)$layers_after * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100)) * 1.00 ;
				} else {
					$stimulation_after = (float)$layers_after * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100));
				}

				if ((date('H') >= 0) AND (date('H') < 6)) {
				   	$date_after = date('Y-m-d H:i:s', strtotime(' -1 day'));
				} else {
					$date_after = date('Y-m-d H:i:s');
				}


				$table_update_1 = mattress_eff::findOrFail($data[0]->effid);
				// $table_update_1->mattress_id = $id;
				// $table_update_1->mattress = $mattress;
				$table_update_1->layers_after_cs = $layers_after;
				$table_update_1->operator_after = $operator;
				$table_update_1->operator2_after = $operator2;
				$table_update_1->stimulation_after = $stimulation_after;
				$table_update_1->date_after = $date_after;
				$table_update_1->location_after = $location;
				// $table_update_1->layers_before_cs;
				// $table_update_1->operator_before;
				// $table_update_1->operator_before2;
				// $table_update_1->stimulation_before;
				// $table_update_1->date_before;
				// $table_update_1->location_before;
				$table_update_1->save();

				// print_r($position);
				$table_update_2 = mattress_details::findOrFail($data[0]->id);
				$table_update_2->layers_a = (float)$layers_a;
				$table_update_2->cons_actual = (float)$layers_a * ((float)$data[0]->marker_length + ((float)$data[0]->extra / 100));
				$table_update_2->position = $position;
				$table_update_2->all_pro_for_main_plant = $all_pro_for_main_plant;
				$table_update_2->comment_operator = $comment_operator;
				$table_update_2->layers_a_reasons = $layers_a_reasons;
				$table_update_2->layers_partial = (float)$layers_partial;
				$table_update_2->num_of_cut_problems = (int)$num_of_cut_problems;
				$table_update_2->save();

				// mattress_phasess
				// all mattress_phases for this mattress set to NOT ACTIVE
				// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				// 		id, mattress 
				// 	FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1"));
				
				// if (isset($find_all_mattress_phasses[0])) {
				// 	$mattress = $find_all_mattress_phasses[0]->mattress;

				// 	// dd($find_all_mattress_phasses);
				// 	for ($y=0; $y < count($find_all_mattress_phasses); $y++) { 
						
				// 			$table_update_3 = mattress_phases::findOrFail($find_all_mattress_phasses[$y]->id);
				// 			$table_update_3->active = 0;
				// 			$table_update_3->save();
				// 	}	
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
				if ($ploce == 1) {
					$status = "TO_JOIN";
					$location_new = "PSO";
					$active = 1;
				} else {
					$status = "TO_CUT";
					$location_new = "CUT";
					$active = 1;
				}

				if ((date('H') >= 0) AND (date('H') < 6)) {
				   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
				} else {
					$date = date('Y-m-d H:i:s');
				}
				
				// save mattress_phases
				// $table1_new = new mattress_phases;
				$table1_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
				$table1_new->mattress_id = $id;
				$table1_new->mattress = $mattress;
				$table1_new->status = $status;
				$table1_new->location = $location_new;
				$table1_new->device = $device;
				$table1_new->active = $active;
				$table1_new->operator1 = $operator;
				$table1_new->operator2 = $operator2;
				$table1_new->date = $date;
				$table1_new->id_status = $id.'-'.$status;
				$table1_new->save();
				

			} else {
				//completly spreaded

				if ($data[0]->spreading_method == "FACE UP") {
					$stimulation_after = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100)) * 1.00 ;
				} else {
					$stimulation_after = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100));
				}

				if ((date('H') >= 0) AND (date('H') < 6)) {
				   	$date_after = date('Y-m-d H:i:s', strtotime(' -1 day'));
				} else {
					$date_after = date('Y-m-d H:i:s');
				}

				$table2_new = new mattress_eff;
				$table2_new->mattress_id = $id;
				$table2_new->mattress = $mattress;
				$table2_new->layers_after_cs = $layers_a;
				$table2_new->operator_after = $operator;
				$table2_new->operator2_after = $operator2;
				$table2_new->stimulation_after = $stimulation_after;
				$table2_new->date_after = $date_after;
				$table2_new->location_after = $location;
				// $table2_new->layers_before_cs;
				// $table2_new->operator_before;
				// $table2_new->operator_before2;
				// $table2_new->stimulation_before;
				// $table2_new->$date_before;
				// $table2_new->$location_before;
				$table2_new->save();

				// print_r($position);
				$table_update_4 = mattress_details::findOrFail($data[0]->id);
				$table_update_4->layers_a = (float)$layers_a;
				$table_update_4->cons_actual = (float)$layers_a * ((float)$data[0]->marker_length + ((float)$data[0]->extra / 100));
				$table_update_4->position = $position;
				$table_update_4->all_pro_for_main_plant = $all_pro_for_main_plant;
				$table_update_4->comment_operator = $comment_operator;
				$table_update_4->layers_a_reasons = $layers_a_reasons;
				$table_update_4->layers_partial = (float)$layers_partial;
				$table_update_4->num_of_cut_problems = (int)$num_of_cut_problems;
				$table_update_4->save();

				// marttres_pro update
				$find_all_mattress_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				FROM [mattress_pros] WHERE [mattress_id] = '".$id."' "));
				// dd($find_all_mattress_pro);
				// dd($layers_a);
				// dd($layers_partial);

				for ($a=0; $a < count($find_all_mattress_pro); $a++) { 
					// dd($find_all_mattress_pro[$a]->id);

					$table_update_mattress_pro = mattress_pro::findOrFail($find_all_mattress_pro[$a]->id);
					$table_update_mattress_pro->pro_pcs_actual = ($table_update_mattress_pro->pro_pcs_layer * $layers_a) + (int)$layers_partial;
					// dd($table_update_mattress_pro->pro_pcs_actual);
					$table_update_mattress_pro->save();	
				}
				// dd('Stop');

				// mattress_phasess
				// all mattress_phases for this mattress set to NOT ACTIVE
				// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				// 		[id], [mattress] 
				// FROM [mattress_phases] WHERE [mattress_id] = '".$id."' AND [active] = 1"));
				
				// if (isset($find_all_mattress_phasses[0])) {
				// 	$mattress = $find_all_mattress_phasses[0]->mattress;

				// 	// dd($find_all_mattress_phasses);
				// 	for ($o=0; $o < count($find_all_mattress_phasses); $o++) { 

				// 			$table3_aa = mattress_phases::findOrFail($find_all_mattress_phasses[$o]->id);
				// 			$table3_aa->active = 0;
				// 			$table3_aa->save();
				// 	}
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
				if ($ploce == 1) {
					$status = "TO_JOIN";
					$location_new = "PSO";
					$active = 1;
				} else {
					$status = "TO_CUT";
					$location_new = "CUT";
					$active = 1;
				}

				if ((date('H') >= 0) AND (date('H') < 6)) {
				   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
				} else {
					$date = date('Y-m-d H:i:s');
				}

				// $table4_new = new mattress_phases;
				$table4_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
				$table4_new->mattress_id = $id;
				$table4_new->mattress = $mattress;
				$table4_new->status = $status;
				$table4_new->location = $location_new;
				$table4_new->device = $device;
				$table4_new->active = $active;
				$table4_new->operator1 = $operator;
				$table4_new->operator2 = $operator2;
				$table4_new->date = $date;
				$table4_new->id_status = $id.'-'.$status;
				$table4_new->save();
			}

			// dd($location);
			// reorder position of SP
			$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					md.[id], md.[mattress_id], md.[mattress], md.[position], mp.[location], mp.[active]
				 FROM [mattress_details] as md
				 INNER JOIN [mattress_phases] as mp ON (mp.[mattress_id] = md.[mattress_id]) AND (mp.[active] = 1)
				 WHERE location = '".$location."'
				 ORDER BY md.[position] asc"));

			if (isset($reorder_position[0])) {
				for ($p=0; $p < count($reorder_position); $p++) { 

					$table_update_5 = mattress_details::findOrFail($reorder_position[$p]->id);
					$table_update_5->position = $p+1;
					$table_update_5->save();
				}
			}
		}
		// dd('stop');
		return redirect('/spreader');
	}
}