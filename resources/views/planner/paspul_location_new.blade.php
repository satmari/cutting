@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Add new Paspul location</div>
                
                <div class="panel-body">

                

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_location_new_post']) !!}

                        <div class="panel-body">
                        <p>Location: <span style="color:red;">*</span></p>
                            {!! Form::text('location', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Type: <span style="color:red;">*</span></p>
                            {!! Form::select('type', array(''=>'', 'line'=>'line', 'stock'=>'stock'), null, array('class' => 'form-control')) !!} 
                        </div>
                        <div class="panel-body">
                        <p>Plant: <span style="color:red;">*</span></p>
                            {!! Form::select('plant', array(''=>'' ,'Subotica'=>'Subotica', 'Kikinda'=>'Kikinda','Senta'=>'Senta'), null, array('class' => 'form-control')) !!} 
                        </div>
                        <br>
                        <br>
                        {!! Form::submit('Add', ['class' => 'btn  btn-success center-block']) !!}
                        @include('errors.list')

                {!! Form::close() !!}


                <hr>
                    <div class="">
                        <a href="{{url('/paspul_locations')}}" class="btn btn-default">Back</a>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
