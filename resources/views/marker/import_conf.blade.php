@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default col-md-4 col-md-offset-4">
                <div class="panel-heading">Import marker: <big><b>{{ $marker_name }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/marker_line_confirm']) !!}

                        {!! Form::hidden('marker_name', $marker_name, ['class' => 'form-control']) !!}

                        {!! Form::hidden('marker_header_id', $marker_header_id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_width', $marker_width, ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_length', $marker_length, ['class' => 'form-control']) !!}

                        {!! Form::hidden('marker_type', $marker_type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('marker_code', $marker_code, ['class' => 'form-control']) !!}
                        {!! Form::hidden('fabric_type', $fabric_type, ['class' => 'form-control']) !!}
                        {!! Form::hidden('constraint', $constraint, ['class' => 'form-control']) !!}

                        {!! Form::hidden('spacing_around_pieces', $spacing_around_pieces, ['class' => 'form-control']) !!}
                        {!! Form::hidden('spacing_around_pieces_top', $spacing_around_pieces_top, ['class' => 'form-control']) !!}
                        {!! Form::hidden('spacing_around_pieces_bottom', $spacing_around_pieces_bottom, ['class' => 'form-control']) !!}
                        {!! Form::hidden('spacing_around_pieces_right', $spacing_around_pieces_right, ['class' => 'form-control']) !!}
                        {!! Form::hidden('spacing_around_pieces_left', $spacing_around_pieces_left, ['class' => 'form-control']) !!}

                        {!! Form::hidden('processing_date', $processing_date, ['class' => 'form-control']) !!}

                        {!! Form::hidden('efficiency', $efficiency, ['class' => 'form-control']) !!}
                        {!! Form::hidden('cutting_perimeter', $cutting_perimeter, ['class' => 'form-control']) !!}
                        {!! Form::hidden('perimeter', $perimeter, ['class' => 'form-control']) !!}
                        {!! Form::hidden('average_consumption', $average_consumption, ['class' => 'form-control']) !!}
                        {!! Form::hidden('lines', $lines, ['class' => 'form-control']) !!}
                        {!! Form::hidden('curves', $curves, ['class' => 'form-control']) !!}
                        {!! Form::hidden('areas', $areas, ['class' => 'form-control']) !!}
                        {!! Form::hidden('angles', $angles, ['class' => 'form-control']) !!}
                        {!! Form::hidden('notches', $notches, ['class' => 'form-control']) !!}
                        {!! Form::hidden('total_pcs', $total_pcs, ['class' => 'form-control']) !!}

                        {!! Form::hidden('variant_model', $variant_model, ['class' => 'form-control']) !!}
                        {!! Form::hidden('key', $key, ['class' => 'form-control']) !!}
                        
                        {!! Form::hidden('status', $status, ['class' => 'form-control']) !!}
                        {!! Form::hidden('operator', $operator, ['class' => 'form-control']) !!}

                        <br>
                        <span style="font-style: italic; text-align: left;  font-size: 10px;">
                        <table style="width: 100%;">
                        <tr>
                        <td style="width: 50%;">
                        <p>marker_name:   <b>{{ $marker_name }}</b></p>

                        <p>marker_width:  <b>{{ $marker_width }}</b></p>
                        <p>marker_length: <b>{{ $marker_length }}</b></p>

                        <p>marker_type: <b>{{ $marker_type }}</b></p>
                        <p>marker_code: <b>{{ $marker_code }}</b></p>
                        <p>fabric_type: <b>{{ $fabric_type }}</b></p>
                        <p>constraint:  <b>{{ $constraint }}</b></p>

                        <p>spacing_around_pieces:        <b>{{ $spacing_around_pieces }}</b></p>
                        <p>spacing_around_pieces_top:    <b>{{ $spacing_around_pieces_top }}</b></p>
                        <p>spacing_around_pieces_bottom: <b>{{ $spacing_around_pieces_bottom }}</b></p>
                        <p>spacing_around_pieces_right:  <b>{{ $spacing_around_pieces_right }}</b></p>
                        <p>spacing_around_pieces_left:   <b>{{ $spacing_around_pieces_left }}</b></p>

                        <p>processing_date: <b>{{ $processing_date }}</b></p>
                        </td>
                        
                        <td style="width: 50%;">
                        <p>efficiency:          <b>{{ $efficiency }}</b></p>
                        <p>cutting_perimeter:   <b>{{ $cutting_perimeter }}</b></p>
                        <p>perimeter:           <b>{{ $perimeter }}</b></p>
                        <p>average_consumption: <b>{{ $average_consumption }}</b></p>
                        <p>lines:               <b>{{ $lines }}</b></p>
                        <p>curves:              <b>{{ $curves }}</b></p>
                        <p>areas:               <b>{{ $areas }}</b></p>
                        <p>angles:              <b>{{ $angles }}</b></p>
                        <p>notches:             <b>{{ $notches}}</b></p>
                        <p>total_pcs:           <b>{{ $total_pcs}}</b></p>

                        <p>variant_model:       <b>{{ $variant_model}}</b></p>
                        <p>key:                 <b>{{ $key}}</b></p>

                        <!-- <p>min_length:          <b>{{ $min_length}}</b></p> -->
                        <p>status:              <b>{{ $status}}</b></p>

                        </td>
                        </tr>
                        </table>
                        </span>

                        <table class="table table-s triped table-bordered" id="sort" 
                        >
                            <thead>
                                <tr>            
                                    <th><b>Style</b></th>
                                    <th><b>Size</b></th>
                                    <th><b>Qty</b></th>
                                </tr>
                            </thead>
                            <!-- <tbody class="searchable"> -->
                            <br>

                            @foreach ($data as $req1)
                                <tr>
                                     <td>
                                        <input type="string" style="width: 100%;" class="btn check" name="style[]" value="{{ $req1['style'] }}">

                                    </td>
                                     <td>
                                        <input type="string" style="width: 100%;" class="btn check" name="size[]" value="{{ $req1['size'] }}">  

                                    </td>
                                    <td>
                                        <input type="string" style="width: 100%;" class="btn check" name="qty[]" value="{{ $req1['qty'] }}">  

                                    </td>
                                </tr>
                            @endforeach
                             
                            </tbody>     
                        </table>
                        <br>
                        <label>Min length (mini marker)</label>
                        <input type="number" step=".01" style="width: 100%;" class="form-control" name="min_length" value="0">  

                        <br>
                        <label>Marker creation type</label>
                        {!! Form::select('creation_type', array(''=>'', 'Local 8min'=>'Local 8min', 'Local 12min' => 'Local 12min', 'Local manually' => 'Local manually', 'Cloud fast' => 'Cloud fast', 'Cloud std 1h' => 'Cloud std 1h', 'Cloud std 4h' => 'Cloud std 4h', 'Cloud std 12h' => 'Cloud std 12h','FLEX NEST' => 'FLEX NEST'), null, array('class' => 'form-control', 'autofocus' => 'autofocus')) !!} 


                        <hr>
                        <br>
                        {!! Form::submit('Import', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')

                {!! Form::close() !!}       
            </div>
        </div>
    </div>
</div>
@endsection
