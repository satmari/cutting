@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Wastage table &nbsp &nbsp &nbsp 
					<a href="wastage_table_import">|Import Reported to Log|</a></div>

				
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
                <table class="table table-striped table-bordered" id="sort" 
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
				           <th>Bag no</th>
				           <th>Skeda</th>
				           <th>SAP Bin</th>
				           <th>Material</th>
				           <th>Weight</th>
				           <th>Coment</th>
				           <th>Container</th>
				           <th>Location</th>
				           <th>TPP ship.</th>
				           <th>Approval</th>
				           <th>Reported to Log</th>
				           <th></th>
				           <th></th>
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	<td>{{ $d->no }}</td>
				        	<td>{{ $d->skeda }}</td>
				        	<td>{{ $d->sap_bin }}</td>
				        	<td>{{ $d->material }}</td>
				        	<td>{{ round($d->weight,2) }}</td>
				        	<td>{{ $d->coment }}</td>
				        	<td>{{ $d->container }}</td>
				        	<td>{{ $d->location }}</td>
				        	<td>{{ $d->tpp_shipment }}</td>
				        	<td>{{ $d->approval }}</td>
				        	<td>{{ $d->log_rep }}</td>
				        	<td><a href="{{ url('wastage_edit/'.$d->id) }}" class="btn btn-success btn-xs center-block">Edit</a></td>
				        	<td>
                                {!! Form::open(['method'=>'POST', 'url'=>'/delete_wastage_line' ]) !!}
                                    {!! Form::hidden('id', $d->id, ['class' => 'form-control']) !!}

                                    {!! Form::submit('Delete line', ['class' => 'btn btn-danger btn-xs center-block']) !!}
                                    @include('errors.list')
                                {!! Form::close() !!}
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
