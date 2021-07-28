@extends('app')

@section('content')

{{ header( "refresh:60;url=/cutting" ) }}
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

		            	<!-- <ul id="sortable">
		            	@foreach ($data as $req)
		            	    <li class="ui-state-default"><span ></span>{{ $req->position}} - {{$req->mattress}}</li>
						@endforeach	  
						</ul> -->

		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-striped table-bordered" id="table-draggable2" 
		                
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
		                    		<th >G-bin</th>
		                            <th >Mattress</th>
		                            <th >Priority</th>
		                            
		                            <th></th>
		                            <th></th>
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable" id="sortable 10">
		                    	<!-- <tr>
		                    		<th class=""><div><span>position</div></span></th>
		                            <th class=""><div><span>mattress</div></span></th>
		                            <th class=""><div><span>material</div></span></th>
		                            <th class=""><div><span>dye_lot</div></span></th>
		                            <th></th>
		                    	</tr> -->
		                    @foreach ($data as $req)
		                        <tr class="ss" id="item[]={{ $req->id }}" style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
		                            
		                            <td>{{ $req->g_bin}}</td>
		                            <td>{{ $req->mattress}}</td>
		                            <td class="
		                            @if ($req->priority == 3) top_priority
				        	    	@elseif ($req->priority == 2) high_priority
				        	    	@endif
				        	    	">
				        	    	@if ($req->priority == 3)Top
				        	    	@elseif ($req->priority == 2)High
			        	    		@elseif ($req->priority == 1)Normal
				        	    	@endif</td>
		                            
									<td>
										<a href="{{ url('mattress_pack/'.$req->id.'/'.$req->g_bin) }}" class="btn btn-danger btn-xs center-block">
										Pack mattress</a>
									</td>
									<td>
										<a href="{{ url('other_functions_pack/'.$req->id) }}" class="btn btn-info btn-xs center-block">
										Details</a>
									</td>

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