@extends('app')

@section('content')
<div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Options:</div>
                
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/wastage_wh_scan')}}" class="btn btn-danger center-block">
                            Insert wastage</a>
                    </div>
                </div>
                <hr>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/move_sapbin_container')}}" class="btn btn-warning center-block">
                            Move SAP bin (all bags) to Container</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/move_container_location')}}" class="btn btn-warning center-block">
                            Move Container (all bags) to Location</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/wastage_remove_skeda')}}" class="btn btn-warning center-block">
                            Remove wastage by skeda</a>
                    </div>
                </div>
                <hr>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/wastage_bin')}}" class="btn btn-info center-block">
                            Container table</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/wastage_location')}}" class="btn btn-info center-block">
                            Location table</a>
                    </div>
                </div>
                <hr>
                <div class="panel-body">
                    <div class="">
                        <a href="{{url('/wastage_table')}}" class="btn btn-success center-block">
                            Wastage Table</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection