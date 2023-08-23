@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Bansek phase adv &nbsp; &nbsp; &nbsp; &nbsp;
					<a class="" href="cutting_bansek_xml_all"><b>Last 5000 lines</b></a>
				</div>

				
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
				           {{-- <th>id</th> --}}
				           
				           <th>Date</th>
				           <th>PRO</th>
				           <th>SKU</th>
				           <th>WC</th>
				           <th>SAP Yeld qty</th>
				           <th>Pulse app qty</th>
				           <th>DELTA</th>
				           <th>Exported</th>
				           
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}

				        	<td>{{ $d->date }}</td>
				        	<td>{{ $d->pro }}</td>
				        	<td>{{ $d->sku }}</td>
				        	<td>{{ $d->wc }}</td>
				        	<td>{{ $d->qty_yield }}</td>
				        	<td>{{ $d->qty_pulse }}</td>
				        	<td>{{ $d->qty_delta }}</td>
				        	<td>{{ $d->exporded }}</td>

				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection
