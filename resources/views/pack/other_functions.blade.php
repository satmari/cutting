@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Other functions: {{ $g_bin }}</b></div>
					<br>
			    <div class="panel-body">	
				
				
					<table style="width:100%" class="table table-striped table-bordered">
						<tr>
							<th>Pro</th>
							<th>Sku</th>
							<th>Pcs on Layer</th>
							<th>Pro pcs planned</th>
							<th>Destination</th>
							<th>Pad print item</th>
							<th>Pad print color</th>
						</tr>
						@foreach ($data as $req) 
						<tr>
							<td>{{ $req->pro }} </td>
                        	<td>{{ $req->sku }} </td>
                        	<td>{{ round($req->pro_pcs_layer,0) }} </td>
                        	<td>{{ round($req->pro_pcs_planned,0) }} </td>
                        	<td>{{ $req->location_all }} </td>
                        	<td>{{ $req->padprint_item }} </td>
	                       	<td>{{ $req->padprint_color }} </td>
	                     </tr>
                    	@endforeach
					</table>					
				

				
	            @if (isset($success))
				<div class="alert alert-success" role="alert">
				  {{ $success }}
				</div>
				@endif
				@if (isset($danger))
				<div class="alert alert-danger" role="alert">
				  {{ $danger }}
				</div>
				@endif

	            <!-- <hr> -->
	            <a href="{{ url('pack') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>
			</div>	
			</div>
		</div>
	</div>
</div>

@endsection