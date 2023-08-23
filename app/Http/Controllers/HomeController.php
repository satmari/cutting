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
use App\mattress_phases;
use App\mattress_eff;
use App\mattress_pro;
use App\mattresses;
use App\mattress_split_request;
use App\po;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class HomeController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index() {	

		$user = User::find(Auth::id());

		// if ($user->is('admin')) { 
		//     // if user has at least one role
		//     $msg = "Hi admin";
		// }
		// if ($user->is('preparacija')) { 
		//     // if user has at least one role
		//     $msg = "Pa gde ste preparacija?";
		//     //return redirect('/maintable');
		// }

		// dd($user);

		if (!is_null($user)) {

			if ($user->is('modul')) { 
			    // if user has at least one role
			    $msg = "Hi modul";
			  	return redirect('/request');
		 	}

		 	if ($user->is('planner')) { 
			    // if user has at least one role
			    // dd("planner");
			    // $msg = "Hi SP";
			  	return redirect('/plan_mattress/BOARD');
		 	}

		 	if ($user->is('SP')) { 
			    // if user has at least one role
			    // dd("SP");
			    // $msg = "Hi SP";
			 //    $operator = Session::get('operator');
				// $work_place = Session::get('work_place');
				// $operators = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
				//       ,[operator]
				//       ,[device]
				//       ,[device_array]
				//   FROM [operators]
				//   WHERE device like '%".$work_place."%' AND status = 'ACTIVE' "));
				// dd($operators);
				
			  	return redirect('/spreader');
			  	
		 	}

		 	if ($user->is('MS')) { 
			    // if user has at least one role
			    // dd("MS");
			    // $msg = "Hi MS";
			  	// return redirect('/request');
			  	return redirect('/spreader');
		 	}

		 	if ($user->is('MM')) { 
			    // if user has at least one role
			    // dd("MM");
			    // $msg = "Hi MS";
			  	// return redirect('/request');
			  	return redirect('/spreader');
		 	}

		 	if ($user->is('LR')) { 
			    // if user has at least one role
			    // dd("LR");
			    // $msg = "Hi MS";
			  	return redirect('/lr');
		 	}

		 	if ($user->is('PSO')) { 
			    // if user has at least one role
			    // dd("PSO");
			    // $msg = "Hi MS";
			  	return redirect('/pso');
		 	}

		 	if ($user->is('PRW')) { 
			    // if user has at least one role
			    // dd("PRW");
			    // $msg = "Hi MS";
			  	// return redirect('/prw');
			  	return redirect('/prw1');
		 	}

		 	if ($user->is('PCO')) { 
			    // if user has at least one role
			    // dd("PCO");
			    // $msg = "Hi MS";
			  	// return redirect('/pco');
			  	return redirect('/pco1');
		 	}

		 	if ($user->is('PACK')) { 
			    // if user has at least one role
			    // dd("PACK");
			    // $msg = "Hi MS";
			  	return redirect('/pack');
		 	}

		 	if ($user->is('PLOT')) { 
			    // if user has at least one role
			    // dd("PLOT");
			    // $msg = "Hi MS";
			  	return redirect('/plot');
		 	}

		 	if ($user->is('LEC')) { 
			    // if user has at least one role
			    // dd("LEC");
			    // $msg = "Hi MS";
			  	return redirect('/cutter');
		 	}

		 	if ($user->is('TUB')) { 
			    // if user has at least one role
			    // dd("TUB");
			    // $msg = "Hi TUB";
			  	return redirect('/tub');
		 	}

		 	if ($user->is('K-PREP')) { 
			    // if user has at least one role
			    return redirect('/req_lost');
		 	}

		 	if ($user->is('PSS')) { 
			    // if user has at least one role
			    return redirect('/pss');
		 	}

		 	if ($user->is('PSK')) { 
			    // if user has at least one role
			    return redirect('/psk');
		 	}

		 	if ($user->is('PSZ')) { 
			    // if user has at least one role
			    return redirect('/psz');
		 	}

		 	if ($user->is('WHS')) { 
			    // if user has at least one role
			    return redirect('/whs');
		 	}

		}

		return view('home');
	}

	public function test() {
		// dd("test");

		/*
		$find_mattress_id = DB::connection('sqlsrv')->select(DB::raw("SELECT mattress_id, status, COUNT(*) as lines
			FROM [cutting].[dbo].[mattress_phases]
			--WHERE status != 'TO_LOAD' AND status != 'ON_HOLD' AND status != 'TO_SPREAD'
			GROUP BY mattress_id, status
			HAVING COUNT(*) > 1
			ORDER BY mattress_id
		"));
		// dd($find_mattress_id);

		for ($i=0; $i < count($find_mattress_id); $i++) { 
			// dd($find_mattress_id[$i]->mattress_id);

			$find_to_load = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP (1) *
				FROM mattress_phases
				WHERE (mattress_id = '".$find_mattress_id[$i]->mattress_id."') AND (status = 'TO_LOAD')
			"));

			// dd($find_to_load[0]->id);
			// $l = mattress_phases::findOrFail($find_to_load[0]->id);
	    	// $l->delete();

		}
		

		$mattress_phases_not_active = DB::connection('sqlsrv')->update(DB::raw("
				UPDATE [mattress_phases_old]
				SET active = 0
				WHERE mattress_id = '1';
		"));
		*/
		
		// $id = 2832;

		// $mattress_phases_not_active = DB::connection('sqlsrv')->select(DB::raw("
		// 	SET NOCOUNT ON;
		// 	UPDATE [mattress_phases]
		// 	SET active = 0
		// 	WHERE mattress_id = '".$id."' AND active = 1;
		// 	SELECT TOP 1 mattress FROM [mattress_phases] WHERE mattress_id = '".$id."';
		// "));
		// $mattress = $mattress_phases_not_active[0]->mattress;
		// dd($mattress);


		
		

		// firstOrNew;
		// $table3_new = new po;
		// $table3_new->po = '112293';
		// $table3_new->status = '112233';
		// $table3_new->save();
	
		// firstOrNew;
		// $table3_new = po::firstOrNew(['status' => 'ssnew1', 'po' => '112295']);
		// $table3_new->po = '112295';
		// $table3_new->status = 'ssnew';
		// $table3_new->save();

		// $table3_new = mattress_phases::firstOrNew(['id_status' => 'ssnew1']);
		// $table3_new->po = '112295';
		// $table3_new->status = 'ssnew';
		// $table3_new->save();


		$jedan = 1;
		$dva = 'dva';

		$final = $jedan.'-'.$dva;
		dd($final);
	}


	public function tombola() {
		return view('tombola');	
	}
}
