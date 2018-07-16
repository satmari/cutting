<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\temp_table_hu;
use App\Reservation;
use App\po;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class poController extends Controller {

	public function index()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM pos ORDER BY po asc"));
		return view('po.index', compact('data'));
	}

	public function new_po()
	{
		//
		return view('po.new_po');
	}

	public function post_new_po(Request $request)
	{
		//
		$this->validate($request, ['po'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$po = $input['po'];
				
		try {
			$table = new po;

			$table->po = $po;
			$table->status = "OPEN";
			
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('po.error');
		}
		
		//return view('defectlevel.index');
		return Redirect::to('/po');

	}

	public function edit_status($id) {

		// dd($id);
		$po = po::findOrFail($id);		
		return view('po.edit_status', compact('po'));
	}

	public function update_status($id, Request $request) {
		//
		$this->validate($request, ['status'=>'required']);

		$input = $request->all(); 
		//dd($input);

		$table = po::findOrFail($id);

		try {
			
			$table->status = $input['status'];
			
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('po.error');			
		}
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE status = 'Open' and res_status = 'YES' and res_po = '".$input['po']."' "));
		// dd(count($data));

		for ($i=0; $i < count($data); $i++) { 

			// dd($data[$i]->id);
			$table = Reservation::findOrFail($data[$i]->id);
		
			try {
				
				$table->res_po = NULL;
				$table->res_qty = NULL;
				$table->res_date = NULL;
				$table->res_status = 'NO';

				$table->save();

			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');
			}		
		}

		return Redirect::to('/po');
	}

	public function edit_po($id) {

		// dd($id);
		$po = po::findOrFail($id);
		return view('po.edit_po', compact('po'));
	}

	public function update_po($id, Request $request) {
		//
		$this->validate($request, ['new_name'=>'required']);

		$input = $request->all(); 
		//dd($input);

		$table = po::findOrFail($id);
		
		try {
			
			$table->po = $input['new_name'];
			
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('reservations.error');
		}

		$po = $input['po'];
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE res_po =  '".$po."' "));

		// dd(count($data));

		for ($i=0; $i < count($data); $i++) { 

			// dd($data[$i]->id);
			$table = Reservation::findOrFail($data[$i]->id);
		
			try {
				
				$table->res_po = $input['new_name'];
				$table->save();

			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');
			}		
		}
		
		return Redirect::to('/po');
	}

}
