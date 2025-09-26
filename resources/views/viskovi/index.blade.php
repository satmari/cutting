@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Viškovi table</div>
                <p>
                    <a href="{{url('viskovi_add')}}" class="btn btn-success btn ">Add new višak</a>
                </p>
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
                            <!-- <th>Id</th> -->
                            <th><b>Style</b></th>
                            <th><b>Color</b></th>
                            <th><b>Color desc</b></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                        @foreach ($data as $req)
                            <tr>
                                <!-- <td>{{ $req->id}}</td> -->
                                <td><span style='font-size:20px; font-weight:bold;'>{{ $req->style}}</span></td>
                                <td><span style='font-size:20px; font-weight:bold;'>{{ $req->color}}      </span></td>
                                <td><span style='font-size:20px; font-weight:bold;'>{{ $req->color_desc}} </span></td>
                                
                              
                                <td>
                                    <a href="{{ url('viskovi_delete/'.$req->id) }}" 
                                       class="btn btn-danger btn-xs ce nter-block"
                                       onclick="return confirm('Are you sure you want to delete?');">
                                       Delete
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>
@endsection



