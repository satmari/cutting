@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">Search <b>mattresses</b> by style and color on <big><b>SP0</b></big></div>
				<!-- <br> -->
					
					
					{!! Form::open(['method'=>'POST', 'url'=>'/recap_by_sku_sp0_post']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						<div class="panel-body">
                        <p>Style and color: <span style="color:red;">*</span></p>
                        <p><small><i>Example search: CL159C   or  CL159C 843I</i></small></p>
                        
                            
                            {!! Form::text('sku', '',array('class' => 'form-control')) !!} 
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