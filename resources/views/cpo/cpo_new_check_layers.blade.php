@extends('app')

@section('content')


<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
        	<br>


            <div class="panel panel-default">
				<div class="panel-heading">Add new layer line</div>
				<!-- <br> -->
					
						
					{!! Form::open(['method'=>'POST', 'url'=>'/cpo_new_check_layers_post']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
						{!! Form::hidden('style', $style, ['class' => 'form-control']) !!}
						{!! Form::hidden('size', $size, ['class' => 'form-control']) !!}
						{!! Form::hidden('bundle', $bundle, ['class' => 'form-control']) !!}
						{!! Form::hidden('part', $part, ['class' => 'form-control']) !!}

						<div class="panel-body">
		                    <p>Layer: <span style="color:red">*</span></p>
		                    {!! Form::input('number', 'layer', '', ['class' => 'form-control','required'=>'required' ]) !!}
		                </div>

		                <div class="panel-body">
		                    <p>Length: <span style="color:red">*</span></p>
		                    {!! Form::input('number', 'length', '', ['class' => 'form-control','step'=>'0.01','required'=>'required']) !!}
		                </div>

		                <div class="panel-body">
		                    <p>Width: <span style="color:red">*</span></p>
		                    {!! Form::input('number', 'width', '', ['class' => 'form-control','step'=>'0.01','required'=>'required']) !!}
		                </div>

		               

						<br>
						<br>
						{!! Form::submit('Confirm', ['class' => 'btn  btn-danger center-block']) !!}
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