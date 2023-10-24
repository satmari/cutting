@extends('app')

@section('content')

	
	<div class="row">
		<div class="text-center">
	    	<div class="text-center">
				<div class="panel panel-default">

			        		<div class="panel-heading">Search <b>paspuls</b> by skeda: <big><b>{{ $skeda }}</b></big></div>

			        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
			                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
			                </div>

			                <table class="table table-striped table-bordered tableFixHead" id="table-draggable2" 
			                data-export-types="['excel']"
			                data-show-export="true"
			                >
			                <!--
			                
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
			                    		<th >Paspul Roll</th>
			                            <th >SAP SU</th>

			                            <th >Material</th>
			                            <th >Color Desc</th>
			                            <th >Dye Lot</th>
			                            
			                            <th >Roll min Width [cm]</th>
			                            <th >Paspul Type</th>
			                            <th >Meters to Rewind [m]</th>
			                            <th >Meters Actual</th>
			                            <th >Uom</th>

			                            <th >PRO</th>
			                            <th >Destination</th>
			                            <th >SKU</th>

			                            <th >Koturi Width [mm]</th>
			                            <th >Koturi Width Tension [mm]</th>

			                            <th >Koturi Planned</th>
			                            <th >Koturi Actual</th>
			                            
			                            <th >Skeda Item Type</th>
			                            <th >Skeda</th>
			                            <th >Bin</th>
			                            <th >TPA Number</th>
			                            <th >Priority</th>
			                            
			                            <th></th>
			                            
			                            
			                    	</tr>
			                    </thead>
			                    <tbody class="connectedSortable_table searchable">
			                    
			                    @foreach ($data as $req)
			                        <tr class="ss" id="item[]={{ $req->id }}" style="border-top: 3px solid grey;
		                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
									box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
			                            
			                            <td class="">{{ $req->paspul_roll}}</td>
			            	    		<td>{{ $req->sap_su}}</td>

			            	    		<td>{{ $req->material}}</td>
			            	    		<td>{{ $req->color_desc}}</td>
			                            <td>{{ $req->dye_lot}}</td>
			                            
			            	    		<td>{{ round($req->width,3) }}</td>
			            	    		<td>{{ $req->paspul_type}}</td>
			            	    		<td>{{ round($req->rewound_length,3) }}</td>
			            	    		<td>{{ round($req->rewound_length_a,3) }}</td>
			            	    		<td>{{ $req->rewound_roll_unit_of_measure }}</td>

			            	    		<td style="width: 75px;">{{ $req->pro}}</td>
			            	    		<td style="width: 60px;">{{ $req->location_all}}</td>
			                            <td style="width: 120px;">{{ $req->sku}}</td>

			                            <td>{{ round($req->kotur_width,0) }}</td>
			                            <td>{{ round($req->kotur_width_without_tension,0) }}</td>

			            	    		<td>{{ round($req->kotur_planned,0) }}</td>
			            	    		<td>{{ round($req->kotur_actual,0) }}</td>
			                            
			                            <td>{{ $req->skeda_item_type }}</td>
			                            <td style="width: 120px;">{{ $req->skeda }}</td>
			                            <td>{{ $req->pasbin }}</td>

			                            <td>{{ $req->tpa_number }}</td>
			                         	<td class="
				                            @if ($req->priority == 6) ts_priority
				                            @elseif ($req->priority == 5) ss_priority
				                            @elseif ($req->priority == 4) fs_priority
				                            @elseif ($req->priority == 3) top_priority
						        	    	@elseif ($req->priority == 2) high_priority
						        	    	@endif
						        	    	">
						        	    	@if ($req->priority == 6)3rd shift
						        	    	@elseif ($req->priority == 5)2nd shift
						        	    	@elseif ($req->priority == 4)1st shift
						        	    	@elseif ($req->priority == 3)Top
						        	    	@elseif ($req->priority == 2)High
					        	    		@elseif ($req->priority == 1)Normal
						        	    	@endif</td>
										<td>
											<a href="{{ url('edit_paspul_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit paspul</a>	
										</td>										
			                        </tr>
			                        <tr style="border-bottom: 3px solid grey;
				                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
										box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
				                        ">
				                        <td  colspan="10" style="padding: 1px; text-align: left;">
				                        	@if ($req->comment_office != '')
				                        	<b>Comment office:</b>
				                        	<i>{{ $req->comment_office }}</i><br>
				                        	@endif
				                        	@if ($req->comment_operator != '')
				                        	<b>Comment operator:</b>
				                        	<i>{{ $req->comment_operator }}</i><br>
				                        	@endif
				                        </td>
				                        <td  colspan="2" style="padding: 1px; text-align: left;">
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
