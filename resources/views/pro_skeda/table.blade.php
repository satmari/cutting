@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Pro Skeda table</div>
              
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
                        <tr>
                            <th>Id</th>
                            
                            <th><b>Pro_id</b></th>
                            <th><b>Pro</b></th>
                            <th><b>Skeda</b></th>

                            <th><b>Style</b></th>
                            <th><b>Size</b></th>
                            <th><b>SKU</b></th>

                            <th><b>Padprint item</b></th>
                            <th><b>Padprint color</b></th>

                            <th><b>Bom cons per pcs</b></th>
                            <th><b>Bom cons per pcs a</b></th>
                            <th><b>Extra mat a</b></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td>{{ $req->id}}</td>
                            <td><b>{{ $req->pro_id}}</b></td>
                            <td><b>{{ $req->pro}}</b></td>
                            <td><b>{{ $req->skeda}}</b></td>
                        
                            <td>{{ $req->style }}</td>
                            <td>{{ $req->size }}</td>
                            <td>{{ $req->sku }}</td>

                            <td>{{ $req->padprint_item }}</td>
                            <td>{{ $req->padprint_color }}</td>

                            <td>{{ round($req->bom_cons_per_pcs,3) }}</td>
                            <td>{{ round($req->bom_cons_per_pcs_a,3) }}</td>
                            <td>{{ round($req->extra_mat_a,3) }}</td>
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
