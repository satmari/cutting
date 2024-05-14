@extends('app')

@section('content')

{{ header( "refresh:360;url=/cutting" ) }}

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

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
		                            <th>Created at</th>
		                            <th>Created loc</th>
		                            <th>Material</th>
		                            <th>Dye lot</th>
		                            <th>SAP Location</th>
		                            <th>Comment</th>
		                            <th>PRW (req. qty.)</th>
		                            <th>Location</th>
		                            <th>Operator</th>
		                            <th>Status</th>
		                            <th>Updated</th>
		                            <th></th>
		                            
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable" id="sortable 10">
		                    @foreach ($data as $req)
		                        <tr class="s s" style="">
		                            <td>{{ substr($req->created_at,0,16)}}</td>
		                            <td>{{ $req->location_created }} </td>
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->dye_lot}}</td>
		                            <td>{{ $req->sap_location1}} {{ $req->sap_location2}} {{ $req->sap_location3}} {{ $req->sap_location4}} {{ $req->sap_location5}}</td>
		                            
		                            <td>{{ $req->comment}}</td>
		                            <td>{{ $req->required_qty}}</td>
		                            <td>{{ $req->location }} </td>
		                            <td>{{ $req->operator1}}</td>

		                            @if ($req->status == 'CREATED')
		                            	<td><span style="color:green"><big>{{ $req->status }}</big></span></td>
		                            @elseif ($req->status == 'ACCEPTED')
		                            	<td><span style="color:blue"><big>{{ $req->status }}</big></span></td>
		                            @else
		                            	<td><span style="color:red"><big>{{ $req->status }}</big></span></td>
		                            @endif
		                            <td>{{ substr($req->up,0,16) }}</td>
		                            <td>
			                            @if ($req->status == 'CREATED')
			                            	<a href="{{ url('request_material_delete/'.$req->id) }}" class="btn btn-danger btn-x s center-block">Delete</a>
			                            @else 
			                            	<a href="{{ url('request_material_delete/'.$req->id) }}" class="btn btn-danger btn-x s center-block" disabled>Delete</a>
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
</div>

@endsection