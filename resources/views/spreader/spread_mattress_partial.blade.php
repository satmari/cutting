@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Spread mattress <big>PARTIALLY</big>: {{ $mattress }}</b></div>
				<br>
			    	
				{!! Form::open(['url' => 'spread_mattress_partial_post']) !!}
					
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Layers partially:<span style="color:red;">*</span></p>
	               		{!! Form::input('number', 'layers_a', $layers_a, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
						<p>Comment operator:<span style="color:red;">*</span></p>
	               		{!! Form::input('string', 'comment_operator', $comment_operator, ['class' => 'form-control']) !!}
					</div>
					<br>
					<div class="panel-body">
						{!! Form::submit('Confirm spread partially', ['class' => 'btn btn-success btn-lg center-block']) !!}
					</div>

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
	            <a href="{{ url('spreader') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>

@endsection