<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\consumption_sap;

// use DB;
use Illuminate\Support\Facades\DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class consumption_sapController extends Controller {


	public function index()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM consumption_saps"));
		// dd($data);
		return view('consumption_sap.table', compact('data'));
	}

	
}
