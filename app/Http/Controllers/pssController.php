<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\paspul_stock;
use App\paspul_stock_log;
use App\paspul_label_print;
use App\paspul_stock_by_key;
use App\paspul_stock_u_cons;

use DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class pssController extends Controller {

	public function index() {
		//
		// dd("Paspul Stock Subotica");

		//// verify userId
		if (Auth::check())
		{
		    $userId = Auth::user()->id;
		    $device = Auth::user()->name;
		} else {
			// dd('User is not autenticated');
			$msg ='User is not autenticated';
			return view('pss.error',compact('msg'));
		}
		// dd($operator1);
		// $location = substr($device, 0,3);
		$location = "PSS";
		// dd($location);
		$count_just_cut = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r FROM [cutting].[dbo].[paspul_stocks] as s
			WHERE s.[location] = 'JUST_CUT'  "));
		if (isset($count_just_cut[0]->r)) {
			$count_just_cut = $count_just_cut[0]->r;
		} else {
			$count_just_cut = 0;
		}

		$count_sub = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Subotica' and s.[location] != 'JUST_CUT' and s.[location] != 'READY_FOR_KIKINDA' and s.[location] != 'READY_FOR_SENTA' and s.[location] != 'READY_FOR_VALY' and s.[location] != 'RECEIVED_IN_SUBOTICA' "));
		if (isset($count_sub[0]->r)) {
			$count_sub = $count_sub[0]->r;
		} else {
			$count_sub = 0;
		}

		$count_received_in_subotica = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Subotica' and s.[location] = 'RECEIVED_IN_SUBOTICA' "));
		if (isset($count_received_in_subotica[0]->r)) {
			$count_received_in_subotica = $count_received_in_subotica[0]->r;
		} else {
			$count_received_in_subotica = 0;
		}

		$count_rki = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Subotica' and s.[location] = 'READY_FOR_KIKINDA' "));
		if (isset($count_rki[0]->r)) {
			$count_rki = $count_rki[0]->r;
		} else {
			$count_rki = 0;
		}

		$count_rse = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Subotica' and s.[location] = 'READY_FOR_SENTA' "));
		if (isset($count_rse[0]->r)) {
			$count_rse = $count_rse[0]->r;
		} else {
			$count_rse = 0;
		}

		$count_rva = DB::connection('sqlsrv')->select(DB::raw("SELECT COUNT(s.[id]) as r
			FROM [cutting].[dbo].[paspul_stocks] as s
			INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
			WHERE l.[plant] = 'Subotica' and s.[location] = 'READY_FOR_VALY' "));
		if (isset($count_rva[0]->r)) {
			$count_rva = $count_rva[0]->r;
		} else {
			$count_rva = 0;
		}

		return view('pss.index', compact('location','count_just_cut','count_sub','count_received_in_subotica','count_rki','count_rse','count_rva'));
	}

	public function paspul_table_just_cut () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			s.* ,
			k.[mtr_per_pcs] as [unit_cons],
			s.[fg_color_code] 
		FROM paspul_stocks as s 
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE s.[location] = 'JUST_CUT'
		"));
		// dd($data);
		return view('pss.table', compact('data'));
	}

	public function paspul_table_stock_su () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT 
			s.* ,
			k.[mtr_per_pcs] as [unit_cons],
			s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s	
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE 
			l.[plant] = 'Subotica' and
			s.[location] != 'JUST_CUT' and 
			s.[location] != 'READY_FOR_KIKINDA' and 
			s.[location] != 'READY_FOR_SENTA'  and 
			s.[location] != 'READY_FOR_VALY' and 
			s.[location] != 'RECEIVED_IN_SUBOTICA'  
		"));
		// dd($data);
		return view('pss.table', compact('data'));
	}

	public function paspul_table_received_in_subotica () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT  s.* ,
			k.[mtr_per_pcs] as [unit_cons],
			s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE	l.[plant] = 'Subotica' and 
				s.[location] = 'RECEIVED_IN_SUBOTICA'
		"));
		// dd($data);
		return view('pss.table', compact('data'));
	}

	public function paspul_table_stock_ready_ki () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT s.* ,
				k.[mtr_per_pcs] as [unit_cons],
				s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE	l.[plant] = 'Subotica' and
				s.[location] = 'READY_FOR_KIKINDA'
		"));
		// dd($data);
		return view('pss.table', compact('data'));
	}

	public function paspul_table_stock_ready_se () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT s.* ,
				k.[mtr_per_pcs] as [unit_cons],
				s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE   l.[plant] = 'Subotica' and 
				s.[location] = 'READY_FOR_SENTA'
		"));
		// dd($data);
		return view('pss.table', compact('data'));
	}

	public function paspul_table_stock_ready_va () {

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT s.* ,
				k.[mtr_per_pcs] as [unit_cons],
				s.[fg_color_code] 
		FROM [cutting].[dbo].[paspul_stocks] as s
		INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		--LEFT JOIN [cutting].[dbo].[paspul_stock_by_keys] as k ON k.[pas_key] = s.[pas_key]
		LEFT JOIN [cutting].[dbo].[paspul_stock_u_cons] as k ON k.skeda = s.skeda and k.paspul_type = s.paspul_type
		WHERE	l.[plant] = 'Subotica' and
				s.[location] = 'READY_FOR_VALY'
		"));
		// dd($data);
		return view('pss.table', compact('data'));
	}

	public function paspul_table_log ($tip) {

		if ($tip == 'WHSU') {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT s.* 
				FROM [cutting].[dbo].[paspul_stock_logs] as s
				INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location_from]
				WHERE s.[created_at] > DATEADD(day,-30,GETDATE())  AND l.[location] like '%READY%'
				ORDER BY s.[created_at] desc
			"));		

		} else {
			$data = DB::connection('sqlsrv')->select(DB::raw("SELECT s.* 
				FROM [cutting].[dbo].[paspul_stock_logs] as s
				INNER JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location_from]
				WHERE s.[created_at] > DATEADD(day,-30,GETDATE())  AND (l.[plant] = '".$tip."' OR l.location like '%".$tip."%'  OR s.location_from = 'EXCEL')
				ORDER BY s.[created_at] desc
			"));	
		}

		// dd($data);
		return view('pss.table_log', compact('data'));
	}

