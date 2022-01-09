@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Delete marker</div>
				<!-- <br> -->
					
					<div class="alert alert-danger" role="alert">
					 
					 	Are you sure to delete marker?
					 	<br>
					 	<br>
					 	
					 	<p style="color=red">You can only delete marker that isn't used!</p>
					  
					</div>
					
					{!! Form::open(['method'=>'POST', 'url'=>'/marker_delete_confirm']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						
						
						{!! Form::submit('Confirm', ['class' => 'btn  btn-danger center-block']) !!}
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