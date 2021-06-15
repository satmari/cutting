<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use DB;
// 

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class cutting_xml extends Controller {

	public function cutting_xml()
	{
		//
		$data = DB::connection('sqlsrv6')->select(DB::raw("SELECT * FROM [posummary].[dbo].[cutting_output_undos] "));
		return view('cutting_xml.table', compact('data'));
	}

	public function cutting_xml_all()
	{
		//
		$data = DB::connection('sqlsrv6')->select(DB::raw("SELECT * FROM [posummary].[dbo].[cutting_outputs] ORDER BY date desc"));
		return view('cutting_xml.table', compact('data'));
	}

}
