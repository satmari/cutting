@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Pretraga paspul cons tabele:
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

                {!! Form::open(['method'=>'POST', 'url'=>'/search_u_cons_post']) !!}
                    <div class="panel-body">
                    <p>Pretraga po skedi: <span style="color:green;"></span></p>
                        <select name="skeda" class="chosen">
                            <option value="" selected></option>
                            @foreach ($skeda as $line)
                                <option value="{{ $line->skeda }}">
                                    {{ $line->skeda }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="panel-body">
                    <p>Pretraga po paspul tip-u: <span style="color:green;"></span></p>
                        <select name="paspul_type" class="chosen">
                            <option value="" selected></option>
                            @foreach ($paspul_type as $line)
                                <option value="{{ $line->paspul_type }}">
                                    {{ $line->paspul_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="panel-body">
                    <p>Pretraga po modelu: <span style="color:green;"></span></p>
                        <select name="style" class="chosen">
                            <option value="" selected></option>
                            @foreach ($style as $line)
                                <option value="{{ $line->style }}">
                                    {{ $line->style }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="panel-body">
                    <p>* Ukoliko ne izaberete ni jedan fiter, prikazace se cela tabela.</p>
                        
                    </div>
                    {!! Form::submit('Trazi', ['class' => 'btn  btn-danger center-block']) !!}

                    @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
