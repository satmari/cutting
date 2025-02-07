@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">1. Choose <b>FIRST</b> leftover roll</div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>

				@endif
				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'mini_marker_create_1']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />


				<div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
		        <br>
				<table style="width:100%">
				<thead>
					<th>Material</th>
					<th>- LR roll</th>
					<th>- G Bin</th>
					<th>- Mattress orig</th>
					<th>- Skeda</th>
					<th>- No or joinings</th>
					<th>- Mattress orig width </th>
				</thead>
				<tbody class="searchable">
				@for ($i = 0; $i < count($data); $i++)
				
				<tr>
					<td style="width:100%" colspan='7'>
						<div class="checkbox">
				    	<label style="width: 100%;" type="button" class="btn check btn-default"  data-color="primary">
				      		<input type="radio" class="btn check" name="items[]" value="{{ $data[$i]->o_roll }}#{{ $data[$i]->skeda }}" >
				      			{{ $data[$i]->material }} - {{ $data[$i]->o_roll }} - {{ $data[$i]->g_bin }} - {{ $data[$i]->mattress_name_orig }} - {{ $data[$i]->skeda }} - {{ $data[$i]->no_of_joinings }} - {{ round($data[$i]->marker_width,0) }}
				    	</label>
				  		</div>
				 	</td>
				</tr>
  				@endfor
  				</tbody>
				</table>
			  
				<br><br>
				{!! Form::submit('Next', ['class' => 'btn btn-success center-block']) !!}
				

				@include('errors.list')
				{!! Form::close() !!}
				</div>

				<!-- <hr> -->
				<!-- <div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-blo ck">Back</a>
				</div> -->
				</div>
				
			</div>
		</div>
	</div>
</div>
@endsection