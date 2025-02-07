@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Change status of cut part request</div>
				
                {!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status']) !!}
						
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}

					<!-- <div class="panel-body">
                        <p>Comment: </p>
                        {{--{!! Form::text('comment', , ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}--}}
                    </div> -->

					<div class="panel-body">
						{!! Form::submit('Complete request', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

				{!! Form::close() !!}

				{!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status_c']) !!}
						
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}

					<!-- <div class="panel-body">
                        <p>Comment: </p>
                        {{--{!! Form::text('comment', , ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}--}}
                    </div> -->

					<div class="panel-body">
						{!! Form::submit('Cancel request', ['class' => 'btn btn-danger center-block']) !!}
					</div>

					@include('errors.list')

				{!! Form::close() !!}
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/req_cut_part_table')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection