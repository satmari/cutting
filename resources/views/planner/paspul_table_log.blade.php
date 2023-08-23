@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
		            <div class="panel panel-default">
		            	<div class="panel-heading">
		            		<a href="{{ url('paspul_stock') }}" class="btn btn-info btn-xs">Paspul table</a>&nbsp;&nbsp;&nbsp;&nbsp; 
		            		<a href="{{ url('paspul_stock_log') }}" disabled class="btn btn-danger btn-xs">Paspul table log (last 30 days)</a>&nbsp;&nbsp;&nbsp;&nbsp; 
		            		
		            	</div>
		            	
		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-striped table-bordered" id="so rt" 
		                data-show-export="true"
		                data-export-types="['excel']"
		                >
		                <!--
		                data-export-types="['excel']"
		                data-show-export="true"
		                data-export-types="['excel']"
		                data-search="true"
		                data-show-refresh="true"
		                data-show-toggle="true"
		                data-query-params="queryParams" 
		                data-pagination="true"
		                data-height="300"
		                data-show-columns="true" 
		                data-export-options='{
		                         "fileName": "preparation_app", 
		                         "worksheetName": "test1",         
		                         "jspdf": {                  
		                           "autotable": {
		                             "styles": { "rowHeight": 20, "fontSize": 10 },
		                             "headerStyles": { "fillColor": 255, "textColor": 0 },
		                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
		                           }
		                         }
		                       }'
		                -->
		                    <thead>
		                       <tr>
		                    		<!-- <th>Paspul key</th> -->
		                       		<th data-sortable="true">Pas Key</th>
		                            <th data-sortable="true">Location FROM</th>
		                            <th data-sortable="true">Location TO</th>
		                            <th data-sortable="true">Type</th>
		                            <th>Kotur Qty</th>
		                            <th>Operator</th>
		                            <th>Device</th>
		                            <th>Kotur width</th>
		                            <th>UoM</th>
		                            <th>Material</th>
		                            <th>FG color code</th>
		                            <th>Created at</th>
		                            
		                            <th></th>

		                    	</tr>
		                    </thead>
		                    <tbody class="searchable" id="sortab le10" >
		                    	
		                    @foreach ($data as $req)
		                        <tr>
		                            <!-- <td>{{ $req->pas_key}}</td> -->
		                            <td>{{ $req->pas_key}}</td>
		                            <td>{{ $req->location_from}}</td>
		                            <td>{{ $req->location_to}}</td>
		                            <td><b>{{ $req->location_type}}</b></td>
		                            <td><b>{{ $req->kotur_qty}}</b></td>
		                            <td>{{ $req->operator}}</td>
		                            <td>{{ $req->shift}}</td>
		                            <td>{{ $req->kotur_width}}</td>
		                            <td>{{ $req->uom}}</td>
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->fg_color_code}}</td>
		                            <td>{{ substr($req->created_at,0,16)}}</td>
		                            

									@if(Auth::user()->level() == 3 )
										@if ($req->location_type == 'line' OR $req->location_type == 'bb')
											@if ($req->skeda != '')
											<td>
												<a href="{{ url('paspul_change_log_q/'.$req->id) }}" class="btn btn-danger btn-xs">Return to stock</a>
											</td>
											@else
											<td></td>
											@endif
										@else
											<td></td>
										@endif
									@endif
		                        </tr>
		                        
		                    @endforeach
		                    
		                    </tbody>
		                  </table>
					</div>
        </div>
    </div>
</div>

<script>
	function sortTable(table, col, reverse) {
	    var tb = table.tBodies[0], // use `<tbody>` to ignore `<thead>` and `<tfoot>` rows
	        tr = Array.prototype.slice.call(tb.rows, 0), // put rows into array
	        i;
	    reverse = -((+reverse) || -1);
	    tr = tr.sort(function (a, b) { // sort rows
	        return reverse // `-1 *` if want opposite order
	            * (a.cells[col].textContent.trim() // using `.textContent.trim()` for test
	                .localeCompare(b.cells[col].textContent.trim())
	               );
	    });
	    for(i = 0; i < tr.length; ++i) tb.appendChild(tr[i]); // append each row in order
	}

	function makeSortable(table) {
	    var th = table.tHead, i;
	    th && (th = th.rows[0]) && (th = th.cells);
	    if (th) i = th.length;
	    else return; // if no `<thead>` then do nothing
	    while (--i >= 0) (function (i) {
	        var dir = 1;
	        th[i].addEventListener('click', function () {sortTable(table, i, (dir = 1 - dir))});
	    }(i));
	}

	function makeAllSortable(parent) {
	    parent = parent || document.body;
	    var t = parent.getElementsByTagName('table'), i = t.length;
	    while (--i >= 0) makeSortable(t[i]);
	}

	window.onload = function () {makeAllSortable();};
</script>

@endsection