@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Add Operator for Relax ans Inspe</b></div>
				
				{!! Form::open(['url' => 'operator_others_create_post']) !!}
				
				<div class="panel-body">
				<p>R number: </p>
					{!! Form::text('rnumber', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
				</div>

				<div class="panel-body">
				<p>Operator name: </p>
					{!! Form::text('operator', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
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