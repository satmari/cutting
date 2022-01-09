@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Other functions: {{ $g_bin }}</b></div>
					<br>
			    <div class="panel-body">	
				
				@if (($status == 'TO_CUT') OR ($status == 'ON_CUT'))

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
						@foreach ($take_comment_operator as $req) 
						<tr>
							<td>{{ $req->pro }} </td>
                        	<td>{{ $req->sku }} </td>
                        	<td>{{ round($req->pro_pcs_layer,0) }} </td>
                        	<td>{{ round($req->pro_pcs_actual,0) }} </td>
                        	<td>{{ $req->location_all }} </td>
                        	<td>{{ $req->padprint_item }} </td>
	                       	<td>{{ $req->padprint_color }} </td>
	                     </tr>
                    	@endforeach
					</table>					
				@endif

				<!-- <hr> -->
					{!! Form::open(['method'=>'POST', 'url'=>'add_operator_comment_cut']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}

				<div class="panel-body">
	                <p>Comment operator:</p>
	            	{!! Form::textarea('comment_operator', $comment_operator , ['class' => 'form-control', 'rows' => 2]) !!}
				</div>

				{!! Form::submit('Save comment', ['class' => 'btn  btn-success center-block']) !!}
	            <br>
	            @include('errors.list')
	            {!! Form::close() !!}

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

	            <hr>
	            <a href="{{ url('cutter') }}" class="btn btn-default center-bl ock">Back</a>
	            <br>
	            <br>
			</div>	
			</div>
		</div>
	</div>
</div>

@endsection