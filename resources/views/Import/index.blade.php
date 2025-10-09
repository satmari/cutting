@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">

			@if(Auth::check() )

				@if(Auth::check() && Auth::user()->name == 'cutting') 
				<div class="panel panel-default">
					<div class="panel-heading">Import TPP material (Excel file)</div>

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

				<div class="panel panel-default">
					<div class="panel-heading">Import Inbound Delivery table</div>
					<p></p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImportInbound_delivery']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file14', ['class' => 'center-block']) !!}
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
					<div class="panel-heading">Import Marker (XML file)</div>
					
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
					<div class="panel-heading">Import Skeda file (MAT, PRO, PAS) (Excel file)</div>
					
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['postImport_skeda']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file4', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>
				<br>
				<div class="panel panel-default">
					<div class="panel-heading">Import PAS bin (Excel file)</div>
					
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['postImport_pas_bin']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file5', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>

				<!-- <div class="panel panel-default">
					<div class="panel-heading">Import Consumption (Excel file)</div>
					
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImport_consumption']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file6', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div> -->

				<div class="panel panel-default">
					<div class="panel-heading">Marker - change status (Excel file)</div>
					
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImport_marker_status']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file7', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>

				@endif

				@if(Auth::check() && (Auth::user()->name == 'planner' OR Auth::user()->name == 'PSS1' OR Auth::user()->name == 'PSS2' OR Auth::user()->name == 'PSS3'
				OR Auth::user()->name == 'PSK1' OR Auth::user()->name == 'PSK2' OR Auth::user()->name == 'PSK3'
				OR Auth::user()->name == 'PSZ1' OR Auth::user()->name == 'PSZ2' OR Auth::user()->name == 'PSZ3'))
				<div class="panel panel-default">
					<div class="panel-heading">Import paspul stock (Excel file)</div>
					<p>Note: Excel file should contian:
					skeda,	type,	dye lot,	length,	location,	kotur qty,	uom (meter/square),	kotur width,	material
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImport_papsul_stock']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file8', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Return paspul from line (Excel file)</div>
					<p>Note: Excel file should contian:
					skeda,	type,	dye lot,	length,	location, kotur qty,	uom (meter/square),	kotur width,	material
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postReturn_papsul_stock']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file9', ['class' => 'center-block']) !!}
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
					<div class="panel-heading">Style parts import (Excel file)</div>
					<p>Note: Excel file should contian:
					Style,	Part
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'url'=>['/postImport_style_parts']]) !!}
					
						<div class="panel-body">
							{!! Form::file('file10', ['class' => 'center-block']) !!}
						</div>
						<div class="panel-body">
							{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
						</div>
						@include('errors.list')
					{!! Form::close() !!}
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Import BOM cons (Excel file)</div>
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@bom_cons_post']]) !!}
					
					<div class="panel-body">
						{!! Form::file('file11', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Import Skeda ratios (Excel file)</div>
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@skeda_ratio_post']]) !!}
					
					<div class="panel-body">
						{!! Form::file('file12', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Import cutting SMV (Excel file)</div>
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@bom_cutting_smv_post']]) !!}
					
					<div class="panel-body">
						{!! Form::file('file13', ['class' => 'center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Import', ['class' => 'btn btn-warning center-block']) !!}
					</div>
					@include('errors.list')
				{!! Form::close() !!}
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">Import <big>TUBOLARE</big> SMV (Excel file)</div>
					</p>
					{!! Form::open(['files'=>True, 'method'=>'POST', 'action'=>['importController@bom_cutting_tubolare_smv_post']]) !!}
					
					<div class="panel-body">
						{!! Form::file('file15', ['class' => 'center-block']) !!}
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