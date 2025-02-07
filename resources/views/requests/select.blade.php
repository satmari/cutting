@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Choose Request?</div>
				<div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>
				
				<div class="panel-body">
					<div class="">
						<a href="{{url('/req_extrabb')}}" class="btn btn-info center-block">Extra Bluebox</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/req_reprintbb')}}" class="btn btn-primary center-block">Reprint Bluebox</a>
					</div>
				</div>
				
				<div class="panel-body">
					<div class="">
						<a href="{{url('/req_cartonbox')}}" class="btn btn-warning center-block">New Catonbox</a>
					</div>
				</div>

				<div class="panel-body">
					<div class="">
						<a href="{{url('/req_padprint')}}" class="btn btn-success center-block">Pad print</a>
					</div>
				</div>

				
				

			</div>
		</div>
		{{-- 
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				
				<table class="table" style="font-size: large">
				
				</table>
				
			</div>
		</div>
		--}}

	</div>
</div>
@endsection