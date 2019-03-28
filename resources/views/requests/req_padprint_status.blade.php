@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-success">
				<div class="panel-heading">Change status of pad print request</div>
				
                @if($status=='Pending')

				

				{!! Form::open(['method'=>'POST', 'url'=>'/req_padprint_status']) !!}
						
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{{-- 
					<div class="panel-body">
                        <p>Comment: </p>
                        {!! Form::text('comment', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                    </div>
                    --}}

					<div class="panel-body">
						{!! Form::submit('Collect', ['class' => 'btn btn-success center-block']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Complete', ['class' => 'btn btn-success center-block disabled']) !!}
					</div>

					@include('errors.list')

				{!! Form::close() !!}


				@endif


				@if($status=='Collected')

				
				{!! Form::open(['method'=>'POST', 'url'=>'/req_padprint_status1']) !!}
						
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					<div class="panel-body">
                        <p>Comment: </p>
                        {!! Form::text('comment', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                    </div>

                    <div class="panel-body">
						{!! Form::submit('Collect', ['class' => 'btn btn-success center-block disabled']) !!}
					</div>
					<div class="panel-body">
						{!! Form::submit('Complete', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

				{!! Form::close() !!}

				@endif
				<hr>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/req_padprint_table')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection