@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Please declare:

				</div>
				<!-- <br> -->
					{!! Form::model($data , ['method' => 'POST', 'url' => 'declare_leftover_post' ]) !!}
					{!! Form::hidden('id', $data[0]->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body" >
						<p>Leftover quantity: <span style="color:red;">*</span></p>
							{!! Form::number('leftover_qty', 0, ['class' => 'form-control','step'=>'0.1']) !!}
					</div>

					<div class="panel-body">
						<p>Comment: </p>
		                	{!! Form::textarea('comment', '', ['class' => 'form-control', 'cols' => '30', 'rows' => '3']) !!}
		                	 
                	</div>
					
					<div class="panel-body">
						{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}
					<!-- <br> -->
				
					
			</div>
		</div>
	</div>
</div>

@endsection