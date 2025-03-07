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
use App\res_log;
use App\Reservation;
use App\pos;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

use Symfony\Component\Console\Helper\ProgressBar;

class reservationController extends Controller {

	public function index()
	{
		// dd(" Cao reservation ");
		return view('reservations.index');
	}

	public function hu_list() // ne koristi se
	{
		
	}

	public function update_reservation_table_old() // ne koristi se
	{

	}

	public function update_reservation_table()
	{
		
	}	

	public function update_reservation_table_oposite() // ne koristi se
	{
		
	}

	public function reserv_mat()
	{

		return view('reservations.reserv_mat');
	}

	public function reserv_input(Request $request)
	{
		
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		// dd($input_batch);

		$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			SUM(balance) as bal,
			COUNT(hu) as coun,
			(SELECT SUM(balance) FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as reserv_not,
			(SELECT COUNT(hu) FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as coun_not,
			(SELECT SUM(balance) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as reserv_yes,
			(SELECT COUNT(hu) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as coun_yes,
			(SELECT SUM(balance) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as reserv_all,
			(SELECT COUNT(hu) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as coun_all
		  FROM [cutting].[dbo].[reservations]
		  where item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."'
		  group by item, variant, batch"));

		$reserved_mat = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(balance) as bal, COUNT(hu) as coun_po, res_po FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' group by res_po"));


		if (isset($reservation_table[0]->bal)) {
			
		}	else {
			$msg = "Combination of Item, Variant and Batch not exist";
			return view('reservations.errorm', compact('msg'));
		}


		// dd(floatval(round($reservation_table[0]->bal, 2)));

		if (isset($reservation_table[0]->bal)) {
			$bal = floatval(round($reservation_table[0]->bal, 2));
		}	else {
			$bal = 0;
		}

		if (isset($reservation_table[0]->coun)) {
			$coun = floatval(round($reservation_table[0]->coun, 2));
		}	else {
			$coun = 0;
		}

		if (isset($reservation_table[0]->reserv_not)) {
			$reserv_not = floatval(round($reservation_table[0]->reserv_not, 2));
		}	else {
			$reserv_not = 0;
		}

		if (isset($reservation_table[0]->coun_not)) {
			$coun_not = floatval(round($reservation_table[0]->coun_not, 2));
		}	else {
			$coun_not = 0;
		}
		
		if (isset($reservation_table[0]->reserv_yes)) {
			$reserv_yes = floatval(round($reservation_table[0]->reserv_yes, 2));
		}	else {
			$reserv_yes = 0;
		}

		if (isset($reservation_table[0]->coun_yes)) {
			$coun_yes = floatval(round($reservation_table[0]->coun_yes, 2));
		}	else {
			$coun_yes = 0;
		}

		if (isset($reservation_table[0]->reserv_all)) {
			$reserv_all = floatval(round($reservation_table[0]->reserv_all, 2));
		}	else {
			$reserv_all = 0;
		}

		if (isset($reservation_table[0]->coun_all)) {
			$coun_all = floatval(round($reservation_table[0]->coun_all, 2));
		}	else {
			$coun_all = 0;
		}

		return view('reservations.reserv_mat_select', compact('input_item','input_variant','input_batch','bal','coun','reserv_not','coun_not','reserv_yes','coun_yes','reserv_all','coun_all','reserved_mat'));
	}

	public function reserv_all_available(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		// dd($input_batch);

		// dd(date("Y-m-d H:i:s"));

		$list = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[reservations] where res_status = 'NO' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' "));
		// dd($list);

		if (isset($list[0]->id)) {
			
			return view('reservations.reserv_mat_po', compact('input_item', 'input_variant', 'input_batch'));

		} else {
			$msg = "There is no available material";
			return view('reservations.errorm', compact('msg'));
		}
	}

	public function reserv_all_available_confirm(Request $request)
	{

		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required', 'po'=> 'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		$input_po = $input['po'];
		// dd($input_po);

		$po_list = DB::connection('sqlsrv')->select(DB::raw("SELECT po FROM pos where status = 'OPEN' and po = '".$input_po."' "));

		if (count($po_list) == 0) {
			$msg = "This komesa is not in Komesa table or have status CLOSED.";
			return view('reservations.errorm', compact('msg'));
		}

		$list = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			SUM(balance) as bal,
			hu,
			(SELECT SUM(balance) as bal FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as bal_sum
			FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' GROUP BY res_po,hu"));
		// dd($list);

		if (isset($list[0]->bal)) {
			
			if ($list[0]->bal > 0) {
			
				$table_log = new res_log;

				try {
					
					$table_log->res_po = $input_po;
					$table_log->item = $input_item;
					$table_log->variant = $input_variant;
					$table_log->batch = $input_batch;

					$table_log->res_qty = floatval(round($list[0]->bal_sum, 2));

					$table_log->res_hus = count($list);

					$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$input_po."' "));
					if (isset($po_status[0]->status)) {
						$table_log->po_status = $po_status[0]->status;
					} else {
						$table_log->po_status = NULL;
					}
							
					$table_log->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to save in log table";
					return view('reservations.errorm', compact('msg'));
				}		

			}
		}

		$list = DB::connection('sqlsrv')->select(DB::raw("SELECT id,balance FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' "));
		// dd($list);

		for ($i=0; $i < count($list); $i++) { 
			
			$table = Reservation::findOrFail($list[$i]->id);
			
			try {

				// $table->hu = $temp_table_hu[$i]->hu;
				// $table->father_hu = $temp_table_hu[$i]->father_hu;
				// $table->item = $temp_table_hu[$i]->item;
				// $table->variant = $temp_table_hu[$i]->variant;
				// $table->status = $temp_table_hu[$i]->status;
				// $table->balance = $temp_table_hu[$i]->balance;
				// $table->batch = $temp_table_hu[$i]->batch;
				// $table->document = $temp_table_hu[$i]->document;
				// $table->bin = $temp_table_hu[$i]->bin;
				// $table->location = $temp_table_hu[$i]->location;

				$table->res_po = $input_po;
				$table->res_log_id = $table_log->id;
				$table->res_date = date("Y-m-d H:i:s");
				$table->res_status = 'YES';
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');			
			}
		}
		return Redirect::to('reservation/');
	}

	public function reserv_by_hu(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		// dd($input_batch);

		// dd(date("Y-m-d H:i:s"));

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[reservations] where status = 'Open' and res_status = 'NO' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' "));
		// dd($data);

		if (isset($data[0]->id)) {
			
			return view('reservations.reserv_mat_hu', compact('input_item', 'input_variant', 'input_batch', 'data'));

		} else {
			$msg = "There is no available material";
			return view('reservations.errorm', compact('msg'));
		}
	}

	public function reserv_by_hu_insert_po(Request $request)
	{	
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		// dd($input);

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		if (isset($input['checked'])) {
			$input_checked[] = $input['checked'];
			$input_checked = $input_checked[0];
			
			return view('reservations.reserv_by_hu_insert_po', compact('input_item', 'input_variant', 'input_batch', 'input_checked'));

		} else {
			// $input_checked[] = [];
			$msg = "You should check some hu's if you want to reserve them";
			return view('reservations.errorm', compact('msg'));
		}
	}
 
	public function reserv_by_hu_confirm(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required', 'po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		// dd($input);

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		$input_checked[] = $input['result'];
		$input_po = $input['po'];
		$input_checked = $input_checked[0];
		// dd($input_checked);

		$po_list = DB::connection('sqlsrv')->select(DB::raw("SELECT po FROM pos where status = 'OPEN' and po = '".$input_po."' "));

		if (count($po_list) == 0) {
			$msg = "This komesa is not in Komesa table or have status CLOSED.";
			return view('reservations.errorm', compact('msg'));
		}

		
		$qty = 0;
		for ($i=0; $i < count($input_checked); $i++) { 

			$table_temp = Reservation::findOrFail($input_checked[$i]);
			$qty = $qty + floatval(round($table_temp->balance, 2));
			// dd($qty);
		}

		if ($qty == 0 ) {
			$msg = "You can not reseve 0 qty";
			return view('reservations.errorm', compact('msg'));	
		}


		try {
			$table_log = new res_log;

			$table_log->res_po = $input_po;
			$table_log->item = $input_item;
			$table_log->variant = $input_variant;
			$table_log->batch = $input_batch;
			$table_log->res_hus = count($input_checked);

			$table_log->res_qty = floatval(round($qty, 2));

			$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$input_po."' "));
			if (isset($po_status[0]->status)) {
				$table_log->po_status = $po_status[0]->status;
			} else {
				$table_log->po_status = NULL;
			}
						
			$table_log->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save in log table";
			return view('reservations.errorm', compact('msg'));
		}

		for ($i=0; $i < count($input_checked); $i++) { 
			// dd($input_checked[$i]);

			$table = Reservation::findOrFail($input_checked[$i]);
			
			try {
				
				// $table->hu = $temp_table_hu[$i]->hu;
				// $table->father_hu = $temp_table_hu[$i]->father_hu;
				// $table->item = $temp_table_hu[$i]->item;
				// $table->variant = $temp_table_hu[$i]->variant;
				// $table->status = $temp_table_hu[$i]->status;
				// $table->balance = $temp_table_hu[$i]->balance;
				// $table->batch = $temp_table_hu[$i]->batch;
				// $table->document = $temp_table_hu[$i]->document;
				// $table->bin = $temp_table_hu[$i]->bin;
				// $table->location = $temp_table_hu[$i]->location;

				$table->res_po = $input_po;
				$table->res_log_id = $table_log->id;
				$table->res_date = date("Y-m-d H:i:s");
				$table->res_status = 'YES';
							
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');			
			}
			
		}

		return Redirect::to('reservation/');
	}

	public function reserv_cancel(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];	

		return view('reservations.reserv_cancel', compact('input_item','input_variant','input_batch'));
	}

	public function cancel_all(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		// dd($input_batch);

		$list = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(r.balance) as bal,
					 r.res_po as po,
					 (SELECT COUNT(hu) as hu FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = r.res_po) as hus
			 FROM [cutting].[dbo].[reservations] as r where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' GROUP BY res_po"));
		// dd($list);

		for ($i=0; $i < count($list); $i++) { 
			
			if ($list[$i]->bal > 0) {
			
				$table_log = new res_log;

				try {
					
					$table_log->res_po = $list[$i]->po;
					$table_log->item = $input_item;
					$table_log->variant = $input_variant;
					$table_log->batch = $input_batch;
					$table_log->res_hus = $list[$i]->hus;

					$table_log->res_qty = floatval(round($list[$i]->bal*(-1), 2));

					$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$list[$i]->po."' "));
					if (isset($po_status[0]->status)) {
						$table_log->po_status = $po_status[0]->status;
					} else {
						$table_log->po_status = NULL;
					}
								
					$table_log->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to save in log table";
					return view('reservations.errorm', compact('msg'));
				}		

			} else {
				$msg = "There is no hu with status = open , reserved = yes";
				return view('reservations.errorm', compact('msg'));
			}
		}

		$list = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' "));
		// dd($list);

		for ($i=0; $i < count($list); $i++) { 
					
			$table = Reservation::findOrFail($list[$i]->id);
			
			try {
				
				// $table->hu = $temp_table_hu[$i]->hu;
				// $table->father_hu = $temp_table_hu[$i]->father_hu;
				// $table->item = $temp_table_hu[$i]->item;
				// $table->variant = $temp_table_hu[$i]->variant;
				// $table->status = $temp_table_hu[$i]->status;
				// $table->balance = $temp_table_hu[$i]->balance;
				// $table->batch = $temp_table_hu[$i]->batch;
				// $table->document = $temp_table_hu[$i]->document;
				// $table->bin = $temp_table_hu[$i]->bin;
				// $table->location = $temp_table_hu[$i]->location;

				$table->res_po = NULL;
				$table->res_log_id = NULL;
				$table->res_date = NULL;
				$table->res_status = 'NO';
							
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');			
			}
		}
		return Redirect::to('reservation/');
	}

