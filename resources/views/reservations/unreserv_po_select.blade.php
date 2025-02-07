@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">UnReservation table by Po: <big><b>{{ $input_po }}</b></big></div>
     

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
                            
                            <th><b>Item</b></th>
                            <th><b>Variant</b></th>
                            <th><b>Batch</b></th>
                            <th><b>Reserved Qty</b></th>
                            <th><b>Reserved rolls</b></th>
                            <th></th>

                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td>{{ $req->item }}</td>
                            <td>{{ $req->variant }}</td>
                            <td>{{ $req->batch }}</td>
                           
                            <td>{{ str_replace(".", ",", floatval($req->bal)) }}</td>
                            <td>{{ str_replace(".", ",", floatval($req->coun)) }}</td>
                            
                            <td>

                                 {!! Form::open(['method'=>'POST', 'url'=>'/unreserv_po_confirm']) !!}

                                    {!! Form::hidden('item', $req->item) !!}
                                    {!! Form::hidden('variant', $req->variant) !!}
                                    {!! Form::hidden('batch', $req->batch) !!}
                                    {!! Form::hidden('po', $input_po) !!}

                                    {!! Form::submit('Unreserve', ['class' => 'btn-xs  btn-success']) !!}

                                    @include('errors.list')
                                {!! Form::close() !!}


                            </td>
                            
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection