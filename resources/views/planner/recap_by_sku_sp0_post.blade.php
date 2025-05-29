@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

		            	<div class="panel-heading">Search <b>mattresses</b> in SP0 by SKU: <big><b>{{ $sku }}</b></big></div>
		        	
		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>
		                <table class="table table-striped table-bordered tableFixHead" id="table-draggable2 sort" 
		                data-show-export="true"
		                data-export-types="['excel']"
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
			                       		<!-- <th >Position</th> -->
			                       		<th >G-bin</th>
			                            <th >Mattress</th>
			                            <th >Marker</th>
			                            <th >Marker Length [m]</th>
			                            <th >Extra [cm]</th>
			                            <th >Mattress Width [cm]</th>
			                            <th >Layers Planned</th>
			                            <th >Layers Actual</th>
			                            <th >PRO</th>
			                            <th >Destination</th>
			                            <th >SKU</th>
			                            <th >Material</th>
			                            <th >Dye Lot</th>
			                            <th >Color Desc</th>
			                            <th >Skeda</th>
			                            <th >Actual Cons [m]</th>
			                            <th >Spreading Method</th>
			                            <th >Pcs per Bundle</th>
			                            <th >Overlapping</th>
			                            <!-- <th >Shift Manager Needed</th>
			                            <th ><span class="glyphicon glyphicon-text-size">&nbsp; &nbsp;<b>Test Marker</b></span></th> -->
			                            <th >Location</th>
			                            <th >Priority</th>
			                            <th >Status</th>
			                            <th></th>
			                            <!-- <th></th> -->
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable">
				                    	
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
				                            {{--<td>{{ $req->position }}</td>--}}
				                            <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
				                            <td style="min-width: 200px;">{{ $req->marker_name}}</td>
				                            <td>{{ round($req->marker_length,3)}}</td>
				                            <td>{{ round($req->extra,0)}}</td>
				                            @if (($req->skeda_item_type == 'MB') OR ($req->skeda_item_type == 'MW'))
				                            	<td>{{ round($req->width_theor_usable,3)}}</td>
				                            @else
				                            	<td>{{ round($req->marker_width,3)}}</td>
				                            @endif
				                            <td>{{ round($req->layers,0)}}</td>
				                            <td>{{ round($req->layers_a,0)}}</td>
				                            <td style="width: 75px;">{{ $req->pro}}</td>
				                            <td style="width: 60px;">{{ $req->location_all}}</td>
				                            <td style="min-width: 120px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="min-width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>

				                            <td>{{ $req->location}}</td>
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
				                            <td>{{ $req->status}}</td>
				                            
				                            <td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a><br>
																						
				                            	<a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
				                            		@if ($req->printed_nalog == NULL) 
				                            		0
				                            		@else
				                            		{{$req->printed_nalog}}
				                            		@endif
				                            		)</i></a>
				                            	
											</td>
											

				                        </tr>
				                        
				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	
					                        <td  colspan="18" style="padding: 5px; text-align: left;">
					                        	@if ($req->comment_office != '')
					                        	<b>Comment office:</b>
					                        	<i>{{ $req->comment_office }}</i><br>
					                        	@endif
					                        	@if ($req->comment_operator != '')
					                        	<b>Comment operator:</b>
					                        	<i>{{ $req->comment_operator }}</i><br>
					                        	@endif
					                        	
					                        </td>
					                        <td  colspan="3" style="padding: 1px; text-align: left;">
					                        	@if ($req->call_shift_manager == 1 )
					                        		<b><span class="glyphicon glyphicon-earphone"></span>&nbsp; &nbsp;<b>Call shift manager</b></b>
					                        	@endif
					                        	@if ($req->test_marker == 1)
					                        		<br><b><span class="glyphicon glyphicon-text-size"></span>&nbsp; &nbsp;<b>Test Marker</b></b>
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
