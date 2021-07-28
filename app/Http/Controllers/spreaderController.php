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

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class spreaderController extends Controller {

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
		

		$pros= '';
		$skus= '';
		$sku_s= '';
		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			// dd($id);


			$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				ps.pro
				,ps.style_size
				,ps.sku
				--,*
			  FROM [mattress_pros] as mp
			  JOIN [pro_skedas] as ps ON ps.pro_id = mp.pro_id
			WHERE mp.mattress_id = '".$id."' "));
			
			for ($x=0; $x < count($prom); $x++) { 

				$pros .= $prom[$x]->pro." ";
				$skus .= $prom[$x]->style_size." ";
				$sku_s .= $prom[$x]->sku." ";
			}

			$data[$i]->pro = trim($pros);
			$data[$i]->style_size = trim($skus);
			$data[$i]->sku = trim($sku_s);
			$pros = '';
			$skus = '';
			$sku_s = '';
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

		$operator = Session::get('operator');
		// dd($operator);
		// $operator = 'test';

		$timerange = date("d/m/Y");


		// dd($timerange);

		// $efficiency_check = DB::connection('sqlsrv')->select(DB::raw("  SELECT 
		// 	  sUM([stimulation_before]) as s_before
		// 	  --,SUM([layers_before_cs]) as l_before
		// 	  --,SUM([stimulation_after]) as s_after
		// 	  --,SUM([layers_after_cs]) as l_after
		// 	  FROM [cutting].[dbo].[mattress_effs] 
		// 	  WHERE (/*[operator_before] = '".$operator."' OR */[operator_after] = '".$operator."' ) AND
		// 	  created_at like 
		// "));
		// dd($efficiency_check);



		// dd("Test");
		return view('spreader.index', compact('data','location','operators','operator'));
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

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
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
		$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				id, mattress 
			FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1"));
		
		if (isset($find_all_mattress_phasses[0])) {
			$mattress = $find_all_mattress_phasses[0]->mattress;

			// dd($find_all_mattress_phasses);
			for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
				// try {
					$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);

					$table3->active = 0;
					$table3->save();
				// }
				// catch (\Illuminate\Database\QueryException $e) {
				// 	dd("Problem to save in mattress_phases, set all to not active");
				// }
			}	
		}
		// save new mattress_phases
		$status = "TO_SPREAD";
		$active = 1;
		// $operator1;

		// try {
			$table3_new = new mattress_phases;
			$table3_new->mattress_id = $id;
			$table3_new->mattress = $mattress;
			$table3_new->status = $status;
			$table3_new->location = $location;
			$table3_new->device = $device;
			$table3_new->active = $active;
			$table3_new->operator1 = $operator;
			$table3_new->operator2;
			$table3_new->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattress_phases");
		// }

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

		// $operator = Session::get('operator');

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
		$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT
		 	mp.[id], mp.[mattress], mp.[status], m.[g_bin]
		 	FROM [mattress_phases] as mp 
		 	JOIN [mattresses] as m ON m.[id] = mp.[mattress_id]
		 	WHERE mp.[mattress_id] = '".$id."' AND mp.[active] = 1  "));
		
		
		if (isset($find_all_mattress_phasses[0])) {
			$mattress = $find_all_mattress_phasses[0]->mattress;

			// dd($find_all_mattress_phasses);
			for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 

				// if (($find_all_mattress_phasses[$i]->active == 'TO_LOAD') OR ($find_all_mattress_phasses[$i]->status == 'TO_SPREAD')){
					$table3_update = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
					$table3_update->active = 0;
					$table3_update->save();
				// }
			}
		}

		// dd('stio');
		// add to mattress_phases
		$table3_new = new mattress_phases;
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $find_all_mattress_phasses[0]->mattress;
		$table3_new->status = "TO_LOAD";
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = 1;
		$table3_new->operator1 = Session::get('operator');
		$table3_new->operator2;
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
		$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT
		 	id, mattress, status 
		 	FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1 "));
		// dd($find_all_mattress_phasses);

		if (isset($find_all_mattress_phasses[0])) {
			$mattress = $find_all_mattress_phasses[0]->mattress;

			// dd($find_all_mattress_phasses);
			for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 

				// if (($find_all_mattress_phasses[$i]->active == 'TO_LOAD') OR ($find_all_mattress_phasses[$i]->status == 'TO_SPREAD')){
					$table3_update = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
					$table3_update->active = 0;
					$table3_update->save();
				// }
			}

			$table3_new = new mattress_phases;
			$table3_new->mattress_id = $id;
			$table3_new->mattress = $mattress;
			$table3_new->status = "ON_HOLD";
			$table3_new->location = $location;
			$table3_new->device = $device;
			$table3_new->active = 1;
			$table3_new->operator1 = $operator;
			$table3_new->operator2;
			$table3_new->save();
		}
		// if is partialy spreaded to delete line in eff ???????????????????????????????????

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

		$table3 = mattress_details::findOrFail($data[0]->id);
		$table3->comment_operator = $comment_operator;
		$table3->save();

		$success = "Saved succesfuly";
		return view('spreader.other_functions', compact('id','comment_operator','status', 'mattress', 'g_bin','success'));
	}

	public function mattress_to_spread($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('spreader.error',compact('msg'));
		}

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
				$stimulation_before = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100)) * 1.15 ;
			} else {
				$stimulation_before = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100));
			}
			// dd($data[0]->id);

			$table3_new = new mattress_eff;
			$table3_new->mattress_id = $id;
			$table3_new->mattress = $mattress;
			$table3_new->layers_after_cs = 0;
			$table3_new->operator_after = '';
			$table3_new->layers_before_cs = (float)$layers_a;
			$table3_new->operator_before = $operator;
			$table3_new->stimulation_after = 0;
			$table3_new->stimulation_before = (float)$stimulation_before;
			$table3_new->save();

			$table2_update = mattress_details::findOrFail($data[0]->id);
			$table2_update->layers_partial = (float)$layers_a;
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
		$layers_partial = $data[0]->layers_partial;
		// dd($layers_partial);

		if ($layers_partial == NULL){
			$layers_partial = 0;
		}

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
		// dd($layers_partial);
		
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
				,s.pro
				,p.location_all
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
					$danger = "Layers actual should be higher then partialy layer qty (".(float)$data[0]->layers_before_cs.") !";
					return view('spreader.spread_mattress_complete', compact('id','mattress_id','comment_operator','status','mattress','layers_a','layers_a_reasons','danger' ));
				}

				if ($data[0]->spreading_method == "FACE UP") {
					$stimulation_after = (float)$layers_after * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100)) * 1.15 ;
				} else {
					$stimulation_after = (float)$layers_after * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100));
				}

				$table6_update = mattress_eff::findOrFail($data[0]->effid);
				// $table6_update->mattress_id = $id;
				// $table6_update->mattress = $mattress;
				$table6_update->layers_after_cs = $layers_after;
				$table6_update->operator_after = $operator;
				// $table6_update->layers_before_cs;
				// $table6_update->operator_before;
				$table6_update->stimulation_after = $stimulation_after;
				// $table6_update->stimulation_before;
				$table6_update->save();

				// print_r($position);
				$table2_update = mattress_details::findOrFail($data[0]->id);
				$table2_update->layers_a = (float)$layers_a;
				$table2_update->cons_actual = (float)$layers_a * ((float)$data[0]->marker_length + ((float)$data[0]->extra / 100));
				$table2_update->position = $position;
				$table2_update->all_pro_for_main_plant = $all_pro_for_main_plant;
				$table2_update->comment_operator = $comment_operator;
				$table2_update->layers_a_reasons = $layers_a_reasons;
				$table2_update->layers_partial = (float)$layers_partial;
				$table2_update->save();

				// mattress_phasess
				// all mattress_phases for this mattress set to NOT ACTIVE
				$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						id, mattress 
					FROM [mattress_phases] WHERE mattress_id = '".$id."' AND active = 1"));
				
				if (isset($find_all_mattress_phasses[0])) {
					$mattress = $find_all_mattress_phasses[0]->mattress;

					// dd($find_all_mattress_phasses);
					for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
						// try {
							$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
							$table3->active = 0;
							$table3->save();
						// }
						// catch (\Illuminate\Database\QueryException $e) {
						// 	dd("Problem to save in mattress_phases, set all to not active");
						// }
					}	
				}
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
				
				// save mattress_phases
				// try {
					$table3_new = new mattress_phases;
					$table3_new->mattress_id = $id;
					$table3_new->mattress = $mattress;
					$table3_new->status = $status;
					$table3_new->location = $location_new;
					$table3_new->device = $device;
					$table3_new->active = $active;
					$table3_new->operator1 = $operator;
					$table3_new->operator2;
					$table3_new->save();
				// }
				// catch (\Illuminate\Database\QueryException $e) {
				// 	dd("Problem to save in mattress_phases");
				// }

			} else {
				//completly spreaded

				if ($data[0]->spreading_method == "FACE UP") {
					$stimulation_after = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100)) * 1.15 ;
				} else {
					$stimulation_after = (float)$layers_a * ((float)$data[0]->marker_length +((float)$data[0]->extra / 100));
				}

				$table3_new = new mattress_eff;
				$table3_new->mattress_id = $id;
				$table3_new->mattress = $mattress;
				$table3_new->layers_after_cs = $layers_a;
				$table3_new->operator_after = $operator;
				// $table3_new->layers_before_cs;
				// $table3_new->operator_before;
				$table3_new->stimulation_after = $stimulation_after;
				// $table3_new->stimulation_before;
				$table3_new->save();

				// print_r($position);
				$table2_update = mattress_details::findOrFail($data[0]->id);
				$table2_update->layers_a = (float)$layers_a;
				$table2_update->cons_actual = (float)$layers_a * ((float)$data[0]->marker_length + ((float)$data[0]->extra / 100));
				$table2_update->position = $position;
				$table2_update->all_pro_for_main_plant = $all_pro_for_main_plant;
				$table2_update->comment_operator = $comment_operator;
				$table2_update->layers_a_reasons = $layers_a_reasons;
				$table2_update->layers_partial = (float)$layers_partial;
				$table2_update->save();

				// mattress_phasess
				// all mattress_phases for this mattress set to NOT ACTIVE
				$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						[id], [mattress] 
				FROM [mattress_phases] WHERE [mattress_id] = '".$id."' AND [active] = 1"));
				
				if (isset($find_all_mattress_phasses[0])) {
					$mattress = $find_all_mattress_phasses[0]->mattress;

					// dd($find_all_mattress_phasses);
					for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
						// try {
							$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);

							$table3->active = 0;
							$table3->save();
						// }
						// catch (\Illuminate\Database\QueryException $e) {
						// 	dd("Problem to save in mattress_phases, set all to not active");
						// }
					}
				}
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
				// $operator1;

				// try {
					$table3_new = new mattress_phases;

					$table3_new->mattress_id = $id;
					$table3_new->mattress = $mattress;
					$table3_new->status = $status;
					$table3_new->location = $location_new;
					$table3_new->device = $device;
					$table3_new->active = $active;
					$table3_new->operator1 = $operator;
					$table3_new->operator2;
					$table3_new->save();
				// }
				// catch (\Illuminate\Database\QueryException $e) {
				// 	dd("Problem to save in mattress_phases");
				// }
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
				for ($i=0; $i < count($reorder_position); $i++) { 

					$table1 = mattress_details::findOrFail($reorder_position[$i]->id);
					$table1->position = $i+1;
					$table1->save();
				}
			}
		}
		// dd('stop');
		return redirect('/spreader');
	}
}