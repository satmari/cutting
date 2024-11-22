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
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *
			,(SELECT TOP 1 tpp_shipments FROM [posummary].[dbo].[pro] WHERE skeda = [cutting].[dbo].[wastages].skeda) as tpp_shipment
			,(SELECT TOP 1 approval FROM [posummary].[dbo].[pro] WHERE skeda = [cutting].[dbo].[wastages].skeda) as approval  
		FROM [wastages] "));

		return view('Wastage.table', compact('data'));
	}

	public function table_all() {
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT [no]
		      ,[skeda]
		      ,[sap_bin]
		      ,[container]
		      ,[location]
		      ,[material]
		      ,[weight]
		      ,[coment]
		      ,'EXIST' as status
		FROM [wastages]

		UNION

		SELECT [no]
		      ,[skeda]
		      ,[sap_bin]
		      ,[container]
		      ,[location]
		      ,[material]
		      ,[weight]
		      ,[coment]
			  ,'REMOVED' as status
		FROM [wastage_logs]	"));
		// dd($data);

		return view('Wastage.table_all', compact('data'));
	}

	public function index_cut() {
		//
		$skeda_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT [skeda] FROM [posummary].[dbo].[pro]"));
		// $material_data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT tpp_material FROM [cutting].[dbo].[tpp_materials]"));
		// dd($skeda_data);
		// dd($material_data);

		return view('Wastage.index_cut',compact('skeda_data'/*,'material_data'*/));
	}

	public function req_wastage_c(Request $request) {

		$this->validate($request, ['skeda'=>'required']);
		$forminput = $request->all();
		// dd($forminput);
		
		$skeda = $forminput['skeda'];
		// dd($skeda);

		$material_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT 
  		    DISTINCT c.material as tpp_material
      		FROM [posummary].[dbo].[pro] as p
  			LEFT JOIN [trebovanje].[dbo].[sap_coois] as c ON c.po = p.pro --and (c.wc = 'WC02A' or c.wc = 'WC02M')
  			JOIN [cutting].[dbo].[tpp_materials] as t ON t.tpp_material = c.material
  			WHERE  p.skeda = '".$skeda."' "));
		// dd($material_data);

		$material_data_tpp = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT tpp_material FROM [cutting].[dbo].[tpp_materials]"));

		return view('Wastage.index_c',compact('material_data','material_data_tpp','skeda'));
	}

	public function req_wastage_cut(Request $request) {
		//
		$this->validate($request, ['skeda'=>'required', 'sap_bin'=>'required', 'qty'=>'required|min:1|max:9', 'material'=>'required']);
		$forminput = $request->all();
		// dd($forminput);
		
		$skeda = $forminput['skeda'];
		$sap_bin = strtoupper($forminput['sap_bin']);
		$qty = (int)$forminput['qty'];
		$material = $forminput['material'];

		if ($qty > 10) {
			dd("Maksimalna kolicina je 10 nalepnica, verovatno ste skenirali barkod umesto da ste uneli kolicinu. Pokusajte ponovo");
		}

		if (strlen($sap_bin) != 10) {
			dd("Krojni nalog odnosno SAP bin mora imati 10 karaktera");
		}
		
		$mattress_exist = DB::connection('sqlsrv')->select(DB::raw("SELECT [skeda], [sap_bin] FROM [wastages] WHERE skeda = '".$skeda."' AND  sap_bin = '".$sap_bin."' "));
		
		// dd($skeda);
		// dd($sap_bin);
		// dd($qty);
		// $start = count($mattress_exist) + 1 ;
		// dd($start);

		if ($qty > 0) {
			
			for ($i=1; $i <= $qty ; $i++) { 
				
				try {
					$table = new wastage;

					$table->no = $i + count($mattress_exist);
					$table->skeda = $skeda;
					$table->sap_bin = $sap_bin;
					$table->weight;
					$table->location;
					$table->material = $material;
										
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("greska pri snimanju u tabelu, zovi IT (1)");
				}

				try {
					$table = new wastages_print;

					$table->no = $i + count($mattress_exist);
					$table->skeda = $skeda;
					$table->bin = $sap_bin;
					$table->printer = 'Krojacnica';
					$table->printed = 0;
					// $table->material = $material;

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

		$pro_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT DISTINCT [pro] as pro FROM pro"));
		// $material_data = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT tpp_material FROM [cutting].[dbo].[tpp_materials]"));
		// dd($skeda_data);
		return view('Wastage.index_cut_mm',compact('pro_data'/*,'material_data'*/));
	}

	public function req_wastage_c_mm(Request $request) {

		$this->validate($request, ['pro'=>'required']);
		$forminput = $request->all();
		// dd($forminput);
		
		$pro = $forminput['pro'];

		$material_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT 
  		    DISTINCT c.material as tpp_material
      		FROM [posummary].[dbo].[pro] as p
  			LEFT JOIN [trebovanje].[dbo].[sap_coois] as c ON c.po = p.pro --and (c.wc = 'WC02A' or c.wc = 'WC02M')
  			JOIN [cutting].[dbo].[tpp_materials] as t ON t.tpp_material = c.material
  			WHERE  p.pro = '".$pro."' "));
		// dd($material_data);

		$material_data_tpp = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT tpp_material FROM [cutting].[dbo].[tpp_materials]"));

		return view('Wastage.index_c_mm',compact('material_data','material_data_tpp','pro'));

	}

	public function req_wastage_cut_mm(Request $request) {
		//
		$this->validate($request, ['pro'=>'required', 'qty'=>'required|min:1|max:9', 'material'=>'required']);
		$forminput = $request->all();
		// dd($forminput);
		
		$pro = $forminput['pro'];
		$qty = (int)$forminput['qty'];
		$material = $forminput['material'];
		
		// Find Skeda
		$pro_data = DB::connection('sqlsrv6')->select(DB::raw("SELECT skeda  FROM pro WHERE pro = '".$pro."' "));
		// dd($pro_data[0]->skeda);

		if (!isset($pro_data[0]->skeda) OR ($pro_data[0]->skeda == '') ) {
			dd("Fali skeda u posummary tabeli, zovi Sonju da uradi update");
		}
		$skeda = $pro_data[0]->skeda;
		// dd($skeda);
		
		// Find Mini marker barcode
		// dd("zovi IT, radimo na ovome");
		// $sap_bin_data = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(sap_bin) as c_mm FROM wastages WHERE sap_bin not like 'G%' "));
		// $sap_bin_data = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 sap_bin as c_mm FROM wastages WHERE skeda = '".$skeda."' AND sap_bin like 'M%' ORDER BY sap_bin desc"));
		$sap_bin_data = DB::connection('sqlsrv')->select(DB::raw("SELECT sap_bin as c_mm FROM wastages WHERE skeda = '".$skeda."' AND sap_bin like 'M%' ORDER BY sap_bin desc"));
		// dd((int)substr($sap_bin_data[0]->c_mm,-6));
		// dd($sap_bin_data);
		
		if (isset($sap_bin_data[0]->c_mm)) {
			$mmnum = (int)substr($sap_bin_data[0]->c_mm,-7);
			// dd($mmnum);
			$mmnum = $mmnum + 0;
			$mmn = str_pad($mmnum, 8, '0', STR_PAD_LEFT);
			$mm = "MM".$mmn;

			$mattress_exist = DB::connection('sqlsrv')->select(DB::raw("SELECT [skeda], [sap_bin] FROM [wastages] WHERE skeda = '".$skeda."' AND  sap_bin = '".$mm."' "));

			if ($qty > 0) {
				for ($i=1; $i <= $qty ; $i++) { 
					
					try {
						$table = new wastage;
						$table->no = $i + count($mattress_exist);
						$table->skeda = $skeda;
						$table->sap_bin = $mm;
						$table->weight;
						$table->location;
						$table->material = $material;
						$table->save();

					}
					catch (\Illuminate\Database\QueryException $e) {
						dd("greska pri snimanju u tabelu, zovi IT (1)");
					}

					try {
						$table = new wastages_print;
						$table->no = $i + count($mattress_exist);
						$table->skeda = $skeda;
						$table->bin = $mm;
						$table->printer = 'Krojacnica';
						$table->printed = 0;
						// $table->material = $material;
						$table->save();

					}
					catch (\Illuminate\Database\QueryException $e) {
						dd("greska pri snimanju u tabelu, zovi IT (2)");
					}
				}
			}
			return Redirect::to('wastage_cut_mm');


		} else {

			$sap_bin_data = DB::connection('sqlsrv')->select(DB::raw("SELECT TOP 1 sap_bin as c_mm FROM wastages WHERE sap_bin like 'M%' ORDER BY sap_bin desc"));
			$mmnum = (int)substr($sap_bin_data[0]->c_mm,-7);
			// dd($mmnum);
			$mmnum = $mmnum + 1;
			$mmn = str_pad($mmnum, 8, '0', STR_PAD_LEFT);
			$mm = "MM".$mmn;

			if ($qty > 0) {
				for ($i=1; $i <= $qty ; $i++) { 
					
					try {
						$table = new wastage;
						$table->no = $i;
						$table->skeda = $skeda;
						$table->sap_bin = $mm;
						$table->weight;
						$table->location;
						$table->material = $material;
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
						// $table->material = $material;
						$table->save();

					}
					catch (\Illuminate\Database\QueryException $e) {
						dd("greska pri snimanju u tabelu, zovi IT (2)");
					}
				}
			}
			return Redirect::to('wastage_cut_mm');

			
		}
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

		$skeda = $data[0]->skeda;

		$check_skeda = DB::connection('sqlsrv6')->select(DB::raw("SELECT TOP 1 tpp_shipments, tpp_wastage FROM [pro] WHERE skeda = '".$skeda."' ORDER BY id asc "));
		// dd($check_skeda);

		if (isset($check_skeda[0]->tpp_shipments)) {
			$tpp_shipments = $check_skeda[0]->tpp_shipments;
			$tpp_wastage = $check_skeda[0]->tpp_wastage;

			if ($tpp_shipments == 'COMPLETE') {
				
				$msg = 'Greska: TPP ship. vrednost u PoSummary app je COMPLETED';
				return view('wastage.index_wh_scan', compact('msg'));
			} else {
				//go 

			}

		} else {

			$msg = 'Greska: Nema informacija za ovu skedu u PoSummary app';
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
					$table_log->material = $table->material;

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

	public function wastage_remove_skeda_partialy($skeda) {
		// dd($id);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [wastages] WHERE [skeda] = '".$skeda."' "));
		// dd($data);

		return view('wastage.wastage_remove_skeda_partialy', compact('data'));
	}

	public function wastage_remove_skeda_partialy_post(Request $request) {	

		// $this->validate($request, ['location'=>'required', 'container'=>'required']);
		$forminput = $request->all();
		// dd($forminput);

		if (!isset($forminput['bag'])) {
			$msg = 'Please select at least one bag';
			return view('wastage.wastage_remove_skeda_partialy', compact('msg'));
		}

		$bag = $forminput['bag'];
		// dd($bag);

		for ($i=0; $i < count($bag); $i++) { 
			// dd($bag[$i]);

			try {
				$table = wastage::findOrFail($bag[$i]);
				// dd($table);

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
					$table_log->material = $table->material;

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

	public function delete_wastage_line(Request $request) {	

		// $this->validate($request, ['location'=>'required', 'container'=>'required']);
		$forminput = $request->all();
		$id = $forminput['id'];

		// dd("Zlatko treba da potvrdi");
		// $table = wastage::findOrFail($id);
		// $table->delete();

		// return Redirect::to('/');
		return view('wastage.delete_wastage_line_c', compact('id'));
	}

	public function delete_wastage_line_g(Request $request) {	

		// $this->validate($request, ['location'=>'required', 'container'=>'required']);
		$forminput = $request->all();
		// dd($forminput);

		$id = $forminput['id'];
		$pass = $forminput['pass'];

		if ($pass == 'peckes') {

			$table = wastage::findOrFail($id);
			$table->delete();

			return Redirect::to('/');	

		} else {
			dd("Password is not correct!!!");
		}
	}

	public function wastage_edit($id) {	

		// dd($id);
		$wastage_line = wastage::findOrFail($id);
		// dd($table);
		return view('wastage.edit', compact('wastage_line'));
	}

	public function wastage_edit_post(Request $request) {	

		// $this->validate($request, ['location'=>'required', 'container'=>'required']);
		$forminput = $request->all();
		// dd($forminput);

		$id = $forminput['id'];
		$log_rep = $forminput['log_rep'];
		$weight = $forminput['weight'];
		// dd($id);

		$table = wastage::findOrFail($id);
		$table->log_rep = 	$log_rep;
		$table->weight = 	round((float)$weight,2);
		
		$table->save();
		
		return Redirect::to('wastage_table');
	}
	
}
