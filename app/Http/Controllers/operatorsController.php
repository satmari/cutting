<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
// use Request; // only for import

use App\operators;
use App\User;
use DB;

class operatorsController extends Controller {

	public function index()
	{
		//
		$work_place = "PLANNER";

		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operators order by id desc"));
		return view('Operators.index',compact('data','operator','operators'));

	}

	public function operator_create() {
		//
		$op = [	    'SP' => 'Spreader operator',
					'MS' => 'Manual spreader operator',
					'MM' => 'Mini marker operator',
					'LR' => 'Leftover operator',
					'PSO' => 'Paspul sewing operator',
					'PRW' => 'Paspul rewinding operator',
					'PCO' => 'Paspul cutting operator',
					'PACK' => 'Packing operator',
					'PLOT' => 'Ploter operator',
					'CUT' => 'Cutting operator',
					'PLANNER' => 'Planner'
				];
		// dd($data);
		return view('Operators.create', compact('op'));
	}

	public function operator_create_post(Request $request)
	{
		//
		$this->validate($request, ['operator'=>'required', 'device'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$operator = $input['operator'];
		$d = $input['device'];
		// dd($d);
		$device = '';
		foreach ($input['device'] as $line) {
			$device .= $line.' ';
		}

		$device = trim($device);
		$device_array = serialize($input['device']);
		// dd(unserialize($device));

		try {
			$table = new operators;

			$table->operator = $operator;
			$table->device = $device;
			$table->device_array = $device_array;
			$table->status = "ACTIVE";
			
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('operator.error');
		}

		return Redirect::to('operators');
	}

	public function operator_edit($id) {
		// dd($id);
		$op = [	    'SP' => 'Spreader operator',
					'MS' => 'Manual spreader operator',
					'MM' => 'Mini marker operator',
					'LR' => 'Leftover operator',
					'PSO' => 'Paspul sewing operator',
					'PRW' => 'Paspul rewinding operator',
					'PCO' => 'Paspul cutting operator',
					'PACK' => 'Packing operator',
					'PLOT' => 'Ploter operator',
					'CUT' => 'Cutting operator',
					'PLANNER' => 'Planner'
				];

		$data = operators::findOrFail($id);
		return view('Operators.edit', compact('data','op'));
	}

	public function operator_edit_post(Request $request) {

		$this->validate($request, ['device'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$id = $input['id'];
		// $operator = $input['operator'];
		$d = $input['device'];
		$status = $input['status'];
		// dd($d);
		$device = '';
		foreach ($input['device'] as $line) {
			$device .= $line.' ';
		}

		$device = trim($device);
		$device_array = serialize($input['device']);
		// dd(unserialize($device));

		try {
			$table = operators::findOrFail($id);

			// $table->operator = $operator;
			$table->device = $device;
			$table->device_array = $device_array;
			$table->status = $status;
			
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			return view('operator.error');
		}

		return Redirect::to('operators');
	}
}
