@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-success">
                <div class="panel-heading">5. Izaberite lokaciju <big><b>u</b></big> koju se stavlja paspul <i>(jedan od dva nacina)</i>:
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

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_loc_to_loc_ki_to_post']) !!}
                    
                    {!! Form::hidden('location_from', $location_from) !!}
                    {!! Form::hidden('pas_one', $pas_one) !!}
                    {!! Form::hidden('qty', $qty) !!}
                    {!! Form::hidden('op', $op) !!}
                    
                    <div class="panel-body">
                    <p>Skeniraj lokaciju: <span style="color:green;"></span></p>
                        {!! Form::text('location1', '', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                    </div>
                        
                    <div class="panel-body">
                    <p>Izaberi lokaciju iz liste <i>(prikazane su samo dozvoljene lokacije)</i>: <span style="color:green;"></span></p>
                        <select name="location2" class="chosen">
                            <option value="" selected></option>
                            @foreach ($to as $line)
                                <option value="{{ $line->location }}">
                                    {{ $line->location }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <br>
                    {!! Form::submit('Finish', ['class' => 'btn  btn-danger center-block']) !!}

                    @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
