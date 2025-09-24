<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;


use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;

use App\marker_line;
use App\mattress_details;
use App\mattress_phases;
use App\mattress_markers;
use App\mattress_pro;
use App\marker_change;
use App\mattress;
use App\mattress_eff;
use App\pro_skeda;
use App\o_roll;
use App\paspul;
use App\paspul_line;
use App\paspul_rewound;
use App\mattress_split_request;
use App\paspul_stock;
use App\paspul_stock_log;
use App\req_paspul;

use App\print_standard_mattress;
use App\print_mini_mattress;

use App\paspul_location;

use App\skeda_comments;
use App\inbound_delivery;
use App\fabric_reservation;
use App\leftover_table;

use App\material_comments;

// use DB;
use Illuminate\Support\Facades\DB;
use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;
use Carbon\Carbon;

use Session;
use Validator;

class plannerController extends Controller {

//DEFAULT
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
			return redirect('/');
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
	      ,m2.[printed_nalog]
	      ,m2.[layer_limit]
	      ,m2.[overlapping]
	      ,m2.[req_time]
	      ,m2.[last_mattress]
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
	      ,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
	      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  LEFT JOIN [mattress_split_requests] as ms ON ms.[mattress_id_new] = m1.[id]
		  WHERE m4.[location] = '".$location."' AND m4.[active] = '1' 
		 
		  ORDER BY m2.[position] asc
		"));
		// dd($data);
	
		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';

		for ($i=0; $i < count($data) ; $i++) { 
			
			$id = $data[$i]->id;
			// dd($id);

			if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.[pro]
					,ps.[style_size]
					,ps.[sku]
					,po.[location_all]
					--,*
				  FROM [mattress_pros] as mp
				  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE mp.[mattress_id] = '".$id."' "));
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

			} else {

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
			      ,m2.[printed_nalog]
			      ,m2.[layer_limit]
			      ,m2.[overlapping]
			      ,m2.[req_time]
			      ,m2.[last_mattress]
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
			      ,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

			  FROM [mattresses] as m1
			  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
			  LEFT JOIN [mattress_split_requests] as ms ON ms.[mattress_id_new] = m1.[id]
			  WHERE m4.[status] = '".$location."' AND m4.[active] = '1' 
			  ORDER BY m2.[position] asc"));

			$pros= '';
			$skus= '';
			$sku_s= '';
			$location_all= '';
			for ($i=0; $i < count($data) ; $i++) { 
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.[pro]
						,ps.[style_size]
						,ps.[sku]
						,po.[location_all]
						--,*
					  FROM [mattress_pros] as mp
					  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
					  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
					WHERE mp.[mattress_id] = '".$id."' "));
					
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

				} else {

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
			}
		}

		if ($location == 'TO_SPLIT') {
			
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [mattress_split_requests] WHERE status = 'TO_SPLIT'"));	
		}

		if ($location == 'TO_CHANGE') {
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
			      ,m2.[overlapping]
			      ,m2.[req_time]
			      ,m2.[last_mattress]
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
			      ,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

			  FROM [mattresses] as m1
			  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
			  LEFT JOIN [mattress_split_requests] as ms ON ms.[mattress_id_new] = m1.[id]
			  WHERE m4.[status] = '".$location."' AND m4.[active] = '1' 
			  ORDER BY m2.[position] asc"));
			// dd($data);
			$pros= '';
			$skus= '';
			$sku_s= '';
			$location_all= '';
			for ($i=0; $i < count($data) ; $i++) { 
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.[pro]
						,ps.[style_size]
						,ps.[sku]
						,po.[location_all]
						--,*
					  FROM [mattress_pros] as mp
					  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
					  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
					WHERE mp.[mattress_id] = '".$id."' "));
					
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

				} else {

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
			      ,m2.[printed_nalog]
			      ,m2.[layer_limit]
			      ,m2.[overlapping]
			      ,m2.[req_time]
			      ,m2.[last_mattress]
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
			      ,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

			  FROM [mattresses] as m1
			  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
			  LEFT JOIN [mattress_split_requests] as ms ON ms.[mattress_id_new] = m1.[id]
			  WHERE m4.[active] = '1' AND m2.[printed_marker] = 0 AND m3.[marker_name] != '' AND 
			  	(m4.[status] != 'DELETED' AND m4.[status] != 'COMPLETED' AND 
			  	m4.[status] != 'NOT_SET' AND m4.[status] != 'ON_HOLD')
			  ORDER BY m2.position asc"));
			
			$pros= '';
			$skus= '';
			$sku_s= '';
			$location_all= '';
			for ($i=0; $i < count($data) ; $i++) {
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.[pro]
						,ps.[style_size]
						,ps.sku
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

				} else {

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
			}
		}

		if ($location == 'BOARD' OR $location == 'BOARDF') {
			
			//SP0
				$sp0 = DB::connection('sqlsrv')->select(DB::raw("SELECT 
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'SP0' AND m4.[active] = 1 
				  ORDER BY m2.[priority] desc, m1.[skeda] asc"));

				$sp0_req_time = [];
				foreach ($sp0 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $sp0_req_time)) {
				            $sp0_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $sp0_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $sp0_req_time)) {
				            $sp0_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $sp0_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $sp0_req_time)) {
				            $sp0_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $sp0_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				// dd($sp0_req_time);

				$sp0_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'SP0' AND m4.[active] = 1"));
				
				$sp0_m = $sp0_m[0]->sum_m_cons;
			//

			//SP1
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'SP1' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				
				$sp1_req_time = [];
				foreach ($sp1 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $sp1_req_time)) {
				            $sp1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $sp1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $sp1_req_time)) {
				            $sp1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $sp1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $sp1_req_time)) {
				            $sp1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $sp1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				// dd($sp1_req_time);
				

				$sp1_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'SP1' AND m4.[active] = 1"));

				$sp1_m = $sp1_m[0]->sum_m_cons;
			//

			//SP2
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'SP2' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));
				
				$sp2_req_time = [];
				foreach ($sp2 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $sp2_req_time)) {
				            $sp2_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $sp2_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $sp2_req_time)) {
				            $sp2_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $sp2_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $sp2_req_time)) {
				            $sp2_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $sp2_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				// dd($sp2_req_time);

				$sp2_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'SP2' AND m4.[active] = 1"));
				
				$sp2_m = $sp2_m[0]->sum_m_cons;
			//

			//SP3
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'SP3' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$sp3_req_time = [];
				foreach ($sp3 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $sp3_req_time)) {
				            $sp3_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $sp3_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $sp3_req_time)) {
				            $sp3_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $sp3_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $sp3_req_time)) {
				            $sp3_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $sp3_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				// dd($sp3_req_time);
				
				$sp3_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'SP3' AND m4.[active] = 1"));

				$sp3_m = $sp3_m[0]->sum_m_cons;
			//

			//SP4
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'SP4' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$sp4_req_time = [];
				foreach ($sp4 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $sp4_req_time)) {
				            $sp4_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $sp4_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $sp4_req_time)) {
				            $sp4_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $sp4_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $sp4_req_time)) {
				            $sp4_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $sp4_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				// dd($sp4_req_time);

				$sp4_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'SP4' AND m4.[active] = 1"));
				
				$sp4_m = $sp4_m[0]->sum_m_cons;
			//

			//MS1
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'MS1' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$ms1_req_time = [];
				foreach ($ms1 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $ms1_req_time)) {
				            $ms1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $ms1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $ms1_req_time)) {
				            $ms1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $ms1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $ms1_req_time)) {
				            $ms1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $ms1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }
					}
				}
				// dd($ms1_req_time);

				$ms1_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'MS1' AND m4.[active] = 1"));
				
				$ms1_m = $ms1_m[0]->sum_m_cons;
			//

			//MS2
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'MS2' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$ms2_req_time = [];
				foreach ($ms2 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $ms2_req_time)) {
				            $ms2_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $ms2_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $ms2_req_time)) {
				            $ms2_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $ms2_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $ms2_req_time)) {
				            $ms2_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $ms2_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }
					}
				}
				// dd($ms2_req_time);

				$ms2_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'MS2' AND m4.[active] = 1"));
				
				$ms2_m = $ms2_m[0]->sum_m_cons;
			//

			//MS3
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'MS3' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$ms3_req_time = [];
				foreach ($ms3 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $ms3_req_time)) {
				            $ms3_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $ms3_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $ms3_req_time)) {
				            $ms3_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $ms3_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $ms3_req_time)) {
				            $ms3_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $ms3_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }
					}
				}
				
				$ms3_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'MS3' AND m4.[active] = 1"));
				
				$ms3_m = $ms3_m[0]->sum_m_cons;
			//

			//TUB
				$tub = DB::connection('sqlsrv')->select(DB::raw("SELECT 
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      /*,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,*/
					,(SELECT 
						TOP 1 x.average_min_per_layer
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers
							FROM [cutting_tubolare_smvs]) as x
						WHERE	
								x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								
				 	) as average_min_per_layer,
					/*(SELECT 
							ROUND(AVG(average_min_per_layer),3)
					  FROM [cutting_tubolare_smvs] ) as average_of_tubolare_smv_all,*/
					'0,167' as average_of_tubolare_smv_all,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment

				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'TUB' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$tub_req_time = [];
				foreach ($tub as $line) {

					if ($line->average_min_per_layer != 0) {
						
				        if (array_key_exists($line->priority, $tub_req_time)) {
				            $tub_req_time[$line->priority] += round((float)$line->average_min_per_layer * (float)$line->layers_a,0);
				        } else { 
				            $tub_req_time[$line->priority] = round((float)$line->average_min_per_layer * (float)$line->layers_a,0);
				        }

					} else {

						if (array_key_exists($line->priority, $tub_req_time)) {
				            $tub_req_time[$line->priority] += round((float)$line->average_of_tubolare_smv_all * (float)$line->layers_a,0);
				        } else {
				            $tub_req_time[$line->priority] = round((float)$line->average_of_tubolare_smv_all * (float)$line->layers_a,0);
				        }
					}
				}
				// dd($tub_req_time);
				// $tub_req_time = 0;
				
				$tub_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					WHERE m4.[location] = 'TUB' AND m4.[active] = 1"));
				
				$tub_m = $tub_m[0]->sum_m_cons;
			//

			//MM1
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'MM1' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));

				$mm1_req_time = [];
				foreach ($mm1 as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $mm1_req_time)) {
				            $mm1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $mm1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $mm1_req_time)) {
				            $mm1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $mm1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $mm1_req_time)) {
				            $mm1_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $mm1_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				
				$mm1_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons,
					SUM(o.[no_of_joinings]) as o_sum
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					JOIN [o_rolls] as o ON o.[mattress_id_new] = m1.[id]
					WHERE m4.[location] = 'MM1' AND m4.[active] = 1"));
				
				$mm1_m = $mm1_m[0]->o_sum;
			//

			//CUT
				$cut = DB::connection('sqlsrv')->select(DB::raw("SELECT 
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
			      ,m2.[layers_a]
			      ,m2.[cons_planned]
			      ,m2.[cons_actual]
			      ,m2.[priority]
			      ,m2.[printed_marker]
			      ,m2.[comment_office]
			      ,m2.[overlapping]
			      ,m2.[last_mattress]
			      ,m3.[marker_name]
			      ,m3.[marker_length]
			      ,m3.[marker_width]
			      ,(SELECT SUM([pro_pcs_actual]) FROM [mattress_pros] WHERE [mattress] = m1.mattress) as pro_pcs_actual
			      ,m4.[status]
			      ,(SELECT TOP 1 location_all FROM [posummary].[dbo].[pro] WHERE skeda = m1.[skeda]) as destination
			      ,(SELECT 
						TOP 1 x.average_of_min_per_meter_minm
					
						FROM (
							SELECT 
							*,
							LEFT([layers_group], CHARINDEX('-', [layers_group]) - 1) AS min_layers,
							RIGHT([layers_group], LEN([layers_group]) - CHARINDEX('-', [layers_group])) AS max_layers,
							LEFT([length_group], CHARINDEX('to', [length_group]) - 1) AS min_length,
							RIGHT([length_group], LEN([length_group]) - CHARINDEX('to', [length_group]) - 1) AS max_length
							FROM [cutting_smv_by_categories]) as x
						WHERE	x.spreading_method = (SELECT 
													CASE 
													WHEN  m1.[spreading_method] = 'FACE TO FACE' THEN 'FACE TO FACE'
													ELSE 'FACE UP/DOWN'
													END)
								AND x.material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
								AND (x.min_layers < m2.[layers_a] AND x.max_layers >= m2.[layers_a])
								AND (x.min_length < m3.[marker_length] AND x.max_length >= m3.[marker_length])
				 	) as average_of_min_per_meter_minm_c,
				 	(SELECT 
							TOP 1 average_of_min_per_meter_minm
						  FROM [cutting_smv_by_materials]
						  WHERE material  like RTRIM(LTRIM(LEFT(m1.[material], 11)))+'%'
				 	) as average_of_min_per_meter_minm_m,
					(SELECT 
							ROUND(AVG(average_of_min_per_meter_minm),3)
					  FROM [cutting_smv_by_materials] ) as average_of_min_per_meter_minm_all,
					(SELECT TOP 1 SUBSTRING(t1.sku, 9,5)
					FROM [pro_skedas] as t1
					JOIN [mattress_pros] as t2 ON t2.pro_id = t1.pro_id
					JOIN [mattresses] as t3 ON t3.id = t2.mattress_id
					WHERE t3.id = m1.id)
					as color
					,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
				  FROM [mattresses] as m1
				  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
				  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
				  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
				  WHERE m4.[location] = 'CUT' AND m4.[active] = 1 
				  ORDER BY m2.[position] asc"));
				
				$cut_req_time = [];
				foreach ($cut as $line) {

					if ($line->average_of_min_per_meter_minm_c != 0) {
						// dd('test');
				        if (array_key_exists($line->priority, $cut_req_time)) {
				            $cut_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        } else { 
				            $cut_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_c * (float)$line->cons_actual,0);
				        }

					} else if ($line->average_of_min_per_meter_minm_m != 0) {

						if (array_key_exists($line->priority, $cut_req_time)) {
				            $cut_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        } else { 
				            $cut_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_m * (float)$line->cons_actual,0);
				        }

					} else {

						if (array_key_exists($line->priority, $cut_req_time)) {
				            $cut_req_time[$line->priority] += round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        } else {
				            $cut_req_time[$line->priority] = round((float)$line->average_of_min_per_meter_minm_all * (float)$line->cons_actual,0);
				        }

					}
				}
				
				$cut_m = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					SUM(m3.[marker_length]) as sum_m_length,
					SUM(m2.[layers_a]) as sum_m_layers,
					SUM(m2.[cons_actual]) as sum_m_cons,
					SUM(o.[no_of_joinings]) as o_sum
					FROM [mattresses] as m1
					LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
					LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
					LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
					JOIN [o_rolls] as o ON o.[mattress_id_new] = m1.[id]
					WHERE m4.[location] = 'CUT' AND m4.[active] = 1"));
				
				$cut_m = $cut_m[0]->o_sum;


			//
			return view('planner.plan_mattress', compact('data','location','sp0','sp1','sp2','sp3','sp4','ms1','ms2','ms3','tub','mm1','cut',
				'operator','operators',
				'sp0_m','sp1_m','sp2_m','sp3_m','sp4_m','ms1_m','ms2_m','ms3_m','tub_m','mm1_m','cut_m',
				'sp0_req_time','sp1_req_time','sp2_req_time','sp3_req_time','sp4_req_time','ms1_req_time','ms2_req_time','ms3_req_time','tub_req_time','mm1_req_time','cut_req_time'));
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
		      ,m2.[printed_nalog]
		      ,m2.[overlapping]
		      ,m2.[req_time]
		      ,m2.[last_mattress]
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
		      ,(SELECT TOP 1 standard_comment FROM material_comments WHERE material = SUBSTRING(m1.[material],0,12)) as standard_comment
		      
			  FROM [mattresses] as m1
			  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
			  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
			  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
			  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
			  LEFT JOIN [mattress_split_requests] as ms ON ms.[mattress_id_new] = m1.[id]
			  WHERE (m4.[location] IN ('SP0','SP1','SP2','SP3','SP4','MS1','MS2','MS3','MM1','TUB')) AND m4.[active] = '1' 
			  ORDER BY m2.[position] asc"));
			
			$pros= '';
			$skus= '';
			$sku_s= '';
			$location_all= '';

			for ($i=0; $i < count($data) ; $i++) { 
			
				$id = $data[$i]->id;
				// dd($id);

				if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
					
					$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						ps.[pro]
						,ps.[style_size]
						,ps.[sku]
						,po.[location_all]
						--,*
					  FROM [mattress_pros] as mp
					  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
					  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
					WHERE mp.[mattress_id] = '".$id."' "));
					
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

				} else {

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
			}

			return view('planner.plan_mattress', compact('data','location','operator','operators'));
		}

		if ($location == 'DELETED') {
		}

		if ($location == 'COMPLETED') {
		}

		return view('planner.plan_mattress', compact('data','location','operator','operators'));
	}

// OPERATOR
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
//

