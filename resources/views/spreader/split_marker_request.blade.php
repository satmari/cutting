@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-5 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Split mattress</b> for g_bin: <big><b>{{ $g_bin }}</b></big></div>
              
                <div class="panel-body">
                <br>
                
                {!! Form::open(['method'=>'POST', 'url'=>'/split_marker_request_post']) !!}

                    {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                    {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                    {!! Form::hidden('md_id', $md_id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('marker_name', $marker_name, ['class' => 'form-control']) !!}
                    {!! Form::hidden('mm_id', $mm_id, ['class' => 'form-control']) !!}
                    {!! Form::hidden('marker_width', $marker_width, ['class' => 'form-control']) !!}
                    {!! Form::hidden('marker_length', $marker_length, ['class' => 'form-control']) !!}
                    <br>
                    <p>
                        Mattress <b>{{ $mattress }}</b> (g bin <b>{{ $g_bin }}</b>) <br> 
                        have marker width of <b>{{ (int)$marker_width }} cm</b><br>
                        have marker length of <b>{{ $marker_length }} m</b><br>
                        Theoretical usable width: <b>{{ round($width_theor_usable,2) }} cm</b>
                        <br><br>
                    </p>
                    
                    <div class="panel-body">
                    <p>Requested width [cm]:<span style="color:red;">*</span></p>
                    {!! Form::input('number', 'requested_width', '', ['class' => 'form-control', 'step'=>'1' ]) !!}
                    </div>
                     
                    <div class="panel-body">
                    <p>Requested length [m]: <i>(dot for decimal place) </i><b>(optional)</b></p>
                    {!! Form::input('number', 'requested_length', '', ['class' => 'form-control','step'=>'0.01' ]) !!}
                    </div>
                    
                    <div class="panel-body">
                    <p>Comment operator: <b>(optional)</b></p>
                        {!! Form::textarea('comment_operator', '' , ['class' => 'form-control', 'rows' => 2]) !!}
                    </div>
                    
                    <hr>
                    {!! Form::submit('Send request', ['class' => 'btn  btn-success center-block']) !!}
                    <br>
                    @include('errors.list')

                {!! Form::close() !!}

                <div class="info alert-danger" role="alert">
                  
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
