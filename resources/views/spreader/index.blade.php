@extends('app')

@section('content')

{{ header( "refresh:60;url=/cutting/spreader" ) }}



<style>
  .progress-container {
    width: 100%;
    height: 30px;
    background-color: #ddd;
    position: relative;
    display: flex;
  }
 
  .progress-bar {
    height: 100%;
    background-color: #4CAF50;
    text-align: center;
    line-height: 30px;
    color: white;
    font-size: 20px;
  }
 
  .checkpoint {
    position: absolute;
    height: 60%;
    width: 2px;
    background-color: black;
  }
 
  .checkpoint-label {
    position: absolute;
    top: 16px;
    transform: translateX(-50%);
    font-size: 12px;
  }
</style>


<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

		            	@if ((Auth::user()->name == 'MM11') OR (Auth::user()->name == 'MM12') OR (Auth::user()->name == 'MM13')
		            	OR (Auth::user()->name == 'MS11') OR (Auth::user()->name == 'MS12') OR (Auth::user()->name == 'MS13')
		            	OR (Auth::user()->name == 'MS21') OR (Auth::user()->name == 'MS22') OR (Auth::user()->name == 'MS23')
		            	OR (Auth::user()->name == 'MS31') OR (Auth::user()->name == 'MS32') OR (Auth::user()->name == 'MS33')
		            	)
		            		@if (config('app.global_variable') == 'gordon')
			            	<div class="input-group">
			            		<span class="input-group-addon" style="">
			            			Efficiency1: &nbsp; <big><b>{{ $eff}} </b></big>
			            		
			            			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
			            			Efficiency2: &nbsp; <big><b>{{ $eff2}} </b></big>
			            		</span>
			            		
			                </div>
			                @else
			                @endif

		                @else
		                	@if (config('app.global_variable') == 'gordon')
			                <div class="input-group">
			            		<!-- <span class="input-group-addon">
			            			Efficiency: &nbsp; &nbsp; <big><b>{{ $eff}} </b></big>
			            		</span> -->
									
									@if( $isSaturday == True)

									<div class="progress-container">
									<div id="progress-bar" class="progress-bar">0m</div>
									<div class="checkpoint-label" style="left: 2%;color:red">Saturday</div>
									<div class="checkpoint" style="left: 44%;"></div>
									<div class="checkpoint-label" style="left: 44%;color:red">2200m = 100 RSD</div>
									<div class="checkpoint" style="left: 52%;"></div>
									<div class="checkpoint-label" style="left: 52%;color:red">2600m = 200 RSD</div>
									<div class="checkpoint" style="left: 60%;"></div>
									<div class="checkpoint-label" style="left: 60%;color:red">3000m = 400 RSD</div>
									<div class="checkpoint" style="left: 68%;"></div>
									<div class="checkpoint-label" style="left: 68%;color:red">3400m = 800 RSD</div>
									<div class="checkpoint" style="left: 100%;"></div>
									<div class="checkpoint-label" style="left: 100%;"></div>

									@else

									<div class="progress-container">
									<div id="progress-bar" class="progress-bar">0m</div>
									<div class="checkpoint" style="left: 52%;"></div>
									<div class="checkpoint-label" style="left: 52%;">2600m = 100 RSD</div>
									<div class="checkpoint" style="left: 60%;"></div>
									<div class="checkpoint-label" style="left: 60%;">3000m = 200 RSD</div>
									<div class="checkpoint" style="left: 70%;"></div>
									<div class="checkpoint-label" style="left: 70%;">3500m = 400 RSD</div>
									<div class="checkpoint" style="left: 78%;"></div>
									<div class="checkpoint-label" style="left: 78%;">3900m = 800 RSD</div>
									<div class="checkpoint" style="left: 100%;"></div>
									<div class="checkpoint-label" style="left: 100%;"></div>

									@endif
			            		
			            			<!-- <div> -->
			            			
									<!-- </div> -->

									<script>
									  function updateProgressBar(meters) {
									  	// console.log('test');
									    const progressBar = document.getElementById('progress-bar');
									    let percentage = (meters / 5000) * 100;
									    if (percentage > 100) {
									      percentage = 100;
									    }
									    progressBar.style.width = percentage + '%';
									    progressBar.innerText = meters + 'm';
									  }
									 
									    // Example usage with a value from PHP
									  <?php
									    $metersDone = (int)$eff; // Example value from PHP
									    echo "updateProgressBar($metersDone);";
									  ?>

									</script>
				            		<!-- </div> -->
				                </div>
				            @else
			                @endif
		                @endif

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
		                    		
		                       		<!-- <th >G-bin</th> -->
		                            <!-- <th >Mattress</th> -->
		                            @if ((Auth::user()->name == 'MM11') OR (Auth::user()->name == 'MM12') OR (Auth::user()->name == 'MM13'))
		                            	<th >Created_at</th>
		                            	<th >Mattress</th>
		                            @else 
		                            	<th >Pos</th>
		                            	@if (config('app.global_variable') == 'gordon')
			                            <th >G-bin</th>
			                            @else
			                            @endif
			                            <th >Skeda</th>
		                            @endif
		                            <th >Marker</th>
		                            <th >Marker Length [m]</th>
		                            <th >Extra [cm]</th>
		                            <th >Mattress Width [cm]</th>
		                            <th >Layers</th>
		                            <th >Req. time [min]</th>
		                            <!-- <th >Layers in last shift</th> -->
		                            @if (config('app.global_variable') == 'gordon')
		                            <th >PRO</th>
		                            <th >Destination</th>
		                            @else
		                            @endif
		                            <th >SKU</th>
		                            <th >Material</th>
		                            <th >Dye Lot</th>
		                            <th >Color Desc</th>
		                            <th >Actual Cons [m]</th>
		                            <th >Spreading Method</th>
		                            <th >Pcs per Bundle</th>
		                            <th >Bottom Paper</th>
		                            <th >Overlapping</th>
		                            <th >Priority</th>
		                            <th >Status</th>
		                            <th >Keep wastage</th>
		                            <th >Last mattress</th>
		                            @if (Auth::user()->name == 'MM11')
		                            <th >Layer limit</th>
		                            @endif
		                            <th></th>
		                            
		                            <th></th>
		                            <!-- <th></th> -->
		                            <!-- <th></th> -->
		                    	</tr>
		                    </thead> 
		                    <tbody class="connectedSortable_t able searchable" id="sortable 10">
		                    	<!-- <tr>
		                    		<th class=""><div><span>position</div></span></th>
		                            <th class=""><div><span>mattress</div></span></th>
		                            <th class=""><div><span>material</div></span></th>
		                            <th class=""><div><span>dye_lot</div></span></th>
		                            <th></th>
		                    	</tr> -->
		                    @foreach ($data as $req)
		                        <tr class="ss" id="item[]={{ $req->id }}" style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
		                            
		                            <!-- <td class=""><span>{{ $req->g_bin}}</span></td> -->
				        	    	<!-- <td class=""><span>{{ $req->mattress}}</span></td> -->

				        	    	@if ((Auth::user()->name == 'MM11') OR (Auth::user()->name == 'MM12') OR (Auth::user()->name == 'MM13'))
				        	    		<td class=""><span>{{ substr($req->created_at,0,16)}}</span></td>
		                            	<td class=""><span>{{ $req->mattress}}</span></td>
		                            	
		                            @else
		                            	<td>{{ $req->position}}</td>
		                            	@if (config('app.global_variable') == 'gordon')
		                            	<td class=""><span>{{ $req->g_bin}}</span></td>
		                            	@else
		                            	@endif
		                            	<td class=""><span>{{ $req->skeda}}</span></td>
		                            @endif

		                            <td>{{ $req->marker_name}}</td>
		                            <td>{{ round($req->marker_length,3)}}</td>
		                            <td>{{ round($req->extra,0)}}</td>
		                            @if (($req->skeda_item_type == 'MB') OR ($req->skeda_item_type == 'MW'))
		                            	<td>{{ round($req->width_theor_usable,3)}}</td>
		                            @else
		                            	<td>{{ round($req->marker_width,3)}}</td>
		                            @endif
		                            <td>{{ round($req->layers_a,0)}}</td>
		                            <td>{{ round($req->req_time,2)}}</td>
		                            <!-- <td></td> -->
		                            @if (config('app.global_variable') == 'gordon')
		                            <td style="width: 75px;">{{ $req->pro}}</td>
		                            <td style="width: 60px;">{{ $req->location_all}}</td>
		                            @else
		                            @endif
		                            <td style="min-width: 138px; max-width: 140px;">{{ $req->sku}}</td>
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->dye_lot}}</td>
		                            <td>{{ $req->color_desc}}</td>
		                            <td>{{ round($req->cons_actual,3)}}</td>
		                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
		                            <td >{{ round($req->pcs_bundle,0)}}</td>
			                        <td>{{ $req->bottom_paper}}</td>
			                        <td>{{ $req->overlapping}}</td>
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
		                            @if ($req->tpp_mat_keep_wastage == 1)
		                            	<td>YES - {{$req->tpa_number}}</td>
		                            @else
		                            	<td>NO</td>
		                            @endif
		                            <td>
		                            	@if($req->last_mattress == 1)
		                            		<span class='last_mattress'>YES</span>
		                            	@else
		                            		NO
		                            	@endif
		                            </td>
                            
		                            @if ( Auth::user()->name == 'MM11')
		                            <td>{{$req->layer_limit}}</td>
		                            
		                            @endif

									<td>
										@if ($req->status == 'TO_LOAD')

											@if ($req->status == 'ON_HOLD')
												<a href="{{ url('mattress_to_load/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled >
													Load mattress
												</a>
											@else
												<a href="{{ url('mattress_to_load/'.$req->id) }}" class="btn btn-info btn-xs center-block" >
													Load mattress
												</a>
											@endif


										@else

											@if ($req->status == 'ON_HOLD')
												<a href="{{ url('mattress_to_spread/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>
													Spread mattress
												</a>
											@else
												<a href="{{ url('mattress_to_spread/'.$req->id) }}" class="btn btn-warning btn-xs center-block"  >
													Spread mattress
												</a>
											@endif

										@endif
									</td>
									<!-- <td>
										@if ($req->status == 'TO_SPREAD')
											<a href="{{ url('mattress_to_unload/'.$req->id) }}" class="btn btn-info btn-xs center-block">Unload mattress</a>
										@endif	
									</td> -->
									<td>
										@if ($req->status == 'ON_HOLD')
											<a href="{{ url('other_functions/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled> Other functions</a><br>
										@else
											<a href="{{ url('other_functions/'.$req->id) }}" class="btn btn-default btn-xs center-block" > Other functions</a><br>
										@endif
										
										<a href="{{ url('request_material/'.$req->id) }}" class="btn btn-default btn-xs center-block">Material request</a>
									</td>
		                        </tr>
		                        <tr style="border-bottom: 3px solid grey;
			                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
									box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
			                        ">
			                        <td></td>
			                        @if (Auth::user()->name == 'MM11')
			                        	<td class="">
			                        	@if ($req->g_bin_orig != '')
			                        		<span>F gbin: {{ $req->g_bin_orig}}</span>
			                        	@endif
			                        	</td>
			                        <td  colspan="20" style="padding: 5px; text-align: left;">
			                        @else 
			                        	<td class="">
			                        	@if ($req->g_bin_orig != '')
			                        		<span>F gbin: {{ $req->g_bin_orig}}</span>
			                        	@endif
			                        	</td>
			                        <td  colspan="19" style="padding: 5px; text-align: left;">
			                        @endif
			                        	@if ($req->comment_office != '')
			                        	<b>Comment office:</b>
			                        	<i>{{ $req->comment_office }}</i><br>
			                        	@endif
			                        	@if ($req->comment_operator != '')
			                        	<b>Comment operator:</b>
			                        	<i>{{ $req->comment_operator }}</i><br>
			                        	@endif
										@if ($req->standard_comment != '')
					                    <b>Material comment :</b>
					                    <i>{{ $req->standard_comment }}</i><br>
					                    @endif
			                        	
			                        </td>
			                        <td  colspan="2" style="padding: 1px; text-align: left;">
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