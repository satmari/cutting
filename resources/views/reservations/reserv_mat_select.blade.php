@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reserve material</div>

                <div class="panel-body">
                    Item: <b>{{$input_item}}</b>, Variant: <b>{{$input_variant}}</b>, Batch: <b>{{$input_batch}}</b>
                </div>
                {{--<div class="panel-body">
                    <div>Total quantity of material: <big><b>{{ $bal }}</b></big> m  , on <big><b>{{ $coun }}</b></big> rolls.</div> 
                </div>--}}
                 <div class="panel-body">
                    <div>Total quantity of <b>available (free) </b>material: <big><b>{{ $reserv_not }}</b></big> m, on <big><b>{{ $coun_not }}</b></big> rolls. <i>Hu with status Open, Not reserved</i></div>
                </div>
                <div class="panel-body">
                    <div>Total quantity of <b>remaining reserved </b>material: <big><b>{{ $reserv_yes }}</b></big> m, on <big><b>{{ $coun_yes }}</b></big> rolls. <i>Hu with status Open, Reserved</i></div>
                </div>
                <div class="panel-body">
                    <div>Total quantity of <b>origianlly reserved </b>material: <big><b>{{ $reserv_all }}</b></big> m, on <big><b>{{ $coun_all }}</b></big> rolls.<i>Hu with status Open+Consumed, Reserved</i></div>
                </div>

                
                @if(isset($reserved_mat[0]->bal))
                    </hr>
                    
                    <div class="panel-body">
                    By production order: </br>

                    @foreach ($reserved_mat as $req)

                    Production order: <b>{{ $req->res_po }}</b>   ,     Quantity: <b>{{ floatval(round($req->bal, 2)) }}</b> m,     Rolls: <b>{{ $req->coun_po }}</b><br /> 

                    @endforeach
                    </div>

                @endif
               
                <hr>
                <div class="panel-body">
                    <!-- <a href="{{ url('/reserv_all_available/'.$input_item.'#'.$input_variant.'#'.$input_batch) }}" class="btn btn-warnin btn-m center-block">Reserve ALL available material</a> -->

                    {!! Form::open(['method'=>'POST', 'url'=>'/reserv_all_available']) !!}
                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        {!! Form::submit('Reserve ALL available material', ['class' => 'btn  btn-danger center-block']) !!}
                        @include('errors.list')
                    {!! Form::close() !!}
                </div>
                <div class="panel-body">
                    <!-- <a href="{{ url('/reserv_by_hu') }}" class="btn btn-warnin btn-m center-block">Reserve material by HU</a> -->

                    {!! Form::open(['method'=>'POST', 'url'=>'/reserv_by_hu']) !!}
                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        {!! Form::submit('Reserve material by HU', ['class' => 'btn  btn-danger center-block']) !!}
                        @include('errors.list')
                    {!! Form::close() !!}
                </div>
                <div class="panel-body">
                    <!-- <a href="{{ url('/reserv_cancel') }}" class="btn btn-warnin btn-m center-block">Cancel all reservations for this material</a> -->

                    {!! Form::open(['method'=>'POST', 'url'=>'/reserv_cancel']) !!}
                        {!! Form::hidden('item', $input_item, ['class' => 'form-control']) !!}
                        {!! Form::hidden('variant', $input_variant, ['class' => 'form-control']) !!}
                        {!! Form::hidden('batch', $input_batch, ['class' => 'form-control']) !!}
                        {!! Form::submit('Cancel reservations for this material', ['class' => 'btn  btn-danger center-block']) !!}
                        @include('errors.list')
                    {!! Form::close() !!}
                </div>
            </br>


            </div>

            <div class="panel panel-default">
               <div class="panel-body">
                    <div><a href="{{url('/reservation')}}" class="btn btn-efault btn-info center-block">Back to reservations</a></div>    
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
