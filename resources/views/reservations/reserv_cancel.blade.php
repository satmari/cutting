@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Cancel reservation of material</div>

                <div class="panel-body">
                    Item: <b>{{$input_item}}</b>, Variant: <b>{{$input_variant}}</b>, Batch: <b>{{$input_batch}}</b>
                </div>
               
                <div class="panel-body">
                    {!! Form::open(['method'=>'POST', 'url'=>'/cancel_all']) !!}

                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        
                        </br>
                        {!! Form::submit('Cancel all reservations', ['class' => 'btn  btn-danger center-block']) !!}

                        @include('errors.list')
                    {!! Form::close() !!}
                </div>

                <div class="panel-body">
                    {!! Form::open(['method'=>'POST', 'url'=>'/cancel_po_imput']) !!}

                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        
                        </br>
                        {!! Form::submit('Cancel reservations by PO', ['class' => 'btn  btn-danger center-block']) !!}

                        @include('errors.list')
                    {!! Form::close() !!}
                </div>

                <div class="panel-body">
                    {!! Form::open(['method'=>'POST', 'url'=>'/cancel_hu_imput']) !!}

                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        
                        </br>
                        {!! Form::submit('Cancel reservations by HU', ['class' => 'btn  btn-danger center-block']) !!}

                        @include('errors.list')
                    {!! Form::close() !!}
                </div>
               <br>
            </div>
            <div class="panel panel-default">
               <div class="panel-body">
                    <div><a href="{{url('/reservation')}}" class="btn btn-efault btn-info center-block">Back to reservations</a></div>    
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
