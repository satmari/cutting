@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Move SAP bin to container <p>1. Scan SAP bin</p></div>
                
                @if(isset($msg))
                    <div class="alert alert-danger" role="alert">
                        {{ $msg }}
                    </div>
                @endif
                
                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/move_sapbin_container_post']) !!}

                        
                        <div class="panel-body">
                        <p>Krojni Nalog (SAP bin): <span style="color:red;">*</span></p>
                            {!! Form::text('sap_bin', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        
                        {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
