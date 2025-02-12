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
		//$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM consumption_saps"));
		$data = DB::connection('sqlsrv6')->select(DB::raw("SELECT TOP 10000 g_bin, SUM(qty*-1) as cons_real FROM [posummary].[dbo].[mb51_cons] GROUP BY g_bin  ORDER BY g_bin desc"));
		$data1 = DB::connection('sqlsrv6')->select(DB::raw("SELECT TOP 1 * FROM [posummary].[dbo].[mb51_cons] ORDER BY created_at desc"));
		// dd($data1);
		
		$last_entered_date = $data1[0]->created_at;

		// dd($data);
		return view('consumption_sap.table', compact('data', 'last_entered_date'));
	}

	
}
