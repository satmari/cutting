@extends('app')

@section('content')
<div class="container-fluid" >
	<div class="row">
		<div class="text-center">
			
				<!-- <div class="panel-heading">Plan matress: <b>{{$location}}</b></div> -->
				<br>
				<div class="btn-group plan_menu" width="width=100%">
				
				<a href="{{ url('plan_mattress/DELETED')}}" class="btn btn-default 
				@if ($location == 'DELETED') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-trash" ></span></a>
				 
				 <a href="{{ url('plan_mattress/NOT_SET')}}" class="btn btn-default
				 @if ($location == 'NOT_SET') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-list-alt">&nbsp;<b>NOT SET</b></span></a>
				 <a href="{{ url('plan_mattress/SP1') }}" class="btn btn-primary
				 @if ($location == 'SP1') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-road" >&nbsp;<b>SP1</b></span></a>
				 <a href="{{ url('plan_mattress/SP2') }}" class="btn btn-primary
				 @if ($location == 'SP2') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-road" >&nbsp;<b>SP2</b></span></a>
				 <a href="{{ url('plan_mattress/SP3') }}" class="btn btn-primary
				 @if ($location == 'SP3') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-road" >&nbsp;<b>SP3</b></span></a>
				 <a href="{{ url('plan_mattress/SP4') }}" class="btn btn-primary
				 @if ($location == 'SP4') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-road" >&nbsp;<b>SP4</b></span></a>

				 <a href="{{ url('plan_mattress/MS1') }}" class="btn btn-success
				 @if ($location == 'MS1') plan_menu_a @endif "
				 ><span class="glyphicon  glyphicon-inbox" >&nbsp;<b>MS1</b></span></a>
				 <a href="{{ url('plan_mattress/MS2') }}" class="btn btn-success
				@if ($location == 'MS2') plan_menu_a @endif "
				><span class="glyphicon glyphicon-inbox" >&nbsp;<b>MS2</b></span></a>
				<a href="{{ url('plan_mattress/MS3') }}" class="btn btn-success
				@if ($location == 'MS3') plan_menu_a @endif "
				><span class="glyphicon glyphicon-inbox" >&nbsp;<b>MS3</b></span></a>

				<a href="{{ url('plan_mattress/MM1') }}" class="btn btn-info
				@if ($location == 'MM1') plan_menu_a @endif "
				><span class="glyphicon glyphicon-inbox">&nbsp;<b>MM1</b></span></a>

				<!-- <a href="{{ url('plan_mattress/LR') }}" class="btn btn-default
				@if ($location == 'LR') plan_menu_a @endif "
				><span class="glyphicon glyphicon-refresh">&nbsp;<b>LR</b></span></a> -->

				<a href="{{ url('plan_mattress/PLOT') }}" class="btn btn-default
				@if ($location == 'PLOT') plan_menu_a @endif "
				><span class="glyphicon glyphicon-print">&nbsp;<b>PLOT</b></span></a>

				<!--
				<a href="{{ url('plan_mattress/LEC1') }}" class="btn btn-warning
				@if ($location == 'LEC1') plan_menu_a @endif "
				><span class="glyphicon glyphicon-scissors">&nbsp;<b>LEC1</b></span></a>

				<a href="{{ url('plan_mattress/LEC2') }}" class="btn btn-warning
				@if ($location == 'LEC2') plan_menu_a @endif "
				><span class="glyphicon glyphicon-scissors">&nbsp;<b>LEC2</b></span></a>
				-->

				<a href="{{ url('plan_mattress/CUT') }}" class="btn btn-danger
				@if ($location == 'CUT') plan_menu_a @endif "
				><span class="glyphicon glyphicon-scissors">&nbsp;<b>CUT</b></span></a>

				<a href="{{ url('plan_mattress/PACK') }}" class="btn btn-warning
				@if ($location == 'PACK') plan_menu_a @endif "
				><span class="glyphicon glyphicon-briefcase">&nbsp;<b>PACK</b></span></a>

				<a href="{{ url('plan_mattress/PSO') }}" class="btn btn-danger
				@if ($location == 'PSO') plan_menu_a @endif "
				><span class="glyphicon glyphicon-duplicate">&nbsp;<b>PSO</b></span></a>
				
				<a href="{{ url('plan_mattress/COMPLETED') }}" style="color:black" class="btn btn-success
				@if ($location == 'COMPLETED') plan_menu_a @endif "
				><span class="glyphicon glyphicon-ok">&nbsp;<b>COMPLETED</b></span></a>

				<!-- <a href="{{ url('plan_mattress/PRW') }}" class="btn btn-default
				@if ($location == 'PRW') plan_menu_a @endif "
				><span class="glyphicon glyphicon-transfer">&nbsp;<b>PRW</b></span></a>

				<a href="{{ url('plan_mattress/PCO') }}" class="btn btn-default
				@if ($location == 'PCO') plan_menu_a @endif "
				><span class="glyphicon glyphicon-import">&nbsp;<b>PCO</b></span></a> -->

				<a href="{{ url('plan_mattress/ON_HOLD')}}" style="background-color:black; color:white !important;" class="btn btn-danger
				 @if ($location == 'ON_HOLD') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-flag">&nbsp;<b>ON HOLD</b></span></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>

				<br>
				<br>
				<a href="{{ url('plan_mattress/BOARD_TABLE')}}" class="btn btn-default
				@if ($location == 'BOARD_TABLE') plan_menu_a @endif"
				><span class="glyphicon  glyphicon-modal-window" aria-hidden="true">&nbsp;Planning board table</span></a>
				<a href="{{ url('plan_mattress/BOARD')}}" class="btn btn-default
				@if ($location == 'BOARD') plan_menu_a @endif"
				><span class="glyphicon  glyphicon-blackboard" aria-hidden="true">&nbsp;Planning board</span></a>
				<!-- <a href="{{ url('plan_mattress_save/'.$location)}}" class="btn btn-default"
				><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">&nbsp;Save</span></a> -->
				<br>

			</div>
		
	</div>
