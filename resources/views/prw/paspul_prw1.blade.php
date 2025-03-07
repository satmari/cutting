@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Paspul rewound roll: <big><b>{{ $paspul_roll }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_prw1_confirm']) !!}

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
                            </b><br>

                            @if($call_shift_manager == 1)
                                Call shift manager: <b>Yes</b> 
                            @endif <br>
                            
                            Comment office: <b>{{ $comment_office}} </b><br>
                            <br>
                            Required rewound length: <b>{{$rewound_length_a}} [m] </b>
                            <br>
                            Already rewounded rolls: <b>{{ $no_of_rewound_rolls }}</b>
                            <br>
                            Still to rewound: <b>{{ $rewound_length_a - $rewound_length_done }} [m] </b>
                            <br>
                            Rewound precentage completed: <b>{{ round($rewound_length_done/$rewound_length_a*100,1) }} % </b>
                            <br><br>
                            <a href="{{url('/finish_rewound/'.$id) }}" class="btn btn-warning btn-xs center-blo ck">Confirm rewound wihtout declaration</a>
                            <hr>

                        <div class="panel-body">
                        <p>Rewound length: </p>
                            {!! Form::number('rewound_length_partialy', '', ['class' => 'form-control', 'step'=>'0.01']) !!}
                        </div>

                        <br>
                        <div class="checkbox">
                            <label style="width: 50%;" type="button" class="btn check btn-default"  data-color="primary">
                                <input type="checkbox" class="btn check" name="last_roll" value="YES">  
                                <input name="hidden[]" type='hidden' value=""> 
                                Last rewound roll
                            </label>
                            <p><i>Info: If Rewound precentage is 100% or more, "Last rewound roll" will be automaticaly applied.</i></p>
                        </div>

                        <div class="panel-body">
                        <p>Comment operator: </p>
                            {!! Form::textarea('comment_operator', $comment_operator , ['class' => 'form-control', 'rows' => 2]) !!}
                        </div>
                        <p><span style="color:red">Please re-check <b>skeda, dye lot and rewound length</b> because is not possible to correct after confirmation! </span></p>
                        <p><span style="color:red">If you find any problem please inform cutting shift manager</span></p>
                        <hr>
                        <br>

                        {!! Form::submit('Confirm rewound', ['class' => 'btn  btn-danger center-block']) !!}
                        <br>
                        @include('errors.list')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection