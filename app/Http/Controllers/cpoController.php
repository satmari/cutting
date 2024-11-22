<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

// use App\mattress_details;
use App\parts;
use App\part_line;
use App\part_g_bin_statuses;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;


class cpoController extends Controller {

	public function index() {
		// dd('cao cpo');

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			dd('User is not autenticated');
			// $msg ='User is not autenticated';
			// return view('cpo.error',compact('msg','operators','operator'));
		}

		// $location = substr($device, 0,3);
		$location = "CPO";
		// dd($location);

		// $work_place = substr($device, 0,2);
		$work_place = "CPO";
		// Session::set('work_place',$work_place);
	
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		
		// $data = DB::connection('sqlsrv')->select(DB::raw("SELECT
		// 		g_bin
		// 		,part 
		// 		,style
		// 		,size
		// 		,bundle
		// 		,(SELECT TOP 1 operator FROM [part_lines] WHERE part_id = id ORDER BY created_at desc) as last_operator
		// FROM [parts]
		// GROUP BY 
		// 	g_bin,
		// 	part,
		// 	style,
		// 	size,
		// 	bundle
		// "));

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			      p.[g_bin],
			      p.[style],
			      s.[status],
			      s.[comment],
			      (SELECT [mandatory_to_ins] FROM [cutting].[dbo].[mattress_details] as d
				  JOIN [cutting].[dbo].[mattresses] as m ON m.id = d.mattress_id
				  WHERE m.g_bin = p.g_bin) as [mandatory_to_ins]
			FROM [cutting].[dbo].[parts] as p
			LEFT JOIN [cutting].[dbo].[part_g_bin_statuses] as s ON s.[g_bin] = p.[g_bin]
			
			WHERE (s.[status] != 'Ready for production' AND s.[status] != 'Not checked') OR s.[status] is NULL
			GROUP BY	p.[g_bin],
						p.[style],
						s.[status],
						s.[comment]
			ORDER BY [mandatory_to_ins] desc
		"));

		// dd($data);


		return view('cpo.index', compact('data','location','operators','operator'));
		// return view('cpo.index', compact('location'));
	}

	public function index_all() {
		// dd('cao cpo');

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			dd('User is not autenticated');
			// $msg ='User is not autenticated';
			// return view('cpo.error',compact('msg','operators','operator'));
		}

		// $location = substr($device, 0,3);
		$location = "CPO";
		// dd($location);

		// $work_place = substr($device, 0,2);
		$work_place = "CPO";
		// Session::set('work_place',$work_place);
	
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT
				g_bin
				,part 
				,style
				,size
				,bundle
				,(SELECT TOP 1 operator FROM [part_lines] WHERE part_id = id ORDER BY created_at desc) as last_operator
		FROM [parts]
		GROUP BY 
			g_bin,
			part,
			style,
			size,
			bundle
		"));

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				      p.[g_bin],
				      p.[style],
				      s.[status],
				      s.[comment],
				      (SELECT [mandatory_to_ins] FROM [cutting].[dbo].[mattress_details] as d
				  		JOIN [cutting].[dbo].[mattresses] as m ON m.id = d.mattress_id
				  		WHERE m.g_bin = p.g_bin) as [mandatory_to_ins]
				FROM [cutting].[dbo].[parts] as p
				LEFT JOIN [cutting].[dbo].[part_g_bin_statuses] as s ON s.[g_bin] = p.[g_bin]
				WHERE s.[status] = 'Ready for production' OR s.[status] = 'Not checked'
				GROUP BY	p.[g_bin],
							p.[style],
							s.[status],
							s.[comment]
		"));

		// dd($data);

		$full_table = TRUE;
		return view('cpo.index', compact('data','location','operators','operator','full_table'));
		// return view('cpo.index', compact('location'));
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
				return redirect('/cpo');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/cpo');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/cpo');
		}
	}

	public function operator_logout () {
		// dd('out');
		$operator = Session::set('operator', NULL);
		return redirect('/cpo');
	}

	public function cpo_scan() {
		// dd('cao cpo');

		// $work_place = substr($device, 0,2);
		$location = "CPO";
		$work_place = "CPO";
		// Session::set('work_place',$work_place);
	
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msge ='Operator must be logged!';
			return view('cpo.error',compact('msge','operators','operator'));
		}

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msge ='User is not autenticated';
			return view('cpo.error',compact('msge','operators','operator'));
		}
		
		return view('cpo.scan', compact('location','operators','operator'));
	}


