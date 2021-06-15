@extends('app')

@section('content')
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

		        	 	<!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div> -->

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
		                            <th >id</th>
		                            
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
		                        <tr class="ss" id="item[]={{ $req->id }}">
		                            
		                            <td>{{ $req->position}}</td>
		                            <td>{{ $req->mattress}}</td>
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
		                            <td>{{ $req->id}}</td>
									<td>
										@if ($req->status == 'TO_LOAD')
											<a href="{{ url('mattress_to_load/'.$req->id) }}" class="btn btn-info btn-xs center-block"
												@if ($req->status == 'ON_HOLD')
													disabled
												@endif>
												Load mattress</a>
										@else
											<a href="{{ url('mattress_to_spread/'.$req->id) }}" class="btn btn-warning btn-xs center-block"
												@if ($req->status == 'ON_HOLD')
													disabled
												@endif
												>Spread mattress</a>
										@endif
									</td>
									<!-- <td>
										@if ($req->status == 'TO_SPREAD')
											<a href="{{ url('mattress_to_unload/'.$req->id) }}" class="btn btn-info btn-xs center-block">Unload mattress</a>
										@endif	
									</td> -->
									<td>
										<a href="{{ url('other_functions/'.$req->id) }}" class="btn btn-default btn-xs center-block"
											@if ($req->status == 'ON_HOLD')
													disabled
												@endif
												>Other functions</a>
										<!-- <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModalCenter" data-id="{{ $req->id }}" data-value="{{ $req->comment_operator }}" data-push="{{ $req->status}}">
  											Other functions
										</button> -->
										<!-- {!! Form::open(['method'=>'POST', 'url'=>'other_functions']) !!}
											{!! Form::hidden('id', $req->id, ['class' => 'form-control']) !!}
											{!! Form::submit('Other functions', ['class' => 'btn btn-default btn-xs center-block']) !!}
	            			            {!! Form::close() !!} -->

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