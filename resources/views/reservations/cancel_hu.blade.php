@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Cancel reservation material by HU</div>

                <div class="panel-body">
                    Item: <b>{{$input_item}}</b>, Variant: <b>{{$input_variant}}</b>, Batch: <b>{{$input_batch}}</b>
                </div>

                {!! Form::open(['method'=>'POST', 'url'=>'/cancel_hu']) !!}
                <meta name="csrf-token" content="{{ csrf_token() }}" />

                {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}

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
                            <th>HU</b></th>
                            <th>PO</th>
                            <th>Reserved qty</th>
                            
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                            <td><b>{{ $req->hu }}</b></td>
                            <td><big><b>{{ $req->res_po }}</b></big></td>
                            <td><span class="amount"> {{ floatval($req->balance) }}</span></td>
                            
                            <td>
                                    <input type="checkbox" id="box" class="btn box" name='checked[]' value="{{ $req->hu }}">
                                    <input name="hidden[]" type='hidden' value="{{ $req->hu }}"> 
                            </td>
                            
                        </tr>
                    @endforeach
                   
                    
                    </tbody>   

                </table>    
                    
                    <div class="panel-body">
                        Total quantity: <b><div id="total"></div></b>
                    </div>
                    

                    <div class="panel-body">
                        {!! Form::submit('Confirm', ['class' => 'btn btn-warning center-block']) !!}
                    </div>

                    @include('errors.list')
                    {!! Form::close() !!}          
               
            </div>
        </div>
    </div>
</div>
@endsection


