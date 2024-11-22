@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

		            	@if (isset($full_table))

		            		<div class="panel-heading">Check parts with statuses by g_bin (status Ready for production + Not checked)
			            		&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('cpo') }}" class="btn btn-danger btn-xs">Table with Missing or Pending status</a>
			            	</div>
		            	@else 
			            	<div class="panel-heading">Check parts with statuses by g_bin (status Missing or Pending)
			            		&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('cpo_all') }}" class="btn btn-danger btn-xs">Table with Ready for production + Not checked status</a>
			            	</div>
			            	<a href="{{ url('cpo_scan') }}" class="btn btn-success">New g_bin check</a>
			            @endif


					
					
				

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
			                       		<th>G bin</th>
			                       		<!-- <th>Part</th> -->
			                       		<th>Style</th>
			                       		<!-- <th>Size</th> -->
			                       		<!-- <th>Bundle</th> -->
			                       		<!-- <th>Last modification by</th> -->
			                       		<th>G bin comment</th>
			                       		<th>Mandatory</th>
			                       		<th>Status</th>
			                       		<th></th>
			                    	</tr>
			                    </thead>
			                    <tbody class="con nectedSortable_table searchable">

			                    @foreach ($data as $req)
			                        <tr style="
			                        	@if ($req->mandatory_to_ins == 'YES')
											 color:red !important;font-weight: 700;
										@endif>
										">
			                            <td>{{ $req->g_bin }}</td>
			                            <td>{{ $req->style }}</td>
			                            <td>{{ $req->comment }}</td>
			                            <td>{{ $req->mandatory_to_ins}}</td>
			                            @if ($req->status == NULL)
			                          	  	<td>Missing</td>	
			                            @else
			                            	<td>{{ $req->status }}</td>
			                            @endif
			                            	
			                            
			                            
			                          		<td><a href="{{ url('set_status_g_bin/'.$req->g_bin) }}" class="btn btn-xs btn-danger">Change status</a> </td>
			                          	
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