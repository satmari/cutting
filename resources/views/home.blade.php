@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					Welcome, this is new Cutting application. 
					@if (Auth::guest())
					<p></p>
					<p>Please login first.</p>
					@endif

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
