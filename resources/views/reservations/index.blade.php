@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Reservation - Functions</div>

				{{--
				<div class="panel-body">
					<a href="{{ url('/hu_list') }}" class="btn btn-primary btn-m ">Take actual Hu List from WMS and add in temp table</a>
				</div>
				--}}

				<div class="panel-body">
					<a href="{{ url('/update_reservation_table') }}" class="btn btn-primary btn-m ">Update reservation table</a> [it takes around 1 min]
				</div>

				<div class="panel-body">
					<a href="{{ url('/reserv_mat') }}" class="btn btn-primary btn-m ">Reserve material</a>
				</div>

			</div>


			<div class="panel panel-default">
				<div class="panel-heading">Reservation - Tables</div>

				<div class="panel-body">
					<a href="{{ url('/reserv_table') }}" class="btn btn-warning btn-m ">Reservation table by item, variant, batch</a>
				</div>

				

			</div>
		</div>
	</div>
</div>
@endsection
