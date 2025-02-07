@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-danger">
                <div class="panel-heading">1. Izaberite skedu sa lokacije <big><b>{{ $location_from }}</b></big> :
                    <p></br>Paznja: svi paspuli za izabranu skedu ce biti obrisani</p></div>
                
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

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_remove_valy_skeda']) !!}
                    
                    {!! Form::hidden('location_from', $location_from) !!}
                    
                    <div class="panel-body">
                    <p>Izaberi skedu<i></i>: <span style="color:green;"></span></p>
                        <select name="skeda" class="chosen">
                            <option value="" selected></option>
                            @foreach ($data as $line)
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
