extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Combine Square mattress</div>
				
				
				<div class="panel-body">
					<p>Are you sure that Square mattress is combined/linked?</p>
					<br>
				<a href="{{ url('mattress_pso_confirm/'.$id) }}" class="btn btn-danger btn-l cen ter-block">
					Confirm</a>
				</div>
				<br>
				<!-- <div class="panel-body">
					<div class="">
						<a href="{{url('/pack')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>

@endsection