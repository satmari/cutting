@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reserve material</div>

                <div class="panel-body">
                    Item: <b>{{$input_item}}</b>, Variant: <b>{{$input_variant}}</b>, Batch: <b>{{$input_batch}}</b>
                </div>
               
                <div class="panel-body">
                    {!! Form::open(['method'=>'POST', 'url'=>'/reserv_by_hu_confirm']) !!}

                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        {{-- {!! Form::hidden('checked[]', $input_checked, ['class' => 'form-control']) !!} --}}

                        @foreach ($input_checked as $checked)

                            <input type="hidden" name="result[]" value="{{ $checked }}" class="form-control" >

                        @endforeach
                        

                    
                        <div class="panel-body">
                        <p>Production order: (6 characters) <span style="color:red;">*</span></p>
                            {!! Form::text('po', null, ['id' => 'po','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        </br>
                        {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')
                    {!! Form::close() !!}
                </div>
               
            </div>
        </div>
    </div>
</div>
@endsection
