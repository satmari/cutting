@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Search <b>mattresses</b> by g_bin</div>
				<!-- <br> -->
					
					
					{!! Form::open(['method'=>'POST', 'url'=>'/recap_by_g_bin_mattress_post']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						<div class="panel-body">
                        <p>G bin: <span style="color:red;">*</span></p>
                            
                            {!! Form::text('g_bin', '',array('class' => 'form-control')) !!} 
                        </div>
						
						
						{!! Form::submit('Search', ['class' => 'btn  btn-success center-block']) !!}
						@include('errors.list')

					{!! Form::close() !!}
					<!-- <hr> -->
					<br>

					
				<!-- <hr> -->
				
			</div>
		</div>
	</div>
</div>

@endsection