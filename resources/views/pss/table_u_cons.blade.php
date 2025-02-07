@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
		            <div class="panel panel-default">
		            	<div class="panel-heading">
		            		<big><b>Paspul consumption table</b></big>
		            		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		            		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		            		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		            		<a href="table_u_cons_add" class="btn btn-danger btn-xs">Add manualy new paspul consumption</a>
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
		                       		<th data-sortable="true">Skeda</th>
		                            <th data-sortable="true">Paspul Type</th>
		                            <th data-sortable="true">Style</th>
		                            <th>Meter per PCS</th>
		                            <th>Created at</th>
		                            <th></th>

		                    	</tr>
		                    </thead>
		                    <tbody class="searchable" id="sortab le10" >
		                    	
		                    @foreach ($data as $req)
		                        <tr>
		                            <td>{{ $req->skeda}}</td>
		                            <td>{{ $req->paspul_type}}</td>
		                            <td>{{ $req->style}}</td>
		                            @if (($req->mtr_per_pcs == NULL) OR ($req->mtr_per_pcs == 0))
		                            	<td><b>Missing</b></td>
		                            @else
		                            	<td><b>{{ round($req->mtr_per_pcs,2)}}</b></td>	
		                            @endif
		                            <td>{{ substr($req->created_at,0,16)}}</td>
		                            <td>
										<a href="{{ url('table_u_cons_change/'.$req->id) }}" class="btn btn-danger btn-xs">Edit cons</a>
									</td>
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