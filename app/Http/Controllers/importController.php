<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Request;

use App\Consumption;
use App\tpp_material;
use App\marker_header;
use App\marker_header_temp;
use App\marker_line;
use App\marker_line_temp;
use App\pro_skeda;
use App\paspul;
use App\paspul_line;

use App\mattress;
use App\mattress_details;
use App\mattress_markers;
use App\mattress_pro;
use App\mattress_phases;

use App\User;
use DB;

class importController extends Controller {

	public function index()
	{
		//

		// $work_place = "PLANNER";

		// $operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		//       ,[operator]
		//       ,[device]
		//       ,[device_array]
		//   FROM [operators]
		//   WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// // dd($operators);

		// $operator = Session::get('operator');

		// return view('Import.index', compact('operator', 'operators'));
		return view('Import.index');
	}

	public function postImportConsPo (Request $request) {
	    $getSheetName = Excel::load(Request::file('file1'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file1'))->chunk(50, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                foreach($readerarray as $row)
	                {
						$po = $row['po'];
						// dd($row['po']);

								/*
								$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT case when COMP.[Status] = 3 then 'Released' else 'Check status' end as [Status],
									      COMP.[Prod_ Order No_],
									      COM2.[Item No_],
									      COM2.[Variant Code],
									      cast(COMP.[Quantity] as float) as [Qty per], 
									      PO.[Cutting Prod_ line], 
									      case when PO.[To be finished] = 1 then 'Yes' else 'No' end as [To be finished],
									      cast(SL.OrderedQty as float) as OrderedQty, 
									      cast(COMP.[Quantity] * SL.OrderedQty as float) as [Theorethical Consumption MT],
									      cast(COMP.[Quantity] * SL.OrderedQty * 0.03 as float) as [Possible overcons MT 3%]

									  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as COMP left join
									  
									  (SELECT [No_]
									      ,[Description 2]

									  FROM [Gordon_LIVE].[dbo].[GORDON\$Item]) as ITM on ITM.[No_] = COMP.[Item No_] left join
									  
									  (SELECT [Status],
									      [Prod_ Order No_],
									      ([Item No_]) as [Item No_],
									      ([Variant Code]) as [Variant Code],
									      ([Quantity]) as Quantity

									  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component]
									  where [PfsHorz Component Group] = 'MTRLS' and [Prod_ Order No_] not like 'C%' and [Prod_ Order No_] not like 'S%' and [status] = 3
									  group by [Status],[Item No_],[Variant Code],[Quantity],
									       [Prod_ Order No_]

									      ) as COM2 on COM2.[Prod_ Order No_] = COMP.[Prod_ Order No_] and COM2.[Item No_] = COMP.[Item No_] and COM2.[Variant Code] = COMP.[Variant Code] right join
									      
									      (SELECT [Status]
									      ,[Prod_ Order No_]
									      --,[Item No_]
									      --,[Variant Code]
									      ,max([Quantity]) as Quantity
									      ,[Location Code]

									  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as POC left join
									  
									  (SELECT [No_]
									      ,[Description 2]

									  FROM [Gordon_LIVE].[dbo].[GORDON\$Item]) as ITM on ITM.[No_] = POC.[Item No_]
									  where [PfsHorz Component Group] = 'MTRLS' and [Prod_ Order No_] not like 'C%' and [Prod_ Order No_] not like 'S%'
									  and [status] = 3 and [Quantity] <> 0 and ITM.[Description 2] = 'fabric'
									  group by [Status]
									      ,[Prod_ Order No_]
									      --,[Item No_]
									      --,[Variant Code]
									      ,[Location Code]--,[Quantity]
									      --having [quantity] = max([quantity])
									) as MX on MX.[Prod_ Order No_] = COMP.[Prod_ Order No_] and MX.[Quantity] = COMP.[Quantity] left join

									(SELECT 
									      [Shortcut Dimension 2 Code]
									      ,[To be finished]
									      ,[Cutting Prod_ Line]
									   
									  FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order]
									  where [Status] = 3
									  group by [Shortcut Dimension 2 Code]
									      ,[To be finished] 
									      ,[Cutting Prod_ Line]) as PO on PO.[Shortcut Dimension 2 Code] = COMP.[Prod_ Order No_] left join
									      
									      (SELECT 
									      [Shortcut Dimension 2 Code]

									      ,sum([PfsOrder Quantity]) as OrderedQty
									    
									  FROM [Gordon_LIVE].[dbo].[GORDON\$Sales Line]
									  group by  [Shortcut Dimension 2 Code]) as SL on SL.[Shortcut Dimension 2 Code] = COMP.[Prod_ Order No_]
									  
									  where --[PfsHorz Component Group] = 'MTRLS' and 
										COMP.[Prod_ Order No_] not like 'C%' and COMP.[Prod_ Order No_] not like 'S%' and COMP.[Prod_ Order No_] like '%".$po."'
										and ITM.[Description 2] = 'fabric' and COMP.[Quantity] <> 0 and comp.[status] = 3
									  group by COMP.[Status]
									      ,COMP.[Prod_ Order No_]
									      ,COM2.[ITem No_]
									      ,COM2.[Variant Code],COMP.[Quantity], PO.[Cutting Prod_ line], PO.[To be finished],SL.OrderedQty
									  order by [Prod_ Order No_]"));


										// dd($data[0]->{"Prod_ Order No_"});



										if (!isset($data[0])) {
											$msg = "Problem to get PO"; 
											return view('cons.error', compact('msg'));

										} else {
											
											$po = $data[0]->{"Prod_ Order No_"};

											$po = substr($po, -6);
											// dd($po);
											$status = $data[0]->{"Status"};
											$to_be_finished = $data[0]->{"To be finished"};
											$cut_prod_line = $data[0]->{"Cutting Prod_ line"};
											$order_qty = (int)$data[0]->{"OrderedQty"};

											$main_item = $data[0]->{"Item No_"};
											$main_variant = $data[0]->{"Variant Code"};
											$qty_per = round($data[0]->{"Qty per"},3);

											$teo_cons = round($data[0]->{"Theorethical Consumption MT"},3);
											$teo_cons_eur = NULL;

											$over_cons = round($data[0]->{"Possible overcons MT 3%"},3);
											$over_cons_eur = NULL;
											$percentage = 0.03;

											$extra_item = NULL;
											$extra_variant = NULL;
											$extra_consumed = NULL;
											$extra_consumed_eur = NULL;

											$error = NULL;

													
											try {
												$table = new Consumption;

												$table->po = $po;
												$table->status = $status;
												$table->to_be_finished = $to_be_finished;
												$table->cut_prod_line = $cut_prod_line;
												$table->order_qty = $order_qty;

												$table->main_item = $main_item;
												$table->main_variant = $main_variant;
												$table->qty_per = $qty_per;

												$table->teo_cons = $teo_cons;
												$table->teo_cons_eur = $teo_cons_eur;

												$table->over_cons = $over_cons;
												$table->over_cons_eur = $over_cons_eur;
												$table->percentage = $percentage;

												$table->extra_item = $extra_item;
												$table->extra_variant = $extra_variant;
												$table->extra_consumed = $extra_consumed;
												$table->extra_consumed_eur = $extra_consumed_eur;

												$table->error = $error;

												$table->save();
												}
											catch (\Illuminate\Database\QueryException $e) {
													$msg = "Problem to save, check if PO already exist."; 
													return view('cons.error', compact('msg'));
												}

										}

							*/


						// Remove po from local db 
						$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id, po FROM consumptions WHERE po = '".$po."' "));
						// dd($data[0]->id);


						if (isset($data[0]->id)) {
							$l = Consumption::findOrFail($data[0]->id);
					    	$l->delete();									
						}
						


						
	                }

	            });
	    }

