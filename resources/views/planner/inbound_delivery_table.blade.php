@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">
		            	<div class="panel-heading">
		            		
		            		<a href="{{url('/inbound_delivery_table')}}" class="btn btn-success" style="text-decoration: underline;">Available fabric</a>
		            		<!-- <br> -->
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		<a href="{{url('/fabric_reservation_table')}}" class="btn btn-warning" >Reserved fabric</a>
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		<a href="{{url('leftover_table')}}" class="btn btn-info">Leftover Queue</a>
		            		
		            	</div>
		            	
		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-striped table-bordered tableFixHead" id="table-draggable2" id="sort" 
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
		                    		<th data-sortable="true">Document number</th>
		                            <th data-sortable="true">Posting date</th>
		                            <th data-sortable="true">Material</th>
		                            <th data-sortable="true">Bagno</th>
		                            <th data-sortable="true">Pref. org</th>
		                            <th data-sortable="true">Received Qty</th>
		                            <th data-sortable="true">Available Qty</th>
		                            <th data-sortable="true">Status</th>
		                            <th data-sortable="true">Type</th>
		                            <th></th>
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable">
		                    @foreach ($data as $req)
		                        <tr class="ss">
		                            <td>{{ $req->document_no}}</td>
		                            <td>{{ $req->posting_date}}</td>
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->bagno}}</td>
		                            <td>{{ $req->preforigin}}</td>
		                            <td><b><big>{{ round($req->qty_received_m,1) }}</big></b></td>

		                            @if ($req->type != 'Leftover')
		                            	<td><b><big>{{ round($req->qty_received_m-$req->qty_reserved_m ,1) }}</big></b></td>
		                            @else
		                            	<td><b><big>{{ round($req->qty_received_m-$req->qty_reserved_m ,1) }}</big></b></td>
		                            @endif
		                            
		                            <td>{{ $req->reserve_status}}</td>
		                            <td>{{ $req->type}}</td>
		                            
		                            @if ($req->reserve_status != 'Reserved')
	                            		<th><a href="{{ url('reserve_material/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Reserve material</a></th>
	                            	@else
	                            		<th><a href="{{ url('reserve_material/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>Reserve material</a></th>
	                            	@endif

		                           
		                        </tr>
		                    @endforeach
		                    </tbody>
		                  </table>
					</div>
			</div>
        </div>
    </div>
</div>

@endsection
