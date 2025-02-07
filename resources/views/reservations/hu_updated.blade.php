@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reservations</div>

                <div class="panel-body">
                    <div>Number of updated rolls: <big><b>{{ $update_count }}</b></big></div>    
                </div>
                <div class="panel-body">
                    <div>Number of added rolls: <big><b>{{ $add_count }}</b></big></div>    
                </div>
                <div class="panel-body">
                    <div>Number of "consumed" rolls: <big><b>{{ $consumed_count }}</b></big></div>    
                </div>
                <div class="panel-body">
                    <div>Number of rolls automaticaly reserved by Father HU: <big><b>{{ $reserved_by_father }}</b></big></div>    
                </div>

                <div class="panel-body">
                    <div>Unreserved rolls (closed PO): <big><b>{{ $unreserved_hu }}</b></big></div>    
                </div>
                <div class="panel-body">
                    <div>Unreserved meters (closed PO): <big><b>{{ $unreserved_mt }}</b></big></div>    
                </div>
            </div>

            <div class="panel panel-default">
               <div class="panel-body">
                    <div><a href="{{url('/reservation')}}" class="btn btn-default btn-info">Back to reservations</a></div>    
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
