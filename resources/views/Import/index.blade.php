@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">

			@if(Auth::check() )

				@if(Auth::check() && Auth::user()->name == 'cutting') 
				<div class="panel panel-default">
					<div class="panel-heading">Import TPP material from Excel file</div>

					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImportMaterials']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file1', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import Items', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>
				@endif

				@if(Auth::check() && Auth::user()->name == 'magacin') 
				<div class="panel panel-default">
					<div class="panel-heading">Import Reported to logistic with Excel file</div>
					<p>Note: Excel file should contian: skeda, reported_to_log (YES/NO)</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImportWastage_report']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file2', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>
				@endif

				@if(Auth::check() && (Auth::user()->name == 'admin') OR (Auth::user()->name == 'planner'))
				<div class="panel panel-default">
					<div class="panel-heading">Import Marker XML file</div>
					
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['postImport_marker']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file3', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">Import Excel file (MAT, PRO, PAS)</div>
					
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImport_skeda']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file4', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>

				@endif
			@endif

			<div class="panel panel-default">
				<div class="panel-body">
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default btn-lg center-block">Back to main menu</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@endsection