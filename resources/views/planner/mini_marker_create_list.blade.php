@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">2. Choose other leftover rolls for new mattress <i>(rolls are filtered by skeda from FIRST roll)</i></div>
				
				@if(isset($warning))

					<div class="alert alert-danger alert-dismissable fade in">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <strong>{{$warning}}</strong>
				    </div>

				@endif
				<div class="panel-body">
				{!! Form::open(['method'=>'POST', 'url'=>'mini_marker_create_2']) !!}
				<meta name="csrf-token" content="{{ csrf_token() }}" />

				{!! Form::hidden('selected_o_roll', $selected_o_roll, ['class' => 'form-control']) !!}
				{!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}

				<table style="width:100%">
					
				@for ($i = 0; $i < count($data); $i++)
				
				<tr>
					<td style="width:80%">
						<div class="checkbox">
				    	<label style="width: 90%;" type="button" class="btn check btn-default"  data-color="primary">
				      		<input type="checkbox" class="btn check" name="items[]" value="{{ $data[$i]->o_roll }}"
				      		@if ($selected_o_roll == $data[$i]->o_roll)
				      			checked disabled
				      		@endif
				      		>  
				      			{{ $data[$i]->material }} - {{ $data[$i]->o_roll }} - {{ $data[$i]->g_bin }} - {{ $data[$i]->mattress_name_orig }} - {{ $data[$i]->skeda }} - {{ $data[$i]->no_of_joinings }}
				    	</label>
				  		</div>
				 	</td>
				</tr>
  				@endfor
				</table>
				<br>
				{!! Form::submit('Next', ['class' => 'btn btn-success center-block']) !!}

				@include('errors.list')
				{!! Form::close() !!}
				</div>

				<hr>
				<div class="panel-body">
					<a href="{{url('/')}}" class="btn btn-default center-blo ck">Back</a>
				</div>
				
				
			</div>
		</div>
	</div>
</div>
@endsection