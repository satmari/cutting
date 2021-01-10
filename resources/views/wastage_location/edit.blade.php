@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Edit wastage location</b></div>
				
				
					{!! Form::open(['url' => 'edit_wastage_location_post/'.$data->id]) !!}
					
					<div class="panel-body">
						<p>Location:<span style="color:red;">*</span></p>
	               		{!! Form::input('string', 'location', $data->location, ['class' => 'form-control']) !!}
					</div>
					
					<div class="panel-body">
						{!! Form::submit('Confirm', ['class' => 'btn btn-success btn-lg center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}

				
				<br>
				
			</div>
		</div>
	</div>
</div>
@endsection
