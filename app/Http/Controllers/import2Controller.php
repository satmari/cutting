<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;

use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Request;

use App\User;
use DB;
use DateTime;

class import2Controller extends Controller {

	public function index() {

		return view('Import2.index');
	}

	public function postImport_by(Request $request) {

		// dd('test');
		$getSheetName = Excel::load(Request::file('file1'))->getSheetNames();
	    // dd($getSheetName);

	    foreach($getSheetName as $sheetName) {

	        // DB::table('tpp_materils')->truncate();
	        Excel::filter('chunk')->selectSheets($sheetName)->load(Request::file('file1'))->chunk(5000, function ($reader)
	            {	
	                $readerarray = $reader->toArray();
	                // dd($readerarray);

	                foreach($readerarray as $row)
	                {
						dd($row);
						$inbd_id = $row['inbd_id'];
						$real_doc_no = $row['real_doc_no'];
						$id = (int)$row['id'];
						
						// dd($comment);

						// $userbulk = new tpp_material;
						// $userbulk->tpp_material = $tpp_materials_in_gordon;
						// $userbulk->save();

						$sql2 = DB::connection('sqlsrv')->update(DB::raw("
								UPDATE [cutting].[dbo].[fabric_reservations]
								SET [document_no] = '".$real_doc_no."'
								WHERE id = '".$id."'
							"));	 
						
	                }

	            });
	    }
	    dd('Finished');
		// return redirect('/');
	}






}