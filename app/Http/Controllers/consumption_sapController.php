<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Database\QueryException as QueryException;
use App\Exceptions\Handler;

use Illuminate\Http\Request;
//use Gbrock\Table\Facades\Table;
use Illuminate\Support\Facades\Redirect;

use App\consumption_sap;

// use DB;
use Illuminate\Support\Facades\DB;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class consumption_sapController extends Controller {


	public function index()
	{
		//
		//$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM consumption_saps"));
		$data = DB::connection('sqlsrv6')->select(DB::raw("SELECT TOP 10000 g_bin, SUM(qty*-1) as cons_real FROM [posummary].[dbo].[mb51_cons] GROUP BY g_bin  ORDER BY g_bin desc"));
		$data1 = DB::connection('sqlsrv6')->select(DB::raw("SELECT TOP 1 * FROM [posummary].[dbo].[mb51_cons] ORDER BY created_at desc"));
		// dd($data1);
		
		$last_entered_date = $data1[0]->created_at;

		// dd($data);
		return view('consumption_sap.table', compact('data', 'last_entered_date'));
	}

	public function mat_con_file()
	{
		//
		// dd('test');
		
		// dd($data);
		return view('consumption_sap.mat_con_file');
	}

	public function mat_con_file_post(Request $request) {

		// $this->validate($request, ['selected_marker'=>'required']);
		$input = $request->all(); // change use (delete or comment user Requestl; )
		// dd($input);

		$StartDate = $input['import_date_od'];
		$EndDate = $input['import_date_do'];

		$sql = "
		    SELECT
		        m.[g_bin],
		        ps.[pro],
		        mp.[pro_pcs_layer] as qty
		    FROM [cutting].[dbo].[mattresses] as m
		    JOIN [cutting].[dbo].[mattress_details] as md ON md.[mattress_id] = m.[id]
		    JOIN [cutting].[dbo].[mattress_pros] as mp ON mp.[mattress_id] = m.[id]
		    JOIN [cutting].[dbo].[mattress_phases] as mh ON mh.[mattress_id] = m.[id] AND mh.[status] = 'TO_CUT'
		    JOIN [cutting].[dbo].[pro_skedas] as ps ON ps.[pro_id] = mp.[pro_id]
		    WHERE m.[g_bin] IS NOT NULL
		    AND mh.[updated_at] BETWEEN ? AND ?

		    UNION

		    SELECT
		        m.[g_bin],
		        ps.[pro],
		        po.[qty] as qty
		    FROM [cutting].[dbo].[mattresses] as m
		    JOIN [cutting].[dbo].[mattress_details] as md ON md.[mattress_id] = m.[id]
		    JOIN [cutting].[dbo].[mattress_phases] as mh ON mh.[mattress_id] = m.[id] AND mh.[status] = 'TO_JOIN'
		    JOIN [cutting].[dbo].[pro_skedas] as ps ON ps.[skeda] = m.[skeda]
		    JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
		    WHERE mh.[updated_at] BETWEEN ? AND ?

		    UNION

		    SELECT
		        m.[g_bin],
		        ps.[pro],
		        po.[qty] as qty
		    FROM [cutting].[dbo].[mattresses] as m
		    JOIN [cutting].[dbo].[mattress_details] as md ON md.[mattress_id] = m.[id]
		    JOIN [cutting].[dbo].[mattress_phases] as mh ON mh.[mattress_id] = m.[id] AND mh.[status] = 'COMPLETED'
		    JOIN [cutting].[dbo].[pro_skedas] as ps ON ps.[skeda] = m.[skeda]
		    JOIN [posummary].[dbo].[pro] as po ON po.[pro] = ps.[pro]
		    WHERE m.skeda_item_type = 'MT'
		    AND mh.[updated_at] BETWEEN ? AND ?
		";

		$data = DB::connection('sqlsrv')->select($sql, [
		    $StartDate, $EndDate,  // for first SELECT
		    $StartDate, $EndDate,  // for second SELECT
		    $StartDate, $EndDate   // for third SELECT
		]);

		// dd($data);
		return view('consumption_sap.mat_con_file_table', compact('data'));


	}
	
}
