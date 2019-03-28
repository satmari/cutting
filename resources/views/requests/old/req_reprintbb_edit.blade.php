@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">Request for reprint BlueBox</div>
                <div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>

                <div class="panel-body">

                    {{-- 
                    <div class="alert alert-warning">
                      <strong>Warning!</strong> Application will suggest released PO from Navision, but you don't have any limitation on po name.
                    </div>
                    --}}
                
                {!! Form::open(['method'=>'POST', 'url'=>'/req_reprintbbconfirm']) !!}

                        <div class="panel-body">
                        <p>Komesa: <span style="color:red;">*</span></p>
                            {!! Form::text('po', null, ['id' => 'po','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        <div class="panel-body">
                            <p>BlueBox: <span style="color:red;">*</span> <big>(Samo poslednja 3 karaktera)</big></p>
                            {!! Form::text('bb', null, ['class' => 'form-control']) !!}
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
