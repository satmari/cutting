@extends('app')

@section('content')
<div class="container-fluid" >
	<div class="row">
		<div class="text-center">
			
				<!-- <div class="panel-heading">Plan matress: <b>{{$location}}</b></div> -->
				<br>
				<div class="btn-group plan_menu" width="width=100%">
				
					<a href="{{ url('plan_paspul/DELETED')}}" class="btn btn-default 
					@if ($location == 'DELETED') plan_menu_a @endif "
					><span class="glyphicon glyphicon-trash" ></span></a>
					 
					<a href="{{ url('plan_paspul/NOT_SET')}}" class="btn btn-default
					@if ($location == 'NOT_SET') plan_menu_a @endif "
					><span class="glyphicon glyphicon-list-alt">&nbsp;<b>NOT SET</b></span></a>
					
					<a href="{{ url('plan_paspul/PRW') }}" class="btn btn-default
					@if ($location == 'PRW') plan_menu_a @endif "
					><span class="glyphicon glyphicon-transfer">&nbsp;<b>PRW</b></span></a>

					<a href="{{ url('plan_paspul/PCO') }}" class="btn btn-default
					@if ($location == 'PCO') plan_menu_a @endif "
					><span class="glyphicon glyphicon-import">&nbsp;<b>PCO</b></span></a>

					<a href="{{ url('plan_paspul/COMPLETED') }}" style="color:black" class="btn btn-success
					@if ($location == 'COMPLETED') plan_menu_a @endif "
					><span class="glyphicon glyphicon-ok">&nbsp;<b>COMPLETED</b></span></a>

				</div>
				<br>
				<br>
				<a href="{{ url('plan_paspul/BOARD')}}" class="btn btn-default
				@if ($location == 'BOARD') plan_menu_a @endif"
				><span class="glyphicon  glyphicon-blackboard" aria-hidden="true"></span>&nbsp;Planning board</a>
				<!-- <a href="{{ url('plan_paspul_save/'.$location)}}" class="btn btn-default"
				><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">&nbsp;Save</span></a> -->
				<br>

			</div>
		</div>
	</div>
<br>

