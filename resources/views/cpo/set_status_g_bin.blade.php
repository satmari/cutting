@extends('app')

@section('content')


<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
        	<br>


            <div class="panel panel-default">
				<div class="panel-heading">Change status {{ $status }}</div>
				<!-- <br> -->
					
						
					{!! Form::open(['method'=>'POST', 'url'=>'set_status_g_bin_post']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
						
	                        <div class="panel-body">
								<span>Status: <span style="color:red;">*</span></span>
								<div class="col">
						       		<select class="form-control" id="status" name="status" required focus>
						       				
						       				@if ($status != '')
						       					<option value="{{ $status }}" selected>{{ $status }}</option>   
						       				@else 
						       					<option value="" disabled selected>Please select</option>   
						       				@endif
						    				
						    				@if ($status == 'Pending')
						    					
						    				@else 
						    					<option value="Pending">Pending</option>
						    				@endif

						    				@if ($status == 'Ready for production')
						    					
						    				@else 
						    					<option value="Ready for production">Ready for production</option>
						    				@endif

						    				@if ($status == 'Not checked')
						    					
						    				@else 
						    					<option value="Not checked">Not checked</option>
						    				@endif

					    					
					    			</select>
						  		</div>
							</div>

						<div class="panel-body">
							<p>Comment:</p>
		               		{!! Form::textarea('comment', $comment , ['class' => 'form-control', 'rows' => 2]) !!}
						</div>

						<br>
						<br>
						{!! Form::submit('Save', ['class' => 'btn  btn-danger center-block']) !!}
						@include('errors.list')

					{!! Form::close() !!}
					<!-- <hr> -->
					<br>

					@if (isset($msge))
	            		<div class="alert alert-danger" role="alert">
	            			{{ $msge }}
						</div>
					@endif

					<a href="javascript:history.go(-1)" class="btn btn-p rimary btn-x s">Back</a>
					<br>
					<br>
			</div>
		</div>

	</div>
</div>

@endsection