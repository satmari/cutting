@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading">Stampanje nalepnice
                    </div>
                
               @if (isset($msge))
                    <small><i>&nbsp &nbsp &nbsp <span style="color:red"><b>{{ $msge }}</b></span></i></small>
                    <audio autoplay="true" style="display:none;">
                        <!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
                    </audio>
                @endif
                @if (isset($msgs))
                    <small><i>&nbsp &nbsp &nbsp <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
                    <audio autoplay="true" style="display:none;">
                        <!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
                    </audio>
                @endif

                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/print_paspul_label1_post']) !!}
                    
                    {!! Form::hidden('id', $id) !!}
                    {!! Form::hidden('kotur_length', $kotur_length) !!}
                    {!! Form::hidden('skeda', $skeda) !!}
                    {!! Form::hidden('paspul_type', $paspul_type) !!}
                    {!! Form::hidden('mtr_per_pcs', $mtr_per_pcs) !!}
                    {!! Form::hidden('pcs_kotur', $pcs_kotur) !!}
                    
                    <div class="panel-body">
                    <p>Izaberi stampac:</p>
                        @if(Auth::user()->level() == 23)
                            {!! Form::select('printer', array('Krojacnica_paspul'=>'Krojacnica paspul','Kikinda'=>'Kikinda','Senta'=>'Senta'), 'Krojacnica_paspul', array('class' => 'form-control')) !!} 
                        @endif

                        @if(Auth::user()->level() == 24)
                            {!! Form::select('printer', array('Krojacnica_paspul'=>'Krojacnica_paspul','Kikinda'=>'Kikinda','Senta'=>'Senta'), 'Kikinda', array('class' => 'form-control')) !!} 
                        @endif

                        @if(Auth::user()->level() == 25)
                            {!! Form::select('printer', array('Krojacnica_paspul'=>'Krojacnica_paspul','Kikinda'=>'Kikinda','Senta'=>'Senta'), 'Senta', array('class' => 'form-control')) !!} 
                        @endif
                    </div>

                    <div class="panel-body">
                    <p>Broj paspul kotura: <span style="color:green;"></span></p>
                        {!! Form::input('number', 'kotur_qty', '', ['class' => 'form-control', 'step'=>'1' , 'autofocus' => 'autofocus']) !!}
                    </div>
                  
                    <hr>
                    <div class="panel-body">
                    <p>Broj nalepnica za stampanje: <span style="color:green;"></span></p>
                        {!! Form::input('number', 'qty', 1, ['class' => 'form-control', 'step'=>'1']) !!}
                    </div>
                    
                    <br>
                    
                    <div class="panel-body">
                    <p>Consumption Mtr per PCS: <b>{{ $mtr_per_pcs }}</b></p>
                    <p>PCS per kotur: <b>{{ round($pcs_kotur,2) }}</b></p>
                    <!-- <p>FG Qty: {{ $fg_qty  }}</p> -->
                    </div>
                    

                     @if (isset($msge2))
                        <div class="panel-body"><i>&nbsp &nbsp &nbsp <span style="color:red"><b>{{ $msge2 }}</b></span></i></div>
                    
                    {!! Form::close() !!}
                    {!! Form::open(['method'=>'POST', 'url'=>'/search_u_cons_post']) !!}

                    {!! Form::hidden('skeda', $skeda) !!}
                    {!! Form::hidden('paspul_type', $paspul_type) !!}

                    {!! Form::submit('Ovde mozete dodati consumption', ['class' => 'btn  btn-danger center-block']) !!}
                    {!! Form::close() !!}                            

                    @else
                        {!! Form::submit('Print', ['class' => 'btn  btn-info center-block']) !!}
                    @endif

                    @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
