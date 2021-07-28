@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">3. Choose PRO</div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>
				@endif

				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'mini_marker_add_pro']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />

				@if (!empty($recap_table))

				<table style="width:100%" class="table">
				<thead>
					<th>pro</th>
					<th>style_size</th>
					<th>order qty (posummary)</th>
					<th>before cut {{-- actual (planned)--}}</th>
					<th>already cut {{-- actual (planned)--}}</th>
					<th>% order qty</th>
					<th>Choose PRO</th>
				</thead>
				<tbody class="searchable">
				@foreach ($recap_table as $l)
				<tr>
					<td>{{ $l->pro}}</td>
				    <td style="width:20%">{{ $l->style_size}}</td>
				    <td>{{ round($l->po_sum_qty,0) }}</td>
					<td>{{ round($l->before_cut_actual,0)}} {{--<i>({{ round($l->before_cut_planned,0) }})</i>--}}</td>
				    <td>{{ round($l->already_cut_actual,0)}} {{--<i>({{ round($l->already_cut_planned,0) }})</i>--}}</td>
				    
				    @if (round($l->po_sum_qty,0) <= 0)
				    <td></td>
				    @else 
				    <td>{{ round((round($l->before_cut_actual,0) + round($l->already_cut_actual,0)) / round($l->po_sum_qty,0) *100 , 0) }}%</td>
				    @endif
				    <td>
				    	<input type="radio" id="pro" name="pro" value="{{ $l->pro.'#'.round($l->po_sum_qty,0).'#'.round($l->before_cut_actual,0).'#'.round($l->already_cut_actual,0) }}" class="form-control">
				    </td>

				    
				</tr>
				@endforeach

  				</tbody>
				</table>
				@else 
				<div><p style="color:red">Missing skeda!!!</p></div>
				@endif

				{!! Form::hidden('items', $items, ['class' => 'form-control']) !!}
				{!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
			
        		<hr>
				{!! Form::submit('Next', ['class' => 'btn btn-success center-block']) !!}
				

				@include('errors.list')
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