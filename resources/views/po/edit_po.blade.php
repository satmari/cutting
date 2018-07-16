@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Po name: (existing name: {!! $po->po !!})</div>
				<br>

				{!! Form::model($po , ['method' => 'POST', 'url' => 'edit_po/'.$po->id /*, 'class' => 'form-inline'*/]) !!}

					<div class="alert alert-warning">
					  <strong>Warning!</strong> Changing Po name, will change also name in reservations table
					</div>
					<div class="panel-body">
						
						{!! Form::hidden('id', $po->id, ['class' => 'form-control']) !!}
						{!! Form::hidden('po', $po->po, ['class' => 'form-control']) !!}
						
						<div class="panel-body">
						<p>PO new name: </p>
							{!! Form::input('string', 'new_name', $po->po, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
							
						</div>

					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

				{!! Form::close() !!}
				
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/po')}}" class="btn btn-default">Back</a>
					</div>
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection