@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add Skeda comment</div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>

				@endif

				{!! Form::open(['method'=>'POST', 'url'=>'skeda_comment_delete']) !!}
				{!! Form::hidden('id', $skeda_comment->id, ['class' => 'form-control']) !!}
				{!! Form::submit('Delete', ['class' => 'btn  btn-danger btn-xs center-block']) !!}
				{!! Form::close() !!}



				{!! Form::model($skeda_comment , ['method' => 'POST', 'url' => 'skeda_comment_edit_post' ]) !!}

					{!! Form::hidden('id', $skeda_comment->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body">
						<span>Skeda: <span style="color:red;">*</span></span>
						{!! Form::input('string', 'skeda', $skeda_comment->skeda, ['class' => 'form-control']) !!}
					</div>
					
					<div class="panel-body">
						<span>Comment: <span style="color:red;">*</span></span>
						
						{!! Form::textarea('comment',  $skeda_comment->comment, ['class' => 'form-control', 'cols' => '40', 'rows' => '10']) !!}
					</div>
					

					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}

					</div>

					
				

				@include('errors.list')
				{!! Form::close() !!}
				</div>

					
					

				<!-- <hr> -->
				<!-- <div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-blo ck">Back</a>
				</div> -->
				</div>
				
			</div>
		</div>
	</div>
</div>
@endsection