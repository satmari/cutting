@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Move SAP bin to container <p>2. Scan Container</p></div>
                
                @if(isset($msg))
                    <div class="alert alert-danger" role="alert">
                        {{ $msg }}
                    </div>
                @endif

                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/move_sapbin_container_post_1']) !!}

                        
                        <div class="panel-body">
                        <p>Container: <span style="color:red;">*</span></p>
                            {!! Form::text('container', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        {!! Form::hidden('sap_bin', $sap_bin, ['class' => 'form-control']) !!}
                        
                        {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
