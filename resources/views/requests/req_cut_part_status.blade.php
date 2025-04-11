@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Change status of cut part request</div>
				
				{{-- One shared comment field --}}
				<div class="panel-body">
					<p>Comment:</p>
<textarea id="comment_cut" class="form-control" rows="3" required autofocus>
@if(isset($comment_cut))
{{ $comment_cut }}
@endif
</textarea>
				</div>			
				<hr>

				{{-- Complete Request Form --}}
				{!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status', 'onsubmit'=>'return attachComment(this)']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					<input type="hidden" name="comment" value="">
					
					<div class="panel-body">
						{!! Form::submit('Complete request', ['class' => 'btn btn-success center-block']) !!}
					</div>

					@include('errors.list')
				{!! Form::close() !!}

				{{-- Partially Complete Request Form --}}
				@if ($status == 'Partially Delivered')
					{!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status_p', 'onsubmit'=>'return attachComment(this)']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						<input type="hidden" name="comment" value="">
						
						<div class="panel-body">
							{!! Form::submit('Partially Delivered request', ['class' => 'btn btn-info center-block', 'disabled'=>'disabled']) !!}
						</div>

						@include('errors.list')
					{!! Form::close() !!}
				@else 
					{!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status_p', 'onsubmit'=>'return attachComment(this)']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						<input type="hidden" name="comment" value="">
						
						<div class="panel-body">
							{!! Form::submit('Partially Delivered request', ['class' => 'btn btn-info center-block']) !!}
						</div>

						@include('errors.list')
					{!! Form::close() !!}
				@endif

				{{-- Cancel Request Form --}}
				@if ($status == 'Partially Delivered')
					{!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status_c', 'onsubmit'=>'return attachComment(this)']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						<input type="hidden" name="comment" value="">

						<div class="panel-body">
							{!! Form::submit('Cancel request', ['class' => 'btn btn-danger center-block', 'disabled'=>'disabled']) !!}
						</div>

						@include('errors.list')
					{!! Form::close() !!}
				@else 
					{!! Form::open(['method'=>'POST', 'url'=>'/req_cut_part_status_c', 'onsubmit'=>'return attachComment(this)']) !!}
						{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
						<input type="hidden" name="comment" value="">

						<div class="panel-body">
							{!! Form::submit('Cancel request', ['class' => 'btn btn-danger center-block']) !!}
						</div>

						@include('errors.list')
					{!! Form::close() !!}
				@endif
				<hr>
				
				<div class="panel-body">
					<div class="">
						<a href="{{ url('/req_cut_part_table') }}" class="btn btn-default center-block">Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- JavaScript to copy comment into each form --}}
<script>
	function attachComment(form) {
		const comment = document.getElementById('comment_cut').value.trim();
		
		form.querySelector('input[name="comment"]').value = comment;
		return true;
	}
</script>

@endsection
