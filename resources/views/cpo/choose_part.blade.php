@extends('app')

@section('content')



<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
        	<br>
            <div class="panel panel-default">
				<div class="panel-heading">Create new check</div>
				<!-- <br> -->
					
						
					{!! Form::open(['method'=>'POST', 'url'=>'/cpo_insert_part']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
						{!! Form::hidden('location', $location, ['class' => 'form-control']) !!}
						{!! Form::hidden('style', $style, ['class' => 'form-control']) !!}
						{!! Form::hidden('size', $size, ['class' => 'form-control']) !!}
						
						{!! Form::hidden('select_bundle', $select_bundle, ['class' => 'form-control']) !!}

							<div class="panel-body">
	                       		<p>Choose garment part: <span style="color:red;">*</span></p>
							       <div class="col">
							       		<select class="form-control" id="select_part" name="select_part" required focus>
							    				<option value="" disabled selected>Please select</option>        
							    				@foreach($data_parts as $req)
							    					<option value="{{$req->part}}">{{ $req->part }}</option>
							    				@endforeach
							  			</select>
							  		</div>
	                       	</div>

	                       

						<br>
						<br>
						{!! Form::submit('Next', ['class' => 'btn  btn-danger center-block']) !!}
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