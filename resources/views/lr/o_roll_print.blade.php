@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Print LR barcodes</div>
				<!-- <br> -->
					
					<div class="alert alert-danger" role="alert">
					  <p>Selected printer shoud have label with dimension 4x5 in two rows!</p>
					  <p>Application will print lables starting from last used.</p>
					  <p>Application will not print labels that are already used!</p>

					</div>

					{!! Form::open(['method'=>'POST', 'url'=>'/o_roll_print_confirm']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />
						
						<div class="panel-body">
							<p>Last used label: {{ $last_used }}</p>
							
						</div>

						<div class="panel-body">
							<p>Label: </p>
							{!! Form::select('labels', array('LR'=>'LR'), 'LR', array('class' => 'form-control')); !!} 
						</div>

						<div class="panel-body">
							<p>Printer:</p>
							{!! Form::select('printer_name', array('Preparacija Zebra'=>'Preparacija Zebra'), null, array('class' => 'form-control')); !!} 
						</div>

						<div class="panel-body">
						<p>Number of labels to print:  <span style="color:red;">*</span></p>
							{!! Form::number('no', null, ['class' => 'form-control',  'autofocus' => 'autofocus']) !!}
						</div>

						@if(isset($msgs))
							<div class="alert alert-success" role="alert">
							  {{ $msgs }}
							</div>
						@endif

						@if(isset($msge))
							<div class="alert alert-danger" role="alert">
							  {{ $msge }}
							</div>
						@endif
						<br>
						{!! Form::submit('Print', ['class' => 'btn  btn-success center-block']) !!}
						@include('errors.list')

						{!! Form::close() !!}
					<!-- <hr> -->
					<br>

					
				<!-- <hr> -->
				
			</div>
		</div>
	</div>
</div>

@endsection