@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
        	<div class="text-center">
		        <div class="panel panel-default">
				<div class="panel-heading"><b>Cut g bin: <span style="font-size:20px">{{ $g_bin }}</span></b></div>
				<br>
			    	
				<div class="panel-body">	
				
				{!! Form::open(['method'=>'POST', 'url'=>'mattress_cut_post']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
					{!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress_id', $mattress_id, ['class' => 'form-control']) !!}
					{!! Form::hidden('status', $status, ['class' => 'form-control']) !!}
					{!! Form::hidden('layers_a', $layers_a, ['class' => 'form-control']) !!}
					
					
					@foreach($data_pro as $req0)
						<input type="hidden" name="style_size[]" value="{{$req0->style_size}}">
					@endforeach

					@foreach($data_pro as $req1)
						<input type="hidden" name="pro_pcs_layer[]" value="{{$req1->pro_pcs_layer}}">
					@endforeach

					@foreach($data_pro as $req2)
						<input type="hidden" name="pro_id[]" value="{{$req2->pro_id}}">
					@endforeach

					@foreach($data_pro as $req3)
						<input type="hidden" name="line_id[]" value="{{$req3->id}}">
					@endforeach

					<table class="table table-s triped table-bordered" id="so rt">
                         <thead>
                         	<th>Style size</th>
                         	<th>Pro id</th>
                         	<th>Marker</th>
                         	<th>Marker Width</th>
                         	<th>Marker Length</th>
                         	<th>Layers planned</th>
                         	<th>Layers actual</th>
                         	<th>Partial Layers</th>
                         	<th>Pcs per layer</th>
                         	<th>Planned Qty</th>
                         	<th>Actual Qty</th>
                         	<th>Demaged Qty</th>
                         </thead>
                        <tbody class="search able">
                        <br>
                         @foreach ($data_pro as $line)
                            <tr>
                                <td>{{$line->style_size}}</td>
                                <td>{{$line->pro_id}}</td>
                                <td>{{$marker_name}}</td>
                                <td>{{$marker_width}}</td>
                                <td>{{$marker_length}}</td>
                                <td>{{$layers}}</td>
                                <td>{{$layers_a}}</td>
                                <td>{{$layers_partial}}</td>
                                <td>{{round($line->pro_pcs_layer, 0)}}</td>
                            	<!-- <td>{{$line->pro_pcs_planned}}</td> -->
                            	<td>{{ round($line->pro_pcs_actual,0)}}</td>
                            	<td>{{ ($layers_a * $line->pro_pcs_layer) + $layers_partial }}</td>
                                <td>
                                	<input type="number" style="width:50px" class="bt n c heck" name="damaged_pcs[]" value="0">  
                                	<input type="hidden" style="width:50px" class="bt n c heck" name="pro_pcs_actual[]" value="{{($layers_a * $line->pro_pcs_layer) + $layers_partial}}">  
                            	</td>
                            </tr>
                        @endforeach
                        </tbody>     
                    </table>

				<div class="panel-body">
	                <p>Comment operator:</p>
	            	{!! Form::textarea('comment_operator', $comment_operator , ['class' => 'form-control', 'rows' => 2]) !!}
				</div>
				<br>
				{!! Form::submit('Confirm cut', ['class' => 'btn  btn-danger center-block']) !!}

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

	            <!-- <hr>
	            <a href="{{ url('cutter') }}" class="btn btn-default center-bl ock">Back</a>
	            <br> -->
	            <br>
	            </div>
			</div>
		</div>
	</div>
</div>

@endsection