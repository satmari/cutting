@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
        	<br>
            <div class="panel panel-default">

            	


				<div class="panel-heading">Type or scan g_bin</div>
				<!-- <br> -->
					
					
					{!! Form::open(['url' => 'cpo_header_table']) !!}
					<input name="_token" type="hidden" value="{!! csrf_token() !!}" />
					
						<div class="panel-body">
						<p>G bin:</p>
							{!! Form::text('g_bin', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
						</div>

						
						<hr>
						<div class="panel-body">
							{!! Form::submit('Next', ['class' => 'btn btn-success btn-l  center-block']) !!}
						</div>

						@include('errors.list')
						
					{!! Form::close() !!}
						
					<br>

					@if (isset($msge))
	            		<div class="alert alert-danger" role="alert">
	            			{{ $msge }}
						</div>
					@endif

					
				<!-- <hr> -->
				
			</div>
		</div>
	</div>
</div>

@endsection