@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">

			<div class="panel panel-default">
				<div class="panel-heading">Consumption - Functions</div>

				<div class="panel-body">
					<a href="{{ url('/cons_table') }}" class="btn btn-info btn-m center-block">Consumption table</a> 
				</div>
				{{-- 
				<div class="panel-body">
					<a href="{{ url('/update_cons_table') }}" class="btn btn-primary btn-m center-block">Refresh consumption table</a> 
				</div>
				--}}
				<div class="panel-body">
					<a href="{{ url('/add_po_cons_table') }}" class="btn btn-success btn-m center-block">Add PO in consumption table</a> 
				</div>

			</div>

		</div>
	</div>
</div>
@endsection
