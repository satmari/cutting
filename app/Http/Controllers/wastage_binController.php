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
use App\wastage_bin;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class wastage_binController extends Controller {

	public function index()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM wastage_bins"));
		return view('Wastage_bin.index',compact('data'));
	}

	public function add()
	{
		//
		return view('Wastage_bin.add');
	}

	public function add_post(Request $request)
	{
		//
		$this->validate($request, ['container' => 'required']);
		$input = $request->all(); 

		$container = $input['container'];
		// dd($container);
		try {
			$table = new Wastage_bin;
			$table->container = strtoupper($container);
			$table->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			return Redirect::to('/wastage_bin');
		}		

		return Redirect::to('/wastage_bin');
	}

	public function edit($id)
	{
		//
		$data = Wastage_bin::findOrFail($id);
		return view('Wastage_bin.edit', compact('data'));
	}

	public function edit_post($id, Request $request)
	{
		$this->validate($request, ['container' => 'required']);
		$input = $request->all(); 

		$container = $input['container'];
		
		try {
			$box = Wastage_bin::findOrFail($id);
			$box->container = strtoupper($container);
			
			$box->save();
			
		}
		catch (\Illuminate\Database\QueryException $e) {
			return Redirect::to('/wastage_bin');
		}

		return Redirect::to('/wastage_bin');
	}

	public function remove($id)
	{
		
		try {
			$table = Wastage_bin::findOrFail($id);
		
			$table->delete();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to delete in sfusiStock table";
			return view('wastage_bin.error',compact('msg'));		
		}
		
		return Redirect::to('/wastage_bin');
	}

}
