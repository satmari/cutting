@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading h-b">Edit Wastage</b></b>
					<br>

				</div>
				<br>
				
				{!! Form::model($wastage_line , ['method' => 'POST', 'url' => '/wastage_edit_post']) !!}

				{!! Form::hidden('id', $wastage_line->id, ['class' => 'form-control']) !!}
				
				<div class="panel-body">
					<span>Weight:</span>
					{!! Form::input('number', 'weight', round($wastage_line->weight,2), ['class' => 'form-control','step' => '0.01']) !!}
				</div>
				<div class="panel-body">
					<span>Reported to logistic:</span>
					{!! Form::select('log_rep', array(''=>'','YES'=>'YES','NO'=>'NO'), $wastage_line->log_rep, array('class' => 'form-control')) !!} 
				</div>
				
				<div class="panel-body">
					{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

				
					
			</div>
		</div>
	</div>
</div>

@endsection