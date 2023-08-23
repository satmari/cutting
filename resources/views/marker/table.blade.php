@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Marker header table</div>
              
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <table class="table table-striped table-bordered" id="sort" 
                
                
                >
                <!--
                data-export-types="['excel']"
                data-show-export="true"
                data-pagination="true"
                data-height="300"
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
                            <th>Id</th>
                            <th><b>Marker name</b></th>
                            <th><b>Width</b></th>
                            <th><b>Lenght</b></th>
                            <!-- <th><b>Type</b></th>  -->
                            <!-- <th><b>Code</b></th> -->
                            <!-- <th><b>F Code</b></th> -->
                            <!-- <th><b>Constraint</b></th> -->
                            <!-- <th><b>Spacing</b></th> -->
                            <!-- <th><b>Top</b></th> -->
                            <!-- <th><b>Bottom</b></th> -->
                            <!-- <th><b>Right</b></th> -->
                            <!-- <th><b>Left</b></th> -->
                            <!-- <th><b>Proc. date</b></th> -->
                            <!-- <th><b>Cut perimeter</b></th> -->
                            <!-- <th><b>Perimeter</b></th> -->
                            <!-- <th><b>Avg. Cons</b></th> -->
                            <!-- <th><b>Lines</b></th> -->
                            <!-- <th><b>Curves</b></th> -->
                            <!-- <th><b>Areas</b></th> -->
                            <!-- <th><b>Angles</b></th> -->
                            <!-- <th><b>Notches</b></th> -->
                            <!-- <th><b>Tot. Pcs</b></th> -->
                            <!-- <th><b>Key</b></th> -->
                            <!-- <th><b>Min Len</b></th> -->
                            <th><b>Eff</b></th>
                            <th><b>Status</b></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            
                        </tr>
                    </thead>
                    <tbody class="searchable">
                        @foreach ($data as $req)
                            <tr>
                                <td>{{ $req->id}}</td>
                                <td>{{ $req->marker_name}}</td>
                                <td>{{ round($req->marker_width,3) }}</td>
                                <td>{{ round($req->marker_length,3)  }}</td>
                                <!-- <td>{{-- round($req->cutting_perimeter,2)  --}}</td> -->
                                <!-- <td>{{-- round($req->perimeter,2)  --}}</td> -->
                                <!-- <td>{{-- round($req->average_consumption,2)  --}}</td> -->
                                <td>{{ round($req->efficiency,2) }}</td>
                                <td>{{ $req->status }}</td>
                                <td><a href="{{ url('marker_edit/'.$req->id) }}" class="btn btn-warning btn-xs center-block">Edit</a></td>
                                <td><a href="{{ url('marker_details/'.$req->id) }}" class="btn btn-info btn-xs center-block">Details</a></td>
                                <td><a href="{{ url('marker_delete/'.$req->id) }}" class="btn btn-danger btn-xs center-block">Delete</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>
@endsection



