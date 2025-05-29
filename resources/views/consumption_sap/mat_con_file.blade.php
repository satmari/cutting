@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
            <div class="panel panel-default">
				<div class="panel-heading">MAT CON file </div>
				<!-- <br> -->
					
					
					{!! Form::open(['method'=>'POST', 'url'=>'/mat_con_file_post']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						<div class="panel-body">
							<span>OD: <b>(format: m/d/y)</b>:</span>
							<input type="date" name="import_date_od" id="date" class="form-control" style="width: 100%; display: inline;" value="{{ date('Y-m-d')  }}">
						</div>

						<div class="panel-body">
							<span>DO:  <b>(format: m/d/y)</b>:</span>
							<input type="date" name="import_date_do" id="date" class="form-control" style="width: 100%; display: inline;" value="{{ date('Y-m-d')  }}">
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