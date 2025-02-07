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

class paspul_binController extends Controller {

	public function index()
	{
		//
		// dd("test");
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[skeda]
		      ,[pas_bin]
		      ,[adez_bin]
		      ,[created_at]
		      ,[updated_at]
			  FROM [paspul_bins]"));
		// dd($data);

		return view('paspul_bin.table',compact('data'));
	}

}
