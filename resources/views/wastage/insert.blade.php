@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading"><b><big>For scaned bin {{ $sap_bin }} we found flowing lines</big></b></div>

                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                {!! Form::open(['method'=>'POST', 'url'=>'/req_wastage_wh_insert']) !!}    

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
                            
                            <th><b>Bag no</b></th>
                            <th><b>SAP bin</b></th>
                            <th>Skeda</th>
                            
                            <th>Weight</th>
                            <th>Coment</th>
                            <th></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">

                    
                    
                        @foreach ($data as $req)
                            <tr>
                               
                                <td><b>{{ $req->no }}</b></td>
                                <td><b>{{ $req->sap_bin }}</b></td>
                                <td><b>{{ $req->skeda }}</b></td>
                                
                                <td>{!! Form::input('number', 'qty[]', round($req->weight,2), ['class' => 'form-control', 'step' => '0.01']) !!}</td>
                                <td>{!! Form::text('coment[]', $req->coment, ['class' => 'form-control']) !!}</td>

                                <td>{!! Form::hidden('id_stari[]', $req->id, ['class' => 'form-control']) !!}</td>

                            </tr>
                        @endforeach
                        @include('errors.list')

                    {!! Form::close() !!}

                    </tbody> 
                </table>
                <br>
                {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
