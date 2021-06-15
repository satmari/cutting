@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Spread mattress<!--  <big>COMPLETELY</big> -->: {{ $mattress }}</b></div>
				<br>
			    	
				{!! Form::open(['url' => 'spread_mattress_complete_post']) !!}
					
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress_id', $mattress_id, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}
					{!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Layers actual:<span style="color:red;">*</span></p>
	               		{!! Form::input('number', 'layers_a', $layers_a, ['class' => 'form-control']) !!}
					</div>
					<hr>

					@if ($skeda_item_type != "MM")
					<div class="panel-body">
						<p>Reason if layer qty is different than planned </span></p>
	               		{!! Form::input('string', 'layers_a_reasons', $layers_a_reasons, ['class' => 'form-control']) !!}
					</div>
					{!! Form::hidden('layers_partial', $layers_partial, ['class' => 'form-control']) !!}
					@else
					<div class="panel-body">
						<p>Partial layers</span></p>
	               		{!! Form::input('number', 'layers_partial', 0, ['class' => 'form-control']) !!}
					</div>
					{!! Form::hidden('layers_a_reasons', $layers_a_reasons, ['class' => 'form-control']) !!}
					@endif

					<div class="panel-body">
						<p>Comment operator:</p>
	               		{!! Form::input('string', 'comment_operator', $comment_operator, ['class' => 'form-control']) !!}
					</div>
					<br>
					<div class="panel-body">
						{!! Form::submit('Confirm spread', ['class' => 'btn btn-success btn-lg center-block']) !!}
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