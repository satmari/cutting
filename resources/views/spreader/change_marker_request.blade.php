@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-5 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Change width for g_bin: <big><b>{{ $g_bin }}</b></big></div>
              
                <div class="panel-body">
                <br>
                
                {!! Form::open(['method'=>'POST', 'url'=>'/change_marker_request_post']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('status', $status, ['class' => 'form-control']) !!}
                        {!! Form::hidden('md_id', $md_id, ['class' => 'form-control']) !!}

                            
                        <br>
                        <p>
                            Mattress <b>{{ $mattress }}</b> (g bin <b>{{ $g_bin }}</b>) <br> 
                            have marker width of <b>{{ (int)$marker_width }} cm</b><br>
                            Theoretical usable width: <b>{{ round($width_theor_usable,2) }} cm</b>
                            <br><br>
                        </p>
                        
                        <div class="panel-body">
                        <p>Requested width:<span style="color:red;">*</span></p>
                        {!! Form::input('number', 'requested_width', $requested_width, ['class' => 'form-control']) !!}
                        </div>
                        <div class="alert alert-info" role="alert">
                          <p>Please insert width of the mattress?</p>
                        </div>                        
                        
                        

                        
                        <hr>
                        {!! Form::submit('Send request', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}

                <div class="alert alert-info" role="alert">
                  <p>Mattress will stay in list, but you can not spread mattress. When planner change width you can continue with spreading.</p>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
