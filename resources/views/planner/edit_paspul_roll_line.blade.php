@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Paspul rewound roll: <big><b>{{ $paspul_rewound_roll }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'edit_paspul_roll_line_confirm']) !!}

                        
                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_rewound_roll', $paspul_rewound_roll, ['class' => 'form-control']) !!}
                        
                        
                            <!-- <br>
                            Material: <b>{{ $material }} </b></b><br>
                            Dye lot: <b>{{ $dye_lot}} </b><br>
                            Color desc: <b>{{ $color_desc}} </b><br>
                            Skeda: <b>{{ $skeda}} </b><br>
                            Skeda type: <b>{{ $skeda_item_type}} </b><br>
                            Rewinding method: <b>{{ $rewinding_method}} </b><br>
                            <hr> -->

                            <table style="width:100%" class="table table-striped table-bordered">
                            <tr><td>Material</td><td><b>{{ $material }} </b></td></tr>
                            <tr><td>Dye lot</td><td><b>{{ $dye_lot }} </b></td></tr>
                            <tr><td>Color desc</td><td><b>{{ $color_desc }} </b></td></tr>
                            <tr><td>Skeda</td><td><b>{{ $skeda }} </b></td></tr>
                            <tr><td>Bin</td><td><b>{{ $pasbin }} </b></td></tr>
                            <tr><td>Skeda type</td><td><b>{{ $skeda_item_type }} </b></td></tr>
                            <tr><td>TPA number</td><td><b>{{ $tpa_number }} </b></td></tr>
                            <tr><td>Rewinding method</td><td><b>{{ $rewinding_method }} </b></td></tr>
                            <tr><td>Rewound roll length </td><td><b>{{ $rewound_length_partialy }} </b></td></tr>
                            <tr><td>Rewound Uom </td><td><b>{{ $rewound_roll_unit_of_measure }} </b></td></tr>
                            @if($rewound_roll_unit_of_measure = 'meter')
                                <tr><td>Mtr per pcs</td><td><b>{{ round($unit_cons,3) }} </b></td></tr>
                            @else
                                <tr><td>Pcs per ploce</td><td><b>{{ round($unit_cons,3) }} </b></td></tr>
                            @endif
                            <tr><td>Kotur planned </td><td><b>{{ $kotur_planned }} </b></td></tr>
                            </table>

                        

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), $priority, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::text('comment_office', $comment_office, ['class' => 'form-control']) !!}
                        </div>

                        <table style="width:100%">
                        <tr>
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', '1' , $call_shift_manager , ['class' => 'form-control']) !!}
                                </div>
                            </td>
                        </tr>
                        </table>
                        <hr>
                        

                        <br>
                        {!! Form::submit('Save', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
