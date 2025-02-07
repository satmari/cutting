@extends('app')

@section('content')



<div class="container container-table">
    <div class="row vertical-center-row">
    <br>

		<div class="text-center col-md-8 col-md-offset-2">

			<div class="panel panel-default">
	        	<div class="panel panel-default">
					<div class="panel-heading" style="background-color:#ebcdcd">History for g_bin:&nbsp;&nbsp;<b><big>{{$g_bin}}</big></b>
						<br>
						<br>

						<a href="{{ url('cpo_new_check/'.$g_bin) }}" class="btn btn-success btn-xs center-bl ock">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New bundle check in this g_bin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="{{ url('cpo_scan') }}" class="btn btn-primary btn-xs center-bl ock">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;New g_bin check &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
						<!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:history.go(-1)" class="btn btn-info btn-xs">Back to previous page</a> -->
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ url('set_status_g_bin/'.$g_bin) }}"  
						class="btn btn-danger btn-xs center-bl ock">Set status for {{$g_bin}}</a>
					</div>

	            	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-st riped table-bordered tableFixHead" id="table-draggable2" 
		                
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
		                    		<th>Style</th>
		                    		<th>Size</th>
		                    		<th>Bundle</th>
		                    		<th>Part</th>
		                            <th>MODE Length</th>
		                            <th>MODE Width</th>
		                            <!-- <th>Comment</th> -->
		                            <th></th>
		                    	</tr>
		                    </thead> 
		                    <tbody class="searc hable">
		                    	
		                    @foreach ($data as $req)
		                        <tr class="" id="" >
		                        	<td>{{ $req->g_bin}}</td>
		                        	<td>{{ $req->style}}</td>
		                        	<td>{{ $req->size}}</td>
		                        	<td>{{ $req->bundle}}</td>
		                        	<td>{{ $req->part}}</td>
		                        	<td>{{ round($req->length_mode,2)}}</td>
		                        	<td>{{ round($req->width_mode,2)}}</td>
		                        	
		                        	<!-- <td>{{ $req->comment}}</td> -->
		                        	<td><a href="{{ url('cpo_check_edit/'.$req->id) }}" class="btn btn-warning btn-xs center-bl ock">Edit line</a></td>
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