</div>
<br>

@if ($location != "BOARD")
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        		<div class="text-center">
		            <div class="panel panel-default">

		            	<!-- <ul id="sortable">
		            	@foreach ($data as $req)
		            	    <li class="ui-state-default"><span ></span>{{ $req->position}} - {{$req->mattress}}</li>
						@endforeach	  
						</ul> -->
		        	
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
		                    		<th >position</th>
		                            <th >mattress</th>
		                            @if ($location != 'NOT_SET')
		                            <th >g_bin</th>
		                            @endif
		                            <th >material</th>
		                            <th >dye_lot</th>
		                            <th >color_desc</th>
		                            <th >skeda</th>
		                            <th >spreading_method</th>
		                            <th >width_theor_usable</th>
		                            <th >layers</th>
		                            <th >layers_a</th>
		                            <th >cons_planned</th>
		                            <th >priority</th>
		                            <th >marker_name</th>
		                            <th >marker_length</th>
		                            <th >marker_width</th>
		                            <th >status</th>
		                            <th >location</th>
		                            <th></th>
		                            
		                    	</tr>
		                    </thead>
		                    <tbody class="connectedSortable_table searchable" 
		                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO'))
		                        id="sortable10"
		                    @endif
		                    
		                    >
		                    	<!-- <tr>
		                    		<th class=""><div><span>position</div></span></th>
		                            <th class=""><div><span>mattress</div></span></th>
		                            <th class=""><div><span>material</div></span></th>
		                            <th class=""><div><span>dye_lot</div></span></th>
		                            <th></th>
		                    	</tr> -->
		                    @foreach ($data as $req)
		                        <tr class="ss" id="item[]={{ $req->id }}">
		                            
		                            <td>{{ $req->position}}</td>
		                            <td class="
		                            @if ($req->priority == 3)
				        	    		high_priority
				        	    	@elseif ($req->priority == 1)
				        	    		low_priority
				        	    	@endif
				        	    	@if (($req->status == 'TO_LOAD') OR ($req->status == 'TO_SPREAD') OR ($req->status == 'ON_CUT') OR ($req->status == 'TO_CUT') OR ($req->status == 'COMPLETED'))
			            	    		text_black
			            	    	@elseif ($req->status == 'ON_HOLD')
			            	    		text_red
			            	    	@else
			            	    		text_green
			            	    	@endif
				        	    	"><span>{{ $req->mattress}}</span></td>
		                            @if ($location != 'NOT_SET')
		                            <td class="
		                            @if ($req->priority == 3)
				        	    		high_priority
				        	    	@elseif ($req->priority == 1)
				        	    		low_priority
				        	    	@endif
				        	    	@if (($req->status == 'TO_LOAD') OR ($req->status == 'TO_SPREAD') OR ($req->status == 'ON_CUT') OR ($req->status == 'TO_CUT') OR ($req->status == 'COMPLETED'))
			            	    		text_black
			            	    	@elseif ($req->status == 'ON_HOLD')
			            	    		text_red
			            	    	@else
			            	    		text_green
			            	    	@endif
				        	    	"><span>{{ $req->g_bin}}</span></td>
				        	    	@endif
		                            <td>{{ $req->material}}</td>
		                            <td>{{ $req->dye_lot}}</td>
		                            <td>{{ $req->color_desc}}</td>
		                            <td>{{ $req->skeda}}</td>
		                            <td>{{ $req->spreading_method}}</td>
		                            <td>{{ round($req->width_theor_usable,3)}}</td>
		                            <td>{{ $req->layers}}</td>
		                            <td>{{ $req->layers_a}}</td>
		                            <td>{{ round($req->cons_planned,3)}}</td>
		                            <td>{{ $req->priority}}</td>
		                            <td>
		                            	@if ($req->marker_name != '') 
		                            		{{ $req->marker_name }}
		                            	@else
		                            		<span>PLOCE</span>
		                            	@endif
		                            </td>
		                            <td>{{ round($req->marker_length,3)}}</td>
		                            <td>{{ round($req->marker_width,3)}}</td>
		                            <td>{{ $req->status}}</td>
		                            <td>{{ $req->location}}</td>
		                            
	                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED'))
	                            	<!-- <a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" disabled>Print nalog</a> -->
	                            	@elseif ($location == 'MM1')
	                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print mini nalog</a></td>
	                            	@else
	                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog</a></td>
	                            	@endif
		                            
									
									@if ($location == 'NOT_SET')
									<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
									<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
									@elseif ($location  == 'ON_HOLD')
									<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
									@elseif (($location != 'COMPLETED') AND ($location != 'DELETED'))
									<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
									@else

									@endif
									
									@if ($location == 'NOT_SET')
									<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
									@endif


		                        </tr>
		                    @endforeach
		                    
		                    </tbody>
		                  </table>
					</div>
			</div>
        </div>
    </div>
