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
use App\Consumption;

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class consController extends Controller {

	public function index()
	{
		//
		return view('cons.index');
	}

	public function add_po_cons_table()
	{
		//
		return view('cons.add_po');
	}

	public function add_new_po_cons(Request $request)
	{
		//
		$this->validate($request, ['po'=>'required']);

		$input = $request->all(); // change use (delete or comment user Requestl; )
		
		$po = $input['po'];

		$data = DB::connection('sqlsrv1')->select(DB::raw("SELECT case when COMP.[Status] = 3 then 'Released' else 'Check status' end as [Status],
      COMP.[Prod_ Order No_],
      COM2.[Item No_],
      COM2.[Variant Code],
      cast(COMP.[Quantity] as float) as [Qty per], 
      PO.[Cutting Prod_ line], 
      case when PO.[To be finished] = 1 then 'Yes' else 'No' end as [To be finished],
      cast(SL.OrderedQty as float) as OrderedQty, 
      cast(COMP.[Quantity] * SL.OrderedQty as float) as [Theorethical Consumption MT],
      cast(COMP.[Quantity] * SL.OrderedQty * 0.03 as float) as [Possible overcons MT 3%]

  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as COMP left join
  
  (SELECT [No_]
      ,[Description 2]

  FROM [Gordon_LIVE].[dbo].[GORDON\$Item]) as ITM on ITM.[No_] = COMP.[Item No_] left join
  
  (SELECT [Status],
      [Prod_ Order No_],
      ([Item No_]) as [Item No_],
      ([Variant Code]) as [Variant Code],
      ([Quantity]) as Quantity

  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component]
  where [PfsHorz Component Group] = 'MTRLS' and [Prod_ Order No_] not like 'C%' and [Prod_ Order No_] not like 'S%' and [status] = 3
  group by [Status],[Item No_],[Variant Code],[Quantity],
       [Prod_ Order No_]

      ) as COM2 on COM2.[Prod_ Order No_] = COMP.[Prod_ Order No_] and COM2.[Item No_] = COMP.[Item No_] and COM2.[Variant Code] = COMP.[Variant Code] right join
      
      (SELECT [Status]
      ,[Prod_ Order No_]
      --,[Item No_]
      --,[Variant Code]
      ,max([Quantity]) as Quantity
      ,[Location Code]

  FROM [Gordon_LIVE].[dbo].[GORDON\$Prod_ Order Component] as POC left join
  
  (SELECT [No_]
      ,[Description 2]

  FROM [Gordon_LIVE].[dbo].[GORDON\$Item]) as ITM on ITM.[No_] = POC.[Item No_]
  where [PfsHorz Component Group] = 'MTRLS' and [Prod_ Order No_] not like 'C%' and [Prod_ Order No_] not like 'S%'
  and [status] = 3 and [Quantity] <> 0 and ITM.[Description 2] = 'fabric'
  group by [Status]
      ,[Prod_ Order No_]
      --,[Item No_]
      --,[Variant Code]
      ,[Location Code]--,[Quantity]
      --having [quantity] = max([quantity])
) as MX on MX.[Prod_ Order No_] = COMP.[Prod_ Order No_] and MX.[Quantity] = COMP.[Quantity] left join

(SELECT 
      [Shortcut Dimension 2 Code]
      ,[To be finished]
      ,[Cutting Prod_ Line]
   
  FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order]
  where [Status] = 3
  group by [Shortcut Dimension 2 Code]
      ,[To be finished] 
      ,[Cutting Prod_ Line]) as PO on PO.[Shortcut Dimension 2 Code] = COMP.[Prod_ Order No_] left join
      
      (SELECT 
      [Shortcut Dimension 2 Code]

      ,sum([PfsOrder Quantity]) as OrderedQty
    
  FROM [Gordon_LIVE].[dbo].[GORDON\$Sales Line]
  group by  [Shortcut Dimension 2 Code]) as SL on SL.[Shortcut Dimension 2 Code] = COMP.[Prod_ Order No_]
  
  where --[PfsHorz Component Group] = 'MTRLS' and 
	COMP.[Prod_ Order No_] not like 'C%' and COMP.[Prod_ Order No_] not like 'S%' and COMP.[Prod_ Order No_] like '%".$po."'
	and ITM.[Description 2] = 'fabric' and COMP.[Quantity] <> 0 and comp.[status] = 3
  group by COMP.[Status]
      ,COMP.[Prod_ Order No_]
      ,COM2.[ITem No_]
      ,COM2.[Variant Code],COMP.[Quantity], PO.[Cutting Prod_ line], PO.[To be finished],SL.OrderedQty
  order by [Prod_ Order No_]"));


	// dd($data[0]->{"Prod_ Order No_"});



	if (!isset($data[0])) {
		$msg = "Problem to get PO"; 
		return view('cons.error', compact('msg'));

	} else {
		
		$po = $data[0]->{"Prod_ Order No_"};

		$po = substr($po, -6);
		// dd($po);
		$status = $data[0]->{"Status"};
		$to_be_finished = $data[0]->{"To be finished"};
		$cut_prod_line = $data[0]->{"Cutting Prod_ line"};
		$order_qty = (int)$data[0]->{"OrderedQty"};

		$main_item = $data[0]->{"Item No_"};
		$main_variant = $data[0]->{"Variant Code"};
		$qty_per = round($data[0]->{"Qty per"},3);

		$teo_cons = round($data[0]->{"Theorethical Consumption MT"},3);
		$teo_cons_eur = NULL;

		$over_cons = round($data[0]->{"Possible overcons MT 3%"},3);
		$over_cons_eur = NULL;
		$percentage = 0.03;

		$extra_item = NULL;
		$extra_variant = NULL;
		$extra_consumed = NULL;
		$extra_consumed_eur = NULL;

		$error = NULL;

				
		try {
			$table = new Consumption;

			$table->po = $po;
			$table->status = $status;
			$table->to_be_finished = $to_be_finished;
			$table->cut_prod_line = $cut_prod_line;
			$table->order_qty = $order_qty;

			$table->main_item = $main_item;
			$table->main_variant = $main_variant;
			$table->qty_per = $qty_per;

			$table->teo_cons = $teo_cons;
			$table->teo_cons_eur = $teo_cons_eur;

			$table->over_cons = $over_cons;
			$table->over_cons_eur = $over_cons_eur;
			$table->percentage = $percentage;

			$table->extra_item = $extra_item;
			$table->extra_variant = $extra_variant;
			$table->extra_consumed = $extra_consumed;
			$table->extra_consumed_eur = $extra_consumed_eur;

			$table->error = $error;

			$table->save();
		}
		catch (\Illuminate\Database\QueryException $e) {
			$msg = "Problem to save, check if PO already exist."; 
			return view('cons.error', compact('msg'));
		}

	}
		
		return Redirect::to('/cons');
	}
	

	public function update_cons_table()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT id,po FROM consumptions ORDER BY po asc"));
		// dd($data[0]->po);


		if(isset($data[0])) {
			for ($i=0; $i < count($data); $i++) { 

				$info = DB::connection('sqlsrv1')->select(DB::raw("SELECT po.[Status],
					   po.[Docket No],
					   --po.[No_],
					   po.[Source No_],
					   po.[Shortcut Dimension 2 Code],
					   ile.[Item No_],
					   ile.[Variant Code],
					   -SUM(ile.[Quantity]) AS consumed
				FROM [Gordon_LIVE].[dbo].[GORDON\$Production Order] AS po 
				LEFT JOIN [Gordon_LIVE].[dbo].[GORDON\$Item Ledger Entry] as ile ON ile.[Simulated Order No_] = po.[No_]
				WHERE po.[Status] = 0 AND po.[No_] LIKE 'CUT%' AND po.[Docket No] LIKE '%WH%' AND ile.[Item No_] IS NOT NULL
				AND po.[Shortcut Dimension 2 Code] LIKE '%".$data[$i]->po."%'
				GROUP BY
					   po.[Status],
					   po.[Docket No],
					   --po.[No_],
					   po.[Source No_],
					   po.[Shortcut Dimension 2 Code],
					   ile.[Item No_],
					   ile.[Variant Code]
				order by po.[Shortcut Dimension 2 Code], consumed desc
				"));
				// dd($info);

				if (isset($info[0])) {
					
					$table = Consumption::findOrFail($data[$i]->id);

					try {
						
						$table->extra_item = $info[0]->{"Item No_"};
						$table->extra_variant = $info[0]->{"Variant Code"};
						$table->extra_consumed = $info[0]->{"consumed"};
						$table->extra_consumed_eur = NULL;

						if (isset($info[1])) {
							$table->error = "Already consumed on 2 or more extra materials";	
						}

						$table->save();
					}
					catch (\Illuminate\Database\QueryException $e) {
						$msg = "Problem to save"; 
						return view('cons.error', compact('msg'));
					}

				}

			}
		}


		return view('cons.index');
	}

	public function cons_table()
	{
		//
		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM consumptions ORDER BY po asc"));
		return view('cons.table', compact('data'));
	}

}
