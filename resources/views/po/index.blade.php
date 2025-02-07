@extends('app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Komesa table</div>

                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/new_po')}}" class="btn btn-info center-block">Create new komesa</a>
                    </div>
                </div>

                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered tableFixHead" id="sort" 
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
                            
                            <th><b>Komesa</b></th>
                            <th><b>Status</b></th>
                            <th></th>
                            <th></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr>
                           
                            <td>{{ $req->po}}</td>
                            <td>{{ $req->status }}</td>
                            
                            <td><a href="{{url('/edit_status/'.$req->id)}}" class="btn btn-info btn-xs ">Change status</a></td>
                            <td><a href="{{url('/edit_po/'.$req->id)}}" class="btn btn-info btn-xs ">Change name</a></td>
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
