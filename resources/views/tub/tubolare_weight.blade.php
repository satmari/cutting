@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Scan Tubolare Bag 
					<br>
				<a href="{{ url('tubolare_weight_table') }}">Table of scaned tubolare bags</a>
			</div>
				<br>
				{!! Form::open(['url' => 'tubolare_weight_box_post']) !!}
					
					<div class="panel-body">
						<p>Scan or insert bag barcode:<span style="color:red;">*</span></p>
	               		{!! Form::input('box', 'box', '', ['class' => 'form-control' , 'autofocus' => 'autofocus' ]) !!}
					</div>

					<br>
					<div class="panel-body">
						{!! Form::submit('Confirm', ['class' => 'btn btn-success btn-lg center-block']) !!}
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