@extends('app')

@section('content')

<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
        	<div class="panel panel-default">
			    <div class="panel-heading">Functions and tables:</div>
			        <div class="panel-body">
			        <br>
						<a href="{{url('/paspul_loc_to_loc_se_from')}}" class="btn btn-success center-block">Move paspul from Location to Location</a>
						<br>
						<a href="{{url('/paspul_loc_to_prod_se_from')}}" class="btn btn-primary center-block">Move paspul from Location to Production (line/bb)</a>
						<br>
						<a href="{{url('/paspul_ret_se_to_su_from')}}" class="btn btn-warning center-block">Return paspul from Senta to Subotica</a>
						<br>
						<!-- <a href="{{url('/paspul_loc_to_del_se_from')}}" class="btn btn-danger center-block">Delete from stock</a> -->
						<br>

						@if (isset($success))
							<p style="color:green;">{{ $success }}</p>
						@endif
						@if (isset($msg))
							<p style="color:red;">{{ $msg }}</p>
						@endif
						
						<br><br>
						<a href="{{url('/paspul_table_ready_for_sen')}}" class="btn btn-default center-block">Table READY FOR  SENTA <i>({{ $count_ready_se }})</i></a>
						<br>
						<a href="{{url('/paspul_table_received_in_sen')}}" class="btn btn-default center-block">Table RECEIVED IN SENTA <i>({{ $count_received_se }})</i></a>
						<br>
						<a href="{{url('/paspul_table_received_in_subotica')}}" class="btn btn-default center-block">Table<span style="color:orange"> RECEIVED IN SUBOTICA </span> <i>({{ $count_received_in_subotica }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_se')}}" class="btn btn-default center-block">Table STOCK Senta <i>({{ $count_sen }})</i></a>
					</div>
			</div>
        </div>
    </div>
</div>

@endsection