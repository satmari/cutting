@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-danger">
                <div class="panel-heading">Restore from log:</div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_change_log_q_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        
                        <div class="panel-body">
                        <big><b>Da li ste sigurni da zelite da vratite na stanje? </b></big>
                        </div>
                        <hr>
                        <br>

                        @foreach ($data as $req)
                            <p>Paspul key: {{$req->pas_key}}</p>
                            <p>Location in which will restore: {{$req->location_from}}</p>
                            <p>Kotur qty: {{$req->kotur_qty}}</p>
                            <p>User: {{$req->operator}}</p>
                            
                            <p>Created: {{$req->created_at}}</p>
                            
                        @endforeach
                        
                        <hr>
                        <br>
                        {!! Form::submit('Confirm restore', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
