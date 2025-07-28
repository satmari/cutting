@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Paspul request from the line</b></div>

                
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
                            <th data-sortable="true"><b>Komesa</b></th>
                            <th><b>BB</b></th>
                            <th data-sortable="true"><b>Style</b></th>
                            <th><b>Color</b></th>
                            <th><b>Size</b></th>
                            <th>Bagno</th>
                            <th data-sortable="true"><big>Line</big></th>
                            <th><b>Qty</b></th>
                            <th><b>Qty REQUESTED</b></th>
                            <th><b>Cut part</b></th>
                            <th data-sortable="true">Status</th>
                            <th>Line Comment</th>
                            <th data-sortable="true">Created</th>
                            <th data-sortable="true">Updated</th>

                            <th></th>
                            
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td><b>{{ $req->po}}</b></td>
                            <td><b>{{ substr($req->bb, -3) }}</b></td>
                            <td><b>{{ $req->style}}</b></td>
                            <td><b>{{ $req->color}}</b></td>
                            <td><b>{{ $req->size }}</b></td>
                            <td>{{ $req->bagno }}</td>
                            <td>{{ $req->module }}</td>
                            <td><b>{{ $req->qty }}</b></td>
                            <td><b>{{ $req->req_qty }}</b></td>
                            <td><b>{{ $req->part }}</b></td>
                            <td>{{ $req->status }}</td>
                            <td>{{ $req->comment }}</td>
                            
                            <td>{{ substr($req->created_at,0,16) }}</td>
                            <td>{{ substr($req->updated_at,0,16) }}</td>
                            
                            @if ($req->status == 'Pending')
                                <td><a href="{{url('/req_paspul_complete/'.$req->id)}}" class="btn btn-danger btn-xs ">Set as complete</a></td>
                            @else
                                <td></td>
                            @endif
                            
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
