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