// CONS MTR PER PCS

	public function search_u_cons() {

		$skeda = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT skeda FROM paspul_stock_u_cons 
			ORDER BY skeda asc"));

		$paspul_type = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT paspul_type FROM paspul_stock_u_cons 
			ORDER BY paspul_type asc"));

		$style = DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT style FROM paspul_stock_u_cons 
			ORDER BY style asc"));

		return view('pss.table_u_cons_search', compact('skeda', 'paspul_type', 'style'));	
	}

	public function search_u_cons_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		if (isset($input['skeda'])) {
			$skeda = $input['skeda'];
		} else {
			$skeda = '';
		}

		if (isset($input['paspul_type'])) {
			$paspul_type = $input['paspul_type'];
		} else {
			$paspul_type = '';
		}

		if (isset($input['style'])) {
			$style = $input['style'];
		} else {
			$style = '';
		}
		
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *  FROM paspul_stock_u_cons 
			WHERE skeda like '%".$skeda."%' and paspul_type like '%".$paspul_type."%' and style like '%".$style."%'
			ORDER BY style asc"));
		// dd($data);

		return view('pss.table_u_cons', compact('data'));
	}

	public function table_u_cons_change($id) {
		// dd($id);
		$data = paspul_stock_u_cons::findOrFail($id);
		$mtr_per_pcs = $data->mtr_per_pcs;
		return view('pss.table_u_cons_post', compact('id','mtr_per_pcs'));
	}

	public function table_u_cons_change_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		$id = $input['id'];

		if (isset($input['mtr_per_pcs'])) {
			$mtr_per_pcs = round($input['mtr_per_pcs'],2);

			if ($mtr_per_pcs == 0) {
				$mtr_per_pcs = NULL;
			}
		} else {
			$mtr_per_pcs = NULL;
		}
		// dd($mtr_per_pcs);

		$data = paspul_stock_u_cons::findOrFail($input['id']);
		$data->mtr_per_pcs = $mtr_per_pcs;
		$data->save();

		// update paspul_stock and paspul_stock_log

		$data = DB::connection('sqlsrv')->update(DB::raw("
		
	  	update [paspul_stocks] 
		set [paspul_stocks].[pcs_kotur] = round([paspul_stocks].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stocks] 
		JOIN [paspul_stock_u_cons] ON [paspul_stocks].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stocks].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stocks].[uom] = 'meter'
		
		update [paspul_stocks] 
		set [paspul_stocks].[pcs_kotur] = round([paspul_stocks].[kotur_length] * [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stocks] 
		JOIN [paspul_stock_u_cons] ON [paspul_stocks].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stocks].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stocks].[uom] = 'ploce'"));

		$data = DB::connection('sqlsrv')->update(DB::raw("

	  	update [paspul_stock_logs] 
		set [paspul_stock_logs].[pcs_kotur] = round([paspul_stock_logs].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stock_logs] 
		JOIN [paspul_stock_u_cons] ON [paspul_stock_logs].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stock_logs].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stock_logs].[uom] = 'meter'
		
	  	update [paspul_stock_logs] 
		set [paspul_stock_logs].[pcs_kotur] = round([paspul_stock_logs].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stock_logs] 
		JOIN [paspul_stock_u_cons] ON [paspul_stock_logs].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stock_logs].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stock_logs].[uom] = 'ploce'"));

		//

		// return Redirect::to('/search_u_cons');
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *  FROM paspul_stock_u_cons 
			WHERE id = '".$input['id']."'
			ORDER BY style asc"));
		// dd($data);

		return view('pss.table_u_cons', compact('data'));
	}

	public function table_u_cons_add () {

		return view('pss.table_u_cons_add');
	}

	public function table_u_cons_add_post (Request $request) {
		//
		$this->validate($request, ['skeda'=>'required','paspul_type'=>'required','mtr_per_pcs'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$skeda = $input['skeda'];

		if (config('app.global_variable') == 'gordon') {
			if (strlen($skeda) != 14) {
				dd('Skeda must be 14 characters');
			}
		}		

		$paspul_type = $input['paspul_type'];
		$skeda_paspul_type = $skeda.'_'.$paspul_type;

		$mtr_per_pcs = round($input['mtr_per_pcs'],2);

		if ($mtr_per_pcs <= 0) {
			$msge = 'Consumption meter per pcs must be higher then 0';
			return view('pss.table_u_cons_add',compact('msge'));
		}

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *  FROM paspul_stock_u_cons 
			WHERE skeda_paspul_type = '".$skeda_paspul_type."' "));
		if (isset($data[0]->id)) {
			$msge = 'Consumption for this skeda and paspul type already exist';
			return view('pss.table_u_cons_add',compact('msge'));
		}


		$table_u_cons = new paspul_stock_u_cons;
		$table_u_cons->skeda_paspul_type = $skeda_paspul_type;
		$table_u_cons->skeda = $skeda;
		$table_u_cons->paspul_type = $paspul_type;

		$style_1 = substr($skeda, 0, 9);
		$style = rtrim($style_1, '0');
		$table_u_cons->style = $style;
		$table_u_cons->mtr_per_pcs = $mtr_per_pcs;

		$table_u_cons->save();

		// update paspul_stock and paspul_stock_log
		$data = DB::connection('sqlsrv')->update(DB::raw("
		
	  	update [paspul_stocks] 
		set [paspul_stocks].[pcs_kotur] = round([paspul_stocks].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stocks] 
		JOIN [paspul_stock_u_cons] ON [paspul_stocks].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stocks].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stocks].[uom] = 'meter'
		
		update [paspul_stocks] 
		set [paspul_stocks].[pcs_kotur] = round([paspul_stocks].[kotur_length] * [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stocks] 
		JOIN [paspul_stock_u_cons] ON [paspul_stocks].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stocks].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stocks].[uom] = 'ploce'"));

		$data = DB::connection('sqlsrv')->update(DB::raw("

	  	update [paspul_stock_logs] 
		set [paspul_stock_logs].[pcs_kotur] = round([paspul_stock_logs].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stock_logs] 
		JOIN [paspul_stock_u_cons] ON [paspul_stock_logs].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stock_logs].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stock_logs].[uom] = 'meter'
		
	  	update [paspul_stock_logs] 
		set [paspul_stock_logs].[pcs_kotur] = round([paspul_stock_logs].[kotur_length] / [paspul_stock_u_cons].[mtr_per_pcs],0,1)
		from [paspul_stock_logs] 
		JOIN [paspul_stock_u_cons] ON [paspul_stock_logs].[skeda] = [paspul_stock_u_cons].[skeda] AND [paspul_stock_logs].[paspul_type] = [paspul_stock_u_cons].[paspul_type]
		WHERE [paspul_stock_logs].[uom] = 'ploce'"));
		//


		// return Redirect::to('/search_u_cons');
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT *  FROM paspul_stock_u_cons 
			WHERE skeda = '".$skeda."'
			ORDER BY style asc"));
		// dd($data);

		return view('pss.table_u_cons', compact('data'));
	}

// PRINT LABEL
	public function print_paspul_label1 ($id) {
		//
		// dd($id);
		$pas_key_data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks]
			WHERE id = '".$id."' "));
		// dd($pas_key_data);
		if (!isset($pas_key_data[0]->pas_key)) {
			dd('paspul was already modified before this attempt');
		}

		$pas_key = $pas_key_data[0]->pas_key;
		$kotur_qty = $pas_key_data[0]->kotur_qty;
		$kotur_length = $pas_key_data[0]->kotur_length;
		$skeda = $pas_key_data[0]->skeda;
		$paspul_type = $pas_key_data[0]->paspul_type;
		$uom = $pas_key_data[0]->uom;

		$skeda_paspul_type = $skeda.'_'.$paspul_type;

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stock_u_cons]
			WHERE skeda_paspul_type = '".$skeda_paspul_type."' "));
		// dd($data);

		if (isset($data[0]->id)) {

			if ($data[0]->mtr_per_pcs != NULL) {

				if ($uom == 'meter') {
					$mtr_per_pcs = round($data[0]->mtr_per_pcs,2);
					$pcs_kotur = floor(round($kotur_length,2) / $mtr_per_pcs);
					$fg_qty = $pcs_kotur * $kotur_qty;	

				} elseif ($uom == 'ploce') {
					
					$mtr_per_pcs = round($data[0]->mtr_per_pcs,2);
					$pcs_kotur = floor(round($kotur_length,2) * $mtr_per_pcs);
					$fg_qty = $pcs_kotur * $kotur_qty;	

				} else {
					$mtr_per_pcs = 'Wrong UoM';
					$pcs_kotur = 'Wrong UoM';
					$fg_qty = 'Wrong UoM';
				}

				

			} else {
				$mtr_per_pcs = 'Missing';
				$pcs_kotur = 'Missing';
				$fg_qty = 'Missing';

				$msge2 = 'Nedostaje vrednost u tabeli paspul consumption za skedu '.$skeda.' i paspul tip '.$paspul_type.' , pa stampanje nalepnice nije moguce.';
				return view('pss.print_paspul_label1', compact('id','skeda','paspul_type','kotur_length','mtr_per_pcs','pcs_kotur','fg_qty','msge2'));
			}

		} else {
			$mtr_per_pcs = 'Missing';
			$pcs_kotur = 'Missing';
			$fg_qty = 'Missing';

			$msge2 = 'Nedostaje vrednost u tabeli paspul consumption za skedu '.$skeda.' i paspul tip '.$paspul_type.' , pa stampanje nalepnice nije moguce.';
			return view('pss.print_paspul_label1', compact('id','skeda','paspul_type','kotur_length','mtr_per_pcs','pcs_kotur','fg_qty','msge2'));
		}

		return view('pss.print_paspul_label1', compact('id','skeda','paspul_type','kotur_length','mtr_per_pcs','pcs_kotur','fg_qty'));
	}

	public function print_paspul_label1_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )


		$id = $input['id'];
		$kotur_length = $input['kotur_length'];
		$kotur_qty = $input['kotur_qty'];
		$printer = $input['printer'];
		$skeda = $input['skeda'];
		$paspul_type = $input['paspul_type'];

		$mtr_per_pcs = $input['mtr_per_pcs'];
		$pcs_kotur = $input['pcs_kotur'];

		// $fg_qty = $pcs_kotur * $kotur_qty;
		$fg_qty = $pcs_kotur;

		$qty = $input['qty']; // label qty

		if ((int)$kotur_qty <= 0) {
			$msge = 'Broj paspul kotura mora biti popunjen';
			// return view('pss.print_paspul_label', compact('id','msge'));
			return view('pss.print_paspul_label1', compact('id','skeda','paspul_type','kotur_length','mtr_per_pcs','pcs_kotur','fg_qty','msge'));
		}

		if ($fg_qty <= 0) {
			$msge = 'Broj gotovih komada ne moze biti 0';
			// return view('pss.print_paspul_label', compact('id','msge'));
			return view('pss.print_paspul_label1', compact('id','skeda','paspul_type','kotur_length','mtr_per_pcs','pcs_kotur','fg_qty','msge'));
		}
		// dd('stop');

		$pa_old = paspul_stock::findOrFail($id);

		$table_print = new paspul_label_print;
		$table_print->pas_key = $pa_old->pas_key;
		$table_print->pas_key_e = $pa_old->pas_key_e;

		$table_print->skeda = $pa_old->skeda;
		$table_print->paspul_type = $pa_old->paspul_type;
		$table_print->dye_lot = $pa_old->dye_lot;
		$table_print->kotur_length = round($pa_old->kotur_length,2);
		
		$table_print->kotur_qty = $kotur_qty;
		$table_print->kotur_width = (int)$pa_old->kotur_width;

		$table_print->uom = $pa_old->uom;
		$table_print->material = $pa_old->material;
		$table_print->fg_color_code = $pa_old->fg_color_code;

		$table_print->fg_qty = $fg_qty;
		$table_print->qty = (int)$qty;

		$table_print->printer = $printer;
	 $table_print->save();

		return Redirect::to('/');
	}

