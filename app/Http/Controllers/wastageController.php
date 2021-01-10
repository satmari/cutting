<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\wastages_print;
use App\wastage;
use App\wastage_log;
use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class wastageController extends Controller {

	public function table() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] "));
		return view('Wastage.table', compact('data'));
	}

	public function index_cut() {
		//
		$skeda_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT [skeda] FROM [posummary].[dbo].[pro]"));
		// dd($skeda_data);
		return view('Wastage.index_cut',compact('skeda_data'));
	}

	public function req_wastage_cut(Request $request) {
		//
		$this->validate($request, ['skeda'=>'required', 'sap_bin'=>'required', 'qty'=>'required|min:1|max:9']);
		$forminput = $request->all();
		// dd($forminput);
		
		$skeda = $forminput['skeda'];
		$sap_bin = strtoupper($forminput['sap_bin']);
		$qty = (int)$forminput['qty'];

		if ($qty > 10) {
			dd("Maksimalna kolicina je 10 nalepnica, verovatno ste skenirali barkod umesto da ste uneli kolicinu. Pokusajte ponovo");
		}
		
		// dd($skeda);
		// dd($sap_bin);
		// dd($qty);

		if ($qty > 0) {
			
			for ($i=1; $i <= $qty ; $i++) { 
				
				try {
					$table = new wastage;

					$table->no = $i;
					$table->skeda = $skeda;
					$table->sap_bin = $sap_bin;
					$table->weight;
					$table->location;
										
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("greska pri snimanju u tabelu, zovi IT (1)");
				}

				try {
					$table = new wastages_print;

					$table->no = $i;
					$table->skeda = $skeda;
					$table->bin = $sap_bin;
					$table->printer = 'Krojacnica';
					$table->printed = 0;

					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("greska pri snimanju u tabelu, zovi IT (2)");
				}
			}
		}
		return Redirect::to('wastage_cut');
	}

	public function index_cut_mm() {
		//
		$pro_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT [pro] as pro FROM pro"));
		// dd($skeda_data);
		return view('Wastage.index_cut_mm',compact('pro_data'));
	}

	public function req_wastage_cut_mm(Request $request) {
		//
		$this->validate($request, ['pro'=>'required', 'qty'=>'required|min:1|max:9']);
		$forminput = $request->all();
		// dd($forminput);
		
		$pro = $forminput['pro'];
		$qty = (int)$forminput['qty'];
		
		// Find Skeda
		$pro_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT skeda  FROM pro WHERE pro = '".$pro."' "));
		// dd($pro_data[0]->skeda);

		if (!isset($pro_data[0]->skeda) OR ($pro_data[0]->skeda == '') ) {
			dd("Fali skeda u posummary tabeli, zovi Sonju da uradi update");
		}
		$skeda = $pro_data[0]->skeda;
		// dd($skeda);
		
		// Find Mini marker barcode
		$sap_bin_data = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(sap_bin) as c_mm FROM wastages WHERE sap_bin not like 'G%' "));
		// dd((int)$sap_bin_data[0]->c_mm);
		$mmnum = (int)$sap_bin_data[0]->c_mm;
		$mmnum = $mmnum + 1;
		$mmn = str_pad($mmnum, 8, '0', STR_PAD_LEFT);
		$mm = "MM".$mmn;

		// dd($mm);

		if ($qty > 0) {
			
			for ($i=1; $i <= $qty ; $i++) { 
				
				try {
					$table = new wastage;

					$table->no = $i;
					$table->skeda = $skeda;
					$table->sap_bin = $mm;
					$table->weight;
					$table->location;
										
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("greska pri snimanju u tabelu, zovi IT (1)");
				}

				try {
					$table = new wastages_print;

					$table->no = $i;
					$table->skeda = $skeda;
					$table->bin = $mm;
					$table->printer = 'Krojacnica';
					$table->printed = 0;

					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("greska pri snimanju u tabelu, zovi IT (2)");
				}
			}
		}
		return Redirect::to('wastage_cut_mm');
	}

	public function index_wh() {
		//
		return view('Wastage.index_wh');
	}

	public function wastage_wh_scan() {
		//
		return view('Wastage.index_wh_scan');
	}

	public function req_wastage_wh(Request $request) {
		//
		$this->validate($request, ['sap_bin'=>'required']);
		$forminput = $request->all();

		$sap_bin = strtoupper($forminput['sap_bin']);
		// dd($sap_bin);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE sap_bin = '".$sap_bin."' ORDER BY id asc "));
		// dd($data);

		if (!isset($data[0]->id)) {
			// dd('Za unesen SAP bin ne postoje infrmacije');
			$msg = 'Greska: Za unesen SAP bin ne postoje infrmacije, pokusaj ponovo.';
			return view('wastage.index_wh_scan', compact('msg'));

		}

		return view('wastage.insert', compact('data', 'sap_bin'));

	}

	public function req_wastage_wh_insert(Request $request) {

		// dd($request);
		// $this->validate($request, ['bin'=>'required']);
		$forminput = $request->all();
		$qty[] = $forminput['qty'];
		$ids[] = $forminput['id_stari'];
		$coment[] = $forminput['coment'];
		// dd($ids[0]);

		for ($i=0; $i < count($ids[0]); $i++) { 
			// var_dump($qty[$i]);
			// dd((float)$qty[$i]);
			// dd($qty[0][$i]);

			$qty_insert = round((float)$qty[0][$i],2);
			// $location_insert = $location[0][$i];
			$coment_insert = $coment[0][$i];
			// dd($qty_insert);
			// dd($ids[0][$i]);

			try {
				$table = wastage::findOrFail($ids[0][$i]);

				$table->weight = $qty_insert;
				$table->coment = $coment_insert;

				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd("greska pri snimanju u tabelu, zovi IT");
			}
		}

	return Redirect::to('wastage_wh');
	}

	public function move_sapbin_container() {

		return view('wastage.move_sapbin_container');
	}

	public function move_sapbin_container_post(Request $request) {

		$this->validate($request, ['sap_bin'=>'required']);
		$forminput = $request->all();
		$sap_bin = strtoupper($forminput['sap_bin']);

		$check_bin = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE sap_bin = '".$sap_bin."' "));
		if (!isset($check_bin[0]->id)) {
			// dd("SAP bin not exist in wastage table");
			$msg = 'Greska: Za unesen SAP bin ne postoje infrmacije, pokusaj ponovo.';
			return view('wastage.move_sapbin_container', compact('msg'));
		}

		$check_if_exist = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE skeda = '".$check_bin[0]->skeda."' "));
		if (isset($check_if_exist[0]->container)) {
			$l = '';
			for ($i=0; $i < count($check_if_exist); $i++) { 
				$l = $check_if_exist[$i]->container.' '.$l;
			}

			$msg = 'This SKEDA you have in: '.$l;
		}
		// dd($msg);

		return view('wastage.move_sapbin_container_1', compact('sap_bin','msg'));
	}

	public function move_sapbin_container_post_1(Request $request) {

		$this->validate($request, ['sap_bin'=>'required', 'container'=>'required']);
		$forminput = $request->all();
		$sap_bin = strtoupper($forminput['sap_bin']);
		$container = strtoupper($forminput['container']);
		// dd($sap_bin);
		// dd($container);

		$check_cont = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM wastage_bins WHERE container = '".$container."' "));
		if (!isset($check_cont[0]->id)) {
			// dd("Container in not inserted in Container table");
			$msg = 'Greska: Za unesen Container postoje infrmacije.';
			return view('wastage.move_sapbin_container', compact('msg'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE sap_bin = '".$sap_bin."' "));

		for ($i=0; $i < count($data); $i++) { 

			// dd($data);
			try {
				$table = wastage::findOrFail($data[$i]->id);

				$table->container = $container;
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd("greska pri snimanju u tabelu, zovi IT");
			}	
		}

		return Redirect::to('wastage_wh');
	}


	public function move_container_location() {

		return view('wastage.move_container_location');
	}

	public function move_container_location_post(Request $request) {

		$this->validate($request, ['container'=>'required']);
		$forminput = $request->all();
		$container = strtoupper($forminput['container']);

		$check_container = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM wastage_bins WHERE container = '".$container."' "));
		if (!isset($check_container[0]->id)) {
			// dd("Container not exist in Container table");
			$msg = 'Greska: Za unesen Container postoje infrmacije.';
			return view('wastage.move_container_location', compact('msg'));
		}

		return view('wastage.move_container_location_1', compact('container'));
	}

	public function move_container_location_post_1(Request $request) {

		$this->validate($request, ['location'=>'required', 'container'=>'required']);
		$forminput = $request->all();
		$location = strtoupper($forminput['location']);
		$container = strtoupper($forminput['container']);
		// dd($location);
		// dd($container);

		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM wastage_locations WHERE location = '".$location."' "));
		if (!isset($check_location[0]->id)) {
			// dd("Location in not inserted in Location table");
			$msg = 'Greska: Za unesen Location postoje infrmacije.';
			return view('wastage.move_container_location', compact('msg'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE container = '".$container."' "));

		for ($i=0; $i < count($data); $i++) { 

			// dd($data);
			try {
				$table = wastage::findOrFail($data[$i]->id);

				$table->location = $location;
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd("greska pri snimanju u tabelu, zovi IT");
			}	
		}

		return Redirect::to('wastage_wh');
	}

	public function wastage_remove_skeda() {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT distinct(skeda) FROM [wastages] "));
		// dd($data);
		return view('wastage.wastage_remove_skeda', compact('data'));

	}

	public function wastage_remove_skeda_post($id) {

		// dd($id);
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE skeda = '".$id."' "));
		// dd($data);

		for ($i=0; $i < count($data); $i++) { 

			// dd($data);

			try {
				$table = wastage::findOrFail($data[$i]->id);

				try {
					$table_log = new wastage_log;

					$table_log->no = $table->no;					
					$table_log->skeda = $table->skeda;					
					$table_log->sap_bin = $table->sap_bin;					
					$table_log->weight = $table->weight;					
					$table_log->coment = $table->coment;					
					$table_log->container_id = $table->container_id;					
					$table_log->container = $table->container;					
					$table_log->location_id = $table->location_id;					
					$table_log->location = $table->location;					

					$table_log->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("greska pri snimanju u tabelu, zovi IT");
				}

				$table->delete();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd("greska pri snimanju u tabelu, zovi IT");
			}
		}

		return Redirect::to('wastage_wh');
	}
}
