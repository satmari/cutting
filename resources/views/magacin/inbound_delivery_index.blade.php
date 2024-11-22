@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Options:</div>
                
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/inbound_delivery_import')}}" class="btn btn-danger center-block">
                            Import Inbound Delivery</a>
                    </div>
                </div>
                <hr>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/inbound_delivery_table_wh')}}" class="btn btn-success center-block">
                            Inbound Delivery Table (last 60 days)</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection