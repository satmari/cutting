@extends('app')

@section('content')


<div class="container container-table">
    <div class="row vertical-center-row">
    <br>

		<div class="text-center col-md-8 col-md-offset-2">

			<div class="panel panel-default">
	        	<div class="panel panel-default">
					<div class="panel-heading" style="background-color:#fff2c3">History for g_bin:<b><big>{{$g_bin}}</big></b>, 
						style:<b><big>{{$style}}</big></b>, 
						size:<b><big>{{$size}}</big></b>, 
						bundle:<b><big>{{$bundle}}</big></b>, 
						part:<b><big>{{$part}}</big></b>
						
						<!-- <br> -->
						<br>
						<!-- <a href="javascript:history.go(-1)">Go Back</a> -->
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{!! Form::open(['url' => 'cpo_header_table']) !!}
								{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
								{!! Form::submit('Back to g_bin', ['class' => 'btn btn-primary btn-xs']) !!}
							{!! Form::close() !!}

						
						<br>
						<!-- <a href="{{ url('cpo_new_check_layers/'.$id) }}" class="btn btn-warning btn-xs center-bl ock">Add layer line</a> -->

						{!! Form::open(['method'=>'POST', 'url'=>'cpo_new_check_layers']) !!}
													
							{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
							{!! Form::hidden('style', $style, ['class' => 'form-control']) !!}
							{!! Form::hidden('size', $size, ['class' => 'form-control']) !!}
							{!! Form::hidden('bundle', $bundle, ['class' => 'form-control']) !!}
							{!! Form::hidden('part', $part, ['class' => 'form-control']) !!}
							

							{!! Form::submit('Add new layer line', ['class' => 'btn btn-xs btn-success']) !!}
							@include('errors.list')

						{!! Form::close() !!}

						
						
					</div>

	            	<div class="input-group"> <span class="input-group-addon">Filter</span>
		                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
		                </div>

		                <table class="table table-st riped table-bordered tableFixHead" id="table-draggable2" 
		                
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
		                    		<th>G bin</th>
		                    		<th>Style</th>
		                    		<th>Size</th>
		                    		<th>Bundle</th>
		                    		<th>Part</th>
		                            <th><b><big>Layer</big></b></th>
		                    		<th>Length</th>
		                            <th>Width</th>
		                            <th>Operator</th>
		                            
		                            <th></th>
		                    	</tr>
		                    </thead> 
		                    <tbody class="searc hable">
		                    	
		                    @foreach ($data as $req)
		                        <tr class="" id="" >
		                        	<td>{{ $req->g_bin}}</td>
		                        	<td>{{ $req->style}}</td>
		                        	<td>{{ $req->size}}</td>
		                        	<td>{{ $req->bundle}}</td>
		                        	<td>{{ $req->part}}</td>
		                        	<td><b><big>{{ $req->layer}}</big></b></td>
		                        	<td>{{ round($req->length,1)}}</td>
		                        	<td>{{ round($req->width,1)}}</td>
		                        	<td>{{ $req->operator }}</td>
		                        	
		                        	<td><a href="{{ url('cpo_edit_check_layers/'.$req->id) }}" class="btn btn-danger btn-xs center-bl ock">Edit layer line</a></td>
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