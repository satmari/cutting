@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-2 col-md-offset-5">
        	<br>
            <div class="panel panel-default">
				<div class="panel-heading">Fabric reservation</div>
				<!-- <br> -->
					
					
					<div class="panel-body">
						<p>
							<a href="{{url('/inbound_delivery_table')}}" class="btn btn-success center-block">Available fabric</a>
						</p>
					</div>
					<div class="panel-body">
						<p>
							<a href="{{url('/fabric_reservation_table')}}" class="btn btn-warning center-block">Reservations</a>
						</p>
					</div>
					<div class="panel-body">
						<p>
							<a href="{{url('leftover_table')}}" class="btn btn-info center-block"><b>Leftover Queue</b></a>
						</p>
					</div>


					<!-- <hr> -->
					<!-- <br> -->

					
				<!-- <hr> -->
				
			</div>
		</div>
	</div>
</div>

@endsection