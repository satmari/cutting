@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Consumption SAP</div>
              
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
                        <tr>
                            
                            <th><b>Gbin</b></th>
                            <th><b>Real Cons</b></th>
                                                       
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                       <tr>
                            
                            <td>{{ $req->g_bin }}</td>
                            <td>{{ round($req->cons_real,2)  }}</td>

                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
