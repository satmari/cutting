@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default col-md-4 col-md-offset-4">
                <div class="panel-heading">Replace marker request for g_bin: <big><b>{{ $g_bin }}</b></big> 
                <!-- global variable: {{ config('app.global_variable') }} -->
                </div>
              
                <div class="panel-body">
                <br>
                    {!! Form::open(['method'=>'POST', 'url'=>'/change_marker_all_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker', $existing_marker, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker_id', $existing_marker_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress_details_id', $mattress_details_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_mattress_marker_id', $existing_mattress_marker_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker_length', $existing_marker_length, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_marker_width', $existing_marker_width, ['class' => 'form-control']) !!}
                        {!! Form::hidden('existing_min_length', $existing_min_length, ['class' => 'form-control']) !!}
                        {!! Form::hidden('style', $style, ['class' => 'form-control']) !!}

                        <br>
                        Existing marker: <b>{{ $existing_marker }} </b><br><br>
                        <p>
                            Width: <b>{{ round((float)$existing_marker_width,2) }} cm</b><br>
                            Length: <b>{{ round((float)$existing_marker_length,2) }} m</b>
                        </p>
                        <p>Theoretical usable width: <b>{{ round($width_theor_usable, 2) }}</b> cm</p>
                        <br>
                        <p>New marker: </p>
                        

                        <select name="selected_marker" id='select2' class="select form-con rol sele ct-form cho sen" style="min-width:200px">
                           <option value="{{$existing_marker}}" selected></option>
                            
                            @foreach ($markers as $m)
                            <option value="{{ $m->marker }}">
                                {{ $m->marker }} 
                            </option>
                            @endforeach
                        </select>

                        <!--  <div class="alert alert-danger" role="alert">
                            Please choose marker that in comparison with existing one have only difference in width or lenght!
                        </div> -->
                        <br>
                        <br>

                        <div>
                            Requested width by operator:<br>
                            <b>{{ $requested_width }} cm</b>
                        </div>
                        <div>
                            Requested length by operator:<br>
                            <b>{{ round($requested_length,3) }} m</b>
                        </div>
                        
                        <hr><br>
                        {!! Form::submit('Change marker', ['class' => 'btn  btn-danger center-block']) !!}
                        
                        @include('errors.list')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
