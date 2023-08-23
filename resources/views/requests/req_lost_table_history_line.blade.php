@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><b><big>LostBB</big></b> table requests from K-PREP</div>

                
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
                            <!-- <th>Id</th> -->
                            <th data-sortable="true"><b>SKU</b></th>
                            <th><b>Qty</b></th>
                            <th data-sortable="true">Module</th>
                            <th data-sortable="true">Bagno</th>
                            <th data-sortable="true">Komentar</th>
                            <th data-sortable="true">Status</th>
                            <th data-sortable="true">Created</th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                            <td><b>{{ $req->sku}}</b></td>
                            <td>{{ $req->qty }}</td>
                            <td>{{ $req->module }}</td>
                            <td>{{ $req->bagno }}</td>
                            <td>{{ $req->comment }}</td>
                            <td><b>{{ $req->status }}</b></td>
                            <td>{{ $req->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