// REPOSITION
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

    public function reposition0() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            
	            // DB::table('mattress_details as d')
	            // ->join('mattresses as m', 'm.id', '=', 'd.mattress_id')
	            // ->where('m.id', '=', $value)->update(['d.position' =>  DB::raw($i) ]);
	            
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP0'/*, 'operator1' => Session::get('operator')*/]);
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
	            
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP1'/*, 'operator1' => Session::get('operator')*/]);
	        }	
	}

	public function reposition3() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP2'/*, 'operator1' => Session::get('operator') */]);
	        }		
	}

	public function reposition4() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP3'/*, 'operator1' => Session::get('operator') */]);
	        }	
	}

	public function reposition5() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'SP4'/*, 'operator1' => Session::get('operator') */]);
	        }	
	}

	public function reposition6() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'MS1'/*, 'operator1' => Session::get('operator') */]);
	        }	
	}

	public function reposition7() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'MS2'/*, 'operator1' => Session::get('operator') */]);
	        }		
	}

	public function reposition8() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'TUB'/*, 'operator1' => Session::get('operator') */]);
	        }	
	}

	public function reposition9() {
	        $i = 0;
	        foreach ($_POST['SP'] as $value) {
	            $i++;
	            DB::table('mattress_details')->where('mattress_id', '=', $value)->update([ 'position' => $i ]);
	            DB::table('mattress_phases')->where('mattress_id', '=', $value)->where('active','=',1)->update(['location' => 'MM1'/*, 'operator1' => Session::get('operator') */]);
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

    public function reposition_p_1() {
	    $i = 0;

        if (isset($_POST['NS'] )) {
        	foreach ($_POST['NS'] as $value) {
	            // Execute statement:
	            // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value $operator = Session::get('operator');
	            $i++;
	            DB::table('paspul_rewounds')->where('id', '=', $value)->update([ 'position' => $i ]);
        	}	
        }
	}

	public function reposition_p_2() {
	    $i = 0;

        if (isset($_POST['PRW'] )) {
        	foreach ($_POST['PRW'] as $value) {
	            // Execute statement:
	            // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value $operator = Session::get('operator');
	            $i++;
	            DB::table('paspul_rewounds')->where('id', '=', $value)->update([ 'position' => $i ]);
	            
        	}	
        }
	}
//

// MATTRESS

	public function plan_mattress_line ($id) {

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
		      ,m2.[tpa_number]
		      ,m2.[last_mattress]
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
			  WHERE m1.[id] = '".$id."' AND m4.[active] = '1' 
			  ORDER BY m2.[position] asc"));
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
		$layers_a = $data[0]->layers_a;
		$cons_planned = $data[0]->cons_planned;
		$cons_actual = $data[0]->cons_actual;
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
		$last_mattress = $data[0]->last_mattress;
		$selected_marker = $marker_name;


		// find mattress_pro
		$pro_m = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM [mattress_pros] 
		  	WHERE [mattress_id] = '".$id."'
		"));
		// dd($pro_m);

		if (($skeda_item_type == 'MW') OR ($skeda_item_type == 'MB')) {
			// continue

		} else {
			if (!isset($pro_m[0]->id)) {
				// dd('no mattress_pro');

				$new_marker_reqs = marker_line::where('marker_name', $selected_marker)->get();

				$new_marker_reqSizes = [];
				foreach ($new_marker_reqs as $record) {
				    $new_marker_reqSizes = array_merge($new_marker_reqSizes, explode(',', $record->style_size));
				}
				$new_marker_reqSizes = array_unique($new_marker_reqSizes);
				// dd(['new_marker_reqSizes' => $new_marker_reqSizes]); // Ensure this is placed properly

				$skeda_req = pro_skeda::where('skeda', $skeda)->get();
				$skeda_reqSizes = [];
				foreach ($skeda_req as $record) {
				    $skeda_reqSizes = array_merge($skeda_reqSizes, explode(',', $record->style_size));
				}
				$skeda_reqSizes = array_unique($skeda_reqSizes);

				// dd(['new_marker_reqSizes' => $new_marker_reqSizes, 'skeda_reqSizes' => $skeda_reqSizes]);

				// Check if all sizes from the first table are found in the second table
				$missingSizes = array_diff($new_marker_reqSizes, $skeda_reqSizes);

				if (empty($missingSizes)) {
				    // dd('All style_size values from the new_marker_req can fit in the skeda_req');

				    $markers = DB::connection('sqlsrv')->select(DB::raw("SELECT 
							id, marker_name, marker_width, marker_length, min_length
						FROM [marker_headers] 
						WHERE marker_name = '".$selected_marker."'  AND (status = 'ACTIVE' OR status = 'USELESS') "));
					// dd($markers);

					if (!isset($markers[0]->id)) {
						
						$msg = 'Selected marker has the status NOT ACTIVE.';
						return view('planner.error',compact('msg'));

						// dd('Selected marker has the status USELESS, please choose a marker with lower width.');
					}

					$find_in_marker_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT style_size, pcs_on_layer FROM marker_lines WHERE marker_name = '".$selected_marker."' "));
					// dd($find_in_marker_lines);

					$mattress_pro_array[] = '';
					$marker_pcs_per_layer[] = '';

					// Ask pro
					foreach ($find_in_marker_lines as $line) {
			   			$style_size = $line->style_size;
			   			$pro_pcs_layer = (float)$line->pcs_on_layer;

			   			array_push($marker_pcs_per_layer, $style_size.'#'.$pro_pcs_layer);


			   			$find_in_pro_skedas = DB::connection('sqlsrv')->select(DB::raw("SELECT pro_id,pro FROM pro_skedas WHERE skeda = '".$skeda."' AND style_size = '".$style_size."' "));
			   			// dd($find_in_pro_skedas);


						// if (!isset($find_in_pro_skedas[0])) {
			   			//		dd('can not find pro for ths mattress');
			   			// }  else {
			   			//		// dd($find_in_pro_skedas);
				  		//		$pro_id = $find_in_pro_skedas[0]->pro_id;
				  		//		$pro = $find_in_pro_skedas[0]->pro;
				  		//		array_push($mattress_pro_array, $style_size.'#'.$pro_id.'#'.$pro);
						// }	

						// If no records found, stop execution
					    if (empty($find_in_pro_skedas)) {
					        dd('Cannot find pro for this mattress');
					    }

					    // Loop through all found results and add them to the array
					    foreach ($find_in_pro_skedas as $pro_data) {
					        $mattress_pro_array[] = $style_size . '#' . $pro_data->pro_id . '#' . $pro_data->pro;
					    }
		   				   		
		   			}
			   		$marker_pcs_per_layer = array_filter($marker_pcs_per_layer);
			   		$mattress_pro_array = array_filter($mattress_pro_array);

			   		// dd($mattress_pro_array);

					return view('planner.plan_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc','skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a','cons_planned','cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager','test_marker','last_mattress','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office'
						,'selected_marker','marker_pcs_per_layer','mattress_pro_array'));

		   		} else {
					$msg ='Some style_size values from the new_marker_req do not fit in the skeda_req, missing '.implode(', ', $missingSizes).' ';
					return view('planner.error',compact('msg', 'operator', 'operators'));
				    // dd('Some style_size values from the new_marker_req do not fit in the skeda_req, missing '.implode(', ', $missingSizes).'');
				}
			}
		}

		return view('planner.plan_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc','skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a','cons_planned','cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager','test_marker','last_mattress','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office'
				,'selected_marker'));
	}

	public function plan_mattress_line_confirm(Request $request) {

		$this->validate($request, ['id'=>'required','pcs_bundle'=>'required','location'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];
		$skeda_item_type = $input['skeda_item_type'];
		$pcs_bundle = (int)$input['pcs_bundle'];
		$mandatory_to_ins = $input['mandatory_to_ins'];
		$priority = (int)$input['priority'];
		$comment_office = $input['comment_office'];
		$bottom_paper = $input['bottom_paper'];
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
		if (isset($input['last_mattress'])) {
			$last_mattress = (int)$input['last_mattress'];
		} else {
			$last_mattress = 0;
		}
		// if (isset($input['tpp_mat_keep_wastage'])) {
		// 	$tpp_mat_keep_wastage = (int)$input['tpp_mat_keep_wastage'];
		// } else {
		// 	$tpp_mat_keep_wastage = 0;
		// }
		
		$location = $input['location'];
		
		if (isset($input['mattressProArray'])) {

			$mattressProArray = isset($input['mattressProArray']) ? json_decode($input['mattressProArray'], true) : [];
		    $markerPcsPerLayer = isset($input['markerPcsPerLayer']) ? json_decode($input['markerPcsPerLayer'], true) : [];
		    $selected_marker = isset($input['selected_marker']) ? $input['selected_marker'] : null;

		    // Extract line quantities (line-0, line-1, etc.)
		    $lineQuantities = [];
		    foreach ($input as $key => $value) {
		        if (preg_match('/^line-(\d+)$/', $key, $matches)) {
		            $lineIndex = (int)$matches[1]; // Extract the index
		            $lineQuantities[$lineIndex] = (int)$value; // Store the quantity
		        }
		    }
		    // dd($lineQuantities);

		    // Combine mattressProArray with lineQuantities
		    $structuredLines = [];
		    foreach ($mattressProArray as $index => $line) {
		        $parts = explode('#', $line);
		        $key = isset($parts[0]) ? $parts[0] : null;
		        $secondValue = isset($parts[1]) ? $parts[1] : null;
		        $thirdValue = isset($parts[2]) ? $parts[2] : null;

		        // Add quantity from lineQuantities
		        $structuredLines[] = [
		            'line_key' => $key,
		            'second_value' => $secondValue,
		            'third_value' => $thirdValue,
		            'quantity' => isset($lineQuantities[$index]) ? $lineQuantities[$index] : 0,
		        ];
		    }
		    // dd($structuredLines);

		    if (!empty($structuredLines)) {
			    // Log the whole structuredLines array before inserts
			    $logHeader = "\n[".Carbon::now()->toDateTimeString()."] structuredLines:\n";
			    $logContent = json_encode($structuredLines, JSON_PRETTY_PRINT);

			    File::append(storage_path('logs/mattress_pro_log.txt'), $logHeader.$logContent."\n");
			}

		    foreach ($structuredLines as $item) {
			    // Access each element

			    $style_size = $item['line_key'];
			    $pro_id = $item['second_value'];
			    $pro = $item['third_value'];
			    $pro_pcs_layer = (float)$item['quantity'];

			    $met_det = mattress_details::where('mattress_id','=',$id)->get();
			    $layers_a = $met_det[0]->layers_a;
			    // dd($layers_a);

			 	// $table1 = new mattress_pro;
				// $table1->mattress_id = $id;
				// $table1->mattress = $mattress;
				// $table1->style_size = $style_size;
				// $table1->pro_id = $pro_id;
				// $table1->pro_pcs_layer = $pro_pcs_layer;
				// $table1->pro_pcs_planned = $table1->pro_pcs_layer * (float)$layers_a;
				// $table1->pro_pcs_actual = $table1->pro_pcs_layer * (float)$layers_a;
				// $table1->save();

			    // Check if row already exists
			    $existing = mattress_pro::where([
			        'mattress_id' => $id,
			        'style_size'  => $style_size,
			        'pro_id'      => $pro_id,
			    ])->first();

				// Insert or update
			    $table1 = mattress_pro::updateOrCreate(
			        [		
			            'mattress_id' => $id,
			            'style_size'  => $style_size,
			            'pro_id'      => $pro_id,
			        ],
			        [
			            'mattress'        => $mattress,
			            'pro_pcs_layer'   => $pro_pcs_layer,
			            'pro_pcs_planned' => $pro_pcs_layer * (float)$layers_a,
			            'pro_pcs_actual'  => $pro_pcs_layer * (float)$layers_a,
			        ]
			    );

			    // Determine action for log
    			$action = $existing ? 'Updated' : 'Inserted';
				
				// Build log line
			    $logLine = sprintf(
			        "[%s] %s: mattress_id=%s, mattress=%s, style_size=%s, pro_id=%s, pcs_layer=%s, pcs_planned=%s, pcs_actual=%s\n",
			        Carbon::now()->toDateTimeString(),
			        $action,
			        $table1->mattress_id,
			        $table1->mattress,
			        $table1->style_size,
			        $table1->pro_id,
			        $table1->pro_pcs_layer,
			        $table1->pro_pcs_planned,
			        $table1->pro_pcs_actual
			    );

			    // Save log
			    File::append(storage_path('logs/mattress_pro_log.txt'), $logLine);

			}
		}
		
		// dd('stop');

		// Gbin check !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		// $g_bin = 'G000000001';

		// dd($g_bin);

		if ($skeda_item_type == 'MM') {
			$g_bin = NULL;
		} else {
			if ($g_bin == '') {
				// Gordon
			
				$find_g_bin = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						TOP 1 g_bin
					FROM [mattresses] ORDER BY g_bin desc"));
				// dd($find_g_bin);
				if (isset($find_g_bin[0])) {
					if ($find_g_bin[0]->g_bin == NULL) {
						$bin = 29999;			
					} else {
						$bin = (int)substr($find_g_bin[0]->g_bin, -8);	
					}
				}
				
				// dd($bin);
				$num = str_pad($bin+1, 9, 0, STR_PAD_LEFT);
				// dd("G".$num);

				$g_bin = "G".$num;
				// dd($g_bin);

				$check_g_bin = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						TOP 1 g_bin
					FROM [mattresses] 
					WHERE g_bin = '".$g_bin."' "));

				if (isset($check_g_bin[0]->g_bin)) {
					dd('problem to create g_bin, try again');
				} 
				
			} else {
				// Bin was imported before
				$g_bin = $g_bin;
			}
		}
		// dd($g_bin);

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
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
			$table1->mandatory_to_ins = $mandatory_to_ins;
			$table1->last_mattress = $last_mattress;
			$table1->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd("Problem to save in mattress_details");
		}

		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
			SET NOCOUNT ON;
			UPDATE [mattress_phases]
			SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
			WHERE mattress_id = '".$id."' AND active = 1;
			SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;

		// save new mattress_phases
		$status = "TO_LOAD";
		$active = 1;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}
		
		// $find_position = mattress_details::where('mattress_id','=',$id)->get();
		// $pre_position = $find_position[0]->position;
		
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device;
		$table3_new->active = $active;
		$table3_new->operator1 = $operator;
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		// $table3_new->pre_position = $pre_position;
		$table3_new->save();

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
			
			$find_marker_style_size = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.marker_name,mh.marker_length,mh.marker_width,mh.status,
			   size = STUFF(
					(SELECT DISTINCT ' ' +CAST(ml.style_size as VARCHAR(MAX) 
					)+ CAST (ml.pcs_on_layer as VARCHAR(MAX))
					FROM [marker_lines] as ml
					WHERE ml.marker_header_id = mh.id 
					FOR XML PATH('')),1,1,' '
				)
			FROM [marker_headers] as mh
			--WHERE mh.[status] = 'ACTIVE' 
			WHERE mh.[status] = 'ACTIVE' OR mh.[status] = 'USELESS'
			ORDER BY mh.marker_width desc "));
			// dd($find_marker_style_size);

			$markers[] = '';
			for ($t=0; $t < count($find_marker_style_size); $t++) { 
				// print_r(trim($find_marker_style_size[$t]->size));

				if (trim($find_marker_style_size[$t]->size) == trim($existing_marker_style_size[0]->size)) {
					// print_r($find_marker_style_size[$t]->marker_name."<br>");
					array_push($markers, $find_marker_style_size[$t]->marker_name.' '.round($find_marker_style_size[$t]->marker_length,3).'->'.$find_marker_style_size[$t]->status);
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
		$selected_marker_full = $input['selected_marker'];
		// dd($selected_marker);

		$selected_marker_array = explode(' ',$selected_marker_full);
		$selected_marker = $selected_marker_array[0];

		$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					id, marker_name, marker_width, marker_length, min_length
				FROM [marker_headers] 
				WHERE marker_name = '".$selected_marker."'  AND status = 'ACTIVE' "));
		if (!isset($markers[0]->id)) {
			
			$msg = 'Selected marker has the status USELESS, please choose a marker with lower width.';
			return view('planner.error',compact('msg'));

			// dd('Selected marker has the status USELESS, please choose a marker with lower width.');
		}
		// dd('marker is fine');

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
		$table2->printed_marker = 0;
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
			SELECT TOP 1 mattress,location,device  FROM [mattress_phases] WHERE mattress_id = '".$id."' ORDER BY id desc;
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;
		$device = $mattress_phases_not_active[0]->device;
		$location = $mattress_phases_not_active[0]->location;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		$status = "TO_LOAD";

		// $find_position = mattress_details::where('mattress_id','=',$id)->get();
		// $pre_position = $find_position[0]->position;
		
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = 1;
		$table3_new->operator1 = $operator;
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		// $table3_new->pre_position = $pre_position;
		$table3_new->save();

		return Redirect::to('/plan_mattress/BOARD');
	}

	public function split_mattress($id) {
		// dd($id);

		$find_marker = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				msr.[requested_width] ,msr.[requested_length], msr.[mattress_id_orig], msr.[mattress_orig], msr.[g_bin_orig], msr.[marker_name_orig], msr.[marker_id_orig], msr.[comment_operator]
				,msr.[marker_width], msr.[marker_length]
				/*,mm.[marker_name], mm.[marker_id], mm.[mattress], mm.[id], mm.[marker_length], mm.[marker_width], mm.[min_length]*/
				,m.[width_theor_usable], m.[g_bin]
			FROM [mattress_split_requests] as msr
			JOIN [mattress_markers] as mm ON mm.[mattress_id] = msr.[mattress_id_orig]
			JOIN [mattresses] as m ON m.[id] = mm.[mattress_id]
			WHERE msr.[id] = '".(int)$id."' "));
		// dd($find_marker);

		if (!isset($find_marker[0])) {
			dd('Marker does not exist');
		}

		$mattress_id_orig = $find_marker[0]->mattress_id_orig;
		$mattress_orig = $find_marker[0]->mattress_orig;
		$g_bin_orig = $find_marker[0]->g_bin_orig;

		$marker_name_orig = $find_marker[0]->marker_name_orig;
		$marker_id_orig = $find_marker[0]->marker_id_orig;
		$marker_width = $find_marker[0]->marker_width;
		$marker_length = $find_marker[0]->marker_length;

		$requested_width = $find_marker[0]->requested_width;
		$requested_length = $find_marker[0]->requested_length;
		$width_theor_usable = $find_marker[0]->width_theor_usable;

		$comment_operator = $find_marker[0]->comment_operator;

		$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT [id],[marker_name],[status]
  			FROM [marker_headers]
  			WHERE [status] = 'ACTIVE' or  [status] = 'USELESS' "));


		return view('planner.split_mattress',compact('id','mattress_id_orig','mattress_orig','g_bin_orig','marker_name_orig', 
				'marker_id_orig','marker_width','marker_length','requested_width','requested_length','comment_operator'
				,'markers','width_theor_usable'));
	}

	public function split_mattress_post(Request $request) {

		$this->validate($request, ['selected_marker'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$id = $input['id'];
		$layers_insert = (int)$input['layers'];
		$selected_marker = (int)$input['selected_marker'];

		$split_request = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				msr.[requested_width] ,msr.[requested_length], msr.[mattress_id_orig], msr.[mattress_orig], msr.[g_bin_orig], msr.[marker_name_orig], msr.[marker_id_orig], msr.[comment_operator]
				,msr.[marker_width], msr.[marker_length]
				/*,mm.[marker_name], mm.[marker_id], mm.[mattress], mm.[id], mm.[marker_length], mm.[marker_width], mm.[min_length]*/
				,m.[width_theor_usable], m.[g_bin]
			FROM [mattress_split_requests] as msr
			JOIN [mattress_markers] as mm ON mm.[mattress_id] = msr.[mattress_id_orig]
			JOIN [mattresses] as m ON m.[id] = mm.[mattress_id]
			WHERE msr.[id] = '".(int)$id."' "));
		// dd($split_request);

		$mattress_id_orig = $split_request[0]->mattress_id_orig;
		// dd($mattress_id_orig);
		$mattress_orig = $split_request[0]->mattress_orig;
		$g_bin_orig = $split_request[0]->g_bin_orig;

		$marker_name_orig = $split_request[0]->marker_name_orig;
		$marker_id_orig = $split_request[0]->marker_id_orig;
		$marker_width = $split_request[0]->marker_width;
		$marker_length = $split_request[0]->marker_length;

		$requested_width = $split_request[0]->requested_width;
		$requested_length = $split_request[0]->requested_length;

		$comment_operator = $split_request[0]->comment_operator;

		$orig_mattress =  DB::connection('sqlsrv')->select(DB::raw("SELECT 
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
		      ,m2.[tpa_number]
		      ,m2.[overlapping]
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
		      -- ,ms.[g_bin_orig]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  --JOIN [o_rolls] as o ON o.[mattress_id_orig] = m1.[id]
		  WHERE m1.[id] = '".$mattress_id_orig."' AND m4.active = '1'
		  ORDER BY m2.position asc"));
		// dd($orig_mattress);

		$test = explode("-", $orig_mattress[0]->mattress);
		// dd($test);
		$mattress_to_search = $test[0]."-".$test[1]."-".$test[2]."-E";
		// dd($mattress_to_search);

		$last_e_used =  DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 mattress FROM  [mattresses]
  				WHERE mattress like '".$mattress_to_search."%'
  				ORDER BY id desc"));
		// dd($last_e_used);

		if (!isset($last_e_used[0])) {
			$last_e_used = 0;
		} else {
			$last_e_used = (int)substr($last_e_used[0]->mattress, -2);
		}
		$last_e_used = str_pad($last_e_used+1, 2, 0, STR_PAD_LEFT);

		$mattress_new = $mattress_to_search."".$last_e_used;
		// dd($mattress_new);

		$marker_new = DB::connection('sqlsrv')->select(DB::raw("SELECT id, marker_name, marker_length, marker_width, min_length
			FROM marker_headers
			WHERE status = 'ACTIVE' and id = '".$selected_marker."' "));
		// dd($marker_new);

		if (!isset($marker_new[0]->id)) {
			// dd('greska');
			$msg = 'Selected marker has the status USELESS, please choose a marker with lower width.';
			return view('planner.error',compact('msg'));
		}
		

		// ----
		$marker_id = $marker_new[0]->id;
		$marker_name = $marker_new[0]->marker_name;
		$marker_length = $marker_new[0]->marker_length;
		$marker_width = $marker_new[0]->marker_width;
		$min_length = $marker_new[0]->min_length;
		// ----

		$g_bin = "";
		$material = $orig_mattress[0]->material;
		$dye_lot = $orig_mattress[0]->dye_lot;
		$color_desc = $orig_mattress[0]->color_desc;
		$width_theor_usable = round((float)$orig_mattress[0]->width_theor_usable,2);
		$skeda = $orig_mattress[0]->skeda;
		$skeda_item_type = $orig_mattress[0]->skeda_item_type;
		$skeda_status = $orig_mattress[0]->skeda_status;
		$spreading_method = $orig_mattress[0]->spreading_method;
		
		$layers = (float)$layers_insert;
		$layers_a = $layers;
		$length_mattress = (int)$orig_mattress[0]->length_mattress;
		$cons_planned = $layers_a * ($marker_length + ((int)$orig_mattress[0]->extra/100));
		$cons_actual = $cons_planned;
		// dd($cons_actual);

		$extra = $orig_mattress[0]->extra;
		$pcs_bundle = $orig_mattress[0]->pcs_bundle; 
		$layers_partial; 
		$priority = 1;
		$call_shift_manager = 0;
		$test_marker = 0;
		$tpp_mat_keep_wastage = $orig_mattress[0]->tpp_mat_keep_wastage;
		$printed_marker = 0;
		$mattress_packed = 0;
		$all_pro_for_main_plant = 0; 
		$tpa_number = $orig_mattress[0]->tpa_number;

		$bottom_paper = $orig_mattress[0]->bottom_paper;
		$overlapping = $orig_mattress[0]->overlapping;

		//-----
		$status = 'NOT_SET';
		$location = 'NOT_SET'; 
		$device = ''; 
		$active = 1;
		$operator1 = Session::get('operator');
		$operator2;
		//-----

		// find position
		$find_position_on_location = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					COUNT(location) as c 
				FROM [mattress_phases] WHERE location = 'NOT_SET' AND active = '1' "));
		// dd($find_position_on_location[0]);
		if (isset($find_position_on_location[0])) {
			$position = $find_position_on_location[0]->c + 1;
		} else {
			$position = 1;
		}

		$find_in_mattress_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM mattress_pros WHERE mattress = '".$mattress_new."' "));
		if (isset($find_in_mattress_pro[0])) {
				
   			$msg = "Mattress '".$mattress."' already exist in mattress_pros!";
   			dd($msg);
		} else {

			$find_in_marker_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT style_size, pcs_on_layer FROM marker_lines WHERE marker_name = '".$marker_new[0]->marker_name."' "));
			// dd($find_in_marker_lines);
			
   			$mattress_pro_array[] = '';

	   		foreach ($find_in_marker_lines as $line) {
	   			$style_size = $line->style_size;
	   			$pro_pcs_layer = (float)$line->pcs_on_layer;

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

		$m_error = 0;

		try {
			$table0 = new mattress;
			$table0->mattress = $mattress_new;
			$table0->g_bin;
			$table0->material = $material;
			$table0->dye_lot = $dye_lot;
			$table0->color_desc = $color_desc;
			$table0->width_theor_usable = $width_theor_usable;			
			$table0->skeda = $skeda;
			$table0->skeda_item_type = $skeda_item_type;
			$table0->skeda_status = $skeda_status;
			$table0->spreading_method = $spreading_method;
			$table0->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			
			//error
			$m_error = $m_error + 1;

			dd("Problem to save in mattresses");
			continue;
		}

		try {
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
			$table1->bottom_paper = $bottom_paper;
			$table1->layers_a_reasons;
			$table1->comment_office;
			$table1->comment_operator;
			$table1->minimattress_code;
			$table1->overlapping = $overlapping;
			$table1->layer_limit;
			$table1->tpa_number = $tpa_number;
			$table1->save();

		}
		catch (\Illuminate\Database\QueryException $e) {
			//error
			$m_error = $m_error + 1;
			$delete = mattress::where('id', $table0->id)->delete();

			dd("Problem to save in mattress_details");
			continue;
		}

		try {
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
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			//error
			$m_error = $m_error + 1;

			$delete = mattress::where('id', $table0->id)->delete();
			$delete = mattress_details::where('mattress_id', $table0->id)->delete();

			dd("Problem to save in mattress_markers");
			continue;
		}

		try {
			$table3 = new mattress_phases;
			$table3->mattress_id = $table0->id;
			$table3->mattress = $table0->mattress;
			$table3->status = $status;
			$table3->location = $location;
			$table3->device;
			$table3->active = $active;
			$table3->operator1 = Session::get('operator');
			$table3->operator2;
			$table3->date = date('Y-m-d H:i:s');
			$table3->id_status = $table0->id.'-'.$status;
			$table3->save();
		}
		catch (\Illuminate\Database\QueryException $e) {

			//error
			$m_error = $m_error + 1;

			$delete = mattress::where('id', $table0->id)->delete();
			$delete = mattress_details::where('mattress_id', $table0->id)->delete();
			$delete = mattress_markers::where('mattress_id', $table0->id)->delete();

			dd("Problem to save in mattress_phases");
			continue;
		}

		$mattress_pro_array = array_filter($mattress_pro_array);
		// dd($mattress_pro_array);

		for ($i=1; $i <= count($mattress_pro_array) ; $i++) {
			// print_r('i: '.$i);
			// print_r('<br>');
			// print_r($mattress_pro_array[$i]);
			// print_r('<br>');

			$info = explode("#", $mattress_pro_array[$i]);
			// dd($info[0]);

			try {
				$table4 = new mattress_pro;
				$table4->mattress_id = $table0->id;
				$table4->mattress = $table0->mattress;
				$table4->style_size = $info[1];
				$table4->pro_id = $info[0];
				$table4->pro_pcs_layer = (float)$info[2];
				$table4->pro_pcs_planned = $table4->pro_pcs_layer * (float)$layers_a;
				$table4->pro_pcs_actual = $table4->pro_pcs_layer * (float)$layers_a;
				$table4->damaged_pcs = 0;
				$table4->save();

			}
			catch (\Illuminate\Database\QueryException $e) {
				
				//error
				$m_error = $m_error + 1;

				$delete = mattress::where('id', $table0->id)->delete();
				$delete = mattress_details::where('mattress_id', $table0->id)->delete();
				$delete = mattress_markers::where('mattress_id', $table0->id)->delete();
				$delete = mattress_phases::where('mattress_id', $table0->id)->delete();

				dd("Problem to save in mattress_pros");
				continue;
			}
		}

		if ($m_error == 0) {

			$table_update = mattress_split_request::findOrFail($input['id']);
			$table_update->mattress_id_new = $table0->id;
			$table_update->mattress_new = $table0->mattress;
			$table_update->layers = (float)$layers_insert;
			$table_update->status = "SPLITTED";
			$table_update->save();

		} else {
			dd("Error");
		}

		return Redirect::to('/plan_mattress/TO_SPLIT');
	}

	public function split_mattress_delete($id) {

		return view('planner.split_mattress_delete_confirm', compact('id'));
	}

	public function split_mattress_delete_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$table_update = mattress_split_request::findOrFail($input['id']);
		$table_update->status = "DELETED";
		$table_update->save();

		return Redirect::to('/plan_mattress/TO_SPLIT');
	}

	public function change_marker_all($id) {
		// dd($id);

		$find_marker = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mm.[marker_name], mm.[marker_id], mm.[mattress], mm.[id], mm.[marker_length], mm.[marker_width], mm.[min_length],
				md.[requested_width],md.[requested_length],md.[id] as mattress_details_id,
				m.[width_theor_usable], m.[g_bin]
				,(SELECT TOP 1 
						LEFT(style_size, CHARINDEX(' ', style_size + ' ') - 1) AS style
					FROM [mattress_pros]
					WHERE mattress_id = mm.mattress_id
					) as style
			FROM [mattress_markers] as mm
			JOIN [mattress_details] as md ON md.[mattress_id] = mm.[mattress_id]
			JOIN [mattresses] as m ON m.[id] = mm.[mattress_id]
			WHERE mm.[mattress_id] = ".(int)$id." "));

		// dd($find_marker);

		if ((!isset($find_marker[0]->marker_name)) OR ($find_marker[0]->marker_name != '')) {
			
			$existing_marker = $find_marker[0]->marker_name;
			$existing_marker_id = $find_marker[0]->marker_id;
			$existing_mattress_marker_id = $find_marker[0]->id;
			$mattress_details_id = $find_marker[0]->mattress_details_id;
			$existing_marker_length = $find_marker[0]->marker_length;
			$existing_marker_width = $find_marker[0]->marker_width;
			$requested_width = $find_marker[0]->requested_width;
			$requested_length = $find_marker[0]->requested_length;
			$width_theor_usable = $find_marker[0]->width_theor_usable;
			$existing_min_length = $find_marker[0]->min_length;
			$mattress = $find_marker[0]->mattress;
			$g_bin = $find_marker[0]->g_bin;
			$style = $find_marker[0]->style;

			// marker list
			$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT
				CONCAT(mh.marker_name, ' ', mh.marker_width, ' ', mh.marker_length, ' -> ', mh.status) AS marker
				FROM [marker_headers] as mh
				JOIN [marker_lines] as ml ON ml.marker_header_id = mh.id 
				WHERE (mh.[status] = 'ACTIVE' OR mh.[status] = 'USELESS')
				AND ml.style = '".$style."' 
				GROUP BY
					mh.marker_name,mh.marker_width,mh.marker_length, mh.status "
			));
			// dd($markers);

			// $markers = (object)(array_filter($markers));
			// $markers = array_filter($markers);
			// dd($markers);

			// print_r(array_filter($markers));
			// dd("stop");
			return view('planner.change_marker_all',compact('id','mattress','g_bin','existing_marker','existing_mattress_marker_id', 
				'existing_marker_id', 'mattress_details_id' ,'existing_marker_length', 'existing_marker_width','markers', 'requested_width', 'requested_length' ,'style','width_theor_usable', 'existing_min_length'));

		} else {
			$msg = 'Marker is not liked with mattress!';
			return view('planner.error',compact('msg'));
		}
	}

	public function change_marker_all_post(Request $request) {

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
		// dd($mattress);
		$existing_marker = $input['existing_marker'];
		$existing_marker_id = $input['existing_marker_id'];
		$mattress_details_id = $input['mattress_details_id'];
		$existing_mattress_marker_id = $input['existing_mattress_marker_id'];
		$existing_marker_length = $input['existing_marker_length'];
		$existing_marker_width = $input['existing_marker_width'];
		$existing_min_length = $input['existing_min_length'];
		$selected_marker_full = $input['selected_marker'];
		$style = $input['style'];
		
		$selected_marker_array = explode(' ',$selected_marker_full);
		$selected_marker = $selected_marker_array[0];
		// dd($selected_marker);

		$find_skeda = mattress::findOrFail($id);
		$skeda = $find_skeda->skeda;
		// dd($skeda);

		
		$new_marker_reqs = marker_line::where('marker_name', $selected_marker)->get();
		$new_marker_reqSizes = [];
		foreach ($new_marker_reqs as $record) {
		    $new_marker_reqSizes = array_merge($new_marker_reqSizes, explode(',', $record->style_size));
		}
		$new_marker_reqSizes = array_unique($new_marker_reqSizes);
		// dd(['new_marker_reqSizes' => $new_marker_reqSizes]); // Ensure this is placed properly

		$skeda_req = pro_skeda::where('skeda', $skeda)->get();
		$skeda_reqSizes = [];
		foreach ($skeda_req as $record) {
		    $skeda_reqSizes = array_merge($skeda_reqSizes, explode(',', $record->style_size));
		}
		$skeda_reqSizes = array_unique($skeda_reqSizes);

		// dd(['new_marker_reqSizes' => $new_marker_reqSizes, 'skeda_reqSizes' => $skeda_reqSizes]);

		// Check if all sizes from the first table are found in the second table
		$missingSizes = array_diff($new_marker_reqSizes, $skeda_reqSizes);

		// If there are no missing sizes, the first table fits in the second table
		if (empty($missingSizes)) {
		    // dd('All style_size values from the new_marker_req can fit in the skeda_req');

		    $markers = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					id, marker_name, marker_width, marker_length, min_length
				FROM [marker_headers] 
				WHERE marker_name = '".$selected_marker."'  AND (status = 'ACTIVE' OR status = 'USELESS') "));
			// dd($markers);

			if (!isset($markers[0]->id)) {
				
				$msg = 'Selected marker has the status NOT ACTIVE.';
				return view('planner.error',compact('msg'));

				// dd('Selected marker has the status USELESS, please choose a marker with lower width.');
			}


			$find_in_marker_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT style_size, pcs_on_layer FROM marker_lines WHERE marker_name = '".$selected_marker."' "));
			// dd($find_in_marker_lines);

			$mattress_pro_array[] = '';
			$marker_pcs_per_layer[] = '';


			// Ask pro
			foreach ($find_in_marker_lines as $line) {
	   			$style_size = $line->style_size;
	   			$pro_pcs_layer = (float)$line->pcs_on_layer;

	   			array_push($marker_pcs_per_layer, $style_size.'#'.$pro_pcs_layer);


	   			$find_in_pro_skedas = DB::connection('sqlsrv')->select(DB::raw("SELECT pro_id,pro FROM pro_skedas WHERE skeda = '".$skeda."' AND style_size = '".$style_size."' "));
	   			// dd($find_in_pro_skedas);


	   			// multiple selection 
	   			
				if (!isset($find_in_pro_skedas[0])) {
	   				dd('can not find pro for ths mattress');
	   			}  else {
	   				// dd($find_in_pro_skedas);
		   			$pro_id = $find_in_pro_skedas[0]->pro_id;
		   			$pro = $find_in_pro_skedas[0]->pro;
		   			array_push($mattress_pro_array, $style_size.'#'.$pro_id.'#'.$pro);
				} 	
				
	   				   		
	   		}
	   		$marker_pcs_per_layer = array_filter($marker_pcs_per_layer);
	   		$mattress_pro_array = array_filter($mattress_pro_array);

	   	

			return view('planner.replace_conf',compact('id', 'mattress' , 'existing_marker', 'existing_marker_id','mattress_details_id' , 'existing_mattress_marker_id',
				'existing_marker_length','existing_marker_width','existing_min_length','existing_marker_width','existing_min_length',
				'selected_marker','style','skeda',
				'marker_pcs_per_layer','mattress_pro_array'
   			));


		} else {
			$msg ='Some style_size values from the new_marker_req do not fit in the skeda_req, missing '.implode(', ', $missingSizes).' ';
			return view('planner.error',compact('msg', 'operator', 'operators'));
		    // dd('Some style_size values from the new_marker_req do not fit in the skeda_req, missing '.implode(', ', $missingSizes).'');

		}

	}

	public function change_marker_all_post_check(Request $request) {

		// Validate the request inputs
	    $this->validate($request, [
	        'id' => 'required',
	        'mattressProArray' => 'required|string', // Expecting JSON-encoded string
	        'markerPcsPerLayer' => 'required|string', // Expecting JSON-encoded string
	    ]);

	    // Get all input data
	    $input = $request->all();
	    // dd($input);

	    // Decode the JSON strings into arrays
	    $mattressProArray = isset($input['mattressProArray']) ? json_decode($input['mattressProArray'], true) : [];
	    $markerPcsPerLayer = isset($input['markerPcsPerLayer']) ? json_decode($input['markerPcsPerLayer'], true) : [];

	    // Debug: Check the decoded arrays
	    // dd($mattressProArray, $markerPcsPerLayer);

	    // Extract line quantities (line-0, line-1, etc.)
	    $lineQuantities = [];
	    foreach ($input as $key => $value) {
	        if (preg_match('/^line-(\d+)$/', $key, $matches)) {
	            $lineIndex = (int)$matches[1]; // Extract the index
	            $lineQuantities[$lineIndex] = (int)$value; // Store the quantity
	        }
	    }

	    // Debug: Check lineQuantities after processing
	    // dd($lineQuantities);

	    // Combine mattressProArray with lineQuantities
	    $structuredLines = [];
	    foreach ($mattressProArray as $index => $line) {
	        $parts = explode('#', $line);
	        $key = isset($parts[0]) ? $parts[0] : null;
	        $secondValue = isset($parts[1]) ? $parts[1] : null;
	        $thirdValue = isset($parts[2]) ? $parts[2] : null;

	        // Add quantity from lineQuantities
	        $structuredLines[] = [
	            'line_key' => $key,
	            'second_value' => $secondValue,
	            'third_value' => $thirdValue,
	            'quantity' => isset($lineQuantities[$index]) ? $lineQuantities[$index] : 0,
	        ];
	    }

	    // Debug: Check final structured lines
	    // dd($structuredLines);

	    $id = $input['id'];
	    $mattress = isset($input['mattress']) ? $input['mattress'] : null;
	    $selected_marker = isset($input['selected_marker']) ? $input['selected_marker'] : null;
	    $existing_marker = isset($input['existing_marker']) ? $input['existing_marker'] : null;
	    $existing_marker_id = isset($input['existing_marker_id']) ? $input['existing_marker_id'] : null;
	    $mattress_details_id = isset($input['mattress_details_id']) ? $input['mattress_details_id'] : null;
	    $existing_mattress_marker_id = isset($input['existing_mattress_marker_id']) ? $input['existing_mattress_marker_id'] : null;
	    $existing_marker_length = isset($input['existing_marker_length']) ? $input['existing_marker_length'] : null;
	    $existing_marker_width = isset($input['existing_marker_width']) ? $input['existing_marker_width'] : null;
	    $existing_min_length = isset($input['existing_min_length']) ? $input['existing_min_length'] : null;
	    $skeda = isset($input['skeda']) ? $input['skeda'] : null;
	    
	    // dd($selected_marker);
	    $markers = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				id, marker_name, marker_width, marker_length, min_length
			FROM [marker_headers] 
			WHERE marker_name = '".$selected_marker."'  AND (status = 'ACTIVE' OR status = 'USELESS') 
		"));

		// save
		// dd('stop');

		$table4 = mattress_markers::findOrFail($existing_mattress_marker_id);
		$table4->mattress_id;
		$table4->mattress;
		$table4->marker_id = (int)$markers[0]->id;
		$table4->marker_name = $markers[0]->marker_name;
		// $table4->marker_name_orig = $input['existing_marker'];
		$table4->marker_width = round((float)$markers[0]->marker_width,3);
		$table4->marker_length = round((float)$markers[0]->marker_length,3);
		$table4->min_length = round((float)$markers[0]->min_length,3);
		$table4->save();
		// dd($table4);

		$table2 = mattress_details::findOrFail($mattress_details_id);
		$table2->cons_actual = $table2->layers_a * ((float)$markers[0]->marker_length + ($table2->extra/100));
		$table2->printed_marker = 0;
		$table2->printed_nalog = 0;
		$table2->save();
		// dd($table2);

		$deletedRows = mattress_pro::where('mattress_id', $id)->delete();
		foreach ($structuredLines as $item) {
		    // Access each element

		    $style_size = $item['line_key'];
		    $pro_id = $item['second_value'];
		    $pro = $item['third_value'];
		    $pro_pcs_layer = (float)$item['quantity'];

		    $table1 = new mattress_pro;
			$table1->mattress_id = $id;
			$table1->mattress = $mattress;
			$table1->style_size = $style_size;
			$table1->pro_id = $pro_id;
			$table1->pro_pcs_layer = $pro_pcs_layer;
			$table1->pro_pcs_planned = $table1->pro_pcs_layer * (float)$table2->layers_a;
			$table1->pro_pcs_actual = $table1->pro_pcs_layer * (float)$table2->layers_a;
			$table1->save();
			// dd($table1);
		}

		// dd('stop');
		// add to marker log
		$table7_new = new marker_change;
		$table7_new->mattress_id = $table4->mattress_id;
		$table7_new->mattress = $table4->mattress;
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
		// dd($table7_new);

		// dd('stop');
		// change mattress_phases
		$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
			SET NOCOUNT ON;
			UPDATE [mattress_phases]
			SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
			WHERE mattress_id = '".$id."' AND active = 1;
			SELECT TOP 1 mattress,location,device  FROM [mattress_phases] WHERE mattress_id = '".$id."' ORDER BY id desc;
		"));
		$mattress = $mattress_phases_not_active[0]->mattress;
		$device = $mattress_phases_not_active[0]->device;
		$location = $mattress_phases_not_active[0]->location;

		$date = date('Y-m-d H:i:s');
		$status = "TO_CUT";

		// $find_position = mattress_details::where('mattress_id','=',$id)->get();
		// $pre_position = $find_position[0]->position;

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
		
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device = $device;
		$table3_new->active = 1;
		$table3_new->operator1;
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		// $table3_new->pre_position = $pre_position;
		$table3_new->save();

		return Redirect::to('/plan_mattress/TO_CHANGE');

	}

	public function edit_mattress($id) {
		
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
		      ,m2.[req_time]
		      ,m2.[mandatory_to_ins]
		      ,m2.[cutter_shrink_x]
      		  ,m2.[cutter_shrink_y]
      		  ,m2.[last_mattress]
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
		  WHERE m1.[id] = '".$id."' AND m4.active = '1' 
		  ORDER BY m2.position asc"));
		// dd($data);
		
		$data_sp = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		      m4.[status]
		      ,m4.[location]
		      ,m4.[device]
		      ,m4.[active]
		      ,m4.[operator1]
		      ,m4.[operator2]
		      ,m4.[updated_at]

		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  WHERE m1.[id] = '".$id."' AND status = 'TO_CUT'
		  ORDER BY m4.updated_at desc"));
		// dd($data_sp);

		if (isset($data_sp[0])) {
			$sp_operator = $data_sp[0]->operator1;
			$sp_date = substr($data_sp[0]->updated_at,0,16);	
		} else {
			$sp_operator = NULL;
			$sp_date = NULL;
		}

		$data_cut = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		      m4.[status]
		      ,m4.[location]
		      ,m4.[device]
		      ,m4.[active]
		      ,m4.[operator1]
		      ,m4.[operator2]
		      ,m4.[updated_at]

		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  WHERE m1.[id] = '".$id."' AND (status = 'COMPLETED' OR status = 'TO_PACK')
		  ORDER BY m4.updated_at asc"));
		// dd($data_cut);

		if (isset($data_cut[0])) {
			$cut_operator = $data_cut[0]->operator1;
			$cut_date = substr($data_cut[0]->updated_at,0,16);	
		} else {
			$cut_operator = NULL;
			$cut_date = NULL;
		}
		
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
		$layers_a = $data[0]->layers_a;
		$cons_planned = $data[0]->cons_planned;
		$cons_actual = $data[0]->cons_actual;
		$marker_name = $data[0]->marker_name;
		$marker_length = $data[0]->marker_length;
		$marker_width = $data[0]->marker_width;
		$pcs_bundle = $data[0]->pcs_bundle;
		$priority = $data[0]->priority;
		$call_shift_manager = $data[0]->call_shift_manager;
		$mandatory_to_ins = $data[0]->mandatory_to_ins;
		$cutter_shrink_x = $data[0]->cutter_shrink_x;
		$cutter_shrink_y = $data[0]->cutter_shrink_y;
		$last_mattress = $data[0]->last_mattress;
		// $call_shift_manager = 1;
		$test_marker = $data[0]->test_marker;
		$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;
		$bottom_paper = $data[0]->bottom_paper;
		$comment_office = $data[0]->comment_office;
		$tpa_number = $data[0]->tpa_number;
		$location = $data[0]->location;
		$status = $data[0]->status;
		$req_time = $data[0]->req_time;

		$layer_limit = $data[0]->layer_limit;

		if ($skeda_item_type == 'MM') {

			$layer_limit = $data[0]->layer_limit;
			$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[no_of_joinings],[g_bin]
				FROM  [o_rolls]
				WHERE mattress_id_new = '".$id."' "));
			return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc',
				'skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a','cons_planned',
				'cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager',
				'test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office','location','data2',
				'layer_limit','cut_operator','cut_date','sp_operator','sp_date','req_time','mandatory_to_ins',
				'cutter_shrink_x','cutter_shrink_y','last_mattress','status'));
		}

		return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc',
				'skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a','cons_planned',
				'cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager',
				'test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office','location',
				'cut_operator','cut_date','sp_operator','sp_date','req_time','layer_limit','mandatory_to_ins',
				'cutter_shrink_x','cutter_shrink_y','last_mattress','status'));
	}

	public function correct_location($id) {

		// dd($id);
		$mattress_phases = DB::connection('sqlsrv')->select(DB::raw("SELECT id, status, location FROM [mattress_phases] WHERE [mattress_id] = '".$id."' AND active  = 1 "));
		// dd($mattress_phases);

		if (isset($mattress_phases[0]->id)) {
			
			$status = $mattress_phases[0]->status;

			if ($status == 'NOT_SET') {
				$location = 'NOT_SET';

			} elseif ($status == 'TO_CUT') {
				$location = 'CUT';	
				
			} elseif ($status == 'ON_CUT') {
				$location = 'CUT';	

			} elseif ($status == 'TO_JOIN') {
				$location = 'PSO';	

			} elseif ($status == 'TO_PACK') {
				$location = 'PACK';	

			} elseif ($status == 'COMPLETED') {
				$location = 'COMPLETED';	
			} else {
				dd('Nothing to change, status is different than (NOT_SET, TO_CUT, ON_CUT, TO_JOIN, TO_PACK, COMPLETED).  ');
			}

			// dd($location);

			$mattress_phases_update = mattress_phases::findOrFail($mattress_phases[0]->id);
			$mattress_phases_update->location = $location;
			$mattress_phases_update->save();

			dd('Status was updated successfuly');

		} else {

			dd('Mattress doesn’t have active=1 phase line, check with IT');
		}
	}

	public function edit_mattress_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$mattress = $input['mattress'];
		$skeda_item_type = $input['skeda_item_type'];
		$g_bin = $input['g_bin'];
		$material = $input['material'];
		$dye_lot = $input['dye_lot'];
		$color_desc = $input['color_desc'];
		$skeda = $input['skeda'];
		$width_theor_usable = $input['width_theor_usable'];
		$layers = $input['layers'];
		$layers_a = $input['layers_a'];
		$cons_planned = $input['cons_planned'];
		$cons_actual = $input['cons_actual'];
		$marker_name = $input['marker_name'];
		$marker_length = $input['marker_length'];
		$marker_width = $input['marker_width'];
		$tpp_mat_keep_wastage = $input['tpp_mat_keep_wastage'];
		$tpa_number = $input['tpa_number'];

		$priority = (int)$input['priority'];
		$comment_office = $input['comment_office'];
		$spreading_method = $input['spreading_method'];
		$pcs_bundle = (int)$input['pcs_bundle'];
		$bottom_paper = $input['bottom_paper'];
		$req_time = round($input['req_time'],2);

		$location = $input['location'];

		$layer_limit = $input['layer_limit'];

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
		if (isset($input['last_mattress'])) {
			$last_mattress = (int)$input['last_mattress'];
		} else {
			$last_mattress = 0;
		}
		// if (isset($input['tpp_mat_keep_wastage'])) {
		// 	$tpp_mat_keep_wastage = (int)$input['tpp_mat_keep_wastage'];
		// } else {
		// 	$tpp_mat_keep_wastage = 0;
		// }
		
		$data_sp = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		      m4.[status]
		      ,m4.[location]
		      ,m4.[device]
		      ,m4.[active]
		      ,m4.[operator1]
		      ,m4.[operator2]
		      ,m4.[updated_at]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  WHERE m1.[id] = '".$id."' AND status = 'TO_CUT'
		  ORDER BY m4.updated_at desc"));
		// dd($data_sp);

		if (isset($data_sp[0])) {
			$sp_operator = $data_sp[0]->operator1;
			$sp_date = substr($data_sp[0]->updated_at,0,16);	
		} else {
			$sp_operator = NULL;
			$sp_date = NULL;
		}

		$data_cut = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		      m4.[status]
		      ,m4.[location]
		      ,m4.[device]
		      ,m4.[active]
		      ,m4.[operator1]
		      ,m4.[operator2]
		      ,m4.[updated_at]
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  --LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id]
		  WHERE m1.[id] = '".$id."' AND (status = 'COMPLETED' OR status = 'TO_PACK')
		  ORDER BY m4.updated_at asc"));
		// dd($data_cut);

		if (isset($data_cut[0])) {
			$cut_operator = $data_cut[0]->operator1;
			$cut_date = substr($data_cut[0]->updated_at,0,16);
			
		} else {
			$cut_operator = NULL;
			$cut_date = NULL;

		}


	
		$table001 = mattress::findOrFail($id);
		$table001->spreading_method = $spreading_method;
		$table001->save();

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_details] WHERE [mattress_id] = '".$id."' "));
		// dd($find_details_id[0]->id);
		
		$table1 = mattress_details::findOrFail($find_details_id[0]->id);
		$table1->pcs_bundle = $pcs_bundle;
		// $table1->position = $position;
		$table1->priority = $priority;
		$table1->call_shift_manager = $call_shift_manager;
		$table1->test_marker = $test_marker;
		// $table1->tpp_mat_keep_wastage = $tpp_mat_keep_wastage;
		$table1->bottom_paper = $bottom_paper;
		if ($comment_office == '') {
			$table1->comment_office = '';
		} else if ($table1->comment_office != $comment_office) {
			$table1->comment_office = Session::get('operator').'->'.$comment_office;
		}
		$table1->req_time = $req_time;
		$table1->last_mattress = $last_mattress;

		$mandatory_to_ins = $table1->mandatory_to_ins;
		$cutter_shrink_x = $table1->cutter_shrink_x;
		$cutter_shrink_y = $table1->cutter_shrink_y;
		
		$table1->save();

		if (isset($input['location_new'])) {
			$location_new = $input['location_new'];
			
			if ($location_new != $location) {
				
				$location_before = $location;
				$location = $location_new;
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

				// $table00 = mattress::findOrFail($id);
				// $table00->g_bin = $g_bin;
				// $table00->spreading_method = $spreading_method;
				// $table00->save();

				$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_details] WHERE [mattress_id] = '".$id."' "));
				// dd($find_details_id[0]->id);

				try {
					$table1 = mattress_details::findOrFail($find_details_id[0]->id);
					$table1->position = $position;
					$table1->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("Problem to save in mattress_details");
				}

				$mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
					SET NOCOUNT ON;
					UPDATE [mattress_phases]
					SET active = 0, id_status = ''+cast([mattress_id] as varchar )+'-'+[status]
					WHERE mattress_id = '".$id."' AND active = 1;
					SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
				"));
				$mattress = $mattress_phases_not_active[0]->mattress;

				// save new mattress_phases
				$status = "TO_LOAD";
				$active = 1;

				if ((date('H') >= 0) AND (date('H') < 6)) {
				   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
				} else {
					$date = date('Y-m-d H:i:s');
				}
				
				// $find_position = mattress_details::where('mattress_id','=',$id)->get();
				// $pre_position = $find_position[0]->position;
				
				$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
				$table3_new->mattress_id = $id;
				$table3_new->mattress = $mattress;
				$table3_new->status = $status;
				$table3_new->location = $location;
				$table3_new->device;
				$table3_new->active = $active;
				$table3_new->operator1 = Session::get('operator');
				$table3_new->operator2;
				$table3_new->date = $date;
				$table3_new->id_status = $id.'-'.$status;
				// $table3_new->pre_position = $pre_position;
				$table3_new->save();

				// reorder position of location_before
				$reorder_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						md.[id], md.[mattress_id], md.[mattress], md.[position], 
						mp.[location], mp.[active]
					 FROM [mattress_details] as md
					 INNER JOIN [mattress_phases] as mp ON mp.[mattress_id] = md.[mattress_id] AND mp.[active] = 1
					 WHERE location = '".$location_before."' 
					 ORDER BY position asc"));

				if (isset($reorder_position[0])) {
					for ($i=0; $i < count($reorder_position); $i++) { 

						$table1 = mattress_details::findOrFail($reorder_position[$i]->id);
						$table1->position = $i+1;
						$table1->save();
					}
				}

			}
		}

		return Redirect::to('/plan_mattress/'.$location);
		// return Redirect::to('/plan_mattress/BOARD');
		
		// $msgs = 'Succesfuly saved';
		/*
		return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc','skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a',
		'cons_planned','cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority',
		'call_shift_manager','test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper',
		'comment_office','location','cut_operator','cut_date','sp_operator','sp_date','req_time','msgs','layer_limit'));
		*/

		// if ($skeda_item_type == 'MM') {

		// 	$layer_limit = $data[0]->layer_limit;
		// 	$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[no_of_joinings],[g_bin]
		// 		FROM  [o_rolls]
		// 		WHERE mattress_id_new = '".$id."' "));
		// 	return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc',
		// 		'skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a','cons_planned',
		// 		'cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager',
		// 		'test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office','location','data2',
		// 		'layer_limit','cut_operator','cut_date','sp_operator','sp_date','req_time','mandatory_to_ins',
		// 		'cutter_shrink_x','cutter_shrink_y','last_mattress','msgs','mandatory_to_ins'));
		// }

		// return view('planner.edit_mattress_line', compact( 'id','mattress','g_bin','material','dye_lot','color_desc',
		// 		'skeda','skeda_item_type','spreading_method','width_theor_usable','layers','layers_a','cons_planned',
		// 		'cons_actual','marker_name','marker_length','marker_width','pcs_bundle','priority','call_shift_manager',
		// 		'test_marker','tpp_mat_keep_wastage','tpa_number','bottom_paper','comment_office','location',
		// 		'cut_operator','cut_date','sp_operator','sp_date','req_time','layer_limit','mandatory_to_ins',
		// 		'cutter_shrink_x','cutter_shrink_y','last_mattress','msgs','mandatory_to_ins'));

	}

	public function edit_layers_a($id) {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			   m2.[position]
			  ,m1.[id]
		      ,m1.[mattress]
		      ,m1.[g_bin]
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

		$layers_a = $data[0]->layers_a;
		$mattress = $data[0]->mattress;
		$g_bin = $data[0]->g_bin;

		$data_sp = DB::connection('sqlsrv')->select(DB::raw("SELECT 
		     m4.[status]
		      ,m4.[location]
		      ,m4.[device]
		      ,m4.[active]
		      --,m4.[operator1]
		      --,m4.[operator2]
		      ,m4.[updated_at]
		      ,m5.[operator_after]
		      ,m5.[operator2_after]
		      ,m5.[layers_after_cs]
		      ,m5.[operator_before]
		      ,m5.[operator2_before]
		      ,m5.[layers_before_cs]

		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id]
		  LEFT JOIN [mattress_effs]	   as m5 ON m5.[mattress_id] = m1.[id]
		  WHERE m1.[id] = '".$id."' AND status = 'TO_CUT'
		  ORDER BY m4.updated_at desc"));
		// dd($data_sp);

		if (isset($data_sp[0])) {

			$operator_after = $data_sp[0]->operator_after;
			$operator2_after = $data_sp[0]->operator2_after;
			$layers_after_cs = $data_sp[0]->layers_after_cs;

			$operator_before = $data_sp[0]->operator_before;
			$operator2_before = $data_sp[0]->operator2_before;
			$layers_before_cs = $data_sp[0]->layers_before_cs;
		} else {
			$operator_after = '';
			$operator2_after = '';
			$layers_after_cs = '';

			$operator_before = '';
			$operator2_before = '';
			$layers_before_cs = '';
		}

		return view('planner.edit_layers_a', compact( 'id','mattress','g_bin','layers_a','operator_after','operator2_after','layers_after_cs','operator_before','operator2_before','layers_before_cs'));
	}

	public function edit_layers_a_confirm(Request $request){
			
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$layers_a_new = (int)$input['layers_a_new'];
		$id = $input['id'];
		$mattress = $input['mattress'];
		$g_bin = $input['g_bin'];

		// if ($layers_a_new != ($layers_before_cs_new+$layers_after_cs_new)) {
		// 	$msg = "Error, layers actual must be sum of layers before and after!";
		// 	return view('planner.error',compact('msg'));
		// }

		$data_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mp.[id], mp.[style_size], mp.[pro_id], mp.[pro_pcs_layer], mp.[pro_pcs_planned], mp.[pro_pcs_actual]
				,md.[layers_partial]
			FROM [mattress_pros] as mp
			JOIN [mattress_details] as md ON md.[mattress_id] = mp.[mattress_id]
			WHERE mp.[mattress_id] = '".$id."' "));
		// dd($data_pro);

		for ($i=0; $i < count($data_pro); $i++) { 

			$table = mattress_pro::findOrFail($data_pro[$i]->id);
			// dd($table);
			
			$pro_pcs_actual = (int)$table->pro_pcs_layer * (int)$layers_a_new + (int)$table->layers_partial - (int)$table->damaged_pcs;
			// dd($pro_pcs_actual);
			$table->pro_pcs_actual = $pro_pcs_actual;
			$table->save();
		}

		$find_details_id = DB::connection('sqlsrv')->select(DB::raw("SELECT d.[id]
					,mm.[marker_length]
					,d.[extra]
				FROM [mattress_details] as d
				JOIN [mattresses] as m ON m.[id] = d.[mattress_id]
				JOIN [mattress_markers] as mm ON mm.mattress_id = m.id
				WHERE m.[id] = '".$id."' "));
		// dd($find_details_id);

		// save in mattress_details (operator comment)
		$table2_update = mattress_details::findOrFail($find_details_id[0]->id);
		$table2_update->layers_a = $layers_a_new;
		$table2_update->cons_actual = $layers_a_new * ((float)$find_details_id[0]->marker_length + ($find_details_id[0]->extra/100));
		// dd($table2_update->cons_actual);
		$table2_update->save();

		// Eff
		$find_eff = DB::connection('sqlsrv')->select(DB::raw("SELECT e.[id]
				FROM [mattress_effs] as e
				WHERE e.[mattress_id] = '".$id."' "));
		// dd($find_eff);

		$table3_update = mattress_eff::findOrFail($find_eff[0]->id);
		
		if (isset($input['layers_before_cs_new'])) {
			
			$table3_update->layers_before_cs = (int)$input['layers_before_cs_new'];
			$stimulation_before = (float)$input['layers_before_cs_new'] * ((float)$find_details_id[0]->marker_length +($find_details_id[0]->extra / 100)) * 1.00 ;
			// dd('b: '. $stimulation_before);
			$table3_update->stimulation_before = $stimulation_before;	
		} 

		if (isset($input['layers_after_cs_new'])) {

			$table3_update->layers_after_cs = (int)$input['layers_after_cs_new'];
			$stimulation_after = (float)$input['layers_after_cs_new'] * ((float)$find_details_id[0]->marker_length +($find_details_id[0]->extra / 100)) * 1.00 ;
			// dd('a: '. $stimulation_after);
			$table3_update->stimulation_after = $stimulation_after;

		}

		$table3_update->save();
		
		return Redirect::to('/edit_mattress_line/'.$id);
	}

	public function update_all_pro_actual() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM mattresses WHERE (skeda_item_type = 'MM' OR skeda_item_type = 'MS' ) "));
		// dd($data);

		for ($x=0; $x < count($data); $x++) { 
			
			// dd($data[$x]->mattress_id);

			$data_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					mp.[id], mp.[style_size], mp.[pro_id], mp.[pro_pcs_layer], mp.[pro_pcs_planned], mp.[pro_pcs_actual]
					,md.[layers_partial], md.[layers_a]
				FROM [mattress_pros] as mp
				JOIN [mattress_details] as md ON md.[mattress_id] = mp.[mattress_id]
				WHERE mp.[mattress_id] = '".$data[$x]->id."' "));
			// dd($data_pro);

			for ($i=0; $i < count($data_pro); $i++) { 
				// dd((int)$data_pro[$i]->layers_a);

				$table = mattress_pro::findOrFail($data_pro[$i]->id);
				// dd($table);
				
				$pro_pcs_actual = (int)$table->pro_pcs_layer * (int)$data_pro[$i]->layers_a + (int)$data_pro[$i]->layers_partial - (int)$table->damaged_pcs;
				// dd($pro_pcs_actual);
				$table->pro_pcs_actual = $pro_pcs_actual;
				// $table->save();
			}
		}
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
			$table1->cons_actual = 0;
			$table1->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			dd("Problem to save in mattress_details");
		}
		// dd("STOP");
		// all mattress_phases for this mattress set to NOT ACTIVE
		
		// $find_all_mattress_phasses = DB::connection('sqlsrv')->select(DB::raw("SELECT [id] FROM [mattress_phases] 
		// 	WHERE [mattress_id] = '".$id."' "));
		
		// if (isset($find_all_mattress_phasses[0])) {
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
		$status = "DELETED";
		$active = 1;
		// $operator1;
		$location = 'DELETED';
		$mattress = $table1->mattress;

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		// $find_position = mattress_details::where('mattress_id','=',$id)->get();
		// $pre_position = $find_position[0]->position;
		
		$table3_new = mattress_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table3_new->mattress_id = $id;
		$table3_new->mattress = $mattress;
		$table3_new->status = $status;
		$table3_new->location = $location;
		$table3_new->device;
		$table3_new->active = $active;
		$table3_new->operator1 = Session::get('operator');
		$table3_new->operator2;
		$table3_new->date = $date;
		$table3_new->id_status = $id.'-'.$status;
		// $table3_new->pre_position = $pre_position;
		$table3_new->save();
	
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

//LR ROLL

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
		  FROM [o_rolls]
		  WHERE updated_at >= DATEADD(day, -60, GETDATE()) "));	
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

//MINI MATTRESS

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

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			o.[id],
			o.[material],
			o.[o_roll],
			o.[g_bin],
			o.[mattress_name_orig],
			o.[skeda],
			o.[no_of_joinings],
			mm.[marker_width]

			FROM o_rolls as o
			JOIN mattress_markers as mm ON mm.[mattress_id] = o.[mattress_id_orig] 
		WHERE [status] = 'CREATED' order by g_bin asc"));
		// dd($data);

		return view('planner.mini_marker_create', compact('data'));
	}

	public function mini_marker_create_1(Request $request) {

		// $this->validate($request, ['selected_marker'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		if (!isset($input['items'])) {
			$warning = 'Choose LR roll';
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				o.[id],
				o.[material],
				o.[o_roll],
				o.[g_bin],
				o.[mattress_name_orig],
				o.[skeda],
				o.[no_of_joinings],
				mm.[marker_width]

				FROM o_rolls as o
				JOIN mattress_markers as mm ON mm.mattress_id = o.mattress_id_orig 
			WHERE status = 'CREATED' order by g_bin asc"));
					// dd($data);
			return view('planner.mini_marker_create', compact('data','warning'));
		}
		$items[] = $input['items'];
		// dd($items[0][0]);

		$info = explode("#", $items[0][0]);
		// dd($info);

		$selected_o_roll = $info[0];
		$skeda = $info[1];

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				o.[id],
				o.[material],
				o.[o_roll],
				o.[g_bin],
				o.[mattress_name_orig],
				o.[skeda],
				o.[no_of_joinings],
				mm.[marker_width]

				FROM o_rolls as o
				JOIN mattress_markers as mm ON mm.mattress_id = o.mattress_id_orig 
		WHERE o.status = 'CREATED' AND o.skeda = '".$skeda."' order by o.g_bin asc"));

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
			,y.test
			,y.po_sum_qty
			,y.po_sum_qty2
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
			,x.test
			,x.po_sum_qty
			,x.po_sum_qty2
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
			      ,(SELECT TOP 1 mh.[marker_name]	FROM [marker_headers] as mh JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id] WHERE mh.[marker_name] like 'MM%' AND ml.[style_size] = mpr.style_size) as test

			      ,(SELECT SUM(qty) FROM [posummary].[dbo].[pro] as posum WHERE posum.skeda = LEFT(p.skeda,12) AND posum.pro = p.pro) as po_sum_qty2
			      ,(SELECT SUM(qty) FROM [posummary].[dbo].[pro] as posum WHERE posum.pro = p.pro) as po_sum_qty

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

			GROUP BY x.pro, x.po_sum_qty, x.po_sum_qty2, x.style_size, x.test, pro_pcs_planned_all, pro_pcs_actual_all

			) as y  "));
		// dd($recap_table);

		$mm_name= '';
		$marker_width= '';
		$marker_length= '';
		$efficiency= '';
		$average_consumption= '';

		for ($x=0; $x < count($recap_table) ; $x++) { 
			$mm_style_size = $recap_table[$x]->style_size;

			$find_markers = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.[marker_name],mh.[marker_width],mh.[marker_length],mh.[efficiency],mh.[average_consumption]
				 FROM [marker_headers] as mh
				 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
				 WHERE mh.[status] = 'ACTIVE' AND mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$mm_style_size."'  "));

			for ($t=0; $t < count($find_markers); $t++) { 

				$mm_name .= $find_markers[$t]->marker_name." ";
				$marker_width .= round($find_markers[$t]->marker_width,0)." ";
				$marker_length .= round($find_markers[$t]->marker_length,2)." ";
				$efficiency .= round($find_markers[$t]->efficiency,2)." ";
				$average_consumption .= round($find_markers[$t]->average_consumption,2)." ";
			}

			$recap_table[$x]->mm_name = trim($mm_name);
			$recap_table[$x]->marker_width = trim($marker_width);
			$recap_table[$x]->marker_length = trim($marker_length);
			$recap_table[$x]->efficiency = trim($efficiency);
			$recap_table[$x]->average_consumption = trim($average_consumption);
			$mm_name= '';
			$marker_width= '';
			$marker_length= '';
			$efficiency= '';
			$average_consumption= '';

		}
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

			$mm_name= '';
			$marker_width= '';
			$marker_length= '';
			$efficiency= '';
			$average_consumption= '';

			for ($x=0; $x < count($recap_table) ; $x++) { 
				$mm_style_size = $recap_table[$x]->style_size;

				$find_markers = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.[marker_name],mh.[marker_width],mh.[marker_length],mh.[efficiency],mh.[average_consumption]
					 FROM [marker_headers] as mh
					 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
					 WHERE mh.[status] = 'ACTIVE' AND mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$mm_style_size."' "));

				for ($t=0; $t < count($find_markers); $t++) { 

					$mm_name .= $find_markers[$t]->marker_name." ";
					$marker_width .= round($find_markers[$t]->marker_width,0)." ";
					$marker_length .= round($find_markers[$t]->marker_length,2)." ";
					$efficiency .= round($find_markers[$t]->efficiency,2)." ";
					$average_consumption .= round($find_markers[$t]->average_consumption,2)." ";
				}

				$recap_table[$x]->mm_name = trim($mm_name);
				$recap_table[$x]->marker_width = trim($marker_width);
				$recap_table[$x]->marker_length = trim($marker_length);
				$recap_table[$x]->efficiency = trim($efficiency);
				$recap_table[$x]->average_consumption = trim($average_consumption);
				$mm_name= '';
				$marker_width= '';
				$marker_length= '';
				$efficiency= '';
				$average_consumption= '';

			}
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
		 WHERE mh.[status] = 'ACTIVE' AND mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$style_size."' "));

		$items_new = unserialize($input['items']);
		// dd($items);

		$gg[]='';
		foreach ($items_new as $value) {
			// dd($value);

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				o.id,
				o.material,
				o.o_roll,
				o.g_bin,
				o.mattress_name_orig,
				o.skeda,
				o.no_of_joinings,
				mm.marker_width,
				mm.marker_length,
				mm.marker_name,
				mh.efficiency,
				mh.average_consumption
					FROM o_rolls as o
					JOIN mattress_markers as mm ON mm.mattress_id = o.mattress_id_orig 
					JOIN marker_headers as mh ON mm.marker_name = mh.marker_name
			WHERE o.status = 'CREATED' AND o.o_roll = '".$value."' order by o.g_bin asc"));
			// dd($data);
			if (!isset($data[0]->o_roll)) {
				dd('Problem: can not find proper information, probably marker doesent exist');
			}
			array_push($gg, $data[0]->o_roll, $data[0]->g_bin, $data[0]->mattress_name_orig, $data[0]->marker_name, round($data[0]->marker_width,0), round($data[0]->marker_length,2), round($data[0]->efficiency,2), round($data[0]->average_consumption,2) );

		}

		$selected_info = array_chunk(array_filter($gg), 8);
		// dd($selected_info);

		return view('planner.mini_marker_add_marker', compact('items','skeda','pro','style_size','markers','po_sum_qty','before_cut_actual','already_cut_actual','selected_info'));
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
		$po_sum_qty = (int)$input['po_sum_qty'];
		// $po_sum_qty = 9510;
		$before_cut_actual = (int)$input['before_cut_actual'];
		$already_cut_actual = (int)$input['already_cut_actual'];
		
		$marker = $input['marker'];

		if ($marker == '') {
			$markers = DB::connection('sqlsrv')->select(DB::raw("SELECT mh.[marker_name]
			 FROM [marker_headers] as mh
			 JOIN [marker_lines] as ml ON ml.[marker_header_id] = mh.[id]
			 WHERE mh.[marker_name] like 'MM%' AND ml.[style_size] = '".$style_size."' AND mh.status = 'ACTIVE' "));

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

		$pc_per_layer = (float)$pc_per_layer_markers[0]->pcs_on_layer;
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
		// dd($mattress_last[0]->skeda);

		$last_mm_used = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 mattress FROM  [mattresses]
  				WHERE skeda_item_type = 'MM' and mattress like '".$mattress_last[0]->skeda."%' and mattress not like '".$mattress_last[0]->skeda."-MM-E%'
  				ORDER BY id desc"));
		// dd($last_mm_used);
		
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
		$extra = 1;
		$pcs_bundle = $mattress_last[0]->pcs_bundle;
		$layers_partial;
		$priority = 1;
		$call_shift_manager = 0;
		$test_marker = 0;
		$tpp_mat_keep_wastage = $mattress_last[0]->tpp_mat_keep_wastage;
		$printed_marker = 0;
		$mattress_packed = 0;
		$all_pro_for_main_plant = 0; 
		$tpa_number = $mattress_last[0]->tpa_number;

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
	   			$pro_pcs_layer = (float)$line->pcs_on_layer;

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
			$table1->overlapping;
			$table1->layer_limit = $layer_limit; // new
			$table1->tpa_number;
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

		if ((date('H') >= 0) AND (date('H') < 6)) {
		   	$date = date('Y-m-d H:i:s', strtotime(' -1 day'));
		} else {
			$date = date('Y-m-d H:i:s');
		}

		// $table3 = new mattress_phases;
		$table3 = mattress_phases::firstOrNew(['id_status' => $table0->id.'-'.$status]);
		$table3->mattress_id = $table0->id;
		$table3->mattress = $table0->mattress;
		$table3->status = $status;
		$table3->location = $location;
		$table3->device;
		$table3->active = $active;
		$table3->operator1 = Session::get('operator');
		$table3->operator2;
		$table3->date = $date;
		$table3->id_status = $table0->id.'-'.$status;
		$table3->save();
	
   		// dd($mattress_pro_array);
   		$mattress_pro_array = array_filter($mattress_pro_array);
		for ($i=1; $i <= count($mattress_pro_array) ; $i++) {
	
			$info = explode("#", $mattress_pro_array[$i]);
			// dd($info[0]);
			
			$table4 = new mattress_pro;
			$table4->mattress_id = $table0->id;
			$table4->mattress = $table0->mattress;
			$table4->style_size = $info[1];
			$table4->pro_id = $info[0];
			$table4->pro_pcs_layer   = (float)$info[2];
			$table4->pro_pcs_planned = 0;
			$table4->pro_pcs_actual  = 0;
			$table4->save();
			
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

//PASPUL
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

		if ($location == 'BOARD') {
			// dd("test");

			$p_ns = DB::connection('sqlsrv')->select(DB::raw("SELECT 

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
			      ,p1.[rewound_length_p]

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

			      ,(SELECT SUM([rewound_length_partialy]) FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = p1.[id]) as rewound_sum
				  ,(SELECT TOP 1 pc.[mtr_per_pcs] FROM [paspul_stock_u_cons] as  pc
				  	WHERE pc.[skeda] = p1.[skeda] and pc.[paspul_type] = p1.[paspul_type]) as unit_cons
 
			  FROM [paspuls] as p1
			  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id]
			  WHERE p2.[location] = 'NOT_SET' AND p2.[active] = '1' 
			  ORDER BY p1.[position] asc"));
				
			$pros= '';
			$skus= '';
			$sku_s= '';
			$location_all= '';

			for ($i=0; $i < count($p_ns) ; $i++) { 
				
				$id = $p_ns[$i]->id;
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.[pro]
					,ps.[style_size]
					,ps.[sku]
					,po.[location_all]
					--,*
				  FROM  [pro_skedas] as ps 
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE ps.[skeda] = '".$p_ns[$i]->skeda."' "));
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

				$p_ns[$i]->pro = trim($pros);
				$p_ns[$i]->style_size = trim($skus);
				$p_ns[$i]->sku = trim($sku_s);
				$p_ns[$i]->location_all = trim($location_all);
				$pros = '';
				$skus = '';
				$sku_s = '';
				$location_all = '';

			}


			$p_prw = DB::connection('sqlsrv')->select(DB::raw("SELECT 

				  p1.[id]
			      ,p1.[paspul_rewound_roll]
			      ,p1.[rewound_length_partialy]
			      ,p1.[kotur_partialy]
			      ,p1.[paspul_roll]
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

			      ,(SELECT SUM([rewound_length_partialy]) FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = p1.[paspul_roll_id]) as rewound_sum
				  ,(SELECT TOP 1 pc.[mtr_per_pcs] FROM [paspul_stock_u_cons] as  pc
				  	WHERE pc.[skeda] = p1.[skeda] and pc.[paspul_type] = p1.[paspul_type]) as unit_cons

				


			  FROM [paspul_rewounds] as p1
			  WHERE p1.[status] = 'TO_REWIND'
			  ORDER BY p1.[position] asc, p1.[priority] desc, p1.[paspul_rewound_roll] asc"));

			$pros= '';
			$skus= '';
			$sku_s= '';
			$location_all= '';

			for ($i=0; $i < count($p_prw) ; $i++) { 
				
				$id = $p_prw[$i]->id;
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.[pro]
					,ps.[style_size]
					,ps.[sku]
					,po.[location_all]
					--,*
				  FROM  [pro_skedas] as ps 
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE ps.[skeda] = '".$p_prw[$i]->skeda."' "));
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

				$p_prw[$i]->pro = trim($pros);
				$p_prw[$i]->style_size = trim($skus);
				$p_prw[$i]->sku = trim($sku_s);
				$p_prw[$i]->location_all = trim($location_all);
				$pros = '';
				$skus = '';
				$sku_s = '';
				$location_all = '';
			}


			return view('planner.plan_paspul', compact('p_ns','p_prw','location','operator','operators'));

		} elseif ($location == 'PRW') {

			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

				  p1.[id]
			      ,p1.[paspul_rewound_roll]
			      ,p1.[rewound_length_partialy]
			      ,p1.[kotur_partialy]
			      ,p1.[paspul_roll]
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

			      ,(SELECT SUM([rewound_length_partialy]) FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = p1.[paspul_roll_id]) as rewound_sum


			  FROM [paspul_rewounds] as p1
			  WHERE status = 'TO_REWIND'
			  ORDER BY p1.[priority] desc, p1.[paspul_rewound_roll] asc"));
			// dd($data);
		
		} elseif ($location == 'PCO') {

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
		
		} elseif ($location == 'COMPLETED') {

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
			  WHERE status = 'COMPLETED' and p1.[updated_at] > DATEADD(day,-30,GETDATE())
			  ORDER BY p1.[updated_at] desc"));
			// dd($data);
		
		} else {

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
			      ,p1.[rewound_length_p]

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

			      ,(SELECT SUM([rewound_length_partialy]) FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = p1.[id]) as rewound_sum

				  ,(SELECT SUM(r5.pro_pcs_actual)
					FROM [mattresses] as r1
					--JOIN [mattress_details] as r2 ON r2.mattress_id = r1.id
					JOIN [mattress_phases] as r3 ON r3.mattress_id = r1.id AND r3.[active] = 1
					--JOIN [mattress_pros] as r4 ON r4.mattress_id = r1.id
					JOIN [mattress_pros] as r5 ON r5.mattress_id = r1.id

					WHERE (r3.[status] = 'TO_LOAD' OR r3.[status] = 'TO_SPREAD')
					AND r1.skeda_item_type != 'MB' AND r1.skeda_item_type != 'MW' --AND r1.skeda_item_type != 'MM'
					AND r3.[location] != 'SP0'
					AND r1.skeda = p1.[skeda] AND r1.dye_lot = p1.[dye_lot]) as sum_pcs_load_spread_by_lot_skeda

				  ,(SELECT SUM(r5.pro_pcs_actual)
					FROM [mattresses] as r1
					--JOIN [mattress_details] as r2 ON r2.mattress_id = r1.id
					JOIN [mattress_phases] as r3 ON r3.mattress_id = r1.id AND r3.[active] = 1
					--JOIN [mattress_pros] as r4 ON r4.mattress_id = r1.id
					JOIN [mattress_pros] as r5 ON r5.mattress_id = r1.id

					WHERE (r3.[status] = 'TO_CUT' OR r3.[status] = 'COMPLETED')
					AND r1.skeda_item_type != 'MB' AND r1.skeda_item_type != 'MW' --AND r1.skeda_item_type != 'MM'
					AND r3.[location] != 'SP0'
					AND r1.skeda = p1.[skeda] AND r1.dye_lot = p1.[dye_lot]) as sum_pcs_cut_comp_by_lot_skeda

			  FROM [paspuls] as p1
			  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id]
			  WHERE p2.[location] = '".$location."' AND p2.[active] = '1'  and p1.[updated_at] > DATEADD(day,-30,GETDATE())
			  ORDER BY p1.[position] asc"));
		
		}

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

		return view('planner.plan_paspul', compact('data','location','operator','operators'));
	}

	// ne koristi se
	/*
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
	*/

	public function plan_paspul_line1 ($id) {

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
		      ,p1.[rewound_length_p]

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

		      -- ,(SELECT SUM([rewound_length_partialy]) as rewound_sum FROM [paspul_rewounds] WHERE [paspul_roll_id] =  p1.[id]) as rewound_sum
		      ,(SELECT TOP 1 pc.[mtr_per_pcs] FROM [paspul_stock_u_cons] as  pc
				  	WHERE pc.[skeda] = p1.[skeda] and pc.[paspul_type] = p1.[paspul_type]) as unit_cons

		  FROM [paspuls] as p1
		  LEFT JOIN [paspul_lines] as p2 ON p2.[paspul_roll_id] = p1.[id] and p2.[active] = 1 
		  WHERE p1.[id] = '".$id."' "));
		// dd($data);
	
		$paspul_roll_id = $data[0]->id;
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
		$tpa_number = $data[0]->tpa_number;
		$rewound_length   = $data[0]->rewound_length;
		$rewound_length_a = $data[0]->rewound_length_a;
		$rewound_length_p = $data[0]->rewound_length_p;
		// $rewound_sum = (float)$data[0]->rewound_sum;

		$rewound_roll_unit_of_measure = $data[0]->rewound_roll_unit_of_measure;
		$unit_cons = $data[0]->unit_cons;
		$kotur_actual = $data[0]->kotur_actual;

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

		// Itaca and Fiorano doesent need pas bin
		if ($skeda_item_type == 'PA' and config('app.global_variable') == 'gordon') {
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
		
		return view('planner.plan_paspul_line1', compact( 'id','paspul_roll','paspul_roll_id','pasbin','skeda_item_type','priority','comment_office','call_shift_manager','rewinding_method',
			'material','dye_lot','color_desc','skeda', 'skeda_item_type', 'tpa_number',
			'bin','pasbin','rewound_length','rewound_length_a',/*'rewound_sum',*/'rewound_length_p','rewound_roll_unit_of_measure','unit_cons','kotur_actual'));
	}

	// ne koristi se
	/*
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
		FROM 		(
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

			// $find_all_papul_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			// 	[id], [paspul_roll], [paspul_roll_id]
			// FROM [paspul_lines] WHERE [paspul_roll_id] = '".$id."' AND active = 1"));
		
			// if (isset($find_all_papul_lines[0])) {
			// 	$paspul_roll = $find_all_papul_lines[0]->paspul_roll;
			// 	// dd($find_all_papul_lines);

			// 	for ($i=0; $i < count($find_all_papul_lines); $i++) {

			// 		$table3 = paspul_line::findOrFail($find_all_papul_lines[$i]->id);
			// 		$table3->active = 0;
			// 		$table3->save();
			// 	}
			// }

			$paspul_lines_not_active = DB::connection('sqlsrv')->update(DB::raw("
				UPDATE [paspul_lines]
				SET active = 0, id_status = ''+cast([paspul_roll_id] as varchar )+'-'+[status]
				WHERE [paspul_roll_id] = '".$id."' AND active = 1
			"));

			
			$table = paspul::findOrFail($id);
			$table->pasbin = $pas_bin;
			$table->position = $position;
			$table->priority = $priority;
			$table->comment_office = $comment_office;
			$table->call_shift_manager = $call_shift_manager;
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
			$table_p->id_status = $table_p->paspul_roll_id.'-'.$table_p->status;
			$table_p->date = $date;

			$table_p->save();

		// }
		// catch (\Illuminate\Database\QueryException $e) {
		// 	dd('Problem to save');
		// }

		return redirect('/plan_paspul/NOT_SET');
	}
	*/

	public function plan_paspul_line_confirm1(Request $request) {

		// $this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		if ($input['action'] == 'plan') {
			
			$paspul_roll_id = $input['paspul_roll_id'];
			$paspul_roll = $input['paspul_roll'];
			$priority = $input['priority'];
			$comment_office = $input['comment_office'];
			$skeda = $input['skeda'];
			$skeda_item_type = $input['skeda_item_type'];

			if (isset($input['final_roll'])) {
				$final_roll = 'YES';
			} else {
				$final_roll = 'NO';
			}

			// Itaca and Fiorano doesent need PAS bin 
			if ($skeda_item_type == "PA" and config('app.global_variable') == 'gordon') {
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

			$rewound_length_partialy = (float)$input['rewound_length_partialy'];
			
			if ($rewound_length_partialy < 0) {
				dd("rewound_length_partialy must be > 0");
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

			// find new child roll
			$no_of_paspul_rewound_rolls = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT([paspul_rewound_roll]) as no
			FROM [paspul_rewounds]
			WHERE [paspul_roll] = '".$paspul_roll."' "));

			$number = (int)$no_of_paspul_rewound_rolls[0]->no;
			$no = $number + 1;
			$no = str_pad($no, 2, '0', STR_PAD_LEFT);
			// dd($no);

		
			// save planned length to father
			$table = paspul::findOrFail($paspul_roll_id);
			$still_possible_to_plan = (float)$table->rewound_length - (float)$table->rewound_length_p;
			
			if ($still_possible_to_plan < $rewound_length_partialy) {
				dd('you can not plan more length then is avilable ('.$still_possible_to_plan.' meters)');

			} 
			// dd($still_possible_to_plan);

			if ($still_possible_to_plan == $rewound_length_partialy ) {
				$final_roll = 'YES';
			}

			if ($final_roll == 'YES') {
					
				// save to paspul father 
				 	$location = "PRW";
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
					$status = 'TO_REWIND';
					$location = 'PRW'; 
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

					// sum all rewound_length_partialy for all rolls
					$find_rewound_length_partialy = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM([rewound_length_partialy]) as rewound_sum
					FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = '".$paspul_roll_id."' "));
					$rewound_length_a = (int)$find_rewound_length_partialy[0]->rewound_sum + (int)$rewound_length_partialy;

					$table = paspul::findOrFail($paspul_roll_id);
					$table->rewound_length_a = $rewound_length_a;
					$table->position = $position;
					$table->pasbin = $pas_bin;
					$table->comment_office = $comment_office;
					$table->priority = $priority;
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
					$table_p->id_status = $table_p->paspul_roll_id.'-'.$table_p->status;
					$table_p->save();
				//	

				// save to paspul_rewound
					$rewound_roll_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					 		COUNT(p.[id]) as no
					 	FROM [paspul_rewounds] as p
					 	WHERE p.[status] = 'TO_REWIND'
					 	"));
					$number = (int)$rewound_roll_position[0]->no;
					$rewound_roll_position = $number + 1;

					if ($rewound_length_partialy > 0) {
						$table_r = new paspul_rewound;
						$table_r->paspul_rewound_roll = $table->paspul_roll."-".$no;
						$table_r->rewound_length_partialy = $rewound_length_partialy;
						$table_r->kotur_partialy;
						$table_r->status = "TO_REWIND";
						$table_r->paspul_roll_id = $paspul_roll_id;
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
						$table_r->pasbin = $pas_bin;
						$table_r->skeda_item_type = $table->skeda_item_type;
						$table_r->skeda = $table->skeda;
						$table_r->skeda_status = $table->skeda_status;
						$table_r->rewound_roll_unit_of_measure = $table->rewound_roll_unit_of_measure;
						$table_r->position = $rewound_roll_position;
						$table_r->priority = $priority;
						$table_r->comment_office = $comment_office;
						$table_r->comment_operator = $table->comment_operator;
						$table_r->call_shift_manager = $table->call_shift_manager;
						$table_r->rewinding_method = $table->rewinding_method;
						$table_r->tpa_number = $table->tpa_number;
						$table_r->save();			
					}				
				//
					
			} else {

				// save to paspul_rewound
					$rewound_roll_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					 		COUNT(p.[id]) as no
					 	FROM [paspul_rewounds] as p
					 	WHERE p.[status] = 'TO_REWIND'
					 	"));
					$number = (int)$rewound_roll_position[0]->no;
					$rewound_roll_position = $number + 1;

					if ($rewound_length_partialy > 0) {
						$table_r = new paspul_rewound;
						$table_r->paspul_rewound_roll = $table->paspul_roll."-".$no;
						$table_r->rewound_length_partialy = $rewound_length_partialy;
						$table_r->kotur_partialy;
						$table_r->status = "TO_REWIND";
						$table_r->paspul_roll_id = $paspul_roll_id;
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
						$table_r->pasbin = $pas_bin;
						$table_r->skeda_item_type = $table->skeda_item_type;
						$table_r->skeda = $table->skeda;
						$table_r->skeda_status = $table->skeda_status;
						$table_r->rewound_roll_unit_of_measure = $table->rewound_roll_unit_of_measure;
						$table_r->position = $rewound_roll_position;
						$table_r->priority = $priority;
						$table_r->comment_office = $comment_office;
						$table_r->comment_operator = $table->comment_operator;
						$table_r->call_shift_manager = $table->call_shift_manager;
						$table_r->rewinding_method = $table->rewinding_method;
						$table_r->tpa_number = $table->tpa_number;
						$table_r->save();			
					}				
				//
			}

			$table_f = paspul::findOrFail($paspul_roll_id);
			$table_f->rewound_length_p = (float)$table_f->rewound_length_p + $rewound_length_partialy;
			$table_f->save();


		} elseif ($input['action'] == 'save') {

			$paspul_roll_id = $input['paspul_roll_id'];
			$paspul_roll = $input['paspul_roll'];
			$priority = $input['priority'];
			$comment_office = $input['comment_office'];
			$dye_lot = $input['dye_lot'];
		
			if (isset($input['call_shift_manager'])) {
				$call_shift_manager = (int)$input['call_shift_manager'];
			} else {
				$call_shift_manager = 0;
			}
			
			$table = paspul::findOrFail($paspul_roll_id);
			$table->dye_lot 	= $dye_lot;
			$table->priority 	= $priority;
			$table->comment_office 		= $comment_office;
			$table->call_shift_manager 	= $call_shift_manager;
			$table->save();



		} else {
			dd('error u glavi');
		}

		return redirect('/plan_paspul/BOARD');
	}

	public function remove_paspul_line($id) {
		// dd($id);

		return view('planner.paspul_delete_confirm', compact( 'id'));
	}

	public function paspul_delete_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // 
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

		// check if exist child
		$check_if_is_planned = DB::connection('sqlsrv')->select(DB::raw("SELECT id 
		FROM paspuls
		WHERE id = '".$id."' AND
			  rewound_length_p is NOT null "));
		// dd($check_if_is_planned);

		if (isset($check_if_is_planned[0]->id)) {
			dd('You can not delete paspul because already has planned qty');
		}
		// dd('continue');

		// new position
		$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
		FROM (
			SELECT position 
				FROM  [paspuls] as p
				JOIN  [paspul_lines] as pl ON pl.[paspul_roll_id] = p.[id] AND active = '1'
			WHERE pl.[location] = 'DELETED' ) SQ
		ORDER BY position desc"));
		// dd($position[0]->position);

		if (isset($position[0]->position)) {
			$position = $position[0]->position;
		} else {
			$position = 0;
		}

		$paspul_lines_not_active = DB::connection('sqlsrv')->update(DB::raw("
			UPDATE [paspul_lines]
			SET active = 0, id_status = ''+cast([paspul_roll_id] as varchar )+'-'+[status]
			WHERE [paspul_roll_id] = '".$id."' AND active = 1
		"));

		$table = paspul::findOrFail($id);
		$table->position = $position+1;
		$table->save();

		$location = 'NOT_SET';

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

		return redirect('/plan_paspul/NOT_SET');
	}
	
	public function remove_paspul_roll_line($id) {
		// dd($id);

		return view('planner.paspul_delete_roll_confirm', compact( 'id'));
	}

	public function paspul_delete_roll_confirm(Request $request) {

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

		$table =  DB::connection('sqlsrv')->select(DB::raw("SELECT pr.[id]
      			,pr.[paspul_rewound_roll]
      			,pr.[paspul_roll_id]
      			,pr.[paspul_roll]
	  			,pl.[status]
	  			,pl.[location]
	  			,p.[rewound_length]
	  			,p.[rewound_length_p]
			FROM [paspul_rewounds] as pr
			JOIN [paspul_lines] as pl ON pl.[paspul_roll_id] = pr.[paspul_roll_id] AND pl.[active] = 1
			JOIN [paspuls] as p ON p.[id] = pr.[paspul_roll_id]
			WHERE pr.[id] = '".$id."' "));
		// dd($table);

		$paspul_roll_id = $table[0]->paspul_roll_id;
		$paspul_rewound_roll_id = $table[0]->id;
		$location = $table[0]->location;

		$still_possible_to_plan = (float)$table[0]->rewound_length - (float)$table[0]->rewound_length_p;
		// dd($still_possible_to_plan);

		$if_is_last_roll = DB::connection('sqlsrv')->select(DB::raw("SELECT id
				FROM [paspul_rewounds]
				WHERE [paspul_roll_id] = '".$paspul_roll_id."' 
				AND status = 'TO_REWIND' 
				AND id not in ('".$paspul_rewound_roll_id."') "));
				
		if (isset($if_is_last_roll[0]->id)) {
			$last_roll = 'NO';  //no
		} else {
			$last_roll = 'YES'; //yes
		}
		// dd($last_roll);

		if (($still_possible_to_plan == 0) AND ($last_roll == 'YES')) {
			// dd('update father and son');

			// save to paspul father
				$location = "PCO";
				//$position = 0; // 
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
				
				$paspul_lines_not_active = DB::connection('sqlsrv')->update(DB::raw("
					UPDATE [paspul_lines]
					SET active = 0, id_status = ''+cast([paspul_roll_id] as varchar )+'-'+[status]
					WHERE [paspul_roll_id] = '".$paspul_roll_id."' AND active = 1
				"));

				$rewound_length_partialy = 0;
				$kotur_partialy = 0;

				// sum rewound_length_partialy for all rolls
				$find_rewound_length_partialy = DB::connection('sqlsrv')->select(DB::raw("SELECT 
						SUM([rewound_length_partialy]) as rewound_sum
				FROM [paspul_rewounds]
				WHERE [paspul_roll_id] = '".$paspul_roll_id."'  AND
					id != '".$paspul_rewound_roll_id."'"));
				// dd($find_rewound_length_partialy);
				$rewound_length_a = (float)$find_rewound_length_partialy[0]->rewound_sum + (float)$rewound_length_partialy;
				// dd($rewound_length_a);
				
				$table = paspul::findOrFail($paspul_roll_id);
				$table->rewound_length_a = $rewound_length_a;
				$table->position = $position;
				$table->save();

				// reorder position of PCO
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

				$table_r = paspul_rewound::findOrFail($paspul_rewound_roll_id);
				$table_r->rewound_length_partialy = $rewound_length_partialy;
				$table_r->kotur_partialy = $kotur_partialy;
				$table_r->status = "COMPLETED";
				$table_r->position = $rewound_roll_position; 
				$table_r->save();
			//

		} else {
			// dd('son');
			// save to paspul_rewound
				$rewound_roll_position = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				 		COUNT(p.[id]) as no
				 	FROM [paspul_rewounds] as p
				 	WHERE p.[status] = 'COMPLETED'
				 	"));

				$number = (int)$rewound_roll_position[0]->no;
				$rewound_roll_position = $number + 1;
				// dd($rewound_roll_position);

				$rewound_length_partialy = 0;
				$kotur_partialy = 0;

				$table_r = paspul_rewound::findOrFail($paspul_rewound_roll_id);
				$table_r->rewound_length_partialy = $rewound_length_partialy;
				$table_r->kotur_partialy = $kotur_partialy;
				$table_r->status = "COMPLETED";
				$table_r->position = $rewound_roll_position; 
				$table_r->save();
			//

		}

		
		return redirect('/plan_paspul/PRW');
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

		$data_sp = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			pl.[operator1],
			pl.[updated_at]
		  FROM [paspuls] as p
		  JOIN [paspul_lines] as pl ON p.[id] = pl.[paspul_roll_id] AND pl.[status] = 'TO_CUT'
		  WHERE p.[id] = '".$id."'
		  ORDER BY pl.[updated_at] desc"));
		// dd($data_sp);

		if (isset($data_sp[0])) {
			$sp_operator = $data_sp[0]->operator1;
			$sp_date = substr($data_sp[0]->updated_at,0,16);	
		} else {
			$sp_operator = NULL;
			$sp_date = NULL;
		}

		$data_cut = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			pl.[operator1],
			pl.[updated_at]
		  FROM [paspuls] as p
		  JOIN [paspul_lines] as pl ON p.[id] = pl.[paspul_roll_id] AND pl.[status] = 'COMPLETED'
		  WHERE p.[id] = '".$id."'
		  ORDER BY pl.[updated_at] desc"));
		// dd($data_cut);

		if (isset($data_cut[0])) {
			$cut_operator = $data_cut[0]->operator1;
			$cut_date = substr($data_cut[0]->updated_at,0,16);	
		} else {
			$cut_operator = NULL;
			$cut_date = NULL;
		}

		return view('planner.edit_paspul_line', compact( 'id','paspul_roll','pasbin','skeda_item_type','priority','comment_office','call_shift_manager','rewinding_method',
			'material','dye_lot','color_desc','skeda', 'skeda_item_type','tpa_number','paspul_roll_id','location', 'sp_operator', 'sp_date', 'cut_operator', 'cut_date'));
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
		$dye_lot = $input['dye_lot'];
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

		$table->dye_lot = $dye_lot;
		$table->priority = $priority;
		$table->comment_office = $comment_office;
		// $table->comment_operator = $comment_operator;
		$table->call_shift_manager = $call_shift_manager;
		// $table->rewinding_method = $rewinding_method;
		$table->save();

		return redirect('/plan_paspul/'.$location);
	}

	public function edit_paspul_roll_line($id) {
		// dd($id);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 

				  p1.[id]
			      ,p1.[paspul_rewound_roll]
			      ,p1.[rewound_length_partialy]
			      ,p1.[kotur_partialy]
			      ,p1.[paspul_roll]
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

			      ,(SELECT SUM([rewound_length_partialy]) FROM [paspul_rewounds]
					WHERE [paspul_roll_id] = p1.[paspul_roll_id]) as rewound_sum
				  ,(SELECT TOP 1 pc.[mtr_per_pcs] FROM [paspul_stock_u_cons] as  pc
				  	WHERE pc.[skeda] = p1.[skeda] and pc.[paspul_type] = p1.[paspul_type]) as unit_cons

			  FROM [paspul_rewounds] as p1
			  WHERE p1.[id] = '".$id."' "));
		
		// dd($data);

		$paspul_rewound_roll = $data[0]->paspul_rewound_roll;
		$material = $data[0]->material;
		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$tpa_number = $data[0]->tpa_number;
		$rewinding_method = $data[0]->rewinding_method;
		$rewound_length_partialy = $data[0]->rewound_length_partialy;
		$rewound_roll_unit_of_measure = $data[0]->rewound_roll_unit_of_measure;
		$unit_cons = $data[0]->unit_cons;
		$kotur_partialy = $data[0]->kotur_partialy;
		$kotur_planned = $data[0]->kotur_planned;
		$pasbin = $data[0]->pasbin;

		$priority = $data[0]->priority;
		$call_shift_manager = $data[0]->call_shift_manager;
		$comment_office = $data[0]->comment_office;


		return view('planner.edit_paspul_roll_line', compact( 'id','paspul_rewound_roll','material','dye_lot','color_desc','skeda','skeda_item_type','tpa_number',
			'rewinding_method','rewound_length_partialy','rewound_roll_unit_of_measure','unit_cons','kotur_partialy','kotur_planned',
			'priority','call_shift_manager','comment_office','pasbin'));
	}

	public function edit_paspul_roll_line_confirm(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$id = $input['id'];
		$paspul_rewound_roll = $input['paspul_rewound_roll'];

		$priority = $input['priority'];
		$comment_office = $input['comment_office'];

		if (isset($input['call_shift_manager'])) {
			$call_shift_manager = (int)$input['call_shift_manager'];
		} else {
			$call_shift_manager = 0;
		}
		
				
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

		
		$table = paspul_rewound::findOrFail($id);
		$table->priority = $priority;
		$table->comment_office = $comment_office;
		$table->call_shift_manager = $call_shift_manager;
		
		$table->save();

		return redirect('/plan_paspul/BOARD');
	}

	public function paspul_change_kotur_qty($id) {
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
		  WHERE status = 'COMPLETED' and p1.[id] = '".$id."'
		  ORDER BY p1.[paspul_rewound_roll] asc "));
		// dd($data);

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

		return view('planner.paspul_pco1', compact('id','paspul_roll','rewound_length_a','comment_office'
			,'material','skeda','dye_lot','color_desc','skeda_item_type','rewinding_method','priority','call_shift_manager','comment_operator','comment_office'
			,'kotur_partialy','paspul_rewound_roll','paspul_roll_id'));
	}

	public function paspul_change_kotur_qty_confirm(Request $request) {


		$this->validate($request, ['kotur_partialy' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		$paspul_rewound_roll = $input['paspul_rewound_roll'];

		$paspul_roll = $input['paspul_roll'];
		$paspul_roll_id = $input['paspul_roll_id'];

		$kotur_partialy = (int)$input['kotur_partialy'];
		// dd($kotur_partialy);
				
		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('planner.error',compact('msg'));
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

		if ($father_status == "COMPLETED") {

			// UPDATE PASPUL REWOUND 

			$table_prw = paspul_rewound::findOrFail($id);
			$table_prw->kotur_partialy = $kotur_partialy;
			$table_prw->save();

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

				$table = paspul::findOrFail($paspul_roll_id);
				$table->kotur_actual = $kotur_actual; // CALCULATIONS
				$table->save();
			}
		} else {
			dd('Father paspul status is not COMPLETED, unable to change kotur qty.');
		}
		
		return redirect('/');
	}

	public function paspul_stock () {

		$data = DB::connection('sqlsrv')->select(DB::raw("  SELECT s.* 
					,round(k.[mtr_per_pcs],2) as [unit_cons]
					
					,CASE s.[uom]
						WHEN 'meter'
						THEN floor(s.[kotur_length] / k.[mtr_per_pcs]) 
						WHEN 'ploce'
						THEN floor(s.[kotur_length] * k.[mtr_per_pcs]) 
						ELSE ''
						END
						as [pcs_kotur]
					
					,CASE s.[uom]
						WHEN 'meter'
						THEN (floor(s.[kotur_length] / k.[mtr_per_pcs]) * s.[kotur_qty]) 
						WHEN 'ploce'
						THEN (floor(s.[kotur_length] * k.[mtr_per_pcs]) * s.[kotur_qty]) 
						ELSE ''
						END
						as FG_qty,
					l.plant
					
			  	FROM [paspul_stocks] as s 
			  	LEFT JOIN [paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
				LEFT JOIN [paspul_locations] as l ON l.[location] = s.[location] 
				ORDER BY l.[location] asc
			 "));
		// dd($data);
		return view('planner.paspul_table', compact('data'));
	}

	public function paspul_change_q ($id) {
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id, kotur_qty 
			FROM paspul_stocks
			WHERE id = '".$id."' "));

		if (isset($data[0]->id)) {
			$id =  $data[0]->id;
			$kotur_qty = $data[0]->kotur_qty;
		}

		return view('planner.paspul_change_q', compact('id','kotur_qty'));
	}

	public function paspul_change_q_post (Request $request) {
		
		$this->validate($request, ['kotur_qty' => 'required']);
		$input = $request->all(); 
		// dd($input);


		$pa_old = paspul_stock::findOrFail($input['id']);
		
		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = $pa_old->location;
		$table_stock->location_to = $pa_old->location;
		$table_stock->location_type = 'change_qty';
		$table_stock->kotur_qty = (int)$pa_old->kotur_qty;
		$table_stock->operator = 'planner';
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

		if ($input['kotur_qty'] == 0) {
			
			$table_stock->location_type = 'change_qty_to_0';
			$table_stock->save();

			$remove = paspul_stock::where('id',$pa_old->id)->delete();

		} else {

			$pa_old->kotur_qty = (int)$input['kotur_qty'];
			$pa_old->save();
		}
	
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT s.* ,k.[unit_cons], s.[fg_color_code], l.[plant] FROM paspul_stocks as s 
			LEFT JOIN [paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
			LEFT JOIN [paspul_locations] as l ON l.[location] = s.[location] 
			ORDER BY l.[location] asc
			 "));
		// dd($data);
		return view('planner.paspul_table', compact('data','id'));
	}

	public function paspul_stock_log () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * 
			FROM [paspul_stock_logs]
			WHERE [created_at] > DATEADD(day,-30,GETDATE()) 
			ORDER BY [created_at] desc"));
		// dd($data);
		return view('planner.paspul_table_log', compact('data'));
	}

	public function paspul_change_log_q ($id) {
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * 
			FROM paspul_stock_logs
			WHERE id = '".$id."' "));

		return view('planner.paspul_change_log_q', compact('id','data'));
	}

	public function paspul_change_log_q_post(Request $request) {
		$this->validate($request, ['id' => 'required']);
		$input = $request->all(); 
		// dd($input);

		// $data_log = DB::connection('sqlsrv')->select(DB::raw("SELECT *
		// 	FROM paspul_stock_logs
		// 	WHERE id = '".$input['id']."' "));
		// dd($data_log);
		$data_log = paspul_stock_log::findOrFail($input['id']);
		// dd($data_log);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM paspul_stocks
			WHERE pas_key = '".$data_log->pas_key."' and location = '".$data_log->location_from."' "));
		// dd($data);

		if (isset($data[0]->id)) {
			// Exist on location

			$pa1 = paspul_stock::findOrFail($data[0]->id);
			$pa1->kotur_qty = $pa1->kotur_qty + $data_log->kotur_qty;
			$pa1->save();

		} else {
			// NOT Exist on location

			$table_stock = new paspul_stock;

			$table_stock->skeda = $data_log->skeda;
			$table_stock->paspul_type = $data_log->paspul_type;
			$table_stock->dye_lot = $data_log->dye_lot;
			$table_stock->kotur_length = $data_log->kotur_length;

			$table_stock->pas_key = $data_log->pas_key;
			$table_stock->pas_key_e = $data_log->pas_key_e;

			$table_stock->location = $data_log->location_from;
			$table_stock->pas_key_location = $data_log->pas_key.'_'.$data_log->location_from;

			$table_stock->kotur_qty = $data_log->kotur_qty;
			$table_stock->kotur_width = $data_log->kotur_width;
			$table_stock->uom = $data_log->uom;
			$table_stock->material = $data_log->material;
			$table_stock->fg_color_code = $data_log->fg_color_code;
			$table_stock->pcs_kotur = $data_log->pcs_kotur;

			$table_stock->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $data_log->pas_key;
		$table_stock->pas_key_e = $data_log->pas_key_e;
		$table_stock->location_from = 'log';
		$table_stock->location_to = $data_log->location_from;
		$table_stock->location_type = 'return_from_log';
		$table_stock->kotur_qty = $data_log->kotur_qty *(-1);
		$table_stock->operator = 'planner';
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $data_log->kotur_width;
		$table_stock->uom = $data_log->uom;
		$table_stock->material = $data_log->material;
		$table_stock->fg_color_code = $data_log->fg_color_code;
		
		$table_stock->skeda = $data_log->skeda;
		$table_stock->paspul_type = $data_log->paspul_type;
		$table_stock->dye_lot = $data_log->dye_lot;
		$table_stock->kotur_length = $data_log->kotur_length;

		$table_stock->returned_from = $data_log->returned_from;
		$table_stock->pcs_kotur = $data_log->pcs_kotur;

		$table_stock->save();

		return Redirect::to('paspul_stock');
	}

	public function paspul_delete_line($id){
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM paspul_stocks
			WHERE [id] = '".$id."' "));
		// dd($data);

		return view('planner.paspul_delete_line', compact('data'));
	}

	public function paspul_delete_line_confirm(Request $request) {

		$input = $request->all(); 
		// dd($input);

		$id = $input['id'];
		// dd($id);

		$pa_old = paspul_stock::findOrFail($id);
		// dd($pa_old);
		$pa_old->delete();
				
		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = $pa_old->location;
		$table_stock->location_to = 'DELETED';
		$table_stock->location_type = 'delete';
		$table_stock->kotur_qty = (int)$pa_old->qty;
		$table_stock->operator = Auth::user()->name;
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

		return Redirect::to('/paspul_stock');
	}

	public function paspul_req_list() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_paspuls 
			WHERE status = 'Pending'
			ORDER BY created_at desc
			"));
		// dd($data);

		
		return view('planner.paspul_req_list', compact('data'));
	}

	public function paspul_req_list_log() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_paspuls 
			WHERE created_at >= DATEADD(DAY, -60, GETDATE())
			ORDER BY created_at desc
			"));
		// dd($data);

		
		return view('planner.paspul_req_list', compact('data'));
	}

	public function req_paspul_complete ($id) {

		$req_paspul = req_paspul::findOrFail($id);
		$req_paspul->status = 'Completed';
		$req_paspul->save();

		return Redirect::to('/paspul_req_list');
	}

	public function paspul_remove_valy() {
		// dd('test');

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT skeda FROM paspul_stocks
			WHERE location = 'RECEIVED_IN_VALY'  "));
		// dd($data);

		$location_from = 'RECEIVED_IN_VALY';

		return view('planner.paspul_remove_valy', compact('location_from','data'));
	}

	public function paspul_remove_valy_skeda(Request $request) {
		$this->validate($request, ['skeda' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$skeda = $input['skeda'];
		$location_from = $input['location_from'];

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM paspul_stocks
			WHERE [location] = 'RECEIVED_IN_VALY' and [skeda] = '".$skeda."' "));
		// dd($data);

		return view('planner.paspul_remove_valy_confirm', compact('location_from','data','skeda'));	
	}

	public function paspul_remove_valy_remove(Request $request) {
		$this->validate($request, ['skeda' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$skeda = $input['skeda'];
		$location_from = $input['location_from'];

		$op = 'planner';
		// dd($op);

		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM paspul_stocks
			WHERE [location] = 'RECEIVED_IN_VALY' and [skeda] = '".$skeda."' "));
		// dd($pa_old[0]);

		for ($i=0; $i < count($pa_old); $i++) { 

			// dd($pa_old[$i]->id);

			// $pa_old = paspul_stock::findOrFail($pa_old[$i]->id);
			// dd($pa_old);
			// $pa_old->delete();

			$paspul_stock_log = new paspul_stock_log;
			$paspul_stock_log->pas_key = $pa_old[$i]->pas_key;
			$paspul_stock_log->pas_key_e = $pa_old[$i]->pas_key_e;
			$paspul_stock_log->location_from = $location_from;
			$paspul_stock_log->location_to = 'REMOVED_VALY';
			$paspul_stock_log->location_type = 'delete';
			$paspul_stock_log->kotur_qty = $pa_old[$i]->kotur_qty;
			$paspul_stock_log->operator = $op;
			$paspul_stock_log->shift = Auth::user()->name;

			$paspul_stock_log->kotur_width = $pa_old[$i]->kotur_width;
			$paspul_stock_log->uom = $pa_old[$i]->uom;
			$paspul_stock_log->material = $pa_old[$i]->material;
			$paspul_stock_log->fg_color_code = $pa_old[$i]->fg_color_code;

			$paspul_stock_log->skeda = $pa_old[$i]->skeda;
			$paspul_stock_log->paspul_type = $pa_old[$i]->paspul_type;
			$paspul_stock_log->dye_lot = $pa_old[$i]->dye_lot;
			$paspul_stock_log->kotur_length = $pa_old[$i]->kotur_length;

			$paspul_stock_log->returned_from = $pa_old[$i]->returned_from;
			$paspul_stock_log->pcs_kotur = $pa_old[$i]->pcs_kotur;

			$paspul_stock_log->save();


			$remove = paspul_stock::where('id',$pa_old[$i]->id)->delete();
				
		}

		return Redirect::to('/');
	}

	public function paspul_stock_check_fg_color_post() {

		$data = DB::connection('sqlsrv')->update(DB::raw("update [paspul_stocks]
		set [paspul_stocks].[fg_color_code] = SUBSTRING([pro_skedas].[sku],9,5)
		from [paspul_stocks] 
		JOIN [pro_skedas] ON [paspul_stocks].[skeda] = [pro_skedas].[skeda]
		where [paspul_stocks].[fg_color_code] is null "));

		return redirect('/paspul_stock');
	}

	public function paspul_stock_update_pc_kotur_post() {

		$data = DB::connection('sqlsrv')->update(DB::raw("
		
	  	update [paspul_stocks] 
		set [paspul_stocks].[pcs_kotur] = round([paspul_stocks].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stocks] 
		JOIN [paspul_stock_u_cons] ON [paspul_stocks].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stocks].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stocks].[uom] = 'meter'
		
		update [paspul_stocks] 
		set [paspul_stocks].[pcs_kotur] = round([paspul_stocks].[kotur_length] * [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stocks] 
		JOIN [paspul_stock_u_cons] ON [paspul_stocks].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stocks].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stocks].[uom] = 'ploce'"));

		$data = DB::connection('sqlsrv')->update(DB::raw("

	  	update [paspul_stock_logs] 
		set [paspul_stock_logs].[pcs_kotur] = round([paspul_stock_logs].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stock_logs] 
		JOIN [paspul_stock_u_cons] ON [paspul_stock_logs].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stock_logs].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stock_logs].[uom] = 'meter'
		
	  	update [paspul_stock_logs] 
		set [paspul_stock_logs].[pcs_kotur] = round([paspul_stock_logs].[kotur_length] * [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stock_logs] 
		JOIN [paspul_stock_u_cons] ON [paspul_stock_logs].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stock_logs].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stock_logs].[uom] = 'ploce'"));


		return redirect('/paspul_stock');
	}


//PRINTING

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
		      ,m2.[tpa_number]
		      ,m2.[cons_actual]
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
		$fabric = trim(substr($material, 0,11));

		if (config('app.global_variable') == 'gordon') {
   			$fabric_data = DB::connection('sqlsrv5')->select(DB::raw("SELECT [sp_parameter]
				 FROM [settings].[dbo].[fabrics]
			  WHERE [fabric] = '".$fabric."' "));
			// dd($fabric_data);

			if(isset($fabric_data[0]->sp_parameter)) {
				$spreading_profile = $fabric_data[0]->sp_parameter;
			} else {
				$spreading_profile = '';
			}

   		} else {
			$spreading_profile = '';
		}
		// dd('spreading profile: '.$spreading_profile);

		$dye_lot = $data[0]->dye_lot;
		$color_desc = $data[0]->color_desc;
		$width_theor_usable = round($data[0]->width_theor_usable,3);
		$skeda = $data[0]->skeda;
		$skeda_item_type = $data[0]->skeda_item_type;
		$spreading_method = $data[0]->spreading_method;
		$layers = round($data[0]->layers,0);

		if ($skeda_item_type == 'MT') {
			$pcs_bundle = "".round($data[0]->cons_actual,0)." kg";
		} else {
			$pcs_bundle = round($data[0]->pcs_bundle,0);
		}
		// dd($pcs_bundle);

		$bottom_paper = $data[0]->bottom_paper;
		$comment_office = $data[0]->comment_office;

		$marker_name = $data[0]->marker_name;
		$marker_length = round($data[0]->marker_length,3);
		$marker_width = round($data[0]->marker_width,3);

		$location = $data[0]->location;
		$overlapping = $data[0]->overlapping;

		$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;
		$tpa_number = $data[0]->tpa_number;

		if ($tpp_mat_keep_wastage == 1) {
			$tpp_mat_keep_wastage = "YES"."-".$tpa_number;
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
		$table->spreading_profile = $spreading_profile;
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

		// dd($input['id']);
		$m_details = DB::connection('sqlsrv')->select(DB::raw("SELECT id
			FROM  [mattress_details]
			WHERE mattress_id = '".$id."' "));
		// dd($m_details[0]->id);
		$t = mattress_details::findOrFail($m_details[0]->id);
		$t->printed_nalog = (int)$t->printed_nalog + 1;
		$t->save();

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
	
		$id = $data[0]->id;
		$mattress = $data[0]->mattress;
		$material = $data[0]->material;
		$fabric = trim(substr($material, 0,11));

		if (config('app.global_variable') == 'gordon') {
   			$fabric_data = DB::connection('sqlsrv5')->select(DB::raw("SELECT [sp_parameter]
				 FROM [settings].[dbo].[fabrics]
			  WHERE [fabric] = '".$fabric."' "));
			// dd($fabric_data);

			if(isset($fabric_data[0]->sp_parameter)) {
				$spreading_profile = $fabric_data[0]->sp_parameter;
			} else {
				$spreading_profile = '';
			}

   		} else {
			$spreading_profile = '';
		}
		// dd('spreading profile: '.$spreading_profile);

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
		$tpa_number = $data[0]->tpa_number;

		if ($tpp_mat_keep_wastage == 1) {
			$tpp_mat_keep_wastage = "YES"."-".$tpa_number;
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

			$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[g_bin]
				FROM  [o_rolls]
				WHERE mattress_id_new = '".$id."' "));
			// dd($data2);

			for ($i=0; $i < count($data2); $i++) { 
					
				// $id = $i + 1;
				// $pro."_".$i = $data1[$i]->pro;
				if (isset($data2[$i]->o_roll)) {
					${"o_roll_{$i}"}= $data2[$i]->o_roll.' - '.$data2[$i]->g_bin;
				} else {
					${"o_roll{$i}"}='';
				}
			}

			for ($g=0; $g <= 9; $g++) { 
				if (!isset(${"o_roll_{$g}"})) {
					${"o_roll_{$g}"}='';
				}	
			}

		} else {

			dd('MB or MW not ready');
			
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
		$table->spreading_profile_0 = $spreading_profile;
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

		// dd($input['id']);
		$m_details = DB::connection('sqlsrv')->select(DB::raw("SELECT id
			FROM  [mattress_details]
			WHERE mattress_id = '".$id."' "));
		// dd($m_details[0]->id);
		$t = mattress_details::findOrFail($m_details[0]->id);
		$t->printed_nalog = (int)$t->printed_nalog + 1;
		$t->save();

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
				      ,m2.[cons_actual]
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
				$fabric = trim(substr($material, 0,11));

				if (config('app.global_variable') == 'gordon') {
		   			$fabric_data = DB::connection('sqlsrv5')->select(DB::raw("SELECT [sp_parameter]
						 FROM [settings].[dbo].[fabrics]
					  WHERE [fabric] = '".$fabric."' "));
					// dd($fabric_data);

					if(isset($fabric_data[0]->sp_parameter)) {
						$spreading_profile = $fabric_data[0]->sp_parameter;
					} else {
						$spreading_profile = '';
					}

		   		} else {
					$spreading_profile = '';
				}
				// dd('spreading profile: '.$spreading_profile);

				$dye_lot = $data[0]->dye_lot;
				$color_desc = $data[0]->color_desc;
				$width_theor_usable = round($data[0]->width_theor_usable,3);
				$skeda = $data[0]->skeda;
				$skeda_item_type = $data[0]->skeda_item_type;
				$spreading_method = $data[0]->spreading_method;
				
				$layers = round($data[0]->layers,0);
				// $pcs_bundle = round($data[0]->pcs_bundle,0);

				if ($skeda_item_type == 'MT') {
					$pcs_bundle = "".round($data[0]->cons_actual,0)." kg";
				} else {
					$pcs_bundle = round($data[0]->pcs_bundle,0);
				}
				// dd($pcs_bundle);

				$bottom_paper = $data[0]->bottom_paper;
				$comment_office = $data[0]->comment_office;

				$marker_name = $data[0]->marker_name;
				$marker_length = round($data[0]->marker_length,3);
				$marker_width = round($data[0]->marker_width,3);

				$location = $data[0]->location;
				$overlapping = $data[0]->overlapping;

				$tpp_mat_keep_wastage = $data[0]->tpp_mat_keep_wastage;
				$tpa_number = $data[0]->tpa_number;

				if ($tpp_mat_keep_wastage == 1) {
					$tpp_mat_keep_wastage = "YES"."-".$tpa_number;
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

					for ($g=0; $g <= 14; $g++) { 
						if (!isset(${"pro_{$g}"})) {
							${"pro_{$g}"}='';
							${"style_size_{$g}"}='';
							${"pro_pcs_actual_{$g}"}='';
							${"pro_pcs_layer_{$g}"}='';
							${"destination_{$g}"}='';
							${"multimaterial_{$g}"}='';
						}	
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

					for ($g=0; $g <= 14; $g++) { 
						if (!isset(${"pro_{$g}"})) {
							${"pro_{$g}"}='';
							${"style_size_{$g}"}='';
							${"pro_pcs_actual_{$g}"}='';
							${"pro_pcs_layer_{$g}"}='';
							${"destination_{$g}"}='';
							${"multimaterial_{$g}"}='';
						}	
						// var_dump(${"pro_{$g}"});
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
				$table->spreading_profile = $spreading_profile;
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

				// dd($input['id']);
				$m_details = DB::connection('sqlsrv')->select(DB::raw("SELECT id
					FROM  [mattress_details]
					WHERE mattress_id = '".$id."' "));
				// dd($m_details[0]->id);
				$t = mattress_details::findOrFail($m_details[0]->id);
				$t->printed_nalog = (int)$t->printed_nalog + 1;
				$t->save();

				for ($g=0; $g <= 14; $g++) { 
					${"pro_{$g}"}='';
					${"style_size_{$g}"}='';
					${"pro_pcs_actual_{$g}"}='';
					${"pro_pcs_layer_{$g}"}='';
					${"destination_{$g}"}='';
					${"multimaterial_{$g}"}='';
				}
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
		  WHERE (m4.[status] = 'TO_LOAD' OR m4.[status] = 'COMPLETED') AND m1.[skeda_item_type] = 'MM' AND (m2.[printed_nalog] IS NULL OR m2.[printed_nalog] = 0)"));
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
		// dd($array);
		$uk = count($items);


		for ($i=0; $i < count($array); $i++) { 
			// var_dump($array[$i]);

			if ($uk > 1) {
				
				for ($x=0; $x < 2 ; $x++) { 
					// var_dump($array[$i][$x]);

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
				      ,m2.[id] as detail_id
				      
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
					$fabric = trim(substr(${"material_{$x}"}, 0,11));

					if (config('app.global_variable') == 'gordon') {
			   			$fabric_data = DB::connection('sqlsrv5')->select(DB::raw("SELECT [sp_parameter]
							 FROM [settings].[dbo].[fabrics]
						  WHERE [fabric] = '".$fabric."' "));
						// dd($fabric_data);
						if(isset($fabric_data[0]->sp_parameter)) {
							${"spreading_profile_{$x}"} = $fabric_data[0]->sp_parameter;
						} else {
							${"spreading_profile_{$x}"} = '';
						}

			   		} else {
						${"spreading_profile_{$x}"} = '';
					}
					
					
					
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

					$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[g_bin]
						FROM  [o_rolls]
						WHERE mattress_id_new = '".$id."' "));
					// dd($data2);

					for ($y=0; $y < count($data2); $y++) { 
							
						// $id = $i + 1;
						// $pro."_".$i = $data1[$i]->pro;
						if (isset($data2[$y]->o_roll)) {
							${"o_roll_{$y}_{$x}"}= $data2[$y]->o_roll.' - '.$data2[$y]->g_bin;
						} else {
							${"o_roll{$y}_{$x}"}='';
						}
					}

					for ($g=0; $g <= 9; $g++) {
						// if (!isset(${"o_roll_{$g}_{$x}"})) {
						// 	${"o_roll_{$g}_{$x}"}='';
						// }	
					
						if (!isset(${"o_roll_{$g}_0"})) {
							${"o_roll_{$g}_0"}='';
						}	
						if (!isset(${"o_roll_{$g}_1"})) {
							${"o_roll_{$g}_1"}='';
						}
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
				$table->spreading_profile_0 = $spreading_profile_0;
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
				$table->pro_pcs_layer_0 = round($pro_pcs_layer_0,0);
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
				$table->spreading_profile_1 = $spreading_profile_1;
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
				$table->pro_pcs_layer_1 = round($pro_pcs_layer_1,0);
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

				// dd($input['id']);
				$m_details1 = DB::connection('sqlsrv')->select(DB::raw("SELECT id
					FROM  [mattress_details]
					WHERE mattress = '".$table->mattress_0."' "));
				// dd($m_details[0]->id);
				$t1 = mattress_details::findOrFail($m_details1[0]->id);
				$t1->printed_nalog = (int)$t1->printed_nalog + 1;
				$t1->save();

				$m_details2 = DB::connection('sqlsrv')->select(DB::raw("SELECT id
					FROM  [mattress_details]
					WHERE mattress = '".$table->mattress_1."' "));
				// dd($m_details[0]->id);
				$t2 = mattress_details::findOrFail($m_details2[0]->id);
				$t2->printed_nalog = (int)$t2->printed_nalog + 1;
				$t2->save();
				
				for ($g=0; $g <= 9; $g++) { 
					${"o_roll_{$g}_0"}='';
					${"o_roll_{$g}_1"}='';
				}

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
				$fabric = trim(substr(${"material_{$x}"}, 0,11));
				
				if (config('app.global_variable') == 'gordon') {
		   			$fabric_data = DB::connection('sqlsrv5')->select(DB::raw("SELECT [sp_parameter]
						 FROM [settings].[dbo].[fabrics]
					  WHERE [fabric] = '".$fabric."' "));
					// dd($fabric_data);
					if(isset($fabric_data[0]->sp_parameter)) {
						${"spreading_profile_{$x}"} = $fabric_data[0]->sp_parameter;
					} else {
						${"spreading_profile_{$x}"} = '';
					}

		   		} else {
					${"spreading_profile_{$x}"} = '';
				}

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

				$data2 = DB::connection('sqlsrv')->select(DB::raw("SELECT [o_roll],[g_bin]
					FROM  [o_rolls]
					WHERE mattress_id_new = '".$id."' "));
				// dd($data2);

				for ($y=0; $y < count($data2); $y++) { 
					// $id = $i + 1;
					// $pro."_".$i = $data1[$i]->pro;
					if (isset($data2[$y]->o_roll)) {
						${"o_roll_{$y}_{$x}"}= $data2[$y]->o_roll.' - '.$data2[$y]->g_bin;
					} else {
						${"o_roll{$y}_{$x}"}='';
					}
				}

				for ($g=0; $g <= 9; $g++) { 
					if (!isset(${"o_roll_{$g}_0"})) {
						${"o_roll_{$g}_0"}='';
					}	
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
				$table1->spreading_profile_0 = $spreading_profile_0;
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
				$table1->pro_pcs_layer_0 = round($pro_pcs_layer_0,0);
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

				// dd($input['id']);
				$m_details = DB::connection('sqlsrv')->select(DB::raw("SELECT id
					FROM  [mattress_details]
					WHERE mattress_id = '".$id."' "));
				// dd($m_details[0]->id);
				$t = mattress_details::findOrFail($m_details[0]->id);
				$t->printed_nalog = (int)$t->printed_nalog + 1;
				$t->save();

				for ($g=0; $g <= 9; $g++) { 
					${"o_roll_{$g}_0"}='';
					
				}
			}
			$uk = $uk - 2;
		}

		return redirect('/');
	}

//SEARCH

	public function recap_by_skeda_mattress(){

		return view('planner.recap_by_skeda_mattress');
	}

	public function recap_by_skeda_mattress_post(Request $request) {

		$this->validate($request, ['skeda'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$skeda = trim($input['skeda']);
		// dd($skeda);

		$data = DB::connection('sqlsrv')->select(DB::raw(" SELECT 
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
		      ,m2.[printed_nalog]
		      ,m2.[layer_limit]
		      ,m2.[overlapping]
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
		  WHERE m1.[skeda] like '".$skeda."%' AND m4.active = '1' 
		  ORDER BY m1.id asc"));
		// dd($data);
		
	
		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';
		for ($i=0; $i < count($data) ; $i++) { 
		
			$id = $data[$i]->id;
			// dd($id);

			if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.pro
					,ps.style_size
					,ps.sku
					,po.[location_all]
					--,*
				  FROM [mattress_pros] as mp
				  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE mp.[mattress_id] = '".$id."' "));
				
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

			} else {

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
		}

		return view('planner.recap_by_skeda_mattress_post', compact('data','skeda'));
	}

	public function recap_by_skeda_paspul(){
		
		return view('planner.recap_by_skeda_paspul');
	}

	public function recap_by_skeda_paspul_post(Request $request) {

		$this->validate($request, ['skeda'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$skeda = $input['skeda'];
		// dd($skeda);

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
		  WHERE p1.[skeda] like '".$skeda."%' AND p2.active = '1' 
		  ORDER BY p1.[id] asc"));

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

		return view('planner.recap_by_skeda_paspul_post', compact('data','skeda'));	
	}

	public function recap_by_g_bin_mattress(){

		return view('planner.recap_by_g_bin_mattress');
	}

	public function recap_by_g_bin_mattress_post(Request $request) {

		$this->validate($request, ['g_bin'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$g_bin = trim($input['g_bin']);
		// dd($g_bin);

		$data = DB::connection('sqlsrv')->select(DB::raw(" SELECT 
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
		      ,m2.[printed_nalog]
		      ,m2.[layer_limit]
		      ,m2.[overlapping]
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
		  WHERE m1.[g_bin] like '".$g_bin."%' AND m4.active = '1' 
		  ORDER BY m1.id asc"));
		// dd($data);
		
	
		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';
		for ($i=0; $i < count($data) ; $i++) { 
		
			$id = $data[$i]->id;
			// dd($id);

			if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.[pro]
					,ps.[style_size]
					,ps.[sku]
					,po.[location_all]
					--,*
				  FROM [mattress_pros] as mp
				  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE mp.[mattress_id] = '".$id."' "));
				
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

			} else {

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
		}

		return view('planner.recap_by_g_bin_mattress_post', compact('data','g_bin'));
	}

	public function paspul_locations() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [paspul_locations] ORDER BY plant, type, location asc"));
		// dd($data);
		return view('planner.paspul_locations', compact('data'));
	}

	public function paspul_location_new(){

		return view('planner.paspul_location_new');
	}

	public function paspul_location_new_post (Request $request) {

		$this->validate($request, ['location'=>'required','type'=>'required','plant'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$location = strtoupper(trim($input['location']));
		$type = $input['type'];
		$plant = $input['plant'];

		$table_p = new paspul_location;

		$table_p->location = $location;
		$table_p->type = $type;
		$table_p->plant = $plant;
		$table_p->save();

		return redirect('/paspul_locations');
	}

	public function paspul_location_edit ($id) {
		
		$location = paspul_location::findOrFail($id);		
		return view('planner.paspul_location_edit', compact('location'));
	}

	public function paspul_location_edit_post(Request $request) {

		$this->validate($request, ['location'=>'required','type'=>'required','plant'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$table = paspul_location::findOrFail($input['id']);		
		$table->location = $input['location'];
		$table->type = $input['type'];
		$table->plant = $input['plant'];
		$table->save();
		
		return Redirect::to('/paspul_locations');
	}

	public function recap_by_sku_sp0() {


		return view('planner.recap_by_sku_sp0');
	}

	public function recap_by_sku_sp0_post(Request $request) {
		// dd('test');

		$this->validate($request, ['sku'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$sku = trim($input['sku']);
		$sku = str_replace(' ', '%', $sku);
		// dd($g_bin);
		$skuLike = '%' . $sku . '%';

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
		      ,m2.[overlapping]
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
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id]
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.location = 'SP0'
		  
		  LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id] AND m4.active = '1' 
		  LEFT JOIN [pro_skedas] as m6 ON m6.[pro_id] = m5.[pro_id]
		  
		  WHERE m6.sku LIKE '%$sku%'
		  ORDER BY m1.id asc
		  "));
		// dd($data);
		
	
		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';
		for ($i=0; $i < count($data) ; $i++) { 
		
			$id = $data[$i]->id;
			// dd($id);

			if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.[pro]
					,ps.[style_size]
					,ps.[sku]
					,po.[location_all]
					--,*
				  FROM [mattress_pros] as mp
				  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE mp.[mattress_id] = '".$id."' "));
				
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

			} else {

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
		}

		return view('planner.recap_by_sku_sp0_post', compact('data','sku'));
	}

	public function recap_by_sku_sp() {


		return view('planner.recap_by_sku_sp');
	}

	public function recap_by_sku_sp_post(Request $request) {
		// dd('test');

		$this->validate($request, ['sku'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		
		$sku = trim($input['sku']);
		$sku = str_replace(' ', '%', $sku);
		// dd($g_bin);
		$skuLike = '%' . $sku . '%';

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
		      ,m2.[overlapping]
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
		      
		  FROM [mattresses] as m1
		  LEFT JOIN [mattress_details] as m2 ON m2.[mattress_id] = m1.[id]
		  LEFT JOIN [mattress_markers] as m3 ON m3.[mattress_id] = m2.[mattress_id] 
		  LEFT JOIN [mattress_phases]  as m4 ON m4.[mattress_id] = m3.[mattress_id] AND m4.location like 'SP%'
		  
		  LEFT JOIN [mattress_pros]	   as m5 ON m5.[mattress_id] = m4.[mattress_id] AND m4.active = '1' 
		  LEFT JOIN [pro_skedas] as m6 ON m6.[pro_id] = m5.[pro_id]
		  
		  WHERE m6.sku LIKE '%$sku%'
		  ORDER BY m1.id asc
		  "));
		// dd($data);
		
	
		$pros= '';
		$skus= '';
		$sku_s= '';
		$location_all= '';
		for ($i=0; $i < count($data) ; $i++) { 
		
			$id = $data[$i]->id;
			// dd($id);

			if (($data[$i]->skeda_item_type == 'MS') OR ($data[$i]->skeda_item_type == 'MM')) {
				
				$prom = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					ps.[pro]
					,ps.[style_size]
					,ps.[sku]
					,po.[location_all]
					--,*
				  FROM [mattress_pros] as mp
				  JOIN [pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
				  LEFT JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
				WHERE mp.[mattress_id] = '".$id."' "));
				
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

			} else {

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
		}

		return view('planner.recap_by_sku_sp_post', compact('data','sku'));
	}

//SKEDA COMMENTS
	public function skeda_comments(){

		// dd('tt');
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [skeda_comments] ORDER BY created_at desc"));
		// dd($data);
		
		return view('planner.skeda_comments', compact('data'));	
	}

	public function skeda_comments_add() {

		return view('planner.skeda_comments_add');
	}

	public function skeda_comment_post(Request $request) {

		$this->validate($request, ['skeda'=>'required','comment'=>'required']);
		$input = $request->all(); 

		$skeda = trim($input['skeda']);
		$comment = trim($input['comment']);
		$operator = Session::get('operator');
		// dd($comment);

		$table = new skeda_comments;
		$table->skeda = $skeda;
		$table->comment = $comment;
		$table->operator = $operator;
		$table->save();

		return Redirect::to('/skeda_comments');
	}

	public function skeda_comment_edit ($id) {

		$skeda_comment = skeda_comments::findOrFail($id);	
		return view('planner.skeda_comments_edit', compact('skeda_comment'));
	}

	public function skeda_comment_edit_post (Request $request) {

		$this->validate($request, ['skeda'=>'required','comment'=>'required']);
		$input = $request->all(); 
		// dd($input);

		$id = trim($input['id']);
		// dd($id);
		$skeda = trim($input['skeda']);
		$comment = trim($input['comment']);
		$operator = Session::get('operator');
		// dd($comment);

		$table = skeda_comments::findOrFail($id);
		$table->skeda = $skeda;
		$table->comment = $comment;
		$table->operator = $operator;
		$table->save();

		return Redirect::to('/skeda_comments');
	}

	public function skeda_comment_delete (Request $request) {

		// $this->validate($request, ['skeda'=>'required','comment'=>'required']);
		$input = $request->all(); 
		
		$id = trim($input['id']);
		$delete = skeda_comments::where('id', $id)->delete();
		
		return Redirect::to('/skeda_comments');
	}

//FABRIC RESERVATION
	public function fabric_reservation() {

		return view('planner.fabric_reservation');
	}

	public function inbound_delivery_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT i.*
			,(SELECT SUM(r.[qty_reserved_m]) FROM fabric_reservations as r
				WHERE r.[material] = i.[material] 
					AND r.[bagno] = i.[bagno] 
					AND r.[document_no] = i.[document_no]) as qty_reserved_m
			
			FROM [inbound_deliveries] as i 
			WHERE i.[reserve_status] != 'Reserved'
			ORDER BY i.id asc"));		
		// dd($data);
		return view('planner.inbound_delivery_table', compact('data'));
	}

	public function leftover_table() {
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [fabric_reservations]
				  WHERE skeda_status = 'Leftover to Check'
				  ORDER BY id asc"));
		// dd($data);
		return view('planner.leftover_table', compact('data'));
	}

	public function fabric_reservation_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [fabric_reservations]
				  WHERE (skeda_status = 'Bagno Still in Use' ) OR (skeda_status = 'Active' )
				  ORDER BY id asc"));
		// dd($data);
		return view('planner.fabric_reservation_table', compact('data'));
	}

	public function reserve_material($id) {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT i.*
			,(SELECT SUM(r.qty_reserved_m) FROM fabric_reservations as r
				WHERE r.material = i.material 
					AND r.bagno = i.bagno 
					AND r.document_no = i.document_no) as qty_reserved_m 
				FROM [inbound_deliveries] as i
				WHERE i.id = '".$id."' "));

		$skedas = DB::connection('sqlsrv6')->select(DB::raw("
					SELECT		
						distinct [skeda]
						--,[skeda_status] 
					FROM [pro] 
					
					where 
						([skeda_status] is NULL OR [skeda_status] = '') AND
						skeda != '' AND LEN([skeda]) >= 12
					
				   "));
		// dd($skedas);

		// dd($inbound_delivery_line[0]);
		return view('planner.fabric_reservation_material_add_info', compact('data','skedas'));
	}

	public function reserve_material_post(Request $request) {

		$this->validate($request, ['skeda'=>'required','skeda_mat'=>'required','reserved_qty'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl;)
		// dd($input);

		$id = $input['id'];
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [inbound_deliveries] 
				  WHERE id = '".$id."' 
				  AND (reserve_status='Not Reserved' OR reserve_status='Partially Reserved') 
				  "));
		if (!isset($data[0]->id)) {
			dd('status of inbound line should be NOT Reserved OR Partially Reserved');
		}
		// dd($data);

		$qty_reserved_m = round((float)$input['reserved_qty'],1);
		// dd($qty_reserved_m);
		// dd($data[0]->qty_received_m);
		// dd(round((float)$data[0]->qty_received_m,1));
		// dd(round($qty_reserved_m,1));

		if (round($qty_reserved_m,1) > round((float)$data[0]->qty_received_m,1))  {
			 dd('you can not reserved more than received1');
		}

		$skeda = $input['skeda'].$input['skeda_mat'];
		$document_no = $data[0]->document_no;
		$reservation_date = date('Y-m-d');
		$material = $data[0]->material;
		$bagno = $data[0]->bagno;
		$preforigin = $data[0]->preforigin;
		$inbd_id = $data[0]->id;
		$qty_reserved_m;

		// reservation status
		// Active
		// $chec_if_material_is
		$skeda_status = 'Active';

		/*
		$posum_skeda_status = DB::connection('sqlsrv6')->select(DB::raw("SELECT		
			[skeda_status]
			--,[skeda_status]
			FROM [pro]
			WHERE LEFT([skeda], 12) = LEFT('".$skeda."', 12) "));
		$posum_skeda_status = $posum_skeda_status[0]->skeda_status;

		// dd($posum_skeda_status);
		if (($posum_skeda_status == NULL) OR ($posum_skeda_status == '')) {
			$skeda_status = 'Active';
		} else {

			$check_if_mat_b_alredy_reserved = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [fabric_reservations] 
				  WHERE material = '".$material."' AND bagno = '".$bagno."' 
				  AND skeda_status != 'Complete'
				  
				  "));
			// dd(empty($check_if_mat_b_alredy_reserved));

			if (!empty($check_if_mat_b_alredy_reserved) == TRUE) {
				// someting already reserved for this material and bagno
				// set Still in Use
				// dd('someting already reserved for this material and bagno');
				$skeda_status = 'Still in Use';
			} else {
				// nothnig reserved
				// set Leftover to Check
				// dd('nothnig reserved');
				$skeda_status = 'Leftover to Check';
			}
		}
		// dd($skeda_status);
		*/

		$comment = trim($input['comment']);
		$operator = Session::get('operator');

		$check_if_alredy_reserved_on_skeda = DB::connection('sqlsrv')->select(DB::raw("SELECT id
				  FROM [fabric_reservations] 
				  WHERE material = '".$material."' AND
				  		bagno = '".$bagno."' AND 
				  		document_no = '".$document_no."' AND
				  		skeda = '".$skeda."' 
				  "));
		// dd($check_if_alredy_reserved_on_skeda);

		if (isset($check_if_alredy_reserved_on_skeda[0]->id)) {


			$table = fabric_reservation::findOrFail($check_if_alredy_reserved_on_skeda[0]->id);
			$table->skeda = $skeda;
			$table->reservation_date = $reservation_date;
			// $table->document_no = $document_no;
			// $table->material = $material;
			// $table->bagno = $bagno;
			// $table->preforigin = $preforigin;

			if (($table->qty_reserved_m + round($qty_reserved_m,1)) > round((float)$data[0]->qty_received_m,1)) {
			 	dd('you can not reserved more than received for this inbound line');
			}

			$table->qty_reserved_m = $table->qty_reserved_m + round($qty_reserved_m,1);
			$table->skeda_status = $skeda_status;
			$table->operator = $operator;
			$table->comment = $comment;
			$table->inbd_id = $inbd_id;	
			$table->save();


		} else {

			$table = new fabric_reservation;
			$table->skeda = $skeda;
			$table->reservation_date = $reservation_date;
			$table->document_no = $document_no;
			$table->material = $material;
			$table->bagno = $bagno;
			$table->preforigin = $preforigin;
			$table->qty_reserved_m = $qty_reserved_m;
			$table->skeda_status = $skeda_status;
			$table->operator = $operator;
			$table->comment = $comment;		
			$table->inbd_id = $inbd_id;		
			$table->save();

		}


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT i.*
			,(SELECT SUM(r.[qty_reserved_m]) FROM fabric_reservations as r
				WHERE r.[material] = i.[material] 
					AND r.[bagno] = i.[bagno] 
					AND r.[document_no] = i.[document_no]) as qty_reserved_m
			
			FROM [inbound_deliveries] as i
			WHERE i.[id] = '".$id."'
			ORDER BY i.id asc"));
		// dd($data);
		if (isset($data[0]->id)) {
			
			$table1 = inbound_delivery::findOrFail($id);	

			$qty_reserved_m_all = $data[0]->qty_reserved_m;

			if ($qty_reserved_m_all == $table1->qty_received_m ) {
				$table1->reserve_status = 'Reserved';
			} elseif ($qty_reserved_m_all < $table1->qty_received_m) {
				$table1->reserve_status = 'Partially Reserved';
			} else {
				dd('error');
			}
			$table1->save();
		}
		
		return redirect('inbound_delivery_table');
	}

	public function delete_reservation_q($id) {	

		$reservation = DB::connection('sqlsrv')->select(DB::raw("SELECT *	
				FROM [fabric_reservations] 
				WHERE id = '".$id."' "));

		$skeda = $reservation[0]->skeda;
		$document_no = $reservation[0]->document_no;
		$material = $reservation[0]->material;
		$qty_reserved_m = $reservation[0]->qty_reserved_m;



		return view('planner.delete_reservation', compact('id','skeda','document_no','material','qty_reserved_m'));
	}

	public function delete_reservation($id) {
		// dd($id);

		// $reservations = DB::connection('sqlsrv')->select(DB::raw("SELECT *
		// 		FROM [fabric_reservations] 
		// 		WHERE id = '".$id."' "));
		// dd($reservations);

		$table = fabric_reservation::findOrFail($id);
		// dd($table->inbd_id);
		// dd((int)$table->qty_reserved_m);

		// $inbound_delivery = DB::connection('sqlsrv')->select(DB::raw("SELECT *
		// 		FROM [inbound_deliveries] 
		// 		WHERE id = '".$reservations[0]->inbd_id."' "));
		// dd($inbound_delivery);

		$table1 = inbound_delivery::findOrFail($table->inbd_id);
		// dd($table1);
		// dd((int)$table1->qty_received_m);

		$reservations_all = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(qty_reserved_m) as qty_reserved_m_q
				FROM [fabric_reservations] 
				WHERE inbd_id = '".$table->inbd_id."' "));
		// dd($reservations_all);

		if (isset($reservations_all[0]->qty_reserved_m_q)) {
			$qty_reserved_m_q = (int)$reservations_all[0]->qty_reserved_m_q;
		} else {
			$qty_reserved_m_q = 0;
		}
		// dd($qty_reserved_m_q);



		if ($table->qty_reserved_m == $qty_reserved_m_q) {
			// dd('complete reservations');
			$table1->reserve_status = 'Not Reserved';
		} else {
			// dd('partial reservations');
			$table1->reserve_status = 'Partially Reserved';
		}
		$table1->save();

		$delete = fabric_reservation::where('id', $id)->delete();


		return redirect('fabric_reservation_table');

	}

	public function update_skeda_status() {

		$all_reservations = DB::connection('sqlsrv')->select(DB::raw("SELECT *	
			FROM [fabric_reservations]
			WHERE [skeda_status] != 'Completed' "));
		// dd($all_reservations);

		foreach ($all_reservations as $line) {
			
			// reservation status
			// Active
			// $chec_if_material_is
			$posum_skeda_status = DB::connection('sqlsrv6')->select(DB::raw("SELECT		
				[skeda_status]
				--,[skeda_status]
				FROM [pro]
				WHERE LEFT([skeda], 12) = LEFT('".$line->skeda."', 12) "));
			$posum_skeda_status = $posum_skeda_status[0]->skeda_status;
			// dd($posum_skeda_status);


			if (($posum_skeda_status == NULL) OR (trim($posum_skeda_status == ''))) {
				$skeda_status = 'Active';
			} else {
				// sekda_status = Done;

				$check_if_mat_b_alredy_reserved = DB::connection('sqlsrv')->select(DB::raw("SELECT *
					  FROM [fabric_reservations] 
					  WHERE material = '".$line->material."' AND bagno = '".$line->bagno."' 
					  AND skeda_status = 'Active'
					  AND id NOT IN ('".$line->id."')
					  "));
				// dd($check_if_mat_b_alredy_reserved);
				
				// if ($line->bagno == '240512') {
				// 	dd(!empty($check_if_mat_b_alredy_reserved));
				// }
				

				if (!empty($check_if_mat_b_alredy_reserved) == TRUE) {
					// someting already reserved for this material and bagno
					// set Still in Use
					// dd('someting already reserved for this material and bagno');
					// $skeda_status = 'Still in Use';
					$skeda_status = 'Completed';
				} else {
					// nothnig reserved
					// set Leftover to Check
					// dd('nothnig reserved');
					$skeda_status = 'Leftover to Check';

					$check_if_mat_b_partialy_reserved = DB::connection('sqlsrv')->select(DB::raw("SELECT *
					  FROM [inbound_deliveries] 
					  WHERE document_no = '".$line->document_no."' AND material = '".$line->material."' AND bagno = '".$line->bagno."' 
					  AND reserve_status = 'Partially Reserved'
					  "));
					
					if (isset($check_if_mat_b_partialy_reserved[0]->id)) {
						$skeda_status = 'Completed';
					}

				}
			}
			// dd($skeda_status);

			$table = fabric_reservation::findOrFail($line->id);
			$table->skeda_status = $skeda_status;
			$table->save();

		}
		return redirect('fabric_reservation_table');
	}

	public function declare_no_leftover($id) {
		// dd($id);

		$skeda_status = 'Completed';

		$table = fabric_reservation::findOrFail($id);
		$table->skeda_status = $skeda_status;
		$table->save();

		return redirect('leftover_table');
	}

	public function declare_leftover($id) {
		// dd($id);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				FROM [fabric_reservations]
				WHERE id = '".$id."' "));
		// dd($data);

		return view('planner.declare_leftover', compact('data'));
	}

	public function declare_leftover_post(Request $request) {

		$this->validate($request, ['leftover_qty'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl;)
		// dd($input);

		$id = $input['id'];
		$leftover_qty = $input['leftover_qty'];


		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				FROM [fabric_reservations]
				WHERE id = '".$id."' "));

		// dd($data);

		$skeda = $data[0]->skeda;
		$reservation_date = $data[0]->reservation_date;
		$document_no = $data[0]->document_no;
		$material = $data[0]->material;
		$bagno = $data[0]->bagno;
		$preforigin = $data[0]->preforigin;
		$qty_reserved_m = round((float)$data[0]->qty_reserved_m,2);
		$qty_leftover_m = round((float)$leftover_qty,2);
		
		$operator = Session::get('operator');
		$comment = trim($input['comment']);
		
		$frez_id = $id;
		$inbd_id = $data[0]->inbd_id;

		// dd($document_no.'-Leftover');

		// save in leftover table
		$table = new leftover_table;
		$table->skeda = $skeda;
		$table->reservation_date = $reservation_date;
		$table->document_no = $document_no;
		$table->material = $material;
		$table->bagno = $bagno;
		$table->preforigin = $preforigin;
		$table->qty_reserved_m = $qty_reserved_m;
		$table->qty_leftover_m = $qty_leftover_m;
		$table->operator = $operator;
		$table->comment = $comment;		
		$table->frez_id = $frez_id;		
		$table->inbd_id = $inbd_id;		
		$table->save();

		// save in inbound table
		$find = inbound_delivery::findOrFail($inbd_id);
		$posting_date = $find->posting_date;
		$reserve_status = "Not Reserved";
		$qty_received_m = $qty_leftover_m;
		$type = "Leftover";
		
		$table1 = new inbound_delivery;
		$table1->document_no = $document_no.'-Leftover';
		$table1->posting_date = $posting_date;
		$table1->material = $material;
		$table1->bagno = $bagno;
		$table1->qty_received_m = $qty_received_m;
		$table1->preforigin = $preforigin;
		$table1->reserve_status = $reserve_status;
		$table1->type = $type;
		$table1->save();

		// update fabric reservation table
		$skeda_status = 'Completed';
		$table2 = fabric_reservation::findOrFail($id);
		$table2->skeda_status = $skeda_status;
		$table2->save();

		return redirect('leftover_table');
	}

// MATERIAL COMMENTS

	public function material_comment_table () {

		// dd('test');
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [material_comments]
				  ORDER BY id asc"));
		// dd($data);
		return view('planner.material_comment_table', compact('data'));
	}

	public function material_comment_new() {

		return view('planner.material_comment_new');
	}

	public function material_comment_new_post(Request $request) {

		$this->validate($request, ['material'=>'required','standard_comment'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl;)
		// dd($input);

		$material = trim($input['material']);
		$standard_comment = trim($input['standard_comment']);

		// dd($standard_comment);

		$table = new material_comments;
		$table->material = $material;
		$table->standard_comment = $standard_comment;
		$table->save();

		// dd('test');
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [material_comments]
				  ORDER BY id asc"));
		// dd($data);
		return view('planner.material_comment_table', compact('data'));
	}

	public function material_comment_edit($id) {

		$data = material_comments::findOrFail($id);
		return view('planner.material_comment_edit', compact('data'));
	}

	public function material_comment_edit_post(Request $request) {

		$this->validate($request, ['material'=>'required','standard_comment'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl;)
		// dd($input);

		$id = $input['id'];
		$material = trim($input['material']);
		$standard_comment = trim($input['standard_comment']);

		$data = material_comments::findOrFail($id);
		$data->material = $material;
		$data->standard_comment = $standard_comment;
		$data->save();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [material_comments]
				  ORDER BY id asc"));
		// dd($data);
		return view('planner.material_comment_table', compact('data'));


	}

	public function material_comment_delete_post(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl;)
		// dd($input);

		$id = $input['id'];

		$delete = material_comments::where('id', $id)->delete();

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
				  FROM [material_comments]
				  ORDER BY id asc"));
		// dd($data);
		return view('planner.material_comment_table', compact('data'));

	}



}	


