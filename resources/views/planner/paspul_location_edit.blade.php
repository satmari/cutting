@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Paspul Location:</div>
				<br>
					{!! Form::model($location , ['method' => 'POST', 'url' => 'paspul_location_edit_post' ]) !!}

					{!! Form::hidden('id', $location->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body">
						<span>Location : <span style="color:red;">*</span></span>
						{!! Form::input('string', 'location', $location->location, ['class' => 'form-control']) !!}
					</div>
					<div class="panel-body">
						<span>Type: <span style="color:red;">*</span></span>
						{!! Form::select('type', array(''=>'', 'line'=>'line', 'stock'=>'stock'), null, array('class' => 'form-control')); !!} 
					</div>

					<div class="panel-body">
						<span>Plant: <span style="color:red;">*</span></span>
						{!! Form::select('plant', array(''=>'','Subotica'=>'Subotica','Kikinda'=>'Kikinda','Senta'=>'Senta'), null, array('class' => 'form-control')); !!} 
					</div>
					

					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}
					<br>
					
					{{-- 
					{!! Form::open(['method'=>'POST', 'url'=>'paspul_locations_delete']) !!}
					{!! Form::hidden('id', $location->id, ['class' => 'form-control']) !!}
					{!! Form::submit('Delete', ['class' => 'btn  btn-danger btn-xs center-block']) !!}
					{!! Form::close() !!}
					--}}
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/paspul_locations')}}" class="btn btn-default">Back</a>
					</div>
				</div>
					
			</div>
		</div>
	</div>
</div>

@endsection