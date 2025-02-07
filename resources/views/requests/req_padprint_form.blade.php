@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-success">
                <div class="panel-heading">Request for pad print</div>
                <div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>

                <div class="panel-body">

                    {{-- 
                    <div class="alert alert-warning">
                      <strong>Warning!</strong> Application will suggest released PO from Navision, but you don't have any limitation on po name.
                    </div>
                    --}}
                
                {!! Form::open(['method'=>'POST', 'url'=>'/req_padprintconfirm']) !!}

                        <div class="panel-body">
                        <p>Qty without pad print / Kolicina bez pad printa: <span style="color:red;">*</span></p>
                            {!! Form::input('number', 'qty', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        {{-- 
                        <div class="panel-body">
                        <p>Style (if you know) / Model (ako znate): </p>
                            {!! Form::text('style', null, ['class' => 'form-control']) !!}
                        </div>
                        --}}

                        <div class="panel-body">
                        <p>Style: <span style="color:red;">*</span></p>
                            <select name="style" class="chosen">
                                <option value="" selected></option>
                            @foreach ($style_data as $line)
                                <option value="{{ $line->style }}">
                                    {{ $line->style }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        
                        {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
