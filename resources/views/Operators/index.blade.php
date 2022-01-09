@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Operators table &nbsp; &nbsp; &nbsp;

				@if((Auth::check() && Auth::user()->name == "admin") OR ( Auth::check() && Auth::user()->name == "planner"))
					<a href="{{ url('operator_create') }}" class="btn btn-success btn-xs ">Add new operator</a>
				@endif

				</div>

                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
                <table class="table table-striped table-bordered tableFixHead" id="sort" 
                data-show-export="true"
                data-export-types="['excel']"
                >
                <!--
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
				           {{-- <th>id</th> --}}
				           
				           <th>Opearator</th>
				           <th>Device</th>
				           <!-- <th>Device Array</th> -->
				           <th>Status</th>
				           
				           <th>Edit</th>

				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}

				        	<td>{{ $d->operator }}</td>
				        	<td>{{ $d->device }}</td>
				        	<!-- <td>{{ $d->device_array }}</td> -->
				        	<td>{{ $d->status }}</td>
				        	
				        	<td>
				        	@if(Auth::check())
				        	  	<a href="{{ url('operator_edit/'.$d->id) }}" class="btn btn-info btn-xs center-block">Edit</a>
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
