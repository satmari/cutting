@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Please add informations:

				</div>
				<!-- <br> -->
					{!! Form::model($data , ['method' => 'POST', 'url' => 'reserve_material_post' ]) !!}
					{!! Form::hidden('id', $data[0]->id, ['class' => 'form-control']) !!}
					
					<div class="panel-body">
						<span><b>Skeda</b><br>
						 <!-- <i>Llist of skedas without Done status in PoSummary</i> </span> -->
						<!-- <br> -->
						 <!-- <select name="skeda" class="select form-control select-form chosen">
			                    <option value="" selected></option>
		                		@foreach ($skedas as $line)
		                    	<option value="{{ $line->skeda }}">
		                        	{{ $line->skeda }}
		                    	</option>
		                		@endforeach
		               	</select> -->

		               	 <select name="skeda" id='select2' class="select form-con rol sele ct-form cho sen" style="min-width:200px">
                            <option value="" selected></option>
                            
                            @foreach ($skedas as $m)
                            <option value="{{ substr($m->skeda,0,12) }}">
                                {{ substr($m->skeda,0,12) }} 
                            </option>
                            @endforeach
                        </select>

                        <select name="skeda_mat" id='select3' class="select form-con rol sele ct-form cho sen" style="min-width:50px">
                            <option value="" selected></option>
                            <option value="-A">-A</option>
                            <option value="-B">-B</option>
                            <option value="-C">-C</option>
                            <option value="-D">-D</option>
                            <option value="-E">-E</option>
                        </select>

					</div>
					
					@if ($data[0]->type != 'Leftover')
						<div class="panel-body" >
							<p>Quantity: <span style="color:red;">*</span></p>
								{!! Form::number('reserved_qty', round($data[0]->qty_received_m - $data[0]->qty_reserved_m,2), ['class' => 'form-control','step'=>'0.1']) !!}
						</div>
					@else
						<div class="panel-body" >
							<p>Quantity: <span style="color:red;">*</span></p>
								{!! Form::number('reserved_qty', round($data[0]->qty_received_m,2), ['class' => 'form-control','step'=>'0.1']) !!}
						</div>
					@endif

					 <div class="panel-body">
						<p>Comment: </p>
		                	{!! Form::textarea('comment', '', ['class' => 'form-control', 'cols' => '30', 'rows' => '3']) !!}
		                	 
                	</div>
					

					<div class="panel-body">
						{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')

					{!! Form::close() !!}
					<!-- <br> -->
					
					
				<!-- <hr>
				<div class="panel-body">
					<div class="">
						<a href="{{url('/inbound_delivery_table')}}" class="btn btn-default">Back</a>
					</div>
				</div> -->
					
			</div>
		</div>
	</div>
</div>

@endsection