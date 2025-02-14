@extends('app')

@section('content')

<style>
    body {
      font-family: Arial, sans-serif;
    }
    .line {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }
    .line span {
      flex: 1;
    }
    button {
      width: 30px;
      height: 30px;
      margin: 0 5px;
    }
    .counts-summary {
      margin-top: 20px;
      padding: 10px;
      border: 1px solid #ccc;
      background: #f9f9f9;
    }
    .counts-summary span {
      display: block;
      margin-bottom: 5px;
    }
    .submit-container {
      margin-top: 20px;
    }
    .submit-container button {
      padding: 10px 20px;
      margin-right: 10px;
      background-color: green;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 150px;
    }
    .submit-container button:disabled {
      background-color: gray;
      cursor: not-allowed;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Mattress: <big><b>{{ $mattress }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                <!-- <form id="production-form" method="POST" action="/cutting/plan_mattress_line_confirm"> -->
                {!! Form::open(['method'=>'POST', 'url'=>'plan_mattress_line_confirm', 'id'=>'production-form']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                        {!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('selected_marker', $selected_marker, ['class' => 'form-control']) !!}
                            
                       
                        <table style="width:100%" class="table table-striped table-bordered">
                        <tr><td>Mattress</td><td><b>{{ $mattress }} </b></td></tr>
                        <tr><td>Material</td><td><b>{{ $material }} </b></td></tr>
                        <tr><td>Dye lot</td><td><b>{{ $dye_lot }} </b></td></tr>
                        <tr><td>Color desc</td><td><b>{{ $color_desc }} </b></td></tr>
                        <tr><td>Skeda</td><td><b>{{ $skeda }} </b></td></tr>
                        <tr><td>Skeda type</td><td><b>{{ $skeda_item_type }} </b></td></tr>
                        <tr><td>Width usable (theoretical)</td><td><b>{{ round($width_theor_usable,3) }} </b></td></tr>
                        <tr><td>Layers Planned</td><td><b>{{ round($layers,0) }} </b></td></tr>
                        <tr><td>Consumption actual</td><td><b>{{ round($cons_actual,3) }} </b></td></tr>
                        <tr><td>Marker name</td><td><b>{{ $marker_name }} </b></td></tr>
                        <tr><td>Marker length</td><td><b>{{ round($marker_length,3) }} </b></td></tr>
                        <tr><td>Marker width</td><td><b>{{ round($marker_width,3) }} </b></td></tr>
                        <tr><td>TPP mat. keep wastage</td><td><b>@if ($tpp_mat_keep_wastage == 0) NO @else YES @endif</b></td></tr>
                        <tr><td>TPA number</td><td><b>{{ $tpa_number }} </b></td></tr>
                        </table>
                        
                        @if (isset($mattress_pro_array) AND ($skeda_item_type != 'MT') )
                          <h4>Mattress ratio by PRO</h4>
                          <input type="hidden" id="mattressProArrayInput" name="mattressProArray" value="">
                          <input type="hidden" id="markerPcsPerLayerInput" name="markerPcsPerLayer" value="">

                            <div id="lines-container"></div>
                            <div class="counts-summary">
                                <h4>Marker ratio by style and size</h4>
                                <div id="counts-summary"></div>
                            </div>
                            <div class="submit-container">
                              <button type="button" id="auto-populate-button">Auto-Populate</button>
                              <!-- <button type="submit" id="submit-button" disabled>Submit</button> -->
                            </div>
                            <br>

                        @endif

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), 1, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Mandatory to inspect cut parts (test marker):</p>
                            {!! Form::select('mandatory_to_ins', array('NO'=>'NO','YES'=>'YES'), 'NO', array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Pcs per bundle: <span style="color:red;">*</span></p>
                            {!! Form::number('pcs_bundle', round($pcs_bundle,0), ['class' => 'form-control', 'autofocus' => 'autofocus', 'step' => '0']) !!}
                        </div>
                        

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::textarea('comment_office', $comment_office, ['class' => 'form-control', 'rows' => 2]) !!}
                        </div>

                        <div class="panel-body">
                        <p>Bottom paper: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::select('bottom_paper', array('Brown'=>'Brown','White'=>'White',''=>''), 'Brown', array('class' => 'form-control')) !!} 
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
                        @if ($skeda_item_type != "MM")

                            @if ($skeda_item_type != "MT")
                            <table  style="width:100%">
                                <tr>
                                    <td style="width:50%">
                                        <div class="panel-body">
                                        <p>SP0: <!-- <span style="color:red;">*</span> --></p>
                                            <input type="radio" value="SP0" id="sp0" name="location" class="form-control" checked>
                                        </div>
                                    </td>

                                    <!-- <td style="width:12%">
                                        <div class="panel-body">
                                        <p>SP1: </p>
                                            <input type="radio" value="SP1" id="sp1" name="location" class="form-control">
                                        </div>
                                    </td>
                                
                                    <td style="width:12%">
                                        <div class="panel-body">
                                        <p>SP2: </p>
                                            <input type="radio" value="SP2" id="sp2" name="location" class="form-control">
                                        </div>
                                    </td>

                                    <td style="width:12%">
                                        <div class="panel-body">
                                        <p>SP3: </p>
                                            <input type="radio" value="SP3" id="sp3" name="location" class="form-control">
                                        </div>
                                    </td>

                                    <td style="width:12%">
                                        <div class="panel-body">
                                        <p>SP4: </p>
                                            <input type="radio" value="SP4" id="sp4" name="location" class="form-control">
                                        </div>
                                    </td>
                                    <td style="width:12%">
                                        <div class="panel-body">
                                        <p>MS1: </p>
                                            <input type="radio" value="MS1" id="ms1" name="location" class="form-control">
                                        </div>
                                    </td>
                                
                                    <td style="width:12%">
                                        <div class="panel-body">
                                        <p>MS2: </p>
                                            <input type="radio" value="MS2" id="ms2" name="location" class="form-control">
                                        </div>
                                    </td>

                                    <td style="width:12%">
                                        <div class="panel-body">
                                        <p>MS3: </p>
                                            <input type="radio" value="MS3" id="ms3" name="location" class="form-control">
                                        </div>
                                    </td> -->
                                </tr>
                            </table>
                            @else 
                            
                            <div class="panel-body">
                            <p>TUB:<!--  <span style="color:red;">*</span> --></p>
                                <input type="radio" value="TUB" id="tub" name="location" class="form-control" checked>
                            </div>
                            @endif
                        @else 
                            
                            <div class="panel-body">
                            <p>MM1:<!--  <span style="color:red;">*</span> --></p>
                                <input type="radio" value="MM1" id="mm1" name="location" class="form-control" checked>
                            </div>
                        @endif
                        
                        <hr>
                        <br>
                        @if (isset($mattress_pro_array) AND ($skeda_item_type != 'MT'))
                            {!! Form::submit('Save', ['id'=>'submit-button','class' => 'btn  btn-success center-block','disabled'=>'disabled']) !!}
                        @else
                            {!! Form::submit('Save', ['id'=>'submit-button','class' => 'btn  btn-success center-block']) !!}
                        @endif
                        <br>
                        @include('errors.list')

                       

                {!! Form::close() !!}

@if (isset($mattress_pro_array) AND ($skeda_item_type != 'MT'))
    <script>
        
        const mattressProArray = <?php echo json_encode(array_values($mattress_pro_array)); ?>;

        const markerPcsPerLayer = <?php 
            $result = [];
            foreach ($marker_pcs_per_layer as $item) {
                $parts = explode('#', $item);
                $key = isset($parts[0]) ? $parts[0] : ''; // Ensure key exists
                $value = isset($parts[1]) ? (int) $parts[1] : 0;

                // Sum up values instead of overwriting
                if (!isset($result[$key])) {
                    $result[$key] = 0;
                }
                $result[$key] += $value; // Sum instead of overwrite
            }
            echo json_encode($result);
        ?>;

        // Track the counts per size across all lines
        const currentCounts = Object.fromEntries(Object.keys(markerPcsPerLayer).map(key => [key, 0]));
        const lineCounts = Array(mattressProArray.length).fill(0);

        function renderLines1() {
            const container = document.getElementById('lines-container');
            container.innerHTML = '';

            mattressProArray.forEach((item, index) => {
                const [key, secondValue, thirdValue] = item.split('#');
                const lineDiv = document.createElement('div');
                lineDiv.className = 'line';

                const nameSpan = document.createElement('span');
                nameSpan.textContent = `${key} - ${thirdValue}`;

                const decreaseButton = document.createElement('button');
                decreaseButton.type = 'button';
                decreaseButton.textContent = '-';
                decreaseButton.addEventListener('click', () => updateCount(key, index, -1));

                const countSpan = document.createElement('span');
                countSpan.textContent = 0;
                countSpan.id = `count-${index}`;

                const increaseButton = document.createElement('button');
                increaseButton.type = 'button';
                increaseButton.textContent = '+';
                increaseButton.addEventListener('click', () => updateCount(key, index, 1));

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `line-${index}`;
                hiddenInput.id = `input-${index}`;
                hiddenInput.value = 0;

                lineDiv.appendChild(nameSpan);
                lineDiv.appendChild(decreaseButton);
                lineDiv.appendChild(countSpan);
                lineDiv.appendChild(increaseButton);
                lineDiv.appendChild(hiddenInput);
                container.appendChild(lineDiv);
            });

            renderCurrentCounts();
            updateHiddenInputs();
        }

        function renderLines() {
            const container = document.getElementById('lines-container');
            container.innerHTML = '';

            let previousSize = null;

            mattressProArray.forEach((item, index) => {
                const [key, secondValue, thirdValue] = item.split('#');
                const lineDiv = document.createElement('div');
                lineDiv.className = 'line';

                const nameSpan = document.createElement('span');
                nameSpan.textContent = `${key} - ${thirdValue}`;

                const decreaseButton = document.createElement('button');
                decreaseButton.type = 'button';
                decreaseButton.textContent = '-';
                decreaseButton.addEventListener('click', () => updateCount(key, index, -1));

                const countSpan = document.createElement('span');
                countSpan.textContent = 0;
                countSpan.id = `count-${index}`;

                const increaseButton = document.createElement('button');
                increaseButton.type = 'button';
                increaseButton.textContent = '+';
                increaseButton.addEventListener('click', () => updateCount(key, index, 1));

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `line-${index}`;
                hiddenInput.id = `input-${index}`;
                hiddenInput.value = 0;

                lineDiv.appendChild(nameSpan);
                lineDiv.appendChild(decreaseButton);
                lineDiv.appendChild(countSpan);
                lineDiv.appendChild(increaseButton);
                lineDiv.appendChild(hiddenInput);
                container.appendChild(lineDiv);

                // Insert a bold line if the next item is a different size or if it's the last item
                if (index === mattressProArray.length - 1 || mattressProArray[index + 1].split('#')[0] !== key) {
                    const boldLine = document.createElement('div');
                    boldLine.style.borderTop = '2px solid black'; // Bold effect
                    boldLine.style.margin = '8px 0';
                    container.appendChild(boldLine);
                }
            });

            renderCurrentCounts();
            updateHiddenInputs();
        }

        function updateCount(key, index, change) {
            const currentSizeTotal = currentCounts[key];
            const maxCount = markerPcsPerLayer[key];
            const currentLineCount = lineCounts[index];
            const newLineCount = currentLineCount + change;

            if (newLineCount >= 0 && currentSizeTotal + change <= maxCount) {
                currentCounts[key] += change;
                lineCounts[index] = newLineCount;

                document.getElementById(`count-${index}`).textContent = newLineCount;
                document.getElementById(`input-${index}`).value = newLineCount;

                renderCurrentCounts();
                updateHiddenInputs();
                toggleSubmitButton();
            }
        }

        function renderCurrentCounts() {
            const summaryContainer = document.getElementById('counts-summary');
            summaryContainer.innerHTML = '';

            Object.keys(currentCounts).forEach(key => {
                const countDisplay = document.createElement('span');
                countDisplay.textContent = `${key}: ${currentCounts[key]} (Max: ${markerPcsPerLayer[key]})`;
                summaryContainer.appendChild(countDisplay);
            });
        }

        function toggleSubmitButton() {
            const allMaxed = Object.keys(markerPcsPerLayer).every(
                key => currentCounts[key] === markerPcsPerLayer[key]
            );

            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = !allMaxed;
        }

        function autoPopulate() {
            Object.keys(markerPcsPerLayer).forEach(size => {
                const maxCount = markerPcsPerLayer[size];
                let remaining = maxCount - currentCounts[size];

                if (remaining > 0) {
                    mattressProArray.forEach((item, index) => {
                        const [key, _] = item.split('#');

                        if (key === size && remaining > 0) {
                            const currentLineCount = lineCounts[index];
                            const availableSpace = Math.min(remaining, maxCount - currentLineCount);
                            updateCount(size, index, availableSpace);
                            remaining -= availableSpace;
                        }
                    });
                }
            });
        }

        function updateHiddenInputs() {
            document.getElementById('mattressProArrayInput').value = JSON.stringify(mattressProArray);
            document.getElementById('markerPcsPerLayerInput').value = JSON.stringify(markerPcsPerLayer);
        }

        document.getElementById('auto-populate-button').addEventListener('click', autoPopulate);
        renderLines();
    </script>
@endif

            </div>
        </div>
    </div>
</div>
@endsection
