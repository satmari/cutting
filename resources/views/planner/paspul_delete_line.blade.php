@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Paspul stock delete:</div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_delete_line_confirm']) !!}

                        {!! Form::hidden('id', $data[0]->id, ['class' => 'form-control']) !!}
                        <br>
                        <p><span style="color:red">Are you sure that you want to delete this paspul?</span></p>
                        <hr>
                        <br>
                        <p>Skeda: {{$data[0]->skeda}}</p>
                        <p>Dye lot: {{$data[0]->dye_lot}}</p>
                        <p>Paspul type: {{$data[0]->paspul_type}}</p>
                        <p>Lenght: {{$data[0]->kotur_length}}</p>
                        <p>Kotur qty: {{$data[0]->kotur_qty}}</p>
                        <p>Location: {{$data[0]->location}}</p>
                        
                        <hr>
                        <br>
                        {!! Form::submit('Confirm delete', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        
                        @include('errors.list')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
