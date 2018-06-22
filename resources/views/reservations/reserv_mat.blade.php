@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Reserve material - insert item , vatriant, batch </div>

                <div class="panel-body">
                
                {!! Form::open(['method'=>'POST', 'url'=>'/reserv_input']) !!}

                        <div class="panel-body">
                        <p>Item: <span style="color:red;">*</span></p>
                            {!! Form::text('item', null, ['id' => 'item','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        <div class="panel-body">
                        <p>Variant: <span style="color:red;">*</span></p>
                            {!! Form::text('variant', null, ['id' => 'variant','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Batch (Bagno): <span style="color:red;">*</span></p>
                            {!! Form::text('batch', null, ['id' => 'batch','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        
                        {!! Form::submit('Next', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