// LOC TO LOC 
	public function paspul_loc_to_loc_su_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE  l.[plant] = 'Subotica'  "));

		return view('pss.paspul_loc_to_loc_su_from', compact('from'));
	}

	public function paspul_loc_to_loc_su_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Subotica'  "));

		if (!empty($input['location1'])) {
			$location_from = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_from = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('pss.paspul_loc_to_loc_su_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Subotica' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('pss.paspul_loc_to_loc_su_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		$location_to = '';

		return view('pss.paspul_loc_to_loc_su_pas', compact('location_from','location_to', 'pas_keys'));
	}

	public function paspul_loc_to_loc_su_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = $input['location_from'];
		if (isset($input['location_to'])) {
			$location_to = $input['location_to'];
		} else {
			$location_to = '';
		}

		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('pss.paspul_loc_to_loc_su_pas', compact('location_from','location_to', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('pss.paspul_loc_to_loc_su_pas', compact('location_from','location_to','pas_keys', 'msge'));
		}
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		// dd($pas_one);

		return view('pss.paspul_loc_to_loc_su_qty', compact('location_from','location_to','pas_one', 'current_qty'));
	}

	public function paspul_loc_to_loc_su_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		if (isset($input['location_to'])) {
			$location_to = $input['location_to'];
		} else {
			$location_to = '';
		}
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('pss.paspul_loc_to_loc_su_qty', compact('location_from','location_to', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('pss.paspul_loc_to_loc_su_op', compact('location_from', 'location_to','pas_one', 'qty'));
	}

	public function paspul_loc_to_loc_su_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		if (isset($input['location_to'])) {
			$location_to = $input['location_to'];
		} else {
			$location_to = '';
		}
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('pss.paspul_loc_to_loc_su_op', compact('location_from','location_to','pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('pss.paspul_loc_to_loc_su_op', compact('location_from','location_to', 'pas_one', 'qty', 'msge'));

		}  else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('pss.paspul_loc_to_loc_su_op', compact('location_from','location_to', 'pas_one', 'qty', 'msge'));
			}
		}

		$op = $input['op1'];
		// dd($op);

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Subotica' and type = 'stock' and [location] != '".$location_from."' "));

		return view('pss.paspul_loc_to_loc_su_to', compact('location_from','location_to','pas_one', 'qty', 'op', 'to'));
	}

	public function paspul_loc_to_loc_su_to_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];

		if (isset($input['op1'])) {

			$location_to = $input['location_to'];

			if (empty($input['op1'])) {
				$msge = 'Please insert correct operator';
				return view('pss.paspul_loc_to_loc_su_op', compact('location_from','location_to', 'pas_one', 'qty', 'msge'));

			} elseif (substr($input['op1'],0,1) != "R" ) {
			
				$msge = 'Please insert correct operator';
				return view('pss.paspul_loc_to_loc_su_op', compact('location_from','location_to', 'pas_one', 'qty', 'msge'));		
			}

			$op = $input['op1'];
			

		} else {

			$op = $input['op'];

			$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location],[type] FROM [cutting].[dbo].[paspul_locations]
			WHERE [plant] = 'Subotica' and type = 'stock' "));
			
			if (!empty($input['location1'])) {
				$location_to = strtoupper($input['location1']);
			} elseif (!empty($input['location2'])) {
				$location_to = strtoupper($input['location2']);
			} else {
				$msge = 'Please scan or select location';
				return view('pss.paspul_loc_to_loc_su_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
			}
			// dd($location_to);

			// check location
			$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
			WHERE [location] = '".$location_to."' and [plant] = 'Subotica' and [type] = 'stock' "));

			if (!isset($check_location[0]->id)) {
				$msge = 'Location is not correct';
				return view('pss.paspul_loc_to_loc_su_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
			}
			// dd($location_to);
		}
		

		


		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);
		

		$pa_new = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_to."'  "));

		// FOR NEW check if exist or create
		if (isset($pa_new[0]->id)) {
			// alredy exist on location_to

			$pa1 = paspul_stock::findOrFail($pa_new[0]->id);
			$pa1->kotur_qty = $pa1->kotur_qty + $qty;
			$pa1->save();

		} else {
			// new line

			$table_stock = new paspul_stock;
			$table_stock->skeda = $pa_old->skeda;
			$table_stock->paspul_type = $pa_old->paspul_type;
			$table_stock->dye_lot = $pa_old->dye_lot;
			$table_stock->kotur_length = $pa_old->kotur_length;
			$table_stock->pas_key = $pa_old->pas_key;
			$table_stock->pas_key_e = $pa_old->pas_key_e;
			$table_stock->location = strtoupper($location_to);
			$table_stock->pas_key_location = $pa_old->pas_key.'_'.$location_to;
			$table_stock->kotur_qty = $qty;
			$table_stock->kotur_width = $pa_old->kotur_width;
			$table_stock->uom = $pa_old->uom;
			$table_stock->material = $pa_old->material;
			$table_stock->fg_color_code = $pa_old->fg_color_code;
			$table_stock->pcs_kotur = $pa_old->pcs_kotur;
			$table_stock->save();
		}

		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = strtoupper($location_from);
		$table_stock->location_to = strtoupper($location_to);
		$table_stock->location_type = 'stock';
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');

	}

