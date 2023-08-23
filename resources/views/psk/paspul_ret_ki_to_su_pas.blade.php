@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-warning">
                <div class="panel-heading">2. Izaberite paspul sa lokacije <big><b>{{ $location_from }}</b></big>:</div>
                
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

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_ret_ki_to_su_pas_post']) !!}
                    
                    {!! Form::hidden('location_from', $location_from) !!}
                    
                    <div class="panel-body">
                    <p>Skeniraj pas key: <span style="color:green;"></span></p>
                        {!! Form::text('pas_one1', '', ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                    </div>

                    <div class="panel-body">
                    <p>Izaberi pas key<i></i>: <span style="color:green;"></span></p>
                        <select name="pas_one2" class="chosen">
                            <option value="" selected></option>
                            @foreach ($pas_keys as $line)
                                <option value="{{ $line->pas_key }}">
                                    {{ $line->skeda }} - {{ $line->paspul_type }} - {{ $line->dye_lot }} - {{ round($line->kotur_length,1) }}
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
