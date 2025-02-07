@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Request material from location: {{ $location }}</b>
					<br>
					<br>
					<p>Material: <b>{{ $material }}</b></p>
					<p>Dye lot:  <b>{{ $dye_lot }}</b></p>
				</div>
				
				{!! Form::open(['method'=>'POST', 'url'=>'request_material_p_insert']) !!}

					{!! Form::hidden('material', $material) !!}
					{!! Form::hidden('dye_lot', $dye_lot) !!}
					{!! Form::hidden('location', $location) !!}
					{!! Form::hidden('device', $device) !!}
				
				<br>
				<div class="panel-body">
	                <p>Required quanity:</p>
	            	{!! Form::number('required_qty', 0 , ['class' => 'form-control']) !!}
				</div>
				<br>
				<div class="panel-body">
	                <p>Comment:</p>
	            	{!! Form::textarea('comment', NULL , ['class' => 'form-control', 'rows' => 3]) !!}
				</div>

				{!! Form::submit('Save request', ['class' => 'btn  btn-success center-block']) !!}
	            <br>

	            @include('errors.list')
	            {!! Form::close() !!}

	            @if (isset($success))
				<div class="alert alert-success" role="alert">
				  {{ $success }}
				</div>
				@endif
				@if (isset($danger))
				<div class="alert alert-danger" role="alert">
				  {{ $danger }}
				</div>
				@endif

	            <hr>
	            <br>
	            	<a href="{{ url('prw') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>
			</div>	
			</div>
		</div>
	</div>
</div>

@endsection