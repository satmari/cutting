@extends('app')

@section('content')
<div class="container container-table">
	<div class="row vertical-center-row">
		<div class="text-center col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Send Paspul request to planner</div>
				
				
				<div class="panel-body">


				{!! Form::open(['method'=>'POST', 'url'=>'/req_paspul_post', 'onsubmit'=>'return attachComment(this)']) !!}
					{!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
					<input type="hidden" name="comment" value="">
					
					<div class="panel-body">
                        <p>Requested quantity / Zahtevana kolicina: <span style="color:red;">*</span></p>
                        
                        {!! Form::input('number', 'req_qty', null, ['class' => 'form-control', 'autofocus' => 'autofocus', 'required' => 'required']) !!}
                        <p>Qty requested from the line: {{ $qty }}</p>
                    </div>

					
					<div class="panel-body">
						{!! Form::submit('Send request to office', ['class' => 'btn btn-info center-block']) !!}
					</div>

					@include('errors.list')
				{!! Form::close() !!}

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
