@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Bansek phase adv - ERRORS &nbsp; &nbsp; &nbsp; &nbsp;
					
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
				           
				           <th>POnum</th>
				           <th>itemNo</th>
				           <th>variantCode</th>
				           <th>qty_pulse</th>
				           <th>po_in_posum</th>
				           <th>sap_pro</th>

				           <th>created</th>
				           <td>error</td>
				           <td>error</td>
				           <td>to do</td>
				           
				           
				        </tr>
				    </thead>
				    <tbody class="searchable">
				    
				    @foreach ($data as $d)
				    	
				        <tr>
				        	{{-- <td>{{ $d->id }}</td> --}}

				        	<td>{{ $d->POnum }}</td>
				        	<td>{{ $d->itemNo }}</td>
				        	<td>{{ $d->variantCode }}</td>
				        	<td>{{ $d->qty_pulse }}</td>
				        	<td>{{ $d->po_in_posum }}</td>
				        	<td>{{ $d->sap_pro }}</td>

				        	<td>{{ $d->created_at }}</td>
				        	
				        	@if ($d->po_in_posum == '')
				        		<td>NOT exist in POSummary</td>
				        	@elseif ($d->po_in_posum != '')
				        		<td>Exist in POSummary</td>

				        	@endif

				        	@if ($d->sap_pro == '')
				        		<td>PO not exist in SAP</td>
				        	@elseif (($d->sap_pro != ''))
				        		<td>PO not exist in SAP</td>

				        	@endif

				        	@if ($d->po_in_posum == '' AND $d->sap_pro == '')  
				        		<td>Check if PO is correct!!!</td>
				        	@else ($d->po_in_posum != '' AND $d->sap_pro == '')  
				        		<td>PO status in SAP isn't REL, check with planners!</td>

							@endif				        		
				    
				    @endforeach
				    </tbody>

				</table>
			</div>
		</div>
	</div>
</div>

@endsection

