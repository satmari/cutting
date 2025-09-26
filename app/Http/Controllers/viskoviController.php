<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;


use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;


use App\viskovi_table;

// use DB;
use Illuminate\Support\Facades\DB;
use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;
use Carbon\Carbon;

use Session;
use Validator;

class viskoviController extends Controller {

	public function index() {
		//
		// dd('test');

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM viskovi_tables ORDER BY id asc"));
		return view('viskovi.index',compact('data'));

	}

	public function viskovi_add() {

		$styles = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT [style]
			FROM [posummary].[dbo].[pro]
			ORDER BY style asc"));
		// dd($styles);

		$colors = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT [color]
			FROM [posummary].[dbo].[pro]
			ORDER BY color asc"));
		// dd($colors);

		return view('viskovi.viskovi_add', compact('styles','colors'));

	}

	public function viskovi_add_post(Request $request) {

	    $input = $request->all();

	    $style = $input['style'];
	    $color = $input['color'];

	    // Get color description
	    $color_d = DB::connection('sqlsrv6')->select(DB::raw("
	        SELECT TOP 1 [color_desc]
	        FROM [posummary].[dbo].[pro]
	        WHERE color = '".$color."'
	        ORDER BY color_desc ASC
	    "));

	    $color_desc = isset($color_d[0]->color_desc) ? $color_d[0]->color_desc : '';

	    // ✅ Check if already exists in viskovi_table
	    $exists = viskovi_table::where('style', $style)
	        ->where('color', $color)
	        ->exists();

	    if ($exists) {
	        // Already exists, return error msg
	        $msge = 'Greška: Ovaj style i color već postoji u tabeli!';
	    } else {
	        // Save new record
	        $table = new viskovi_table;
	        $table->style = $style;
	        $table->color = $color;
	        $table->color_desc = $color_desc;
	        $table->save();

	        $msgs = 'Uspešno snimljen višak';
	    }

	    // Load dropdown values again
	    $styles = DB::connection('sqlsrv6')->select(DB::raw("
	        SELECT DISTINCT [style]
	        FROM [posummary].[dbo].[pro]
	        ORDER BY style ASC
	    "));

	    $colors = DB::connection('sqlsrv6')->select(DB::raw("
	        SELECT DISTINCT [color]
	        FROM [posummary].[dbo].[pro]
	        ORDER BY color ASC
	    "));

	    return view('viskovi.viskovi_add', compact('styles', 'colors', 'msgs', 'msge'));
		

	}

	public function viskovi_delete ($id) {

		// dd($id);
		$table = viskovi_table::where('id', $id)->firstOrFail();
		$table->delete();

		return Redirect::to('/');

	}




}
