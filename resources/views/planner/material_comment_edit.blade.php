@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-3 col-md-offset-4">
            <div class="panel panel-default">
				<div class="panel-heading">Edit <b>standard comment </b>for material
				&nbsp;&nbsp;&nbsp;&nbsp;
					{!! Form::open(['method'=>'POST', 'url'=>'material_comment_delete_post']) !!}
					{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}
					{!! Form::submit('Delete comment', ['class' => 'btn  btn-danger btn-xs center-block']) !!}
					{!! Form::close() !!}

				</div>
					{!! Form::model($data , ['method' => 'POST', 'url' => 'material_comment_edit_post' ]) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />
						{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}

						<div class="panel-body">
                        <p>Material: <span style="color:red;">*</span></p>
                            
                            {!! Form::text('material', $data->material,array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Standard comment: <span style="color:red;">*</span></p>
                            
                            {!! Form::textarea('standard_comment', $data->standard_comment, ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
						
						
						{!! Form::submit('Save', ['class' => 'btn  btn-success center-block']) !!}
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