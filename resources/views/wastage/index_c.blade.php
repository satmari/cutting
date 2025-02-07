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

                {!! Form::open(['method'=>'POST', 'url'=>'/req_wastage_cut']) !!}

                        {!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}


                        @if ($material_data)

                            <div class="panel-body">
                            <p>Material: <span style="color:green;">* (lista je filtrirana)</span></p>
                                <select name="material" class="chosen">
                                    <option value="" selected></option>
                                @foreach ($material_data as $line)
                                    <option value="{{ $line->tpp_material }}">
                                        {{ $line->tpp_material }}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                        @else 
                        
                            <div class="panel-body">
                            <p>Material tpp: <span style="color:red;">* (lista nije filtrirana)</span></p>
                                <select name="material" class="chosen">
                                    <option value="" selected></option>
                                @foreach ($material_data_tpp as $line)
                                    <option value="{{ $line->tpp_material }}">
                                        {{ $line->tpp_material }}
                                    </option>
                                @endforeach
                                </select>
                            </div>

                        @endif

                        <div class="panel-body">
                        <p>Kolicina: <span style="color:red;">*</span></p>
                            {!! Form::input('number', 'qty', 0, ['class' => 'form-control']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Krojni Nalog (SAP bin (G00.. bin)): <span style="color:red;">*</span></p>
                            {!! Form::text('sap_bin', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        <br>
                        <div class="panel-info"><u><b>Default printer is: Zebra Krojacnica</b></u></div>

                        <br>
                        {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
