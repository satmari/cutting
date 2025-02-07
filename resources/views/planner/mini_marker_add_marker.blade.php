@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">4. Choose marker</div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>
				@endif

				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'mini_marker_add_marker']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />

				{!! Form::hidden('items', $items, ['class' => 'form-control']) !!}
				{!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
				{!! Form::hidden('pro', $pro, ['class' => 'form-control']) !!}
				{!! Form::hidden('style_size', $style_size, ['class' => 'form-control']) !!}
				{!! Form::hidden('po_sum_qty', $po_sum_qty, ['class' => 'form-control']) !!}
				{!! Form::hidden('before_cut_actual', $before_cut_actual, ['class' => 'form-control']) !!}
				{!! Form::hidden('already_cut_actual', $already_cut_actual, ['class' => 'form-control']) !!}
				
                <p>Marker: <span style="color:red;">* </span></p>
                <select name="marker" class="chosen">
                    <option value="" selected></option>
                @foreach ($markers as $line)
                    <option value="{{ $line->marker_name }}">
                        {{ $line->marker_name }}
                    </option>
                @endforeach
               	</select>
        		<br><br>
        		
        		<br><br>
				{!! Form::submit('Next', ['class' => 'btn btn-success center-block']) !!}
				
				<br><hr>
				<table class='table'>
					<thead>
						<th>LR</th>
						<th>G bin</th>
						<th style="min-width: 150px; color:orange;">Mattress orig</th>
						<th style="min-width: 150px; color:orange;">Marker name</th>
						<th style="color:orange;">Width</th>
						<th style="color:orange;">Length</th>
						<th style="color:blue;">Marker Eff</th>
						<th style="color:blue;">Marker cons</th>
					</thead>
					<tbody>
						
						
							@foreach ($selected_info as $info)
							<tr>
								@foreach ($info as $i)
								<td>
									{{ $i }}
								</td>
								@endforeach
							</tr>
							@endforeach
						
						
					</tbody>
				</table>

				@include('errors.list');
				{!! Form::close() !!}
				</div>

				<!-- <hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-blo k">Back</a>
				</div> -->
				
			</div>
		</div>
	</div>
</div>
@endsection