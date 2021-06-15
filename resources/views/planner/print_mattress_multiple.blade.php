@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Print multiple nalog</div>
				<!-- <h3 style="color:red;">Error!</h3> -->
				<div class="panel-body">
					<br>
					@if (isset($msg))
					<p style="color:red;">{{ $msg }}</p>
					@endif 


					<div class="">
						<a href="{{url('/print_mattress_multiple_sm')}}" class="btn btn-info center-block">Standard mattress</a>
					</div>
					<br>
					<div class="">
						<a href="{{url('/print_mattress_multiple_mm')}}" class="btn btn-success center-block">Mini mattress</a>
					</div>


					<hr>
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection