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

	public function hu_list()
	{
		//
		$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT  [HU No_] as hu,
		 [Item No_] as item, 
		 [Father HU No_] as father_hu,
		 [Variant Code] as variant, 
		 --[Status] as status, 
		 (CASE WHEN [Status] = '0' THEN 'Open' END) AS status,
		 [Balance] as balance, 
		 [Quantity] as qty, 
		 [Batch_Dye lot] as batch,
		 [Document No_] as document,
		 [Bin Code] as bin,
		 --[Location Barcode],
		 (SELECT TOP 1 [Cell Code] FROM [Gordon_LIVE].[dbo].[GORDON\$WMS Storage Location] WHERE [Location Barcode] = [Barcode No_]) as location
  		FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit]
  
  		WHERE [Status] = 0 and Balance <> 0 and [HU No_] not like 'NOT%' and [HU No_] <> ''
  		GROUP BY [HU No_], [Father HU No_] ,[Item No_], [Variant Code], [Status], [Balance], [Quantity], [Batch_Dye lot], [Document No_], [Bin Code], [Location Barcode]
  		ORDER BY [Item No_], [Variant Code]"));

		$count_hu = DB::connection('sqlsrv1')->select(DB::raw("SELECT COUNT([HU No_]) as count_hu
  		FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit]
   		WHERE Status = 0 and Balance <> 0 and [HU No_] not like 'NOT%' "));
		
		
		if (isset($count_hu[0])) {
			$count = $count_hu[0]->count_hu;
		} else {
			$count = "0";
		}

		// dd($data);
  		// return view('reservations.hu_list', compact('data', 'count'));
  		
  		if (isset($data[0])) {
			
  			// dd($data);
  			temp_table_hu::truncate();

			for ($i=0; $i < count($data); $i++) { 
							
				// dd($data[$i]->hu);
				// dd(floatval(round($data[$i]->balance,2)));

				try {
					$table = new temp_table_hu;

					$table->hu = $data[$i]->hu;
					$table->father_hu = $data[$i]->father_hu;
					$table->item = $data[$i]->item;
					$table->variant = $data[$i]->variant;
					$table->status = $data[$i]->status;
					$table->balance = floatval(round($data[$i]->balance,2));
					$table->batch = $data[$i]->batch;
					$table->document = $data[$i]->document;
					$table->bin = $data[$i]->bin;
					$table->location = $data[$i]->location;
					
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					return view('reservations.error');
				}
			}			

		} else {
			
			$msg = "No HU in HU table";
			return view('reservations.error',compact('msg'));
		}

		// return view('reservations.hu_list', compact('data', 'count'));
		return view('reservations.hu_imported', compact('count'));
		// return Redirect::to('/');
		
	}

	public function update_reservation_table()
	{

	// update temp table
		$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT  [HU No_] as hu,
		 [Item No_] as item, 
		 [Father HU No_] as father_hu,
		 [Variant Code] as variant, 
		 --[Status] as status, 
		 (CASE WHEN [Status] = '0' THEN 'Open' END) AS status,
		 [Balance] as balance, 
		 [Quantity] as qty, 
		 [Batch_Dye lot] as batch,
		 [Document No_] as document,
		 [Bin Code] as bin,
		 --[Location Barcode],
		 (SELECT TOP 1 [Cell Code] FROM [Gordon_LIVE].[dbo].[GORDON\$WMS Storage Location] WHERE [Location Barcode] = [Barcode No_]) as location
  		FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit]
  
  		WHERE [Status] = 0 and Balance <> 0 and [HU No_] not like 'NOT%' and [HU No_] <> ''
  		GROUP BY [HU No_], [Father HU No_] ,[Item No_], [Variant Code], [Status], [Balance], [Quantity], [Batch_Dye lot], [Document No_], [Bin Code], [Location Barcode]
  		ORDER BY [Item No_], [Variant Code]"));

		if (isset($data[0])) {
			
  			// dd($data);
  			temp_table_hu::truncate();

			for ($i=0; $i < count($data); $i++) { 
							
				// dd($data[$i]->hu);
				// dd(floatval(round($data[$i]->balance,2)));

				try {
					$table = new temp_table_hu;

					$table->hu = $data[$i]->hu;
					$table->father_hu = $data[$i]->father_hu;
					$table->item = $data[$i]->item;
					$table->variant = $data[$i]->variant;
					$table->status = $data[$i]->status;
					$table->balance = floatval(round($data[$i]->balance,2));
					$table->batch = $data[$i]->batch;
					$table->document = $data[$i]->document;
					$table->bin = $data[$i]->bin;
					$table->location = $data[$i]->location;
					
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					return view('reservations.error');
				}
			}			

		} else {
			
			$msg = "No HU in HU table";
			return view('reservations.error',compact('msg'));
		}


	// from temp table to reservation table
		$temp_table_hu = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_table_hus ORDER BY id"));
		// dd($temp_table_hu);
		// dd($temp_table_hu[0]->id);

		if (isset($temp_table_hu[0]->id)) {

			$update_count = 0;
			$add_count = 0;

			for ($i=0; $i < count($temp_table_hu) ; $i++) { 
				// sleep(1);

				$res_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE hu = '".$temp_table_hu[$i]->hu."' "));
				
				if (isset($res_table[0]->id)) {
					
					$table = Reservation::findOrFail($res_table[0]->id);
					
					try {
						
						// $table->hu = $temp_table_hu[$i]->hu;
						// $table->father_hu = $temp_table_hu[$i]->father_hu;
						// $table->item = $temp_table_hu[$i]->item;
						// $table->variant = $temp_table_hu[$i]->variant;
						$table->status = $temp_table_hu[$i]->status;
						$table->balance = $temp_table_hu[$i]->balance;
						$table->batch = $temp_table_hu[$i]->batch;
						$table->document = $temp_table_hu[$i]->document;
						$table->bin = $temp_table_hu[$i]->bin;
						$table->location = $temp_table_hu[$i]->location;						
									
						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');			
					}

					$update_count = $update_count + 1;

				} else {


					try {
						$table = new Reservation;
					
						$table->hu = $temp_table_hu[$i]->hu;
						$table->father_hu = $temp_table_hu[$i]->father_hu;
						$table->item = $temp_table_hu[$i]->item;
						$table->variant = $temp_table_hu[$i]->variant;
						$table->status = $temp_table_hu[$i]->status;
						$table->balance = $temp_table_hu[$i]->balance;
						$table->batch = $temp_table_hu[$i]->batch;
						$table->document = $temp_table_hu[$i]->document;
						$table->bin = $temp_table_hu[$i]->bin;
						$table->location = $temp_table_hu[$i]->location;
						
						$table->res_status = "NO";

						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');			
					}

					$add_count = $add_count + 1;
				}

			}

		}


	// from reservation table to temp table
		$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE status = 'Open' ORDER BY id"));
		// dd($reservation_table);

		if (isset($reservation_table[0]->id)) {

			$consumed_count = 0;

			for ($i=0; $i < count($reservation_table) ; $i++) {

				$temp_table = DB::connection('sqlsrv')->select(DB::raw("SELECT hu FROM temp_table_hus WHERE hu = '".$reservation_table[$i]->hu."' "));

				// dD($temp_table);


				if (isset($temp_table[0]->hu)) {

					// dd($temp_table[0]->hu);

				} else {

					// $reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations ORDER BY id"));

					$table = Reservation::findOrFail($reservation_table[$i]->id);
					
					try {
						
						// $table->hu = $temp_table_hu[$i]->hu;
						// $table->item = $temp_table_hu[$i]->item;
						// $table->variant = $temp_table_hu[$i]->variant;
						$table->status = "Consumed";
						// $table->balance = $temp_table_hu[$i]->balance;
						// $table->batch = $temp_table_hu[$i]->batch;
						// $table->document = $temp_table_hu[$i]->document;
						// $table->bin = $temp_table_hu[$i]->bin;
						// $table->location = $temp_table_hu[$i]->location;						
									
						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');
					}

					$consumed_count = $consumed_count + 1;
				}

			}

		}

		//check if partialy consumed (new) rolls should keep reservation from father
		if (isset($reservation_table[0]->id)) {

			$reserved_by_father = 0;

			for ($i=0; $i < count($reservation_table) ; $i++) {

				if ($reservation_table[$i]->hu != $reservation_table[$i]->father_hu) {
					// dd($reservation_table[$i]->hu);

					$res_table_father = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE res_status = 'YES' and hu = '".$reservation_table[$i]->father_hu."' "));
					// dd($res_table_father[0]);
					// var_dump($res_table_father);


					if (isset($res_table_father[0]->id)) {
						// dd($res_table_father);

							$table = Reservation::findOrFail($reservation_table[$i]->id);
							
							try {

								$table->res_po = $res_table_father[0]->res_po;
								$table->res_qty = $temp_table_hu[$i]->balance;
								$table->res_date = $res_table_father[0]->res_date;
								$table->res_status = 'YES';

								$table->save();
							}
							catch (\Illuminate\Database\QueryException $e) {
								return view('reservations.error');
							}

							$reserved_by_father = $reserved_by_father + 1;
					} 	
				}
			}
		}

		return view('reservations.hu_updated', compact('update_count','add_count','consumed_count','reserved_by_father'));
		// return Redirect::to('/');

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

		$reserved_mat = DB::connection('sqlsrv')->select(DB::raw("SELECT SUM(balance) as bal, COUNT(hu) as coun_po, res_po FROM [cutting].[dbo].[reservations] where res_status = 'YES' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' group by res_po"));


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

		$this->validate($request, ['item'=>'required', 'variant'=>'required', 'batch'=>'required', 'po'=> 'required|min:6|max:6']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$input_item = $input['item'];
		$input_variant = $input['variant'];
		$input_batch = $input['batch'];
		$input_po = $input['po'];
		// dd($input_po);

		$list = DB::connection('sqlsrv')->select(DB::raw("SELECT id,balance FROM [cutting].[dbo].[reservations] where res_status = 'NO' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' "));
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
				$table->res_qty = $list[$i]->balance;
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

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[reservations] where res_status = 'NO' and item = '".$input_item."' and variant = '".$input_variant."' and batch = '".$input_batch."' "));
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
				$table->res_qty = $table->balance;
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
		// dd($input_batch);

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
				$table->res_qty = NULL;
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

}