	public function cancel_po_imput(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT(res_po) as po,SUM(balance) as qty FROM reservations where status = 'Open' and res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' group by res_po order by res_po desc "));
		// dd($po_list);
		
		return view('reservations.cancel_po', compact('input_item', 'input_variant', 'input_batch', 'data'));
	}

	public function cancel_po(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		if (isset($input['checked'])) {
			// $input_checked[] = $input['checked'];
			// $po = $input_checked[0];

			// dd($input['checked']);

			for ($i=0; $i < count($input['checked']) ; $i++) {
				
				$po = $input['checked'][$i];

				$list_po = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(r.balance) as bal,
						 r.res_po as po,
						 (SELECT COUNT(hu) as hu FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = r.res_po) as hus
						 FROM [cutting].[dbo].[reservations] as r where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = '".$po."' GROUP BY res_po"));
				
				for ($i=0; $i < count($list_po); $i++) { 
					
					if ($list_po[$i]->bal > 0) {
					
						$table_log = new res_log;

						try {
							
							$table_log->res_po = $list_po[$i]->po;
							$table_log->item = $input_item;
							$table_log->variant = $input_variant;
							$table_log->batch = $input_batch;
							$table_log->res_hus = $list_po[$i]->hus*(-1);

							$table_log->res_qty = floatval(round($list_po[$i]->bal*(-1), 2));

							$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$list_po[$i]->po."' "));
							if (isset($po_status[0]->status)) {
								$table_log->po_status = $po_status[0]->status;
							} else {
								$table_log->po_status = NULL;
							}
										
							$table_log->save();
						}
						catch (\Illuminate\Database\QueryException $e) {
							$msg = "Problem to save in log table";
							return view('reservations.errorm', compact('msg'));
						}		

					} else {
						$msg = "There is no hu with status = open , reserved = yes";
						return view('reservations.errorm', compact('msg'));
					}
				}

				$list_hu = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = '".$po."' "));
				// dd($list_hu);

				for ($i=0; $i < count($list_hu); $i++) { 
							
					$table = Reservation::findOrFail($list_hu[$i]->id);
					
					try {
						
						// $table->hu = $temp_table_hu[$i]->hu;
						// $table->father_hu = $temp_table_hu[$i]->father_hu;
						// $table->item = $temp_table_hu[$i]->item;
						// $table->variant = $temp_table_hu[$i]->variant;
						// $table->status = $temp_table_hu[$i]->status;
						// $table->balance = $temp_table_hu[$i]->balance;
						// $table->batch = $temp_table_hu[$i]->batch;
						// $table->document = $temp_table_hu[$i]->document;
						// $table->bin = $temp_table_hu[$i]->bin;
						// $table->location = $temp_table_hu[$i]->location;

						$table->res_po = NULL;
						$table->res_log_id = NULL;
						$table->res_date = NULL;
						$table->res_status = 'NO';
									
						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');			
					}
				}

			}

