@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Edit marker</b></div>
				
				
					{!! Form::open(['url' => 'marker_edit_confirm']) !!}
						
						{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Status:<span style="color:red;">*</span></p>
	               		{!! Form::select('status', array('ACTIVE'=>'ACTIVE', 'NOT ACTIVE' => 'NOT ACTIVE'), $data->status, array('class' => 'form-control', 'autofocus' => 'autofocus')) !!} 
					</div>
					
					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success btn-lg center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}

				
				<br>
				
			</div>
		</div>
	</div>
</div>
@endsection
