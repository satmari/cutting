@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">Edit paspul roll: <big><b>{{ $paspul_roll }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/edit_paspul_line_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_roll', $paspul_roll, ['class' => 'form-control']) !!}
                        {!! Form::hidden('paspul_roll_id', $paspul_roll_id, ['class' => 'form-control']) !!}
                        <!-- {!! Form::hidden('skeda', $skeda, ['class' => 'form-control']) !!} -->
                        {!! Form::hidden('skeda_item_type', $skeda_item_type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('location', $location, ['class' => 'form-control']) !!}
                        
                        <table style="width:100%" class="table table-striped table-bordered">
                            <tr><td>Material</td><td><b>{{ $material }} </b></td></tr>
                            <tr><td>Dye lot</td><td><b>{{ $dye_lot }} </b></td></tr>
                            <tr><td>Color desc</td><td><b>{{ $color_desc }} </b></td></tr>
                            <tr><td>Skeda</td><td><b>{{ $skeda }} </b></td></tr>
                            <tr><td>Skeda type</td><td><b>{{ $skeda_item_type }} </b></td></tr>
                            <tr><td>TPA number</td><td><b>{{ $tpa_number }} </b></td></tr>
                            <tr><td>Rewinding method</td><td><b>{{ $rewinding_method }} </b></td></tr>
                        </table>

                        @if (isset($sp_operator))
                        <table style="width:100%" class="table table-striped table-bordered">
                            <tr><td>PRW operator:</td><td><b>{{$sp_operator}}</b></td></tr>
                            <tr><td>Date:</td><td><b>{{$sp_date}}</b></td></tr>
                        </table>
                        @endif

                        @if (isset($cut_operator))
                        <table style="width:100%" class="table table-striped table-bordered">
                            <tr><td>PCO operator:</td><td><b>{{$cut_operator}}</b></td></tr>
                            <tr><td>Date:</td><td><b>{{$cut_date}}</b></td></tr>
                        </table>
                        @endif

                    
                    @if ($location != 'COMPLETED' )

                        <div class="panel-body">
                        <p>Skeda: <span style="color:red;">*</span></p>
                            
                           {!! Form::text('skeda', $skeda, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Dye lot: <span style="color:red;">*</span></p>
                            
                           {!! Form::text('dye_lot', $dye_lot, ['class' => 'form-control']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), $priority, array('class' => 'form-control')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::textarea('comment_office', $comment_office, ['class' => 'form-control', 'rows' => 2]) !!}
                        </div>

                        <table style="width:100%">
                        <tr>
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', '1' , $call_shift_manager , ['class' => 'form-control']) !!}
                                </div>
                            </td>
                
                        </tr>
                        </table>
                        <hr>
                        {!! Form::submit('Save', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')


                    @else

                        <div class="panel-body">
                        <p>Priority: <span style="color:red;">*</span></p>
                            <!-- {!! Form::number('priority', $priority, ['class' => 'form-control']) !!} -->
                            {!! Form::select('priority', array('1'=>'Normal','2'=>'Flash','3'=>'Top','4'=>'1st shift','5'=>'2nd shift','6'=>'3rd shift','7'=>'Test'), $priority, array('class' => 'form-control', 'disabled')) !!} 
                        </div>

                        <div class="panel-body">
                        <p>Comment office: <!-- <span style="color:red;">*</span> --></p>
                            {!! Form::textarea('comment_office', $comment_office, ['class' => 'form-control', 'rows' => 2, 'disabled']) !!}
                        </div>

                        <table style="width:100%">
                        <tr>
                            <td style="width:32%">
                                <div class="panel-body">
                                <p>Call shift manager: <!-- <span style="color:red;">*</span> --></p>
                                    {!! Form::checkbox('call_shift_manager', '1' , $call_shift_manager , ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </td>
                
                        </tr>
                        </table>

                        <hr>
                        {!! Form::submit('Save', ['class' => 'btn  btn-success center-block','disabled']) !!}
                        <br>
                        @include('errors.list')

                    @endif

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
