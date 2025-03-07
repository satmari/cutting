@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">
		            	<div class="panel-heading">
		            		<a href="{{url('/o_roll_table')}}" class="btn btn-warning btn-xs center-blo ck">Table</a>
		            		&nbsp;&nbsp;&nbsp;&nbsp;
		            		<a href="{{url('/o_roll_table_all')}}" class="btn btn-success btn-xs center-blo ck">Log table (last 60 days)</a>
		            		
		            		 

		            		&nbsp;&nbsp;&nbsp;&nbsp; Leftover Roll table  &nbsp;&nbsp;&nbsp;&nbsp;
		            		<!-- <br> -->
		            		<a href="{{url('/')}}" class="btn btn-danger btn-xs center-blo ck">Back to Main page</a>
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
		                    		<th data-sortable="true">Leftover roll</th>
		                            <th data-sortable="true">Mattress Orig</th>
		                            <th data-sortable="true">Mattress New</th>
		                            <th data-sortable="true">G bin</th>
		                            <th data-sortable="true">Skeda</th>
		                            <th data-sortable="true">Status</th>
		                            <th data-sortable="true">No of parts</th>
		                            <th data-sortable="true">Operator</th>
		                            <th data-sortable="true">Created</th>
		                            <th></th>

		                            @if(Auth::user()->level() == 3)
		                            <th></th>
		                            @endif
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable">
		                    @foreach ($data as $req)
		                        <tr class="ss">
		                            <td>{{ $req->o_roll}}</td>
		                            <td>{{ $req->mattress_name_orig}}</td>
		                            <td>{{ $req->mattress_name_new}}</td>
		                            <td>{{ $req->g_bin}}</td>
		                            <td>{{ $req->skeda}}</td>
		                            <td>{{ $req->status}}</td>
		                            <td>{{ $req->no_of_joinings}}</td>
		                            <td>{{ $req->operator}}</td>
		                            <td>{{ $req->created_at}}</td>

		                            
		                            @if ($req->status == 'PLANNED')
	                            		<th><a href="{{ url('o_roll_return/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Return to stock</a></th>
	                            	@else
	                            		<th><a href="{{ url('o_roll_return/'.$req->id) }}" class="btn btn-warning btn-xs center-block" disabled>Return to stock</a></th>
	                            	@endif

		                            @if(Auth::user()->level() == 3)
		                            	@if ($req->status != 'CREATED')
		                            	<th><a href="{{ url('o_roll_delete/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>Delete</a></th>
		                            	@else
		                            	<th><a href="{{ url('o_roll_delete/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></th>
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
</div>

@endsection