//  scan g_bin
	public function cpo_header_table (Request $request) {
		// dd('stop');
		// $this->validate($request, ['location' => 'required']);
		$input = $request->all(); 
		// dd($input);

		// $location = $input['location'];

		$location = "CPO";
		$work_place = "CPO";
		// Session::set('work_place',$work_place);
	
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			$msgs ='Operator must be logged!';
			// return view('cpo.error',compact('msg','operators','operator'));
			return view('cpo.scan', compact('location','operators','operator','msge'));
		}
		
		if ($input['g_bin'] != '') {

			if (substr($input['g_bin'], 0,1) != 'G') {
				$msge = 'G bin must be inserted';
				// return view('cpo.error', compact('location', 'msg', 'operators','operator'));
				return view('cpo.scan', compact('location','operators','operator','msge'));

			} else {

				if (strlen($input['g_bin']) != 10 ) {
					$msge = 'G bin must have 10 chars';
					// return view('cpo.error', compact('location', 'msg', 'operators','operator'));
					return view('cpo.scan', compact('location','operators','operator','msge'));
				} else {
					
					$data = DB::connection('sqlsrv')->select(DB::raw("SELECT m.[id]
						FROM [mattresses] as m 
						LEFT JOIN [mattress_phases] as mp ON mp.[mattress_id] = m.[id]
						WHERE mp.[active] = '1' AND (mp.[status] = 'COMPLETED' OR mp.[status] = 'TO_PACK') 
							AND m.[g_bin] = '".$input['g_bin']."'
						 "));
					// dd($data);

					if (!isset($data[0]->id)) {
						
						$msge = 'G bin is not COMPLETED or TO_PACK';
						// return view('cpo.error', compact('location', 'msg', 'operators','operator'));
						return view('cpo.scan', compact('location','operators','operator','msge'));

					} else {
						$g_bin = trim($input['g_bin']);	
					}
				}
			}
			// continue

			// dd($g_bin);
		} else {
			$msge = 'G bin must be inserted';
			// return view('cpo.error', compact('location', 'msg' , 'operators','operator'));
			return view('cpo.scan', compact('location','operators','operator','msge'));

		}
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM [parts] as p
			
			--LEFT JOIN [part_lines] as pl ON pl.[part_id] = p.[id]
			WHERE p.[g_bin] = '".$g_bin."' 
		"));

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT x.part_id,
				x.part,
				x.g_bin,
				x.style,
				x.size,
				x.bundle
			,(SELECT 
			         TOP 1 [length]
			     FROM 
			         [cutting].[dbo].[part_lines]
			     WHERE 
			         [length] IS NOT NULL AND (
					 [part] = x.[part] AND
					 [g_bin] = x.[g_bin] AND
					 [style] = x.[style] AND
					 [size] = x.[size] AND
					 [bundle] = x.[bundle] 
					 )
			     GROUP BY 
			         [part], [g_bin], [style], [size], [bundle], [length]
				 ORDER BY COUNT([length]) desc) as length_mode

			,(SELECT 
			         TOP 1 [width]
			     FROM 
			         [cutting].[dbo].[part_lines]
			     WHERE 
			         [width] IS NOT NULL AND (
					 [part] = x.[part] AND
					 [g_bin] = x.[g_bin] AND
					 [style] = x.[style] AND
					 [size] = x.[size] AND
					 [bundle] = x.[bundle] 
					 )
			     GROUP BY 
			         [part], [g_bin], [style], [size], [bundle], [width]
				 ORDER BY COUNT([width]) desc) as width_mode

			,(SELECT comment FROM parts WHERE
				 	[part] = x.[part] AND
					[g_bin] =  x.g_bin AND
					[style] = x.[style] AND
					[size] = x.[size] AND
					[bundle] = x.[bundle] ) as comment
			
			,(SELECT id FROM parts WHERE
				 	[part] = x.[part] AND
					[g_bin] = x.[g_bin] AND
					[style] = x.[style] AND
					[size] = x.[size] AND
					[bundle] = x.[bundle] ) as id

			  FROM [cutting].[dbo].[part_lines] as x
			  WHERE x.g_bin = '".$g_bin."'
			  GROUP BY 
			  		x.part_id,
					x.part,
					x.g_bin,
					x.style,
					x.size,
					x.bundle
		"));
		// dd($data);

		return view('cpo.cpo_header_table',compact('location', 'g_bin', 'data'));
	}

//  new cehck
	public function cpo_new_check($g_bin) {
		// dd($g_bin);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			$work_place = "CPO";
			// Session::set('work_place',$work_place);

			$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
			      ,[operator]
			      ,[device]
			      ,[device_array]
			  FROM [operators]
			  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
			// dd($operators);

			$operator = Session::get('operator');

			$msge ='Operator must be logged!';
			return view('cpo.error',compact('msge', 'operator', 'operators'));
		}

		$location = "CPO";

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				m.[id] ,m.[g_bin], m.[skeda], m.[mattress], m.[material],
				md.[layers_a],
				ml.[style], ml.[size], ml.[style_size]
			FROM [mattresses] as m
			LEFT JOIN [mattress_details] as md on md.[mattress_id] = m.[id]
			LEFT JOIN [mattress_phases] as mp on mp.[mattress_id] = m.[id]
			LEFT JOIN [mattress_markers] as mm on mm.[mattress_id] = m.[id]
			LEFT JOIN [marker_headers] as mh on mh.[marker_name] = mm.[marker_name]
			LEFT JOIN [marker_lines] as ml on ml.[marker_name] = mh.[marker_name]
			WHERE mp.[active] = 1 and  (mp.[status] = 'COMPLETED' OR mp.[status] = 'TO_PACK') and  m.[g_bin] = '".$g_bin."' 
		"));
		// dd($data);
		
		if (!isset($data[0]->style)) {
			$msge = 'G bin was not found or doesent have status COMPLETED or TO_PACK';
			return view('cpo.index', compact('location', 'msge'));
		}

		return view('cpo.choose_style_size', compact('g_bin','location','data'));
	}

	public function cpo_insert_style_size_bundle(Request $request) {
		//
		// $this->validate($request, ['' => 'required']);
		$input = $request->all(); 
		// dd($input);

		$g_bin = $input['g_bin'];
		$location = $input['location'];
		$select_style_size = $input['select_style_size'];
		$select_bundle = $input['select_bundle'];

		$array = explode(" ", $select_style_size);
		$style = $array[0];
		$size = $array[1];
		// dd($style);

		$data_parts = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			FROM [part_styles]
			WHERE style = '".$style."' 
		"));
		// dd($data_parts);


		return view('cpo.choose_part',compact('location', 'g_bin', 'style', 'size', 'select_bundle','data_parts', ''));
	}

	public function cpo_insert_part(Request $request) {
		//
		// $this->validate($request, ['' => 'required']);
		$input = $request->all();
		// dd($input);

		$g_bin = $input['g_bin'];
		$location = $input['location'];
		$style = $input['style'];
		$size = $input['size'];
		$bundle = $input['select_bundle'];
		$part = $input['select_part'];
		

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				pl.*
  			FROM [cutting].[dbo].[parts] as p
			JOIN [cutting].[dbo].[part_lines] as pl ON pl.[part_id] = p.[id] 
			WHERE pl.[g_bin] = '".$g_bin."'   and
					pl.[style] = '".$style."'   and
					pl.[size] = '".$size."'  and
					pl.[bundle] = '".$bundle."'  and
					pl.[part] = '".$part."'  
		"));
		// dd($data);
		if (isset($data[0]->part_id)) {
			$id = $data[0]->part_id;
			// $comment = $data[0]->comment;

		} else {
			$id = '';
			// $comment = '';
		}
		
		return view('cpo.cpo_line_table', compact('id','g_bin','style','size','bundle','part','data'));

	}

