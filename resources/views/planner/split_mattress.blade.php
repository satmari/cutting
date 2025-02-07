@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default col-md-4 col-md-offset-4">
                <div class="panel-heading">Split mattress request for g_bin: <big><b>{{ $g_bin_orig }}</b></big></div>
              
                <div class="panel-body">
                
             
                    {!! Form::open(['method'=>'POST', 'url'=>'/split_mattress_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress_id_orig', $mattress_id_orig, ['class' => 'form-control']) !!}
                       
                        
                        <br>
                        Existing mattress: <br>
                        <b>{{ $mattress_orig }} </b><br>
                        Existing marker: <br>
                        <b>{{ $marker_name_orig }} </b>
                        <p>
                            Width: <b>{{ round($marker_width,0) }}</b> cm ;
                            Length: <b>{{ round($marker_length,2) }}</b> m
                        </p>
                        <br>
                        <p>Theoretical usable width: <b>{{ round($width_theor_usable, 2) }}</b> cm</p>
                        <p>
                            Requested width: <b>{{ round($requested_width, 2) }}</b> m
                            Requested length: <b>{{ round($requested_length,0) }}</b> cm <br>
                        </p>
                        <br>

                        <div class="panel-body">
                        <p>Layers:</p>
                            {!! Form::text('layers', 0, ['class' => 'form-control']) !!}
                        </div>
                        <br>

                        <p>New marker: </p>
                        <select name="selected_marker" class="select form-control select-form chosen">
                            <option value="" selected></option>
                            
                            @foreach ($markers as $m)
                            <option value="{{ $m->id }}">
                                {{ $m->marker_name }} => {{ $m->status }}
                            </option>
                            @endforeach
                        </select>
                        
                        <hr>
                        {!! Form::submit('Create mattress', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        @include('errors.list')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
