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
        <div class="text-center">
            <div class="panel panel-default col-md-4 col-md-offset-4">
                <div class="panel-heading">Selected marker: <big><b>{{ $selected_marker }}</b></big></div>
                
                <!-- <pre><?php print_r($mattress_pro_array); ?></pre> -->
                <!-- <pre><?php print_r($marker_pcs_per_layer); ?></pre> -->

                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                

                       
                        
                        <!-- <br> -->

                        <!-- <table class="table table-s triped table-bordered" id="sort" >
                            <thead>
                                <tr>            
                                    <th><b>Style</b></th>
                                    <th><b>Size</b></th>
                                    <th><b>Qty</b></th>
                                </tr>
                            </thead>
                             <tbody class="searchable">
                            <br>
                            </tbody>     
                        </table>  -->

                        <!-- <br> -->
                        

                        <!-- <form id="production-form" method="POST" action="change_marker_all_post_check"> -->
                        {!! Form::open(['method'=>'POST', 'url'=>'/change_marker_all_post_check','id'=>'production-form']) !!}

                          {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                          {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                          {!! Form::hidden('selected_marker', $selected_marker, ['class' => 'form-control']) !!}
                          {!! Form::hidden('existing_marker', $existing_marker, ['class' => 'form-control']) !!}
                          {!! Form::hidden('existing_marker_id', $existing_marker_id, ['class' => 'form-control']) !!}
                          {!! Form::hidden('mattress_details_id', $mattress_details_id, ['class' => 'form-control']) !!}
                          {!! Form::hidden('existing_mattress_marker_id', $existing_mattress_marker_id, ['class' => 'form-control']) !!}
                          {!! Form::hidden('existing_marker_length', $existing_marker_length, ['class' => 'form-control']) !!}
                          {!! Form::hidden('existing_marker_width', $existing_marker_width, ['class' => 'form-control']) !!}
                          {!! Form::hidden('existing_min_length', $existing_min_length, ['class' => 'form-control']) !!}
                          {!! Form::hidden('selected_marker', $selected_marker, ['class' => 'form-control']) !!}
                          {!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!}

                          <br>
                          <h4>Mattress ratio by PRO</h4>
                          <br>

                          <input type="hidden" id="mattressProArrayInput" name="mattressProArray" value="">
                          <input type="hidden" id="markerPcsPerLayerInput" name="markerPcsPerLayer" value="">

                            <div id="lines-container"></div>
                            <div class="counts-summary">
                              <h4>Marker ratio by style and size</h4>
                              <div id="counts-summary"></div>
                            </div>
                            <div class="submit-container">
                              <button type="button" id="auto-populate-button">Auto-Populate</button>
                              <button type="submit" id="submit-button" disabled>Save</button>
                            </div>
                            <br>
                        </form>


<script>
    
    // Pass PHP arrays to JavaScript
    const mattressProArray = {!! json_encode(array_values($mattress_pro_array)) !!};
    const markerPcsPerLayer = {!! json_encode(array_combine(
        array_map(function ($item) {
            return explode('#', $item)[0]; 
        }, $mattress_pro_array),
        array_map(function ($item) {
            return (int)explode('#', $item)[1]; 
        }, $marker_pcs_per_layer)
    )) !!};


    // Track the counts per size across all lines
    const currentCounts = Object.fromEntries(Object.keys(markerPcsPerLayer).map(key => [key, 0]));

    // Track individual line counts
    const lineCounts = Array(mattressProArray.length).fill(0);

    function renderLines() {
        const container = document.getElementById('lines-container');
        container.innerHTML = ''; // Clear existing content

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
        updateHiddenInputs(); // Update hidden inputs
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
            updateHiddenInputs(); // Update hidden inputs
            toggleSubmitButton();
        }
    }

    function updateCount(key, index, change) {
      const currentSizeTotal = currentCounts[key]; // Total for the size
      const maxCount = markerPcsPerLayer[key]; // Max for the size
      const currentLineCount = lineCounts[index]; // Current line's count
      const newLineCount = currentLineCount + change;

      // Ensure the new total does not exceed the max and is >= 0
      if (newLineCount >= 0 && currentSizeTotal + change <= maxCount) {
        // Update the counts
        currentCounts[key] += change;
        lineCounts[index] = newLineCount;

        // Update the UI
        document.getElementById(`count-${index}`).textContent = newLineCount;
        document.getElementById(`input-${index}`).value = newLineCount;
        renderCurrentCounts(); // Update the summary
        toggleSubmitButton(); // Check if submit button should be shown
      }
    }

    function renderCurrentCounts() {
      const summaryContainer = document.getElementById('counts-summary');
      summaryContainer.innerHTML = ''; // Clear the previous summary

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
        const mattressProArrayInput = document.getElementById('mattressProArrayInput');
        const markerPcsPerLayerInput = document.getElementById('markerPcsPerLayerInput');

        // Convert JavaScript objects/arrays into JSON strings for posting
        mattressProArrayInput.value = JSON.stringify(mattressProArray);
        markerPcsPerLayerInput.value = JSON.stringify(markerPcsPerLayer);
    }

    document.getElementById('auto-populate-button').addEventListener('click', autoPopulate);

    renderLines();
  </script>
                        


            </div>
        </div>
    </div>
</div>
@endsection
