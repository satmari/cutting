@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Scan LR roll</div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>
				@endif

				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'o_roll_scan_post']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />
				
				
				<div class="panel-body" >
                	{!! Form::text('roll', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>
                <!-- <br> -->
                
        		
        		<hr>
				{!! Form::submit('Next', ['class' => 'btn btn-success center-block']) !!}
				

				@include('errors.list')
				{!! Form::close() !!}
				</div>

				<!-- <hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-blo k">Back</a>
				</div> -->
				
			</div>
		</div>
	</div>
</div>
@endsection