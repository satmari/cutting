@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center">
			<div class="panel panel-default">
				<div class="panel-heading">Create new Request for cut part of: PO <b>{{$po}} </b> Style: <b>{{ $style }}</b> <span class="pull-right">Line: <b>{{$line}}</b></span></div>
				
				{!! Form::open(['method'=>'POST', 'url'=>'/requeststore_cut_part']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />

				{!! Form::hidden('line', $line, ['class' => 'form-control']) !!}
				{!! Form::hidden('bbname', $bbname, ['class' => 'form-control']) !!}
				{!! Form::hidden('po', $po, ['class' => 'form-control']) !!}
				{!! Form::hidden('style', $style, ['class' => 'form-control']) !!}
				{!! Form::hidden('color', $color, ['class' => 'form-control']) !!}
				{!! Form::hidden('size', $size, ['class' => 'form-control']) !!}
				{!! Form::hidden('bagno', $bagno, ['class' => 'form-control']) !!}
				{!! Form::hidden('image', $image, ['class' => 'form-control']) !!}

				<div class="panel-body">

				<table style="width:100%" class="tab le">
					<th style="width:10%;text-align:center;">Standard part</th>
					<th style="width:10%;text-align:center;">Insert Qty</th>
					<th style="width:35%;text-align:center;">Insert Comment</th>
					<th style="width:40%" rowspan="11">
							<!-- <div style='width: 400px; height: 400px; float: left; margin: 3px; padding: 3px;'> -->
							<!-- <img style='width 400px; height: 400px; border:1px solid; object-fit:cover; display: block; margin-left: auto; margin-right: auto;' src="http://172.27.161.173/settings/public/storage/StyleImages/{{ $image }}" > -->
							<!-- </div>	 -->
					</th>
					<tr>
						<td style="width:10%; padding:10px">
				  		<input name="paspul" type='hidden' value="paspul"> 
			      		<big><b>Paspul</b></big>
					 	</td>

					 	<td style="width:10%">
					 		<input type="number" class="" name="paspul_qty" size="" min="1" max="100" value="">  
					 	</td>
					 	<td style="width:35%">
					 		<input type="text" class="" name="paspul_comment" size="40" min="1" max="100" value=""> 
					 	</td>
					</tr>
					<tr>
						<td style="width:10%; padding:10px">
				  		<input name="other" type='hidden' value="other"> 
			      		<big><b>Other</b></big>
					 	</td>

					 	<td style="width:10%">
					 		<input type="number" class="" name="other_qty" size="" min="1" max="100" value="">  
					 	</td>
					 	<td style="width:35%">
					 		<input type="text" class="" name="other_comment" size="40" min="1" max="100" value=""> 
					 	</td>
					</tr>
				</table>

				<table style="width:100%" class="tab le">

						<th style="width:10%;text-align:center;">Part from picture</th>
						<th style="width:10%;text-align:center;">Insert Qty</th>
						<th style="width:35%;text-align:center;">Insert Comment</th>
						<th style="width:40%" rowspan="11">
							<!-- <div style='width: 400px; height: 400px; float: left; margin: 3px; padding: 3px;'> -->
							<img style='width 400px; height: 400px; border:1px solid; object-fit:cover; display: block; margin-left: auto; margin-right: auto;' src="http://172.27.161.173/settings/public/storage/StyleImages/{{ $image }}" >
							<!-- </div>	 -->
						</th>
						

				@for ($i = 1; $i < 11; $i++)
				
  						<tr>
  							<td style="width:10%; padding:10px">
  								<!-- <div class="checkbox"> -->
							    	<!-- <label style="width: 400px;" type="button" class="btn check btn-default"  data-color="primary"> -->
							      		<!-- <input type="checkbox" class="btn check" name="items[]" value="{{ $i }}">   -->
							      		<input name="hidden[]" type='hidden' value="{{ $i }}"> 
							      		<big><b>{{ $i }}</b></big>


										<!-- <input type="number" class="" name="qty[]"  min="1" max="100" >   -->
										<!-- <input type="text" class="" name="comment[]" min="1" max="100" value="">  -->
										
										
							    	<!-- </label> -->


							  	<!-- </div> -->
  						 	</td>

  						 	<td style="width:10%">
  						 		<input type="number" class="" name="qty[]" size="" min="1" max="100" value="">  
  						 	</td>
  						 	<td style="width:35%">
  						 		<input type="text" class="" name="comment[]" size="40" min="1" max="100" value=""> 
  						 	</td>
  							

  						</tr>
  				@endfor
				</table>
			    
			  	
			  	<!-- <div class="checkbox">
			    	<label style="width: 30%;" type="button" class="btn check btn-warrning"  data-color="info">
			      		<input type="checkbox" class="btn check" id="checkAll"><b>Izaberi sve</b>
			    	</label>
			  	</div> -->
					

			   <!--  <div class="panel-body">
			    	<p>Comment:</p>
				    {!! Form::text('comment', null, ['class' => 'form-control']) !!}
				</div>	 -->			    

				<div class="panel-body">
					{!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
				</div>

				@include('errors.list')
				{!! Form::close() !!}

				{{--
				<hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
				</div>
				--}}
				
			</div>
		</div>
	</div>
</div>
@endsection