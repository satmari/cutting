@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Print multiple nalog</div>
				<!-- <h3 style="color:red;">Error!</h3> -->
				<div class="panel-body">
					<br>
					@if (isset($msg))
					<p style="color:red;">{{ $msg }}</p>
					@endif 

					{!! Form::open(['method'=>'POST', 'url'=>'print_mattress_multiple_mm_complete']) !!}

					<div class="alert alert-warning" role="alert">
					 
					 	Are you sure to print multiple nalog?
					  
					</div>
					
						{!! Form::hidden('items', $items, ['class' => 'form-control']) !!}
						
						<div class="panel-body">
                        <p>Choose printer: <span style="color:red;">*</span></p>
                            
                            @if (config('app.global_variable') == 'gordon')
								{!! Form::select('printer', array('Cutting A4'=>'Cutting A4','Magacin A4'=>'Magacin A4','Workstudy A4'=>'Workstudy A4','IT'=>'IT'), '',array('class' => 'form-control')) !!} 
							@elseif (config('app.global_variable') == 'fiorano')
								{!! Form::select('printer', array('SBR-PRT05'=>'SBR-PRT05','SBR-PRT10'=>'SBR-PRT10'), '',array('class' => 'form-control')) !!} 
							@else
								
							@endif
                        </div>
						<hr>
						{!! Form::submit('Print nalog', ['class' => 'btn  btn-success center-block']) !!}
						@include('errors.list')

					{!! Form::close() !!}
					
					<!-- <hr>
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

@endsection