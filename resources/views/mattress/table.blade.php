@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Mattress table</div>
              
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered" id="sort" 
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
                            <th class="rotate"><div><span>mattress</div></span></th>
                            <th class="rotate"><div><span>g_bin</div></span></th>
                            <th class="rotate"><div><span>material</div></span></th>
                            <th class="rotate"><div><span>dye_lot</div></span></th>
                            <th class="rotate"><div><span>color_desc</div></span></th>
                            <th class="rotate"><div><span>width_theor_usable</div></span></th>
                            <th class="rotate"><div><span>skeda</div></span></th>
                            <th class="rotate"><div><span>skeda_item_type</div></span></th>
                            <th class="rotate"><div><span>skeda_status</div></span></th>
                            <th class="rotate"><div><span>spreading_method</div></span></th>
                            <th class="rotate"><div><span>created_at</div></span></th>
                            <th class="rotate"><div><span>updated_at</div></span></th>
                            <th class="rotate"><div><span>|</div></span></th>
                            <th class="rotate"><div><span>layers</div></span></th>
                            <th class="rotate"><div><span>layers_a</div></span></th>
                            <th class="rotate"><div><span>length_mattress</div></span></th>
                            <th class="rotate"><div><span>cons_planned</div></span></th>
                            <th class="rotate"><div><span>extra</div></span></th>
                            <th class="rotate"><div><span>pcs_bundle</div></span></th>
                            <th class="rotate"><div><span>layers_partial</div></span></th>
                            <th class="rotate"><div><span>position</div></span></th>
                            <th class="rotate"><div><span>priority</div></span></th>
                            <th class="rotate"><div><span>call_shift_manager</div></span></th>
                            <th class="rotate"><div><span>test_marker</div></span></th>
                            <th class="rotate"><div><span>tpp_mat_keep_wastage</div></span></th>
                            <th class="rotate"><div><span>printed_marker</div></span></th>
                            <th class="rotate"><div><span>mattress_packed</div></span></th>
                            <th class="rotate"><div><span>all_pro_for_main_plant</div></span></th>
                            <th class="rotate"><div><span>bottom_paper</div></span></th>
                            <th class="rotate"><div><span>layers_a_reasons</div></span></th>
                            <th class="rotate"><div><span>comment_office</div></span></th>
                            <th class="rotate"><div><span>comment_operator</div></span></th>
                            <th class="rotate"><div><span>minimattress_code</div></span></th>
                            <th class="rotate"><div><span>|</div></span></th>
                            <th class="rotate"><div><span>marker_id</div></span></th>
                            <th class="rotate"><div><span>marker_name</div></span></th>
                            <th class="rotate"><div><span>marker_name_orig</div></span></th>
                            <th class="rotate"><div><span>marker_length</div></span></th>
                            <th class="rotate"><div><span>marker_width</div></span></th>
                            <th class="rotate"><div><span>min_length</div></span></th>
                            <th class="rotate"><div><span>|</div></span></th>
                            <th class="rotate"><div><span>status</div></span></th>
                            <th class="rotate"><div><span>location</div></span></th>
                            <th class="rotate"><div><span>device</div></span></th>
                            <th class="rotate"><div><span>active</div></span></th>
                            <th class="rotate"><div><span>operator1</div></span></th>
                            <th class="rotate"><div><span>operator2</div></span></th>
                            <th class="rotate"><div><span>|</div></span></th>
                            <th class="rotate"><div><span>style_size</div></span></th>
                            <th class="rotate"><div><span>pro_id</div></span></th>
                            <th class="rotate"><div><span>pcs_on_layer</div></span></th>
                            <th class="rotate"><div><span>layers</div></span></th>
                            <th class="rotate"><div><span>layers_cut</div></span></th>
                            
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr class="test">
                           
                            <td>{{ $req->id}}</td>
                            <td>{{ $req->mattress}}</td>
                            <td>{{ $req->g_bin}}</td>
                            <td>{{ $req->material}}</td>
                            <td>{{ $req->dye_lot}}</td>
                            <td>{{ $req->color_desc}}</td>
                            <td>{{ round($req->width_theor_usable,3) }}</td>
                            <td>{{ $req->skeda}}</td>
                            <td>{{ $req->skeda_item_type}}</td>
                            <td>{{ $req->skeda_status}}</td>
                            <td>{{ $req->spreading_method}}</td>
                            <td>{{ $req->created_at}}</td>
                            <td>{{ $req->updated_at}}</td>
                            <td><span style="background-color: aqua;">#</span></td>
                            <td>{{ $req->layers}}</td>
                            <td>{{ $req->layers_a}}</td>
                            <td>{{ round($req->length_mattress,3)}}</td>
                            <td>{{ round($req->cons_planned,3) }}</td>
                            <td>{{ $req->extra}}</td>
                            <td>{{ $req->pcs_bundle}}</td>
                            <td>{{ $req->layers_partial}}</td>
                            <td>{{ $req->position}}</td>
                            <td>{{ $req->priority}}</td>
                            <td>{{ $req->call_shift_manager}}</td>
                            <td>{{ $req->test_marker}}</td>
                            <td>{{ $req->tpp_mat_keep_wastage}}</td>
                            <td>{{ $req->printed_marker}}</td>
                            <td>{{ $req->mattress_packed}}</td>
                            <td>{{ $req->all_pro_for_main_plant}}</td>
                            <td>{{ $req->bottom_paper}}</td>
                            <td>{{ $req->layers_a_reasons}}</td>
                            <td>{{ $req->comment_office}}</td>
                            <td>{{ $req->comment_operator}}</td>
                            <td>{{ $req->minimattress_code}}</td>
                            <td><span style="background-color: red;">#</span></td>
                            <td>{{ $req->marker_id}}</td>
                            <td>{{ $req->marker_name}}</td>
                            <td>{{ $req->marker_name_orig}}</td>
                            <td>{{ round($req->marker_length,3)}}</td>
                            <td>{{ $req->marker_width}}</td>
                            <td>{{ $req->min_length}}</td>
                            <td><span style="background-color: green;">#</span></td>
                            <td>{{ $req->status}}</td>
                            <td>{{ $req->location}}</td>
                            <td>{{ $req->device}}</td>
                            <td>{{ $req->active}}</td>
                            <td>{{ $req->operator1}}</td>
                            <td>{{ $req->operator2}}</td>
                            <td><span style="background-color: orange;">#</span></td>
                            <td>{{ $req->style_size}}</td>
                            <td>{{ $req->pro_id}}</td>
                            <td>{{ $req->pro_pcs_layer}}</td>
                            <td>{{ $req->pro_pcs_planned}}</td>
                            <td>{{ $req->pro_pcs_actual}}</td>
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
