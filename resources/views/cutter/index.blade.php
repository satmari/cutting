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
		                       		<th >G-bin</th>
		                            <th >Mattress</th>
		                            <th >Marker</th>
		                            <th >Marker Length [m]</th>
		                            <th >Extra [cm]</th>
		                            <th >Marker Width [cm]</th>
		                            <th >Layers</th>
		                            <th >Layers Actual</th>
		                            <!-- <th >PRO</th> -->
		                            <!-- <th >SKU</th> -->
		                            <th >Material</th>
		                            <th >Dye Lot</th>
		                            <th >Color Desc</th>
		                            <!-- <th >Skeda</th> -->
		                            <!-- <th >Planned Cons [m]</th> -->
		                            <!-- <th >Spreading Method</th> -->
		                            <!-- <th >Pcs per Bundle</th> -->
		                            <!-- <th >Bottom Paper</th> -->
		                            <th >All pro for Subotica</th>
		                            <th >Priority</th>
		                            <th >Status</th>
		                            <th >Keep wastage</th>
		                            <th >Layers partial</th>
			                        
		                            <th></th>
		                            <th></th>
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
		                            
		                            {{--<td>{{ $req->position }}</td>--}}
		                            <td class=""><span>{{ $req->g_bin}}</span></td>
				        	    	<td class=""><span>{{ $req->mattress}}</span></td>
		                            <td>{{ $req->marker_name}}</td>
		                            <td>{{ round($req->marker_length,3)}}</td>
		                            <td>{{ round($req->extra,0)}}</td>
		                            <td>{{ round($req->marker_width,3)}}</td>
		                            <td>{{ round($req->layers,0)}}</td>
		                            <td>{{ round($req->layers_a,0)}}</td>
		                            {{--<td style="width: 75px;">{{ $req->pro}}</td>--}}
		                            {{--<td style="width: 120px;">{{ $req->sku}}</td>--}}
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->dye_lot}}</td>
		                            <td>{{ $req->color_desc}}</td>

		                            @if ($req->all_pro_for_main_plant == 1)
		                            	<td>YES</td>
		                            @else
		                            	<td>NO</td>
		                            @endif
		                            
		                           <td class="
		                            @if ($req->priority == 3) top_priority
				        	    	@elseif ($req->priority == 2) high_priority
				        	    	@endif
				        	    	">
				        	    	@if ($req->priority == 3)Top
				        	    	@elseif ($req->priority == 2)High
			        	    		@elseif ($req->priority == 1)Normal
				        	    	@endif</td>
		                            <td>{{ $req->status}}</td>
		                            
		                            @if ($req->tpp_mat_keep_wastage == 1)
		                            	<td style="background: yellowgreen;">YES</td>
		                            @else
		                            	<td>NO</td>
		                            @endif

		                            <td>{{ round($req->layers_partial,0) }}</td>

									<td>
										@if ($req->status == "TO_CUT")
											<a href="{{ url('mattress_to_cut/'.$req->id) }}" class="btn btn-info btn-xs center-block">On cutter</a>
										@else ($req->status == "ON_CUT")
											<a href="{{ url('mattress_cut/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Cut mattress</a>
										@endif
										
									</td>
									<!-- <td>
										@if ($req->status == 'TO_SPREAD')
											<a href="{{ url('mattress_to_unload/'.$req->id) }}" class="btn btn-info btn-xs center-block">Unload mattress</a>
										@endif	
									</td> -->
									<td>
										<a href="{{ url('other_functions_cut/'.$req->id) }}" class="btn btn-default btn-xs center-block">Other functions</a>
										<!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModalCenter" data-id="{{ $req->id }}" data-value="{{ $req->comment_operator }}" data-push="{{ $req->status}}">
  											Other functions
										</button> -->
										<!-- {!! Form::open(['method'=>'POST', 'url'=>'other_functions']) !!}
											{!! Form::hidden('id', $req->id, ['class' => 'form-control']) !!}
											{!! Form::submit('Other functions', ['class' => 'btn btn-default btn-xs center-block']) !!}
	            			            {!! Form::close() !!} -->

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