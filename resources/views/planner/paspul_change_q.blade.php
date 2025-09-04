@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Paspul stock modification:</div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_change_q_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}


                        <hr>
                        <span style="color:red">If you want to completly remove this line click on </span>
                        
                        <a href="{{url('/paspul_delete_line/'.$id)}}" class="btn btn-danger btn-xs center-blo ck">Delete</a>
                        <hr>
                        <div class="panel-body">
                        <p>Koturi <b><i>(insert number of cut out koturi)</i></b>: </p>
                            {!! Form::number('kotur_qty', $kotur_qty, ['class' => 'form-control', 'step'=>'1']) !!}
                        </div>

                        
                        <br>
                        {!! Form::submit('Confirm koturi', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
