@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading">Declare and print TPP wastage (Standard Marker)</div>
                <a href="{{url('/wastage_cut')}}" class="btn btn-info btn-xs ">Standard Marker</a>
                <a href="{{url('/wastage_cut_mm')}}" class="btn btn-warning btn-xs ">Mini Marker</a>
                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/req_wastage_c']) !!}

                      
                        <div class="panel-body">
                        <p>Skeda: <span style="color:red;">*</span></p>
                            <select name="skeda" class="chosen">
                                <option value="" selected></option>
                            @foreach ($skeda_data as $line)
                                <option value="{{ $line->skeda }}">
                                    {{ $line->skeda }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        
                        <br>
                        {!! Form::submit('Next', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
