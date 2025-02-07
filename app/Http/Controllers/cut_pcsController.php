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

use App\User;
use Bican\Roles\Models\Role;
use Bican\Roles\Models\Permission;
use Auth;

use Session;
use Validator;

class cut_pcsController extends Controller {


	public function req_cut_part($line, $id) {
		// dd($id);
		// dd($line);

		$bbdata = DB::connection('sqlsrv')->select(DB::raw("SELECT [id]
		      ,[bbcode]
		      ,[bbname]
		      ,[po]
		      ,[style]
		      ,[color]
		      ,[size]
		      ,[bagno]
		  FROM [bbStock].[dbo].[bbStock]
		  WHERE id = '".$id."'
		  "));
		// dd($bbdata);

		$line;
		$bbcode = $bbdata[0]->bbcode;
		$bbname = $bbdata[0]->bbname;
		$po = $bbdata[0]->po;
		$style = $bbdata[0]->style;
		$color = $bbdata[0]->color;
		$size = $bbdata[0]->size;
		$bagno = $bbdata[0]->bagno;

		$style_extra_img = DB::connection('sqlsrv3')->select(DB::raw("SELECT image
			FROM [settings].[dbo].[styles_extras]
		  	WHERE style = '".$style."' AND color = '".$color."' AND size = '".$size."'
		"));
		// dd($style_extra_img[0]->image);

		$style_img = DB::connection('sqlsrv3')->select(DB::raw("SELECT image
			FROM [settings].[dbo].[styles]
		  	WHERE style = '".$style."' 
		"));
		// dd($style_img[0]->image);

		if (isset($style_extra_img[0]->image)) {
			$image = $style_extra_img[0]->image;

		} else if (isset($style_img[0]->image)) {
			$image = $style_img[0]->image;

		} else {
			$image = null;
		}
		// dd($image);

		$rows = 10;

		return view('requests.req_cut_part', compact('line','po','bbname','style','color','size','bagno','image'));

	}

	public function requeststore_cut_part(Request $request) {
		
		//
		// $this->validate($request, [	]);

		$input = $request->all();
		// dd($input);

		$module = $input['line'];
		$bbname = $input['bbname'];
		$po = $input['po'];
		$style = $input['style'];
		$color = $input['color'];
		$size = $input['size'];
		$bagno = $input['bagno'];
		$image = $input['image'];

		$hidden[] = $input['hidden'];
		$qty[] = $input['qty'];
		$comment[] = $input['comment'];

		// dd($hidden[0]);
		// dd($qty);
		// var_dump($qty);

		for ($i=0; $i < count($hidden[0]); $i++) { 
			


			$part = $hidden[0][$i];
			$qty_line = (int)$qty[0][$i];
			$comment_line = $comment[0][$i];
			// dd($part[$i]);
			// dd($qty_line);
			// dd($part.' '.$qty_line.' '.$comment_line);
			// var_dump($part.' '.$qty_line.' '.$comment_line);

			if ($qty_line <> 0) {
				// dd($qty_line);

				try {
					$table = new req_cut_part;

					$table->module = $module;
					$table->po = $po;
					$table->bb = $bbname;
					$table->style = $style;
					$table->color = $color;
					$table->size = $size;
					$table->bagno = $bagno;
					$table->image = $image;
					$table->part = $part;
					$table->qty = $qty_line;
					$table->comment = $comment_line;
					
					$table->status = "Pending";
					
					$table->save();
				}
				catch (\Illuminate\Database\QueryException $e) {
					dd("Problem to save in req_cut_part");
				}
			}

		}

		$paspul_qty = (int)$input['paspul_qty'];

		if ($paspul_qty <>0) {
			try {
				$table = new req_cut_part;

				$table->module = $module;
				$table->po = $po;
				$table->bb = $bbname;
				$table->style = $style;
				$table->color = $color;
				$table->size = $size;
				$table->bagno = $bagno;
				$table->image = $image;
				$table->part = "Paspul";
				$table->qty = $paspul_qty;
				$table->comment = $input['paspul_comment'];
				
				$table->status = "Pending";
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd("Problem to save in req_cut_part");
			}
		}

		$other_qty = (int)$input['other_qty'];

		if ($other_qty <>0) {
			try {
				$table = new req_cut_part;

				$table->module = $module;
				$table->po = $po;
				$table->bb = $bbname;
				$table->style = $style;
				$table->color = $color;
				$table->size = $size;
				$table->bagno = $bagno;
				$table->image = $image;
				$table->part = "Other";
				$table->qty = $other_qty;
				$table->comment = $input['other_comment'];
				
				$table->status = "Pending";
				
				$table->save();
			}
			catch (\Illuminate\Database\QueryException $e) {
				dd("Problem to save in req_cut_part");
			}
		}

		
		return Redirect::to('http://172.27.161.172/bbstock2/production');

	}

	public function req_extrabb_line_history($line) {
		// dd($id);
		// dd($line);

		$data = DB::connection('sqlsrv')->select(DB::raw("SELECT * FROM [cutting].[dbo].[req_cut_parts] WHERE created_at >= DATEADD(day,-60,GETDATE()) AND module = '".$line."' ORDER BY created_at desc"));
		return view('requests.req_cut_part_table_line', compact('data', 'line'));

	}
}
