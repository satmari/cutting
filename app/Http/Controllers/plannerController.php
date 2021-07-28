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
use App\mattress_pro;
use App\marker_change;
use App\mattress;
use App\o_roll;
use App\paspul;
use App\paspul_line;

use App\print_standard_mattress;
use App\print_mini_mattress;

// use DB;
use Illuminate\Support\Facades\DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class plannerController extends Controller {

	public function plan_mattress($location) {
		
		$operator = Session::get('operator');
		
		$work_place = "PLANNER";
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$user = User::find(Auth::id());
		// dd($user);
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');

			if ($user->is('admin')) {
				
				$operator = 'admin';
				Session::set('operator', $operator);
			} else {
				$msg ='Operator must be logged!';
				return view('planner.error',compact('msg', 'operator', 'operators'));	
			}
			dd('stop');
		}

		if (($location == 'LEC1') OR ($location == 'LEC2')) {
			$location = 'CUT';
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
		      ,m2.[printed_nalog]
		      ,m2.[layer_limit]
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
	
		$pros= '';
		$skus= '';
		$sku_s= '';
		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			// dd($id);

			if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
				
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

			} else {

				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.pro
					,ps.style_size
					,ps.sku
					--,*
				  FROM  [pro_skedas] as ps 
				WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
				// dd($prom);

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
			
		}

	
		if ($location == 'ON_HOLD') {
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
			      ,m2.[printed_nalog]
			      ,m2.[layer_limit]
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
			  WHERE m4.[status] = '".$location."' AND m4.active = '1' 
			  ORDER BY m2.position asc"));

			$pros= '';
			$skus= '';
			$sku_s= '';
			for ($i=0; $i < count($data) ; $i++) { 
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
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

				} else {

					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.pro
						,ps.style_size
						,ps.sku
						--,*
					  FROM  [pro_skedas] as ps 
					WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
					// dd($prom);

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
				
			}
		}

		if ($location == 'PLOT') {
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
			      ,m2.[printed_nalog]
			      ,m2.[layer_limit]
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
			  WHERE m4.[active] = '1' AND m2.[printed_marker] = 0 AND m3.[marker_name] != '' AND (m4.[status] != 'DELETED' AND m4.[status] != 'COMPLETED')
			  ORDER BY m2.position asc"));
			
			$pros= '';
			$skus= '';
			$sku_s= '';
			for ($i=0; $i < count($data) ; $i++) { 
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
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

				} else {

					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.pro
						,ps.style_size
						,ps.sku
						--,*
					  FROM  [pro_skedas] as ps 
					WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
					// dd($prom);

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
				
			}
		}

		if ($location == 'BOARD') {
			// dd("Test");
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'NOT_SET' AND m4.[active] = 1 
		  ORDER BY m2.position asc"));
			
			$sp1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			   m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'SP1' AND m4.active = 1 
		  ORDER BY m2.position asc"));

			$sp1_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'SP1' AND m4.active = 1"));
			$sp1_m = $sp1_m[0]->sum_m_cons;
			// dd($sp1_m);

			$sp2 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			   m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'SP2' AND m4.active = 1 
		  ORDER BY m2.position asc"));

			$sp2_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'SP2' AND m4.active = 1"));
			$sp2_m = $sp2_m[0]->sum_m_cons;

			$sp3 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'SP3' AND m4.active = 1 
		  ORDER BY m2.position asc"));
			// dd($sp3);

			$sp3_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'SP3' AND m4.active = 1"));
			$sp3_m = $sp3_m[0]->sum_m_cons;

			$sp4 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			   m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'SP4' AND m4.active = 1 
		  ORDER BY m2.position asc"));

			$sp4_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'SP4' AND m4.active = 1"));
			$sp4_m = $sp4_m[0]->sum_m_cons;

			$ms1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			   m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'MS1' AND m4.active = 1 
		  ORDER BY m2.position asc"));

			$ms1_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'MS1' AND m4.active = 1"));
			$ms1_m = $ms1_m[0]->sum_m_cons;

			$ms2 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'MS2' AND m4.active = 1 
		  ORDER BY m2.position asc"));

			$ms2_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'MS2' AND m4.active = 1"));
			$ms2_m = $ms2_m[0]->sum_m_cons;

			$ms3 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			 m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'MS3' AND m4.active = 1 
		  ORDER BY m2.position asc"));
			// dd($sp3);

			$ms3_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			WHERE m4.[location] = 'MS3' AND m4.active = 1"));
			$ms3_m = $ms3_m[0]->sum_m_cons;

			$mm1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[material]
		      ,m1.[g_bin]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[skeda]
		      ,m1.[spreading_method]
		      ,m1.[width_theor_usable]
		      ,m2.[position]
		      ,m2.[layers]
		      ,m2.[cons_planned]
		      ,m2.[priority]
		      ,m2.[printed_marker]
		      ,m2.[comment_office]
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m4.[status]
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  WHERE m4.[location] = 'MM1' AND m4.[active] = 1 
		  ORDER BY m2.[position] asc"));

			$mm1_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.layers_a) as sum_m_layers,
					SUM(m2.cons_actual) as sum_m_cons,
					SUM(o.no_of_joinings) as o_sum
			FROM [mattresses] as m1
			LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			JOIN [o_rolls] as o ON o.[mattress_id_new] = m1.[id]
			WHERE m4.[location] = 'MM1' AND m4.[active] = 1"));
			// $mm1_m = $mm1_m[0]->sum_m_cons;
			$mm1_m = $mm1_m[0]->o_sum;

			return view('planner.plan_mattress', compact('data','location','sp1','sp2','sp3','sp4','ms1','ms2','ms3','mm1','operator','operators','sp1_m','sp2_m','sp3_m','sp4_m','ms1_m','ms2_m','ms3_m','mm1_m'));
		}

		if ($location == 'BOARD_TABLE') {
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
		      ,m2.[printed_nalog]
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
		  WHERE (m4.[location] IN ('SP1','SP2','SP3','SP4','MS1','MS2','MS3','MM1')) AND m4.[active] = '1' 
		  ORDER BY m2.[position] asc"));
			// dd($data);

			$pros= '';
			$skus= '';
			$sku_s= '';
			$pros= '';
			$skus= '';
			$sku_s= '';
			for ($i=0; $i < count($data) ; $i++) { 
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
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

				} else {

					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.pro
						,ps.style_size
						,ps.sku
						--,*
					  FROM  [pro_skedas] as ps 
					WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
					// dd($prom);

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
				
			}
		
			return view('planner.plan_mattress', compact('data','location','operator','operators'));
		}
		return view('planner.plan_mattress', compact('data','location','operator','operators'));
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
				return redirect('/plan_mattress/BOARD');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/plan_mattress/BOARD');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/plan_mattress/BOARD');
		}
	}
	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/plan_mattress/BOARD');
	}
	public function reposition() {
        $i = 0;

        if (isset($_POST['item'] )) {
        	foreach ($_POST['item'] as $value) {
	            // Execute statement:
	            // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value $operator = Session::get('operator');
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
        	}	
        }
    }
	public function reposition2() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            
	            // DB::table('mattress_details as d')
	            // ->join('mattresses as m', 'm.id', '=', 'd.mattress_id')
	            // ->where('m.id', '=', $value)->update(['d.position' =>  DB::raw($i) ]);
	            
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP1', 'operator1' => Session::get('operator')]);
	        }	
	}
	public function reposition3() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP2', 'operator1' => Session::get('operator') ]);
	        }		
	}
	public function reposition4() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP3', 'operator1' => Session::get('operator') ]);
	        }	
	}
	public function reposition5() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP4', 'operator1' => Session::get('operator') ]);
	        }	
	}
	public function reposition6() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'MS1', 'operator1' => Session::get('operator') ]);
	        }	
	}
	public function reposition7() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'MS2', 'operator1' => Session::get('operator') ]);
	        }		
	}
	public function reposition8() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',TRUE)->update(['location' => 'MS3', 'operator1' => Session::get('operator') ]);
	        }	
	}
	public function reposition9() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'MM1', 'operator1' => Session::get('operator') ]);
	        }	
	}
	public function reposition_pas() {
        $i = 0;

        if (isset($_POST['item'] )) {
        	foreach ($_POST['item'] as $value) {
	            // Execute statement:
	            // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value $operator = Session::get('operator');
	            $i++;
	            DB::table('paspuls')->where('id', '=', $value)->update([ 'position' => $i ]);
        	}	
        }
    }

