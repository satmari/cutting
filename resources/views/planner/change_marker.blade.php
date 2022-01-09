@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default col-md-4 col-md-offset-4">
                <div class="panel-heading">Change marker request for g_bin: <big><b>{{ $g_bin }}</b></big></div>
              
                <div class="panel-body">
                <br>
                    {!! Form::open(['method'=>'POST', 'url'=>'/change_marker_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker', $existing_marker, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker_id', $existing_marker_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_mattress_marker_id', $existing_mattress_marker_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker_length', $existing_marker_length, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker_width', $existing_marker_width, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_min_length', $existing_min_length, ['class' => 'form-control']) !!}

                        <br>
                        Existing marker: <br>
                        <b>{{ $existing_marker }} </b></b><br>
                        <p>Length: {{ round((float)$existing_marker_length,2) }}, Width: <b>{{ round((float)$existing_marker_width,2) }}</b> cm</p>
                        <p>Theoretical usable width: <b>{{ round($width_theor_usable, 2) }}</b> cm</p>
                        <br>
                        <p>New marker: </p>
                        <select name="selected_marker" class="select form-control select-form chosen">
                            <option value="{{$existing_marker}}" selected></option>
                            
                            @for ($i = 1; $i <= count($markers); $i++)
                            <option value="{{ $markers[$i] }}">
                                {{ $markers[$i] }}
                            </option>
                            @endfor
                        </select>

                        <!--  <div class="alert alert-danger" role="alert">
                            Please choose marker that in comparison with existing one have only difference in width or lenght!
                        </div> -->
                        <br>
                        <br>

                        <div>
                            Requested width by operator:<br>
                            <b>{{ $requested_width }}</b>
                        </div>
                        
                        <hr>
                        {!! Form::submit('Change marker', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        @include('errors.list')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
