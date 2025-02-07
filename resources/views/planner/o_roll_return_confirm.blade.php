@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Return to stock leftover roll</div>
				<!-- <br> -->
					
					<div class="alert alert-warning" role="alert">
					 
					 	Are you sure to return leftover roll?
					  
					</div>
					
					{!! Form::open(['method'=>'POST', 'url'=>'/o_roll_return_confirm']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						
						{!! Form::submit('Confirm', ['class' => 'btn  btn-warning center-block']) !!}
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