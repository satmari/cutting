@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-3 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading">Add <b>standard comment </b>for material<a href=" comments"></a></div>
				<!-- <br> -->
					
					
					{!! Form::open(['method'=>'POST', 'url'=>'/material_comment_new_post']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						<div class="panel-body">
                        <p>Material: <span style="color:red;">*</span></p>
                            
                            {!! Form::text('material', '',array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Standard comment: <span style="color:red;">*</span></p>
                            
                            {!! Form::textarea('standard_comment', '', ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
						
						
						{!! Form::submit('Save', ['class' => 'btn  btn-danger center-block']) !!}
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