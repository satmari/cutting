@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-6 col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">Print multiple nalog</div>
				<!-- <h3 style="color:red;">Error!</h3> -->
				<div class="panel-body">
					<br>
					@if (isset($msg))
					<p style="color:red;">{{ $msg }}</p>
					@endif 

					{!! Form::open(['method'=>'POST', 'url'=>'print_mattress_multiple_mm_post']) !!}

					<div class="input-group"> <span class="input-group-addon">Filter</span>
	                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
	                </div>

					<table class="tab le" style="width:100%">
						<th style="width:100%"></th>
						<tbody class="searchable">
						@foreach ($data as $line)
		  						<tr>
		  							<td style="width:80%">
		  								<div class="checkbox">
									    	<label style="width: 90%;" type="button" class="btn check btn-default"  data-color="primary">
									      		<input type="checkbox" class="btn check" name="items[]" value="{{ $line->id }}">  
									      		<input name="hidden[]" type='hidden' value="{{ $line->id}}"> 

									      		{{ $line->mattress }} - {{ $line->tpa_number}} 
												
									    	</label>
									  	</div>
		  						 	</td>
		  						</tr>
		  				@endforeach
		  				</tbody>
						</table>
						<div class="checkbox">
					    	<label style="width: 30%;" type="button" class="btn check btn-warrning"  data-color="info">
					      		<input type="checkbox" class="btn check" id="checkAll"><b>Select all</b> <i>(filter doesn't work)</i>
					    	</label>
					  	</div>
					<hr>
					{!! Form::submit('Next', ['class' => 'btn btn-success center-block']) !!}

					@include('errors.list')
					{!! Form::close() !!}
					<!-- <hr>
					<div class="">
						<a href="{{url('/')}}" class="btn btn-default center-block">Back</a>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

@endsection