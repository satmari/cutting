@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Reservation - Functions</div>

				{{--
				<div class="panel-body">
					<a href="{{ url('/hu_list') }}" class="btn btn-primary btn-m center-block">Take actual Hu List from WMS and add in temp table</a>
				</div>
				--}}

				<div class="panel-body">
					<a href="{{ url('/update_reservation_table') }}" class="btn btn-info btn-m center-block">Update reservation table</a> <i>[it takes around 1 min]</i>
				</div>

				<div class="panel-body">
					<a href="{{ url('/reserv_mat') }}" class="btn btn-primary btn-m center-block">Reserve by material</a>
				</div>
				<div class="panel-body">
					<a href="{{ url('/unreserv_mat') }}" class="btn btn-primary btn-m center-block">Unreserve by material</a>
				</div>
				<div class="panel-body">
					<a href="{{ url('/unreserv_po') }}" class="btn btn-primary btn-m center-block">Unreserve by PO</a>
				</div>
				{{-- 
				<div class="panel-body">
					<a href="{{ url('/cancel_reservation_for_closed_po') }}" class="btn btn-primary btn-m center-block">Cancel reservation for closed PO</a>
				</div>
				--}}

			</div>


			<div class="panel panel-default">
				<div class="panel-heading">Reservation - Tables</div>

				<div class="panel-body">
					<a href="{{ url('/reserv_table') }}" class="btn btn-warning btn-m center-block">Reservation table by item, variant, batch (full table)</a> <i>[it takes around 1 min]</i>
				</div>
				<div class="panel-body">
					<a href="{{ url('/reserv_table_filter') }}" class="btn btn-warning btn-m center-block">Reservation table by item, variant, batch (with filter)</a>
				</div>

			</div>

			<div class="panel panel-default">
				<div class="panel-heading">Reservation by Po table</div>
				<div class="panel-body">
					<a href="{{ url('/reserv_by_po') }}" class="btn btn-success btn-m center-block">Reservation by po</a>
				</div>
				<div class="panel-body">
					<a href="{{ url('/po') }}" class="btn btn-success btn-m center-block">Komesa table</a>
				</div>
			</div>			
		</div>
	</div>
</div>
@endsection
