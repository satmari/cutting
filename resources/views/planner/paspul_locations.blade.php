@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
		            <div class="panel panel-default">
		            	<div class="panel-heading">Paspul locations &nbsp;&nbsp;&nbsp;&nbsp;
		            		@if(Auth::check() && Auth::user()->level() == 3)
		            		<a href="{{url('/paspul_location_new')}}" class="btn btn-success btn-xs center-blo ck">New Location</a>
		            		@endif
		            	</div>
		            	
		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-striped table-bordered tableFixHead" id="table-draggable2" 
		                
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
		                    		<th >Location</th>
		                       		<th >Type</th>
		                            <th >Plant</th>
		                            
		                            <th></th>
		                            <!-- <th></th> -->
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
		                            <td>{{ $req->location}}</td>
		                            <td>{{ $req->type}}</td>
		                            <td>{{ $req->plant}}</td>
									<td>
										@if(Auth::check() && Auth::user()->level() == 3)
											<a href="{{ url('paspul_location_edit/'.$req->id) }}" class="btn btn-danger btn-xs center-block">
											Edit</a>
										@endif
									</td>
		                        </tr>
		                        
		                    @endforeach
		                    
		                    </tbody>
		                  </table>
					</div>
        </div>
    </div>
</div>

@endsection