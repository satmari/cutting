@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Edit marker</b></div>
				
					{!! Form::open(['url' => 'marker_edit_confirm']) !!}
						
						{!! Form::hidden('id', $data->id, ['class' => 'form-control']) !!}

					<div class="panel-body">
						<p>Status:<span style="color:red;">*</span></p>
	               		{!! Form::select('status', array('ACTIVE'=>'ACTIVE', 'NOT ACTIVE' => 'NOT ACTIVE', 'USELESS' => 'USELESS'), $data->status, array('class' => 'form-control', 'autofocus' => 'autofocus')) !!} 
					</div>

					<div class="panel-body">
                        <p>Marker length:</p>
                    	{!! Form::number('marker_length', round($data->marker_length,3) , ['class' => 'form-control', 'step'=>'0.001']) !!}
                    </div>

                    <div class="panel-body">
                        <p>Efficiency:</p>
                    	{!! Form::number('efficiency', round($data->efficiency,2) , ['class' => 'form-control', 'step'=>'0.01']) !!}
                    </div>
					
					<div class="panel-body">
                        <p>Cutting perimeter:</p>
                    	{!! Form::number('cutting_perimeter', round($data->cutting_perimeter,2) , ['class' => 'form-control', 'step'=>'0.01']) !!}
                    </div>

                    <div class="panel-body">
                        <p>Perimeter:</p>
                    	{!! Form::number('perimeter', round($data->perimeter,2) , ['class' => 'form-control', 'step'=>'0.01']) !!}
                    </div>

                    <div class="panel-body">
                        <p>Average Consumption:</p>
                    	{!! Form::number('average_consumption', round($data->average_consumption,3) , ['class' => 'form-control', 'step'=>'0.001']) !!}
                    </div>

                    <div class="panel-body">
                        <p>Creation type:</p>
                    	{!! Form::select('creation_type', array(''=>'','Local 8min'=>'Local 8min', 'Local 12min' => 'Local 12min', 'Local manually' => 'Local manually', 'Cloud fast' => 'Cloud fast', 'Cloud std 1h' => 'Cloud std 1h', 'Cloud std 4h' => 'Cloud std 4h', 'Cloud std 12h' => 'Cloud std 12h','FLEX NEST' => 'FLEX NEST'), $data->creation_type , array('class' => 'form-control', 'autofocus' => 'autofocus')) !!} 
                    </div>

					<div class="panel-body">
						{!! Form::submit('Save', ['class' => 'btn btn-success btn-lg center-block']) !!}
					</div>
					@include('errors.list')
					{!! Form::close() !!}
				<br>
			</div>
		</div>
	</div>
</div>
@endsection
