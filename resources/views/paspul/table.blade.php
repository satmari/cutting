@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Paspul table</div>
              
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered tableFixHead" id="sort" 
                data-export-types="['excel']"
                data-show-export="true"
                >
                <!--
                data-export-types="['excel']"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-query-params="queryParams" 
                data-pagination="true"
                data-height="300"
                data-show-columns="true" 
                data-export-options='{
                         "fileName": "preparation_app", 
                         "worksheetName": "test1",         
                         "jspdf": {                  
                           "autotable": {
                             "styles": { "rowHeight": 20, "fontSize": 10 },
                             "headerStyles": { "fillColor": 255, "textColor": 0 },
                             "alternateRowStyles": { "fillColor": [60, 69, 79], "textColor": 255 }
                           }
                         }
                       }'
                -->
                    <thead>
                        <tr class="test">
                           <th class="rotate"><div><span>id</div></span></th>
                           <th class="rotate"><div><span>paspul_roll</div></span></th>
                           <th class="rotate"><div><span>sap_su</div></span></th>
                           <th class="rotate"><div><span>material</div></span></th>
                           <th class="rotate"><div><span>color_desc</div></span></th>
                           <th class="rotate"><div><span>dye_lot</div></span></th>
                           <th class="rotate"><div><span>paspul_type</div></span></th>
                           <th class="rotate"><div><span>width</div></span></th>
                           <th class="rotate"><div><span>kotur_width</div></span></th>
                           <th class="rotate"><div><span>kotur_width_without_tension</div></span></th>
                           <th class="rotate"><div><span>kotur_planned</div></span></th>
                           <th class="rotate"><div><span>kotur_actual</div></span></th>
                           <th class="rotate"><div><span>rewound_length</div></span></th>
                           <th class="rotate"><div><span>rewound_length_a</div></span></th>
                           <th class="rotate"><div><span>pasbin</div></span></th>
                           <th class="rotate"><div><span>skeda_item_type</div></span></th>
                           <th class="rotate"><div><span>skeda</div></span></th>
                           <th class="rotate"><div><span>skeda_status</div></span></th>
                           <th class="rotate"><div><span>rewound_roll_unit_of_measure</div></span></th>
                           <th class="rotate"><div><span>position</div></span></th>
                           <th class="rotate"><div><span>priority</div></span></th>
                           <th class="rotate"><div><span>comment_office</div></span></th>
                           <th class="rotate"><div><span>comment_operator</div></span></th>
                           <th class="rotate"><div><span>call_shift_manager</div></span></th>
                           <th class="rotate"><div><span>rewinding_method</div></span></th>
                           <th class="rotate"><div><span>created_at</div></span></th>
                           <th class="rotate"><div><span>updated_at</div></span></th>

                           <th class="rotate"><div><span>|</div></span></th>

                           <th class="rotate"><div><span>status</div></span></th>
                           <th class="rotate"><div><span>location</div></span></th>
                           <th class="rotate"><div><span>device</div></span></th>
                           <th class="rotate"><div><span>active</div></span></th>
                           <th class="rotate"><div><span>operator1</div></span></th>
                           <th class="rotate"><div><span>operator2</div></span></th>

                            

                     
                            
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr class="test">
                           
                            <td>{{ $req->id}}</td>
                            <td>{{ $req->paspul_roll}}</td>
                            <td>{{ $req->sap_su}}</td>
                            <td>{{ $req->material}}</td>
                            <td>{{ $req->color_desc}}</td>
                            <td>{{ $req->dye_lot}}</td>
                            <td>{{ $req->paspul_type}}</td>
                            <td>{{ $req->width}}</td>
                            <td>{{ $req->kotur_width}}</td>
                            <td>{{ $req->kotur_width_without_tension}}</td>
                            <td>{{ $req->kotur_planned}}</td>
                            <td>{{ $req->kotur_actual}}</td>
                            <td>{{ round($req->rewound_length,2) }}</td>
                            <td>{{ round($req->rewound_length_a,2) }}</td>
                            <td>{{ $req->pasbin}}</td>
                            <td>{{ $req->skeda_item_type}}</td>
                            <td>{{ $req->skeda}}</td>
                            <td>{{ $req->skeda_status}}</td>
                            <td>{{ $req->rewound_roll_unit_of_measure}}</td>
                            <td>{{ $req->position}}</td>
                            <td>{{ $req->priority}}</td>
                            <td>{{ $req->comment_office}}</td>
                            <td>{{ $req->comment_operator}}</td>
                            <td>{{ $req->call_shift_manager}}</td>
                            <td>{{ $req->rewinding_method}}</td>
                            <td>{{ $req->created_at}}</td>
                            <td>{{ $req->updated_at}}</td>

                            <td><span style="background-color: green;">#</span></td>

                            <td>{{ $req->status}}</td>
                            <td>{{ $req->location}}</td>
                            <td>{{ $req->device}}</td>
                            <td>{{ $req->active}}</td>
                            <td>{{ $req->operator1}}</td>
                            <td>{{ $req->operator2}}</td>
 
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
