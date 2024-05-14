@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-5 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Spread g bin: {{ $g_bin }}</b></div>
				<br>
			    	
				
				<div class="panel-body">	
				@if ($status == 'TO_SPREAD')
					<a href="{{ url('spread_mattress_complete/'.$id) }}" class="btn btn-danger center-block" >Spread mattress completly</a>
					<br>
					@if ($already_partialy_spreaded > 0)
						<a href="{{ url('spread_mattress_partial/'.$id) }}" class="btn btn-warning center-block" disabled><span style="color:black">MATTRESS ALREADY SPREAD PARTIALLY, <b>{{ (float)$already_partialy_spreaded }} LAYERS</b></span></a>
					@else 
						<a href="{{ url('spread_mattress_partial/'.$id) }}" class="btn btn-warning center-block">Spread mattress partialy</a>
					@endif
					<br>
				@elseif ($status == 'SUSPENDED')

					<a href="{{ url('spread_mattress_complete/'.$id) }}" class="btn btn-danger center-block" disabled>Spread mattress completly</a>
					<br>
					<a href="{{ url('spread_mattress_partial/'.$id) }}" class="btn btn-warning center-block" disabled>Spread mattress partialy</a>
					<br>
				@endif

	            @if (isset($success))
				<div class="alert alert-success" role="alert">
				  {{ $success }}
				</div>
				@endif
				@if (isset($danger))
				<div class="alert alert-danger" role="alert">
				  {{ $danger }}
				</div>
				@endif

	            <hr>
	            <a href="{{ url('spreader') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>

			</div>
		</div>
	</div>
</div>

@endsection