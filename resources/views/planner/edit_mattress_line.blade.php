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
                @if (isset($msgs))
                    <div class="alert alert-success" role="alert">
                    {{ $msgs }}     
                    </div>
                @endif

                {!! Form::open(['method'=>'POST', 'url'=>'/edit_mattress_line_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('material', $material, ['class' => 'form-control']) !!}
                        {!! Form::hidden('dye_lot', $dye_lot, ['class' => 'form-control']) !!}
                        {!! Form::hidden('color_desc', $color_desc, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
                        {!! Form::hidden('width_theor_usable', round($width_theor_usable,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('layers', round($layers,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('layers_a', round($layers_a,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('cons_planned', round($layers_a,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('cons_actual', round($cons_actual,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_name', $marker_name, ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_length', round($marker_length,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_width', round($marker_width,3), ['class' => 'form-control']) !!}
                        {!! Form::hidden('tpp_mat_keep_wastage', $tpp_mat_keep_wastage, ['class' => 'form-control']) !!}
                        {!! Form::hidden('tpa_number', $tpa_number, ['class' => 'form-control']) !!}
                        {!! Form::hidden('location', $location, ['class' => 'form-control']) !!}
                        
                        {!! Form::hidden('layer_limit', $layer_limit, ['class' => 'form-control']) !!}
                        

                            
                            <table style="width:100%; font-size: large;" class="table table-striped table-bordered">
                            <tr><td>Mattress</td><td><b>{{ $mattress }} </b></td></tr>
                            <tr><td>Material</td><td><b>{{ $material }} </b></td></tr>
                            <tr><td>Dye lot</td><td><b>{{ $dye_lot }} </b></td></tr>
                            <tr><td>Color desc</td><td><b>{{ $color_desc }} </b></td></tr>
                            <tr><td>Skeda</td><td><b>{{ $skeda }} </b></td></tr>
                            <tr><td>Skeda type</td><td><b>{{ $skeda_item_type }} </b></td></tr>
                            <tr><td>Width usable (theoretical)</td><td><b>{{ round($width_theor_usable,3) }} </b></td></tr>
                            <tr><td>Layers Planned</td><td><b>{{ round($layers,0) }} </b></td></tr>
                            <tr><td>Layers Actual</td><td><b>{{ round($layers_a,0) }} </b>
                                @if (($location == 'CUT' OR $location == 'COMPLETED'))
                                        &nbsp;&nbsp;&nbsp;<a href="{{ url('edit_layers_a/'.$id) }}" class="btn btn-danger btn-xs">Change</a>
                                @endif
                            </td></tr>
                            <tr><td>Consumption actual</td><td><b>{{ round($cons_actual,3) }} </b></td></tr>
                            <tr><td>Marker name</td><td><b>{{ $marker_name }} </b></td></tr>
                            <tr><td>Marker length</td><td><b>{{ round($marker_length,3) }} </b></td></tr>
                            <tr><td>Marker width</td><td><b>{{ round($marker_width,3) }} </b></td></tr>
                            <tr><td>TPP mat. keep wastage</td><td><b>@if ($tpp_mat_keep_wastage == 0) NO @else YES @endif</b></td></tr>
                            <tr><td>TPA number</td><td><b>{{ $tpa_number }} </b></td></tr>
                            <tr><td>Mandatory to inspect </td><td><b>{{ $mandatory_to_ins }} </b></td></tr>
                            @if ($location == 'MM1')
                                <tr><td>Layer limit</td><td><b>{{ $layer_limit }} </b></td></tr>
                            @endif
                            <tr><td>Cutter shrink X</td><td><b>{{ $cutter_shrink_x }} </b></td></tr>
                            <tr><td>Cutter shrink Y</td><td><b>{{ $cutter_shrink_y }} </b></td></tr>
                            <tr><td></td><td><a href="{{url('/correct_location/'.$id) }}" class="btn btn-danger btn-xs center-blo ck">Correct location</a></td></tr>
                            </table>


                            @if (isset($data2))
                            <table style="width:100%" class="table table-striped table-bordered">
                                <tr>
                                    <td>Leftover roll</td>
                                    <td>G bin</td>
                                    <td>Number of joinings</td>
                                </tr>
                                 @foreach ($data2 as $req)
                                 <tr>
                                    <td><b>{{ $req->o_roll}}</b></td>
                                    <td><b>{{ $req->g_bin}}</b></td>
                                    <td><b>{{ $req->no_of_joinings}}</b></td>
                                 </tr>
                                 @endforeach
                            </table>
                            @endif

                            @if (isset($sp_operator))
                            <table style="width:100%" class="table table-striped table-bordered">
                                <tr><td>Spreader operator:</td><td><b>{{$sp_operator}}</b></td></tr>
                                <tr><td>Date:</td><td><b>{{$sp_date}}</b></td></tr>
                            </table>
                            @endif

                            @if (isset($cut_operator))
                            <table style="width:100%" class="table table-striped table-bordered">
                                <tr><td>Cutter operator:</td><td><b>{{$cut_operator}}</b></td></tr>
                                <tr><td>Date:</td><td><b>{{$cut_date}}</b></td></tr>
                            </table>
                            @endif
                        
                    @if ($location == 'COMPLETED' OR $location == 'DELETED')

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), $priority, array('class' => 'form-control', 'disabled')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Pcs per bundle: <span style="color:red;">*</span></p>
                           {!! Form::number('pcs_bundle', round($pcs_bundle,0), ['class' => 'form-control', 'disabled']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Required time: <span style="color:red;">*</span></p>
                           {!! Form::number('req_time', round($req_time,2), ['class' => 'form-control', 'disabled']) !!}
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
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', $call_shift_manager, $call_shift_manager , ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </td>
                        
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Test marker:<!--  <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('test_marker',  $test_marker, $test_marker, ['class' => 'form-control', 'disabled'])!!}
                                </div>
                            </td>

                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Last  mattress:<!--  <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('last_mattress',  $last_mattress, $last_mattress, ['class' => 'form-control', 'disabled'])!!}
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
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), $priority, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Pcs per bundle: <span style="color:red;">*</span></p>
                            {!! Form::number('pcs_bundle', round($pcs_bundle,0), ['class' => 'form-control']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Required time: <span style="color:red;">*</span></p>
                           {!! Form::number('req_time', round($req_time,2), ['class' => 'form-control','step' => '0.01']) !!}
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
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', '1' , $call_shift_manager , ['class' => 'form-control']) !!}
                                </div>
                            </td>
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Test marker:<!--  <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('test_marker', '1', $test_marker, ['class' => 'form-control'])!!}
                                </div>
                            </td>
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Last  mattress:<!--  <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('last_mattress',  '1', $last_mattress, ['class' => 'form-control'])!!}
                                </div>
                            </td>
                       
                        </tr>
                        </table>

                        @if ($location != 'TUB' AND $location != 'MM1')
                        <hr>
                        <p>Set new location</p>
                            <table style="width:100%">
                                <tr>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                            <p>SP0: </p>
                                            <input type="radio" value="SP0" id="sp0" name="location_new" class="form-control"
                                                {{ $location == 'SP0' ? 'checked' : '' }} {{ $status != 'TO_LOAD' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                            <p>SP1: </p>
                                            <input type="radio" value="SP1" id="sp1" name="location_new" class="form-control"
                                                {{ $location == 'SP1' ? 'checked' : '' }} {{ $status != 'TO_LOAD' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                            <p>SP2: </p>
                                            <input type="radio" value="SP2" id="sp2" name="location_new" class="form-control"
                                                {{ $location == 'SP2' ? 'checked' : '' }} {{ $status != 'TO_LOAD' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                            <p>SP3: </p>
                                            <input type="radio" value="SP3" id="sp3" name="location_new" class="form-control"
                                                {{ $location == 'SP3' ? 'checked' : '' }} {{ $status != 'TO_LOAD' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                            <p>SP4: </p>
                                            <input type="radio" value="SP4" id="sp4" name="location_new" class="form-control"
                                                {{ $location == 'SP4' ? 'checked' : '' }} {{ $status != 'TO_LOAD' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                            <p>MS1: </p>
                                            <input type="radio" value="MS1" id="ms1" name="location_new" class="form-control"
                                                {{ $location == 'MS1' ? 'checked' : '' }} {{ $status != 'TO_LOAD' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        @endif 
                        <hr>


                        <br>
                        {!! Form::submit('Save', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        @include('errors.list')

                    @endif
                    <div class="panel-body">
                        <a href="{{ url('plan_mattress/'.$location) }}" class="btn btn-default center-block">Back without Save</a></th>    
                    </div>
                    
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
