@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-success">
                <div class="panel-heading"><b><big>Pad print</big></b> table requests from lines</div>


                @if(isset($h))
                    <p>
                        <a href="{{url('/req_extrabb_table/')}}" class="btn btn-info btn-xs ">ExtraBB requests</a>
                        <a href="{{url('/req_reprintbb_table/')}}" class="btn btn-primary btn-xs ">ReprintBB requests</a>
                        <a href="{{url('/req_cartonbox_table/')}}" class="btn btn-warning btn-xs ">Cartonbox requests</a>
                        <a href="{{url('/req_padprint_table/')}}" class="btn btn-success btn-xs ">Padprint requests</a>
                        <a href="{{url('/req_cut_part_table/')}}" class="btn btn-default btn-xs ">Cut part requests</a>
                        <a href="{{url('/req_lost_table/')}}" class="btn btn-default btn-xs ">LostBB requests</a>
                    </p>
                    History of last 30 days
                    <p><a href="{{url('/req_padprint_table/')}}" class="btn btn-xs ">Back</a></p>
                @endif
                @if(!isset($h))
                    <p>
                        <a href="{{url('/req_extrabb_table/')}}" class="btn btn-info btn-xs ">ExtraBB requests</a>
                        <a href="{{url('/req_reprintbb_table/')}}" class="btn btn-primary btn-xs ">ReprintBB requests</a>
                        <a href="{{url('/req_cartonbox_table/')}}" class="btn btn-warning btn-xs ">Cartonbox requests</a>
                        <a href="{{url('/req_padprint_table/')}}" class="btn btn-success btn-xs ">Padprint requests</a>
                        <a href="{{url('/req_cut_part_table/')}}" class="btn btn-default btn-xs ">Cut part requests</a>
                        <a href="{{url('/req_lost_table/')}}" class="btn btn-default btn-xs ">LostBB requests</a>
                    </p>
                    <a href="{{url('/req_padprint_table_history/')}}" class="btn btn-danger btn-xs ">History</a>
                @endif
                
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
                            
                            
                            <th data-sortable="true"><b>Line</b></th>
                            <th>Leader</th>
                            <th data-sortable="true">Style</th>
                            <th><b>Qty</b></th>
                            <th data-sortable="true">Status</th>
                            <th data-sortable="true">Created</th>
                            <th data-sortable="true">Updated</th>
                            <th>Comment</th>
                            <th></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td><b>{{ $req->module }}</b></td>
                            <td>{{ $req->leader }}</td>
                            <td>{{ $req->style }}</td>
                            <td><b>{{ $req->qty }}</b></td>
                            <td>{{ $req->status }}</td>
                            <td>{{ substr($req->created_at,0,16) }}</td>
                            <td>{{ substr($req->updated_at,0,16) }}</td>
                            <td>{{ $req->comment }}</td>
                            
                            @if(!isset($h))
                                <td><a href="{{url('/req_padprint_status/'.$req->id)}}" class="btn btn-danger btn-xs ">Change status</a></td>    
                            @endif
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
