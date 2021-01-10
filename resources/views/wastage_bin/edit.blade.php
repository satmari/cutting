@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Edit wastage container</b></div>
				
				
					{!! Form::open(['url' => 'edit_wastage_bin_post/'.$data->id]) !!}
					
					<div class="panel-body">
						<p>Container:<span style="color:red;">*</span></p>
	               		{!! Form::input('string', 'container', $data->container, ['class' => 'form-control']) !!}
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
