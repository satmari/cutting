@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Paspul job: <big><b>{{ $paspul_roll }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'plan_paspul_line_confirm1']) !!}

                        
                        {!! Form::hidden('paspul_roll', $paspul_roll, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_roll_id', $paspul_roll_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}
                        
                        
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
                            <tr><td>Skeda type</td><td><b>{{ $skeda_item_type }} </b></td></tr>
                            <!-- <tr><td>Bin</td><td><b>{{ $pasbin }} </b></td></tr> -->
                            <tr><td>TPA number</td><td><b>{{ $tpa_number }} </b></td></tr>
                            <tr><td>Rewinding method</td><td><b>{{ $rewinding_method }} </b></td></tr>
                            <tr><td>Rewound length </td><td><b>{{ $rewound_length_a }} </b></td></tr>
                            <tr><td>Rewound Uom </td><td><b>{{ $rewound_roll_unit_of_measure }} </b></td></tr>
                            @if($rewound_roll_unit_of_measure = 'meter')
                                <tr><td>Mtr per pcs</td><td><b>{{ round($unit_cons,3) }} </b></td></tr>
                            @else
                                <tr><td>Pcs per Square</td><td><b>{{ round($unit_cons,3) }} </b></td></tr>
                            @endif
                            <tr><td>Kotur actual </td><td><b>{{ round($kotur_actual,2) }} </b></td></tr>
                            </table>

                        @if(isset($bin) AND count($bin) > 1 )
                        <p>Bin: <span style="color:red;">*</span></p>
                        <table style="width:100%">
                        <tbody class="searchable">
                        
                            @foreach ($bin as $req1)
                            <tr>
                                <div class="checkbox">
                                <label style="width: 95%;" type="button" class="btn check btn-default"  data-color="primary">
                                    <input type="radio" class="btn check" name="bins[]" value="{{ $req1 }}"
                                    >
                                       {{ $req1 }}
                                </label>
                                </div>
                            </tr>
                            @endforeach

                        </tbody>
                        </table>
                        @endif

                        @if(isset($bin) AND count($bin) == 1 AND $bin[0] !='')
                            {!! Form::hidden('bin', $bin[0], ['class' => 'form-control']) !!}
                            <p>Bin: <b><big>{{ $bin[0] }}</big></b></p>
                        @endif

                        
                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), $priority, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::text('comment_office', $comment_office, ['class' => 'form-control']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Dye lot: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::text('dye_lot', $dye_lot, ['class' => 'form-control']) !!}
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
                        
                        <br>


                        <button type="submit" name="action" value="save" class="btn btn-success center-block">Save</button>
                        <hr>
                        <br>


                        
                        <div style="border-style: solid;border-width: 5px;border-color:orange; border-image: linear-gradient(45deg, yellow 25%, black 25%, black 50%, yellow 50%, yellow 75%, black 75%) 10;">
                            <table class="table" style="  border-collapse: collapse;width: 100%;">
                                <tr>
                                    <th>Insert required garment quantity</th>
                                    <th>Rewound length suggestion</th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="number" id="quantity" oninput="calculateResult()" placeholder="insert qty">
                                    </td>
                                    <td id="result" style="font-weight: bold;font-size: 18px;">result</td>
                                </tr>
                            </table>
                            <script>
                                 // PHP variables passed to JavaScript
                                    const rewoundRollUnitOfMeasure = "<?php echo $rewound_roll_unit_of_measure; ?>";
                                    const unitCons = "<?php echo $unit_cons; ?>";
                                    const koturActual = "<?php echo $kotur_actual; ?>";

                                    function calculateResult() {
                                        const qty = document.getElementById('quantity').value;
                                        let result;

                                        if (rewoundRollUnitOfMeasure === 'meter') {
                                            // Formula for 'meter'
                                            result = (qty * unitCons) / koturActual;
                                        } else {
                                            // Formula for other units
                                            result = qty / unitCons / koturActual;
                                        }

                                        document.getElementById('result').textContent = result ? result.toFixed(2) : 'result';
                                    }
                            </script>
                        </div>

                        <hr>
                        <div class="panel-body">
                        <p>Rewound length: <span style="color:red;">*</span></p>
                        <p><span style="color:purple">From this paspul you still have to plan <big><b>{{ round($rewound_length - $rewound_length_p,2)  }}</b></big> meters</span></p>
                            {!! Form::number('rewound_length_partialy', round($rewound_length - $rewound_length_p,2), ['class' => 'form-control','step'=>'0.1']) !!} 
                            
                        </div>
                         <table style="width:100%">
                        <tr>
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Final roll: <!-- <span style="color:red;">*</span> --></p>

                                    {!! Form::checkbox('final_roll', '1' , '' , ['class' => 'form-control']) !!}
                                </div>
                            </td>
                
                        </tr>
                        </table>
                        <br>

                        <br>
                        <!-- {!! Form::submit('Create', ['class' => 'btn  btn-success center-block']) !!}\ -->
                        <button type="submit" name="action" value="plan" class="btn btn-success center-block">Plan paspul</button>
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
