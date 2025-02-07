@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Reservation table by Item, Variant and Batch</div>
     

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
                            {{--<th><b>Balance</b></th>--}}
                            {{--<th><b>Count of rolls</b></th>--}}
                            <th><b>Free Qty</b></th>
                            <th><b>Free rolls</b></th>
                            <th><b>Remaining Reserved Qty</b></th>
                            <th><b>Remaining Reserved rolls</b></th>
                            <th><b>Originally Reserved Qty</b></th>
                            <th><b>Originally Reserved rolls</b></th>
                            <th></th>

                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td>{{ $req->item }}</td>
                            <td>{{ $req->variant }}</td>
                            <td>{{ $req->batch }}</td>
                            {{--<td>{{ floatval(round($req->bal,2)) }}</td>--}}
                            {{--<td>{{ str_replace(".", ",", floatval($req->bal)) }}</td>--}}
                            {{--<td>{{ $req->coun }}</td>--}}
                            <td>{{ str_replace(".", ",", floatval($req->reserv_not)) }}</td>
                            <td>{{ $req->coun_not }}</td>
                            <td>{{ str_replace(".", ",", floatval($req->reserv_yes)) }}</td>
                            <td>{{ $req->coun_yes }}</td>
                            <td>{{ str_replace(".", ",", floatval($req->reserv_all)) }}</td>
                            <td>{{ $req->coun_all }}</td>

                            <td>

                                 {!! Form::open(['method'=>'POST', 'url'=>'/reserv_input']) !!}

                                    {!! Form::hidden('item', $req->item) !!}
                                    {!! Form::hidden('variant', $req->variant) !!}
                                    {!! Form::hidden('batch', $req->batch) !!}

                                    {!! Form::submit('Edit', ['class' => 'btn-xs  btn-success']) !!}

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