			// return view('reservations.reserv_by_hu_insert_po', compact('input_item', 'input_variant', 'input_batch', 'input_checked'));
		} else {
			// $input_checked[] = [];
			$msg = "You should select some po's if you want to cancel reservations";
			return view('reservations.errorm', compact('msg'));
		}

		return Redirect::to('reservation/');
	}

	public function cancel_hu_imput(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id, hu, res_po, balance FROM reservations where status = 'Open' and res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' order by res_po desc "));
		// dd($po_list);
		
		return view('reservations.cancel_hu', compact('input_item', 'input_variant', 'input_batch', 'data'));
	}

	public function cancel_hu(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		if (isset($input['checked'])) {
			// $input_checked[] = $input['checked'];
			// $po = $input_checked[0];

			// dd($input['checked']);

			for ($i=0; $i < count($input['checked']) ; $i++) {
				
				$hu = $input['checked'][$i];
				// dd($hu);
				// var_dump($hu." ". $i);

				$list_po = DB::connection('sqlsrv')->select(DB::raw("SELECT balance,res_po FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and hu = '".$hu."' "));
				
				// for ($i=0; $i < count($list_po); $i++) { 
					// if ($list_po[$i]->balance > 0) {
					
						$table_log = new res_log;

						try {
							
							$table_log->res_po = $list_po[0]->res_po;
							// $table_log->res_po = $list_po[$i]->res_po;
							// $table_log->res_po = "TEST";
							$table_log->item = $input_item;
							$table_log->variant = $input_variant;
							$table_log->batch = $input_batch;
							$table_log->res_hus = -1;

							$table_log->res_qty = floatval(round($list_po[0]->balance*(-1), 2));
							// $table_log->res_qty = floatval(round($list_po[$i]->balance*(-1), 2));

							$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$list_po[0]->res_po."' "));
							if (isset($po_status[0]->status)) {
								$table_log->po_status = $po_status[0]->status;
							} else {
								$table_log->po_status = NULL;
							}
										
							$table_log->save();
						}
						catch (\Illuminate\Database\QueryException $e) {
							$msg = "Problem to save in log table";
							return view('reservations.errorm', compact('msg'));
						}

					// } else {
						// $msg = "There is no hu with status = open , reserved = yes";
						// return view('reservations.errorm', compact('msg'));
					// }
				// }

				$list_hu = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and hu = '".$hu."' "));
				// dd($list_hu);

				// for ($i=0; $i < count($list_hu); $i++) { 
							
					// $table = Reservation::findOrFail($list_hu[$i]->id);
					$table = Reservation::findOrFail($list_hu[0]->id);
					
					try {
						
						// $table->hu = $temp_table_hu[$i]->hu;
						// $table->father_hu = $temp_table_hu[$i]->father_hu;
						// $table->item = $temp_table_hu[$i]->item;
						// $table->variant = $temp_table_hu[$i]->variant;
						// $table->status = $temp_table_hu[$i]->status;
						// $table->balance = $temp_table_hu[$i]->balance;
						// $table->batch = $temp_table_hu[$i]->batch;
						// $table->document = $temp_table_hu[$i]->document;
						// $table->bin = $temp_table_hu[$i]->bin;
						// $table->location = $temp_table_hu[$i]->location;

						$table->res_po = NULL;
						$table->res_log_id = NULL;
						$table->res_date = NULL;
						$table->res_status = 'NO';
									
						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');			
					}
				// }
			}
			// return view('reservations.reserv_by_hu_insert_po', compact('input_item', 'input_variant', 'input_batch', 'input_checked'));
			return view('reservations.index');

		} else {
			// $input_checked[] = [];
			$msg = "You should select some po's if you want to cancel reservations";
			return view('reservations.errorm', compact('msg'));
		}

		// return Redirect::to('reservation/');
	}

	public function unreserve_mat()
	{

		return view('reservations.unreserv_mat');
	}

	public function unreserv_mat_input(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];

		// dd($input_batch);

		$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			SUM(balance) as bal,
			COUNT(hu) as coun,
			/*
			(SELECT SUM(balance) FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as reserv_not,
			(SELECT COUNT(hu) FROM [cutting].[dbo].[reservations] where res_status = 'NO' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as coun_not,
			*/
			(SELECT SUM(balance) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as reserv_yes,
			(SELECT COUNT(hu) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as coun_yes,
			(SELECT SUM(balance) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as reserv_all,
			(SELECT COUNT(hu) FROM [cutting].[dbo].[reservations] where res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."') as coun_all
		  FROM [cutting].[dbo].[reservations]
		  where item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."'
		  group by item, variant, batch"));

		$reserved_mat = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(balance) as bal, COUNT(hu) as coun_po, res_po FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' group by res_po"));


		if (isset($reservation_table[0]->reserv_yes)) {

			if (isset($reservation_table[0]->bal)) {
				$bal = floatval(round($reservation_table[0]->bal, 2));
			}	else {
				$bal = 0;
			}

			if (isset($reservation_table[0]->coun)) {
				$coun = floatval(round($reservation_table[0]->coun, 2));
			}	else {
				$coun = 0;
			}
			/*
			if (isset($reservation_table[0]->reserv_not)) {
				$reserv_not = floatval(round($reservation_table[0]->reserv_not, 2));
			}	else {
				$reserv_not = 0;
			}

			if (isset($reservation_table[0]->coun_not)) {
				$coun_not = floatval(round($reservation_table[0]->coun_not, 2));
			}	else {
				$coun_not = 0;
			}
			*/
			if (isset($reservation_table[0]->reserv_yes)) {
				$reserv_yes = floatval(round($reservation_table[0]->reserv_yes, 2));
			}	else {
				$reserv_yes = 0;
			}

			if (isset($reservation_table[0]->coun_yes)) {
				$coun_yes = floatval(round($reservation_table[0]->coun_yes, 2));
			}	else {
				$coun_yes = 0;
			}

			if (isset($reservation_table[0]->reserv_all)) {
				$reserv_all = floatval(round($reservation_table[0]->reserv_all, 2));
			}	else {
				$reserv_all = 0;
			}

			if (isset($reservation_table[0]->coun_all)) {
				$coun_all = floatval(round($reservation_table[0]->coun_all, 2));
			}	else {
				$coun_all = 0;
			}

		}	else {
			$msg = "Combination of Item, Variant and Batch wasn't reserved";
			return view('reservations.errorm', compact('msg'));
		}

		// dd($coun_all);
		return view('reservations.unreserv_mat_select', compact('input_item','input_variant','input_batch','bal','coun'/*,'reserv_not','coun_not'*/,'reserv_yes','coun_yes','reserv_all','coun_all','reserved_mat'));
	}

	public function unreserve_po()
	{

		return view('reservations.unreserv_po');
	}

	public function unreserv_po_input(Request $request)
	{
		$this->validate($request, ['po'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_po = $input['po'];
		// dd($input_po);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			item, 
			variant, 
			batch,
			SUM(balance) as bal,
			COUNT(hu) as coun
		  FROM [cutting].[dbo].[reservations]
		  WHERE res_po = '".$input_po."'
	 	  GROUP BY item, variant, batch"));

		// dd($reservation_table);
		
		return view('reservations.unreserv_po_select', compact('data', 'input_po'));
	}

	public function unreserv_po_confirm1(Request $request)
	{
		// $this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required' ,'po'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		// $input_item = $input['item'];
		// $input_variant = $input['variant'];
		// $input_batch = $input['batch'];
		// $po = $input['po'];
		
		if (isset($input['checked'])) {
			$input_checked[] = $input['checked'];
			// dd(count($input_checked[0]));
			// var_dump("$input checked: ".$input['checked']);

			// $input_checked = $input_checked[0];
			// var_dump("count: ".count($input_checked[0]));

			// dd($input_checked[0]);
			$test = "";

			for ($i=0; $i < count($input_checked[0]) ; $i++) { 
				
				$input_string = $input_checked[0][$i];
				// dd($input_string);
				// dd("input_string: ".$input_string." for ".$i);

				$test = $test." input_string: ".$input_string." for ".$i;

				list($input_item, $input_variant, $input_batch, $po) = explode("&", $input_string);
				// dd($input_string);
				// dd($po);
				// var_dump($input_item." ".$input_variant." ".$input_batch." ".$po);

				$list_po = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(r.balance) as bal,
				 r.res_po as po,
				 (SELECT COUNT(hu) as hu FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = r.res_po) as hus
				 FROM [cutting].[dbo].[reservations] as r 
				 WHERE res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = '".$po."' GROUP BY res_po"));
				

				// var_dump(count($list_po));



				// for ($i=0; $i < count($list_po); $i++) { 
					
					// if ($list_po[$i]->bal > 0) {
					
						$table_log = new res_log;

						try {
							
							$table_log->res_po = $list_po[$i]->po;
							$table_log->item = $input_item;
							$table_log->variant = $input_variant;
							$table_log->batch = $input_batch;
							$table_log->res_hus = $list_po[$i]->hus*(-1);

							$table_log->res_qty = floatval(round($list_po[$i]->bal*(-1), 2));

							$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$list_po[$i]->po."' "));
							if (isset($po_status[0]->status)) {
								$table_log->po_status = $po_status[0]->status;
							} else {
								$table_log->po_status = NULL;
							}
										
							$table_log->save();
						}
						catch (\Illuminate\Database\QueryException $e) {
							$msg = "Problem to save in log table";
							return view('reservations.errorm', compact('msg'));
						}		

					// } else {
					// 	$msg = "There is no hu with status = open , reserved = yes";
					// 	return view('reservations.errorm', compact('msg'));
					// }

				// }

				$list_hu = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = '".$po."' "));
				// dd($list_hu);

				for ($i=0; $i < count($list_hu); $i++) { 
							
					$table = Reservation::findOrFail($list_hu[$i]->id);
					
					try {
						
						// $table->hu = $temp_table_hu[$i]->hu;
						// $table->father_hu = $temp_table_hu[$i]->father_hu;
						// $table->item = $temp_table_hu[$i]->item;
						// $table->variant = $temp_table_hu[$i]->variant;
						// $table->status = $temp_table_hu[$i]->status;
						// $table->balance = $temp_table_hu[$i]->balance;
						// $table->batch = $temp_table_hu[$i]->batch;
						// $table->document = $temp_table_hu[$i]->document;
						// $table->bin = $temp_table_hu[$i]->bin;
						// $table->location = $temp_table_hu[$i]->location;

						$table->res_po = NULL;
						$table->res_log_id = NULL;
						$table->res_date = NULL;
						$table->res_status = 'NO';
									
						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');			
					}
				}
				
			}
			

			// dd($test);

		} else {
			// $input_checked[] = [];
			$msg = "You should check some lines if you want to unreserve them";
			return view('reservations.errorm', compact('msg'));
		}
				
		// return Redirect::to('reservation/');
	}

	public function unreserv_po_confirm(Request $request)
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required' ,'po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		$po = $input['po'];
		// dd($input_po);
		
		$list_po = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(r.balance) as bal,
				 r.res_po as po,
				 (SELECT COUNT(hu) as hu FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = r.res_po) as hus
				 FROM [cutting].[dbo].[reservations] as r where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = '".$po."' GROUP BY res_po"));
		
		for ($i=0; $i < count($list_po); $i++) { 
			
			if ($list_po[$i]->bal > 0) {
			
				$table_log = new res_log;
				try {
					
					$table_log->res_po = $list_po[$i]->po;
					$table_log->item = $input_item;
					$table_log->variant = $input_variant;
					$table_log->batch = $input_batch;
					$table_log->res_hus = $list_po[$i]->hus*(-1);
					$table_log->res_qty = floatval(round($list_po[$i]->bal*(-1), 2));
					$po_status = DB::connection('sqlsrv')->select(DB::raw("SELECT status FROM pos where po = '".$list_po[$i]->po."' "));
					if (isset($po_status[0]->status)) {
						$table_log->po_status = $po_status[0]->status;
					} else {
						$table_log->po_status = NULL;
					}
								
					$table_log->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					$msg = "Problem to save in log table";
					return view('reservations.errorm', compact('msg'));
				}		
			} else {
				$msg = "There is no hu with status = open , reserved = yes";
				return view('reservations.errorm', compact('msg'));
			}
		}
		$list_hu = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[reservations] where res_status = 'YES' and status = 'Open' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' and res_po = '".$po."' "));
		// dd($list_hu);
		for ($i=0; $i < count($list_hu); $i++) { 
					
			$table = Reservation::findOrFail($list_hu[$i]->id);
			
			try {
				
				// $table->hu = $temp_table_hu[$i]->hu;
				// $table->father_hu = $temp_table_hu[$i]->father_hu;
				// $table->item = $temp_table_hu[$i]->item;
				// $table->variant = $temp_table_hu[$i]->variant;
				// $table->status = $temp_table_hu[$i]->status;
				// $table->balance = $temp_table_hu[$i]->balance;
				// $table->batch = $temp_table_hu[$i]->batch;
				// $table->document = $temp_table_hu[$i]->document;
				// $table->bin = $temp_table_hu[$i]->bin;
				// $table->location = $temp_table_hu[$i]->location;
				$table->res_po = NULL;
				$table->res_log_id = NULL;
				$table->res_date = NULL;
				$table->res_status = 'NO';
							
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');			
			}
		}
		
		return Redirect::to('reservation/');
	}
	
	public function reserv_table() 
	{
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			rz.item, 
			rz.variant, 
			rz.batch,
			--SUM(rz.balance) as bal,
			--COUNT(rz.hu) as coun,
			(SELECT SUM(balance) FROM [reservations]  where res_status = 'NO' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as reserv_not,
			(SELECT COUNT(hu) FROM [reservations]  where res_status = 'NO' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as coun_not,
			(SELECT SUM(balance) FROM [reservations] where res_status = 'YES' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as reserv_yes,
			(SELECT COUNT(hu) FROM [reservations] where res_status = 'YES' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as coun_yes,
			(SELECT SUM(balance) FROM [reservations] where res_status = 'YES' and item = rz.item and variant = rz.variant and batch = rz.batch) as reserv_all,
			(SELECT COUNT(hu) FROM [reservations] where res_status = 'YES' and item = rz.item and variant = rz.variant and batch = rz.batch) as coun_all
		FROM [reservations] as rz
		GROUP BY rz.item, rz.variant, rz.batch"));


		if (isset($data[0])) {
			
  			return view('reservations.reserv_table',compact('data'));

		} else {
			
			$msg = "Nothing in Reservation table";
			return view('reservations.errorm',compact('msg'));
		}
	}

	public function reserv_table_by_po(Request $request) 
	{
		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required']);
		$input = $request->all();

		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		// dd($input_batch);
	}

	public function reserv_table_filter() 
	{
		
		return view('reservations.reserv_table_filter');
	}

	public function reserv_filter(Request $request) 
	{	
		$input = $request->all();
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		// dd($input_batch);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			rz.item, 
			rz.variant, 
			rz.batch,
			--SUM(rz.balance) as bal,
			--COUNT(rz.hu) as coun,
			(SELECT SUM(balance) FROM [reservations]  where res_status = 'NO' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as reserv_not,
			(SELECT COUNT(hu) FROM [reservations]  where res_status = 'NO' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as coun_not,
			(SELECT SUM(balance) FROM [reservations] where res_status = 'YES' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as reserv_yes,
			(SELECT COUNT(hu) FROM [reservations] where res_status = 'YES' and status = 'Open' and item = rz.item and variant = rz.variant and batch = rz.batch) as coun_yes,
			(SELECT SUM(balance) FROM [reservations] where res_status = 'YES' and item = rz.item and variant = rz.variant and batch = rz.batch) as reserv_all,
			(SELECT COUNT(hu) FROM [reservations] where res_status = 'YES' and item = rz.item and variant = rz.variant and batch = rz.batch) as coun_all
		FROM [reservations] as rz
		WHERE (rz.item = case when '".$input_item."' <> '' then '".$input_item."' else rz.item end ) and
		(rz.variant = case when '".$input_variant."' <> '' then '".$input_variant."' else rz.variant end ) and
		(rz.batch = case when '".$input_batch."' <> '' then '".$input_batch."' else rz.batch end )
		GROUP BY rz.item, rz.variant, rz.batch
		"));


		if (isset($data[0])) {
			
  			return view('reservations.reserv_table',compact('data'));

		} else {
			
			$msg = "Nothing in Reservation table";
			return view('reservations.errorm',compact('msg'));
		}
	}

	public function cancel_reservation_for_closed_po() // Ne koristi se
	{	
		
	}

	public function reserv_by_po()
	{	

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM res_logs ORDER BY id"));	

		return view('reservations.reserv_by_po', compact('data'));
	}

}