@if ($location == "BOARD")

	<div class="  col-md-1 8musk etara" style="width: 50%;text-align: center;">
		<span style="font-size: 20px"><b>NOT SET</b>&nbsp;&nbsp;&nbsp;</span> 
		<br><br>
			<!-- <div style="border: 1px solid #6f6f6f;margin-bottom: 5px;border-radius: 10px;background-color: #b1b0b066; "> -->
		<!-- </div> -->

		<ul id="sortable_p_1" class="connectedSortable_ul_1">
        	@foreach ($p_ns as $req1)
        	    <li class="ui-state-default" id="NS-{{ $req1->id }}" data-html="true" style="height: 95px !important; background: antiqu ewhite;text-align: left">
        	    	
        	    	<!-- <br>{{$req1->skeda}} -->
        	    	<!-- <br>color: {{$req1->color_desc}}
        	    	&nbsp;&nbsp;&nbsp;dye lot: {{$req1->dye_lot}}
        	    	<br>paspul type: {{$req1->paspul_type}}
        	    	&nbsp;&nbsp;&nbsp;kotur actual: {{round($req1->kotur_actual, 0)}}
        	    	<br>uom: {{$req1->rewound_roll_unit_of_measure}}
        	    	&nbsp;&nbsp;&nbsp;already rewound: {{round($req1->rewound_sum,0)}} -->

        	    	<table class="tab le">

						<tr style="text-align: left">
				            <td>
				            <a href="{{ url('plan_paspul_line1/'.$req1->id) }}"><span style="font-weight: bold;font-size: 15px;color:red;">{{$req1->paspul_roll}}</span>
        	    			</a>
		        	    	</td>
				            <td>Material:
				            <b>{{ $req1->material}}</b></td>
				            <td>
				            </td>
				        </tr>
				        <tr style="text-align: left; padding: 0;">
				        	<td>Color:
				            <b>{{ $req1->color_desc }}</b></td>
				            <td>Dye Lot:
				            <b>{{ $req1->dye_lot }}</b></td>
				            <td>Paspul Type:
				            <b>{{ $req1->paspul_type }}</b></td>
				        </tr>
				        <tr style="text-align: left; padding: 0;">
				            <td>Kotur Actual:
				            <b>{{ round($req1->kotur_actual, 0) }}</b></td>
				            <td>Unit of Measure:
				            <b>{{ $req1->rewound_roll_unit_of_measure }}</b></td>
				            <td>Stil to Rewound:
				            <b>{{ round($req1->rewound_length_a - $req1->rewound_sum, 1) }}  from {{ round($req1->rewound_length_a,1) }}</b></td>
				        </tr>
				        <tr style="text-align: left padding: 0;">
				        	@if($req1->rewound_roll_unit_of_measure == 'meter')
				        		<td>Mtr per pcs: <b>{{round($req1->unit_cons,2)}}</b></td>
				        	@else
				        		<td>Pcs per ploce: <b>{{round($req1->unit_cons,2)}}</b></td>
				        	@endif

				        	<td>Garments qty:
				        	@if($req1->rewound_roll_unit_of_measure == 'meter')
				        		@if ($req1->unit_cons != 0)
				        			<b>{{round( (($req1->rewound_length_a  - $req1->rewound_sum) * $req1->kotur_actual)/ $req1->unit_cons ,0) }}</b></td>
				        		@else
				        			<span sytle="color:red"><big><b>Missing mtr per pcs</b></big></span>
				        		@endif
				        		
				        	@else
				        		<b>{{round( (($req1->rewound_length_a  - $req1->rewound_sum) * $req1->kotur_actual)* $req1->unit_cons ,0) }}</b></td>
				        	@endif
				        	<td class="
	                            @if ($req1->priority == 7) tt_priority
	                            @elseif ($req1->priority == 6) ts_priority
	                            @elseif ($req1->priority == 5) ss_priority
	                            @elseif ($req1->priority == 4) fs_priority
	                            @elseif ($req1->priority == 3) top_priority
			        	    	@elseif ($req1->priority == 2) high_priority
			        	    	@endif
			        	    	">Priority: <b>
			        	    	@if ($req1->priority == 7)Test
			        	    	@elseif ($req1->priority == 6)3rd shift
			        	    	@elseif ($req1->priority == 5)2nd shift
			        	    	@elseif ($req1->priority == 4)1st shift
			        	    	@elseif ($req1->priority == 3)Top
			        	    	@elseif ($req1->priority == 2)Flash
		        	    		@elseif ($req1->priority == 1)Normal
			        	    	@endif	</b>
				        	</td>
				        </tr>
			    	</table>

    	    	</li>
			@endforeach	  
		</ul>
	</div>

	<div class="  col-md-1 8musk etara" style="width: 50%;text-align: center;">
		<span style="font-size: 20px"><b>PRW</b>&nbsp;&nbsp;&nbsp;</span> 
		<br><br>
		<!-- <div style="border: 1px solid #6f6f6f;margin-bottom: 5px;border-radius: 10px;background-color: #b1b0b066; ">
		</div> -->
		
		<ul id="sortable_p_2" class="connectedSortable_ul_1">
        	@foreach ($p_prw as $req1)
        	    <li class="ui-state-default" id="PRW-{{ $req1->id }}" data-html="true"  style="height: 95px !important; background: antiqu ewhite;text-align: left">
        	    	

        	    	<table class="tab le">
				        <tr style="text-align: left">
				            <td>
				            <a href="{{ url('edit_paspul_roll_line/'.$req1->id) }}"><span style="font-weight: bold;font-size: 15px;color:blue;" class="">
		        	    		{{$req1->paspul_rewound_roll}}</span>
		        	    	</a>
		        	    	</td>
				            <td>Material:
				            <b>{{ $req1->material}}</b></td>
				            <td>Bin:
				            <b>{{ $req1->pasbin}}</b>
				        	</td>
				        </tr>
				        <tr style="text-align: left; padding: 0; width: 33%; ">
				        	<td>Color:
				            <b>{{ $req1->color_desc }}</b></td>
				            <td>Dye Lot:
				            <b>{{ $req1->dye_lot }}</b></td>
				            <td>Paspul Type:
				            <b>{{ $req1->paspul_type }}</b></td>
				        </tr>
				        <tr style="text-align: left; padding: 0; width: 33%; ">
				            <td>Kotur Planned:
				            <b>{{ round($req1->kotur_planned, 0) }}</b></td>
				            <td>Unit of Measure:
				            <b>{{ $req1->rewound_roll_unit_of_measure }}</b></td>
				            <td>Stil to Rewound:
				            <b>{{ round($req1->rewound_length_partialy, 1) }}</b></td>
				        </tr>
				        <tr style="text-align: left padding: 0; width: 33%; ">
				        	@if($req1->rewound_roll_unit_of_measure == 'meter')
				        		<td>Mtr per pcs: <b>{{round($req1->unit_cons,2)}}</b></td>
				        	@else
				        		<td>Pcs per ploce: <b>{{round($req1->unit_cons,2)}}</b></td>
				        	@endif

				        	<td>Garments qty:
				        	@if($req1->rewound_roll_unit_of_measure == 'meter')
				        		@if ($req1->unit_cons != 0)
				        			<b>{{round( (($req1->rewound_length_partialy) * $req1->kotur_planned)/ $req1->unit_cons ,0) }}</b></td>
				        		@else
				        			<span sytle="color:red">Missing mtr per pcs</span>
				        		@endif
				        	@else
				        		<b>{{round( (($req1->rewound_length_partialy) * $req1->kotur_planned)* $req1->unit_cons ,0) }}</b></td>
				        	@endif
				        	<td class="
					                            @if ($req1->priority == 7) tt_priority
					                            @elseif ($req1->priority == 6) ts_priority
					                            @elseif ($req1->priority == 5) ss_priority
					                            @elseif ($req1->priority == 4) fs_priority
					                            @elseif ($req1->priority == 3) top_priority
							        	    	@elseif ($req1->priority == 2) high_priority
							        	    	@endif
							        	    	">Priority: <b>
							        	    	@if ($req1->priority == 7)Test
							        	    	@elseif ($req1->priority == 6)3rd shift
							        	    	@elseif ($req1->priority == 5)2nd shift
							        	    	@elseif ($req1->priority == 4)1st shift
							        	    	@elseif ($req1->priority == 3)Top
							        	    	@elseif ($req1->priority == 2)Flash
						        	    		@elseif ($req1->priority == 1)Normal
							        	    	@endif	</b>
				        	</td>
				        </tr>
			    	</table>

    	    	</li>
			@endforeach	  
		</ul>
	</div>

