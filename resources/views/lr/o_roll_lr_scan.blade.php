@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
        	<div class="panel panel-default">
		    <div class="panel-heading">2. Scan LR barcode:</div>
		           	<div class="panel-body">
		           		
		           		{!! Form::open(['url' => 'o_roll_lr_scan']) !!}

		           			{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
		           			{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
		           			{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
		           			{!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
		           			{!! Form::hidden('material', $material, ['class' => 'form-control']) !!}
		           			
							<div class="panel-body">
							<!-- <p>Gbin: </p> -->
								{!! Form::text('o_roll', '', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
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