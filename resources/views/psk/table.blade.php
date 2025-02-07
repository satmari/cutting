@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
		            <div class="panel panel-default">
		            	<div class="panel-heading">Paspul table
		            		
		            	</div>
		            	
		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-striped table-bordered tableFixHead" id="table-draggable2" 
		                >
		                <!--
		                
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
		                            <th>Paspul type</th>
		                            <th>Dye lot</th>
		                            <th>Paspul length [m]</th>
		                            <th>Pas Key</th>
		                            <th>Kotur width [mm]</th>
		                            <th>UoM</th>
		                            <th>Material</th>
		                            <th>FG color code</th>
		                            <th data-sortable="true">Location</th>
		                            <th><i>[m/pcs]</i></th>
		                            <th><i>[pcs/kotur]</i></th>
		                            <th>Kotur Qty</th>
		                            <th>FG Qty</th>
		                            <th></th>

		                    	</tr>
		                    </thead>
		                    <tbody class="connectedSortable_t able searchable" id="sortable 10" >
		                    	<!-- <tr>
		                    		<th class=""><div><span>position</div></span></th>
		                            <th class=""><div><span>mattress</div></span></th>
		                            <th class=""><div><span>material</div></span></th>
		                            <th class=""><div><span>dye_lot</div></span></th>
		                            <th></th>
		                    	</tr> -->
		                    @foreach ($data as $req)
		                        <tr>
		                            <!-- <td>{{ $req->pas_key}}</td> -->
		                            <td>{{ $req->skeda}}</td>
		                            <td>{{ $req->paspul_type}}</td>
		                            <td>{{ $req->dye_lot}}</td>
		                            <td>{{ round($req->kotur_length,2)}}</td>
		                            <td><small>{{ $req->pas_key}}</small></td>
		                            <td>{{ $req->kotur_width}}</td>
		                            <td>{{ $req->uom}}</td>
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->fg_color_code}}</td>
		                            <td>{{ $req->location}}</td>
		                            
		                            @if ($req->unit_cons == 0) 
		                            	<td>missing</td>
		                        	@else
		                        		<td>{{ round($req->unit_cons ,2) }}</td>
		                        	@endif

		                        	@if ($req->unit_cons == 0) 
		                            	<td>missing</td>
		                        	@else
		                        		@if ($req->uom == 'meter')
		                        			<td>{{ floor(round($req->kotur_length,2) / $req->unit_cons) }}</td>
		                        		@elseif ($req->uom == 'ploce')
		                        			<td>{{ floor(round($req->kotur_length,2) * $req->unit_cons) }}</td>
		                        		@else
		                        			<td>Wrong Uom</td>
		                        		@endif
		                        	@endif

		                        		<td><big><b>{{ $req->kotur_qty}}</b></big></td>

		                            @if ($req->unit_cons == 0) 
		                            	<td>missing</td>
		                        	@else
		                        		@if ($req->uom == 'meter')
		                        			<td>{{ round($req->kotur_qty * floor(round($req->kotur_length,2) / $req->unit_cons) ,1) }}</td>
		                        		@elseif ($req->uom == 'ploce')
		                        			<td>{{ round($req->kotur_qty * floor(round($req->kotur_length,2) * $req->unit_cons) ,1) }}</td>
		                        		@else
		                        			<td>Wrong Uom</td>
		                        		@endif

		                        	@endif
		                            
									

									@if(Auth::user()->level() == 23 OR Auth::user()->level() == 24 OR Auth::user()->level() == 25)
									<td>
										<!-- <a href="{{ url('print_paspul_label/'.$req->id) }}" class="btn btn-primary btn-xs">Print label</a> -->
										<a href="{{ url('print_paspul_label1/'.$req->id) }}" class="btn btn-info btn-xs">Print label (new)</a>
									</td>

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