<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\marker_header;
use App\marker_line;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class markerController extends Controller {


	public function index()
	{
		//

		// if ((date('H') >= 0) AND (date('H') < 6)) {
		//    	$date_before = date('Y-m-d H:i:s', strtotime(' -1 day'));
		// } else {
		// 	$date_before = date('Y-m-d H:i:s');
		// }
		// // $date_before = date('Y-m-d H:i:s', strtotime(' -1 day'));
		// dd($date_before);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id,marker_name,marker_width,marker_length,cutting_perimeter,perimeter,average_consumption,efficiency,status  FROM marker_headers where [status] = 'ACTIVE'"));
		return view('marker.table',compact('data'));
	}

	public function index_line($id)
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM marker_lines WHERE marker_header_id = '".$id."' "));
		return view('marker.table_line',compact('data'));
	}

	public function marker_line_confirm(Request $request)
	{
		//
		$this->validate($request, ['marker_name'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$marker_name = strtoupper($input['marker_name']);
		$marker_header_id = $input['marker_header_id'];

		$marker_width = round((float)str_replace(',', '.',$input['marker_width']),3);
		$marker_length = round((float)str_replace(',', '.',$input['marker_length']),3);

		$marker_type = strtoupper($input['marker_type']);
		$marker_code = strtoupper($input['marker_code']);
		$fabric_type = strtoupper($input['fabric_type']);
		$constraint = strtoupper($input['constraint']);

		$spacing_around_pieces = round((float)str_replace(',', '.',$input['spacing_around_pieces']),3);
		$spacing_around_pieces_top = round((float)str_replace(',', '.',$input['spacing_around_pieces_top']),3);
		$spacing_around_pieces_bottom = round((float)str_replace(',', '.',$input['spacing_around_pieces_bottom']),3);
		$spacing_around_pieces_right = round((float)str_replace(',', '.',$input['spacing_around_pieces_right']),3);
		$spacing_around_pieces_left = round((float)str_replace(',', '.',$input['spacing_around_pieces_left']),3);

		$processing_date = date( "Y-m-d", strtotime($input['processing_date']));

		$efficiency = round((float)str_replace(',', '.',$input['efficiency']),3);
		$cutting_perimeter = round((float)str_replace(',', '.',$input['cutting_perimeter']),3);
		$perimeter = round((float)str_replace(',', '.',$input['perimeter']),3);
		$average_consumption = round((float)str_replace(',', '.',$input['average_consumption']),3);
		$lines = round((float)str_replace(',', '.',$input['lines']),3);
		$curves = round((float)str_replace(',', '.',$input['curves']),3);
		$areas = round((float)str_replace(',', '.',$input['areas']),3);
		$angles = round((float)str_replace(',', '.',$input['angles']),3);
		$notches = round((float)str_replace(',', '.',$input['notches']),3);
		$total_pcs = round((float)str_replace(',', '.',$input['total_pcs']),3);

		$variant_model = strtoupper($input['variant_model']);
		$key = strtoupper($input['key']);

		$min_length = round((float)$input['min_length'],3);
		$status = $input['status'];

		// $style_size =  $input['style_size'];
		$style =  $input['style'];
		$size =  $input['size'];
		$qty =  $input['qty'];

		// print_r($style_size);

		// save to header
		$table = new marker_header;

		$table->marker_name = $marker_name;

		$table->marker_width = $marker_width;
		$table->marker_length = $marker_length;

		$table->marker_type = $marker_type;
		$table->marker_code = $marker_code;
		$table->fabric_type = $fabric_type;
		$table->constraint = $constraint;

		$table->spacing_around_pieces = $spacing_around_pieces;
		$table->spacing_around_pieces_top = $spacing_around_pieces_top;
		$table->spacing_around_pieces_bottom = $spacing_around_pieces_bottom;
		$table->spacing_around_pieces_right = $spacing_around_pieces_right;
		$table->spacing_around_pieces_left = $spacing_around_pieces_left;

		$table->processing_date = $processing_date;

		$table->efficiency = $efficiency;
		$table->cutting_perimeter = $cutting_perimeter;
		$table->perimeter = $perimeter;
		$table->average_consumption = $average_consumption;
		$table->lines = $lines;
		$table->curves = $curves;
		$table->areas = $areas;
		$table->angles = $angles;
		$table->notches = $notches;
		$table->total_pcs =  $total_pcs;

		$table->variant_model = $variant_model;
		$table->key = $key;

		$table->min_length = $min_length;
		$table->status = "ACTIVE";

		$table->save();


		for ($i=0; $i < count($style); $i++) { 

			// save to line
			if ((int)$qty[$i] == 0) {
				continue;
			}

			// dd($style_size[$i]);
			$table_l = new marker_line;
			$table_l->marker_header_id = $table->id;
			$table_l->marker_name = $table->marker_name;

			$style_size = $style[$i].' '.$size[$i];

			$table_l->style_size = strtoupper($style_size);
			$table_l->style = strtoupper($style[$i]);
			$table_l->size = strtoupper($size[$i]);

			$table_l->pcs_on_layer = $qty[$i];
			$table_l->comment = '';

			$table_l->save();

		}
		return Redirect::to('marker');
		// dd("stop");
		
	}

	public function marker_delete($id) {

		// dd($id);
		return view('marker.marker_delete',compact('id'));

	}

	public function marker_delete_confirm(Request $request)
	{	
		$this->validate($request, ['id'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$id =  $input['id'];
		// dd($id);

		$marker_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM marker_headers WHERE id = '".$id."' "));
		// dd($data);
		if(isset($marker_data[0]->marker_name)) {
			$marker_name = $marker_data[0]->marker_name;
		} else {
			dd('No marker with that id');
		}

		$mattress_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM mattress_markers WHERE marker_name= '".$marker_name."' "));
		// dd($mattress_data);

		if (isset($mattress_data[0]->id)) {
			
			// dd('This marker is already used! You can not delete it!');	
			$msg ='This marker is already used! You can not delete it!';
			return view('marker.error',compact('msg'));
		}

		$marker_line = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM marker_lines WHERE marker_header_id = '".$marker_data[0]->id."' "));
		// dd($marker_line);

		if (isset($marker_line[0]->id)) {
			
			for ($i=0; $i < count($marker_line) ; $i++) { 
			
				$table_line = marker_line::findOrFail($marker_line[$i]->id);
				$table_line->delete();
			}	
		}
		
		$table_header = marker_header::findOrFail($marker_data[0]->id);
		$table_header->delete();

		return Redirect::to('marker');

	}

	public function marker_edit ($id) {
		
		$data = marker_header::findOrFail($id);
		// dd($data);

		return view('marker.marker_edit', compact('data'));
	}

	public function marker_edit_confirm (Request $request) {	
		$this->validate($request, ['id'=>'required', 'status' => 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$id =  $input['id'];
		$status =  $input['status'];
		$marker_length =  round($input['marker_length'],3);
		$efficiency =  round($input['efficiency'],2);
		$cutting_perimeter =  round($input['cutting_perimeter'],2);
		$perimeter =  round($input['perimeter'],2);
		$average_consumption =  round($input['average_consumption'],3);

		// dd($status);
		$marker='';
		if ($status == 'NOT ACTIVE') {
				
			$check_if_is_in_use = DB::connection('sqlsrv')->select(DB::raw("SELECT	mp.mattress_id
				,mp.mattress
				,mp.status
				,mm.marker_name
			FROM [cutting].[dbo].[mattress_phases] as mp
			JOIN [cutting].[dbo].[mattress_markers] as mm ON mp.mattress_id = mm.mattress_id
			WHERE mp.active = 'True' AND 
			(mp.status = 'NOT_SET' OR mp.status = 'TO_LOAD' OR mp.status = 'TO_SEPREAD' OR mp.status = 'TO_CUT' OR mp.status = 'ON_CUT' OR mp.status = 'ON_HOLD') AND 
			marker_id = '".$id."' "));
			// dd($check_if_is_in_use);

			if (!empty($check_if_is_in_use)) {
				for ($i=0; $i < count($check_if_is_in_use) ; $i++) { 

					// dd($check_if_is_in_use[$i]->marker_name);
					$marker = $marker.$check_if_is_in_use[$i]->mattress.' , ';
				}
				// dd('Marker/s have mattresses that are in use: '.$marker);

			} else {
				$data = marker_header::findOrFail($id);
				$data->status = strtoupper($status);
				$data->marker_length = $marker_length;
				$data->efficiency = $efficiency;
				$data->cutting_perimeter = $cutting_perimeter;
				$data->perimeter = $perimeter;
				$data->average_consumption = $average_consumption;
				$data->save();
			}

		} else {
			$data = marker_header::findOrFail($id);
			$data->status = strtoupper($status);
			$data->marker_length = $marker_length;
			$data->efficiency = $efficiency;
			$data->cutting_perimeter = $cutting_perimeter;
			$data->perimeter = $perimeter;
			$data->average_consumption = $average_consumption;
			$data->save();
		}

		if (!$marker == '') {
			dd('Marker have mattress that is in use: '.$marker);
		}
		
		return Redirect::to('marker');
	}
	

}