// mattress
	public function plan_mattress_line ($id) {

		// dd($id);
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
		      ,m2.[tpa_number]
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
		  WHERE m1.[id] = '".$id."' AND m4.active = '1' 
		  ORDER BY m2.position asc"));
		// dd($data);

		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$id = $data[0]->id;
		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$spreading_method = $data[0]->spreading_method;
		$width_theor_usable = $data[0]->width_theor_usable;
		$layers = $data[0]->layers;
		$cons_planned = $data[0]->cons_planned;
		$marker_name = $data[0]->marker_name;
		$marker_length = $data[0]->marker_length;
		$marker_width = $data[0]->marker_width;
		$pcs_bundle = $data[0]->pcs_bundle;
		$priority = $data[0]->priority;
		$call_shift_manager = $data[0]->call_shift_manager;
		// $call_shift_manager = 1;
		$test_marker = $data[0]->test_marker;
		$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;
		$bottom_paper = $data[0]->bottom_paper;
		$comment_office = $data[0]->comment_office;
		$tpa_number = $data[0]->tpa_number;

		return view('planner.plan_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc','skeda','skeda_item_type','spreading_method','width_theor_usable','layers','cons_planned','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager','test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office'));
	}

	public function plan_mattress_line_confirm(Request $request) {

		$this->validate($request, ['id'=>'required','pcs_bundle'=>'required','location'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$mattress = $input['mattress'];
		$pcs_bundle = (int)$input['pcs_bundle'];
		$priority = (int)$input['priority'];
		$comment_office = $input['comment_office'];
		$bottom_paper = $input['bottom_paper'];
		$skeda_item_type = $input['skeda_item_type'];
		$spreading_method = $input['spreading_method'];

		if (isset($input['call_shift_manager'])) {
			$call_shift_manager = (int)$input['call_shift_manager'];
		} else {
			$call_shift_manager = 0;
		}
		if (isset($input['test_marker'])) {
			$test_marker = (int)$input['test_marker'];
		} else {
			$test_marker = 0;
		}
		// if (isset($input['tpp_mat_keep_wastage'])) {
		// 	$tpp_mat_keep_wastage = (int)$input['tpp_mat_keep_wastage'];
		// } else {
		// 	$tpp_mat_keep_wastage = 0;
		// }
		
		$location = $input['location'];

		// Gbin check !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// $g_bin = 'G000000001';

		if ($skeda_item_type == 'MM') {
			$g_bin = NULL;

		} else {
			$find_g_bin = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					TOP 1 g_bin
				FROM [mattresses] ORDER BY g_bin desc"));
			// dd($find_g_bin);
			$bin = (int)substr($find_g_bin[0]->g_bin, -8);
			// dd($bin);
			$num = str_pad($bin+1, 9, 0, STR_PAD_LEFT);
			// dd("G".$num);

			$g_bin = "G".$num;
			// dd($g_bin);
		}

		// Position check
		// $position = 1;
		$find_position_on_location = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					COUNT(location) as c 
				FROM [mattress_phases] WHERE location = '".$location."' AND active = '1' "));
		// dd($find_position_on_location[0]);
		if (isset($find_position_on_location[0])) {
			$position = $find_position_on_location[0]->c + 1;
		} else {
			$position = 1;
		}
		// dd($position);

		$table00 = mattress::findOrFail($id);
		$table00->g_bin = $g_bin;
		$table00->spreading_method = $spreading_method;
		$table00->save();

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_details] WHERE [mattress_id] = '".$id."' "));
		// dd($find_details_id[0]->id);

		try {
			$table1 = mattress_details::findOrFail($find_details_id[0]->id);
			$table1->pcs_bundle;
			$table1->position = $position;
			$table1->priority = $priority;
			$table1->call_shift_manager = $call_shift_manager;
			$table1->test_marker = $test_marker;
			$table1->pcs_bundle = $pcs_bundle;
			// $table1->tpp_mat_keep_wastage = $tpp_mat_keep_wastage;
			$table1->bottom_paper = $bottom_paper;
			$table1->comment_office = $comment_office;
			$table1->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd("Problem to save in mattress_details");
		}
		// dd("STOP");
		// all mattress_phases for this mattress set to NOT ACTIVE

		$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_phases] WHERE [mattress_id] = '".$id."' "));
		
		if (isset($find_all_mattress_phasses[0])) {
			// dd($find_all_mattress_phasses);
			for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
				try {
					$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
					$table3->active = 0;
					$table3->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("Problem to save in mattress_phases, set all to not active");
				}
			}	
		}

		// save new mattress_phases
		$status = "TO_LOAD";
		$active = 1;
		// $operator1;

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $operator1 = Auth::user()->name;
		} else {
			$msg = 'User is not autenticated';
			return view('planner.error',compact('msg'));
		}

		try {
			$table3_new = new mattress_phases;
			$table3_new->mattress_id = $id;
			$table3_new->mattress = $mattress;
			$table3_new->status = $status;
			$table3_new->location = $location;
			$table3_new->device;
			$table3_new->active = $active;
			$table3_new->operator1 = Session::get('operator');
			$table3_new->operator2;
			$table3_new->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd("Problem to save in mattress_phases");
		}

		// reorder position of NOT_SET
		$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				md.[id], md.[mattress_id], md.[mattress], md.[position], 
				mp.[location], mp.[active]
			 FROM [mattress_details] as md
			 INNER JOIN [mattress_phases] as mp ON mp.[mattress_id] = md.[mattress_id] AND mp.[active] = 1
			 WHERE location = 'NOT_SET' 
			 ORDER BY position asc"));

		if (isset($reorder_position[0])) {
			for ($i=0; $i < count($reorder_position); $i++) { 

				$table1 = mattress_details::findOrFail($reorder_position[$i]->id);
				$table1->position = $i+1;
				$table1->save();
			}
		}
		return Redirect::to('/plan_mattress/NOT_SET');
	}

	public function change_marker($id) {
		// dd($id);

		$find_marker = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mm.[marker_name], mm.[marker_id], mm.[mattress], mm.[id], mm.[marker_length], mm.[marker_width], mm.[min_length],
				md.[requested_width],
				m.[width_theor_usable], m.[g_bin]
			FROM [mattress_markers] as mm
			JOIN [mattress_details] as md ON md.[mattress_id] = mm.[mattress_id]
			JOIN [mattresses] as m ON m.[id] = mm.[mattress_id]
			WHERE mm.[mattress_id] = ".(int)$id." "));
		// dd($find_marker);

		if ((!isset($find_marker[0]->marker_name)) OR ($find_marker[0]->marker_name != '')) {
			
			$existing_marker = $find_marker[0]->marker_name;
			$existing_marker_id = $find_marker[0]->marker_id;
			$existing_mattress_marker_id = $find_marker[0]->id;
			$existing_marker_length = $find_marker[0]->marker_length;
			$existing_marker_width = $find_marker[0]->marker_width;
			$requested_width = $find_marker[0]->requested_width;
			$width_theor_usable = $find_marker[0]->width_theor_usable;
			$existing_min_length = $find_marker[0]->min_length;
			$mattress = $find_marker[0]->mattress;
			$g_bin = $find_marker[0]->g_bin;
			// dd($existing_marker);
			/*
			$style = substr($mattress, 0,6);
			// dd($style);

			$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT
					marker_name
				FROM [marker_headers] 
				WHERE [marker_name] like '%".$style."%'
				ORDER BY id"));
			*/
	
			// $existing_marker_style_size = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			// 	ml.[style_size],
			// 	ml.[pcs_on_layer]
			//   FROM [marker_headers] as mh
			//   JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
			//   WHERE mh.[marker_name] = 'CLD858-4S-4M-2L-171-R16' "));

			$existing_marker_style_size = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.marker_name,
				   size = STUFF(
						(SELECT DISTINCT ' ' +CAST(ml.style_size as VARCHAR(MAX) 
						)+ CAST (ml.pcs_on_layer as VARCHAR(MAX))
						FROM [marker_lines] as ml
						WHERE ml.marker_header_id = mh.id
						FOR XML PATH('')),1,1,' '
					)
			FROM [marker_headers] as mh
			WHERE marker_name =  '".$existing_marker."' "));
			
			$find_marker_style_size = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.marker_name,
			   size = STUFF(
					(SELECT DISTINCT ' ' +CAST(ml.style_size as VARCHAR(MAX) 
					)+ CAST (ml.pcs_on_layer as VARCHAR(MAX))
					FROM [marker_lines] as ml
					WHERE ml.marker_header_id = mh.id
					FOR XML PATH('')),1,1,' '
				)
			FROM [marker_headers] as mh"));

			$markers[] = '';
			for ($t=0; $t < count($find_marker_style_size); $t++) { 
				// print_r(trim($find_marker_style_size[$t]->size));

				if (trim($find_marker_style_size[$t]->size) == trim($existing_marker_style_size[0]->size)) {
					// print_r($find_marker_style_size[$t]->marker_name."<br>");

					array_push($markers, $find_marker_style_size[$t]->marker_name);
				}
			}

			// $markers = (object)(array_filter($markers));
			$markers = array_filter($markers);
			// dd($markers);

			// print_r(array_filter($markers));
			// dd("stop");
			return view('planner.change_marker',compact('id','mattress','g_bin','existing_marker','existing_mattress_marker_id', 
				'existing_marker_id', 'existing_marker_length', 'existing_marker_width','markers', 'requested_width'/*, 'style'*/,'width_theor_usable', 'existing_min_length'));

		} else {
			$msg = 'Marker is not liked with mattress!';
			return view('planner.error',compact('msg'));
		}
	}

	public function change_marker_post(Request $request) {

		$this->validate($request, ['selected_marker'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$user = User::find(Auth::id());

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			if ($user->is('admin')) {
				
				$operator = 'admin';
				Session::set('operator', $operator);
			} else {
				$msg ='Operator must be logged!';
				return view('planner.error',compact('msg', 'operator', 'operators'));	
			}
		}

		$id = (int)$input['id'];
		$mattress = $input['mattress'];
		$existing_marker = $input['existing_marker'];
		$existing_marker_id = $input['existing_marker_id'];
		$existing_mattress_marker_id = $input['existing_mattress_marker_id'];
		$existing_marker_length = $input['existing_marker_length'];
		$existing_marker_width = $input['existing_marker_width'];
		$existing_min_length = $input['existing_min_length'];
		$selected_marker = $input['selected_marker'];
		// dd($selected_marker);

		$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					id, marker_name, marker_width, marker_length, min_length
				FROM [marker_headers] 
				WHERE marker_name = '".$selected_marker."' "));
		// dd($markers);

		$table4 = mattress_markers::findOrFail($existing_mattress_marker_id);
		$table4->marker_id = (int)$markers[0]->id;
		$table4->marker_name = $markers[0]->marker_name;
		// $table4->marker_name_orig = $input['existing_marker'];
		$table4->marker_width = round((float)$markers[0]->marker_width,3);
		$table4->marker_length = round((float)$markers[0]->marker_length,3);
		$table4->min_length = round((float)$markers[0]->min_length,3);
		$table4->save();

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT d.[id] 
			FROM [mattress_details] as d JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
			WHERE m.[id] = '".$id."' "));

		$table2 = mattress_details::findOrFail($find_details_id[0]->id);
		$table2->cons_actual = $table2->layers_a * ((float)$markers[0]->marker_length + ($table2->extra/100));
		$table2->save();

		// add to marker log
		$table7_new = new marker_change;
		$table7_new->mattress_id = $id;
		$table7_new->mattress = $mattress;
		$table7_new->marker_id_orig = (int)$existing_marker_id;
		$table7_new->marker_name_orig = $existing_marker;
		$table7_new->marker_length_orig = round((float)$existing_marker_length,3);
		$table7_new->marker_width_orig = round((float)$existing_marker_width,3);
		$table7_new->min_length_orig = round((float)$existing_min_length,3);
		$table7_new->marker_id_new = (int)$markers[0]->id;
		$table7_new->marker_name_new = $markers[0]->marker_name;
		$table7_new->marker_length_new = round((float)$markers[0]->marker_length,3);
		$table7_new->marker_width_new = round((float)$markers[0]->marker_width,3);
		$table7_new->min_length_new = round((float)$markers[0]->min_length,3);
		$table7_new->save();

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('planner.error',compact('msg'));
		}

		// change mattress_phases
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
		}

		// add to mattress_phases
		$table3_new = new mattress_phases;
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = "TO_LOAD";
		$table3_new->location = $table3_update->location;
		$table3_new->device = $table3_update->device;
		$table3_new->active = 1;
		$table3_new->operator1 = Session::get('operator');
		$table3_new->operator2;
		$table3_new->save();

		return Redirect::to('/plan_mattress/BOARD');
	}

	public function edit_mattress($id) {
		// dd($id);
		// dd($id);
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
		      ,m2.[tpa_number]
		      ,m2.[layer_limit]
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
		  WHERE m1.[id] = '".$id."' AND m4.active = '1' 
		  ORDER BY m2.position asc"));
		// dd($data);

		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$id = $data[0]->id;
		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$spreading_method = $data[0]->spreading_method;
		$width_theor_usable = $data[0]->width_theor_usable;
		$layers = $data[0]->layers;
		$cons_planned = $data[0]->cons_planned;
		$marker_name = $data[0]->marker_name;
		$marker_length = $data[0]->marker_length;
		$marker_width = $data[0]->marker_width;
		$pcs_bundle = $data[0]->pcs_bundle;
		$priority = $data[0]->priority;
		$call_shift_manager = $data[0]->call_shift_manager;
		// $call_shift_manager = 1;
		$test_marker = $data[0]->test_marker;
		$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;
		$bottom_paper = $data[0]->bottom_paper;
		$comment_office = $data[0]->comment_office;
		$tpa_number = $data[0]->tpa_number;

		$location = $data[0]->location;
		
		if ($location == 'MM1') {

			$layer_limit = $data[0]->layer_limit;
			$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[no_of_joinings]
				FROM  [o_rolls]
				WHERE mattress_id_new = '".$id."' "));
			// dd($data2);
			return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc','skeda','skeda_item_type','spreading_method','width_theor_usable','layers','cons_planned','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager','test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office','location','data2','layer_limit'));
		}

		return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc','skeda','skeda_item_type','spreading_method','width_theor_usable','layers','cons_planned','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager','test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office','location'));
	}
	public function edit_mattress_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$mattress = $input['mattress'];
		$skeda_item_type = $input['skeda_item_type'];

		$priority = (int)$input['priority'];
		$comment_office = $input['comment_office'];
		$spreading_method = $input['spreading_method'];
		$pcs_bundle = (int)$input['pcs_bundle'];
		$bottom_paper = $input['bottom_paper'];

		if (isset($input['call_shift_manager'])) {
			$call_shift_manager = (int)$input['call_shift_manager'];
		} else {
			$call_shift_manager = 0;
		}
		if (isset($input['test_marker'])) {
			$test_marker = (int)$input['test_marker'];
		} else {
			$test_marker = 0;
		}
		// if (isset($input['tpp_mat_keep_wastage'])) {
		// 	$tpp_mat_keep_wastage = (int)$input['tpp_mat_keep_wastage'];
		// } else {
		// 	$tpp_mat_keep_wastage = 0;
		// }
		
		$location = $input['location'];

		// Gbin check !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// $g_bin = 'G000000001';

		// if ($skeda_item_type == 'MM') {
		// 	$g_bin = NULL;

		// } else {
		// 	$find_g_bin = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 			TOP 1 g_bin
		// 		FROM [mattresses] ORDER BY g_bin desc"));
		// 	// dd($find_g_bin);
		// 	$bin = (int)substr($find_g_bin[0]->g_bin, -8);
		// 	// dd($bin);
		// 	$num = str_pad($bin+1, 9, 0, STR_PAD_LEFT);
		// 	// dd("G".$num);

		// 	$g_bin = "G".$num;
		// 	// dd($g_bin);
		// }

		// Position check
		// $position = 1;
		// $find_position_on_location = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 			COUNT(location) as c 
		// 		FROM [mattress_phases] WHERE location = '".$location."' AND active = '1' "));
		// // dd($find_position_on_location[0]);
		// if (isset($find_position_on_location[0])) {
		// 	$position = $find_position_on_location[0]->c + 1;
		// } else {
		// 	$position = 1;
		// }
		// dd($position);

		$table001 = mattress::findOrFail($id);
		$table001->spreading_method = $spreading_method;
		$table001->save();

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_details] WHERE [mattress_id] = '".$id."' "));
		// dd($find_details_id[0]->id);

		// try {
			$table1 = mattress_details::findOrFail($find_details_id[0]->id);
			$table1->pcs_bundle = $pcs_bundle;
			// $table1->position = $position;
			$table1->priority = $priority;
			$table1->call_shift_manager = $call_shift_manager;
			$table1->test_marker = $test_marker;
			// $table1->tpp_mat_keep_wastage = $tpp_mat_keep_wastage;
			$table1->bottom_paper = $bottom_paper;
			$table1->comment_office = $comment_office;
			$table1->save();



		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattress_details");
		// }
		// dd("STOP");
		// all mattress_phases for this mattress set to NOT ACTIVE

		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_phases] WHERE [mattress_id] = '".$id."' "));
		
		// if (isset($find_all_mattress_phasses[0])) {
		// 	// dd($find_all_mattress_phasses);
		// 	for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
		// 		try {
		// 			$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
		// 			$table3->active = 0;
		// 			$table3->save();
		// 		}
		// 		catch (\Illuminate\Database\QueryException $e) {
		// 			dd("Problem to save in mattress_phases, set all to not active");
		// 		}
		// 	}	
		// }

		// save new mattress_phases
		// $status = "TO_LOAD";
		// $active = 1;
		// $operator1;

		// verify userId
		// if (Auth::check())
		// {
		//     $userId = Auth::user()->id;
		//     $operator1 = Auth::user()->name;
		// } else {
		// 	$msg = 'User is not autenticated';
		// 	return view('planner.error',compact('msg'));
		// }

		// try {
		// 	$table3_new = new mattress_phases;
		// 	$table3_new->mattress_id = $id;
		// 	$table3_new->mattress = $mattress;
		// 	$table3_new->status = $status;
		// 	$table3_new->location = $location;
		// 	$table3_new->device;
		// 	$table3_new->active = $active;
		// 	$table3_new->operator1 = Session::get('operator');
		// 	$table3_new->operator2;
		// 	$table3_new->save();
		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattress_phases");
		// }

		// reorder position of NOT_SET
		// $reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		// 		md.[id], md.[mattress_id], md.[mattress], md.[position], 
		// 		mp.[location], mp.[active]
		// 	 FROM [mattress_details] as md
		// 	 INNER JOIN [mattress_phases] as mp ON mp.[mattress_id] = md.[mattress_id] AND mp.[active] = 1
		// 	 WHERE location = 'NOT_SET' 
		// 	 ORDER BY position asc"));

		// if (isset($reorder_position[0])) {
		// 	for ($i=0; $i < count($reorder_position); $i++) { 

		// 		$table1 = mattress_details::findOrFail($reorder_position[$i]->id);
		// 		$table1->position = $i+1;
		// 		$table1->save();
		// 	}
		// }
		return Redirect::to('/plan_mattress/'.$location);
	}

	public function delete_mattress ($id) {

		return view('planner.mattress_delete_confirm', compact('id'));
	}
	public function delete_mattress_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];

		$user = User::find(Auth::id());
		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $operator1 = Auth::user()->name;
		} else {
			$msg = 'User is not autenticated';
			return view('planner.error',compact('msg'));
		}


		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			if ($user->is('admin')) {
				
				$operator = 'admin';
				Session::set('operator', $operator);
			} else {
				$msg ='Operator must be logged!';
				return view('planner.error',compact('msg', 'operator', 'operators'));	
			}
		}

		// Position check
		// $position = 1;
		$find_position_on_location = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					COUNT(location) as c 
				FROM [mattress_phases] WHERE location = 'DELETED' AND active = '1' "));
		// dd($find_position_on_location[0]);
		if (isset($find_position_on_location[0])) {
			$position = $find_position_on_location[0]->c + 1;
		} else {
			$position = 1;
		}
		// dd($position);

		$find_location = DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [mattress_phases] WHERE [mattress_id] = '".$id."' AND [active] = 1 "));
		$location_old = $find_location[0]->location;
		// dd($location_old);

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_details] WHERE [mattress_id] = '".$id."' "));
		// dd($find_details_id[0]->id);

		try {
			$table1 = mattress_details::findOrFail($find_details_id[0]->id);
			$table1->position = $position;
			$table1->layers_a = 0;
			$table1->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd("Problem to save in mattress_details");
		}
		// dd("STOP");
		// all mattress_phases for this mattress set to NOT ACTIVE
		
		$find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_phases] WHERE [mattress_id] = '".$id."' "));
		
		if (isset($find_all_mattress_phasses[0])) {
			// dd($find_all_mattress_phasses);
			for ($i=0; $i < count($find_all_mattress_phasses); $i++) { 
				try {
					$table3 = mattress_phases::findOrFail($find_all_mattress_phasses[$i]->id);
					$table3->active = 0;
					$table3->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("Problem to save in mattress_phases, set all to not active");
				}
			}	
		}

		// save new mattress_phases
		$status = "DELETED";
		$active = 1;
		// $operator1;
		$location = 'DELETED';
		$mattress = $table1->mattress;

		try {
			$table3_new = new mattress_phases;
			$table3_new->mattress_id = $id;
			$table3_new->mattress = $mattress;
			$table3_new->status = $status;
			$table3_new->location = $location;
			$table3_new->device;
			$table3_new->active = $active;
			$table3_new->operator1 = Session::get('operator');
			$table3_new->operator2;
			$table3_new->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd("Problem to save in mattress_phases");
		}

		// reorder position of NOT_SET
		$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				md.[id], md.[mattress_id], md.[mattress], md.[position], 
				mp.[location], mp.[active]
			 FROM [mattress_details] as md
			 INNER JOIN [mattress_phases] as mp ON mp.[mattress_id] = md.[mattress_id] AND mp.[active] = 1
			 WHERE location = '".$location_old."'
			 ORDER BY position asc"));

		if (isset($reorder_position[0])) {
			for ($i=0; $i < count($reorder_position); $i++) { 

				$table1 = mattress_details::findOrFail($reorder_position[$i]->id);
				$table1->position = $i+1;
				$table1->save();
			}
		}
		return Redirect::to('/plan_mattress/NOT_SET');
	}

