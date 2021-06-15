@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-warning">
                <div class="panel-heading">Request for new Cartonbox</div>
                <div class="panel-heading"><span>Majstorica: <b>{{$leader}}</b></span></div>

                <div class="panel-body">

                    {{-- 
                    <div class="alert alert-warning">
                      <strong>Warning!</strong> Application will suggest released PO from Navision, but you don't have any limitation on po name.
                    </div>
                    --}}
                
                {!! Form::open(['method'=>'POST', 'url'=>'/req_cartonboxconfirm']) !!}

                        <div class="panel-body">
                        <p>Komesa: <span style="color:red;">*</span></p>
                            {!! Form::text('po', null, ['id' => 'po','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        <div class="panel-body">
                            <p>Size/Velicina: <span style="color:red;">*</span></p>
                            {!! Form::select('size', array(''=>'','XS'=>'XS','S'=>'S','M'=>'M','L'=>'L','XL'=>'XL','XXL'=>'XXL','M/L'=>'M/L','S/M'=>'S/M','XS/S'=>'XS/S','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','3-4'=>'3-4','5-6'=>'5-6','7-8'=>'7-8','9-10'=>'9-10','11-12'=>'11-12','LSHO'=>'LSHO','SSHO'=>'SSHO','MSHO'=>'MSHO','XSSHO'=>'XSSHO','XLSHO'=>'XLSHO','TU'=>'TU','1/2'=>'1/2','3/4'=>'3/4'), '', array('class' => 'form-control')) !!} 
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
