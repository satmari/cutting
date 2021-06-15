@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Print nalog?</div>
				<!-- <br> -->
					
					<div class="alert alert-warning" role="alert">
					 
					 	Are you sure to print nalog?
					  
					</div>
					
					{!! Form::open(['method'=>'POST', 'url'=>'print_mattress_confirm']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						
						<div class="panel-body">
                        <p>Choose printer: <span style="color:red;">*</span></p>
                            
                            {!! Form::select('printer', array('Cutting A4'=>'Cutting A4','Magacin A4'=>'Magacin A4','Workstudy A4'=>'Workstudy A4'), '',array('class' => 'form-control')) !!} 
                        </div>
						<hr>
						{!! Form::submit('Print nalog', ['class' => 'btn  btn-success center-block']) !!}
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