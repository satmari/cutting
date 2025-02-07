<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\inbound_delivery;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class magacinController extends Controller {

	public function inbound_delivery_index() {

		return view('Magacin.inbound_delivery_index');	
	}

	public function inbound_delivery_table_wh() {
		//
		// dd('test');
		$operator = 'Bojan Kramli';
		Session::set('operator', $operator);
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM inbound_deliveries
			WHERE created_at >= DATEADD(DAY, -60, GETDATE()) "));
		// dd($data);

		return view('Magacin.inbound_delivery_table_wh', compact('data'));

	}

	public function inbound_delivery_import()
	{
		//
		return view('Import.index');
	}

	


}
