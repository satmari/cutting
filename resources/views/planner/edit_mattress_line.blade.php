@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Mattress: <big><b>{{ $mattress }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/edit_mattress_line_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}

                        {!! Form::hidden('location', $location, ['class' => 'form-control']) !!}
                            
                            <br>
                            Material: <b>{{ $material }} </b></b><br>
                            Dye lot: <b>{{ $dye_lot}} </b><br>
                            Color desc: <b>{{ $color_desc}} </b><br>
                            Skeda: <b>{{ $skeda}} </b><br>
                            Skeda type: <b>{{ $skeda_item_type}} </b><br>
                            Spreading method: <b>{{ $spreading_method}} </b><br>
                            Width usable (theoretical): <b>{{ round($width_theor_usable,3) }} </b><br>
                            Layers: <b>{{ $layers}} </b><br>
                            Consumption planned: <b>{{ round($cons_planned,3)}} </b><br>
                            Marker name: <b>{{ $marker_name}} </b><br>
                            Marker length: <b>{{ round($marker_length,3)}} </b><br>
                            Marker width: <b>{{ round($marker_width,3)}} </b><br>
                            TPP mat. keep wastage: <b>{{ $tpp_mat_keep_wastage }} </b><br>
                            TPA No: <b>{{ $tpa_number }} </b><br>
                            Pcs per bundle: <b>{{ $pcs_bundle }} </b><br>
                            Bottom paper: <b>{{ $bottom_paper }} </b>
                            <hr>
                            
                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'1 low','2'=>'2 medium','3'=>'3 high'), $priority, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::textarea('comment_office', $comment_office, ['class' => 'form-control', 'rows' => 2]) !!}
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

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