@elseif ($location == 'PRW')
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
			                    		<!-- <th >Position</th> -->
			                            <th >Paspul rewound roll</th>
			                            
			                            <th >PRO</th>
			                            <th >Destination</th>
			                            <th >SKU</th>

			                            <th >Paspul type</th>
			                            <th >Kotur width [mm]</th>
			                            <th >Roll min width [cm]</th>
			                            <th >Rewound length actual [m]</th>
			                            <th >Unit of measure</th>
			                            <th >Planned kotur [kom]</th>

			                            @if ($location == 'COMPLETED')
			                            <th >Kotur Qty [kom]</th>
			                            @endif
			                            
			                            <th >Material</th>
			                            <th >Color Desc</th>
			                            <th >Dye lot</th>

			                            <th >Skeda</th>

			                            <th >Bin</th>
			                            <th >Priority</th>
			                            <th >Keep wastage</th>
			                            <th >Rewinding method</th>
			                            <th>Kotur width w/out Tension [mm]</th>
			                            <th></th>
			                            <!-- <th></th> -->
			                            	
			                       	</tr>
			                    </thead>
			                    <tbody class="connectedSortable_table searchable" 
				                    @if ($location != "PCO")
				                        id="sortab le11"
				                    @endif
				                    >

				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }}" style="border-top: 3px solid grey;
					                        -webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
				                            {{-- <td>{{ $req->position}}</td> --}}
				                            <td>{{ $req->paspul_rewound_roll}}</td>

				                            <td style="width: 75px;">{{ $req->pro}}</td>
				                            <td style="width: 60px;">{{ $req->location_all}}</td>
				                            <td style="width: 120px;">{{ $req->sku}}</td>

				                            <td>{{ $req->paspul_type}}</td>
				                            <td>{{ round($req->kotur_width,3) }}</td>

				                            <td>{{ round($req->width,3) }}</td>
				                            <td><big><b>{{ round($req->rewound_length_partialy, 2)}}</b></big></td>
				                            <td>{{ $req->rewound_roll_unit_of_measure}}</td>
				                            <td>{{ round($req->kotur_planned,3) }}</td>
				                            
				                            @if ($location == 'COMPLETED')
				                            <td><big><b>{{ round($req->kotur_partialy,3) }}</b></big></td>
				                            @endif
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td>{{ $req->dye_lot}}</td>

				                            <td>{{ $req->skeda}}</td>
				                            <td>{{ $req->pasbin }}</td>
				                            <td class="
					                            @if ($req->priority == 7) tt_priority
					                            @elseif ($req->priority == 6) ts_priority
					                            @elseif ($req->priority == 5) ss_priority
					                            @elseif ($req->priority == 4) fs_priority
					                            @elseif ($req->priority == 3) top_priority
							        	    	@elseif ($req->priority == 2) high_priority
							        	    	@endif
							        	    	">
							        	    	@if ($req->priority == 7)Test
							        	    	@elseif ($req->priority == 6)3rd shift
							        	    	@elseif ($req->priority == 5)2nd shift
							        	    	@elseif ($req->priority == 4)1st shift
							        	    	@elseif ($req->priority == 3)Top
							        	    	@elseif ($req->priority == 2)Flash
						        	    		@elseif ($req->priority == 1)Normal
							        	    	@endif</td>
											<td>
												@if ($req->tpa_number == NULL)NO
								        	    @else YES
								        	    @endif
								        	</td>
								        	<td>{{ $req->rewinding_method}}</td>
				                            <td>{{ round($req->kotur_width_without_tension,3) }}</td>
											<td>
												<a href="{{ url('edit_paspul_roll_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit paspul roll</a>
												<br>

												<a href="{{ url('remove_paspul_roll_line/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>Delete</a>
											</td>

				                        </tr>
				                        <tr style="border-bottom: 3px solid grey;
						                    -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
						                    ">
					                        <td  colspan="16" style="padding: 5px; text-align: left;">
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
			               
