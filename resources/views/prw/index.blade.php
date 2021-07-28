@extends('app')

@section('content')

{{ header( "refresh:60;url=/cutting" ) }}
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

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
			                    		<th >Position</th>
			                            <th >Paspul roll</th>
			                            <th >SAP SU</th>
			                            <th >Paspul type</th>
			                            <th >Roll width (cm)</th>
			                            <th >Rewinding method</th>
			                            <th >Rewound length</th>
			                            <th >Material</th>
			                            <th >Color desc</th>
			                            <th >Dye lot</th>
			                            <th >Unit of measure</th>
			                            <th >Skeda</th>
			                            <th >Bin</th>
			                            <th >Priority</th>
			                            <th></th>
			                            
			                    	</tr>
			                    </thead>
			                    <tbody class="connectedSortable_table searchable" 
			                    @if ($location != "PRW")
			                        id="sortable11"
			                    @endif
			                    >
			                    	
			                    @foreach ($data as $req)
			                        <tr class="ss" id="item[]={{ $req->id }}" style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
			                            
			                            <td>{{ $req->position}}</td>
			                            <td>{{ $req->paspul_roll}}</td>
			                            <td>{{ $req->sap_su}}</td>
			                            <td>{{ $req->paspul_type}}</td>
			                            <td>{{ round($req->width,3) }}</td>
			                            <td>{{ $req->rewinding_method}}</td>
			                            <td>{{ round($req->rewound_length_a,3) }}</td>
			                            <td>{{ $req->material}}</td>
			                            <td>{{ $req->color_desc}}</td>
			                            <td>{{ $req->dye_lot}}</td>
			                            <td>{{ $req->rewound_roll_unit_of_measure}}</td>
			                            <td>{{ $req->skeda }}</td>
			                            <td>{{ $req->pasbin }}</td>
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
											<a href="{{ url('paspul_prw/'.$req->id) }}" class="btn btn-danger btn-xs center-block">
											Rewound paspul roll</a>
										</td>

		                        </tr>
		                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td  colspan="14" style="padding: 5px; text-align: left;">
					                        	@if ($req->comment_office != '')
					                        	<b>Comment office:</b>
					                        	<i>{{ $req->comment_office }}</i><br>
					                        	@endif
					                        	@if ($req->comment_operator != '')
					                        	<b>Comment operator:</b>
					                        	<i>{{ $req->comment_operator }}</i><br>
					                        	@endif
					                        </td>
					                        <td  colspan="1" style="padding: 1px; text-align: left;">
					                        	@if ($req->call_shift_manager == 1 )
					                        		<b><span class="glyphicon glyphicon-earphone"></span>&nbsp; &nbsp;<b>Call shift manager</b></b>
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