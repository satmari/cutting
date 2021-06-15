@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Print OR barcodes</div>
				<!-- <br> -->
					
					<div class="alert alert-warning" role="alert">
					 
					 	Are you sure to print {{ $no }} label/s, from label {{ $from }} to {{ $to }} , on printer {{ $printer }} ?
					  
					</div>
					
					{!! Form::open(['method'=>'POST', 'url'=>'/o_roll_print_confirm_print']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('printer', $printer, ['class' => 'form-control']) !!}
						{!! Form::hidden('labels', $labels, ['class' => 'form-control']) !!}
						{!! Form::hidden('no', $no, ['class' => 'form-control']) !!}
						{!! Form::hidden('lu', $lu, ['class' => 'form-control']) !!}
						
						
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