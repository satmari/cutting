@extends('app')

@section('content')


<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
        	<div class="panel panel-default">
			    <div class="panel-heading">Functions and tables:</div>
			        <div class="panel-body">
			        <br>
						<a href="{{url('/paspul_transfer_su_ki')}}" class="btn btn-success center-block">Transfer paspul from Subotica to KIKINDA</a>
						<br>
						<a href="{{url('/paspul_transfer_su_se')}}" class="btn btn-primary center-block">Transfer paspul from Subotica to SENTA</a>
						<br>
						<a href="{{url('/paspul_transfer_su_va')}}" class="btn btn-info center-block">Transfer paspul from Subotica to VALY</a>
						<br>

						@if (isset($success))
							<p style="color:green;">{{ $success }}</p>
						@endif
						@if (isset($msg))
							<p style="color:red;">{{ $msg }}</p>
						@endif
						
						<br><br>
						<a href="{{url('/paspul_table_stock_ready_ki')}}" class="btn btn-default center-block">Table READY FOR KIKINDA <i>({{ $count_ready_for_ki }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_ready_se')}}" class="btn btn-default center-block">Table READY FOR SENTA <i>({{ $count_ready_for_se }})</i></a>
						<br>
						<a href="{{url('/paspul_table_stock_ready_va')}}" class="btn btn-default center-block">Table READY FOR VALY <i>({{ $count_ready_for_va }})</i></a>
					</div>
			</div>
        </div>
    </div>
</div>

@endsection