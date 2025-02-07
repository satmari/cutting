@extends('app')

@section('content')


<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
        	<div class="panel panel-default">
			    <div class="panel-heading">Functions and tables:</div>
			        <div class="panel-body">
			        <br>
						<a href="{{url('/paspul_loc_to_loc_ki_from')}}" class="btn btn-success center-block">Move paspul from Location to Location</a>
						<br>
						<a href="{{url('/paspul_loc_to_prod_ki_from')}}" class="btn btn-primary center-block">Move paspul from Location to Production (line/bb)</a>
						<br>
						<a href="{{url('/paspul_ret_ki_to_su_from')}}" class="btn btn-warning center-block">Return paspul from Kikinda to Subotica</a>
						<br>
						<!-- <a href="{{url('/paspul_loc_to_del_ki_from')}}" class="btn btn-danger center-block">Delete from stock</a> -->
						<br>

						@if (isset($success))
							<p style="color:green;">{{ $success }}</p>
						@endif
						@if (isset($msg))
							<p style="color:red;">{{ $msg }}</p>
						@endif
						
						<br><br>
						<a href="{{url('/paspul_table_ready_for_kik')}}" class="btn btn-default center-block">Table READY FOR KIKINDA <i>({{ $count_ready_ki }})</i></a>
						<br>
						<a href="{{url('/paspul_table_received_in_kik')}}" class="btn btn-default center-block">Table RECEIVED IN KIKINDA <i>({{ $count_received_ki }})</i></a>
						<br>
						<a href="{{url('/paspul_table_received_in_subotica')}}" class="btn btn-default center-block">Table<span style="color:orange"> RECEIVED IN SUBOTICA </span> <i>({{ $count_received_in_subotica }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_ki')}}" class="btn btn-default center-block">Table STOCK Kikinda <i>({{ $count_kik }})</i></a>
					</div>
			</div>
        </div>
    </div>
</div>

@endsection