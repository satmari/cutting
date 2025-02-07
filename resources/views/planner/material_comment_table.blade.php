@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">
		            	<div class="panel-heading">
		            		
		            		<a href="{{url('material_comment_new')}}" class="btn btn-success btn-xs center-blo ck">New Standard comment</a>
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
		                    		<th data-sortable="true">Material</th>
		                            <th data-sortable="true">Comment</th>
		                            <th></th>

		                            
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable">
		                    @foreach ($data as $req)
		                        <tr class="ss">
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->standard_comment}}</td>
		                            
		                           	<td>
	                            		<a href="{{ url('material_comment_edit/'.$req->id) }}" class="btn btn-warning btn-xs">Edit comment</a>
	                            	</th>
	                            	
		                           
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
