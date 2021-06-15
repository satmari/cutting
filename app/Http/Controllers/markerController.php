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
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM marker_headers"));
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
	

}
