@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Hu list, number of rolls <big><b>{{ $count }}</b></big></div>
                
                {{-- 
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/bd_machine_new')}}" class="btn btn-default btn-info">Add new BD Category Machine link</a>
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
                            <th><b>Hu</b></th>
                            <th><b>Father Hu</b></th>
                            <th><b>Item</b></th>
                            <th><b>Variant</b></th>
                            <th><b>Batch</b></th>
                            <th><b>Balance</b></th>
                            {{--<th><b>Balance</b></th>--}}
                            {{--<th><b>Status</b></th>--}}
                            <th><b>Purchase Invoice</b></th>
                            <th><b>Bin</b></th>
                            <th><b>Location</b></th>

                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                            
                            <td>{{ $req->hu }}</td>
                            <td>{{ $req->father_hu }}</td>
                            <td>{{ $req->item }}</td>
                            <td>{{ $req->variant }}</td>
                            <td>{{ $req->batch }}</td>
                            <td>{{ floatval(round($req->balance,2)) }}</td>
                            {{--<td>{{ floatval(str_replace(",", ".", $req->balance)) }}</td>--}}
                            {{--<td>{{ $req->status }}</td>--}}
                            <td>{{ $req->document }}</td>
                            <td>{{ $req->bin }}</td>
                            <td>{{ $req->location }}</td>
                            
                           
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
