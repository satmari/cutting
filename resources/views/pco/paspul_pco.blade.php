@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Cut Paspul roll: <big><b>{{ $paspul_roll }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_pco_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_roll', $paspul_roll, ['class' => 'form-control']) !!}
                        
                            <br>
                            Material: <b>{{ $material }} </b></b><br>
                            Dye lot: <b>{{ $dye_lot}} </b><br>
                            Color desc: <b>{{ $color_desc}} </b><br>
                            Skeda: <b>{{ $skeda}} </b><br>
                            Skeda type: <b>{{ $skeda_item_type}} </b><br>
                            Rewinding method: <b>{{ $rewinding_method}} </b><br>
                            Priority: <b>
                                        @if ($priority == 7)Test
                                        @elseif ($priority == 6)3rd shift
                                        @elseif ($priority == 5)2nd shift
                                        @elseif ($priority == 4)1st shift
                                        @elseif ($priority == 3)Top
                                        @elseif ($priority == 2)Flash
                                        @elseif ($priority == 1)Normal </b><br>
                                        @endif
                            @if($call_shift_manager == 1)
                             Call shift manager: <b>Yes</b> 
                            @endif <br>
                            Comment office: <b>{{ $comment_office}} </b><br>
                            <hr>

                        <div class="panel-body">
                        <p>Koturi: </p>
                            {!! Form::number('kotur_actual', round($kotur_actual,2), ['class' => 'form-control', 'step'=>'0.01']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Comment operator: </p>
                            {!! Form::textarea('comment_operator', $comment_operator , ['class' => 'form-control', 'rows' => 2]) !!}
                        </div>
                        <br>
                        {!! Form::submit('Confirm cut', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