</div>

@endif

@if ($location == "BOARD")
<div class="container-fluid">
	<div class="row">
		<!-- <div class="col-5 col-md-2">Table NOT SET
			<ul id="sortable1" class="connectedSortable_ul">
            	@foreach ($data as $req0)
            	    <li class="ui-state-default" id="NOT_SET-{{ $req0->id }}">&nbsp;&nbsp;{{ $req0->position}} - {{$req0->mattress}}</li>
				@endforeach	  
			</ul>
		</div>
 		-->
 		<hr>
		<div class="col-md-of fset-2  col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>SP1</b></big>&nbsp;&nbsp;&nbsp;({{round($sp1_m,2)}} m)</span>
			<br><br>
			<ul id="sortable2" class="connectedSortable_ul_1">
            	@foreach ($sp1 as $req1)
            	    <li class="ui-state-default
            	    @if ($req1->priority == 3)
        	    	high_priority
        	    	@elseif ($req1->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req1->id }}" data-html="true" 
            	    	title="{{$req1->mattress}}<br />
            	    	<big>Gbin: {{$req1->g_bin}}</big><br />
            	    	Material: {{$req1->material}}<br />
            	    	Dye lot: {{$req1->dye_lot}}<br /> 
            	    	Color: {{$req1->color_desc}}<br /> 
            	    	Skeda: {{$req1->skeda}}<br /> 
            	    	Spreading method: {{$req1->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req1->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req1->layers}}<br />
            	    	Cons planned: {{round($req1->cons_planned,3)}}<br />
            	    	Priority: {{$req1->priority}}<br /> 
            	    	Marker: {{$req1->marker_name}}<br /> 
            	    	Marker length: {{round($req1->marker_length,3)}}<br /> 
            	    	Marker width: {{$req1->marker_width}}<br />
            	    	Comment office: {{$req1->comment_office}}<br />
            	    	<b>Status: {{$req1->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req1->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req1->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req1->g_bin}}<br>{{$req1->mattress}}</span>

        	    	</li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>SP2</b></big>&nbsp;&nbsp;&nbsp;({{round($sp2_m,2)}} m)</span>
			<br><br>
			<ul id="sortable3" class="connectedSortable_ul_1">
            	@foreach ($sp2 as $req2)
            	    <li class="ui-state-default
            	    @if ($req2->priority == 3)
        	    	high_priority
        	    	@elseif ($req2->priority == 1)
        	    	low_priority
        	    	@endif
        	    		" id="SP-{{ $req2->id }}" data-html="true"
            	    	title="{{$req2->mattress}}<br />
            	    	<big>Gbin: {{$req2->g_bin}}</big><br />
            	    	Material: {{$req2->material}}<br />
            	    	Dye lot: {{$req2->dye_lot}}<br /> 
            	    	Color: {{$req2->color_desc}}<br /> 
            	    	Skeda: {{$req2->skeda}}<br /> 
            	    	Spreading method: {{$req2->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req2->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req2->layers}}<br />
            	    	Cons planned: {{round($req2->cons_planned,3)}}<br />
            	    	Priority: {{$req2->priority}}<br /> 
            	    	Marker: {{$req2->marker_name}}<br /> 
            	    	Marker length: {{round($req2->marker_length,3)}}<br /> 
            	    	Marker width: {{$req2->marker_width}}<br /> 
            	    	Comment office: {{$req2->comment_office}}<br />
            	    	<b>Status: {{$req2->status }}<b />
            	    	">
            	    	
            	    	<span class="
            	    	@if ($req2->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req2->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req2->g_bin}}<br>{{$req2->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>SP3</b></big>&nbsp;&nbsp;&nbsp;({{round($sp3_m,2)}} m)</span>
			<br><br>
			<ul id="sortable4" class="connectedSortable_ul_1">
            	@foreach ($sp3 as $req3)
            	    <li class="ui-state-default
            	    @if ($req3->priority == 3)
        	    	high_priority
        	    	@elseif ($req3->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req3->id }}" data-html="true"
            	    	title="{{$req3->mattress}}<br />
            	    	<big>Gbin: {{$req3->g_bin}}</big><br />
            	    	Material: {{$req3->material}}<br />
            	    	Dye lot: {{$req3->dye_lot}}<br /> 
            	    	Color: {{$req3->color_desc}}<br /> 
            	    	Skeda: {{$req3->skeda}}<br /> 
            	    	Spreading method: {{$req3->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req3->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req3->layers}}<br />
            	    	Cons planned: {{round($req3->cons_planned,3)}}<br /> 
            	    	Priority: {{$req3->priority}}<br /> 
            	    	Marker: {{$req3->marker_name}}<br /> 
            	    	Marker length: {{round($req3->marker_length,3)}}<br /> 
            	    	Marker width: {{$req3->marker_width}}<br /> 
            	    	Comment office: {{$req3->comment_office}}<br />
            	    	<b>Status: {{$req3->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req3->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req3->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req3->g_bin}}<br>{{$req3->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>SP4</b></big>&nbsp;&nbsp;&nbsp;({{round($sp4_m,2)}} m)</span>
			<br><br>
			<ul id="sortable5" class="connectedSortable_ul_1">
            	@foreach ($sp4 as $req4)
            	    <li class="ui-state-default
            	    @if ($req4->priority == 3)
        	    	high_priority
        	    	@elseif ($req4->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req4->id }}" data-html="true"
            	    	title="{{$req4->mattress}}<br /> 
            	    	<big>Gbin: {{$req4->g_bin}}</big><br />
            	    	Material: {{$req4->material}}<br />
            	    	Dye lot: {{$req4->dye_lot}}<br /> 
            	    	Color: {{$req4->color_desc}}<br /> 
            	    	Skeda: {{$req4->skeda}}<br /> 
            	    	Spreading method: {{$req4->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req4->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req4->layers}}<br />
            	    	Cons planned: {{round($req4->cons_planned,3)}}<br /> 
            	    	Priority: {{$req4->priority}}<br /> 
            	    	Marker: {{$req4->marker_name}}<br /> 
            	    	Marker length: {{round($req4->marker_length,3)}}<br /> 
            	    	Marker width: {{$req4->marker_width}}<br /> 
            	    	Comment office: {{$req4->comment_office}}<br />
            	    	<b>Status: {{$req4->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req4->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req4->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req4->g_bin}}<br>{{$req4->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>MS1</b></big>&nbsp;&nbsp;&nbsp;({{round($ms1_m,2)}} m)</span>
			<br><br>
			<ul id="sortable6" class="connectedSortable_ul_1">
            	@foreach ($ms1 as $req5)
            	    <li class="ui-state-default
            	    @if ($req5->priority == 3)
        	    	high_priority
        	    	@elseif ($req5->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req5->id }}" data-html="true" 
            	    	title="{{$req5->mattress}}<br /> 
            	    	<big>Gbin: {{$req5->g_bin}}</big><br />
            	    	Material: {{$req5->material}}<br />
            	    	Dye lot: {{$req5->dye_lot}}<br /> 
            	    	Color: {{$req5->color_desc}}<br /> 
            	    	Skeda: {{$req5->skeda}}<br /> 
            	    	Spreading method: {{$req5->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req5->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req5->layers}}<br />
            	    	Cons planned: {{round($req5->cons_planned,3)}}<br /> 
            	    	Priority: {{$req5->priority}}<br /> 
            	    	Marker: {{$req5->marker_name}}<br /> 
            	    	Marker length: {{round($req5->marker_length,3)}}<br /> 
            	    	Marker width: {{$req5->marker_width}}<br /> 
            	    	Comment office: {{$req5->comment_office}}<br />
            	    	<b>Status: {{$req5->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req5->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req5->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req5->g_bin}}<br>{{$req5->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>MS2</b></big>&nbsp;&nbsp;&nbsp;({{round($ms2_m,2)}} m)</span>
			<br><br>
			<ul id="sortable7" class="connectedSortable_ul_1">
            	@foreach ($ms2 as $req6)
            	    <li class="ui-state-default
            	    @if ($req6->priority == 3)
        	    	high_priority
        	    	@elseif ($req6->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req6->id }}" data-html="true"
            	    	title="{{$req6->mattress}}<br /> 
            	    	<big>Gbin: {{$req6->g_bin}}</big><br />
            	    	Material: {{$req6->material}}<br />
            	    	Dye lot: {{$req6->dye_lot}}<br /> 
            	    	Color: {{$req6->color_desc}}<br /> 
            	    	Skeda: {{$req6->skeda}}<br /> 
            	    	Spreading method: {{$req6->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req6->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req6->layers}}<br />
            	    	Cons planned: {{round($req6->cons_planned,3)}}<br />
            	    	Priority: {{$req6->priority}}<br /> 
            	    	Marker: {{$req6->marker_name}}<br /> 
            	    	Marker length: {{round($req6->marker_length,3)}}<br /> 
            	    	Marker width: {{$req6->marker_width}}<br /> 
            	    	Comment office: {{$req6->comment_office}}<br />
            	    	<b>Status: {{$req6->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req6->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req6->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req6->g_bin}}<br>{{$req6->mattress}}</span>

            	   	</li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>MS3</b></big>&nbsp;&nbsp;&nbsp;({{round($ms3_m,2)}} m)</span>
			<br><br>
			<ul id="sortable8" class="connectedSortable_ul_1">
            	@foreach ($ms3 as $req7)
            	    <li class="ui-state-default
            	    @if ($req7->priority == 3)
        	    	high_priority
        	    	@elseif ($req7->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req7->id }}" data-html="true"
            	    	title="{{$req7->mattress}}<br /> 
            	    	<big>Gbin: {{$req7->g_bin}}</big><br />
            	    	Material: {{$req7->material}}<br />
            	    	Dye lot: {{$req7->dye_lot}}<br /> 
            	    	Color: {{$req7->color_desc}}<br /> 
            	    	Skeda: {{$req7->skeda}}<br /> 
            	    	Spreading method: {{$req7->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req7->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req7->layers}}<br />
            	    	Cons planned: {{round($req7->cons_planned,3)}}<br /> 
            	    	Priority: {{$req7->priority}}<br /> 
            	    	Marker: {{$req7->marker_name}}<br /> 
            	    	Marker length: {{round($req7->marker_length,3)}}<br /> 
            	    	Marker width: {{$req7->marker_width}}<br /> 
            	    	Comment office: {{$req7->comment_office}}<br />
            	    	<b>Status: {{$req7->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req7->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req7->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req7->g_bin}}<br>{{$req7->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>MM1</b></big>&nbsp;&nbsp;&nbsp;({{round($mm1_m,2)}} parts)</span>
			<br><br>
			<ul id="sortable9" class="connectedSortable_ul_1">
            	@foreach ($mm1 as $req8)
            	    <li class="ui-state-default
            	    @if ($req8->priority == 3)
        	    	high_priority
        	    	@elseif ($req8->priority == 1)
        	    	low_priority
        	    	@endif
            	    	" id="SP-{{ $req8->id }}" data-html="true"
            	    	title="{{$req8->mattress}}<br /> 
            	    	<big>Gbin: {{$req8->g_bin}}</big><br />
            	    	Material: {{$req8->material}}<br />
            	    	Dye lot: {{$req8->dye_lot}}<br /> 
            	    	Color: {{$req8->color_desc}}<br /> 
            	    	Skeda: {{$req8->skeda}}<br /> 
            	    	Spreading method: {{$req8->spreading_method}}<br /> 
            	    	Width theor usable: {{round($req8->width_theor_usable,3)}}<br /> 
            	    	Layers: {{$req8->layers}}<br />
            	    	Cons planned: {{round($req8->cons_planned,3)}}<br /> 
            	    	Priority: {{$req8->priority}}<br /> 
            	    	Marker: {{$req8->marker_name}}<br /> 
            	    	Marker length: {{round($req8->marker_length,3)}}<br /> 
            	    	Marker width: {{$req8->marker_width}}<br /> 
            	    	Comment office: {{$req8->comment_office}}<br />
            	    	<b>Status: {{$req8->status }}<b />
            	    	">
            	    	<span class="
            	    	@if ($req8->status == 'TO_LOAD')
            	    		text_black
            	    	@elseif ($req8->status == 'ON_HOLD')
            	    		text_red
            	    	@else
            	    		text_green
            	    	@endif
            	    	">{{$req8->g_bin}}<br>{{$req8->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

	</div>
</div>
@endif

    
@endsection