@elseif ($location == 'PCO' OR $location == 'COMPLETED')
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
		                    		<!-- <th >Position</th> -->
		                            <th >Paspul rewound roll</th>
		                            
		                            <th >PRO</th>
		                            <th >Destination</th>
		                            <th >SKU</th>

		                            <th >Paspul type</th>
		                            <th >Kotur width [mm]</th>
		                            <th >Roll min width [cm]</th>
		                            <th >Rewound length actual [m]</th>
		                            <th >Unit of measure</th>
		                            <th >Planned kotur [kom]</th>

		                            @if ($location == 'COMPLETED')
		                            <th >Kotur Qty [kom]</th>
		                            @endif
		                            
		                            <th >Material</th>
		                            <th >Color Desc</th>
		                            <th >Dye lot</th>

		                            <th >Skeda</th>

		                            <th >Bin</th>
		                            <th >Priority</th>
		                            <th >Keep wastage</th>

		                            <th >Rewinding method</th>

		                            <th >Kotur width w/out Tension [mm]</th>

		                            <th></th>
		                    	</tr>
		                    </thead>
		                    <tbody class="connectedSortable_table searchable" 
			                    @if ($location != "PCO")
			                        id="sortab le11"
			                    @endif
			                    >

			                    @foreach ($data as $req)
			                        <tr class="ss" id="item[]={{ $req->id }}" style="border-top: 3px solid grey;
				                        -webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
										box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
			                            
			                            {{-- <td>{{ $req->position}}</td> --}}
			                            <td>{{ $req->paspul_rewound_roll}}</td>

			                            <td style="width: 75px;">{{ $req->pro}}</td>
			                            <td style="width: 60px;">{{ $req->location_all}}</td>
			                            <td style="width: 120px;">{{ $req->sku}}</td>

			                            <td>{{ $req->paspul_type}}</td>
			                            <td>{{ round($req->kotur_width,3) }}</td>

			                            <td>{{ round($req->width,3) }}</td>
			                            <td><big><b>{{ round($req->rewound_length_partialy, 2)}}</b></big></td>
			                            <td>{{ $req->rewound_roll_unit_of_measure}}</td>
			                            <td>{{ round($req->kotur_planned,3) }}</td>
			                            
			                            @if ($location == 'COMPLETED')
			                            <td><big><b>{{ round($req->kotur_partialy,3) }}</b></big></td>
			                            @endif
			                            <td>{{ $req->material}}</td>
			                            <td>{{ $req->color_desc}}</td>
			                            <td>{{ $req->dye_lot}}</td>

			                            <td>{{ $req->skeda}}</td>
			                            <td>{{ $req->pasbin }}</td>
			                            <td class="
				                            @if ($req->priority == 7) tt_priority
				                            @elseif ($req->priority == 6) ts_priority
				                            @elseif ($req->priority == 5) ss_priority
				                            @elseif ($req->priority == 4) fs_priority
				                            @elseif ($req->priority == 3) top_priority
						        	    	@elseif ($req->priority == 2) high_priority
						        	    	@endif
						        	    	">
						        	    	@if ($req->priority == 7)Test
						        	    	@elseif ($req->priority == 6)3rd shift
						        	    	@elseif ($req->priority == 5)2nd shift
						        	    	@elseif ($req->priority == 4)1st shift
						        	    	@elseif ($req->priority == 3)Top
						        	    	@elseif ($req->priority == 2)Flash
					        	    		@elseif ($req->priority == 1)Normal
						        	    	@endif</td>
										<td>
											@if ($req->tpa_number == NULL)NO
							        	    @else YES
							        	    @endif
							        	</td>
							        	<td>{{ $req->rewinding_method}}</td>
			                            <td>{{ round($req->kotur_width_without_tension,3) }}</td>
										<td>
											@if ($location == 'COMPLETED')
											 <a href="{{ url('paspul_change_kotur_qty/'.$req->id) }}" class="btn btn-danger btn-xs center-block">
											Change kotur qty</a>
											@endif
										</td>

			                        </tr>
			                        <tr style="border-bottom: 3px solid grey;
				                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
										box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
				                        ">
				                        <td  colspan="16" style="padding: 5px; text-align: left;">
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
			               
