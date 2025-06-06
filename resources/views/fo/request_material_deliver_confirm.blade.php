@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Confirm deliver to Loader?</div>
				<h3 style="color:red;"></h3>
				@if (isset($msg))
				<p style="color:red;">{{ $msg }}</p>
				@endif 	

				<div class="panel-body">
					<div class="">
						<a href="{{url('/request_material_deliver_confirm_post/'.$id) }}"
						 class="btn btn-lg btn-danger">Deliver to Loader</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<!-- <a href="{{url('/')}}" class="btn btn-default center-block">Back</a> -->
						<button class="btn btn-default center-block" onclick="history.back()">Go Back</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection