@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Cut Rewounded Paspul roll: <big><b>{{ $paspul_rewound_roll }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_pco1_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_rewound_roll', $paspul_rewound_roll, ['class' => 'form-control']) !!}

                        {!! Form::hidden('paspul_roll', $paspul_roll, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_roll_id', $paspul_roll_id, ['class' => 'form-control']) !!}
                        
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
                        <p>Koturi <b><i>(unesite broj iskrojenih kotura)</i></b>: </p>
                            {!! Form::number('kotur_partialy', "", ['class' => 'form-control', 'step'=>'1']) !!}
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
