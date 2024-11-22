@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">
		            	<div class="panel-heading">
		            		<a href="{{url('/inbound_delivery_table')}}" class="btn btn-success">Available fabric</a>
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		<a href="{{url('/fabric_reservation_table')}}" class="btn btn-warning" style="text-decoration: underline;">Reserved fabric</a>
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		&nbsp;
		            		<a href="{{url('leftover_table')}}" class="btn btn-info">Leftover Queue</a>
		            		<!-- <br><br> -->
		            		
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
		                    		<th data-sortable="true">Skeda</th>
		                            <th data-sortable="true">Reservation date</th>
		                            <th data-sortable="true">Document no</th>
		                            <th data-sortable="true">Material</th>
		                            <th data-sortable="true">Bagno</th>
		                            <th data-sortable="true">Pref. org</th>
		                            <th data-sortable="true"><b><big>Reserved Qty</big></b></th>
		                            <th data-sortable="true"><a href="{{ url('update_skeda_status') }}" class="btn btn-primary btn-xs center-bl ock">Update status</a></th>
		                            <th data-sortable="true">Operator</th>
		                            <th data-sortable="true">Comment</th>
		                            <!-- <th></th> -->

		                            
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable">
		                    @foreach ($data as $req)
		                        <tr class="ss">
		                            <td>{{ $req->skeda}}</td>
		                            <td>{{ substr($req->reservation_date,0,16) }}</td>
		                            <td>{{ $req->document_no}}</td>
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->bagno}}</td>
		                            <td>{{ $req->preforigin}}</td>
		                            <td><b><big>{{ round($req->qty_reserved_m,1) }}</big></b></td>
		                            <td>{{ $req->skeda_status}}</td>
		                            <td>{{ $req->operator}}</td>
		                            <td>{{ $req->comment}}</td>
		                            
		                           <!--  @if ($req->skeda_status == 'Leftover to Check')
	                            		<td>
	                            			<a href="{{ url('declare_no_leftover/'.$req->id) }}" class="btn btn-warning btn-xs">Confirm No Leftover</a>
	                            			<a href="{{ url('declare_leftover/'.$req->id) }}" class="btn btn-warning btn-xs">Declare Leftover</a>
	                            		</th>
	                            	@else
	                            		<td>
	                            			<a href="{{ url('declare_no_leftover/'.$req->id) }}" class="btn btn-warning btn-xs" disabled>Confirm No Leftover</a>
	                            			<a href="{{ url('declare_leftover/'.$req->id) }}" class="btn btn-warning btn-xs" disabled>Declare Leftover</a>
	                            		</th>
	                            	@endif -->

		                           
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
