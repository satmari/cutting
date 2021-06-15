@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Other (cutter) functions for: {{ $mattress }}</b></div>
					<br>
			    	
			    <div class="panel-body">	
				@if ($status == 'TO_SPREAD')
					<a href="{{ url('mattress_to_unload/'.$id) }}" class="btn btn-info center-block" >Unload mattress</a>
					<br>
					<a href="{{ url('change_marker_request/'.$id) }}" class="btn btn-info center-block">Change marker request (width)</a>
					<!-- <br>
					<a href="{{ url('create_new_mattress_request/'.$id) }}" class="btn btn-info center-block">Create new mattress request</a> -->
				@elseif ($status == 'TO_LOAD')

					<a href="{{ url('mattress_to_unload/'.$id) }}" class="btn btn-info center-block" disabled>Unload mattress</a>
					<br>
					<a href="{{ url('change_marker_request/'.$id) }}" class="btn btn-info center-block">Change marker request (width)</a>
					<!-- <br>
					<a href="{{ url('create_new_mattress_request/'.$id) }}" class="btn btn-info center-block">Create new mattress request</a> -->
				@elseif ($status == 'SUSPENDED')

					<a href="{{ url('mattress_to_unload/'.$id) }}" class="btn btn-info center-block" disabled>Unload mattress</a>
					<br>
					<a href="{{ url('change_marker_request/'.$id) }}" class="btn btn-info center-block" disabled>Change marker request (width)</a>
					<!-- <br>
					<a href="{{ url('create_new_mattress_request/'.$id) }}" class="btn btn-info center-block" disabled>Create new mattress request</a> -->
				@endif	

				<hr>
				{!! Form::open(['method'=>'POST', 'url'=>'add_operator_comment_cut']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}

				<div class="panel-body">
	                <p>Comment operator:</p>
	            	{!! Form::text('comment_operator', $comment_operator , ['class' => 'form-control']) !!}
				</div>

				{!! Form::submit('Save comment', ['class' => 'btn  btn-success center-block']) !!}
	            <br>
	            @include('errors.list')
	            {!! Form::close() !!}

	            @if (isset($success))
				<div class="alert alert-success" role="alert">
				  {{ $success }}
				</div>
				@endif
				@if (isset($danger))
				<div class="alert alert-danger" role="alert">
				  {{ $danger }}
				</div>
				@endif

	            <hr>
	            <a href="{{ url('cutter') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>

			</div>	
			</div>
		</div>
	</div>
</div>

@endsection