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

				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'skeda_comment_post']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />


				<div class="panel-body">
				<p>Skeda: <span style="color:red;">*</span></p>
                	{!! Form::text('skeda', '',array('class' => 'form-control')) !!} 
                </div>

                <div class="panel-body">
				<p>Comment: <span style="color:red;">*</span></p>
                	{!! Form::textarea('comment', '', ['class' => 'form-control', 'cols' => '40', 'rows' => '10']) !!}
                	 
                </div>
				
				{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
				

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