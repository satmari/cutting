@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">G bin: <big><b>{{ $g_bin }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/edit_mattress_line_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}

                        {!! Form::hidden('location', $location, ['class' => 'form-control']) !!}
                            
                            <!-- <br>
                            Mattress: <b>{{ $mattress }} </b></b><br>
                            Material: <b>{{ $material }} </b></b><br>
                            Dye lot: <b>{{ $dye_lot}} </b><br>
                            Color desc: <b>{{ $color_desc}} </b><br>
                            Skeda: <b>{{ $skeda}} </b><br>
                            Skeda type: <b>{{ $skeda_item_type}} </b><br>
                            Width usable (theoretical): <b>{{ round($width_theor_usable,3) }} </b><br>
                            Layers: <b>{{ $layers}} </b><br>
                            Consumption planned: <b>{{ round($cons_planned,3)}} </b><br>
                            Marker name: <b>{{ $marker_name}} </b><br>
                            Marker length: <b>{{ round($marker_length,3)}} </b><br>
                            Marker width: <b>{{ round($marker_width,3)}} </b><br>
                            TPP mat. keep wastage: <b>{{ $tpp_mat_keep_wastage }} </b><br>
                            TPA No: <b>{{ $tpa_number }} </b>
                            <hr> -->

                            <table style="width:100%" class="table table-striped table-bordered">
                            <tr><td>Mattress</td><td><b>{{ $mattress }} </b></td></tr>
                            <tr><td>Material</td><td><b>{{ $material }} </b></td></tr>
                            <tr><td>Dye lot</td><td><b>{{ $dye_lot }} </b></td></tr>
                            <tr><td>Color desc</td><td><b>{{ $color_desc }} </b></td></tr>
                            <tr><td>Skeda</td><td><b>{{ $skeda }} </b></td></tr>
                            <tr><td>Skeda type</td><td><b>{{ $skeda_item_type }} </b></td></tr>
                            <tr><td>Width usable (theoretical)</td><td><b>{{ round($width_theor_usable,3) }} </b></td></tr>
                            <tr><td>Layers</td><td><b>{{ $layers }} </b></td></tr>
                            <tr><td>Consumption planned</td><td><b>{{ round($cons_planned,3) }} </b></td></tr>
                            <tr><td>Marker name</td><td><b>{{ $marker_name }} </b></td></tr>
                            <tr><td>Marker length</td><td><b>{{ round($marker_length,3) }} </b></td></tr>
                            <tr><td>Marker width</td><td><b>{{ round($marker_width,3) }} </b></td></tr>
                            <tr><td>TPP mat. keep wastage</td><td><b>@if ($tpp_mat_keep_wastage == 0) NO @else YES @endif</b></td></tr>
                            <tr><td>TPA number</td><td><b>{{ $tpa_number }} </b></td></tr>
                            @if ($location == 'MM1')
                                <tr><td>Layer limit</td><td><b>{{ $layer_limit }} </b></td></tr>
                            @endif
                            </table>

                            @if (isset($data2))
                            <table style="width:100%" class="table table-striped table-bordered">
                                <tr>
                                    <td>Leftover roll</td>
                                    <td>Number of joinings</td>
                                </tr>
                                 @foreach ($data2 as $req)
                                 <tr>
                                    <td><b>{{ $req->o_roll}}</b></td>
                                    <td><b>{{ $req->no_of_joinings}}</b></td>
                                 </tr>
                                 @endforeach
                            </table>
                            @endif
                        
                        @if ($location == 'COMPLETED' OR $location == 'DELETED')

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'High','3'=>'Top'), $priority, array('class' => 'form-control', 'disabled')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Pcs per bundle: <span style="color:red;">*</span></p>
                           {!! Form::number('pcs_bundle', round($pcs_bundle,0), ['class' => 'form-control', 'disabled']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::textarea('comment_office', $comment_office, ['class' => 'form-control', 'disabled', 'rows' => 2]) !!}
                        </div>

                        <div class="panel-body">
                        <p>Bottom paper: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::select('bottom_paper', array('Brown'=>'Brown','White'=>'White',''=>''), $bottom_paper, array('class' => 'form-control', 'disabled')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Spreading method: </p>
                            {!! Form::text('spreading_method', $spreading_method , ['class' => 'form-control', 'disabled']) !!}
                        </div>

                        <table style="width:100%">
                        <tr>
                            <td style="width:49%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', $call_shift_manager, $call_shift_manager , ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </td>
                        
                            <td style="width:49%">
                                <div class="panel-body">
                                <p>Test marker:<!--  <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('test_marker',  $test_marker, $test_marker, ['class' => 'form-control', 'disabled'])!!}
                                </div>
                            </td>
                       
                        </tr>
                        </table>

                        <hr>
                        <div class="">
                            <a href="{{url('/')}}" class="btn btn-info cen ter-block">Back</a>
                        </div>
                        <br>

                        @else

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'High','3'=>'Top'), $priority, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Pcs per bundle: <span style="color:red;">*</span></p>
                            {!! Form::number('pcs_bundle', $pcs_bundle, ['class' => 'form-control']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::textarea('comment_office', $comment_office, ['class' => 'form-control', 'rows' => 2]) !!}
                        </div>

                        <div class="panel-body">
                        <p>Bottom paper: <!-- <span style="color:red;">*</span> --></p>
                            
                            {!! Form::select('bottom_paper', array('Brown'=>'Brown','White'=>'White',''=>''), $bottom_paper, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Spreading method: </p>
                            {!! Form::text('spreading_method', $spreading_method , ['class' => 'form-control']) !!}
                        </div>

                        <table style="width:100%">
                        <tr>
                            <td style="width:49%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', '1' , $call_shift_manager , ['class' => 'form-control']) !!}
                                </div>
                            </td>
                        
                            <td style="width:49%">
                                <div class="panel-body">
                                <p>Test marker:<!--  <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('test_marker', '1', $test_marker, ['class' => 'form-control'])!!}
                                </div>
                            </td>
                       
                        </tr>
                        </table>

                        <hr>
                        {!! Form::submit('Save', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')

                        @endif

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
