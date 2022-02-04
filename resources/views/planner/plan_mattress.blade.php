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
				<a href="{{ url('plan_mattress/TUB') }}" class="btn btn-success 
				@if ($location == 'TUB') plan_menu_a @endif " style="background-color:green; color:white !important;"
				><span class="glyphicon glyphicon-inbox" >&nbsp;<b>TUB</b></span></a>
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

				 <a href="{{ url('plan_mattress/TO_SPLIT')}}" style="background-color:brown; color:white !important;" class="btn btn-danger
				 @if ($location == 'TO_SPLIT') plan_menu_a @endif "
				 ><span class="glyphicon glyphicon-flag">&nbsp;<b>TO SPLIT</b></span></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<br><br>
				<a href="{{ url('plan_mattress/BOARD_TABLE')}}" class="btn btn-default
				@if ($location == 'BOARD_TABLE') plan_menu_a @endif"
				><span class="glyphicon  glyphicon-modal-window" aria-hidden="true"></span>&nbsp;Planning board table</a>
				<a href="{{ url('plan_mattress/BOARD')}}" class="btn btn-default
				@if ($location == 'BOARD') plan_menu_a @endif"
				><span class="glyphicon  glyphicon-blackboard" aria-hidden="true"></span>&nbsp;Planning board</a>
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

		            	
		        	 	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>
		                <table class="table table-striped table-bordered tableFixHead" id="table-draggable2" 
		                data-pagination="true"
		                data-height="300"
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

		                @if ($location == 'DELETED')

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
			                           
			                            <th >Priority</th>
			                            <!-- <th></th> -->
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DEL ETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
				                            {{--<td>{{ $req->position }}</td>--}}
				                            <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
				                            <td>{{ $req->marker_name}}</td>
				                            <td>{{ round($req->marker_length,3)}}</td>
				                            <td>{{ round($req->extra,0)}}</td>
				                            @if (($req->skeda_item_type == 'MB') OR ($req->skeda_item_type == 'MW'))
				                            	<td>{{ round($req->width_theor_usable,3)}}</td>
				                            @else
				                            	@if (($req->skeda_item_type == 'MB') OR ($req->skeda_item_type == 'MW'))
				                            	<td>{{ round($req->width_theor_usable,3)}}</td>
				                            @else
				                            	<td>{{ round($req->marker_width,3)}}</td>
				                            @endif
				                            @endif
				                            <td>{{ round($req->layers,0)}}</td>
				                            <td>{{ round($req->layers_a,0)}}</td>
				                            
				                            <td style="width: 75px;">{{ $req->pro}}</td>
				                            <td style="width: 60px;">{{ $req->location_all}}</td>
				                            <td style="width: 138px;">{{ $req->sku}}</td>
				                            
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="min-width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td class="
				                            @if ($req->priority == 3) top_priority
						        	    	@elseif ($req->priority == 2) high_priority
						        	    	@endif
						        	    	">
						        	    	@if ($req->priority == 3)Top
						        	    	@elseif ($req->priority == 2)High
					        	    		@elseif ($req->priority == 1)Normal
						        	    	@endif</td>
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            @endif
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
											
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class=""><span>{{ $req->g_bin_orig}}</span></td>
					                        <td  colspan="13" style="padding: 5px; text-align: left;">
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
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

		                @if ($location == 'NOT_SET')

		                        <thead>
			                       <tr>
			                       		<th >Mattress</th>
			                            <th >Marker</th>
			                            <th >Marker Length [m]</th>
			                            <th >Mattress Width [cm]</th>
			                            <th >Layers Actual</th>
			                            <th >PRO</th>
			                            <th >Destination</th>
			                            <th >SKU</th>
			                            <th >Material</th>
			                            <th >Dye Lot</th>
			                            <th >Color Desc</th>
			                            <th >Skeda</th>
			                            <th >Actual Cons [m]</th>
			                            <th></th>
			                            <th></th>
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
				                            <td>{{ $req->marker_name}}</td>
				                            <td>{{ round($req->marker_length,3)}}</td>
				                            @if (($req->skeda_item_type == 'MB') OR ($req->skeda_item_type == 'MW'))
				                            	<td>{{ round($req->width_theor_usable,3)}}</td>
				                            @else
				                            	<td>{{ round($req->marker_width,3)}}</td>
				                            @endif
				                            <td>{{ round($req->layers_a,0)}}</td>
				                            <td style="width: 75px;">{{ $req->pro}}</td>
				                            <td style="width: 60px;">{{ $req->location_all}}</td>
				                            <td style="width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            
				                           @if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
				                            
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td  colspan="21" style="padding: 5px; text-align: left;">
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
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR
                        	 ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR
                        	 ($location == 'PSO') OR ($location == 'PACK') OR ($location == 'CUT'))

								<thead>
			                       <tr>	
			                       		<!-- <th >Position</th> -->
			                       		@if ($location == 'MM1')
			                       		@else
			                       			<th >G-bin</th>
			                       		@endif
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
			                            <th >Priority</th>
			                            <th >Status</th>
			                             @if ($location == 'MM1')
				                            <th>Layer limit</th>
				                            <th>Created_at</th>
										 @endif
			                            <th></th>
			                            <th></th>
			                            <th></th>
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
				                            <!-- <td>{{ $req->position }}</td> -->
				                            @if ($location == 'MM1')
				                       		@else
				                       			<td class="" ><span>{{ $req->g_bin}}</span></td>
				                       		@endif
				                            
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
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>

				                            {{-- <td>{{ $req->location}}</td> --}}
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
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            	<td>{{ substr($req->created_at,0 ,16)}}</td>
				                            @endif
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
				                            
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class=""><span>{{ $req->g_bin_orig}}</span></td>

					                        @if ($location == 'MM1' OR $location == 'CUT')
				                            	<td  colspan="20" style="padding: 5px; text-align: left;">
				                            @else 
				                            	<td  colspan="18" style="padding: 5px; text-align: left;">
				                            @endif
					                        	@if ($req->comment_office != '')
					                        	<b>Comment office:</b>
					                        	<i>{{ $req->comment_office }}</i><br>
					                        	@endif
					                        	@if ($req->comment_operator != '')
					                        	<b>Comment operator:</b>
					                        	<i>{{ $req->comment_operator }}</i><br>
					                        	@endif
					                        </td>
					                        @if ($location == 'MM1' OR $location == 'CUT')
						                        <td  colspan="3" style="padding: 1px; text-align: left;">
					                        @else 
				                            	<td  colspan="3" style="padding: 1px; text-align: left;">
				                            @endif
					                        	@if ($req->call_shift_manager == 1 )
					                        		<b><span class="glyphicon glyphicon-earphone"></span>&nbsp; &nbsp;<b>Call shift manager</b></b>
					                        	@endif
					                        	@if ($req->test_marker == 1)
					                        		<br><b><span class="glyphicon glyphicon-text-size"></span>&nbsp; &nbsp;<b>Test Marker</b></b>
					                        	@endif
					                        </td>
				                    	</tr>
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'TUB')
								<thead>
			                       <tr>	
			                       		<!-- <th >Position</th> -->
			                       		@if ($location == 'MM1')
			                       		@else
			                       			<th >G-bin</th>
			                       		@endif
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
			                            <th >Actual Cons [kg]</th>
			                            <th >Spreading Method</th>
			                            <th >Pcs per Bundle</th>
			                            <th >Overlapping</th>
			                            <th >Priority</th>
			                            <th >Status</th>
			                             @if ($location == 'MM1')
				                            <th>Layer limit</th>
				                            <th>Created_at</th>
										 @endif
			                            <th></th>
			                            <th></th>
			                            <th></th>
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'TUB') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
				                            <!-- <td>{{ $req->position }}</td> -->
				                            @if ($location == 'MM1')
				                       		@else
				                       			<td class="" ><span>{{ $req->g_bin}}</span></td>
				                       		@endif
				                            
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
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>

				                            {{-- <td>{{ $req->location}}</td> --}}
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
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            	<td>{{ substr($req->created_at,0 ,16)}}</td>
				                            @endif
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
				                            
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU') OR (substr($location,0 ,2) == 'TU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class=""><span>{{ $req->g_bin_orig}}</span></td>

					                        @if ($location == 'MM1' OR $location == 'CUT')
				                            	<td  colspan="20" style="padding: 5px; text-align: left;">
				                            @else 
				                            	<td  colspan="18" style="padding: 5px; text-align: left;">
				                            @endif
					                        	@if ($req->comment_office != '')
					                        	<b>Comment office:</b>
					                        	<i>{{ $req->comment_office }}</i><br>
					                        	@endif
					                        	@if ($req->comment_operator != '')
					                        	<b>Comment operator:</b>
					                        	<i>{{ $req->comment_operator }}</i><br>
					                        	@endif
					                        </td>
					                        @if ($location == 'MM1' OR $location == 'CUT')
						                        <td  colspan="3" style="padding: 1px; text-align: left;">
					                        @else 
				                            	<td  colspan="3" style="padding: 1px; text-align: left;">
				                            @endif
					                        	@if ($req->call_shift_manager == 1 )
					                        		<b><span class="glyphicon glyphicon-earphone"></span>&nbsp; &nbsp;<b>Call shift manager</b></b>
					                        	@endif
					                        	@if ($req->test_marker == 1)
					                        		<br><b><span class="glyphicon glyphicon-text-size"></span>&nbsp; &nbsp;<b>Test Marker</b></b>
					                        	@endif
					                        </td>
				                    	</tr>
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'PLOT')
		                           
 								<thead>
			                       <tr>	
			                       		<!-- <th >Position</th> -->
			                       		<th >G-bin</th>
			                            <th >Mattress</th>
			                            <th >Marker</th>
			                            <th >Marker Length [m]</th>
			                            <th >Extra [cm]</th>
			                            <th >Mattress Width [cm]</th>
			                            <th >Layers</th>
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
			                            <th >Location</th>
			                            <th >Priority</th>
			                            <th >Status</th>
			                            <th><b>Printed</b></th>
			                            <th></th>
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
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
				                            <td>{{ round($req->layers_a,0)}}</td>
				                            <td style="width: 75px;">{{ $req->pro}}</td>
				                            <td style="width: 60px;">{{ $req->location_all}}</td>
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>

				                            <td>{{ $req->location}}</td>
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
				                            <td>{{ $req->printed_marker}}</td>
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            @endif
				                            
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
											
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class=""><span>{{ $req->g_bin_orig}}</span></td>

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
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'ON_HOLD')
		                           
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
			                            <th></th>
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
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
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>
				                            <td>{{ $req->location}}</td>
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
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            @endif
				                            
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
				                            
											
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class=""><span>{{ $req->g_bin_orig}}</span></td>

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
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'COMPLETED')

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
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
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
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>

				                            <td>{{ $req->location}}</td>
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
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            @endif
				                            
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a><br>
											
												@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
				                            	@elseif ($req->skeda_item_type == 'MM')
				                            	<a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
				                            		@if($req->printed_nalog == NULL) 0
				                            		@else {{$req->printed_nalog}} @endif
				                            		)</i></a>
				                            	@else
				                            	<a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
				                            		@if ($req->printed_nalog == NULL) 
				                            		0
				                            		@else
				                            		{{$req->printed_nalog}}
				                            		@endif
				                            		)</i></a>
				                            	@endif
											</td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 
				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class="">
					                        	@if ($req->g_bin_orig != '')
					                        		<span>Father g_bin: {{ $req->g_bin_orig }}</span>
					                        	@endif
					                        </td>

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
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'BOARD_TABLE')

 								<thead>
			                       <tr>
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
			                            <th></th>
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
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
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td style="width: 120px;">{{ $req->skeda}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
				                            <td style="width: 50px;">{{ $req->spreading_method}}</td>
				                            <td >{{ round($req->pcs_bundle,0)}}</td>
				                            <td>{{ $req->overlapping}}</td>

				                            <td>{{ $req->location}}</td>
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
				                            
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            @endif
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	@elseif ($req->skeda_item_type == 'MM')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
				                            
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else
											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU') )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	<td class=""><span>{{ $req->mattress}}</span></td>
						        	    	<td class=""><span>{{ $req->g_bin_orig}}</span></td>

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
					                        <td  colspan="3" style="padding: 2px; text-align: left;">
					                        	@if ($req->call_shift_manager == 1 )
					                        		<b><span class="glyphicon glyphicon-earphone"></span>&nbsp; &nbsp;<b>Call shift manager</b></b>
					                        	@endif
					                        	@if ($req->test_marker == 1)
					                        		<br><b><span class="glyphicon glyphicon-text-size"></span>&nbsp; &nbsp;<b>Test Marker</b></b>
					                        	@endif
					                        </td>
				                    	</tr>
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'TO_SPLIT')
                        	<thead>
			                       <tr>	
			                       		<!-- <th >Position</th> -->
			                       		<th >G-bin (orig)</th>
			                            <th >Mattress (orig)</th>
			                            <th >Marker (orig)</th>
			                            <th >Marker width (orig)</th>
			                            <th >Marker length (orig)</th>
			                            
			                            <th >Request width</th>
			                            <th >Request length</th>
			                            <th >Operator comment</th>
			                            <th >Operator</th>
			                            <th >Location</th>
			                            
			                            <th >Status</th>
			                            <th >Created</th>
			                            
			                            <th></th>
			                            <th></th>
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable">
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
						        	    	<td >{{ $req->g_bin_orig}}</td>
						        	    	<td >{{ $req->mattress_orig}}</td>
						        	    	<td >{{ $req->marker_name_orig}}</td>
						        	    	<td >{{ round($req->marker_width,0)}}</td>
						        	    	<td >{{ round($req->marker_length,2)}}</td>

				                       		<td style="color:orange">{{ round($req->requested_width,0) }}</td>     
				                       		<td style="color:orange">{{ round($req->requested_length,2) }}</td>     
				                       		<td>{{ $req->comment_operator }}</td> 
				                       		<td>{{ $req->operator1 }}</td>     
				                       		<td>{{ $req->location }}</td>     
				                       		<td>{{ $req->status }}</td>
				                       		<td>{{ substr($req->created_at, 0 ,19) }}</td>
				                       		<td><a href="{{ url('split_mattress/'.$req->id) }}" class="btn btn-warning btn-xs center-block" >Split mattress</a></td>
				                       		<td><a href="{{ url('split_mattress_delete/'.$req->id) }}" class="btn btn-danger btn-xs center-block" >Delete</a></td>
				                        </tr>
				                    @endforeach
			                  	</tbody>
                        @endif

                        @if ($location == 'TEST')
		                        <thead>
			                       <tr>
			                       		@if (($location != 'BOARD_TABLE') OR ($location != 'PLOT'))
			                    		<th >position</th>
			                    		@endif
			                            <th >mattress</th>
			                            @if ($location != 'NOT_SET')
			                            <th >g_bin</th>
			                            @endif
			                            <th >material</th>
			                            <th >dye_lot</th>
			                            <th >color_desc</th>
			                            <th >skeda</th>
			                            <!-- <th >spreading_method</th> -->
			                            <th >width_theor_usable</th>
			                            <th >layers</th>
			                            <th >layers_a</th>
			                            <th >cons_actual</th>
			                            <th >priority</th>
			                            <th >marker_name</th>
			                            <th >marker_length</th>
			                            <th >marker_width</th>
			                            <th >status</th>
			                            <th >location</th>
			                            <th style="width: 75px;">pro</th>
			                            <!-- <th style="width: 100px;">style_size</th> -->
			                            <th>sku</th>
			                            @if ($location == 'MM1')
			                            	<th>layer_limit</th>
			                            @endif
			                            <th></th>
			                            
			                    	</tr>
			                  	</thead>
			                  	<tbody class="connectedSortable_table searchable" 
				                    @if (($location == 'SP1') OR ($location == 'SP2') OR ($location == 'SP3') OR ($location == 'SP4') OR ($location == 'MS1') OR ($location == 'MS2') OR ($location == 'MS3') OR ($location == 'MM1') OR ($location == 'CUT') OR ($location == 'PSO') OR ($location == 'DELETED') OR ($location == 'COMPLETED'))
				                        id="sortable10"
				                    @endif
				                    >
				                    	
				                    @foreach ($data as $req)
				                        <tr class="ss" id="item[]={{ $req->id }} " style="border-top: 3px solid grey;
				                        	-webkit-box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1); 
											box-shadow: inset 2px 13px 18px 6px rgba(0,0,0,0.1);">
				                            
				                            @if (($location != 'BOARD_TABLE') OR ($location != 'PLOT'))
				                            <td>{{ $req->position}}</td>
				                            @endif
				                            <td class=""><span>{{ $req->mattress}}</span></td>
				                            @if ($location != 'NOT_SET')
				                            <td class=""><span>{{ $req->g_bin}}</span></td>
						        	    	@endif
				                            <td>{{ $req->material}}</td>
				                            <td>{{ $req->dye_lot}}</td>
				                            <td>{{ $req->color_desc}}</td>
				                            <td>{{ $req->skeda}}</td>
				                            {{--<td>{{ $req->spreading_method}}</td>--}}
				                            <td>{{ round($req->width_theor_usable,3)}}</td>
				                            <td>{{ $req->layers}}</td>
				                            <td>{{ $req->layers_a}}</td>
				                            <td>{{ round($req->cons_actual,3)}}</td>
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
				                            	@if ($req->marker_name != '') 
				                            		{{ $req->marker_name }}
				                            	@else
				                            		<span>PLOCE</span>
				                            	@endif
				                            </td>
				                            <td>{{ round($req->marker_length,3)}}</td>
				                            @if (($req->skeda_item_type == 'MB') OR ($req->skeda_item_type == 'MW'))
				                            	<td>{{ round($req->width_theor_usable,3)}}</td>
				                            @else
				                            	<td>{{ round($req->marker_width,3)}}</td>
				                            @endif
				                            <td>{{ $req->status}}</td>
				                            <td>{{ $req->location}}</td>
				                            <td style="width: 75px;">{{ $req->pro}}</td>
				                            <!-- <td style="width: 100px;">{{ $req->style_size}}</td> -->
				                            <td style="min-width: 138px;">{{ $req->sku}}</td>
				                            @if ($location == 'MM1')
				                            	<td>{{ $req->layer_limit}}</td>
				                            @endif
				                            
			                            	@if (($location == 'NOT_SET') OR ($location == 'DELETED') OR ($location == 'PLOT'))
			                            	<!-- <a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" disabled>Print nalog</a> -->
			                            	@elseif ($location == 'MM1')
			                            	<td><a href="{{ url('print_mattress_m/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if($req->printed_nalog == NULL) 0
			                            		@else {{$req->printed_nalog}} @endif
			                            		)</i></a></td>
			                            	@else
			                            	<td><a href="{{ url('print_mattress/'.$req->id) }}" class="btn btn-info btn-xs center-block" >Print nalog <i>(
			                            		@if ($req->printed_nalog == NULL) 
			                            		0
			                            		@else
			                            		{{$req->printed_nalog}}
			                            		@endif
			                            		)</i></a></td>
			                            	@endif
				                            
											
											@if ($location == 'NOT_SET')
											<td><a href="{{ url('plan_mattress_line/'.$req->id) }}" class="btn btn-success btn-xs center-block">Plan mattress</a></td>
											<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Modal</button> -->
											@elseif ($location  == 'ON_HOLD')
											<td><a href="{{ url('change_marker/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Change marker</a></td>
											@elseif ($location != 'DELETED')
											<td><a href="{{ url('edit_mattress_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit mattress</a></td>
											@else

											@endif
											
											@if (($location == 'NOT_SET') OR (substr($location,0 ,2) == 'SP') OR (substr($location,0 ,2) == 'MS') OR (substr($location,0 ,2) == 'MM') OR (substr($location,0 ,2) == 'CU')  )
											<td><a href="{{ url('delete_mattress/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
											@endif

				                        </tr>
				                        @if ($location != 'NOT_SET') 

				                        <tr style="border-bottom: 3px solid grey;
					                        -webkit-box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1); 
											box-shadow: inset 1px -22px 21px 1px rgba(0,0,0,0.1);
					                        ">
					                        <td  colspan="3" style="padding: 1px;">
					                        <span><b>Comment office:</b></span><br>
					                        <span><b>Comment operator:</b> </span>
					                        </td>
					                        <td  colspan="19" style="padding: 1px; text-align: left;"><i>{{ $req->comment_office }}</i><br>
					                        	<i>{{ $req->comment_operator }}</i>
					                        </td>
				                    	</tr>
				                        @endif
				                    @endforeach
			                  	</tbody>
                        @endif
		                    
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
        	    		top_priority
        	    	@elseif ($req1->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req1->layers_a,0)}}<br />
            	    	Cons actual: {{round($req1->cons_actual,3)}}<br />
            	    	Priority: 
			        	    	@if ($req1->priority == 3)Top
			        	    	@elseif ($req1->priority == 2)High
		        	    		@elseif ($req1->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req1->marker_name}}<br />
            	    	Marker length: {{round($req1->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req1->marker_width,0)}}<br />
            	    	Comment office: {{$req1->comment_office}}<br />
            	    	<b>Status: {{$req1->status }}<b />
            	    	">
            	    	<span class="">{{$req1->g_bin}}<br>{{$req1->mattress}}</span>

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
        	    		top_priority
        	    	@elseif ($req2->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req2->layers_a,0)}}<br />
            	    	Cons actual: {{round($req2->cons_actual,3)}}<br />
            	    	Priority: 
			        	    	@if ($req2->priority == 3)Top
			        	    	@elseif ($req2->priority == 2)High
		        	    		@elseif ($req2->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req2->marker_name}}<br /> 
            	    	Marker length: {{round($req2->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req2->marker_width,0)}}<br /> 
            	    	Comment office: {{$req2->comment_office}}<br />
            	    	<b>Status: {{$req2->status }}<b />
            	    	">
            	    	
            	    	<span class="">{{$req2->g_bin}}<br>{{$req2->mattress}}</span>

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
        	    		top_priority
        	    	@elseif ($req3->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req3->layers_a,0)}}<br />
            	    	Cons actual: {{round($req3->cons_actual,3)}}<br /> 
            	    	Priority: 
			        	    	@if ($req3->priority == 3)Top
			        	    	@elseif ($req3->priority == 2)High
		        	    		@elseif ($req3->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req3->marker_name}}<br /> 
            	    	Marker length: {{round($req3->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req3->marker_width,0)}}<br /> 
            	    	Comment office: {{$req3->comment_office}}<br />
            	    	<b>Status: {{$req3->status }}<b />
            	    	">
            	    	<span class="">{{$req3->g_bin}}<br>{{$req3->mattress}}</span>

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
        	    		top_priority
        	    	@elseif ($req4->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req4->layers_a,0)}}<br />
            	    	Cons actual: {{round($req4->cons_actual,3)}}<br /> 
            	    	Priority: 
			        	    	@if ($req4->priority == 3)Top
			        	    	@elseif ($req4->priority == 2)High
		        	    		@elseif ($req4->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req4->marker_name}}<br /> 
            	    	Marker length: {{round($req4->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req4->marker_width,0)}}<br /> 
            	    	Comment office: {{$req4->comment_office}}<br />
            	    	<b>Status: {{$req4->status }}<b />
            	    	">
            	    	<span class="">{{$req4->g_bin}}<br>{{$req4->mattress}}</span>

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
        	    		top_priority
        	    	@elseif ($req5->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req5->layers_a,0)}}<br />
            	    	Cons actual: {{round($req5->cons_actual,3)}}<br /> 
            	    	Priority: 
			        	    	@if ($req5->priority == 3)Top
			        	    	@elseif ($req5->priority == 2)High
		        	    		@elseif ($req5->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req5->marker_name}}<br /> 
            	    	Marker length: {{round($req5->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req5->marker_width,0)}}<br /> 
            	    	Comment office: {{$req5->comment_office}}<br />
            	    	<b>Status: {{$req5->status }}<b />
            	    	">
            	    	<span class="">{{$req5->g_bin}}<br>{{$req5->mattress}}</span>

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
        	    		top_priority
        	    	@elseif ($req6->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req6->layers_a,0)}}<br />
            	    	Cons actual: {{round($req6->cons_actual,3)}}<br />
            	    	Priority: 
			        	    	@if ($req6->priority == 3)Top
			        	    	@elseif ($req6->priority == 2)High
		        	    		@elseif ($req6->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req6->marker_name}}<br /> 
            	    	Marker length: {{round($req6->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req6->marker_width,0)}}<br /> 
            	    	Comment office: {{$req6->comment_office}}<br />
            	    	<b>Status: {{$req6->status }}<b />
            	    	">
            	    	<span class="">{{$req6->g_bin}}<br>{{$req6->mattress}}</span>

            	   	</li>
				@endforeach	  
			</ul>
		</div>

		<div class="col-md-1 8musketara" style="width: 12.499999995%;text-align: center;"><span><big><b>TUB</b></big>&nbsp;&nbsp;&nbsp;({{round($tub_m,2)}} kg)</span>
			<br><br>
			<ul id="sortable8" class="connectedSortable_ul_1" style='background-color:#8fe3a266; !important'>
            	@foreach ($tub as $req7)
            	    <li class="ui-state-default
            	    @if ($req7->priority == 3)
        	    		top_priority
        	    	@elseif ($req7->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req7->layers_a,0)}}<br />
            	    	Cons actual: {{round($req7->cons_actual,3)}}<br /> 
            	    	Priority: 
			        	    	@if ($req7->priority == 3)Top
			        	    	@elseif ($req7->priority == 2)High
		        	    		@elseif ($req7->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req7->marker_name}}<br /> 
            	    	Marker length: {{round($req7->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req7->marker_width,0)}}<br /> 
            	    	Comment office: {{$req7->comment_office}}<br />
            	    	<b>Status: {{$req7->status }}<b />
            	    	">
            	    	<span class="">{{$req7->g_bin}}<br>{{$req7->mattress}}</span>

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
        	    		top_priority
        	    	@elseif ($req8->priority == 2)
        	    		high_priority
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
            	    	Layers: {{round($req8->layers_a,0)}}<br />
            	    	Cons actual: {{round($req8->cons_actual,3)}}<br /> 
            	    	Priority: 
			        	    	@if ($req8->priority == 3)Top
			        	    	@elseif ($req8->priority == 2)High
		        	    		@elseif ($req8->priority == 1)Normal
			        	    	@endif
			        	    	<br />
            	    	Marker: {{$req8->marker_name}}<br /> 
            	    	Marker length: {{round($req8->marker_length,3)}}<br /> 
            	    	Marker width: {{round($req8->marker_width,0)}}<br />
            	    	Comment office: {{$req8->comment_office}}<br />
            	    	<b>Status: {{$req8->status }}<b />
            	    	">
            	    	<span class="">{{$req8->g_bin}}<br>{{$req8->mattress}}</span>

            	    </li>
				@endforeach	  
			</ul>
		</div>

	</div>
</div>
@endif

    
@endsection
