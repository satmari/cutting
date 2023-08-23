@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Dodaj consumption MTR per PCS:
                    </div>
                
                @if (isset($msge))
                    <small><i>&nbsp &nbsp &nbsp <span style="color:red"><b>{{ $msge }}</b></span></i></small>
                    <audio autoplay="true" style="display:none;">
                        <!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
                    </audio>
                @endif
                @if (isset($msgs))
                    <small><i>&nbsp &nbsp &nbsp <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
                    <audio autoplay="true" style="display:none;">
                        <!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
                    </audio>
                @endif

                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/table_u_cons_add_post']) !!}
                    
                    <div class="panel-body">
                    <p>Skeda: <span style="color:red;">*</span></p>
                        {!! Form::text('skeda', '', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                    </div>

                    <div class="panel-body">
                    <p>Paspul type: <span style="color:red;">*</span></p>
                        {!! Form::text('paspul_type', '', ['class' => 'form-control']) !!}
                    </div>

                    <div class="panel-body">
                        <p>MTS per PCS: <span style="color:red;">*</span></p>
                            {!! Form::input('number', 'mtr_per_pcs', '' , ['class' => 'form-control', 'step'=>'0.01']) !!}
                    </div>
                    
                    <br>
                    {!! Form::submit('Snimi', ['class' => 'btn  btn-danger center-block']) !!}

                    @include('errors.list')
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection