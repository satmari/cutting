<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\wastages_print;
use App\wastage;
use App\wastage_location;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class wastage_locationController extends Controller {

	public function index()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM wastage_locations"));
		return view('Wastage_location.index',compact('data'));
	}

	public function add()
	{
		//
		return view('Wastage_location.add');
	}

	public function add_post(Request $request)
	{
		//
		$this->validate($request, ['location' => 'required']);
		$input = $request->all(); 

		$location = $input['location'];
		// dd($location);
		try {
			$table = new Wastage_location;
			$table->location = strtoupper($location);
			$table->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			return Redirect::to('/wastage_location');
		}		

		return Redirect::to('/wastage_location');
	}

	public function edit($id)
	{
		//
		$data = Wastage_location::findOrFail($id);
		return view('Wastage_location.edit', compact('data'));
	}

	public function edit_post($id, Request $request)
	{
		$this->validate($request, ['location' => 'required']);
		$input = $request->all(); 

		$location = $input['location'];
		
		try {
			$box = Wastage_location::findOrFail($id);
			$box->location = strtoupper($location);
			
			$box->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			return Redirect::to('/wastage_location');
		}

		return Redirect::to('/wastage_location');
	}

	public function remove($id)
	{
		
		try {
			$table = Wastage_location::findOrFail($id);
		
			$table->delete();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete in sfusiStock table";
			return view('wastage_location.error',compact('msg'));		
		}
		
		return Redirect::to('/wastage_location');
	}

}
