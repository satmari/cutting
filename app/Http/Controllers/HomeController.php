<?php namespace App\Http\Controllers;

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

	public function index()
	{	
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
				//   FROM [cutting].[dbo].[operators]
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
			  	return redirect('/prw');
		 	}

		 	if ($user->is('PCO')) { 
			    // if user has at least one role
			    // dd("PCO");
			    // $msg = "Hi MS";
			  	return redirect('/pco');
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

		}

		return view('home');
	}

}
