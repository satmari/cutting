@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">5. Insert layer limit</div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>
				@endif

				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'mini_marker_add_limit']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />
				
				{!! Form::hidden('items', $items, ['class' => 'form-control']) !!}
				{!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
				{!! Form::hidden('pro', $pro, ['class' => 'form-control']) !!}
				{!! Form::hidden('style_size', $style_size, ['class' => 'form-control']) !!}
				{!! Form::hidden('po_sum_qty', $po_sum_qty, ['class' => 'form-control']) !!}
				{!! Form::hidden('before_cut_actual', $before_cut_actual, ['class' => 'form-control']) !!}
				{!! Form::hidden('already_cut_actual', $already_cut_actual, ['class' => 'form-control']) !!}
				{!! Form::hidden('marker', $marker, ['class' => 'form-control']) !!}
				
				<div class="panel-body" style="width:200px ; margin-left: 36%;">
                	<p>Order qty: 		<b>{{ $po_sum_qty }} </b></p>
                	<p>Before cut: 		<b>{{ $before_cut_actual }}</b></p>
                	<p>After qty: 		<b>{{ $already_cut_actual}}</b></p>
                	<p>Required qty: 	<b>{{ $po_sum_qty - ($before_cut_actual + $already_cut_actual)}}</b></p>
                	<p>Pcs per size:	<b>{{ $pc_per_layer}} </b></p>
                	<p>Calculated layer limit: <b>{{$layer_limit}} </b></p>
                    
                </div>
				
				<div class="panel-body" style="width:200px ; margin-left: 36%;">
                	<p>Layer limit (suggested): <span style="color:red;">*</span></p>
                    {!! Form::number('layer_limit', $layer_limit, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                </div>
                <!-- <br> -->
                
        		<br><br>
				{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}

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