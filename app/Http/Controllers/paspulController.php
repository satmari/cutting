<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\paspuls;
use App\paspul_lines;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class paspulController extends Controller {

	public function index()
	{
		//
		// dd("test");
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT  p.[id]
		      ,p.[paspul_roll]
		      ,p.[sap_su]
		      ,p.[material]
		      ,p.[color_desc]
		      ,p.[dye_lot]
		      ,p.[paspul_type]
		      ,p.[width]
		      ,p.[kotur_width]
		      ,p.[kotur_width_without_tension]
		      ,p.[kotur_planned]
		      ,p.[kotur_actual]
		      ,p.[rewound_length]
		      ,p.[rewound_length_a]
		      ,p.[pasbin]
		      ,p.[skeda_item_type]
		      ,p.[skeda]
		      ,p.[skeda_status]
		      ,p.[rewound_roll_unit_of_measure]
		      ,p.[position]
		      ,p.[priority]
		      ,p.[comment_office]
		      ,p.[comment_operator]
		      ,p.[call_shift_manager]
		      ,p.[rewinding_method]
		      ,p.[created_at]
		      ,p.[updated_at]
		      
		      ,pl.[status]
		      ,pl.[location]
		      ,pl.[device]
		      ,pl.[active]
		      ,pl.[operator1]
		      ,pl.[operator2]
		      
		  FROM [paspuls] as p
		  LEFT JOIN [paspul_lines] as pl ON pl.[paspul_roll_id]= p.[id]"));
		// dd($data);

		return view('paspul.table',compact('data'));
	}



}
