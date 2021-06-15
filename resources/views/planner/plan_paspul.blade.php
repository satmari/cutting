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

				<!-- <br>
				<br> -->
				<!-- <a href="{{ url('plan_paspul/BOARD')}}" class="btn btn-default
				@if ($location == 'BOARD') plan_menu_a @endif"
				><span class="glyphicon  glyphicon-blackboard" aria-hidden="true">&nbsp;Planning board</span></a> -->
				<!-- <a href="{{ url('plan_paspul_save/'.$location)}}" class="btn btn-default"
				><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">&nbsp;Save</span></a> -->
				<br>

			</div>
		</div>
	</div>
<br>

	
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
			                    		<th >position</th>
			                            <th >paspul_roll</th>
			                            <th >sap_su</th>
			                            <th >material</th>
			                            <th >color_desc</th>
			                            <th >dye_lot</th>
			                            <th >paspul_type</th>

			                            <th >width</th>
			                            <th >kotur_width</th>
			                            <!-- <th >kotur_width_without_tension</th> -->
			                            <th >kotur_planned</th>
			                            <th >kotur_actual</th>
			                            <th >rewound_length</th>
			                            <th >rewound_length_a</th>

			                            <th >pasbin</th>
			                            <th >skeda_item_type</th>
			                            <th >skeda</th>
			                            <!-- <th >skeda_status</th> -->
			                            
			                            <!-- <th >rewound_roll_unit_of_measure</th> -->
			                            
			                            <th >priority</th>
			                            <!-- <th >comment_office</th> -->
			                            <!-- <th >comment_operator</th> -->
			                            <!-- <th >call_shift_manager</th> -->
			                            <!-- <th >rewinding_method</th> -->
			                            <!-- <th >created_at</th> -->
			                            <!-- <th >updated_at</th> -->

			                            <!-- <th>status</th> -->
			                            <th>location</th>
			                            <!-- <th>device</th> -->
			                            <!-- <th>active</th> -->
			                            <!-- <th>operator1</th> -->
			                            <!-- <th>operator2</th> -->

			                            <th></th>
			                            <th></th>
			                            <th></th>
			                    	</tr>
			                    </thead>
			                    <tbody class="connectedSortable_table searchable" 
			                    @if ($location != "COMPLETED")
			                        id="sortable11"
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
			                            <td>{{ $req->paspul_roll}}</td>
			                            <td>{{ $req->sap_su}}</td>
			                            <td>{{ $req->material}}</td>
			                            <td>{{ $req->color_desc}}</td>
			                            <td>{{ $req->dye_lot}}</td>
			                            <td>{{ $req->paspul_type}}</td>

			                            <td>{{ round($req->width,3) }}</td>
			                            <td>{{ round($req->kotur_width,3) }}</td>
			                            <!-- <td>{{ round($req->kotur_width_without_tension,3) }}</td> -->
			                            <td>{{ round($req->kotur_planned,3) }}</td>
			                            <td>{{ round($req->kotur_actual,3) }}</td>
			                            <td>{{ round($req->rewound_length,3) }}</td>
			                            <td>{{ round($req->rewound_length_a,3) }}</td>

			                            <td>{{ $req->pasbin }}</td>
			                            <td>{{ $req->skeda_item_type }}</td>
			                            <td>{{ $req->skeda }}</td>
			                            <!-- <td>{{ $req->skeda_status }}</td> -->

			                            <!-- <td>{{ $req->rewound_roll_unit_of_measure }}</td> -->
			                            
			                            <td>{{ $req->priority }}</td>
			                            <!-- <td>{{ $req->comment_office }}</td> -->
			                            <!-- <td>{{ $req->comment_operator }}</td> -->
			                            <!-- <td>{{ $req->call_shift_manager }}</td> -->
			                            <!-- <td>{{ $req->rewinding_method }}</td> -->
			                            <!-- <td>{{ $req->created_at }}</td> -->
			                            <!-- <td>{{ $req->updated_at }}</td> -->

			                            {{--<td>{{ $req->status }}</td>--}}
			                            <td>{{ $req->location }}</td>
			                            <!-- <td>{{ $req->device }}</td> -->
			                            <!-- <td>{{ $req->active }}</td> -->
			                            <!-- <td>{{ $req->operator1 }}</td> -->
			                            <!-- <td>{{ $req->operator2 }}</td> -->

										<td>
											@if ($location == 'NOT_SET')
											<a href="{{ url('plan_paspul_line/'.$req->id) }}" class="btn btn-info btn-xs center-block">Plan paspul</a>
											@else
											<a href="{{ url('plan_paspul_line/'.$req->id) }}" class="btn btn-info btn-xs center-block" disabled>Plan paspul</a>
											@endif
										</td>

										<td>
											@if (($location == 'DELETED') OR ($location == 'COMPLETED'))
												<a href="{{ url('edit_paspul_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block" disabled>Edit paspul</a>
											@else 
												<a href="{{ url('edit_paspul_line/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit paspul</a>
											@endif
										</td>

										<td>
											@if (($location == 'DELETED') OR ($location == 'COMPLETED'))
												<a href="{{ url('remove_paspul_line/'.$req->id) }}" class="btn btn-danger btn-xs center-block" disabled>Remove</a>
											@else 
												<a href="{{ url('remove_paspul_line/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a>
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
