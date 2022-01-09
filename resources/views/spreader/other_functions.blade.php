@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Other (spreader) functions for: {{ $g_bin }}</b></div>
					

				@if (isset($data2))
                <table style="width:100%" class="table table-striped table-bordered">
                    <tr>
                        <td>Leftover rolls</td>
                        <td>Number of joinings</td>
                    </tr>
                     @foreach ($data2 as $req)
                     <tr>
                        <td><b>{{ $req->o_roll}}</b></td>
                        <td><b>{{ $req->no_of_joinings}}</b></td>
                     </tr>
                     @endforeach
                </table>
                @endif
                <br>
			    	
			    <div class="panel-body">	
				@if ($status == 'TO_SPREAD')
					<a href="{{ url('mattress_to_unload/'.$id) }}" class="btn btn-warning center-block" >Unload mattress</a>
					<br>
					<a href="{{ url('change_marker_request/'.$id) }}" class="btn btn-info center-block">Change marker request (width)</a>
					<br>
					<a href="{{ url('split_marker_request/'.$id) }}" class="btn btn-primary center-block">Split marker request (width, height)</a>
					<!-- <br>
					<a href="{{ url('create_new_mattress_request/'.$id) }}" class="btn btn-info center-block">Create new mattress request</a> -->
				@elseif ($status == 'TO_LOAD')

					<a href="{{ url('mattress_to_unload/'.$id) }}" class="btn btn-warning center-block" disabled>Unload mattress</a>
					<br>
					<a href="{{ url('change_marker_request/'.$id) }}" class="btn btn-info center-block">Change marker request (width)</a>
					<br>
					<a href="{{ url('split_marker_request/'.$id) }}" class="btn btn-primary center-block">Split marker request (width, height)</a>
					<!-- <br>
					<a href="{{ url('create_new_mattress_request/'.$id) }}" class="btn btn-info center-block">Create new mattress request</a> -->
				@elseif ($status == 'SUSPENDED')

					<a href="{{ url('mattress_to_unload/'.$id) }}" class="btn btn-warning center-block" disabled>Unload mattress</a>
					<br>
					<a href="{{ url('change_marker_request/'.$id) }}" class="btn btn-info center-block" disabled>Change marker request (width)</a>
					<br>
					<a href="{{ url('split_marker_request/'.$id) }}" class="btn btn-primary center-block" disabled>Split marker request (width, height)</a>
					<!-- <br>
					<a href="{{ url('create_new_mattress_request/'.$id) }}" class="btn btn-info center-block" disabled>Create new mattress request</a> -->
				@endif	

				<hr>
				{!! Form::open(['method'=>'POST', 'url'=>'add_operator_comment']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}
					
				<div class="panel-body">
	                <p>Comment operator:</p>
	            	{!! Form::textarea('comment_operator', $comment_operator , ['class' => 'form-control', 'rows' => 5]) !!}
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
	            <a href="{{ url('spreader') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>

			</div>	
			</div>
		</div>
	</div>
</div>

@endsection