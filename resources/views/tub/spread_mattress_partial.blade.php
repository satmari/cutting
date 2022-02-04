@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Spread <big>PARTIALLY</big> g_bin: {{ $g_bin }}</b></div>
				<br>
			    	
				{!! Form::open(['url' => 'spread_mattress_tub_partial_post']) !!}
					
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Layers partially:<span style="color:red;">*</span></p>
	               		{!! Form::input('number', 'layers_a', $layers_a, ['class' => 'form-control']) !!}
					</div>

					<div class="panel-body">
						<p>Comment operator:<span style="color:red;">*</span></p>
	               		{!! Form::textarea('comment_operator', $comment_operator , ['class' => 'form-control', 'rows' => 2]) !!}
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
	            <a href="{{ url('tub') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>

@endsection