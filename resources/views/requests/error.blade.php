@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading"></div>
				<h3 style="color:red;">Error!</h3>

				<p style="color:red;">{{ $msg }}</p>
				<p></p>
				<hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/request')}}" class="btn btn-default center-block">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection