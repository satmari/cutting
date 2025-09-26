@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-8 col-md-offset-2">
            <br>
            <div class="panel panel-default">
                <div class="panel-heading" >Add new viškovi</b>
                        
                    
                </div>
                <br>

                @if (isset($msgs))
                    <i>&nbsp &nbsp &nbsp <span style="color:green; font-size:22px; font-weight:bold;"><b>{{ $msgs }}</b></span></i>
                @endif
                @if (isset($msge))
                    <i>&nbsp &nbsp &nbsp <span style="color:red; font-size:32px; font-weight:bold;"><b>{{ $msge }}</b></span></i>
                @endif
                
                {!! Form::open(['url' => 'viskovi_add_post']) !!}
                    
                    <div class="panel-body">
                    <p>Style: <span style="color: red">*</span></p>
                        <select name="style" class="chosen narrow-chosen" data-placeholder="Select style" data-allow_single_deselect="true" required>
                            <option value="" disabled selected>Select style</option>
                            @foreach ($styles as $line)
                                <option value="{{ $line->style }}">
                                    {{ $line->style }}
                                </option>
                            @endforeach
                        </select>
                    </p>
                    </div>

                    <div class="panel-body">
                    <p>Color: <span style="color: red">*</span></p>
                        <select name="color" class="chosen narrow-chosen" data-placeholder="Select color" data-allow_single_deselect="true" required>
                            <option value="" disabled selected>Select color</option>
                            @foreach ($colors as $line)
                                <option value="{{ $line->color }}">
                                    {{ $line->color }}
                                </option>
                            @endforeach
                        </select>
                    </p>
                    </div>

                    <div class="panel-body">
                        {!! Form::submit('Save', ['class' => 'btn btn-success btn-lg center-block']) !!}
                    </div>

                    
                @include('errors.list')
                {!! Form::close() !!}
                <hr>

                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/')}}" class="btn btn-default center-block">Return to table</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection