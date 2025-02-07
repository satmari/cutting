@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
        	<div class="panel panel-default">
		    <div class="panel-heading">Plan mini marker:</div>
		           	<div class="panel-body">
		           		<br>
		           		<div class="">
							<a href="{{url('/mini_marker_create')}}" class="btn btn-info center-block">Create mini marker</a>
							@if (isset($success))
							<p style="color:green;">{{ $success }}</p>
							@endif 
						</div>
						<br>
						<div class="">
							<a href="{{url('/o_roll_print')}}" class="btn btn-success center-block">Print Leftover Roll (LR label)</a>
						</div>
						<hr>
						<div class="">
							<a href="{{url('/o_roll_table')}}" class="btn btn-default center-block">Table of leftover rolls</a>
						</div>
					</div>
				</div>
        </div>
    </div>
</div>

@endsection