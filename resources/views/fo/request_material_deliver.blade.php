@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Deliver request to? </div>
				<h3 style="color:red;"></h3>
				@if (isset($msg))
				<p style="color:red;">{{ $msg }}</p>
				@endif 	

				<div class="panel-body">
					<div class="">
						<a href="{{url('/request_material_deliver_confirm/'.$id) }}" class="btn btn-lg btn-danger" 
						style="width:250px">Deliver to Loader</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/request_material_relax_confirm/'.$id) }}" class="btn btn-lg btn-warning"
						style="width:250px"
						@if ($status == 'RELAX')
							disabled
						@endif
						>Deliver to Relaxation</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/request_material_qc_confirm/'.$id) }}" class="btn btn-lg btn-info"
						style="width:250px"
						@if ($status == 'QC')
							disabled
						@endif
						>Deliver to QC</a>
					</div>
				</div>
				<hr>
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