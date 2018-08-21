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

	public function update_reservation_table_old() // ne koristi se
	{

		// update temp table
		$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT  u.[HU No_] as hu,
		 u.[Item No_] as item, 
		 u.[Father HU No_] as father_hu,
		 u.[Variant Code] as variant, 
		 --[Status] as status, 
		 (CASE WHEN u.[Status] = '0' THEN 'Open' END) AS status,
		 u.[Balance] as balance, 
		 u.[Quantity] as qty, 
		 u.[Batch_Dye lot] as batch,
		 u.[Document No_] as document,
		 u.[Bin Code] as bin,
		 --[Location Barcode],
		 (SELECT TOP 1 temp.[Quantity] FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit] as temp WHERE temp.[HU No_] = u.[HU No_] ORDER BY temp.[Entry No_] asc) as original_qty,
		 (SELECT TOP 1 [Cell Code] FROM [Gordon_LIVE].[dbo].[GORDON\$WMS Storage Location] WHERE [Location Barcode] = [Barcode No_]) as location
  		FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit] as u
  
  		WHERE u.[Status] = 0 and u.Balance <> 0 and u.[HU No_] not like 'NOT%' and u.[HU No_] <> ''
  		GROUP BY u.[HU No_], u.[Father HU No_] ,u.[Item No_], u.[Variant Code], u.[Status], u.[Balance], u.[Quantity], u.[Batch_Dye lot], u.[Document No_], u.[Bin Code], u.[Location Barcode]
  		ORDER BY u.[Item No_], u.[Variant Code]"));

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

					$table->original_qty = floatval(round($data[$i]->original_qty,2));
					
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
						$table->father_hu = $temp_table_hu[$i]->father_hu;
						$table->item = $temp_table_hu[$i]->item;
						$table->variant = $temp_table_hu[$i]->variant;
						$table->status = $temp_table_hu[$i]->status;
						$table->balance = $temp_table_hu[$i]->balance;
						$table->batch = $temp_table_hu[$i]->batch;
						$table->document = $temp_table_hu[$i]->document;
						$table->bin = $temp_table_hu[$i]->bin;
						$table->location = $temp_table_hu[$i]->location;	

						$table->original_qty = floatval(round($temp_table_hu[$i]->original_qty,2));
									
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

						$table->original_qty = floatval(round($temp_table_hu[$i]->original_qty,2));

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

						// $table->original_qty = floatval(round($temp_table_hu[$i]->original_qty,2));
									
						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						return view('reservations.error');
					}

					$consumed_count = $consumed_count + 1;
				}

			}

		}


		$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE status = 'Open' and hu != father_hu ORDER BY id"));
		
		//check if partialy consumed (new) rolls should keep reservation from father
		if (isset($reservation_table[0]->id)) {

			$reserved_by_father = 0;

			for ($i=0; $i < count($reservation_table) ; $i++) {

				if ($reservation_table[$i]->hu != $reservation_table[$i]->father_hu) {
					// dd($reservation_table[$i]->hu);

					$res_table_father = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE res_status = 'YES' and hu = '".$reservation_table[$i]->father_hu."' "));
					// dd($res_table_father[0]);
					
					if (isset($res_table_father[0]->id)) {
						// dd($res_table_father);

							// var_dump($res_table_father);
							// var_dump($reservation_table);
							// dd("stop");

							
							$table = Reservation::findOrFail($reservation_table[$i]->id);
							
							// dd($table);
							// dd("0hu: ".$reservation_table[$i]->hu." , 0fhu: ".$reservation_table[$i]->father_hu." ,1hu: ".$table->hu." , 1fhu: ".$table->father_hu." ,father hu: ".$res_table_father[0]->hu." , father fhu: ".$res_table_father[0]->hu);

							try {

								$table->res_po = $res_table_father[0]->res_po;
								$table->res_qty = $reservation_table[$i]->balance;
								$table->res_date = $res_table_father[0]->res_date;
								$table->res_status = 'YES';

								$table->save();
							}
							catch (\Illuminate\Database\QueryException $e) {
								return view('reservations.error');
							}

							

							// Update Father HU reserved qty if hu was splited
							// $father = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE res_status = 'YES' and hu = '".$reservation_table[$i]->father_hu."' "));
							$table_f = Reservation::findOrFail($res_table_father[0]->id);

							// dd($table_f);
										
							try {

								// $table_f->res_po = $res_table_father[0]->res_po;
								$table_f->res_qty = $res_table_father[0]->res_qty - $reservation_table[$i]->balance;
								// $table_f->res_date = $res_table_father[0]->res_date;
								// $table_f->res_status = 'YES';

								// $table_f->save();
							}
							catch (\Illuminate\Database\QueryException $e) {
								return view('reservations.error');
							}
			
							

							$reserved_by_father = $reserved_by_father + 1;
					} 	
				}
			}
		}

		$unreserved_hu = 0; 
		$unreserved_mt = 0; 


		return view('reservations.hu_updated', compact('update_count','add_count','consumed_count','reserved_by_father', 'unreserved_hu', 'unreserved_mt'));
		// return Redirect::to('/');
	}

	public function update_reservation_table()
	{
		// update temp table
		$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT  u.[HU No_] as hu,
		 u.[Item No_] as item, 
		 u.[Father HU No_] as father_hu,
		 u.[Variant Code] as variant, 
		 --[Status] as status, 
		 (CASE WHEN u.[Status] = '0' THEN 'Open' END) AS status,
		 u.[Balance] as balance, 
		 u.[Quantity] as qty, 
		 u.[Batch_Dye lot] as batch,
		 u.[Document No_] as document,
		 u.[Bin Code] as bin,
		 --[Location Barcode],
		 (SELECT TOP 1 temp.[Quantity] FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit] as temp WHERE temp.[HU No_] = u.[HU No_] ORDER BY temp.[Entry No_] asc) as original_qty,
		 (SELECT TOP 1 [Cell Code] FROM [Gordon_LIVE].[dbo].[GORDON\$WMS Storage Location] WHERE [Location Barcode] = [Barcode No_]) as location
  		FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit] as u
  
  		WHERE u.[Status] = 0 and u.Balance <> 0 and u.[HU No_] not like 'NOT%' and u.[HU No_] <> ''
  		GROUP BY u.[HU No_], u.[Father HU No_] ,u.[Item No_], u.[Variant Code], u.[Status], u.[Balance], u.[Quantity], u.[Batch_Dye lot], u.[Document No_], u.[Bin Code], u.[Location Barcode]
  		ORDER BY u.[Item No_], u.[Variant Code]"));

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

					$table->original_qty = floatval(round($data[$i]->original_qty,2));
					
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
		//

		//Update and consume hus
		$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations ORDER BY id"));

		$update_count = 0;
		$consumed_count = 0;

		if (isset($reservation_table[0]->id)) {
			
			for ($i=0; $i < count($reservation_table) ; $i++) { 
				
				if ($reservation_table[$i]->status == "Open") {
				
					$temp_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_table_hus WHERE hu = '".$reservation_table[$i]->hu."' "));

					if (isset($temp_table[0]->id)) {

						$table = Reservation::findOrFail($reservation_table[$i]->id);
						
						try {
							
							// $table->hu = $temp_table_hu[$i]->hu;
							$table->father_hu = $temp_table[0]->father_hu;
							$table->item = $temp_table[0]->item;
							$table->variant = $temp_table[0]->variant;
							$table->status = $temp_table[0]->status;
							$table->balance = $temp_table[0]->balance;
							$table->batch = $temp_table[0]->batch;
							$table->document = $temp_table[0]->document;
							$table->bin = $temp_table[0]->bin;
							$table->location = $temp_table[0]->location;	

							// $table->original_qty = floatval(round($temp_table[0]->original_qty,2));
										
							$table->save();

							$update_count = $update_count + 1;
						}
						catch (\Illuminate\Database\QueryException $e) {
							$msg = "Error: Update hu, update";
							return view('reservations.errorm',compact('msg'));
						}

					} else {

						$table = Reservation::findOrFail($reservation_table[$i]->id);
						
						try {
							
							// $table->hu = $temp_table_hu[$i]->hu;
							// $table->father_hu = $temp_table[0]->father_hu;
							// $table->item = $temp_table[0]->item;
							// $table->variant = $temp_table[0]->variant;
							$table->status = "Consumed";
							$table->balance = 0;
							// $table->batch = $temp_table[0]->batch;
							// $table->document = $temp_table[0]->document;
							// $table->bin = $temp_table[0]->bin;
							// $table->location = $temp_table[0]->location;	

							// $table->original_qty = floatval(round($temp_table[0]->original_qty,2));
										
							$table->save();
							
							$consumed_count = $consumed_count + 1;

						}
						catch (\Illuminate\Database\QueryException $e) {
							$msg = "Error: Update hu, consumed";
							return view('reservations.errorm',compact('msg'));
						}
					}
				}
			} 
		}


		//Add new hus
		$temp_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM temp_table_hus ORDER BY id"));

		$add_count = 0;
		$reserved_by_father = 0;

		if (isset($temp_table[0]->id)) {

			for ($i=0; $i < count($temp_table) ; $i++) {
			
				$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE hu = '".$temp_table[$i]->hu."' "));

				if (isset($reservation_table[0]->id)) {			
					// exist, do noting

				} else {

					try {
						$table = new Reservation;
					
						$table->hu = $temp_table[$i]->hu;
						$table->father_hu = $temp_table[$i]->father_hu;
						$table->item = $temp_table[$i]->item;
						$table->variant = $temp_table[$i]->variant;
						$table->status = $temp_table[$i]->status;
						$table->balance = $temp_table[$i]->balance;
						$table->batch = $temp_table[$i]->batch;
						$table->document = $temp_table[$i]->document;
						$table->bin = $temp_table[$i]->bin;
						$table->location = $temp_table[$i]->location;
						
						// $table->original_qty = floatval(round($temp_table[$i]->original_qty,2));

						$table->res_status = "NO";

						$table->save();

						$add_count = $add_count + 1;

					}
					catch (\Illuminate\Database\QueryException $e) {
						$msg = "Error: Add new";
						return view('reservations.errorm',compact('msg'));
					}

					// check if is father?
					
					if ($temp_table[$i]->hu == $temp_table[$i]->father_hu) {
						// original hu, do nothing
					} else {


						$reservation_table_father = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations WHERE hu = '".$temp_table[$i]->father_hu."' "));					

						if (isset($reservation_table_father[0]->id)) {
							// father found

							if ($reservation_table_father[0]->status == "Consumed") {
								// fatehr was partialy consumed, reserve son on same po from father

								if ($reservation_table_father[0]->res_status == 'YES') {
									
									try {

										$table->res_po = $reservation_table_father[0]->res_po;
										$table->res_log_id = $reservation_table_father[0]->res_log_id;
										$table->res_date = $reservation_table_father[0]->res_date;
										$table->res_status = "YES";

										$table->save();

										$reserved_by_father = $reserved_by_father + 1;
									}
									catch (\Illuminate\Database\QueryException $e) {
										$msg = "Error: Reserve from father";
										return view('reservations.errorm', compact('msg'));
									}

								}

							} else {
								// father hu was split
							}

						} else {
							// father not found, do nothing
						}
					}
				}
			}
		}

		$unreserved_hu = 0;
		$unreserved_mt = 0;

		return view('reservations.hu_updated', compact('update_count','add_count','consumed_count','reserved_by_father', 'unreserved_hu', 'unreserved_mt'));
		// return Redirect::to('/');
	}	

	public function update_reservation_table_oposite() // ne koristi se
	{
		$reservation_table = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM reservations ORDER BY id"));

		for ($i=0; $i < count($reservation_table) ; $i++) {

			$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT TOP 1 [Quantity] as original_qty FROM [Gordon_LIVE].[dbo].[GORDON\$Handling Unit] WHERE [HU No_] = '".$reservation_table[$i]->hu."' ORDER BY [Entry No_] asc"));

			// dd($data[0]->original_qty);

			$table = Reservation::findOrFail($reservation_table[$i]->id);
			try {

				// $table->original_qty = $data[0]->original_qty; 
				$table->original_qty = floatval(round($data[0]->original_qty,2));
				// $table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				return view('reservations.error');
			}

		}
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


	public function cancel_reservation_for_closed_po() 
	{	
		//cancel_reservation_for_closed_po

		// $open_po = DB::connection('sqlsrv1')->select(DB::raw("SELECT [No_],[Status]
		// 	FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order]
		// 	where Status = '3'"));
  		

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[reservations] where status = 'Open' and res_status = 'YES' "));

		$unreserved_hu = 0;
		$unreserved_mt = 0;

		for ($i=0; $i < count($data) ; $i++) { 
				
			// dd($data[$i]->res_po);

			$open_po = DB::connection('sqlsrv1')->select(DB::raw("SELECT [No_] as no,[Status] as status
			FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order]
			where Status = '3' and [No_] like '%".$data[$i]->res_po."' "));

			// dd($open_po);
			
			if (isset($open_po[0])) {
				// var_dump($open_po[0]->no);

				$table = Reservation::findOrFail($data[$i]->id);
			
				$unreserved_hu = $unreserved_hu + 1;
				$unreserved_mt = $unreserved_mt + $table->balance;

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

		$update_count = 0;
		$add_count = 0;
		$consumed_count = 0;
		$reserved_by_father = 0;

		return view('reservations.hu_updated', compact('update_count','add_count','consumed_count','reserved_by_father','unreserved_hu','unreserved_mt'));

	}

	public function reserv_by_po() {

		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM res_logs ORDER BY id"));	

		return view('reservations.reserv_by_po', compact('data'));

	}

}