// JUST_CUT TO LOC 
	public function paspul_jc_to_loc_su_from() {

		$location_from = "JUST_CUT";
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('pss.paspul_loc_to_loc_su_pas', compact('location_from', 'pas_keys'));
	}

// JUST_CUT TO READY_FOR_KIKINDA
	public function paspul_jc_to_rk_su_from() {

		$location_from = "JUST_CUT";
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		$location_to = "READY_FOR_KIKINDA";

		return view('pss.paspul_loc_to_loc_su_pas', compact('location_from', 'pas_keys', 'location_to'));
	}

// JUST_CUT TO READY_FOR_SENTA
	public function paspul_jc_to_rs_su_from() {

		$location_from = "JUST_CUT";
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		$location_to = "READY_FOR_SENTA";

		return view('pss.paspul_loc_to_loc_su_pas', compact('location_from', 'pas_keys', 'location_to'));
	}

// JUST_CUT TO READY_FOR_VALY
	public function paspul_jc_to_rv_su_from() {

		$location_from = "JUST_CUT";
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		$location_to = "READY_FOR_VALY";

		return view('pss.paspul_loc_to_loc_su_pas', compact('location_from', 'pas_keys', 'location_to'));
	}

// LOC TO PROD 
	public function paspul_loc_to_prod_su_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE s.[location] = 'JUST_CUT' OR l.[plant] = 'Subotica'  "));

		return view('pss.paspul_loc_to_prod_su_from', compact('from'));
	}

	public function paspul_loc_to_prod_su_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE s.[location] = 'JUST_CUT' OR l.[plant] = 'Subotica'  "));

		if (!empty($input['location1'])) {
			$location_from = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_from = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan or select location';
			return view('pss.paspul_loc_to_prod_su_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Subotica' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('pss.paspul_loc_to_prod_su_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('pss.paspul_loc_to_prod_su_pas', compact('location_from', 'pas_keys'));
	}

	public function paspul_loc_to_prod_su_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = $input['location_from'];
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('pss.paspul_loc_to_prod_su_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('pss.paspul_loc_to_prod_su_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('pss.paspul_loc_to_prod_su_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_loc_to_prod_su_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('pss.paspul_loc_to_prod_su_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('pss.paspul_loc_to_prod_su_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_loc_to_prod_su_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('pss.paspul_loc_to_prod_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('pss.paspul_loc_to_prod_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('pss.paspul_loc_to_prod_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));
			}
		}

		$op = $input['op1'];
		// dd($op);

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Subotica' and type = 'line' "));

		return view('pss.paspul_loc_to_prod_su_to', compact('location_from', 'pas_one', 'qty', 'op', 'to'));
	}

	public function paspul_loc_to_prod_su_to_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		$op = $input['op'];

		$to =  DB::connection('sqlsrv')->select(DB::raw("SELECT [location] FROM [cutting].[dbo].[paspul_locations]
		WHERE [plant] = 'Subotica' and type = 'line' "));
		
		if (!empty($input['location1'])) {
			$location_to = strtoupper($input['location1']);
		} elseif (!empty($input['location2'])) {
			$location_to = strtoupper($input['location2']);
		} else {
			$msge = 'Please scan line or bb';
			return view('pss.paspul_loc_to_prod_su_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
		}
		// dd($location_to);

		// check line
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id,location FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_to."' and [plant] = 'Subotica' and [type] = 'line' "));
		// dd($check_location);

		// check bb
		$check_bb = DB::connection('sqlsrv2')->select(DB::raw("SELECT bb.[INTKEY] as id
					,bb.[BlueBoxNum] as bb
					,st.[StyCod] as style
			FROM dbo.CNF_BlueBox AS bb 
				LEFT JOIN dbo.CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT JOIN dbo.CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY
				LEFT JOIN dbo.CNF_STYLE AS st ON sku.STYKEY = st.INTKEY
			WHERE bb.[Status] != '99' and bb.[CREATEDATE] > '2022-01-01' and bb.[INTKEY] = '".(int)$location_to."'

			UNION ALL

			SELECT bb.[INTKEY] as id
					,bb.[BlueBoxNum] as bb
					,st.[StyCod] as style
			FROM            
				[SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_BlueBox AS bb 
				LEFT JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_PO AS po ON bb.IntKeyPO = po.INTKEY 
				LEFT JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_SKU AS sku ON po.SKUKEY = sku.INTKEY
				LEFT JOIN [SBT-SQLDB01P\INTEOSKKA].[BdkCLZKKA].[dbo].CNF_STYLE AS st ON sku.STYKEY = st.INTKEY
				
			WHERE bb.[Status] != '99' and bb.[CREATEDATE] > '2022-01-01' and bb.[INTKEY] = '".(int)$location_to."'
		"));
		// dd($check_bb);

		if ( (!isset($check_location[0]->id)) AND (!isset($check_bb[0]->id)) ) {
			$msge = 'Line or BB is not correct';
			return view('pss.paspul_loc_to_prod_su_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
		}
		// dd($location_to);

		if (!isset($check_location[0]->id)) {

			$style = substr($check_bb[0]->style, 0, 4);

			if (substr($style, 0, 4) != substr($pas_one, 0, 4)) {
				
				$msge = 'BB style is different from paspul style! ';
				return view('pss.paspul_loc_to_prod_su_to', compact('location_from', 'pas_one', 'qty', 'op', 'to', 'msge'));
			}

			$location_to = $check_bb[0]->bb;
			$location_type = 'bb';

		} elseif (!isset($check_bb[0]->id)) {
			$location_to = $check_location[0]->location;
			$location_type = 'line';
		}

		// dd($location_to);

		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);
		
		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = strtoupper($location_from);
		$table_stock->location_to = strtoupper($location_to);
		$table_stock->location_type = $location_type;
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');

	}

// DELETE
	public function paspul_loc_to_del_su_from() {

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Subotica' and s.[location] != 'READY_FOR_KIKINDA' and s.[location] != 'READY_FOR_SENTA' and s.[location] != 'READY_FOR_VALY' "));

		return view('pss.paspul_loc_to_del_su_from', compact('from'));
	}

	public function paspul_loc_to_del_su_from_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);
		// $po = $input['po'];

		$from =  DB::connection('sqlsrv')->select(DB::raw("SELECT DISTINCT s.[location] FROM [cutting].[dbo].[paspul_stocks] as s
		LEFT JOIN [cutting].[dbo].[paspul_locations] as l ON l.[location] = s.[location]
		WHERE l.[plant] = 'Subotica'  "));

		if (!empty($input['location1'])) {
			$location_from = $input['location1'];
		} elseif (!empty($input['location2'])) {
			$location_from = $input['location2'];
		} else {
			$msge = 'Please scan or select location';
			return view('pss.paspul_loc_to_del_su_from', compact('from','msge'));
		}
		// dd($location_from);

		// check location
		$check_location = DB::connection('sqlsrv')->select(DB::raw("SELECT id FROM [cutting].[dbo].[paspul_locations] 
		WHERE [location] = '".$location_from."' and [plant] = 'Subotica' "));

		if (!isset($check_location[0]->id)) {
			$msge = 'Location is not correct';
			return view('pss.paspul_loc_to_del_su_from', compact('from','msge'));
		}
			
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));
		// dd($pas_keys);

		return view('pss.paspul_loc_to_del_su_pas', compact('location_from', 'pas_keys'));
	}

	public function paspul_loc_to_del_su_pas_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);

		$location_from = $input['location_from'];
		$pas_keys =  DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] as s
		WHERE s.[location] = '".$location_from."' "));

		if (!empty($input['pas_one1'])) {
			$pas_one = $input['pas_one1'];
		} elseif (!empty($input['pas_one2'])) {
			$pas_one = $input['pas_one2'];
		} else {
			$msge = 'Please scan or select paspul key';
			return view('pss.paspul_loc_to_del_su_pas', compact('location_from', 'pas_keys', 'msge'));
		}

		// check location
		$check_pas = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE ([pas_key] = '".$pas_one."' OR [pas_key_e] = '".$pas_one."') and [location] = '".$location_from."' "));

		if (!isset($check_pas[0]->id)) {
			$msge = 'Paspul is not correct';
			return view('pss.paspul_loc_to_del_su_pas', compact('location_from', 'pas_keys', 'msge'));
		}
		// dd($pas_one);
		$pas_one = $check_pas[0]->pas_key;
		$current_qty = (int)$check_pas[0]->kotur_qty;
		
		return view('pss.paspul_loc_to_del_su_qty', compact('location_from', 'pas_one', 'current_qty'));
	}

	public function paspul_loc_to_del_su_qty_post (Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$current_qty = $input['current_qty'];

		if (($input['qty'] <= 0) OR ($input['qty'] > $current_qty)) {
			$msge = 'Please insert correct quantity';
			return view('pss.paspul_loc_to_del_su_qty', compact('location_from', 'pas_one', 'current_qty', 'msge'));
		}

		$qty = (int)$input['qty'];
		// dd($qty);

		return view('pss.paspul_loc_to_del_su_op', compact('location_from', 'pas_one', 'qty'));
	}

	public function paspul_loc_to_del_su_op_post(Request $request) {
		//
		// $this->validate($request, ['po'=>'required']);
		$input = $request->all();
		// dd($input);
		$location_from = $input['location_from'];
		$pas_one = $input['pas_one'];
		$qty = $input['qty'];
		

		if (empty($input['op1'])) {
			$msge = 'Please insert correct operator';
			return view('pss.paspul_loc_to_del_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));

		} elseif (substr($input['op1'],0,1) != "R" ) {
		
			$msge = 'Please insert correct operator';
			return view('pss.paspul_loc_to_del_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));	

		} else {
			
			$op = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM operator_others WHERE rnumber = '".$input['op1']."' "));
			// dd($op);
			
			if (!isset($op[0]->operator)) {
				$msge = 'Please insert correct operator';
				return view('pss.paspul_loc_to_del_su_op', compact('location_from', 'pas_one', 'qty', 'msge'));
			}
		}

		$op = $input['op1'];
		// dd($op);

		$pa_old = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[paspul_stocks] 
		WHERE [pas_key] = '".$pas_one."' and [location] = '".$location_from."'  "));

		$pa_old = paspul_stock::findOrFail($pa_old[0]->id);

		// FOR OLD // delete if is last qty on loc
		if (($pa_old->kotur_qty - $qty) == 0  ) {
			$pa_old->delete();
		} elseif (($pa_old->kotur_qty - $qty) < 0  ) {
			dd("Error ($pa_old->kotur_qty - $qty) < 0");
		} else {
			$pa_old->kotur_qty = $pa_old->kotur_qty - $qty;
			$pa_old->save();
		}

		$table_stock = new paspul_stock_log;
		$table_stock->pas_key = $pa_old->pas_key;
		$table_stock->pas_key_e = $pa_old->pas_key_e;
		$table_stock->location_from = $location_from;
		$table_stock->location_to = 'DELETED';
		$table_stock->location_type = 'delete';
		$table_stock->kotur_qty = (int)$qty;
		$table_stock->operator = $op;
		$table_stock->shift = Auth::user()->name;

		$table_stock->kotur_width = $pa_old->kotur_width;
		$table_stock->uom = $pa_old->uom;
		$table_stock->material = $pa_old->material;
		$table_stock->fg_color_code = $pa_old->fg_color_code;

		$table_stock->skeda = $pa_old->skeda;
		$table_stock->paspul_type = $pa_old->paspul_type;
		$table_stock->dye_lot = $pa_old->dye_lot;
		$table_stock->kotur_length = $pa_old->kotur_length;

		$table_stock->returned_from = $pa_old->returned_from;
		$table_stock->pcs_kotur = $pa_old->pcs_kotur;

		$table_stock->save();

		return Redirect::to('/');	
	}

}