@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Cancel request?</div>
				<h3 style="color:red;"></h3>
				@if (isset($msg))
				<p style="color:red;">{{ $msg }}</p>
				@endif 	

				{!! Form::open(['method'=>'POST', 'url'=>'request_material_cancel_confirm']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}

					<div class="panel-body">
		                <p>Comment warehouse:</p>
		            	{!! Form::textarea('comment_wh', '' , ['class' => 'form-control', 'rows' => 2, 'required' => 'required']) !!}
					</div>
					<br>
				
				{!! Form::submit('Cancel', ['class' => 'btn btn-xl  btn-danger center-block']) !!}
				{!! Form::close() !!}
				<br>
				

				<!-- <div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
						<button class="btn btn-default center-block" onclick="history.back()">Go Back</button>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>

@endsection