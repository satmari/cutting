@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-success">
                <div class="panel-heading">4. Skenirajte karticu operatera ili unesite rucno:
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

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_loc_to_loc_se_op_post']) !!}
                    
                    {!! Form::hidden('location_from', $location_from) !!}
                    {!! Form::hidden('pas_one', $pas_one) !!}
                    {!! Form::hidden('qty', $qty) !!}
                    
                    <div class="panel-body">
                    <p>Operater (R broj): <span style="color:green;"></span></p>
                        {!! Form::text('op1', '', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
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
