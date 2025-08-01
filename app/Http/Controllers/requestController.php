<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use DB;
use App\req_extrabb;
use App\req_cartonbox;
use App\req_reprintbb;
use App\req_padprint;
use App\req_cut_part;
use App\req_lost;
use App\req_paspul;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class requestController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		// Session::set('leaderid', NULL);
	}

	public function index()
	{
		//
		/*
		$leaderid = Session::get('leaderid');
		if (isset($leaderid)) {

			$leader = Session::get('leader');
	    	$module = Session::get('module');
			// return Redirect::to('afterlogin');
			return view('requests.select', compact('leaderid','leader','module'));
		}
		*/
		return view('InteosLogin.index');

	}

	public function logincheck(Request $request) {

		$this->validate($request, ['pin'=>'required|min:4|max:5']);
		$forminput = $request->all(); 

		$pin = $forminput['pin'];
		// dd($pin);

		$inteosll = DB::connection('sqlsrv2')->select(DB::raw("SELECT Cod,Name FROM BdkCLZG.dbo.WEA_PersData WHERE Func = 23 AND FlgAct = 1 AND PinCode = '".$pin."'"));
		/*
		$inteosleaders = DB::connection('sqlsrv2')->select(DB::raw("SELECT 
			Name 
		FROM [BdkCLZG].[dbo].[WEA_PersData] 
		WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.")
		UNION ALL
		SELECT 
			Name 
		FROM [SBT-SQLDB01P\\INTEOSKKA].[BdkCLZKKA].[dbo].[WEA_PersData]
		WHERE (Func = 23) and (FlgAct = 1) and (PinCode = ".$pin.")"));
		*/

		if (empty($inteosll)) {
			$msg = 'LineLeader with this PIN is not active';
		    return view('InteosLogin.error',compact('msg'));
		
		} else {
			foreach ($inteosll as $row) {
				$leaderid = $row->Cod;
    			$leader = $row->Name;
    			Session::set('leaderid', $leaderid);
    			Session::set('leader', $leader);
    		}

   			if (Auth::check())
			{
			    $userId = Auth::user()->id;
			    $module = Auth::user()->name;
			} else {
				$msg = 'Modul is not autenticated';
				return view('InteosLogin.error',compact('msg'));
			}


			$crtica = substr($module, 1, 1);

			if ($crtica == "-") {

				$module_line = substr($module, 0, 1);
	    		$module_name = substr($module, 2, 3);
	    		
	    		$module = $module_line." ".$module_name;

	    		Session::set('module', $module);	

			} else {

				$module_line = substr($module, 0, 1);
	    		$module_name = substr($module, 1, 3);
	    		
	    		$module = $module_line." ".$module_name;

	    		Session::set('module', $module);	
			}

			
    	}

    	$leaderid = Session::get('leaderid');
    	$leader = Session::get('leader');
    	$module = Session::get('module');

    	if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}
    	
		// dd($leader);
    	// dd($module);

		return view('requests.select', compact('leaderid','leader','module'));
		// return Redirect::to('request/');
	}

	public function table_select() {

		return view('requests.table_select');
	}
	
	// Extra BB
	public function req_extrabb() {

		$leaderid = Session::get('leaderid');
    	$leader = Session::get('leader');
    	$module = Session::get('module');

    	if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

    	// dd($module);
    	return view('requests.req_extrabb_form', compact('leaderid','leader','module'));

	}

	public function req_extrabbconfirm(Request $request) {

		$this->validate($request, ['po'=>'required|min:6|max:7', 'size'=>'required', 'qty'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$po = $input['po'];
		$size = $input['size'];
		$bagno = $input['bagno'];
		$qty = (int)$input['qty'];

		$check_po = DB::connection('sqlsrv4')->select(DB::raw("SELECT DISTINCT (CASE WHEN po like '%-%' THEN substring(po, 1,6) ELSE substring (po, 4,6) END) as po, fg
		FROM [trebovanje].[dbo].[sap_coois] WHERE po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));
		// dd($check_po);


		if (!isset($check_po[0])) {
			$msg = 'Komesa + velicina ne postoji ili nije vise otvorena';
			return view('requests.error',compact('msg'));	
		}

		$style = $check_po[0]->fg;

    	$module = Session::get('module');
    	$leader = Session::get('leader');

		if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

		try {
			$table = new req_extrabb;
			$table->po = $po;
			$table->size = $size;
			$table->bagno = $bagno;
			$table->module = $module;
			$table->leader = $leader;
			$table->qty = $qty;
			$table->style = $style;
			$table->status = "Pending";
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('request/');
	}

	public function req_extrabb_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_extrabbs WHERE status = 'Pending' ORDER BY created_at asc"));
		return view('requests.req_extrabb_table', compact('data'));
	}

	public function req_extrabb_table_history() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_extrabbs WHERE created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		$h = 'History';
		return view('requests.req_extrabb_table', compact('data', 'h'));
	}

	public function edit_req_extrabb_status($id) {
		// dd($id);
		return view('requests.req_extrabb_status', compact('id'));
	}

	public function req_extrabb_status(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		$comment = $input['comment'];

		try {
			$table = req_extrabb::findOrFail($id);

			$table->status = "Completed";
			$table->comment = $comment;
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_extrabb_table/');
	}
	
	// Catonbox
	public function req_cartonbox() {

		$leaderid = Session::get('leaderid');
    	$leader = Session::get('leader');
    	$module = Session::get('module');

    	if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

    	// dd($module);
    	return view('requests.req_cartonbox_form', compact('leaderid','leader','module'));

	}

	public function req_cartonboxconfirm(Request $request) {

		$this->validate($request, ['po'=>'required|min:6|max:7', 'size'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$po = $input['po'];
		$size = $input['size'];
		
		$check_po = DB::connection('sqlsrv4')->select(DB::raw("SELECT DISTINCT (CASE WHEN po like '%-%' THEN substring(po, 1,6) ELSE substring (po, 4,6) END) as po
		FROM [trebovanje].[dbo].[sap_coois] WHERE po like '%".$po."%' AND substring(fg,14,5) = '".$size."' "));
		// dd($check_po);

		if (!isset($check_po[0])) {
			$msg = 'Komesa + velicina ne postoji ili nije vise otvorena';
			return view('requests.error',compact('msg'));	
		}

		
    	$module = Session::get('module');
    	$leader = Session::get('leader');

		if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

		try {
			$table = new req_cartonbox;

			$table->po = $po;
			$table->size = $size;
			$table->module = $module;
			$table->leader = $leader;
			
			$table->status = "Pending";

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('request/');
	}

	public function req_cartonbox_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_cartonboxes WHERE status = 'Pending' ORDER BY created_at asc"));
		return view('requests.req_cartonbox_table', compact('data'));

	}

	public function req_cartonbox_table_history() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_cartonboxes WHERE created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		$h = 'History';
		return view('requests.req_cartonbox_table', compact('data', 'h'));
	}

	public function edit_req_cartonbox_status($id) {
		// dd($id);
		return view('requests.req_cartonbox_status', compact('id'));
	}

	public function req_cartonbox_status(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		$comment = $input['comment'];

		try {
			$table = req_cartonbox::findOrFail($id);

			$table->status = "Completed";
			$table->comment = $comment;
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_cartonbox_table/');
	}

	// ReprintBB
	public function req_reprintbb() {

		$leaderid = Session::get('leaderid');
    	$leader = Session::get('leader');
    	$module = Session::get('module');

    	if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

    	// dd($module);
    	return view('requests.req_reprintbb_form', compact('leaderid','leader','module'));

	}

	public function req_reprintbbconfirm(Request $request) {

		$this->validate($request, ['po'=>'required|min:6|max:7', 'bb'=>'required|min:3|max:4', 'size'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$po = $input['po'];
		$bb = $input['bb'];
		$size = $input['size'];
		// dd($size);
		
		// $check_bb = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BlueBoxNum]
		// FROM [BdkCLZG].[dbo].[CNF_BlueBox]
		// WHERE BlueBoxNum like '%".$po.$bb."'"));

		// $check_bb = DB::connection('sqlsrv2')->select(DB::raw("SELECT [BlueBoxNum] as bb
		// FROM [BdkCLZG].[dbo].[CNF_BlueBox]

		// WHERE BlueBoxNum like '%".$po."%".$bb."' 
		// UNION ALL
		// SELECT [BlueBoxNum]
		// FROM [SBT-SQLDB01P\\INTEOSKKA].[BdkCLZKKA].[dbo].[CNF_BlueBox]
		// WHERE BlueBoxNum like '%".$po."%".$bb."' "));


		// dd($check_bb);

		// if (!isset($check_bb[0])) {
		// 	$msg = 'Komesa + bb ne postoji';
		// 	return view('requests.error',compact('msg'));
		// }
		
    	$module = Session::get('module');
    	$leader = Session::get('leader');

		if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

		try {
			$table = new req_reprintbb;

			$table->po = $po;
			$table->bb = $bb;
			// $table->bb = $check_bb[0]->bb;
			$table->size = $size;
			$table->module = $module;
			$table->leader = $leader;
			
			$table->status = "Pending";

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('request/');
	}

	public function req_reprintbb_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_reprintbbs WHERE status = 'Pending' ORDER BY created_at asc"));
		return view('requests.req_reprintbb_table', compact('data'));

	}

	public function req_reprintbb_table_history() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_reprintbbs WHERE created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		$h = 'History';
		return view('requests.req_reprintbb_table', compact('data', 'h'));
	}

	public function edit_req_reprintbb_status($id) {
		// dd($id);
		return view('requests.req_reprintbb_status', compact('id'));
	}

	public function req_reprintbb_status(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		$comment = $input['comment'];

		try {
			$table = req_reprintbb::findOrFail($id);

			$table->status = "Completed";
			$table->comment = $comment;
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_reprintbb_table/');
	}

	// PadPrint
	public function req_padprint() {
		// dd("CAO");

		$leaderid = Session::get('leaderid');
    	$leader = Session::get('leader');
    	$module = Session::get('module');
    	// dd($module);
    	if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

    	$style_data = DB::connection('sqlsrv3')->select(DB::raw("SELECT style FROM styles WHERE LEN(style) < 9 ORDER BY style asc"));
    	// dd($style_data);

    	return view('requests.req_padprint_form', compact('leaderid','leader','module','style_data'));

	}

	public function req_padprintconfirm(Request $request) {

		$this->validate($request, ['qty'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$qty = $input['qty'];
		$style = $input['style'];

		/*
		$check_pad;
		if (!isset($check_bb[0])) {
			$msg = 'Komesa + bb ne postoji';
			return view('requests.error',compact('msg'));
		}
		*/

    	$module = Session::get('module');
    	$leader = Session::get('leader');

		if (!isset($module)) {
			$msg = 'Module is not autenticated';
			return view('requests.error',compact('msg'));
		}
		if (!isset($leader)) {
			$msg = 'LineLeader is not autenticated';
			return view('requests.error',compact('msg'));
		}

		try {
			$table = new req_padprint;

			$table->qty = $qty;
			$table->style = $style;
			$table->module = $module;
			$table->leader = $leader;
			
			$table->status = "Pending";

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('request/');
	}

	public function req_padprint_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_padprints WHERE status = 'Pending' OR status = 'Collected' ORDER BY created_at asc"));
		return view('requests.req_padprint_table', compact('data'));
	}

	public function req_padprint_table_history() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_padprints WHERE created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		$h = 'History';
		return view('requests.req_padprint_table', compact('data', 'h'));
	}

	public function edit_req_padprint_status($id) {
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id, status FROM req_padprints WHERE id = '".$id."' "));
		// dd($data);

		$id = $data[0]->id;
		$status = $data[0]->status;

		// dd($status);

		return view('requests.req_padprint_status', compact('id','status'));
	}

	public function req_padprint_status(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		// $comment = $input['comment'];

		try {
			$table = req_padprint::findOrFail($id);

			$table->status = "Collected";
			// $table->comment = $comment;

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_padprint_table/');
	}

	public function req_padprint_status1(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		$comment = $input['comment'];

		try {
			$table = req_padprint::findOrFail($id);

			$table->status = "Completed";
			$table->comment = $comment;

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_padprint_table/');
	}

	public function req_cut_part_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_cut_parts WHERE (status = 'Pending' OR
		 status = 'Partially Delivered') ORDER BY created_at asc"));
		return view('requests.req_cut_part_table', compact('data'));
	}

	public function req_cut_part_table_history() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_cut_parts WHERE created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		$h = 'History';
		return view('requests.req_cut_part_table', compact('data', 'h'));
	}
	
	public function edit_req_cut_part_status($id) {
		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT status, comment_cut FROM req_cut_parts WHERE id = '".$id."' "));
		// dd($data[0]->status);
		$status = $data[0]->status;
		$comment_cut = $data[0]->comment_cut;

		return view('requests.req_cut_part_status', compact('id','status','comment_cut'));
	}

	public function req_cut_part_status(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		
		$comment_cut = $input['comment'];

		try {
			$table = req_cut_part::findOrFail($id);

			$table->status = "Completed";
			$table->comment_cut = $comment_cut;
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_cut_part_table/');
	}

	public function req_cut_part_status_p(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		// dd($input);

		$id = $input['id'];
		
		$comment_cut = $input['comment'];

		try {
			$table = req_cut_part::findOrFail($id);

			$table->status = "Partially Delivered";
			$table->comment_cut = $comment_cut;
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_cut_part_table/');
	}

	public function req_cut_part_status_c(Request $request) {	

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		
		$comment_cut = $input['comment'];

		try {
			$table = req_cut_part::findOrFail($id);

			$table->status = "Canceled";
			$table->comment_cut = $comment_cut;
			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		return Redirect::to('req_cut_part_table/');
	}

	public function req_paspul($id) {

		// dd($id);
		$table = req_cut_part::findOrFail($id);
		$qty = $table->qty;
		return view('requests.req_paspul', compact('id','qty'));

	}

	public function req_paspul_post(Request $request) {

		$this->validate($request, ['id'=>'required']);
		$input = $request->all();
		$id = $input['id'];
		$req_qty = (int)$input['req_qty'];

		// dd($req_qty);
		
			$table = req_cut_part::findOrFail($id);
			$table->sent = 'sent';			
			$table->req_qty = $req_qty;
			$table->save();

			$table_new = new req_paspul;
			$table_new->module = $table->module;
			$table_new->po = $table->po;
			$table_new->bb = $table->bb;
			$table_new->style = $table->style;
			$table_new->color = $table->color;
			$table_new->size = $table->size;
			$table_new->bagno = $table->bagno;
			$table_new->image = $table->image;

			$table_new->part = $table->part;
			$table_new->qty = $table->qty;
			$table_new->comment = $table->comment;
			$table_new->status = $table->status;			

			$table_new->sent = 'sent';
			$table_new->req_qty = $req_qty;
			$table_new->save();


		return Redirect::to('req_cut_part_table/');

	}

	public function req_lost() {

		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $module = Auth::user()->name;
		} else {
			$msg = 'Modul is not autenticated';
			return view('InteosLogin.error',compact('msg'));
		}

    	
		$skus = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT sku FROM [posummary].[dbo].[pro] ORDER BY sku asc"));
		// dd($skus);

    	return view('requests.req_lost_form', compact('module','skus'));
	}

	public function req_lostconfirm (Request $request) {	

		// $this->validate($request, ['qty'=>'required','selected_sku'=>'required']);
		$input = $request->all();
		// dd($input);
		$module = $input['module'];

		$skus = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT sku FROM [posummary].[dbo].[pro] ORDER BY sku asc"));

		if (!isset($input['selected_sku'])) {
			$msge = "Missing SKU";
			return view('requests.req_lost_form', compact('module','skus','msge'));

		} elseif ($input['selected_sku'] == "") {
			$msge = "Missing SKU";
			return view('requests.req_lost_form', compact('module','skus','msge'));
		}

		if (($input['qty']) <= 0) {
			$msge = "Missing Qty";
			return view('requests.req_lost_form', compact('module','skus','msge'));
		}

		$sku = trim($input['selected_sku']);
		$qty = (int)$input['qty'];
		$comment = $input['comment'];

		try {
			$table = new req_lost;

			$table->sku = $sku;
			$table->qty = $qty;
			$table->module = $module;
			$table->bagno = "LOST BB";
			$table->status = "Pending";
			$table->comment = $comment;

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}

		$msgs = "Successfuly saved";
		return view('requests.req_lost_form', compact('module','skus','msgs'));

	}

	public function req_lost_table() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_losts WHERE status = 'Pending' ORDER BY created_at asc"));
		return view('requests.req_lost_table', compact('data'));
	}

	public function req_lost_table_history() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_losts WHERE created_at >= DATEADD(day,-30,GETDATE()) ORDER BY created_at desc"));
		$h = 'History';
		return view('requests.req_lost_table', compact('data', 'h'));
	}

	public function edit_req_lost_status($id) {

		return view('requests.req_lost_status', compact('id'));

	}

	public function req_lost_status(Request $request) {	

		// $this->validate($request, ['qty'=>'required','selected_sku'=>'required']);
		$input = $request->all();
		// dd($input);
		$id = $input['id'];
		$comment = $input['comment'];
		
		try {
			$table = req_lost::findOrFail($id);

			$table->status = "Completed";
			$table->comment = $comment;

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save"; 
			return view('requests.error', compact('msg'));
		}		

		return Redirect::to('req_lost_table');
	}

	public function req_lost_table_history_line() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM req_losts WHERE created_at >= DATEADD(day,-60,GETDATE()) ORDER BY created_at desc"));
		return view('requests.req_lost_table_history_line', compact('data'));
	}
}
