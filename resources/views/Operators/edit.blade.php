@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Edit Operator</b></div>
				
				{!! Form::open(['url' => 'operator_edit_post']) !!}

				{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}
				
				<div class="panel-body">
				<p>Operator name: </p>
					{!! Form::text('operator', $data->operator, ['class' => 'form-control', 'autofocus' => 'autofocus', 'disabled'=>'disabled']) !!}
				</div>

				<div class="panel-body">
					<span>Status:</span>
					{!! Form::select('status', array('ACTIVE'=>'ACTIVE','NOT ACTIVE'=>'NOT ACTIVE'), $data->status, array('class' => 'form-control')) !!} 
				</div>

				<!-- <div class="panel-body">
					<span>Device:</span>
					{!! Form::select('device', array(''=>'','SP'=>'SP - Spreader operator','MS'=>'MS - Manual spreader operator','MM'=>'MM - Mini marker operator','LR'=>'LR - Leftover rewinder','PSO'=>'PSO - Paspul sewing operator','PRW'=>'PRW - Paspul rewinding operator','PCO'=>'PCO - Paspul cutting operator','PACK'=>'PACK - Packing operator','PLOT'=>'PLOT - Ploter operator','CUT'=>'CUT - Cutter operator'), null, array('class' => 'form-control')) !!} 
				</div> -->

				<div class="panel-body">
				<span>Device:</span> 

					<table style="width:100%">
						<th style="width:100%"></th>
							@foreach ($op as $d => $k)
							<tr>
	  							<td style="width:80%">
	  								<div class="checkbox">
								    	<label style="width: 90%;" type="button" class="btn check btn-default"  data-color="primary">
								      		<input type="checkbox" class="btn check" name="device[]" value="{{$d}}" 
								      		{{ strpos(strtoupper($data->device), $d) }}

								      		@if(is_numeric(strpos(strtoupper($data->device), $d) ))
								      		checked
								      		@endif
								      		>
								      		<input name="hidden[]" type='hidden' value="{{$d}}">
								      		{{$d}} - <small><i>{{$k}}</i></small>
								    	</label>
								  	</div>
	  						 	</td>
	  						</tr>
	  						@endforeach
	  				</table>

				</div>

				<div class="panel-body">
					{!! Form::submit('Save', ['class' => 'btn btn-success btn-l  center-block']) !!}
				</div>

				@include('errors.list')

				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>
@endsection