//  edit check

	public function cpo_check_edit($id) {
		// dd($id);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				pl.*,
				p.[comment]
  			FROM [cutting].[dbo].[parts] as p
			JOIN [cutting].[dbo].[part_lines] as pl ON pl.[part_id] = p.[id] 
			WHERE p.[id] = '".$id."' 
		"));
		// dd($data);
		$g_bin = $data[0]->g_bin;
		$style = $data[0]->style;
		$size = $data[0]->size;
		$bundle = $data[0]->bundle;
		$part = $data[0]->part;
		// $comment = $data[0]->comment;


		return view('cpo.cpo_line_table', compact('id','g_bin','style','size','bundle','part','data'));

	}

// add layer line

	public function cpo_new_check_layers(Request $request) {
		//
		// $this->validate($request, ['' => 'required']);
		$input = $request->all();
		// dd($input);

		$g_bin = $input['g_bin'];
		$style = $input['style'];
		$size = $input['size'];
		$bundle = $input['bundle'];
		$part = $input['part'];
		// $comment = $input['comment'];

		return view('cpo.cpo_new_check_layers', compact('g_bin','style','size','bundle','part'));

	}

	public function cpo_new_check_layers_post(Request $request) {
		//
		// $this->validate($request, ['' => 'required']);
		$input = $request->all();
		// dd($input);

		$g_bin = $input['g_bin'];
		$style = $input['style'];
		$size = $input['size'];
		$bundle = $input['bundle'];
		$part = $input['part'];
		// $comment = $input['comment'];

		$layer = (int)$input['layer'];
		$length = (float)$input['length'];
		$width = (float)$input['width'];
		

		if (($layer > 500) OR ($layer < 0)) {
			dd('Error, layer input');
		}
		if (($length > 500) OR ($length < -500)) {
			dd('Error, length input');
		}
		if (($width > 500) OR ($width < -500)) {
			dd('Error, width input');
		}

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			dd('User is not autenticated');
			// $msg ='User is not autenticated';
			// return view('cpo.error',compact('msg','operators','operator'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				pl.*
  			FROM [cutting].[dbo].[parts] as p
			JOIN [cutting].[dbo].[part_lines] as pl ON pl.[part_id] = p.[id] 
			WHERE pl.[g_bin] = '".$g_bin."'   and
					pl.[style] = '".$style."'   and
					pl.[size] = '".$size."'  and
					pl.[bundle] = '".$bundle."'  and
					pl.[part] = '".$part."'  
		"));
		// dd($data);
		// dd('Update app u toku, pokusajte kasnije');


		if (isset($data[0]->part_id)) {
			//exist part 

			try {
				// $table = parts::findOrFail($data[0]->part_id);
				// $table->comment = $comment;
				// $table->save();


				//add lines
				$table_lines = new part_line;

				$table_lines->part_id = $data[0]->part_id;
				$table_lines->part = $data[0]->part;
				$table_lines->g_bin = $g_bin;
				$table_lines->style = $style;
				$table_lines->size = $size;
				$table_lines->bundle = $bundle;

				$table_lines->layer = $layer;
				$table_lines->length = $length;
				$table_lines->width = $width;
				$table_lines->operator = Session::get('operator');
				$table_lines->device = $device;

				$table_lines->key_part_line =  strval($g_bin).'-'.strval($style).'-'.strval($size).'-'.strval($bundle).'-'.strval($part).'-'.strval($layer);
				// dd($table_lines->key_part_line);
				// dd('stop');

				$table_lines->save();

			} catch (\Illuminate\Database\QueryException $e) {
				dd('Problem to save, probably is diplicated. Ne moze da se sacuva, verovatno je duplirana kombinacija (g_bin,bundle,part,layer)');
			}

		} else {
			// does not exist

			// $part = $g_bin."-".$style."-".$size."-".$bundle."-".$part;
			// add header

			try {
				$table = new parts;

				$table->part = $part;
				$table->g_bin = $g_bin;
				$table->style = $style;
				$table->size = $size;
				$table->bundle = $bundle;
				$table->length_mode;
				$table->width_mode; 
				// $table->comment = $comment;
				$table->key_part =  strval($g_bin).'-'.strval($style).'-'.strval($size).'-'.strval($bundle).'-'.strval($part);

				$table->save();

				//add lines
				$table_lines = new part_line;

				$table_lines->part_id = $table->id;
				$table_lines->part = $table->part;
				$table_lines->g_bin = $g_bin;
				$table_lines->style = $style;
				$table_lines->size = $size;
				$table_lines->bundle = $bundle;

				$table_lines->layer = $layer;
				$table_lines->length = $length;
				$table_lines->width = $width;
				$table_lines->operator = Session::get('operator');
				$table_lines->device = $device;

				$table_lines->key_part_line =  strval($g_bin).'-'.strval($style).'-'.strval($size).'-'.strval($bundle).'-'.strval($part).'-'.strval($layer);

				
				$table_lines->save();


				// $table->length_mode;
				// $table->width_mode;
				// $table->save();

			} catch (\Illuminate\Database\QueryException $e) {
				dd('Problem to save, probably is diplicated. Ne moze da se sacuva, verovatno je duplirana kombinacija (g_bin,bundle,part,layer)');
			}

		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					pl.*
	  			FROM [cutting].[dbo].[parts] as p
				JOIN [cutting].[dbo].[part_lines] as pl ON pl.[part_id] = p.[id] 
				WHERE pl.[g_bin] = '".$g_bin."'   and
						pl.[style] = '".$style."'   and
						pl.[size] = '".$size."'  and
						pl.[bundle] = '".$bundle."'  and
						pl.[part] = '".$part."'  
			"));
			// dd($data);
		if (isset($data[0]->part_id)) {
			$id = $data[0]->part_id;
		} else {
			$id = '';
		}
			
		return view('cpo.cpo_line_table', compact('id','g_bin','style','size','bundle','part','data'));

	}


// edit layer line
	
	public function cpo_edit_check_layers($id) {
		// dd($id);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					pl.*,
					p.[comment]
	  			FROM [cutting].[dbo].[parts] as p
				JOIN [cutting].[dbo].[part_lines] as pl ON pl.[part_id] = p.[id] 
	  			WHERE pl.[id] = ".$id."
			"));
		// dd($data);

		$id;
		$layer = $data[0]->layer;
		$length = $data[0]->length;
		$width = $data[0]->width;
		// $comment = $data[0]->comment;

		return view('cpo.cpo_edit_check_layers', compact('id','layer','length','width'));

	}

	public function cpo_edit_check_layers_post(Request $request) {
		//
		// $this->validate($request, ['' => 'required']);
		$input = $request->all();
		// dd($input);

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			dd('User is not autenticated');
			// $msg ='User is not autenticated';
			// return view('cpo.error',compact('msg','operators','operator'));
		}
		
		$id = $input['id'];
		$layer = (int)$input['layer'];
		$length = (float)$input['length'];
		$width = (float)$input['width'];
		// $comment = $input['comment'];


		//add lines
		$table_lines = part_line::findOrFail($id);
		$table_lines->layer = $layer;
		$table_lines->length = $length;
		$table_lines->width = $width;
		$table_lines->operator = Session::get('operator');
		$table_lines->device = $device;
		$table_lines->key_part_line =  strval($table_lines->g_bin).'-'.strval($table_lines->style).'-'.strval($table_lines->size).'-'.strval($table_lines->bundle).'-'.strval($table_lines->part).'-'.strval($layer);
		$table_lines->save();

		$table = parts::findOrFail($table_lines->part_id);
		// $table->comment = $comment;
		// $table->save();


		$g_bin = $table->g_bin;
		$style = $table->style;
		$size = $table->size;
		$bundle = $table->bundle;
		$part = $table->part;

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
					pl.*
	  			FROM [cutting].[dbo].[parts] as p
				JOIN [cutting].[dbo].[part_lines] as pl ON pl.[part_id] = p.[id] 
				WHERE pl.[g_bin] = '".$g_bin."'   and
						pl.[style] = '".$style."'   and
						pl.[size] = '".$size."'  and
						pl.[bundle] = '".$bundle."'  and
						pl.[part] = '".$part."'  
			"));
					
		return view('cpo.cpo_line_table', compact('id','g_bin','style','size','bundle','part','data'));

	}


