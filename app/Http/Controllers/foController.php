<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\mattress_details;
use App\mattress_markers;
use App\marker_change;
use App\material_requests;
use App\material_request_phases;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class foController extends Controller {

	public function index() {
		// dd('cao fo');
		
		// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('fo.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "FO";
		// dd($location);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mr.*,
				mrp.status, mrp.location, mrp.operator1, mrp.operator2, mrp.updated_at as up
				,(SELECT location FROM [material_request_phases] WHERE [material_request_id] =  mr.[id] AND [status] = 'CREATED') as location_created
			FROM [material_requests] as mr
			JOIN [material_request_phases] as mrp ON mrp.[material_request_id] = mr.[id]
		 	WHERE (mrp.[status] = 'CREATED' OR mrp.[status] = 'ACCEPTED' OR mrp.[status] = 'RELAX' OR mrp.[status] = 'QC') AND active = '1'
		 	ORDER by mr.[created_at] asc
		 	"));
		// dd($data);

		// $work_place = substr($device, 0,2);
		$work_place = "FO";
		// Session::set('work_place',$work_place);
	
		$operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[operator]
		      ,[device]
		      ,[device_array]
		  FROM [operators]
		  WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
		// dd($operators);

		$operator = Session::get('operator');
		// $operator = 'test';
		// dd("Test");

		return view('fo.index', compact('data','location','operators','operator'));
	}

	public function operator_login (Request $request) {
		//
		// $this->validate($request, ['container' => 'required']);
		$input = $request->all(); 
		// dd($input);
		if (isset($input['selected_operator'])) {
			$selected_operator = $input['selected_operator'];

			if ($selected_operator != '') {
				$operator = Session::set('operator', $selected_operator);
				return redirect('/fo');
			} else {
				$operator = Session::set('operator', NULL);
				return redirect('/fo');
			}
		} else {
			$operator = Session::get('operator');
			// $operator = Session::set('operator', $selected_operator);
			return redirect('/fo');
		}
	}

	public function operator_logout () {
		
		$operator = Session::set('operator', NULL);
		return redirect('/fo');
	}

	public function request_material_accept($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}


		return view('fo.request_material_accept',compact('id'));
	}

	public function request_material_accept_confirm($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('fo.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mr.*,
				mrp.status, mrp.location, mrp.device, mrp.operator1, mrp.operator2
			FROM [material_requests] as mr
			JOIN [material_request_phases] as mrp ON mrp.[material_request_id] = mr.[id]
		 	WHERE mrp.active = '1' and mr.id = '".$id."'
		 	ORDER by mrp.[status] desc, mrp.[created_at] desc "));		
		// dd($data);

		// $location 	= $data[0]->location;
		// $device 	= $data[0]->device;
		// $operator1	= $data[0]->operator1;
		// $operator2 	= $data[0]->operator2;

		$request_material_phases_not_active = DB::connection('sqlsrv')->update(DB::raw("
			UPDATE [material_request_phases]
			SET active = 0
			WHERE material_request_id = '".$id."' AND active = 1;
		"));

		$status = 'ACCEPTED';

		$table_phases = material_request_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table_phases->material_request_id = $id;
		$table_phases->status = $status;
		$table_phases->location = $location;
		$table_phases->device = $device;
		$table_phases->active = 1;
		$table_phases->operator1 = $operator;
		$table_phases->operator2 = $operator2;
		$table_phases->id_status = $id."-".$status;
		$table_phases->save();

		return redirect('/');
	}

	public function request_material_deliver($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mr.*,
				mrp.status, mrp.location, mrp.device, mrp.operator1, mrp.operator2
			FROM [material_requests] as mr
			JOIN [material_request_phases] as mrp ON mrp.[material_request_id] = mr.[id]
		 	WHERE mrp.active = '1' and mr.id = '".$id."'
		 	ORDER by mrp.[status] desc, mrp.[created_at] desc "));	

		$status = $data[0]->status;

		return view('fo.request_material_deliver',compact('id', 'status'));
	}

	public function request_material_deliver_confirm($id) {
		// dd($id);
		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		return view('fo.request_material_deliver_confirm',compact('id'));
	}

	public function request_material_deliver_confirm_post ($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('fo.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mr.*,
				mrp.status, mrp.location, mrp.device, mrp.operator1, mrp.operator2
			FROM [material_requests] as mr
			JOIN [material_request_phases] as mrp ON mrp.[material_request_id] = mr.[id]
		 	WHERE mrp.active = '1' and mr.id = '".$id."'
		 	ORDER by mrp.[status] desc, mrp.[created_at] desc "));		
		// dd($data);

		// $location 	= $data[0]->location;
		// $device 	= $data[0]->device;
		// $operator1	= $data[0]->operator1;
		// $operator2 	= $data[0]->operator2;

		
		$request_material_phases_not_active = DB::connection('sqlsrv')->update(DB::raw("
			UPDATE [material_request_phases]
			SET active = 0
			WHERE material_request_id = '".$id."' AND active = 1;
		"));

		$status = 'DELEVERED';

		$table_phases = material_request_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table_phases->material_request_id = $id;
		$table_phases->status = $status;
		$table_phases->location = $location;
		$table_phases->device = $device;
		$table_phases->active = 1;
		$table_phases->operator1 = $operator;
		$table_phases->operator2 = $operator2;
		$table_phases->id_status = $id."-".$status;
		$table_phases->save();

		return redirect('/');
	}

	public function request_material_relax_confirm($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		return view('fo.request_material_relax_confirm',compact('id'));
	}

	public function request_material_relax_confirm_post($id) {

		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('fo.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mr.*,
				mrp.status, mrp.location, mrp.device, mrp.operator1, mrp.operator2
			FROM [material_requests] as mr
			JOIN [material_request_phases] as mrp ON mrp.[material_request_id] = mr.[id]
		 	WHERE mrp.active = '1' and mr.id = '".$id."'
		 	ORDER by mrp.[status] desc, mrp.[created_at] desc "));		
		// dd($data);

		// $location 	= $data[0]->location;
		// $device 	= $data[0]->device;
		// $operator1	= $data[0]->operator1;
		// $operator2 	= $data[0]->operator2;

		
		$request_material_phases_not_active = DB::connection('sqlsrv')->update(DB::raw("
			UPDATE [material_request_phases]
			SET active = 0
			WHERE material_request_id = '".$id."' AND active = 1;
		"));

		$status = 'RELAX';

		$table_phases = material_request_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table_phases->material_request_id = $id;
		$table_phases->status = $status;
		$table_phases->location = $location;
		$table_phases->device = $device;
		$table_phases->active = 1;
		$table_phases->operator1 = $operator;
		$table_phases->operator2 = $operator2;
		$table_phases->id_status = $id."-".$status;
		$table_phases->save();

		return redirect('/');
	}

	public function request_material_qc_confirm($id) {

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		return view('fo.request_material_qc_confirm',compact('id'));
	}

	public function request_material_qc_confirm_post($id) {
		// dd($id);

		$operator = Session::get('operator');
		if (!isset($operator) OR $operator == '') {
			
			$msg ='Operator must be logged!';
			return view('fo.error',compact('msg'));
		}
		$check_op = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 *
			FROM operators
			WHERE operator = '".$operator."' COLLATE Latin1_General_CI_AI 
			AND ( [device] like '%TO%' )"));
		if (!isset($check_op)) {
			dd("Wrong operator, call Atila !");
		}

		$operator2 = Session::get('operator2');
		if (!isset($operator2) OR $operator2 == '') {
			$operator2 = '';
		}

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			$msg ='Device is not autenticated';
			return view('fo.error',compact('msg'));
		}
		$location = substr($device, 0,3);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
				mr.*,
				mrp.status, mrp.location, mrp.device, mrp.operator1, mrp.operator2
			FROM [material_requests] as mr
			JOIN [material_request_phases] as mrp ON mrp.[material_request_id] = mr.[id]
		 	WHERE mrp.active = '1' and mr.id = '".$id."'
		 	ORDER by mrp.[status] desc, mrp.[created_at] desc "));		
		// dd($data);

		// $location 	= $data[0]->location;
		// $device 	= $data[0]->device;
		// $operator1	= $data[0]->operator1;
		// $operator2 	= $data[0]->operator2;

		
		$request_material_phases_not_active = DB::connection('sqlsrv')->update(DB::raw("
			UPDATE [material_request_phases]
			SET active = 0
			WHERE material_request_id = '".$id."' AND active = 1;
		"));

		$status = 'QC';

		$table_phases = material_request_phases::firstOrNew(['id_status' => $id.'-'.$status]);
		$table_phases->material_request_id = $id;
		$table_phases->status = $status;
		$table_phases->location = $location;
		$table_phases->device = $device;
		$table_phases->active = 1;
		$table_phases->operator1 = $operator;
		$table_phases->operator2 = $operator2;
		$table_phases->id_status = $id."-".$status;
		$table_phases->save();

		return redirect('/');
	}

	
}