		return redirect('/');
	}

	public function postImportUpdatePass(Request $request) {
	    
	    
	    
	    $sql = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM users"));

	    for ($i=0; $i < count($sql) ; $i++) { 
	    	
	    	// dd($sql[$i]->password);

	    	$password = bcrypt($sql[$i]->name);
	    	// dd($password);

			$sql2 = DB::connection('sqlsrv')->select(DB::raw("
					SET NOCOUNT ON;
					UPDATE [cutting].[dbo].[users]
					SET password = '".$password."'
					WHERE name = '".$sql[$i]->name."';
					SELECT TOP 1 [id] FROM [cutting].[dbo].[users];
				"));	    	

	    }

		return redirect('/');
	}

	public function postImportMaterials(Request $request) {

		$getSheetName = Excel::load(Request::file('file1'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        DB::table('tpp_materials')->truncate();
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file1'))->chunk(5000, function ($reader)
	            
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                foreach($readerarray as $row)
	                {
						// dd($row);
						$tpp_materials_in_gordon = $row['tpp_materials_in_gordon'];
						// dd($row['tpp_materials_in_gordon']);

						$userbulk = new tpp_material;
						$userbulk->tpp_material = $tpp_materials_in_gordon;
												
						$userbulk->save();
						
	                }

	            });
	    }
		return redirect('/');
	}

	public function postImportWastage_report(Request $request) {

		$getSheetName = Excel::load(Request::file('file2'))->getSheetNames();
	    
	    foreach($getSheetName as $sheetName)
	    {
	        
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file2'))->chunk(5000, function ($reader)
	        
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                foreach($readerarray as $row)
	                {
						// dd($row);
						
						$skeda = $row['skeda'];
						$log_rep = $row['reported_to_log'];

						$sql2 = DB::connection('sqlsrv')->select(DB::raw("
							SET NOCOUNT ON;
							UPDATE [cutting].[dbo].[wastages]
							SET log_rep = '".$log_rep."'
							WHERE skeda = '".$skeda."';
							SELECT TOP 1 [id] FROM [cutting].[dbo].[wastages];
						"));
	                }

	            });
	    }
		return redirect('wastage_table');
	}

	public function postImport_marker(Request $request) {
		// dd($request);
		// dd(" asdad asdas asdasd ");

		$work_place = "PLANNER";
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('planner.error',compact('msg', 'operator', 'operators'));
			// $operator = "admin";
		}

		// DB::table('marker_header_temps')->truncate();
		// DB::table('marker_line_temps')->truncate();

		// XML --------------------------
		$xmlString = file_get_contents(Request::file('file3'));
		// dd($xmlString);

        $xmlObject = simplexml_load_string($xmlString);
        // dd($xmlObject);

        $json = json_encode($xmlObject);
        // dd($json);

        $phpArray = json_decode($json, true); 
   		// dd($phpArray);

   		// dd($phpArray['Tolerances']['MarkerContent']['NewVariant']);
   		// print_r($phpArray['Marker']['@attributes']['Name']);

   		$marker_name = $phpArray['Marker']['@attributes']['Name'];

   		function get_string_between($string, $start, $end){
		    $string = ' ' . $string;
		    $ini = strpos($string, $start);
		    if ($ini == 0) return '';
		    $ini += strlen($start);
		    $len = strpos($string, $end, $ini) - $ini;
		    return substr($string, $ini, $len);
		}

		$start = "PLX\\";
		$end = ".PLX";
   		$marker_name = get_string_between($marker_name, $start, $end);
   		// print_r('marker_name: '.$marker_name);
   		// print_r('<br>');
		// print_r('<br>');

   		$check_marker_name = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM marker_headers WHERE marker_name = '".$marker_name."' "));

   		if (isset($check_marker_name[0])) {
   			dd("Marker already exist in marker_headers table");
   		}
   		//-----------------

		$marker_width = $phpArray['WidthDescription']['Width']['@attributes']['Value'];
   		// print_r('marker_width: '.$marker_width);
   		// print_r('<br>');	

   		$marker_length = $phpArray['WidthDescription']['Length']['@attributes']['Value'];
   		// print_r('marker_length: '.$marker_length);
   		// print_r('<br>');
		// print_r('<br>');
   		//---------------------

   		$marker_type = $phpArray['Fabric']['@attributes']['MarkerType'];
   		// print_r('marker_type: '.$marker_type);
   		// print_r('<br>');

   		$marker_code = $phpArray['Marker']['@attributes']['Code'];
   		// print_r('marker_code: '.$marker_code);
   		// print_r('<br>');

   		$fabric_type = $phpArray['Fabric']['@attributes']['Type'];
   		// print_r('fabric_type: '.$fabric_type);
   		// print_r('<br>');

   		$constraint = $phpArray['Fabric']['@attributes']['ConstraintFile'];
   		// print_r('constraint: '.$constraint);
   		// print_r('<br>');
		// print_r('<br>');
   		//----------------------

   		$spacing_around_pieces = $phpArray['Tolerances']['GlobalSpacing']['@attributes']['Value'];
   		// print_r('spacing_around_pieces: '.$spacing_around_pieces);
   		// print_r('<br>');

   		$spacing_around_pieces_top = $phpArray['Tolerances']['FabricEdges']['Top']['@attributes']['Value'];
   		// print_r('spacing_around_pieces_top: '.$spacing_around_pieces_top);
   		// print_r('<br>');

   		$spacing_around_pieces_bottom = $phpArray['Tolerances']['FabricEdges']['Bottom']['@attributes']['Value'];
   		// print_r('spacing_around_pieces_bottom: '.$spacing_around_pieces_bottom);
   		// print_r('<br>');

   		$spacing_around_pieces_right = $phpArray['Tolerances']['FabricEdges']['Right']['@attributes']['Value'];
   		// print_r('spacing_around_pieces_right: '.$spacing_around_pieces_right);
   		// print_r('<br>');

   		$spacing_around_pieces_left = $phpArray['Tolerances']['FabricEdges']['Left']['@attributes']['Value'];
   		// print_r('spacing_around_pieces_left: '.$spacing_around_pieces_left);
   		// print_r('<br>');
   		// print_r('<br>');
  		//-------------------

   		$processing_date = $phpArray['Marker']['@attributes']['ProcessingDate'];
   		// print_r('processing_date: '.$processing_date);
   		// print_r('<br>');
   		// print_r('<br>');
   		//-------------------

		$efficiency = $phpArray['WidthDescription']['Efficiency']['@attributes']['Value'];
   		// print_r('efficiency: '.$efficiency);
   		// print_r('<br>');

   		$cutting_perimeter = $phpArray['Tolerances']['Statistics']['CutPerimeter']['@attributes']['Value'];
   		// print_r('cutting_perimeter: '.$cutting_perimeter);
   		// print_r('<br>');

   		$perimeter = $phpArray['Tolerances']['Statistics']['Perimeter']['@attributes']['Value'];
   		// print_r('perimeter: '.$perimeter);
   		// print_r('<br>');

   		$average_consumption = $phpArray['WidthDescription']['MetersByVariants']['@attributes']['Value'];
   		// print_r('average_consumption: '.$average_consumption);
   		// print_r('<br>');

   		$lines = $phpArray['Tolerances']['Statistics']['Lines']['@attributes']['Value'];
   		// print_r('lines: '.$lines);
   		// print_r('<br>');

   		$curves = $phpArray['Tolerances']['Statistics']['Curves']['@attributes']['Value'];
   		// print_r('curves: '.$curves);
   		// print_r('<br>');

   		$areas = $phpArray['Tolerances']['Statistics']['Area']['@attributes']['Value'];
   		// print_r('areas: '.$areas);
   		// print_r('<br>');

   		$angles = $phpArray['Tolerances']['Statistics']['Angles']['@attributes']['Value'];
   		// print_r('angles: '.$angles);
   		// print_r('<br>');

   		$notches = $phpArray['Tolerances']['Statistics']['Notches']['@attributes']['Value'];
   		// print_r('notches: '.$notches);
   		// print_r('<br>');

   		$total_pcs = $phpArray['Tolerances']['Statistics']['TotalPieces']['@attributes']['Value'];
   		// print_r('total_pcs: '.$total_pcs);
   		// print_r('<br>');
   		// print_r('<br>');
   		//----------------------

   		// $variant = $phpArray['Tolerances']['MarkerContent']['NewVariant'][0]['Variant']['@attributes']['Value'];
 		// $model = $phpArray['Tolerances']['MarkerContent']['NewVariant'][0]['Model']['@attributes']['Value'];

 		// $variant_model = $variant."|".$model;
 		// print_r('variant_model: '.$variant_model);
   		// print_r('<br>');

   		// $key = $variant_model."|".$constraint."|".$spacing_around_pieces;
   		// print_r('key: '.$key);
   		// print_r('<br>');
   		// print_r('<br>');

		//----------------------

   		$min_length = 0;
 		// print_r('min_length: '.$min_length);
   		// print_r('<br>');

   		$status = "ACTIVE";
   		// print_r('status: '.$status);
   		// print_r('<br>');
   		// print_r('<br>');

   		//-----------------------

   		// dd(round((float)str_replace(',', '.',$marker_length),3));
   		// $arr[] = '';
 		// $arr_line[] ='';

		/*
    	$table = new marker_header;

		$table->marker_name = $marker_name;

		$table->marker_width = round((float)str_replace(',', '.',$marker_width),3);
		$table->marker_length = round((float)str_replace(',', '.',$marker_length),3);

		$table->marker_type = $marker_type;
		$table->marker_code = $marker_code;
		$table->fabric_type = $fabric_type;
		$table->constraint = $constraint;

		$table->spacing_around_pieces = round((float)str_replace(',', '.',$spacing_around_pieces),3);
		$table->spacing_around_pieces_top = round((float)str_replace(',', '.',$spacing_around_pieces_top),3);
		$table->spacing_around_pieces_bottom = round((float)str_replace(',', '.',$spacing_around_pieces_bottom),3);
		$table->spacing_around_pieces_right = round((float)str_replace(',', '.',$spacing_around_pieces_right),3);
		$table->spacing_around_pieces_left = round((float)str_replace(',', '.',$spacing_around_pieces_left),3);

		$table->processing_date = date( "Y-m-d", strtotime($processing_date));

		$table->efficiency = round((float)str_replace(',', '.',$efficiency),3);
		$table->cutting_perimeter = round((float)str_replace(',', '.',$cutting_perimeter),3);
		$table->perimeter = round((float)str_replace(',', '.',$perimeter),3);
		$table->average_consumption = round((float)str_replace(',', '.',$average_consumption),3);
		$table->lines = round((float)str_replace(',', '.',$lines),3);
		$table->curves = round((float)str_replace(',', '.',$curves),3);
		$table->areas = round((float)str_replace(',', '.',$areas),3);
		$table->angles = round((float)str_replace(',', '.',$angles),3);
		$table->notches = round((float)str_replace(',', '.',$notches),3);
		$table->total_pcs = round((float)str_replace(',', '.',$total_pcs),3);

		$table->variant_model = $variant_model;
		$table->key = $key;

		$table->min_length = round((float)$min_length,3);
		$table->status = "ACTIVE";

		$table->save();
		*/

		//-----------------------

   		// $l[] = '';
   		$a[] = '';
   		$mv[] = '';
   		for ($i=0; $i < count($phpArray['Tolerances']['MarkerContent']['NewVariant']); $i++) { 
   			# code...
   			// print_r($i);
   			// print_r('<br>');


   			$model = $phpArray['Tolerances']['MarkerContent']['NewVariant'][$i]['Model']['@attributes']['Value'];
   			$variant = $phpArray['Tolerances']['MarkerContent']['NewVariant'][$i]['Variant']['@attributes']['Value'];
   			$size = $phpArray['Tolerances']['MarkerContent']['NewVariant'][$i]['Size']['@attributes']['Value'];
   			$qty = $phpArray['Tolerances']['MarkerContent']['NewVariant'][$i]['Quantity']['@attributes']['Value'];

   			$exist = strpos($variant, "_");
   			// dd($exist);

   			$st = explode('_', $model);
			// dd($ex[1]);
			$style = $st[1];

			if ($style == 'F') {
				$style = $st[2];
			}
			// print_r("Style: ".$style);
			// print_r('<br>');

			$si = explode('_', $size);
			// dd(count($si));
			// dd(substr($style, 0, 4));

			if(count($si) > 1) {
				if ($si[0] == '1' AND $si[1] == '2') {
					$size = $si[0].'/'.$si[1];
				} else if ($si[0] == '3' AND $si[1] == '4' AND substr($style, 0, 4) == "MODC" ){
					$size = $si[0].'/'.$si[1];
				} else {
					$size = $si[0].'-'.$si[1];
				}
			}

			$size = str_replace(":" , "" , $size);
			// print_r("Size: ".$size);

   			// print_r('style:'.$style.' size: '.$size.' qty: '.$qty.' model: '.$model.' variant: '.$variant);
   			// print_r('<br>');

   			$style_size = $style.' '.$size;
   			$model_variant = $model."#".$variant;

   			// $line = $style_size.'|'.$qty;
   			$line = ['style_size'=>$style_size,'qty'=>$qty, 'model'=>$model ,'variant' => $variant];
   			$line_mv = ['model_variant'=>$model_variant];
   			// $line = [$style_size =>$qty];
	   		array_push( $a , $line );
	   		array_push( $mv , $line_mv );

   			
   		}	

   		$a = array_filter($a);
   		$mv = array_filter($mv);
   		// print_r('<br>');
   		// print_r($mv);
   		// print_r('<br>');

   		// print_r('<br>l:');
   		// print_r(array_filter($l));
   		// print_r('<br>');


   		$a1 [] = '';
   		foreach ($a as $key1 => $value) {
			// print_r('line: '.$value['style_size'].' , qty: '.$value['qty']);
			// print_r('<br>');

			$t = 0;	
			$qty = 0;
			foreach ($a as $k => $v) {
				// print_r($v);
				if ($value['style_size'] == $v['style_size']) {
					$t += 1;
					$qty += $v['qty'];
				}
			}

			$line2 = ['style_size'=>$value['style_size'],'qty'=>$qty];
			array_push( $a1 , $line2 );
   		}

   		// print_r('<br>');
   		$a1 = array_filter($a1);
   		// print_r($a1);
   		// print_r('<br>');

   		$a2 = array_map("unserialize", array_unique(array_map("serialize", $a1)));
 		$mv = array_map("unserialize", array_unique(array_map("serialize", $mv)));

		// print_r('<br>');
  		// print_r($mv);
  		// print_r('<br>');

   		$data = $a2;
   		$marker_header_id = 0;
   		// print_r($data);

   		$data2[] = '';
   		foreach ($a2 as $key2 => $value1) {

   			// dd($value1['style_size']);
   			$style_size = $value1['style_size'];
   			$qty = $value1['qty'];
   			// $model = $value1['model'];
   			// $variant = $value1['variant'];

   			$t = explode(" ", $style_size);
   			$style = strtoupper(trim($t[0]));
   			// dd($style);

   			$size = strtoupper(trim($t[1]));
   			// dd($size);

   			$line3 =  ['style'=>$style,'size'=>$size,'qty'=>$qty];
			array_push($data2,$line3);
   		}

   		$variant_model = '';
   		foreach ($mv as $e) {
   			// dd($e['model_variant']);
   			$variant_model = $e['model_variant'].'|'.$variant_model;
   		}

   		// dd($variant_model);
   		$key = $variant_model."".$constraint."|".$spacing_around_pieces;
   		// dd($key);
   		$variant_model= substr($variant_model, 0,-1);
   		// dd($variant_model);
   		$data = array_filter($data2);
   		// dd($data);

   		// return view('marker.import_conf',compact('data', 'marker_name','marker_header_id'));
   		return view('marker.import_conf',compact('data', 'marker_name','marker_header_id',
   			'marker_width','marker_length','marker_type','marker_code','fabric_type','constraint',
   			'spacing_around_pieces','spacing_around_pieces_top','spacing_around_pieces_bottom','spacing_around_pieces_right','spacing_around_pieces_left',
   			'processing_date',
   			'efficiency','cutting_perimeter','perimeter','average_consumption','lines','curves','areas','angles','notches','total_pcs',
   			'variant_model','key',
   			'min_length','status',
   			'operator'
   			));
	}

	public function postImport_skeda(Request $request) {
		// dd("sss");
		$work_place = "PLANNER";
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msg ='Operator must be logged!';
			return view('planner.error',compact('msg', 'operator', 'operators'));
			// $operator = 'planner';
		}

		$getSheetName = Excel::load(Request::file('file4'))->getSheetNames();
	    
		$pro = '';
		Session::set('pro', null);

		// $msg[] = '';
	    foreach($getSheetName as $sheetName)
	    {
	        if ($sheetName == 'PRO') {
	        	Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file4'))->chunk(5000, function ($reader)
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                // foreach(array_slice($readerarray,1) as $row)
	                foreach($readerarray as $row)
	                {
						// print_r('PRO');
						// dd($row);

						$pro_id = $row['pro_id'];

				   		// $check_pro_id = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM pro_skedas WHERE pro_id = '".$pro_id."' "));
				   		// if (isset($check_pro_id[0])) {
				   		// 	dd("Pro_id already exist in pro_skeda table");
				   		// }

						$pro = $row['pro'];
						$skeda = $row['skeda'];
						
						$padprint_item = strtoupper($row['padprint_item']);
						$padprint_color = strtoupper($row['padprint_color']);
						
						$style_size = strtoupper($row['style_size']);
						$s = explode(" ", $style_size);
						$style = $s[0];
						$size = $s[1];

						$bom_cons_per_pcs = (float)$row['bom_cons_per_pcs'];
						$bom_cons_per_pcs_a;
						$extra_mat_a;
						
						// dd($mattress);
						try {
							$table = new pro_skeda;

							$table->pro_id = $pro_id;
							$table->pro = $pro;
							$table->skeda = $skeda;

							$table->padprint_item = $padprint_item;
							$table->padprint_color = $padprint_color;

							$table->style = $style;
							$table->size = $size;
							$table->style_size = $style_size;

							$table->bom_cons_per_pcs = $bom_cons_per_pcs;
							// $table->bom_cons_per_pcs_a = $bom_cons_per_pcs_a;
							// $table->extra_mat_a = $extra_mat_a;
							
							$table->save();
						}
						catch (\Illuminate\Database\QueryException $e) {
							// $msg = '';
							// return view('po.error');
							$pro_err = $pro_id.'|';
							// 
							$pro_get = Session::get('pro');
							// dd($msg);

							$pro = $pro_get.$pro_err;
							Session::set('pro', $pro);
						}
					}
	            });
	        } elseif ($sheetName == 'PAS') {
	        	Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file4'))->chunk(5000, function ($reader)
	        
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                // foreach(array_slice($readerarray,1) as $row)
	                foreach($readerarray as $row)
	                {
						// print_r('PAS');
						// dd($row);
	                	$paspul_roll = $row['paspul_roll'];	
	                							
						// $check_paspul_roll = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM paspuls WHERE paspul_roll = '".$paspul_roll."' "));
				  		// if (isset($check_paspul_roll[0])) {
				  		// 		dd("Paspul_roll already exist in paspul table");
				  		// }

						$sap_su = $row['sap_su'];
						$material = $row['material'];
						$color_desc = strtoupper($row['color_desc']);
						$dye_lot = $row['dye_lot'];
						$paspul_type = $row['paspul_type'];

						$kotur_width = (float)$row['kotur_width'];
						$kotur_width_without_tension = (float)$row['kotur_width_without_tension'];
						$koturi_planned = (float)$row['koturi_planned'];
						$kotur_actual = $koturi_planned;	// koturi_planned
						$rewound_length = (float)$row['rewound_length'];
						$rewound_length_a = $rewound_length; // rewound_length

						$width = ((float)$kotur_width*(float)$koturi_planned)/1000; // ?

						$pasbin = ''; //auto assign after
						$skeda_item_type = $row['skeda_item_type'];
						$skeda = $row['skeda'];
						$skeda_status = 'OPEN'; // OPEN/CLOSED ???????????????????????
						
						if ($skeda_item_type == 'PA') {
							$rewound_roll_unit_of_measure = 'meter';
						} else {
							$rewound_roll_unit_of_measure = 'ploce';
						}

						//$position = 0; // auto ??????????????????????????????????????
						$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
						FROM 
						(
						SELECT position 
						FROM [cutting].[dbo].[paspuls] as p
						JOIN [cutting].[dbo].[paspul_lines] as pl ON pl.paspul_roll_id = p.id AND active = '1'
						WHERE pl.location = 'NOT_SET' --and pl.status = 'NOT_SET'
						) SQ
						ORDER BY position desc"));

						if (isset($position[0])) {
							$position = (int)$position[0]->position;
						} else {
							$position = 0;
						}
						$position = $position + 1;

						$priority = 2; // 0
						$comment_office = ''; // ''
						$comment_operator = ''; // ''
						$call_shift_manager = 0; // default 0

						$rewinding_method = $row['rewinding_method'];

						//-----
						$status = 'NOT_SET';
						$location = 'NOT_SET'; //PRW or NOT_SET //????????????????
						$device; //null or insert  	//????????????????
						$active = 1;
						$operator1 = Session::get('operator');
						$operator2;
						//-----

						try {

							$table = new paspul;

							$table->paspul_roll = $paspul_roll;
							$table->sap_su = $sap_su;
							$table->material = $material;
							$table->color_desc = $color_desc;
							$table->dye_lot = $dye_lot;
							$table->paspul_type = $paspul_type;
							
							$table->width = $width;
							$table->kotur_width = $kotur_width;
							$table->kotur_width_without_tension = $kotur_width_without_tension;
							$table->kotur_planned = $koturi_planned;
							$table->kotur_actual = $kotur_actual;
							$table->rewound_length = $rewound_length;
							$table->rewound_length_a = $rewound_length_a;

							$table->pasbin = $pasbin;
							$table->skeda_item_type = $skeda_item_type;
							$table->skeda = $skeda;
							$table->skeda_status = $skeda_status;
							$table->rewound_roll_unit_of_measure = $rewound_roll_unit_of_measure;
							$table->position = $position;
							$table->priority = $priority;
							$table->comment_office = $comment_office;
							$table->comment_operator = $comment_operator;
							$table->call_shift_manager = $call_shift_manager;

							$table->rewinding_method = $rewinding_method;
							
							$table->save();

							// check if exist paspul_roll_id ??????????????????????????????????
							$table1 = new paspul_line;

							$table1->paspul_roll_id = $table->id;
							$table1->paspul_roll = $table->paspul_roll;

							$table1->status = $status;
							$table1->location = $location;
							$table1->device;
							$table1->active = $active;

							$table1->operator1 = Session::get('operator');
							$table1->operator2;

							$table1->save();
						}
						catch (\Illuminate\Database\QueryException $e) {
							
							$pas_err = $paspul_roll.'|';
							$pas_get = Session::get('pas');
							// dd($msg);

							$pas = $pas_get.$pas_err;
							Session::set('pas', $pas);

						}
	                }
	            });

	        } elseif ($sheetName == 'MAT') {
	        	Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file4'))->chunk(5000, function ($reader)
	        
	            {
	                $readerarray = $reader->toArray();
	                //var_dump($readerarray);
	                // foreach(array_slice($readerarray, 1) as $row)
	                foreach($readerarray as $row)
	                {
						
						// print_r('MAT');
						// dd($row);

						// +++++++++++++ mattresses
						$mattress = $row['mattress']; 
						$find_in_mattresses = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM mattresses WHERE mattress = '".$mattress."' "));
						if (isset($find_in_mattresses[0])) {
							
							// dd("Mattress '".$mattress."' already exist in mattresses table");
							$err = Session::get('err');
							// dd($msg);
			   				$err0 = "Mattress '".$mattress."' already exist in mattresses table!";
							$err = $err.'#'.$err0;
							Session::set('err', $err);

							$skeda_item_type = $row['skeda_item_type'];

						} else {

							$g_bin; //not mandatory till NOT_SET / progressive //???
							$material = $row['material'];
							$dye_lot = $row['dye_lot'];
							$color_desc = $row['color_desc'];
							$width_theor_usable = (float)$row['width_theor_usable'];
							$skeda = $row['skeda'];
							$skeda_item_type = $row['skeda_item_type'];
							$skeda_status = 'OPEN';  // OPEN/CLOSE ?????????????????
							$spreading_method = $row['spreading_method'];
							
							
						}

						$find_in_mattress_details = DB::connection('sqlsrv')->select(DB::raw("SELECT [mattress] FROM [mattress_details] WHERE [mattress] = '".$mattress."' "));
						if (isset($find_in_mattress_details[0])) {

							// dd("Mattress '".$mattress."' already exist in mattress_details table");
							$err = Session::get('err');
							// dd($msg);
			   				$err0 = "Mattress '".$mattress."' already exist in mattress_details table!";
							$err = $err.'#'.$err0;
							Session::set('err', $err);

						} else {

							// ++++++++++++++ mattress_details
							$layers = (float)$row['layers'];
							$layers_a = $layers; 
							$length_usable = round((float)$row['length_usable'],2);
							$cons_planned = round((float)$row['cons_planned'],2);
							$cons_actual = round((float)$row['cons_planned'],2);
							$extra = (floaT)$row['extra'];
							$pcs_bundle;	//manualy
							$layers_partial = 0; // MM => manualy
							$position = 1; // check last position with NOT_SET status and loc   ??????
							$overlapping = $row['overlapping'];
							$tpa_number = $row['tpa_number'];

							if ($tpa_number == '') {
								$tpp_mat_keep_wastage = 0;  
							} else {
								$tpp_mat_keep_wastage = 1;  
							}

							$position = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 position 
							FROM 
							(
							SELECT position 
							FROM [cutting].[dbo].[mattress_details] as md
							JOIN [cutting].[dbo].[mattress_phases] as mp ON mp.[mattress_id] = md.[mattress_id] AND active = '1'
							WHERE mp.[location] = 'NOT_SET' --and mp.status = 'NOT_SET'
							) SQ
							ORDER BY position desc"));

							if (isset($position[0])) {
								$position = (int)$position[0]->position;
							} else {
								$position = 0;
							}
							$position = $position + 1;

							$priority = 0; // default  0 			???????????????

							$call_shift_manager = 0;  // default 0 //manualy after
							$test_marker = 0;  // default 0 	   //manualy	after
							
							$printed_marker = 0;  // default 0
							$mattress_packed = 0;  // default 0
							$all_pro_for_main_plant = 0; // check ?????????????????????????

							$bottom_paper;
							$layers_a_reasons;
							$comment_office;
							$comment_operator;
							$minimattress_code;
						}
						
						// ++++++++++++++++ mattress_markers
						$marker_name = $row['marker_name'];

						$find_in_marker_headers = DB::connection('sqlsrv')->select(DB::raw("SELECT id,marker_length,marker_width,min_length FROM marker_headers WHERE marker_name = '".$marker_name."' "));
				   		if (!isset($find_in_marker_headers[0])) {

				   			if (($skeda_item_type == 'MW') OR ($skeda_item_type == 'MB')){
				   				
				   				$marker_id = 0;
				   				$marker_name = '';
				   				$marker_name_orig = '';
								$marker_length = round((float)$row['length_usable'],3);
								$marker_width = 0;
								$min_length = 0;
	
				   			} else {

				   				// dd("Marker name '".$marker_name."' not exist in marker_headers table");
					   			$err = Session::get('err');
								// dd($err);
				   				$err0 = "For Mattress '".$mattress."', marker name not exist in marker_headers table, mattress is:".$skeda_item_type;
								$err = $err.'#'.$err0;
								Session::set('err', $err);

					   			//$mat_pro_get0 = Session::get('mat_pro0');
								// // dd($msg);
				   				//$mat_pro_err0 = $marker_name.'|';
								// $mat_pro0 = $mat_pro_get0.$mat_pro_err0;
				   				//Session::set('mat_pro0', $mat_pro0);
				   			}

				   		} else {

				   			$find_in_mattress_markers = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM mattress_markers WHERE mattress = '".$mattress."' "));
				   			if (isset($find_in_mattress_markers[0])) {

				   				// dd("Mattress '".$mattress."' already exist in mattress_markers table");

				   				$err = Session::get('err');
								// dd($msg);
				   				$err0 = "Mattress '".$mattress."' already exist in mattress_markers table!";
								$err = $err.'#'.$err0;
								Session::set('err', $err);

				   			} else {

				   				$marker_id = $find_in_marker_headers[0]->id; // find by marker_name
								$marker_name_orig = $marker_name;
								$marker_length = round((float)$find_in_marker_headers[0]->marker_length,3); // find by marker_name
								$marker_width = round((float)$find_in_marker_headers[0]->marker_width,3); // find by marker_name
								$min_length = (float)$find_in_marker_headers[0]->min_length; // find by marker_name	
				   			}
				   		}

						// ++++++++++++++++ mattress_phases
						$find_in_mattress_phases = DB::connection('sqlsrv')->select(DB::raw("SELECT id, active FROM mattress_phases WHERE mattress = '".$mattress."' "));
						if (isset($find_in_mattress_phases[0])) {

							// dd("Mattress '".$mattress."'and active  = 1,  already exist in mattress_phases table");

							$err = Session::get('err');
							// dd($msg);
			   				$err0 = "Mattress '".$mattress."' and active=1,  already exist in mattress_phases table!";
							$err = $err.'#'.$err0;
							Session::set('err', $err);
							
						} else {
							$status = 'NOT_SET'; // defult NOT_SET
							$location = 'NOT_SET'; //
							$device; //null or insert //??????????????????????????
							$active = 1; // defualt 0 // insert 1
							$operator1 = Session::get('operator');
							$operator2;
						}

						// ++++++++++++++++ mattress_pros
						// $marker_name;
						// list of $style_size; // ??????????????????????????????????????????????
						// list of $pro_pcs_layer;

						$find_in_marker_lines = DB::connection('sqlsrv')->select(DB::raw("SELECT style_size, pcs_on_layer FROM marker_lines WHERE marker_name = '".$marker_name."' "));
						if (!isset($find_in_marker_lines[0])) {

							if (($skeda_item_type == 'MW') OR ($skeda_item_type == 'MB')) {
								
								
								
							} else {
								// dd("Marker name ".$marker_name." not exist in marker_lines table");
					   			$err = Session::get('err');
								// dd($msg);
				   				$err0 = "For Mattress '".$mattress."', marker name not exist in marker_lines table, mattress is:".$skeda_item_type;
								$err = $err.'#'.$err0;
								Session::set('err', $err);

					   			//$mat_pro_get1 = Session::get('mat_pro1');
								//dd($msg);
				   				//$mat_pro_err1 = $marker_name.'|';
								//$mat_pro1 = $mat_pro_get1.$mat_pro_err1;
				   				// Session::set('mat_pro1', $mat_pro1);	
							}
				   			

				   		} else {

							$find_in_mattress_pro = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM mattress_pros WHERE mattress = '".$mattress."' "));
							if (isset($find_in_mattress_pro[0])) {				   			
								
								$err = Session::get('err');
								// dd($msg);
				   				$err0 = "Mattress '".$mattress."' already exist in mattress_pros!";
								$err = $err.'#'.$err0;
								Session::set('err', $err);

							} else {

					   			$mattress_pro_array[] = '';
						   		foreach ($find_in_marker_lines as $line) {
						   			$style_size = $line->style_size;
						   			$pro_pcs_layer = $line->pcs_on_layer;
						   			// dd($style_size);
						   			// print_r($style_size."<br>");
						   			// dd($skeda);

						   			// $skeda;
									// find by $style_size;
									// $pro_id;
						   			if (isset($skeda)) {
							   				
							   			$find_in_pro_skedas = DB::connection('sqlsrv')->select(DB::raw("SELECT pro_id FROM pro_skedas WHERE skeda = '".$skeda."' AND style_size = '".$style_size."' "));
							   			if (!isset($find_in_pro_skedas[0])) {
							   				// dd("Pro id not found in pro skeda");
							   				// print_r("Pro id not found in pro skeda table, skeda: ".$skeda." and style_size: ".$style_size."<br>");
							   				// dd("Skeda ".$skeda." not exist in pro_skedas table");

							   				$err = Session::get('err');
											// dd($msg);
							   				$err0 = "Skeda '".$skeda."' not exist in pro_skedas table";
											$err = $err.'#'.$err0;
											Session::set('err', $err);

							   				//$mat_pro_get2 = Session::get('mat_pro2');
											//dd($msg);
							   				//$mat_pro_err2 = $skeda."#".$style_size."|";
											//$mat_pro2 = $mat_pro_get2.$mat_pro_err2;
											// Session::set('mat_pro2', $mat_pro2);

							   			} else {
							   				// dd($find_in_pro_skedas);
								   			$pro_id = $find_in_pro_skedas[0]->pro_id;
								   			// print_r('insert:'.$pro_id.'#'.$style_size.'#'.$pro_pcs_layer);
								   			// print_r('<br>');
								   			array_push($mattress_pro_array, $pro_id.'#'.$style_size.'#'.$pro_pcs_layer);
							   			}
							   		} else {
							   			//$err = Session::get('err');
										// // dd($msg);
						   				//$err0 = "Skeda not exist";
										// $err = $err.'#'.$err0;
										// Session::set('err', $err);
						   			}
						   		}
					   		}
				   		}


				   		// check errors
					    $err = Session::get('err');
					    // dd($err);
					    
					    if (isset($err) OR !is_null($err)) {
					    	Session::set('err', null);

						    $err = explode("#", $err);

						    print_r('Mattress:');
						    foreach ($err as $line) {
						    	print_r($line."<br>");
						    }	
						    print_r('<br>');
					    } else {
					    //save to database

					    	if (($skeda_item_type == 'MW') OR ($skeda_item_type == 'MB')) {
								// Ploce								
					    		try {
									$table0 = new mattress;

									$table0->mattress = $mattress;
									// $table->g_bin;
									$table0->material = $material;
									$table0->dye_lot = $dye_lot;
									$table0->color_desc = $color_desc;
									
									$table0->skeda = $skeda;
									$table0->skeda_item_type = $skeda_item_type;
									$table0->skeda_status = $skeda_status;
									$table0->width_theor_usable = $width_theor_usable;
									$table0->spreading_method = $spreading_method;

									$table0->save();
								}
								catch (\Illuminate\Database\QueryException $e) {
									dd("Problem to save in mattresses");
								}

								try {
									$table1 = new mattress_details;

									$table1->mattress_id = $table0->id;
									$table1->mattress = $table0->mattress;

									$table1->layers = $layers;
									$table1->layers_a = $layers_a;
									$table1->length_usable = $length_usable;
									$table1->cons_planned = $cons_planned;
									$table1->cons_actual = $cons_actual;
									$table1->extra = $extra;
									$table1->pcs_bundle;
									$table1->layers_partial = $layers_partial;
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
									$table1->minimattress_code;
									$table1->overlapping = $overlapping;
									$table1->tpa_number = $tpa_number;
									
									$table1->save();
								}
								catch (\Illuminate\Database\QueryException $e) {
									dd("Problem to save in mattress_details");
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
									dd("Problem to save in mattress_markers");
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

									$table3->save();
								}
								catch (\Illuminate\Database\QueryException $e) {
									dd("Problem to save in mattress_phases");
								}

								// print_r(array_filter($mattress_pro_array));
								// print_r('<br>');
								// print_r('<br>');
								/*
									// Not needed for MW and MB
									$mattress_pro_array = array_filter($mattress_pro_array);
									for ($i=1; $i <= count($mattress_pro_array) ; $i++) {
																			
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
											$table4->layers = $table4->pro_pcs_layer * (float)$layers_a;
											$table4->layers_cut = $table4->pro_pcs_layer * (float)$layers_a;

											$table4->save();
										}
										catch (\Illuminate\Database\QueryException $e) {
											dd("Problem to save in mattress_pros");
										}
									}
									$mattress_pro_array = '';
								*/
							
							} else {
							// Standard Marker

								try {
									$table0 = new mattress;

									$table0->mattress = $mattress;
									// $table->g_bin;
									$table0->material = $material;
									$table0->dye_lot = $dye_lot;
									$table0->color_desc = $color_desc;
									
									$table0->skeda = $skeda;
									$table0->skeda_item_type = $skeda_item_type;
									$table0->skeda_status = $skeda_status;
									$table0->width_theor_usable = $width_theor_usable;
									$table0->spreading_method = $spreading_method;

									$table0->save();
								}
								catch (\Illuminate\Database\QueryException $e) {
									dd("Problem to save in mattresses");
								}

								try {
									$table1 = new mattress_details;

									$table1->mattress_id = $table0->id;
									$table1->mattress = $table0->mattress;

									$table1->layers = $layers;
									$table1->layers_a = $layers_a;
									$table1->length_usable = $length_usable;
									$table1->cons_planned = $cons_planned;
									$table1->cons_actual = $cons_actual;
									$table1->extra = $extra;
									$table1->pcs_bundle;
									$table1->layers_partial = $layers_partial;
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
									$table1->minimattress_code;
									$table1->overlapping = $overlapping;
									$table1->tpa_number = $tpa_number;
									
									$table1->save();
								}
								catch (\Illuminate\Database\QueryException $e) {
									dd("Problem to save in mattress_details");
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
									dd("Problem to save in mattress_markers");
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

									$table3->save();
								}
								catch (\Illuminate\Database\QueryException $e) {
									dd("Problem to save in mattress_phases");
								}

								// print_r('mattress: '.$mattress);
								// print_r('<br>');
								// print_r('<br>');
								// print_r(array_filter($mattress_pro_array));
								// print_r('<br>');
								// print_r('<br>');

								$mattress_pro_array = array_filter($mattress_pro_array);
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

										$table4->save();
									}
									catch (\Illuminate\Database\QueryException $e) {
										dd("Problem to save in mattress_pros");
									}
								}
								$mattress_pro_array = '';
							}
					    }
	                }
	            });
				
	        }
	    }
	    
	    $pro = Session::get('pro');
	    
	    if (isset($pro)) {
	    	Session::set('pro', null);

		    $pro_err = explode("|", $pro);

		    print_r('<br>');
		    print_r('Pro Skeda: pro_id already exist in pro_skedas table');
		    print_r('<br>');
		    foreach ($pro_err as $line) {
		    	print_r($line."<br>");
		    }	
	    }

	    $pas = Session::get('pas');
	    
	    if (isset($pas)) {
	    	Session::set('pas', null);

		    $pas_err = explode("|", $pas);

		    print_r('<br>');
		    print_r('Paspul: paspul already exist in paspuls table');
		    print_r('<br>');
		    foreach ($pas_err as $line) {
		    	print_r($line."<br>");
		    }	
	    }

	   	print_r("File imported succesfuly");
	    
	    // print_r(array_filter($mattress_pro_array));
		// return redirect('import');

	}
}