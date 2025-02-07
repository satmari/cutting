@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-5 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Change marker for g_bin: <big><b>{{ $g_bin }}</b></big></div>
              
                <div class="panel-body">
                <br>
                
                {!! Form::open(['method'=>'POST', 'url'=>'/change_all_marker_request_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_name', $marker_name, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('status', $status, ['class' => 'form-control']) !!}
                        {!! Form::hidden('md_id', $md_id, ['class' => 'form-control']) !!}

                            
                        <br>
                        <p>
                            Mattress <b>{{ $mattress }}</b> (g bin <b>{{ $g_bin }}</b>) <br> 
                            with marker <b>{{ $marker_name }}</b> <br>
                            have <b>marker width of {{ (int)$marker_width }} cm</b><br>
                            theoretical <b>usable width {{ round($width_theor_usable,2) }} cm</b><br>
                            and marker <b>length {{ round($marker_length,2) }} m</b> <br>
                            <br><br>
                        </p>
                        
                        <div class="panel-body">
                            <p>Requested width:<span style="color:red;">*</span></p>
                            {!! Form::input('number', 'requested_width', round($requested_width,2), ['class' => 'form-control']) !!}
                            </div>
                            <div class="alert alert-info" role="alert">
                              <p>Please insert width of the mattress?</p>
                        </div>  
                        <div class="panel-body">
                            <p>Requested length:<span style="color:red;">*</span></p>
                            {!! Form::input('number', 'requested_length', round($requested_length,3), ['class' => 'form-control','step' => '0.01']) !!}
                            </div>
                            <div class="alert alert-info" role="alert">
                              <p>Please insert length of the mattress?</p>
                        </div>                        
                        
                        <hr>
                        <br>
                        {!! Form::submit('Send request', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}

                <div class="alert alert-warning" role="alert">
                  <p>Mattress will stay in list, but you can not spread mattress. When planner change marker you can continue with cutting.</p>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
