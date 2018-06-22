@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Reserve material by HU</div>

                <div class="panel-body">
                    Item: <b>{{$input_item}}</b>, Variant: <b>{{$input_variant}}</b>, Batch: <b>{{$input_batch}}</b>
                </div>

                {!! Form::open(['method'=>'POST', 'url'=>'/reserv_by_hu_insert_po']) !!}
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
                            <!-- <th>Id</th> -->
                            <th><b>Hu</b></th>
                            {{--<th><b>Father Hu</b></th>--}}
                            {{--<th><b>Item</b></th>
                            <th><b>Variant</b></th>
                            <th><b>Batch</b></th>--}}
                            <th><b>Balance</b></th>
                            {{--<th><b>Balance</b></th>--}}
                            {{--<th><b>Status</b></th>--}}
                            <th><b>Purchase Invoice</b></th>
                            <th><b>Bin</b></th>
                            <th><b>Location</b></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                            
                            <td>{{ $req->hu }}</td>
                            {{--<td>{{ $req->father_hu }}</td>--}}
                            {{--<td>{{ $req->item }}</td>
                            <td>{{ $req->variant }}</td>
                            <td>{{ $req->batch }}</td>--}}
                            
                            <td><span class="amount"> {{floatval($req->balance) }}</span></td>
                            {{--<td>{{ $req->status }}</td>--}}
                            <td>{{ $req->document }}</td>
                            <td>{{ $req->bin }}</td>
                            <td>{{ $req->location }}</td>
                            <td>
                                    <input type="checkbox" id="box" class="btn box" name='checked[]' value="{{ $req->id }}">
                                    <input name="hidden[]" type='hidden' value="{{ $req->id }}"> 
                            </td>
                            
                        </tr>
                    @endforeach
                    
                    </tbody>   

                </table>    

                    <div class="panel-body">
                        Total: <b><div id="total"></div></b>
                    </div>

                    <div class="panel-body">
                        {!! Form::submit('Confirm', ['class' => 'btn btn-success center-block']) !!}
                    </div>

                    @include('errors.list')
                    {!! Form::close() !!}          
               
            </div>
        </div>
    </div>
</div>
@endsection