// o_roll
	public function o_roll_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			*
		  FROM [o_rolls]
		  WHERE status = 'CREATED' "));	
		// dd($data);

		return view('planner.o_roll_table', compact('data'));
	}

	public function o_roll_table_all() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			*
		  FROM [o_rolls] "));	
		// dd($data);

		return view('planner.o_roll_table', compact('data'));
	}

	public function o_roll_delete ($id) {

		// dd($id);
		return view('planner.o_roll_delete_confirm', compact('id'));
	}

	public function o_roll_delete_confirm(Request $request) {

		$input = $request->all();
		$id = $input['id'];
		$data = o_roll::findOrFail($id);
		$data->delete();

		return Redirect::to('/o_roll_table');
	}

	public function o_roll_return ($id) {

		// dd($id);
		return view('planner.o_roll_return_confirm', compact('id'));
	}

	public function o_roll_return_confirm(Request $request) {

		$input = $request->all();
		// dd($input);
		$id = $input['id'];

		$data = o_roll::findOrFail($id);
		$data->mattress_id_new = NULL;
		$data->mattress_name_new = NULL;
		$data->status = 'CREATED';
		$data->save();

		// return Redirect::to('/');
		$success = "Roll successfuly returned";
  		return view('lr.index', compact('success'));
	}

	public function o_roll_scan() {

		return view('planner.o_roll_scan');
	}

	public function o_roll_scan_post(Request $request) {

		$input = $request->all();
		// dd($input);
		$roll = $input['roll'];

		$find_lr = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [o_rolls]
		  WHERE o_roll = '".$roll."' AND status = 'PLANNED' "));
		
		if (!isset($find_lr[0]->o_roll)) {
			
			$warning = 'LR roll not found or status of roll is different than PLANNED';
			return view('planner.o_roll_scan', compact('warning'));
		}

		$id = $find_lr[0]->id;
		return view('planner.o_roll_return_confirm', compact('id'));

	}

