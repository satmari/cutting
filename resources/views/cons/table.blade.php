@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Consumption table</div>
                {{-- 
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/add_po')}}" class="btn btn-info center-block">Add Po</a>
                    </div>
                </div>
                --}}

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
                            
                            <th><b>PO</b></th>
                            <th><b>PO status</b></th>
                            <th><b>To be finished</b></th>
                            <th><b>Flash</b></th>
                            <th><b>Order qty</b></th>
                            <th><b>Main Item</b></th>
                            <th><b>Main Variant</b></th>
                            <th><b>Qty per</b></th>
                            <th><b>Teo cons</b></th>
                            <th><b>Over cons (3%)</b></th>
                            <th><b>Extra Item</b></th>
                            <th><b>Extra Variant</b></th>
                            <th><b>Extra Consumed</b></th>
                            <th><b>Still available to Con</b></th>
                            <th><b>Error</b></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td><b>{{ $req->po}}</b></td>
                            <td>{{ $req->status }}</td>
                            <td>{{ $req->to_be_finished }}</td>
                            <td>{{ $req->cut_prod_line }}</td>
                            <td>{{ $req->order_qty }}</td>
                            <td>{{ $req->main_item }}</td>
                            <td>{{ $req->main_variant }}</td>
                            <td>{{ round($req->qty_per,3) }}</td>
                            <td>{{ round($req->teo_cons,1) }}</td>
                            <td>{{ round($req->over_cons,1)}}</td>
                            <td>{{ $req->extra_item }}</td>
                            <td>{{ $req->extra_variant }}</td>                        
                            <td>{{ round($req->extra_consumed,1) }}</td>                        
                            <td><b>{{ round($req->over_cons,1)-round($req->extra_consumed,1) }}</b></td>
                            <td>{{ $req->error }}</td>                        
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
