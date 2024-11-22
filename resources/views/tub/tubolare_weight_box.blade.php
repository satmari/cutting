@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Measure Tubolare bag
				</div>
				<br>
				{!! Form::open(['url' => 'tubolare_weight_box_weight_post']) !!}
					
					{!! Form::hidden('box', $box, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Insert weight in kilograms:<span style="color:red;">*</span></p>
	               		{!! Form::input('number', 'weight', '', ['class' => 'form-control', 'step' => '0.01'  , 'autofocus' => 'autofocus']) !!}
					</div>

					<br>
					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success btn-lg center-block']) !!}
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

	            
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>

@endsection