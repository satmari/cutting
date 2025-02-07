@extends('app')

@section('content')
<div class="container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Po status:</div>
				<br>

				{!! Form::model($po , ['method' => 'POST', 'url' => 'edit_status/'.$po->id /*, 'class' => 'form-inline'*/]) !!}

					<div class="alert alert-warning">
					  <strong>Warning!</strong> Changing Po status to close will cancel all reservations for this po
					</div>
					<div class="panel-body">
						
						{!! Form::hidden('id', $po->id, ['class' => 'form-control']) !!}
						{!! Form::hidden('po', $po->po, ['class' => 'form-control']) !!}
						
						<div class="panel-body">
						<p>PO Status: </p>
							{{-- {!! Form::select('status', array('OPEN'=>'OPEN','CLOSED'=>'CLOSED'), $po->status, array('class' => 'form-control')) !!} --}}
							{!! Form::select('status', array('CLOSED'=>'CLOSED'), $po->status, array('class' => 'form-control', 'autofocus' => 'autofocus')) !!} 
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