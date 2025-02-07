@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-8 col-md-offset-2">
		            <div class="panel panel-default">
		            	<div class="panel-heading">Skeda comments &nbsp;&nbsp;&nbsp;&nbsp;
		            		
		            		<a href="{{url('/skeda_comments_add')}}" class="btn btn-success btn-xs center-blo ck">New Skeda comment</a>
		            		
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
		                    		<th >Skeda</th>
		                       		<th >Comment</th>
		                            <th >User</th>
		                            <th >Created</th>
		                            
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
		                            <td>{{ $req->skeda}}</td>
		                            <td>{{ $req->comment}}</td>
		                            <td>{{ $req->operator}}</td>
		                            <td>{{ substr($req->created_at,0,16)}}</td>
									<td>
										@if(Auth::check() && Auth::user()->level() == 3)
											<a href="{{ url('skeda_comment_edit/'.$req->id) }}" class="btn btn-danger btn-xs center-block">
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