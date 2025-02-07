@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel">
                <div class="panel-heading"><b><big>Inbound delivery table</big> (last 60 days)</b>

                    <br>
                    <br>
                    <a href="{{url('/inbound_delivery_import')}}" class="btn btn-danger cent er-block btn-xs">
                            Import Inbound Delivery</a>
                    <div class="panel-body">
                       <p>
                           <a href="{{url('leftover_table')}}" class="btn btn-info center-blo ck"><b>Leftover Queue</b></a>
                       </p>
                    </div>
                </div>

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
                            
                            <th><b>Document no</b></th>
                            <th><b>Posting date</b></th>
                            <th>Material</th>
                            <th>Bagno</th>
                            <th>Received Qty</th>
                            <th>Pref Origin</th>
                            <th>Created at</th>

                            
                            <!-- <th></th> -->
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">

                    
                    
                        @foreach ($data as $req)
                            <tr>
                               
                                <td><b>{{ $req->document_no }}</b></td>
                                <td><b>{{ $req->posting_date }}</b></td>
                                <td>{{ $req->material }}</td>
                                <td>{{ $req->bagno }}</td>
                                <td>{{ round($req->qty_received_m,1) }}</td>
                                <td>{{ $req->preforigin }}</td>
                                <td>{{ $req->created_at }}</td>
                               

                            </tr>
                        @endforeach
                        @include('errors.list')

                    {!! Form::close() !!}

                    </tbody> 
                </table>
                
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
