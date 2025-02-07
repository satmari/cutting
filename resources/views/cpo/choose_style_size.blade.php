@extends('app')

@section('content')


<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
        	<br>


            <div class="panel panel-default">
				<div class="panel-heading">Create new check</div>
				<!-- <br> -->
					
						
					{!! Form::open(['method'=>'POST', 'url'=>'/cpo_insert_style_size_bundle']) !!}
						<input name="_token" type="hidden" value="{!! csrf_token() !!}" />

						{!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
						{!! Form::hidden('location', $location, ['class' => 'form-control']) !!}

							<div class="panel-body">
	                       		<p>Choose style and size: <span style="color:red;">*</span></p>
							       <div class="col">
							       		<select class="form-control" id="select_style_size" name="select_style_size" required focus>
							    				<option value="" disabled selected>Please select</option>        
							    				@foreach($data as $req)
							    					<option value="{{$req->style_size}}">{{ $req->style_size }}</option>
							    				@endforeach
							  			</select>
							  		</div>
	                       	</div>

	                        <div class="panel-body">
								<span>Bundle: <span style="color:red;">*</span></span>
								<div class="col">
						       		<select class="form-control" id="select_bundle" name="select_bundle" required focus>
						    				<option value="" disabled selected>Please select</option>   
						    				<option value="1">1</option>
					    					<option value="2">2</option>
					    					<option value="3">3</option>
					    					<option value="4">4</option>
					    					<option value="5">5</option>
					    					<option value="6">6</option>
					    					<option value="7">7</option>
					    					<option value="8">8</option>
					    					<option value="9">9</option>
					    					<option value="10">10</option>
					    					<option value="11">11</option>
					    					<option value="12">12</option>
					    					<option value="13">13</option>
					    					<option value="14">14</option>
					    					<option value="15">15</option>
					    					<option value="16">16</option>
					    					<option value="17">17</option>
					    					<option value="18">18</option>
					    					<option value="19">19</option>
					    					<option value="20">20</option>
					    			</select>
						  		</div>
							</div>

						<br>
						<br>
						{!! Form::submit('Next', ['class' => 'btn  btn-danger center-block']) !!}
						@include('errors.list')

					{!! Form::close() !!}
					<!-- <hr> -->
					<br>

					@if (isset($msge))
	            		<div class="alert alert-danger" role="alert">
	            			{{ $msge }}
						</div>
					@endif

					<a href="javascript:history.go(-1)" class="btn btn-p rimary btn-x s">Back</a>
					<br>
					<br>
			</div>
		</div>

	</div>
</div>

@endsection