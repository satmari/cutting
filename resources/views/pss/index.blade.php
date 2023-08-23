@extends('app')

@section('content')


<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
        	<div class="panel panel-default">
			    <div class="panel-heading">Functions and tables:</div>
			        <div class="panel-body">
			        <br>
			        	<a href="{{url('/paspul_jc_to_loc_su_from')}}" class="btn btn-success center-block">Move paspul from <b><span style="color:black">JUST_CUT </span></b>to Location</a>
						<br>
						<a href="{{url('/paspul_jc_to_rk_su_from')}}" class="btn btn-success center-block">Move paspul from <b><span style="color:black">JUST_CUT </span>to<span style="color:red"> READY FOR KIKINDA</span></b></a>
						<br>
						<a href="{{url('/paspul_jc_to_rs_su_from')}}" class="btn btn-success center-block">Move paspul from <b><span style="color:black">JUST_CUT </span>to<span style="color:navy"> READY FOR SENTA</span></b></a>
						<br>
						<a href="{{url('/paspul_jc_to_rv_su_from')}}" class="btn btn-success center-block">Move paspul from <b><span style="color:black">JUST_CUT </span>to<span style="color:pink"> READY FOR VALY</span></b></a>
						<br>
						<a href="{{url('/paspul_loc_to_loc_su_from')}}" class="btn btn-success center-block">Move paspul from Location to Location</a>
						<br>
						<hr>
						<a href="{{url('/paspul_loc_to_prod_su_from')}}" class="btn btn-primary center-block">Move paspul from Location to Production (line/bb)</a>
						<br>
						<a href="{{url('/paspul_loc_to_del_su_from')}}" class="btn btn-danger center-block">Delete from stock</a>
						<br>

						@if (isset($success))
							<p style="color:green;">{{ $success }}</p>
						@endif
						@if (isset($msg))
							<p style="color:red;">{{ $msg }}</p>
						@endif
						
						<br><br>
						<a href="{{url('/paspul_table_just_cut')}}" class="btn btn-default center-block">Table <b>JUST_CUT</b> <i>({{ $count_just_cut }})</i></a>
						<br>
						<a href="{{url('/paspul_table_received_in_subotica')}}" class="btn btn-default center-block">Table<span style="color:orange"> RECEIVED IN SUBOTICA </span> <i>({{ $count_received_in_subotica }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_su')}}" class="btn btn-default center-block">Table <b>STOCK</b> Subotica <i>({{ $count_sub }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_ready_ki')}}" class="btn btn-default center-block">Table<span style="color:red"> READY FOR KIKINDA </span> <i>({{ $count_rki }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_ready_se')}}" class="btn btn-default center-block">Table<span style="color:navy"> READY FOR SENTA </span> <i>({{ $count_rse }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_ready_va')}}" class="btn btn-default center-block">Table<span style="color:pink"> READY FOR VALY </span> <i>({{ $count_rva }})</i></a>
						
					</div>
			</div>
        </div>
    </div>
</div>

@endsection