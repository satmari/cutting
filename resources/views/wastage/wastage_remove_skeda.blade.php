@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row vertical-center-row">
        <div class="text-center col-md-5 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Remove wastage bags by skeda</div>

               
                <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>
                <table class="table table-striped table-bordered" id="sort" 
                data-show-export="true"
                data-export-types="['excel']"
                >
                <!--
                data-show-export="true"
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
                           {{-- <th>id</th> --}}
                           
                           <th>Skeda</th>
                           <th></th>
                           <th></th>

                        </tr>
                    </thead>
                    <tbody class="searchable">
                    
                    @foreach ($data as $d)
                        
                        <tr>
                            {{-- <td>{{ $d->id }}</td> --}}

                            <td>{{ $d->skeda }}</td>
                            
                            <td>
                            @if(Auth::check())
                                <a href="{{ url('wastage_remove_skeda/'.$d->skeda) }}" class="btn btn-danger btn-xs center-block">Remove all bags</a>
                            @endif
                            </td>
                            <td>
                            @if(Auth::check())
                                <a href="{{ url('wastage_remove_skeda_partialy/'.$d->skeda) }}" class="btn btn-warning btn-xs center-block">Remove bags partialy</a>
                            @endif
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