@else 
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
						        		<th >Paspul Roll</th>
						                <th >SAP SU</th>

						                <th >Material</th>
						                <th >Color Desc</th>
						                <th >Dye Lot</th>
						                
						                <th >Roll min Width [cm]</th>
						                <th >Paspul Type</th>
						                <th >Meters to Rewind [m]</th>
						                <th >Already planned</th>
						                <th >Still to rewound</th>
						                
						                <th >Rewound %</th>
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
						                <th>Planned pcs</th>
			                            <th>Cut pcs</th>
						                
						                <th></th>
						                <!-- <th></th> -->
						                <!-- <th></th> -->
						        	</tr>
						        </thead>
						        <tbody class="connectedSortable_table searchable" 
							        @if (($location == "PRW") OR ($location == "PCO"))
							            id="sortable11"
							        @endif
							        >
						        
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
								    		<td><b>{{ round($req->rewound_length,3) }}</b></td>
								    		<td><b>{{ round($req->rewound_length_p,3) }}</b></td>
								    		@if ($req->rewound_sum != 0)
								    			<td><b>{{ round($req->rewound_length_a-$req->rewound_sum,3) }}</b></td>
								    		@else
								    			<td></td>
								    		@endif


								    		@if ($req->rewound_length_a != 0)
								    			<td><big><b>{{ round($req->rewound_sum / $req->rewound_length *100,0) }} %</b></big></td>
								    		@else 
								    			<td></td>
								    		@endif

								    		<td>{{ $req->rewound_roll_unit_of_measure }}</td>

								    		<td style="width: 75px;">{{ $req->pro}}</td>
								    		<td style="width: 60px;">{{ $req->location_all}}</td>
							                <td style="width: 120px;">{{ $req->sku}}</td>

							                <td>{{ round($req->kotur_width,0) }}</td>
							                <td>{{ round($req->kotur_width_without_tension,0) }}</td>

								    		<td>{{ round($req->kotur_planned,0) }}</td>
								    		<td>{{ round($req->kotur_actual,0) }}</td>
							                
							                <td>{{ $req->skeda_item_type }}</td>
							                <td>{{ $req->skeda }}</td>
							                <td>{{ $req->pasbin }}</td>

							                <td>{{ $req->tpa_number }}</td>
							             	<td class="
							                        @if ($req->priority == 7) tt_priority
							                        @elseif ($req->priority == 6) ts_priority
							                        @elseif ($req->priority == 5) ss_priority
							                        @elseif ($req->priority == 4) fs_priority
							                        @elseif ($req->priority == 3) top_priority
								        	    	@elseif ($req->priority == 2) high_priority
								        	    	@endif
								        	    	">
								        	    	@if ($req->priority == 7)Test
								        	    	@elseif ($req->priority == 6)3rd shift
								        	    	@elseif ($req->priority == 5)2nd shift
								        	    	@elseif ($req->priority == 4)1st shift
								        	    	@elseif ($req->priority == 3)Top
								        	    	@elseif ($req->priority == 2)Flash
							        	    		@elseif ($req->priority == 1)Normal
								        	    	@endif</td>
							                <!-- <td>{{ $req->call_shift_manager }}</td> -->
							                <!-- <td>{{ $req->rewinding_method }}</td> -->
							                <td>{{ round($req->sum_pcs_load_spread_by_lot_skeda,0) }}</td>
						        	    	<td>{{ round($req->sum_pcs_cut_comp_by_lot_skeda,0) }}</td>	
							              	<td>
												@if ($location == 'NOT_SET')
													<a href="{{ url('plan_paspul_line1/'.$req->id) }}" class="btn btn-info btn-xs center-block">Plan paspul</a>
													<!-- <a href="{{ url('plan_paspul_line1/'.$req->id) }}" class="btn btn-info btn-xs center-block">Plan paspul (new)</a> -->
												@else
													<a href="{{ url('plan_paspul_line1/'.$req->id) }}" class="btn btn-info btn-xs center-block" disabled>Plan paspul</a>
													<!-- <a href="{{ url('plan_paspul_line1/'.$req->id) }}" class="btn btn-info btn-xs center-block" disabled>Plan paspul (new)</a> -->
												@endif
												<br>
												@if (($location == 'DELETED') OR ($location == 'COMPLETED'))
													<a href="{{ url('remove_paspul_line/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>Delete</a>
												@else 
													@if ($req->rewound_sum != 0)
														<a href="{{ url('remove_paspul_line/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>Delete</a>
													@else
														<a href="{{ url('remove_paspul_line/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a>
													@endif
												@endif
											</td>
											<!-- <td>
												@if (($location == 'DELETED') OR ($location == 'NOT_SET'))
													<a href="{{ url('edit_paspul_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block" disabled>Edit paspul</a>
												@else 
													<a href="{{ url('edit_paspul_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit paspul</a>
												@endif
											</td> -->
											<!-- <td>
												
											</td> -->
							            </tr>

							            @if ($location != 'NOT_SET')

							                <tr style="border-bottom: 3px solid grey;
							                    -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
												box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
							                    ">
							                    <td  colspan="20" style="padding: 1px; text-align: left;">
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
							                @endif

							        @endforeach
						        
						        </tbody>
						</table>

@endif      
				</div>
	        </div>
	    </div>
	</div>


</div>
    
@endsection