// GBIN STATUS
	public function set_status_g_bin($g_bin) {
		// dd($g_bin);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id,comment,status
					/*,(SELECT [mandatory_to_ins] FROM [cutting].[dbo].[mattress_details] as d
				  		JOIN [cutting].[dbo].[mattresses] as m ON m.id = d.mattress_id
				  		WHERE m.g_bin = '".$g_bin."') as [mandatory_to_ins]
				  	*/
	  			FROM part_g_bin_statuses
	  			WHERE g_bin = '".$g_bin."'
			"));
		// dd($data);

		if (isset($data[0]->id)) {
			$comment = $data[0]->comment;
			$status = $data[0]->status;
		} else {
			$comment = '';
			$status = '';
		}

		$mandatory_to_ins = DB::connection('sqlsrv')->select(DB::raw("
			SELECT [mandatory_to_ins] FROM [cutting].[dbo].[mattress_details] as d
			JOIN [cutting].[dbo].[mattresses] as m ON m.id = d.mattress_id
			WHERE m.g_bin = '".$g_bin."'
			"));
		
		if (isset($mandatory_to_ins[0]->mandatory_to_ins)) {
			$mandatory_to_ins = $mandatory_to_ins[0]->mandatory_to_ins;
		}
		// dd($mandatory_to_ins);

		// dd($status);
		return view('cpo.set_status_g_bin', compact('g_bin','comment','status','mandatory_to_ins'));
	}

	public function set_status_g_bin_post(Request $request) {
		//
		// $this->validate($request, ['' => 'required']);
		$input = $request->all();
		// dd($input);

		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			dd('User is not autenticated');
			// $msg ='User is not autenticated';
			// return view('cpo.error',compact('msg','operators','operator'));
		}

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			// return redirect('/spreader');
			// $msgs ='Operator must be logged!';
			// return view('cpo.error',compact('msg','operators','operator'));
			// return view('cpo.scan', compact('location','operators','operator','msge'));
			dd('Operator must be logged!');
		}
			
		
		$g_bin = $input['g_bin'];
		$status = $input['status'];
		$comment = $input['comment'];
		// dd($comment);

		$check = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM part_g_bin_statuses 
			WHERE  g_bin = '".$g_bin."'
		"));


		if (isset($check[0]->id)) {
			$data = DB::connection('sqlsrv')->update(DB::raw("UPDATE part_g_bin_statuses
	  			SET status = '".$status."',
	  				comment = '".$comment."',
	  				operator = '".$operator."',
	  				device = '".$device."'

	  				WHERE g_bin = '".$g_bin."';
			"));	

		} else {

			$table = new part_g_bin_statuses;
			$table->g_bin = $g_bin;
			$table->status = $status;
			$table->comment = $comment;
			$table->operator = $operator;
			$table->device = $device;
			$table->save();
		}

		return redirect('/cpo');

	}

}

