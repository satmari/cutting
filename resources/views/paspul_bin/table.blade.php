@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">Pas_bin table (importing from Excel)</div>
              
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
                        <tr class="te st">
                           <th class="ro tate"><div><span>id</div></span></th>
                           
                           <th class="rot ate"><div><span>skeda</div></span></th>
                           <th class="rot ate"><div><span>pas_bin</div></span></th>
                           <th class="rota te"><div><span>adez_bin</div></span></th>
                           <th class="rotat e"><div><span>created_at</div></span></th>
                           

                        </tr>
                    </thead>
                    <tbody class="searchable">
                    @foreach ($data as $req)
                        <tr class="te st">
                           
                            <td>{{ $req->id}}</td>
                           
                            <td>{{ $req->skeda}}</td>
                            <td>{{ $req->pas_bin}}</td>
                            <td>{{ $req->adez_bin}}</td>
                            <td>{{ $req->created_at}}</td>
                            
                        </tr>
                    @endforeach
                    
                    </tbody>                
            </div>
        </div>
    </div>
</div>
@endsection
