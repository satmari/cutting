@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Cut g bin: {{ $g_bin }}</b></div>
				<br>
			    	
				<div class="panel-body">	
				
				{!! Form::open(['method'=>'POST', 'url'=>'mattress_cut_post']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					{!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
					{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
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
                         	<th>Layers actual</th>
                         	<th>Pcs per layer</th>
                         	<th>Planned Qty</th>
                         	<th>Actual Qty</th>
                         </thead>
                        <tbody class="search able">
                        <br>
                         @foreach ($data_pro as $line)
                            <tr>
                                <td>{{$line->style_size}}</td>
                                <td>{{$line->pro_id}}</td>
                                <td>{{$layers_a}}</td>
                                <td>{{$line->pro_pcs_layer}}</td>
                            	<td>{{$line->pro_pcs_planned}}</td>
                                <td>
                                	<input type="number" style="width:130px" class="bt n c heck" name="pro_pcs_actual[]" value="{{$layers_a * $line->pro_pcs_layer}}">  
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

@endsection