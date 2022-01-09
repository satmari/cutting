@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
        	<div class="panel panel-default">
		    <div class="panel-heading">1. Scan G-bin:</div>
		           	<div class="panel-body">
		           		
		           		{!! Form::open(['url' => 'o_roll_gbin']) !!}

							<div class="panel-body">
							<!-- <p>Gbin: </p> -->
								{!! Form::text('gbin', '', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
							</div>
							
							<div class="panel-body">
								{!! Form::submit('Next', ['class' => 'btn btn-success btn-l  center-block']) !!}
							</div>

						@include('errors.list')
						{!! Form::close() !!}

					</div>
				</div>
			
        </div>
    </div>
</div>

@endsection