// mini_mattress
	public function plan_mini_marker() {

		$work_place = "PLANNER";
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		return view('planner.plan_mini_marker', compact('operator', 'operators'));
	}

	public function mini_marker_create() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
		 FROM o_rolls WHERE status = 'CREATED' order by g_bin asc"));
		// dd($data);
		return view('planner.mini_marker_create', compact('data'));
	}

	public function mini_marker_create_1(Request $request) {

		// $this->validate($request, ['selected_marker'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		if (!isset($input['items'])) {
			$warning = 'Choose LR roll';
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				 FROM o_rolls WHERE status = 'CREATED' order by g_bin asc"));
				// dd($data);
			return view('planner.mini_marker_create', compact('data','warning'));
		}
		$items[] = $input['items'];
		// dd($items[0][0]);

		$info = explode("#", $items[0][0]);
		// dd($info);

		$selected_o_roll = $info[0];
		$skeda = $info[1];

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
		FROM o_rolls WHERE status = 'CREATED' AND skeda = '".$skeda."' order by g_bin asc"));

		return view('planner.mini_marker_create_list', compact('data', 'selected_o_roll', 'skeda'));
	}

	public function mini_marker_create_2(Request $request) {

		// $this->validate($request, ['selected_marker'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$skeda = $input['skeda'];
		$selected_o_roll = $input['selected_o_roll'];

		if (!isset($input['items'])) {
			$items[] = null;
			array_push($items, $selected_o_roll);
		} else {
			$items = $input['items'];	
			array_push($items, $selected_o_roll);
		}
		
		// dd(array_filter($items));
		$items = array_filter($items);
		$items = serialize($items);
		// dd($items);

		
		$recap_table = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			distinct y.pro
			,y.style_size
			,y.po_sum_qty
			--,y.status
			,y.before_cut_planned
			,y.before_cut_actual
			,y.already_cut_planned
			,y.already_cut_actual
			,y.pro_pcs_planned_all
			,y.pro_pcs_actual_all
			FROM (
			SELECT 
			distinct x.pro
			,x.style_size
			,x.po_sum_qty
			--,x.location
			--,x.status
			,SUM (x.before_cut_planned) as before_cut_planned
			,SUM (x.before_cut_actual) as before_cut_actual
			,SUM (x.already_cut_planned) as already_cut_planned
			,SUM (x.already_cut_actual) as already_cut_actual
			,x.pro_pcs_planned_all
			,x.pro_pcs_actual_all
			FROM (
			SELECT 
			      p.[pro]
			      --,p.[skeda]
			      --,m.id
			      --,m.mattress
			      --,mpr.pro_id
			      ,mpr.style_size
			      
			      ,(SELECT SUM(qty) FROM [posummary].[dbo].[pro] as posum WHERE posum.skeda = LEFT(p.skeda,12) AND posum.pro = p.pro) as po_sum_qty
			      
			      --,(SELECT location FROM  [mattress_phases] as mp WHERE active = 1 AND mp.mattress_id = m.id) as location
			      --,(SELECT status FROM  [mattress_phases] as mp WHERE active = 1 AND mp.mattress_id = m.id) as status
			      
				  ,(SELECT SUM(pro_pcs_planned) FROM  [mattress_pros] as mpp 
					JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'NOT_SET') OR (mp.status = 'TO_LOAD') OR (mp.status = 'TO_SPREAD') OR (mp.status = 'TO_CUT') OR (mp.status = 'ON_HOLD') OR ( mp.status = 'ON_CUT') )
					WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as before_cut_planned
				  
				  ,(SELECT SUM(pro_pcs_actual) FROM  [mattress_pros] as mpp 
					JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'NOT_SET') OR (mp.status = 'TO_LOAD') OR (mp.status = 'TO_SPREAD') OR (mp.status = 'TO_CUT') OR (mp.status = 'ON_HOLD') OR ( mp.status = 'ON_CUT') )
					WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as before_cut_actual
					
					,(SELECT SUM(pro_pcs_planned) FROM  [mattress_pros] as mpp 
					JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'COMPLETED') OR (mp.status = 'TO_PACK') )
					WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as already_cut_planned
				  
				  ,(SELECT SUM(pro_pcs_actual) FROM  [mattress_pros] as mpp 
					JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'COMPLETED') OR (mp.status = 'TO_PACK') )
					WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as already_cut_actual
				  
				  ,(SELECT SUM(pro_pcs_planned) FROM  [mattress_pros] as mpp WHERE mpp.pro_id = p.pro_id) as pro_pcs_planned_all
				  ,(SELECT SUM(pro_pcs_actual) FROM  [mattress_pros] as mpp WHERE mpp.pro_id = p.pro_id) as pro_pcs_actual_all
			      
			  FROM  [mattresses] as m
			  JOIN  [mattress_pros] as mpr ON mpr.mattress_id = m.id
			  JOIN  [pro_skedas] as p ON p.skeda = m.skeda AND p.pro_id = mpr.pro_id
			  
			  WHERE p.[skeda] = '".$skeda."'
			) as x 

			GROUP BY x.pro, x.po_sum_qty, x.style_size, pro_pcs_planned_all, pro_pcs_actual_all

			) as y  "));
		// dd($recap_table);
		return view('planner.mini_marker_add_pro', compact('items','skeda','recap_table'));
	}

	public function mini_marker_add_pro(Request $request) {

		// $this->validate($request, ['pro'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$items = $input['items'];
		$skeda = $input['skeda'];

		if (!isset($input['pro'])) {
			// dd('nema pro');
			
			$recap_table = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				distinct y.pro
				,y.style_size
				,y.po_sum_qty
				--,y.status
				,y.before_cut_planned
				,y.before_cut_actual
				,y.already_cut_planned
				,y.already_cut_actual
				,y.pro_pcs_planned_all
				,y.pro_pcs_actual_all
				FROM (
				SELECT 
				distinct x.pro
				,x.style_size
				,x.po_sum_qty
				--,x.location
				--,x.status
				,SUM (x.before_cut_planned) as before_cut_planned
				,SUM (x.before_cut_actual) as before_cut_actual
				,SUM (x.already_cut_planned) as already_cut_planned
				,SUM (x.already_cut_actual) as already_cut_actual
				,x.pro_pcs_planned_all
				,x.pro_pcs_actual_all
				FROM (
				SELECT 
				      p.[pro]
				      --,p.[skeda]
				      --,m.id
				      --,m.mattress
				      --,mpr.pro_id
				      ,mpr.style_size
				      
				      ,(SELECT SUM(qty) FROM [posummary].[dbo].[pro] as posum WHERE posum.skeda = LEFT(p.skeda,12) AND posum.pro = p.pro) as po_sum_qty
				      
				      --,(SELECT location FROM  [mattress_phases] as mp WHERE active = 1 AND mp.mattress_id = m.id) as location
				      --,(SELECT status FROM  [mattress_phases] as mp WHERE active = 1 AND mp.mattress_id = m.id) as status
				      
					  ,(SELECT SUM(pro_pcs_planned) FROM  [mattress_pros] as mpp 
						JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'NOT_SET') OR (mp.status = 'TO_LOAD') OR (mp.status = 'TO_SPREAD') OR (mp.status = 'TO_CUT') OR (mp.status = 'ON_HOLD') OR ( mp.status = 'ON_CUT') )
						WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as before_cut_planned
					  
					  ,(SELECT SUM(pro_pcs_actual) FROM  [mattress_pros] as mpp 
						JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'NOT_SET') OR (mp.status = 'TO_LOAD') OR (mp.status = 'TO_SPREAD') OR (mp.status = 'TO_CUT') OR (mp.status = 'ON_HOLD') OR ( mp.status = 'ON_CUT') )
						WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as before_cut_actual
						
						,(SELECT SUM(pro_pcs_planned) FROM  [mattress_pros] as mpp 
						JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'COMPLETED') OR (mp.status = 'TO_PACK') )
						WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as already_cut_planned
					  
					  ,(SELECT SUM(pro_pcs_actual) FROM  [mattress_pros] as mpp 
						JOIN  [mattress_phases] as mp ON mp.active = 1 AND mp.mattress_id = m.id AND ((mp.status = 'COMPLETED') OR (mp.status = 'TO_PACK') )
						WHERE mpp.mattress_id = m.id AND mpp.pro_id = p.pro_id) as already_cut_actual
					  
					  ,(SELECT SUM(pro_pcs_planned) FROM  [mattress_pros] as mpp WHERE mpp.pro_id = p.pro_id) as pro_pcs_planned_all
					  ,(SELECT SUM(pro_pcs_actual) FROM  [mattress_pros] as mpp WHERE mpp.pro_id = p.pro_id) as pro_pcs_actual_all
				      
				  FROM  [mattresses] as m
				  JOIN  [mattress_pros] as mpr ON mpr.mattress_id = m.id
				  JOIN  [pro_skedas] as p ON p.skeda = m.skeda AND p.pro_id = mpr.pro_id
				  
				  WHERE p.[skeda] = '".$skeda."'
				) as x 

				GROUP BY x.pro, x.po_sum_qty, x.style_size, pro_pcs_planned_all, pro_pcs_actual_all

				) as y  "));
			// dd($recap_table);
			$warning = 'Please choose PRO';
			return view('planner.mini_marker_add_pro', compact('items','skeda','recap_table','warning'));

		} else {

			$info = explode("#", $input['pro']);
			// dd($info);
			$pro = $info[0];
			$po_sum_qty = (int)$info[1];
			$before_cut_actual = (int)$info[2];
			$already_cut_actual = (int)$info[3];
			// dd($pro);
		}

		// dd('ima pro');
		$pro_size = DB::connection('sqlsrv')->select(DB::raw("SELECT style_size
		 FROM [pro_skedas]
		 WHERE pro = '".$pro."' "));
		// dd($pro_size[0]->style_size);

		if (!isset($pro_size[0]->style_size)) {
			dd("Missing style and size in pro_skeda for that pro");
		}
		$style_size = $pro_size[0]->style_size;

		$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.[marker_name]
		 FROM [marker_headers] as mh
		 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
		 WHERE mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$style_size."' "));

		return view('planner.mini_marker_add_marker', compact('items','skeda','pro','style_size','markers','po_sum_qty','before_cut_actual','already_cut_actual'));
	}

	public function mini_marker_add_marker(Request $request) {

		// $this->validate($request, ['layer_limit'=>'required','marker'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$items = $input['items'];
		$skeda = $input['skeda'];
		// dd($skeda);
		$pro = $input['pro'];
		$style_size = $input['style_size'];
		// $po_sum_qty = (int)$input['po_sum_qty'];
		$po_sum_qty = 5000;
		$before_cut_actual = (int)$input['before_cut_actual'];
		$already_cut_actual = (int)$input['already_cut_actual'];
		
		$marker = $input['marker'];

		if ($marker == '') {
			$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.[marker_name]
			 FROM [marker_headers] as mh
			 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
			 WHERE mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$style_size."' "));

			$warning = 'Please select marker';
			return view('planner.mini_marker_add_marker', compact('items','skeda','pro','style_size','markers','po_sum_qty','before_cut_actual','already_cut_actual','warning'));
		}

		// dd($already_cut_actual);
		$layer_limit = $po_sum_qty - ($before_cut_actual + $already_cut_actual);
		if ($layer_limit < 0) {
			$layer_limit = 0;
		}
		// dd($layer_limit);

		$pc_per_layer_markers = DB::connection('sqlsrv')->select(DB::raw("SELECT ml.[pcs_on_layer]
			 FROM [marker_headers] as mh
			 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
			 WHERE ml.[marker_name] = '".$marker."' AND ml.[style_size] = '".$style_size."' "));

		$pc_per_layer = $pc_per_layer_markers[0]->pcs_on_layer;
		$layer_limit = round(ceil($layer_limit / $pc_per_layer),0);

		// dd($layer_limit);
		return view('planner.mini_marker_add_limit', compact('items','skeda','pro','style_size','marker','po_sum_qty','before_cut_actual','already_cut_actual','layer_limit', 'pc_per_layer'));
	}

	public function mini_marker_add_limit(Request $request) {

		// $this->validate($request, ['layer_limit'=>'required','marker'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$skeda = $input['skeda'];
		// dd($skeda);
		$pro = $input['pro'];
		$style_size = $input['style_size'];
		$po_sum_qty = $input['po_sum_qty'];
		$before_cut_actual = $input['before_cut_actual'];
		$already_cut_actual = $input['already_cut_actual'];

		$items = $input['items'];
		$marker = $input['marker'];
		
		if ($input['layer_limit'] == '' OR $input['layer_limit'] == 0) {
			// dd("fali");
			$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.[marker_name]
			 FROM [marker_headers] as mh
			 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
			 WHERE mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$style_size."' "));

			$warning = "Please insert layer limit";
			// dd($warning);
			$layer_limit = (int)$input['layer_limit'];			
			return view('planner.mini_marker_add_limit', compact('items','skeda','pro','style_size','marker','po_sum_qty','before_cut_actual','already_cut_actual','layer_limit','warning'));
		}
		// dd("ne fali");

		$layer_limit = (int)$input['layer_limit'];

		
		$items = unserialize($input['items']);
		// dd($items);

		// dd('stop');
		$mattress_last = DB::connection('sqlsrv')->select(DB::raw("SELECT 
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
		      ,m2.[printed_marker]
		      ,m2.[mattress_packed]
		      ,m2.[all_pro_for_main_plant]
		      ,m2.[bottom_paper]
		      ,m2.[layers_a_reasons]
		      ,m2.[comment_office]
		      ,m2.[comment_operator]
		      ,m2.[minimattress_code]
		      ,m2.[layer_limit]
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
		      ,m5.[style_size]
		      ,m5.[pro_id]
		      ,m5.[pro_pcs_layer]
		      ,m5.[pro_pcs_planned]
		      ,m5.[pro_pcs_actual]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  JOIN [o_rolls] as o ON o.[mattress_id_orig] = m1.[id]
		  WHERE o.[o_roll] = '".end($items)."' AND m4.active = '1' 
		  ORDER BY m2.position asc"));
		// dd($mattress_last);

		$last_mm_used = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 mattress FROM  [mattresses]
  				WHERE skeda_item_type = 'MM' 
  				ORDER BY id desc"));
		
		if (!isset($last_mm_used[0])) {
			$last_mm_used = 0;
		} else {
			$last_mm_used = (int)substr($last_mm_used[0]->mattress, -2);
		}

		$last_mm_used = str_pad($last_mm_used+1, 2, 0, STR_PAD_LEFT);
		// dd($last_mm_used);

		$mattress = $mattress_last[0]->skeda."-MM-".$last_mm_used;
		// dd($mattress);
		$g_bin = "";
		$material = $mattress_last[0]->material;
		$dye_lot = "O_0";
		$color_desc = $mattress_last[0]->color_desc;
		$width_theor_usable = $mattress_last[0]->width_theor_usable;
		$skeda = $mattress_last[0]->skeda;
		$skeda_item_type = "MM";
		$skeda_status = $mattress_last[0]->skeda_status;
		$spreading_method = $mattress_last[0]->spreading_method;
		
		// $marker_id = $mattress_last[0]->marker_id;
		// $marker_name = $mattress_last[0]->marker_name;
		$marker_name_orig = $mattress_last[0]->marker_name_orig;
		// $marker_length = $mattress_last[0]->marker_length;
		// $marker_width = $mattress_last[0]->marker_width;
		// $min_length = $mattress_last[0]->min_length;

		$layers = 0;
		$layers_a = 0;
		$length_mattress = 0;
		$cons_planned = 0;
		$cons_actual = 0;
		$extra = 0.01;
		$pcs_bundle = 0;
		$layers_partial;
		$priority = 1;
		$call_shift_manager = 0;
		$test_marker = 0;
		$tpp_mat_keep_wastage = 0;
		$printed_marker = 0;
		$mattress_packed = 0;
		$all_pro_for_main_plant = 0; //????????????

		// Position check
		// $position = 1;
		$find_position_on_location = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					COUNT(location) as c 
				FROM [mattress_phases] WHERE location = 'NOT_SET' AND active = '1' "));
		// dd($find_position_on_location[0]);
		if (isset($find_position_on_location[0])) {
			$position = $find_position_on_location[0]->c + 1;
		} else {
			$position = 1;
		}

		$marker_name = $input['marker'];
		// dd($marker);
		// dd(end($items));

		$marker_new = DB::connection('sqlsrv')->select(DB::raw("SELECT id, marker_name, marker_length, marker_width, min_length
			FROM marker_headers
			WHERE marker_name = '".$marker_name."' "));

		$marker_id = $marker_new[0]->id;
		$marker_name = $marker_new[0]->marker_name;
		$marker_length = $marker_new[0]->marker_length;
		$marker_width = $marker_new[0]->marker_width;
		$min_length = $marker_new[0]->min_length;

		$find_in_mattress_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM mattress_pros WHERE mattress = '".$mattress."' "));
		if (isset($find_in_mattress_pro[0])) {				   			
				
   				$msg = "Mattress '".$mattress."' already exist in mattress_pros!";
   				dd($msg);
				
			} else {

				$find_in_marker_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT style_size, pcs_on_layer FROM marker_lines WHERE marker_name = '".$marker_name."' "));
				
	   			$mattress_pro_array[] = '';
		   		foreach ($find_in_marker_lines as $line) {
		   			$style_size = $line->style_size;
		   			$pro_pcs_layer = $line->pcs_on_layer;

		   			if (isset($skeda)) {
			   				
			   			$find_in_pro_skedas = DB::connection('sqlsrv')->select(DB::raw("SELECT pro_id FROM pro_skedas WHERE skeda = '".$skeda."' AND style_size = '".$style_size."' "));
			   			if (!isset($find_in_pro_skedas[0])) {
			   				$msg = "Skeda '".$skeda."' with style '".$style_size."' not exist in pro_skedas table";
			   				dd($msg);

			   			} else {
			   				// dd($find_in_pro_skedas);
				   			$pro_id = $find_in_pro_skedas[0]->pro_id;
				   			// print_r('insert:'.$pro_id.'#'.$style_size.'#'.$pro_pcs_layer);
				   			// print_r('<br>');
				   			array_push($mattress_pro_array, $pro_id.'#'.$style_size.'#'.$pro_pcs_layer);
			   			}
			   		} else {
			   			$msg = "Skeda not exist";
		   				dd($msg);
		   			}
		   		}
	   		}

		// try {
			$table0 = new mattress;
			$table0->mattress = $mattress;
			$table0->g_bin = $g_bin;
			$table0->material = $material;
			$table0->dye_lot = $dye_lot;
			$table0->color_desc = $color_desc;
			$table0->width_theor_usable = $width_theor_usable;
			$table0->skeda = $skeda;
			$table0->skeda_item_type = $skeda_item_type;
			$table0->skeda_status = $skeda_status;
			$table0->spreading_method = $spreading_method;
			$table0->save();
		// }
		// 	catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattresses");
		// }

		// try {
			$table1 = new mattress_details;
			$table1->mattress_id = $table0->id;
			$table1->mattress = $table0->mattress;
			$table1->layers = $layers;
			$table1->layers_a = $layers_a;
			$table1->length_mattress = $length_mattress;
			$table1->cons_planned = $cons_planned;
			$table1->cons_actual = $cons_actual;
			$table1->extra = $extra;
			$table1->pcs_bundle = $pcs_bundle;
			$table1->layers_partial;
			$table1->position = $position;
			$table1->priority = $priority;
			$table1->call_shift_manager = $call_shift_manager;
			$table1->test_marker = $test_marker;
			$table1->tpp_mat_keep_wastage = $tpp_mat_keep_wastage;
			$table1->printed_marker = $printed_marker;
			$table1->mattress_packed = $mattress_packed;
			$table1->all_pro_for_main_plant = $all_pro_for_main_plant;
			$table1->bottom_paper;
			$table1->layers_a_reasons;
			$table1->comment_office;
			$table1->comment_operator;
			$table1->minimattress_code; // ????????????????????????
			$table1->layer_limit = $layer_limit; // new
			$table1->save();

		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattress_details");
		// }

		// try {
			$table2 = new mattress_markers;
			$table2->mattress_id = $table0->id;
			$table2->mattress = $table0->mattress;
			$table2->marker_id = $marker_id;
			$table2->marker_name = $marker_name;
			$table2->marker_name_orig = $marker_name_orig;
			$table2->marker_length = $marker_length;
			$table2->marker_width = $marker_width;
			$table2->min_length = $min_length;
			$table2->save();
		// }
		// 	catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattress_markers");
		// }

		$status = 'NOT_SET';
		$location = 'NOT_SET';
		$device = '';
		$active = 1;

		// try {
			$table3 = new mattress_phases;
			$table3->mattress_id = $table0->id;
			$table3->mattress = $table0->mattress;
			$table3->status = $status;
			$table3->location = $location;
			$table3->device;
			$table3->active = $active;
			$table3->operator1 = Session::get('operator');
			$table3->operator2;
			$table3->save();
		// }
		// 	catch (\Illuminate\Database\QueryException $e) {
		// 	dd("Problem to save in mattress_phases");
		// }
		// 

   		// dd($mattress_pro_array);
   		$mattress_pro_array = array_filter($mattress_pro_array);
		for ($i=1; $i <= count($mattress_pro_array) ; $i++) {
	
			$info = explode("#", $mattress_pro_array[$i]);
			// dd($info[0]);

			// try {
				$table4 = new mattress_pro;
				$table4->mattress_id = $table0->id;
				$table4->mattress = $table0->mattress;
				$table4->style_size = $info[1];
				$table4->pro_id = $info[0];
				$table4->pro_pcs_layer   = (float)$info[2];
				$table4->pro_pcs_planned = 0;
				$table4->pro_pcs_actual  = 0;
				$table4->save();
			// }
			// catch (\Illuminate\Database\QueryException $e) {
			// 	dd("Problem to save in mattress_pros");
			// }
		}
		$mattress_pro_array = '';

		// dd($items);
		foreach ($items as $value) {
			// dd($value);
			$find_used_rolls = DB::connection('sqlsrv')->select(DB::raw("SELECT id, mattress_id_orig FROM o_rolls WHERE [o_roll] = '".$value."' "));

			$table5 = o_roll::findOrFail($find_used_rolls[0]->id);
			$table5->mattress_id_new = $table0->id;
			$table5->mattress_name_new = $table0->mattress;
			$table5->status = "PLANNED";
			$table5->save();

			$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT d.[id]
				FROM [mattress_details] as d
				JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
				WHERE m.[id] = '".$find_used_rolls[0]->mattress_id_orig."' "));

			$table6 = mattress_details::findOrFail($find_details_id[0]->id);
			$table6->minimattress_code = $table0->id;
			$table6->save();
		}

		return redirect('/');
	}

// paspul
	public function plan_paspul($location) {
		
		$operator = Session::get('operator');
		
		$work_place = "PLANNER";
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$user = User::find(Auth::id());

		// dd($operator);
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');

			if ($user->is('admin')) {
				
				$operator = 'admin';
				Session::set('operator', $operator);
			} else {
				$msg ='Operator must be logged!';
				return view('planner.error',compact('msg', 'operator', 'operators'));	
			}
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
		      ,p1.[tpa_number]
		      ,p1.[created_at]
		      ,p1.[updated_at]

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

		$pros= '';
		$skus= '';
		$sku_s= '';
		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			
			$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				ps.pro
				,ps.style_size
				,ps.sku
				--,*
			  FROM  [pro_skedas] as ps 
			WHERE ps.[skeda] = '".$data[$i]->skeda."' "));
			// dd($prom);

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

		return view('planner.plan_paspul', compact('data','location','operator','operators'));
	}

	public function plan_paspul_line ($id) {

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
		$call_shift_manager = $data[0]->call_shift_manager;
		$rewinding_method = $data[0]->rewinding_method;

		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$paspul_roll_id = $data[0]->paspul_roll_id;
		$tpa_number = $data[0]->tpa_number;

		$skeda_to_find = substr($skeda, 0, 12);
		// dd($skeda_to_find);

		// $pasbin = 'PAS0000001'; //auto assign ???????????????????????
		$pas_bin_find = DB::connection('sqlsrv')->select(DB::raw("SELECT pas_bin , adez_bin 
		FROM paspul_bins
		WHERE skeda like '".$skeda_to_find."%'
		ORDER BY id desc"));
		// dd($pas_bin_find);

		if (isset($pas_bin_find[0])) {
			$pas_bin = $pas_bin_find[0]->pas_bin;
			$adez_bin = $pas_bin_find[0]->adez_bin;
		} else {
			$pas_bin = '';
			$adez_bin = '';
		}

		if ($skeda_item_type == 'PA') {
			if (($pas_bin == '') AND ($adez_bin == '')) {
				// dd("pas_bin is empty for this skeda");
				$msg = 'Can not find paspul bin or adez bin for this skeda and skeda_type PA , please insert it in paspul_bin table!';
				return view('planner.error',compact('msg'));
			}	

			$bin[]='';
			if ($pas_bin != '') {
				array_push($bin, $pas_bin);	
			}
			if ($adez_bin != '') {
				array_push($bin, $adez_bin);	
			}
			// dd(array_filter($bin));
			$bin = array_filter($bin);
			$bin = array_values($bin);

		} else {
			$bin[] = '';
		}
		
		return view('planner.plan_paspul_line', compact( 'id','paspul_roll','pasbin','skeda_item_type','priority','comment_office','call_shift_manager','rewinding_method',
			'material','dye_lot','color_desc','skeda', 'skeda_item_type','paspul_roll_id', 'tpa_number',
			'bin'));
	}	

	public function plan_paspul_line_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$paspul_roll = $input['paspul_roll'];
		$priority = $input['priority'];
		$comment_office = $input['comment_office'];
		$paspul_roll_id = $input['paspul_roll_id'];
		$skeda = $input['skeda'];
		$skeda_item_type = $input['skeda_item_type'];
		// dd($skeda);

		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('planner.error',compact('msg'));
		}

		if ($skeda_item_type == "PA") {
			if (isset($input['bin'])) {
				$pas_bin = $input['bin'];
			}else if (isset($input['bins'])) {
				$pas_bin = $input['bins'][0];
			} else {
				$msg ='Pas bin must be selected!';
				return view('planner.error',compact('msg'));
			}	
		} else {
			$pas_bin = '';
		}
		
		// dd($pas_bin);

		if (isset($input['call_shift_manager'])) {
			$call_shift_manager = (int)$input['call_shift_manager'];
		} else {
			$call_shift_manager = 0;
		}
		$location = "PRW";

		// $check_paspul_roll = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM paspuls WHERE paspul_roll = '".$paspul_roll."' "));
  		// if (isset($check_paspul_roll[0])) {
  		// 		dd("Paspul_roll already exist in paspul table");
  		// }

		// $sap_su = $row['sap_su'];
		// $material = $row['material'];
		// $color_desc = strtoupper($row['color_desc']);
		// $dye_lot = $row['dye_lot'];
		// $paspul_type = $row['paspul_type'];

		// $kotur_width = (float)$row['kotur_width'];
		// $kotur_width_without_tension = (float)$row['kotur_width_without_tension'];
		// $koturi_planned = (float)$row['koturi_planned'];
		// $kotur_actual;	// koturi_planned
		// $rewound_length = (float)$row['rewound_length'];
		// $rewound_length_a = $rewound_length; // rewound_length

		// $width = (float)$kotur_width*(float)$koturi_planned; // ?

		// $skeda_item_type = $row['skeda_item_type'];
		// $skeda = $row['skeda'];
		// $skeda_status = 'OPEN'; // OPEN/CLOSED???????????????????????
		// if ($skeda_item_type == 'PA') {
		// 	$rewound_roll_unit_of_measure = 'meter';
		// } else {
		// 	$rewound_roll_unit_of_measure = 'ploce';
		// }

		//$position = 0; // auto ??????????????????????????????????????
		$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
		FROM 
		(
		SELECT position 
		FROM  [paspuls] as p
		JOIN  [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND active = '1'
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
		$status = 'TO_REWIND';
		$location = 'PRW'; //PRW or NOT_SET //????????????????
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
			// $table->rewound_length_a = $rewound_length_a;

			$table->pasbin = $pas_bin;
			// $table->skeda_item_type = $skeda_item_type;
			// $table->skeda = $skeda;
			// $table->skeda_status = $skeda_status;
			// $table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
			$table->position = $position;
			$table->priority = $priority;
			$table->comment_office = $comment_office;
			// $table->comment_operator = $comment_operator;
			$table->call_shift_manager = $call_shift_manager;
			// $table->rewinding_method = $rewinding_method;
			$table->save();

			// reorder position of NOT_SET
			$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					p.[id], p.[paspul_roll], p.[position], 
					pl.[location], pl.[active], pl.[paspul_roll_id]
				 FROM [paspuls] as p
				 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
				 WHERE pl.[location] = 'NOT_SET'
				 ORDER BY p.[position] asc"));
			// dd($reorder_position);

			if (isset($reorder_position[0])) {
				for ($i=0; $i < count($reorder_position); $i++) { 

					$table1 = paspul::findOrFail($reorder_position[$i]->id);
					$table1->position = $i+1;
					$table1->save();
				}
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
			$table_p->save();

		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd('Problem to save');
		// }

		return redirect('/plan_paspul/NOT_SET');
	}

	public function remove_paspul_line($id) {
		// dd($id);

		return view('planner.paspul_delete_confirm', compact( 'id'));
	}

	public function paspul_delete_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		// dd($id);

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('planner.error',compact('msg'));
		}

		// new position
		$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
		FROM 
		(
		SELECT position 
		FROM  [paspuls] as p
		JOIN  [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND active = '1'
		WHERE pl.[location] = 'DELETED'
		) SQ
		ORDER BY position desc"));
		// dd($position[0]->position);

		if (isset($position[0]->position)) {
			$position = $position[0]->position;
		} else {
			$position = 0;
		}
		// dd($position);
		// dd($id);

		// set all to 0
		$find_all_papul_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				[id], [paspul_roll], [paspul_roll_id], [location]
			FROM [paspul_lines] WHERE [paspul_roll_id] = '".$id."' AND active = 1"));
		// dd($find_all_papul_lines);

			if (isset($find_all_papul_lines[0])) {
				$paspul_roll = $find_all_papul_lines[0]->paspul_roll;
				$location = $find_all_papul_lines[0]->location;
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
			// $table->rewound_length_a = $rewound_length_a;
			// $table->pasbin = $pas_bin;
			// $table->skeda_item_type = $skeda_item_type;
			// $table->skeda = $skeda;
			// $table->skeda_status = $skeda_status;
			// $table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
			$table->position = $position+1;
			// $table->priority = $priority;
			// $table->comment_office = $comment_office;
			// $table->comment_operator = $comment_operator;
			// $table->call_shift_manager = $call_shift_manager;
			// $table->rewinding_method = $rewinding_method;
			$table->save();

			// reorder position of location
			$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					p.[id], p.[paspul_roll], p.[position], 
					pl.[location], pl.[active], pl.[paspul_roll_id]
				 FROM [paspuls] as p
				 INNER JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND pl.[active] = 1
				 WHERE pl.[location] = '".$location."'
				 ORDER BY p.[position] asc"));
			// dd($reorder_position);

			if (isset($reorder_position[0])) {
				for ($i=0; $i < count($reorder_position); $i++) { 

					$table1 = paspul::findOrFail($reorder_position[$i]->id);
					$table1->position = $i+1;
					$table1->save();
				}
			}

			$status = "DELETED";
			$location = "DELETED";
			$active = 1;

			$table_p = new paspul_line;

			$table_p->paspul_roll_id = $table->id;
			$table_p->paspul_roll = $table->paspul_roll;
			$table_p->status = $status;
			$table_p->location = $location;
			$table_p->device = $device;
			$table_p->active = $active;
			$table_p->operator1 = Session::get('operator');
			$table_p->operator2;
			$table_p->save();
	
		return redirect('/plan_paspul/NOT_SET');
	}

	public function edit_paspul ($id) {

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
		$call_shift_manager = $data[0]->call_shift_manager;
		$rewinding_method = $data[0]->rewinding_method;

		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$paspul_roll_id = $data[0]->paspul_roll_id;
		$tpa_number = $data[0]->tpa_number;
		$location = $data[0]->location;

		return view('planner.edit_paspul_line', compact( 'id','paspul_roll','pasbin','skeda_item_type','priority','comment_office','call_shift_manager','rewinding_method',
			'material','dye_lot','color_desc','skeda', 'skeda_item_type','tpa_number','paspul_roll_id','location'));
	}	

	public function edit_paspul_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$paspul_roll = $input['paspul_roll'];
		$priority = $input['priority'];
		$comment_office = $input['comment_office'];
		$paspul_roll_id = $input['paspul_roll_id'];
		$skeda = $input['skeda'];
		$skeda_item_type = $input['skeda_item_type'];
		$location = $input['location'];
		// dd($skeda);

		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('planner.error',compact('msg'));
		}

		// dd($pas_bin);

		if (isset($input['call_shift_manager'])) {
			$call_shift_manager = (int)$input['call_shift_manager'];
		} else {
			$call_shift_manager = 0;
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
		// $table->rewound_length_a = $rewound_length_a;

		// $table->pasbin = $pas_bin;
		// $table->skeda_item_type = $skeda_item_type;
		// $table->skeda = $skeda;
		// $table->skeda_status = $skeda_status;
		// $table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
		// $table->position = $position;
		$table->priority = $priority;
		$table->comment_office = $comment_office;
		// $table->comment_operator = $comment_operator;
		$table->call_shift_manager = $call_shift_manager;
		// $table->rewinding_method = $rewinding_method;
		$table->save();

		return redirect('/plan_paspul/'.$location);
	}

// printing
	public function print_mattress ($id) {

		return view('planner.print_mattress_confirm', compact( 'id'));
	}
	public function print_mattress_confirm (Request $request) {

		$this->validate($request, ['id'=>'required', 'printer' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$printer = $input['printer'];

		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[g_bin]
		      ,m1.[material]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[width_theor_usable]
		      ,m1.[skeda]
		      ,m1.[skeda_item_type]
		      ,m1.[spreading_method]
		      --,'|'
		      ,m2.[layers]
		      ,m2.[pcs_bundle]
		      ,m2.[bottom_paper]
		      ,m2.[comment_office]
		      ,m2.[overlapping]
		      ,m2.[tpp_mat_keep_wastage]
		      --,'|'
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      --,'|'
		      ,m4.[location]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
		  WHERE m1.[id] = '".$id."' "));
		// dd($data);
	
		$id = $data[0]->id;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;
		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$width_theor_usable = round($data[0]->width_theor_usable,3);
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$spreading_method = $data[0]->spreading_method;
		
		$layers = round($data[0]->layers,0);
		$pcs_bundle = round($data[0]->pcs_bundle,0);
		$bottom_paper = $data[0]->bottom_paper;
		$comment_office = $data[0]->comment_office;

		$marker_name = $data[0]->marker_name;
		$marker_length = round($data[0]->marker_length,3);
		$marker_width = round($data[0]->marker_width,3);

		$location = $data[0]->location;
		$overlapping = $data[0]->overlapping;

		$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;

		if ($tpp_mat_keep_wastage == 1) {
			$tpp_mat_keep_wastage = "YES";
		} else {
			$tpp_mat_keep_wastage = "NO";
		}

		if (($skeda_item_type == 'MS') OR ($skeda_item_type == 'MM')) {
		// MS or MM

				$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				      mp.[mattress]
				      ,mp.[style_size]
				      --,mp.[pro_id]
				      ,mp.[pro_pcs_layer]
				      --,mp.[pro_pcs_planned]
				      ,mp.[pro_pcs_actual]
				      ,s.[padprint_item]
				      ,s.[padprint_color]
				      ,s.[pro]
				      ,s.[sku]
				      ,s.[multimaterial]
				      ,po.[location_all]
				      
				FROM  [mattress_pros] as mp
				JOIN  [pro_skedas] as s ON s.[pro_id] = mp.[pro_id]
				LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
				WHERE mp.[mattress_id] = '".$id."' "));
				// dd($data1);

				$padprint_item = $data1[0]->padprint_item;
				$padprint_color = $data1[0]->padprint_color;

				for ($i=0; $i < count($data1); $i++) { 
					// $id = $i + 1;
					// $pro."_".$i = $data1[$i]->pro;
					if (isset($data1[$i]->pro)) {
						${"pro_{$i}"}=$data1[$i]->pro;	
					} else {
						${"pro_{$i}"}='';
					}

					if (isset($data1[$i]->sku)) {
						${"style_size_{$i}"}=$data1[$i]->sku;
					} else {
						${"style_size_{$i}"}='';
					}

					if (isset($data1[$i]->pro_pcs_actual)) {
						${"pro_pcs_actual_{$i}"}=$data1[$i]->pro_pcs_actual;
					} else {
						${"pro_pcs_actual_{$i}"}='';
					}

					if (isset($data1[$i]->pro_pcs_layer)) {
						${"pro_pcs_layer_{$i}"}=round($data1[$i]->pro_pcs_layer,0);
					} else {
						${"pro_pcs_layer_{$i}"}='';
					}
					
					if (isset($data1[$i]->location_all)) {
						${"destination_{$i}"}=$data1[$i]->location_all;
					} else {
						${"destination_{$i}"}='';
					}

					if (isset($data1[$i]->multimaterial)) {
						${"multimaterial_{$i}"}=$data1[$i]->multimaterial;
					} else {
						${"multimaterial_{$i}"}='';
					}
				}

				for ($i=0; $i <= 14; $i++) { 
					if (!isset(${"pro_{$i}"})) {
						${"pro_{$i}"}='';
						${"style_size_{$i}"}='';
						${"pro_pcs_actual_{$i}"}='';
						${"pro_pcs_layer_{$i}"}='';
						${"destination_{$i}"}='';
						${"multimaterial_{$i}"}='';
					}	
					// var_dump(${"pro_{$i}"});
				}
		} else {
		// MB or MW

			$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			      m.[mattress]
			      --,m.[skeda]
			      --,s.[pro_id]
			      ,s.[style_size]
			      ,mp.[pro_pcs_layer]
			      --,mp.[pro_pcs_planned]
			      ,mp.[pro_pcs_actual]
			      ,s.[padprint_item]
			      ,s.[padprint_color]
			      ,s.[pro]
			      ,s.[sku]
			      ,s.[multimaterial]
			      ,po.[location_all]
			      
			FROM  [mattresses] as m
			JOIN  [pro_skedas] as s ON s.[skeda] = m.[skeda]
			LEFT JOIN  [mattress_pros] as mp ON mp.[pro_id] = s.[pro_id] AND mp.[mattress_id] = m.[id]
			LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
			WHERE m.[id] = '".$id."' "));
			// dd($data1);

			$padprint_item = $data1[0]->padprint_item;
			$padprint_color = $data1[0]->padprint_color;

			for ($i=0; $i < count($data1); $i++) { 
				// $id = $i + 1;
				// $pro."_".$i = $data1[$i]->pro;
				if (isset($data1[$i]->pro)) {
					${"pro_{$i}"}=$data1[$i]->pro;	
				} else {
					${"pro_{$i}"}='';
				}

				if (isset($data1[$i]->sku)) {
					${"style_size_{$i}"}=$data1[$i]->sku;
				} else {
					${"style_size_{$i}"}='';
				}

				if (isset($data1[$i]->pro_pcs_actual)) {
					${"pro_pcs_actual_{$i}"}=$data1[$i]->pro_pcs_actual;
				} else {
					${"pro_pcs_actual_{$i}"}='';
				}

				if (isset($data1[$i]->pro_pcs_layer)) {
						${"pro_pcs_layer_{$i}"}=round($data1[$i]->pro_pcs_layer,0);
				} else {
					${"pro_pcs_layer_{$i}"}='';
				}
				
				if (isset($data1[$i]->location_all)) {
					${"destination_{$i}"}=$data1[$i]->location_all;
				} else {
					${"destination_{$i}"}='';
				}

				if (isset($data1[$i]->multimaterial)) {
					${"multimaterial_{$i}"}=$data1[$i]->multimaterial;
				} else {
					${"multimaterial_{$i}"}='';
				}
			}

			for ($i=0; $i <= 14; $i++) { 
				if (!isset(${"pro_{$i}"})) {
					${"pro_{$i}"}='';
					${"style_size_{$i}"}='';
					${"pro_pcs_actual_{$i}"}='';
					${"pro_pcs_layer_{$i}"}='';
					${"destination_{$i}"}='';
					${"multimaterial_{$i}"}='';
				}	
				// var_dump(${"pro_{$i}"});
			}
		}

		$table = new print_standard_mattress;

		$table->mattress = $mattress;
		$table->marker_name = $marker_name;
		$table->g_bin = $g_bin;
		$table->location = $location;
		$table->skeda = $skeda;

		$table->overlapping = $overlapping;
		$table->width_theor_usable = $width_theor_usable;
		$table->marker_length = $marker_length;
		if ($marker_width == 0) {
			$marker_width = $width_theor_usable;
		} 
		$table->marker_width = $marker_width;
		$table->spreading_method = $spreading_method;
		$table->material = $material;
		$table->color_desc = $color_desc;
		$table->dye_lot = $dye_lot;
		$table->pcs_bundle = $pcs_bundle;
		$table->bottom_paper = $bottom_paper;
		$table->tpp_mat_keep_wastage = $tpp_mat_keep_wastage;

		$table->layers = $layers;
		$table->padprint_item = $padprint_item;
		$table->padprint_color = $padprint_color;
		$table->comment_office = $comment_office;

		$table->comment_office = $comment_office;
		$table->printer = $printer;

		$table->pro_0 = $pro_0;
		$table->style_size_0 = $style_size_0;
		$table->pro_pcs_actual_0 = round($pro_pcs_actual_0,0);
		$table->destination_0 = $destination_0;
		$table->pro_pcs_layer_0 = $pro_pcs_layer_0;
		$table->multimaterial_0 = $multimaterial_0;

		$table->pro_1 = $pro_1;
		$table->style_size_1 = $style_size_1;
		$table->pro_pcs_actual_1 = round($pro_pcs_actual_1,0);
		$table->destination_1 = $destination_1;
		$table->pro_pcs_layer_1 = $pro_pcs_layer_1;
		$table->multimaterial_1 = $multimaterial_1;

		$table->pro_2 = $pro_2;
		$table->style_size_2 = $style_size_2;
		$table->pro_pcs_actual_2 = round($pro_pcs_actual_2,0);
		$table->destination_2 = $destination_2;
		$table->pro_pcs_layer_2 = $pro_pcs_layer_2;
		$table->multimaterial_2 = $multimaterial_2;

		$table->pro_3 = $pro_3;
		$table->style_size_3 = $style_size_3;
		$table->pro_pcs_actual_3 = round($pro_pcs_actual_3,0);
		$table->destination_3 = $destination_3;
		$table->pro_pcs_layer_3 = $pro_pcs_layer_3;
		$table->multimaterial_3 = $multimaterial_3;

		$table->pro_4 = $pro_4;
		$table->style_size_4 = $style_size_4;
		$table->pro_pcs_actual_4 = round($pro_pcs_actual_4,0);
		$table->destination_4 = $destination_4;
		$table->pro_pcs_layer_4 = $pro_pcs_layer_4;
		$table->multimaterial_4 = $multimaterial_4;

		$table->pro_5 = $pro_5;
		$table->style_size_5 = $style_size_5;
		$table->pro_pcs_actual_5 = round($pro_pcs_actual_5,0);
		$table->destination_5 = $destination_5;
		$table->pro_pcs_layer_5 = $pro_pcs_layer_5;
		$table->multimaterial_5 = $multimaterial_5;

		$table->pro_6 = $pro_6;
		$table->style_size_6 = $style_size_6;
		$table->pro_pcs_actual_6 = round($pro_pcs_actual_6,0);
		$table->destination_6 = $destination_6;
		$table->pro_pcs_layer_6 = $pro_pcs_layer_6;
		$table->multimaterial_6 = $multimaterial_6;

		$table->pro_7 = $pro_7;
		$table->style_size_7 = $style_size_7;
		$table->pro_pcs_actual_7 = round($pro_pcs_actual_7,0);
		$table->destination_7 = $destination_7;
		$table->pro_pcs_layer_7 = $pro_pcs_layer_7;
		$table->multimaterial_7 = $multimaterial_7;

		$table->pro_8 = $pro_8;
		$table->style_size_8 = $style_size_8;
		$table->pro_pcs_actual_8 = round($pro_pcs_actual_8,0);
		$table->destination_8 = $destination_8;
		$table->pro_pcs_layer_8 = $pro_pcs_layer_8;
		$table->multimaterial_8 = $multimaterial_8;

		$table->pro_9 = $pro_9;
		$table->style_size_9 = $style_size_9;
		$table->pro_pcs_actual_9 = round($pro_pcs_actual_9,0);
		$table->destination_9 = $destination_9;
		$table->pro_pcs_layer_9 = $pro_pcs_layer_9;
		$table->multimaterial_9 = $multimaterial_9;

		$table->pro_10 = $pro_10;
		$table->style_size_10 = $style_size_10;
		$table->pro_pcs_actual_10 = round($pro_pcs_actual_10,0);
		$table->destination_10 = $destination_10;
		$table->pro_pcs_layer_10 = $pro_pcs_layer_10;
		$table->multimaterial_10 = $multimaterial_10;

		$table->pro_11 = $pro_11;
		$table->style_size_11 = $style_size_11;
		$table->pro_pcs_actual_11 = round($pro_pcs_actual_11,0);
		$table->destination_11 = $destination_11;
		$table->pro_pcs_layer_11 = $pro_pcs_layer_11;
		$table->multimaterial_11 = $multimaterial_11;

		$table->pro_12 = $pro_12;
		$table->style_size_12 = $style_size_12;
		$table->pro_pcs_actual_12 = round($pro_pcs_actual_12,0);
		$table->destination_12 = $destination_12;
		$table->pro_pcs_layer_12 = $pro_pcs_layer_12;
		$table->multimaterial_12 = $multimaterial_12;

		$table->pro_13 = $pro_13;
		$table->style_size_13 = $style_size_13;
		$table->pro_pcs_actual_13 = round($pro_pcs_actual_13,0);
		$table->destination_13 = $destination_13;
		$table->pro_pcs_layer_13 = $pro_pcs_layer_13;
		$table->multimaterial_13 = $multimaterial_13;

		$table->pro_14 = $pro_14;
		$table->style_size_14 = $style_size_14;
		$table->pro_pcs_actual_14 = round($pro_pcs_actual_14,0);
		$table->destination_14 = $destination_14;
		$table->pro_pcs_layer_14 = $pro_pcs_layer_14;
		$table->multimaterial_14 = $multimaterial_14;

		$table->save();

		$table1 = mattress_details::findOrFail($id);
		$table1->printed_nalog = (int)$table1->printed_nalog + 1;
		$table1->save();

		return redirect('/');
	}

	public function print_mattress_m ($id) {

		return view('planner.print_mattress_confirm_m', compact( 'id'));
	}

	public function print_mattress_confirm_m (Request $request) {

		$this->validate($request, ['id'=>'required', 'printer' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$printer = $input['printer'];

		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[g_bin]
		      ,m1.[material]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[width_theor_usable]
		      ,m1.[skeda]
		      ,m1.[skeda_item_type]
		      ,m1.[spreading_method]
		      --,'|'
		      ,m2.[layers]
		      ,m2.[pcs_bundle]
		      ,m2.[bottom_paper]
		      ,m2.[comment_office]
		      ,m2.[overlapping]
		      ,m2.[tpp_mat_keep_wastage]
		      ,m2.[printed_nalog]
		      ,m2.[layer_limit]
		      --,'|'
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m3.[min_length]
		      --,'|'
		      ,m4.[location]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
		  WHERE m1.[id] = '".$id."' "));
		// dd($data);
	
		$id = $data[0]->id;
		$mattress = $data[0]->mattress;
		$material = $data[0]->material;
		$dye_lot = '0-O';
		$color_desc = $data[0]->color_desc;
		$width_theor_usable = round($data[0]->width_theor_usable,3);
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$spreading_method = $data[0]->spreading_method;
		
		// $layers = round($data[0]->layers,0);
		$pcs_bundle = round($data[0]->pcs_bundle,0);
		$bottom_paper = $data[0]->bottom_paper;
		$comment_office = $data[0]->comment_office;

		$marker_name = $data[0]->marker_name;
		$marker_length = round($data[0]->marker_length,3);
		$marker_width = round($data[0]->marker_width,3);
		$min_length = round($data[0]->min_length,3);

		$location = $data[0]->location;
		$overlapping = $data[0]->overlapping;
		$layer_limit = $data[0]->layer_limit;

		$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;

		if ($tpp_mat_keep_wastage == 1) {
			$tpp_mat_keep_wastage = "YES";
		} else {
			$tpp_mat_keep_wastage = "NO";
		}

		if (($skeda_item_type == 'MS') OR ($skeda_item_type == 'MM')) {
		
			$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			      mp.[mattress]
			      ,mp.[style_size]
			      --,mp.[pro_id]
			      ,mp.[pro_pcs_layer]
			      --,mp.[pro_pcs_planned]
			      ,mp.[pro_pcs_actual]
			      ,s.[padprint_item]
			      ,s.[padprint_color]
			      ,s.[pro]
			      ,s.[sku]
			      ,s.[multimaterial]
			      ,po.[location_all]
			      
			FROM  [mattress_pros] as mp
			JOIN  [pro_skedas] as s ON s.[pro_id] = mp.[pro_id]
			LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
			WHERE mp.[mattress_id] = '".$id."' "));
			// dd($data1);

			$padprint_item = $data1[0]->padprint_item;
			$padprint_color = $data1[0]->padprint_color;
			$pro = $data1[0]->pro;
			$style_size = $data1[0]->sku;
			$destination = $data1[0]->location_all;
			$pro_pcs_layer = round($data1[0]->pro_pcs_layer);
			$multimaterial = $data1[0]->multimaterial;

			$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll]
				FROM  [o_rolls]
				WHERE mattress_id_new = '".$id."' "));
			// dd($data2);

			for ($i=0; $i < count($data2); $i++) { 
					
				// $id = $i + 1;
				// $pro."_".$i = $data1[$i]->pro;
				if (isset($data2[$i]->o_roll)) {
					${"o_roll_{$i}"}=$data2[$i]->o_roll;
				} else {
					${"o_roll{$i}"}='';
				}
			}

			for ($i=0; $i <= 9; $i++) { 
				if (!isset(${"o_roll_{$i}"})) {
					${"o_roll_{$i}"}='';
				}	
				// var_dump(${"pro_{$i}"});
			}

		} else {

			dd('MB or MW not ready');
			$padprint_item = '';
			$padprint_color = '';
			$pro = '';
			$style_size = '';
			$destination = '';
			$pro_pcs_layer = '';
			$multimaterial = '';

			for ($i=0; $i <= 9; $i++) { 
				if (!isset(${"o_roll_{$i}"})) {
					${"o_roll_{$i}"}='';
				}	
				// var_dump(${"pro_{$i}"});
			}
		}

		$table = new print_mini_mattress;

		$table->mattress_0 = $mattress;
		$table->marker_name_0 = $marker_name;
		$table->location_0 = $location;
		$table->skeda_0 = $skeda;

		$table->width_theor_usable_0 = $width_theor_usable;
		$table->marker_length_0 = $marker_length;
		if ($marker_width == 0) {
			$marker_width = $width_theor_usable;
		} 
		$table->marker_width_0 = $marker_width;
		$table->min_length_0 = $min_length;
		$table->spreading_method_0 = $spreading_method;
		$table->material_0 = $material;
		$table->color_desc_0 = $color_desc;
		$table->dye_lot_0 = $dye_lot;
		$table->pcs_bundle_0 = $pcs_bundle;
		$table->bottom_paper_0 = $bottom_paper;
		$table->tpp_mat_keep_wastage_0 = $tpp_mat_keep_wastage;
		$table->layer_limit_0 = $layer_limit;

		$table->padprint_item_0 = $padprint_item;
		$table->padprint_color_0 = $padprint_color;
		$table->comment_office_0 = $comment_office;
		
		$table->pro_0 = $pro;
		$table->style_size_0 = $style_size;
		$table->destination_0 = $destination;
		$table->pro_pcs_layer_0 = $pro_pcs_layer;
		$table->multimaterial_0 = $multimaterial;

		$table->o_roll_0_0 = $o_roll_0;
		$table->o_roll_1_0 = $o_roll_1;
		$table->o_roll_2_0 = $o_roll_2;
		$table->o_roll_3_0 = $o_roll_3;
		$table->o_roll_4_0 = $o_roll_4;
		$table->o_roll_5_0 = $o_roll_5;
		$table->o_roll_6_0 = $o_roll_6;
		$table->o_roll_7_0 = $o_roll_7;
		$table->o_roll_8_0 = $o_roll_8;
		$table->o_roll_9_0 = $o_roll_9;

		$table->printer = $printer;
		$table->save();

		$table1 = mattress_details::findOrFail($id);
		$table1->printed_nalog = (int)$table1->printed_nalog + 1;
		$table1->save();

		return redirect('/');

	}

	public function print_mattress_multiple () {

		return view('planner.print_mattress_multiple');
	}

	public function print_mattress_multiple_sm () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[g_bin]
		      ,m1.[material]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[width_theor_usable]
		      ,m1.[skeda]
		      ,m1.[skeda_item_type]
		      ,m1.[spreading_method]
		      --,'|'
		      ,m2.[layers]
		      ,m2.[pcs_bundle]
		      ,m2.[bottom_paper]
		      ,m2.[comment_office]
		      ,m2.[overlapping]
		      ,m2.[tpp_mat_keep_wastage]
		      ,m2.[tpa_number]
		      ,m2.[printed_nalog]
		      --,'|'
		      ,m3.[marker_name]
		      ,m3.[marker_length]
		      ,m3.[marker_width]
		      ,m3.[min_length]
		      --,'|'
		      ,m4.[location]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
		  WHERE (m4.[status] = 'TO_LOAD' OR m4.[status] = 'TO_SPREAD') AND m1.[skeda_item_type] != 'MM' AND m2.[printed_nalog] IS NULL"));
		// dd($data);

		return view('planner.print_mattress_multiple_sm', compact('data'));
	}

	public function print_mattress_multiple_sm_post(Request $request) {

		$this->validate($request, ['items'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$items = serialize($input['items']);
		// dd($items);

		return view('planner.print_mattress_multiple_sm_post', compact('items'));
	}

	public function print_mattress_multiple_sm_complete(Request $request) {

		$this->validate($request, ['items'=>'required', 'printer'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$printer = $input['printer'];
		$items = unserialize($input['items']);
		// dd($items);

		foreach ($items as $id) {
			
				// dd($id);
				$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					  m1.[id]
				      ,m1.[mattress]
				      ,m1.[g_bin]
				      ,m1.[material]
				      ,m1.[dye_lot]
				      ,m1.[color_desc]
				      ,m1.[width_theor_usable]
				      ,m1.[skeda]
				      ,m1.[skeda_item_type]
				      ,m1.[spreading_method]
				      --,'|'
				      ,m2.[layers]
				      ,m2.[pcs_bundle]
				      ,m2.[bottom_paper]
				      ,m2.[comment_office]
				      ,m2.[overlapping]
				      ,m2.[tpp_mat_keep_wastage]
				      ,m2.[tpa_number]
				      ,m2.[printed_nalog]
				      --,'|'
				      ,m3.[marker_name]
				      ,m3.[marker_length]
				      ,m3.[marker_width]
				      --,'|'
				      ,m4.[location]
				      
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
				  WHERE m1.[id] = '".$id."' "));
				// dd($data);
			
				$id = $data[0]->id;
				$mattress = $data[0]->mattress;
				$g_bin = $data[0]->g_bin;
				$material = $data[0]->material;
				$dye_lot = $data[0]->dye_lot;
				$color_desc = $data[0]->color_desc;
				$width_theor_usable = round($data[0]->width_theor_usable,3);
				$skeda = $data[0]->skeda;
				$skeda_item_type = $data[0]->skeda_item_type;
				$spreading_method = $data[0]->spreading_method;
				
				$layers = round($data[0]->layers,0);
				$pcs_bundle = round($data[0]->pcs_bundle,0);
				$bottom_paper = $data[0]->bottom_paper;
				$comment_office = $data[0]->comment_office;

				$marker_name = $data[0]->marker_name;
				$marker_length = round($data[0]->marker_length,3);
				$marker_width = round($data[0]->marker_width,3);

				$location = $data[0]->location;
				$overlapping = $data[0]->overlapping;

				$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;

				if ($tpp_mat_keep_wastage == 1) {
					$tpp_mat_keep_wastage = "YES";
				} else {
					$tpp_mat_keep_wastage = "NO";
				}

				if (($skeda_item_type == 'MS') OR ($skeda_item_type == 'MM')) {

					$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					      mp.[mattress]
					      ,mp.[style_size]
					      --,mp.[pro_id]
					      ,mp.[pro_pcs_layer]
					      --,mp.[pro_pcs_planned]
					      ,mp.[pro_pcs_actual]
					      ,s.[padprint_item]
					      ,s.[padprint_color]
					      ,s.[pro]
					      ,s.[sku]
					      ,s.[multimaterial]
					      ,po.[location_all]
					      
					FROM  [mattress_pros] as mp
					JOIN  [pro_skedas] as s ON s.[pro_id] = mp.[pro_id]
					LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
					WHERE mp.[mattress_id] = '".$id."' "));
					// dd($data1);

					$padprint_item = $data1[0]->padprint_item;
					$padprint_color = $data1[0]->padprint_color;

					for ($x=0; $x < count($data1); $x++) { 
							
						// $xd = $x + 1;
						// $pro."_".$x = $data1[$x]->pro;
						if (isset($data1[$x]->pro)) {
							${"pro_{$x}"}=$data1[$x]->pro;	
						} else {
							${"pro_{$x}"}='';
						}

						if (isset($data1[$x]->style_size)) {
							${"style_size_{$x}"}=$data1[$x]->sku;
						} else {
							${"style_size_{$x}"}='';
						}

						if (isset($data1[$x]->pro_pcs_actual)) {
							${"pro_pcs_actual_{$x}"}=$data1[$x]->pro_pcs_actual;
						} else {
							${"pro_pcs_actual_{$x}"}='';
						}

						if (isset($data1[$x]->pro_pcs_layer)) {
							${"pro_pcs_layer_{$x}"}=round($data1[$x]->pro_pcs_layer,0);
						} else {
							${"pro_pcs_layer_{$x}"}='';
						}
						
						if (isset($data1[$x]->location_all)) {
							${"destination_{$x}"}=$data1[$x]->location_all;
						} else {
							${"destination_{$x}"}='';
						}

						if (isset($data1[$x]->multimaterial)) {
							${"multimaterial_{$x}"}=$data1[$x]->multimaterial;
						} else {
							${"multimaterial_{$x}"}='';
						}
					}

					for ($y=0; $y <= 14; $y++) { 
						
						if (!isset(${"pro_{$y}"})) {
							
							${"pro_{$y}"}='';
							${"style_size_{$y}"}='';
							${"pro_pcs_actual_{$y}"}='';
							${"pro_pcs_layer_{$y}"}='';
							${"destination_{$y}"}='';
							${"multimaterial_{$y}"}='';
						}	
						// var_dump(${"pro_{$y}"});
					}

				} else {

					$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					      m.[mattress]
					      --,m.[skeda]
					      --,s.[pro_id]
					      ,s.[style_size]
					      ,mp.[pro_pcs_layer]
					      --,mp.[pro_pcs_planned]
					      ,mp.[pro_pcs_actual]
					      ,s.[padprint_item]
					      ,s.[padprint_color]
					      ,s.[pro]
					      ,s.[sku]
					      ,s.[multimaterial]
					      ,po.[location_all]
					      
					FROM  [mattresses] as m
					JOIN  [pro_skedas] as s ON s.[skeda] = m.[skeda]
					LEFT JOIN  [mattress_pros] as mp ON mp.[pro_id] = s.[pro_id] AND mp.[mattress_id] = m.[id]
					LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
					WHERE m.[id] = '".$id."' "));
					// dd($data1);

					$padprint_item = $data1[0]->padprint_item;
					$padprint_color = $data1[0]->padprint_color;

					for ($i=0; $i < count($data1); $i++) { 
						// $id = $i + 1;
						// $pro."_".$i = $data1[$i]->pro;
						if (isset($data1[$i]->pro)) {
							${"pro_{$i}"}=$data1[$i]->pro;	
						} else {
							${"pro_{$i}"}='';
						}

						if (isset($data1[$i]->sku)) {
							${"style_size_{$i}"}=$data1[$i]->sku;
						} else {
							${"style_size_{$i}"}='';
						}

						if (isset($data1[$i]->pro_pcs_actual)) {
							${"pro_pcs_actual_{$i}"}=$data1[$i]->pro_pcs_actual;
						} else {
							${"pro_pcs_actual_{$i}"}='';
						}

						if (isset($data1[$i]->pro_pcs_layer)) {
								${"pro_pcs_layer_{$i}"}=round($data1[$i]->pro_pcs_layer,0);
						} else {
							${"pro_pcs_layer_{$i}"}='';
						}
						
						if (isset($data1[$i]->location_all)) {
							${"destination_{$i}"}=$data1[$i]->location_all;
						} else {
							${"destination_{$i}"}='';
						}

						if (isset($data1[$i]->multimaterial)) {
							${"multimaterial_{$i}"}=$data1[$i]->multimaterial;
						} else {
							${"multimaterial_{$i}"}='';
						}
					}

					for ($i=0; $i <= 14; $i++) { 
						if (!isset(${"pro_{$i}"})) {
							${"pro_{$i}"}='';
							${"style_size_{$i}"}='';
							${"pro_pcs_actual_{$i}"}='';
							${"pro_pcs_layer_{$i}"}='';
							${"destination_{$i}"}='';
							${"multimaterial_{$i}"}='';
						}	
						// var_dump(${"pro_{$i}"});
					}
				}

				$table = new print_standard_mattress;

				$table->mattress = $mattress;
				// var_dump($mattress);
				$table->marker_name = $marker_name;
				$table->g_bin = $g_bin;
				$table->location = $location;
				$table->skeda = $skeda;

				$table->overlapping = $overlapping;
				$table->width_theor_usable = $width_theor_usable;
				$table->marker_length = $marker_length;
				if ($marker_width == 0) {
					$marker_width = $width_theor_usable;
				} 
				$table->marker_width = $marker_width;
				$table->spreading_method = $spreading_method;
				$table->material = $material;
				$table->color_desc = $color_desc;
				$table->dye_lot = $dye_lot;
				$table->pcs_bundle = $pcs_bundle;
				$table->bottom_paper = $bottom_paper;
				$table->tpp_mat_keep_wastage = $tpp_mat_keep_wastage;

				$table->layers = $layers;
				$table->padprint_item = $padprint_item;
				$table->padprint_color = $padprint_color;
				$table->comment_office = $comment_office;

				$table->comment_office = $comment_office;
				$table->printer = $printer;

				$table->pro_0 = $pro_0;
				$table->style_size_0 = $style_size_0;
				$table->pro_pcs_actual_0 = round($pro_pcs_actual_0,0);
				$table->destination_0 = $destination_0;
				$table->pro_pcs_layer_0 = $pro_pcs_layer_0;
				$table->multimaterial_0 = $multimaterial_0;

				$table->pro_1 = $pro_1;
				$table->style_size_1 = $style_size_1;
				$table->pro_pcs_actual_1 = round($pro_pcs_actual_1,0);
				$table->destination_1 = $destination_1;
				$table->pro_pcs_layer_1 = $pro_pcs_layer_1;
				$table->multimaterial_1 = $multimaterial_1;

				$table->pro_2 = $pro_2;
				$table->style_size_2 = $style_size_2;
				$table->pro_pcs_actual_2 = round($pro_pcs_actual_2,0);
				$table->destination_2 = $destination_2;
				$table->pro_pcs_layer_2 = $pro_pcs_layer_2;
				$table->multimaterial_2 = $multimaterial_2;

				$table->pro_3 = $pro_3;
				$table->style_size_3 = $style_size_3;
				$table->pro_pcs_actual_3 = round($pro_pcs_actual_3,0);
				$table->destination_3 = $destination_3;
				$table->pro_pcs_layer_3 = $pro_pcs_layer_3;
				$table->multimaterial_3 = $multimaterial_3;

				$table->pro_4 = $pro_4;
				$table->style_size_4 = $style_size_4;
				$table->pro_pcs_actual_4 = round($pro_pcs_actual_4,0);
				$table->destination_4 = $destination_4;
				$table->pro_pcs_layer_4 = $pro_pcs_layer_4;
				$table->multimaterial_4 = $multimaterial_4;

				$table->pro_5 = $pro_5;
				$table->style_size_5 = $style_size_5;
				$table->pro_pcs_actual_5 = round($pro_pcs_actual_5,0);
				$table->destination_5 = $destination_5;
				$table->pro_pcs_layer_5 = $pro_pcs_layer_5;
				$table->multimaterial_5 = $multimaterial_5;

				$table->pro_6 = $pro_6;
				$table->style_size_6 = $style_size_6;
				$table->pro_pcs_actual_6 = round($pro_pcs_actual_6,0);
				$table->destination_6 = $destination_6;
				$table->pro_pcs_layer_6 = $pro_pcs_layer_6;
				$table->multimaterial_6 = $multimaterial_6;

				$table->pro_7 = $pro_7;
				$table->style_size_7 = $style_size_7;
				$table->pro_pcs_actual_7 = round($pro_pcs_actual_7,0);
				$table->destination_7 = $destination_7;
				$table->pro_pcs_layer_7 = $pro_pcs_layer_7;
				$table->multimaterial_7 = $multimaterial_7;

				$table->pro_8 = $pro_8;
				$table->style_size_8 = $style_size_8;
				$table->pro_pcs_actual_8 = round($pro_pcs_actual_8,0);
				$table->destination_8 = $destination_8;
				$table->pro_pcs_layer_8 = $pro_pcs_layer_8;
				$table->multimaterial_8 = $multimaterial_8;

				$table->pro_9 = $pro_9;
				$table->style_size_9 = $style_size_9;
				$table->pro_pcs_actual_9 = round($pro_pcs_actual_9,0);
				$table->destination_9 = $destination_9;
				$table->pro_pcs_layer_9 = $pro_pcs_layer_9;
				$table->multimaterial_9 = $multimaterial_9;

				$table->pro_10 = $pro_10;
				$table->style_size_10 = $style_size_10;
				$table->pro_pcs_actual_10 = round($pro_pcs_actual_10,0);
				$table->destination_10 = $destination_10;
				$table->pro_pcs_layer_10 = $pro_pcs_layer_10;
				$table->multimaterial_10 = $multimaterial_10;

				$table->pro_11 = $pro_11;
				$table->style_size_11 = $style_size_11;
				$table->pro_pcs_actual_11 = round($pro_pcs_actual_11,0);
				$table->destination_11 = $destination_11;
				$table->pro_pcs_layer_11 = $pro_pcs_layer_11;
				$table->multimaterial_11 = $multimaterial_11;

				$table->pro_12 = $pro_12;
				$table->style_size_12 = $style_size_12;
				$table->pro_pcs_actual_12 = round($pro_pcs_actual_12,0);
				$table->destination_12 = $destination_12;
				$table->pro_pcs_layer_12 = $pro_pcs_layer_12;
				$table->multimaterial_12 = $multimaterial_12;

				$table->pro_13 = $pro_13;
				$table->style_size_13 = $style_size_13;
				$table->pro_pcs_actual_13 = round($pro_pcs_actual_13,0);
				$table->destination_13 = $destination_13;
				$table->pro_pcs_layer_13 = $pro_pcs_layer_13;
				$table->multimaterial_13 = $multimaterial_13;

				$table->pro_14 = $pro_14;
				$table->style_size_14 = $style_size_14;
				$table->pro_pcs_actual_14 = round($pro_pcs_actual_14,0);
				$table->destination_14 = $destination_14;
				$table->pro_pcs_layer_14 = $pro_pcs_layer_14;
				$table->multimaterial_14 = $multimaterial_14;

				$table->save();

				$table1 = mattress_details::findOrFail($id);
				$table1->printed_nalog = (int)$table1->printed_nalog + 1;
				$table1->save();
		}
		return redirect('/');		
	}

	public function print_mattress_multiple_mm () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			  m1.[id]
		      ,m1.[mattress]
		      ,m1.[g_bin]
		      ,m1.[material]
		      ,m1.[dye_lot]
		      ,m1.[color_desc]
		      ,m1.[width_theor_usable]
		      ,m1.[skeda]
		      ,m1.[skeda_item_type]
		      ,m1.[spreading_method]
		      --,'|'
		      ,m2.[layers]
		      ,m2.[pcs_bundle]
		      ,m2.[bottom_paper]
		      ,m2.[comment_office]
		      ,m2.[overlapping]
		      ,m2.[tpp_mat_keep_wastage]
		      ,m2.[tpa_number]
		      ,m2.[printed_nalog]
		      ,m2.[layer_limit]
		      --,'|'
		      ,m3.[marker_name]
		      ,m3.[marker_length]
			  ,m3.[marker_width]
		      ,m3.[min_length]
		      --,'|'
		      ,m4.[location]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
		  WHERE (m4.[status] = 'TO_LOAD' OR m4.[status] = 'TO_LOAD') AND m1.[skeda_item_type] = 'MM' AND m2.[printed_nalog] IS NULL"));
		// dd($data);

		return view('planner.print_mattress_multiple_mm', compact('data'));
	}

	public function print_mattress_multiple_mm_post(Request $request) {

		$this->validate($request, ['items'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$items = serialize($input['items']);
		// dd($items);

		return view('planner.print_mattress_multiple_mm_post', compact('items'));
	}

	public function print_mattress_multiple_mm_complete(Request $request) {

		$this->validate($request, ['items'=>'required', 'printer'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$printer = $input['printer'];
		$items = unserialize($input['items']);
		// dd($items);

		$array = array_chunk($items,2);
		// print_r($array);
		$uk = count($items);


		for ($i=0; $i < count($array); $i++) { 
			// var_dump($array[$i]);

			if ($uk > 1) {
				
				for ($x=0; $x < 2 ; $x++) { 
					var_dump($array[$i][$x]);

					$id = $array[$i][$x];

					$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					  m1.[id]
				      ,m1.[mattress]
				      ,m1.[g_bin]
				      ,m1.[material]
				      ,m1.[dye_lot]
				      ,m1.[color_desc]
				      ,m1.[width_theor_usable]
				      ,m1.[skeda]
				      ,m1.[skeda_item_type]
				      ,m1.[spreading_method]
				      --,'|'
				      ,m2.[layers]
				      ,m2.[pcs_bundle]
				      ,m2.[bottom_paper]
				      ,m2.[comment_office]
				      ,m2.[overlapping]
				      ,m2.[tpp_mat_keep_wastage]
				      ,m2.[tpa_number]
				      ,m2.[printed_nalog]
				      ,m2.[layer_limit]
				      --,'|'
				      ,m3.[marker_name]
				      ,m3.[marker_length]
				      ,m3.[marker_width]
				      ,m3.[min_length]
				      --,'|'
				      ,m4.[location]
				      
					  FROM [mattresses] as m1
					  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
					  WHERE m1.[id] = '".$id."' "));
					// dd($data);
					// var_dump($data);
				
					${"id_{$x}"} = $data[0]->id;
					${"mattress_{$x}"} = $data[0]->mattress;
					${"material_{$x}"} = $data[0]->material;
					${"dye_lot_{$x}"} = '0-O';
					${"color_desc_{$x}"} = $data[0]->color_desc;
					${"width_theor_usable_{$x}"} = round($data[0]->width_theor_usable,3);
					${"skeda_{$x}"} = $data[0]->skeda;
					${"skeda_item_type_{$x}"} = $data[0]->skeda_item_type;
					${"spreading_method_{$x}"} = $data[0]->spreading_method;
					
					${"pcs_bundle_{$x}"} = round($data[0]->pcs_bundle,0);
					${"bottom_paper_{$x}"} = $data[0]->bottom_paper;
					${"tpp_mat_keep_wastage_{$x}"} = $data[0]->tpp_mat_keep_wastage;
					${"layer_limit_{$x}"} = $data[0]->layer_limit;

					${"comment_office_{$x}"} = $data[0]->comment_office;

					${"marker_name_{$x}"} = $data[0]->marker_name;
					${"marker_length_{$x}"} = round($data[0]->marker_length,3);
					${"marker_width_{$x}"} = round($data[0]->marker_width,3);
					${"min_length_{$x}"} = round($data[0]->min_length,3);

					${"location_{$x}"} = $data[0]->location;
					${"overlapping_{$x}"} = $data[0]->overlapping;

					$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					      mp.[mattress]
					      ,mp.[style_size]
					      --,mp.[pro_id]
					      ,mp.[pro_pcs_layer]
					      --,mp.[pro_pcs_planned]
					      ,mp.[pro_pcs_actual]
					      ,s.[padprint_item]
					      ,s.[padprint_color]
					      ,s.[pro]
					      ,s.[sku]
					      ,s.[multimaterial]
					      ,po.[location_all]
					      
					FROM  [mattress_pros] as mp
					JOIN  [pro_skedas] as s ON s.[pro_id] = mp.[pro_id]
					LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
					WHERE mp.[mattress_id] = '".$id."' "));
					// dd($data1);

					${"padprint_item_{$x}"} = $data1[0]->padprint_item;
					${"padprint_color_{$x}"} = $data1[0]->padprint_color;
					${"pro_{$x}"} = $data1[0]->pro;
					${"style_size_{$x}"} = $data1[0]->sku;
					${"destination_{$x}"} = $data1[0]->location_all;
					${"pro_pcs_layer_{$x}"} = round($data1[0]->pro_pcs_layer,0);
					${"multimaterial_{$x}"} = $data1[0]->multimaterial;

					$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll]
						FROM  [o_rolls]
						WHERE mattress_id_new = '".$id."' "));
					// dd($data2);

					for ($y=0; $y < count($data2); $y++) { 
							
						// $id = $i + 1;
						// $pro."_".$i = $data1[$i]->pro;
						if (isset($data2[$y]->o_roll)) {
							${"o_roll_{$y}_{$x}"}=$data2[$y]->o_roll;
						} else {
							${"o_roll{$y}_{$x}"}='';
						}
					}

					for ($y=0; $y <= 9; $y++) { 
						if (!isset(${"o_roll_{$y}_{$x}"})) {
							${"o_roll_{$y}_{$x}"}='';
						}	
						// var_dump(${"pro_{$y}_{$x}"});
					}
				}	

				$table = new print_mini_mattress;

				$table->mattress_0 = $mattress_0;
				// var_dump($mattress_0);
				$table->marker_name_0 = $marker_name_0;
				$table->location_0 = $location_0;
				$table->skeda_0 = $skeda_0;

				$table->width_theor_usable_0 = $width_theor_usable_0;
				$table->marker_length_0 = $marker_length_0;
				if ($marker_width_0 == 0) {
					$marker_width_0 = $width_theor_usable_0;
				} 
				$table->marker_width_0 = $marker_width_0;
				$table->min_length_0 = $min_length_0;
				$table->spreading_method_0 = $spreading_method_0;
				$table->material_0 = $material_0;
				$table->color_desc_0 = $color_desc_0;
				$table->dye_lot_0 = $dye_lot_0;
				$table->pcs_bundle_0 = $pcs_bundle_0;
				$table->bottom_paper_0 = $bottom_paper_0;
				$table->layer_limit_0 = $layer_limit_0;

				if ($tpp_mat_keep_wastage_0 == 1) {
					$table->tpp_mat_keep_wastage_0 = "YES";
				} else {
					$table->tpp_mat_keep_wastage_0 = "NO";
				}

				$table->padprint_item_0 = $padprint_item_0;
				$table->padprint_color_0 = $padprint_color_0;
				$table->comment_office_0 = $comment_office_0;

				$table->comment_office_0 = $comment_office_0;
				
				$table->pro_0 = $pro_0;
				$table->style_size_0 = $style_size_0;
				$table->destination_0 = $destination_0;
				$table->pro_pcs_layer_0 = $pro_pcs_layer_0;
				$table->multimaterial_0 = $multimaterial_0;

				$table->o_roll_0_0 = $o_roll_0_0;
				$table->o_roll_1_0 = $o_roll_1_0;
				$table->o_roll_2_0 = $o_roll_2_0;
				$table->o_roll_3_0 = $o_roll_3_0;
				$table->o_roll_4_0 = $o_roll_4_0;
				$table->o_roll_5_0 = $o_roll_5_0;
				$table->o_roll_6_0 = $o_roll_6_0;
				$table->o_roll_7_0 = $o_roll_7_0;
				$table->o_roll_8_0 = $o_roll_8_0;
				$table->o_roll_9_0 = $o_roll_9_0;

				$table->mattress_1 = $mattress_1;
				// var_dump($mattress_1);
				$table->marker_name_1 = $marker_name_1;
				$table->location_1 = $location_1;
				$table->skeda_1 = $skeda_1;

				$table->width_theor_usable_1 = $width_theor_usable_1;
				$table->marker_length_1 = $marker_length_1;
				if ($marker_width_1 == 0) {
					$marker_width_1 = $width_theor_usable_1;
				} 
				$table->marker_width_1 = $marker_width_1;
				$table->min_length_1 = $min_length_1;
				$table->spreading_method_1 = $spreading_method_1;
				$table->material_1 = $material_1;
				$table->color_desc_1 = $color_desc_1;
				$table->dye_lot_1 = $dye_lot_1;
				$table->pcs_bundle_1 = $pcs_bundle_1;
				$table->bottom_paper_1 = $bottom_paper_1;
				$table->layer_limit_1 = $layer_limit_1;

				if ($tpp_mat_keep_wastage_1 == 1) {
					$table->tpp_mat_keep_wastage_1 = "YES";
				} else {
					$table->tpp_mat_keep_wastage_1 = "NO";
				}

				$table->padprint_item_1 = $padprint_item_1;
				$table->padprint_color_1 = $padprint_color_1;
				$table->comment_office_1 = $comment_office_1;

				$table->comment_office_1 = $comment_office_1;
				
				$table->pro_1 = $pro_1;
				$table->style_size_1 = $style_size_1;
				$table->destination_1 = $destination_1;
				$table->pro_pcs_layer_1 = $pro_pcs_layer_1;
				$table->multimaterial_1 = $multimaterial_1;

				$table->o_roll_0_1 = $o_roll_0_1;
				$table->o_roll_1_1 = $o_roll_1_1;
				$table->o_roll_2_1 = $o_roll_2_1;
				$table->o_roll_3_1 = $o_roll_3_1;
				$table->o_roll_4_1 = $o_roll_4_1;
				$table->o_roll_5_1 = $o_roll_5_1;
				$table->o_roll_6_1 = $o_roll_6_1;
				$table->o_roll_7_1 = $o_roll_7_1;
				$table->o_roll_8_1 = $o_roll_8_1;
				$table->o_roll_9_1 = $o_roll_9_1;

				$table->printer = $printer;
				$table->save();

				$table0 = mattress_details::findOrFail($id_0);
				$table0->printed_nalog = (int)$table0->printed_nalog + 1;
				$table0->save();

				$table1 = mattress_details::findOrFail($id_1);
				$table1->printed_nalog = (int)$table1->printed_nalog + 1;
				$table1->save();

			} else {
				// var_dump($array[$i][0]);

				$id = $array[$i][0];
				// dd($id);

				$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					  m1.[id]
				      ,m1.[mattress]
				      ,m1.[g_bin]
				      ,m1.[material]
				      ,m1.[dye_lot]
				      ,m1.[color_desc]
				      ,m1.[width_theor_usable]
				      ,m1.[skeda]
				      ,m1.[skeda_item_type]
				      ,m1.[spreading_method]
				      --,'|'
				      ,m2.[layers]
				      ,m2.[pcs_bundle]
				      ,m2.[bottom_paper]
				      ,m2.[comment_office]
				      ,m2.[overlapping]
				      ,m2.[tpp_mat_keep_wastage]
				      ,m2.[tpa_number]
				      ,m2.[printed_nalog]
				      ,m2.[layer_limit]
				      --,'|'
				      ,m3.[marker_name]
				      ,m3.[marker_length]
				      ,m3.[marker_width]
				      ,m3.[min_length]
				      --,'|'
				      ,m4.[location]
				      
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.[active] = '1'
				  WHERE m1.[id] = '".$id."' "));
				// dd($data);
				// var_dump($data);
				// var_dump("<br>");
				
				$x = 0;
					${"id_{$x}"} = $data[0]->id;
					${"mattress_{$x}"} = $data[0]->mattress;
					${"material_{$x}"} = $data[0]->material;
					${"dye_lot_{$x}"} = '0-O';
					${"color_desc_{$x}"} = $data[0]->color_desc;
					${"width_theor_usable_{$x}"} = round($data[0]->width_theor_usable,3);
					${"skeda_{$x}"} = $data[0]->skeda;
					${"skeda_item_type_{$x}"} = $data[0]->skeda_item_type;
					${"spreading_method_{$x}"} = $data[0]->spreading_method;
					
					${"pcs_bundle_{$x}"} = round($data[0]->pcs_bundle,0);
					${"bottom_paper_{$x}"} = $data[0]->bottom_paper;
					${"tpp_mat_keep_wastage_{$x}"} = $data[0]->tpp_mat_keep_wastage;
					${"layer_limit_{$x}"} = $data[0]->layer_limit;

					${"comment_office_{$x}"} = $data[0]->comment_office;

					${"marker_name_{$x}"} = $data[0]->marker_name;
					${"marker_length_{$x}"} = round($data[0]->marker_length,3);
					${"marker_width_{$x}"} = round($data[0]->marker_width,3);
					${"min_length_{$x}"} = round($data[0]->min_length,3);

					${"location_{$x}"} = $data[0]->location;
					${"overlapping_{$x}"} = $data[0]->overlapping;
					
					$data1 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					      mp.[mattress]
					      ,mp.[style_size]
					      --,mp.[pro_id]
					      ,mp.[pro_pcs_layer]
					      --,mp.[pro_pcs_planned]
					      ,mp.[pro_pcs_actual]
					      ,s.[padprint_item]
					      ,s.[padprint_color]
					      ,s.[pro]
					      ,s.[sku]
					      ,s.[multimaterial]
					      ,po.[location_all]
					      
					FROM  [mattress_pros] as mp
					JOIN  [pro_skedas] as s ON s.[pro_id] = mp.[pro_id]
					LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = s.[pro]
					WHERE mp.[mattress_id] = '".$id."' "));
					// dd($data1);

					${"padprint_item_{$x}"} = $data1[0]->padprint_item;
					${"padprint_color_{$x}"} = $data1[0]->padprint_color;
					${"pro_{$x}"} = $data1[0]->pro;
					${"style_size_{$x}"} = $data1[0]->sku;
					${"destination_{$x}"} = $data1[0]->location_all;
					${"pro_pcs_layer_{$x}"} = $data1[0]->pro_pcs_layer;
					${"multimaterial_{$x}"} = $data1[0]->multimaterial;

					$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll]
						FROM  [o_rolls]
						WHERE mattress_id_new = '".$id."' "));
					// dd($data2);

					for ($y=0; $y < count($data2); $y++) { 
							
						// $id = $i + 1;
						// $pro."_".$i = $data1[$i]->pro;
						if (isset($data2[$y]->o_roll)) {
							${"o_roll_{$y}_{$x}"}=$data2[$y]->o_roll;
						} else {
							${"o_roll{$y}_{$x}"}='';
						}
					}

					for ($y=0; $y <= 9; $y++) { 
						if (!isset(${"o_roll_{$y}_{$x}"})) {
							${"o_roll_{$y}_{$x}"}='';
						}	
						// var_dump(${"pro_{$y}_{$x}"});
					}

				$table1 = new print_mini_mattress;

				$table1->mattress_0 = $mattress_0;
				// var_dump($mattress_0);

				$table1->marker_name_0 = $marker_name_0;
				$table1->location_0 = $location_0;
				$table1->skeda_0 = $skeda_0;

				$table1->width_theor_usable_0 = $width_theor_usable_0;
				$table1->marker_length_0 = $marker_length_0;
				if ($marker_width_0 == 0) {
					$marker_width_0 = $width_theor_usable_1;
				} 
				$table1->marker_width_0 = $marker_width_0;
				$table1->min_length_0 = $min_length_0;
				$table1->spreading_method_0 = $spreading_method_0;
				$table1->material_0 = $material_0;
				$table1->color_desc_0 = $color_desc_0;
				$table1->dye_lot_0 = $dye_lot_0;
				$table1->pcs_bundle_0 = $pcs_bundle_0;
				$table1->bottom_paper_0 = $bottom_paper_0;
				$table1->layer_limit_0 = $layer_limit_0;

				if ($tpp_mat_keep_wastage_0 == 1) {
					$table1->tpp_mat_keep_wastage_0 = "YES";
				} else {
					$table1->tpp_mat_keep_wastage_0 = "NO";
				}

				$table1->padprint_item_0 = $padprint_item_0;
				$table1->padprint_color_0 = $padprint_color_0;
				$table1->comment_office_0 = $comment_office_0;

				$table1->pro_0 = $pro_0;
				$table1->style_size_0 = $style_size_0;
				$table1->destination_0 = $destination_0;
				$table1->pro_pcs_layer_0 = $pro_pcs_layer_0;
				$table1->multimaterial_0 = $multimaterial_0;

				$table1->o_roll_0_0 = $o_roll_0_0;
				$table1->o_roll_1_0 = $o_roll_1_0;
				$table1->o_roll_2_0 = $o_roll_2_0;
				$table1->o_roll_3_0 = $o_roll_3_0;
				$table1->o_roll_4_0 = $o_roll_4_0;
				$table1->o_roll_5_0 = $o_roll_5_0;
				$table1->o_roll_6_0 = $o_roll_6_0;
				$table1->o_roll_7_0 = $o_roll_7_0;
				$table1->o_roll_8_0 = $o_roll_8_0;
				$table1->o_roll_9_0 = $o_roll_9_0;

				$table1->printer = $printer;
				$table1->save();

				$table2 = mattress_details::findOrFail($id_0);
				$table2->printed_nalog = (int)$table2->printed_nalog + 1;
				$table2->save();

			}
			$uk = $uk - 2;
		}
		return redirect('/');
	}
}
