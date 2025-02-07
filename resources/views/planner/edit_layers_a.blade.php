@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">G bin: <big><b>{{ $g_bin }}</b></big></div>
              
                <!-- <div class="input-group"> <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div> -->

                {!! Form::open(['method'=>'POST', 'url'=>'/edit_layers_a_confirm']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        {!! Form::hidden('mattress', $mattress, ['class' => 'form-control']) !!}
                        {!! Form::hidden('g_bin', $g_bin, ['class' => 'form-control']) !!}
                            

                            <table style="width:100%; font-size: large;" class="table table-striped table-bordered">
                                <tr><td>Mattress</td><td><b>{{ $mattress }} </b></td></tr>

                                <tr><td>Layers Actual </td><td><input type="number" style="width: 100%;" class="btn check" name="layers_a_new" value="{{ round($layers_a,0) }}"></td></tr>
                            </table>

                            <table style="" class="table table-striped table-bordered" id="sort">
                                <thead>
                                    <tr>
                                        <th></th>            
                                        <th><b>Operator1 <br>(SM,MS,MM)</b></th>
                                        <th><b>Operator2 <br>(MS,MM)</b></th>
                                        <th><b>Layers actual for eff (editable)</b></th>
                                    </tr>
                                </thead>
                                <br>
                                    
                                    <tr>
                                        <td><b>Operator before</b></td>
                                        <td>@if ($operator_before != NULL)
                                            {{ $operator_before }}
                                            @endif
                                        </td>
                                        <td>{{ $operator2_before }}</td>
                                        @if ($operator_before == NULL)
                                            <td><input type="number" style="width: 100%;" class="btn check" name="layers_before_cs_new" value="{{ $layers_before_cs }}" disabled></td>
                                            {!! Form::hidden('layers_before_cs_new', '0', ['class' => 'form-control']) !!}
                                        @else
                                            <td><input type="number" style="width: 100%;" class="btn check" name="layers_before_cs_new" value="{{ $layers_before_cs }}"></td>
                                        @endif
                                    </tr>
                                    
                                    <tr>
                                        <td><b>Operator after</b></td>
                                        <td>{{ $operator_after }}</td>
                                        <td>{{ $operator2_after}}</td>
                                        <td><input type="number" style="width: 100%;" class="btn check" name="layers_after_cs_new" value="{{ $layers_after_cs }}"></td>
                                    </tr>
                                </tbody>     
                            </table>

                        <hr>
                        {!! Form::submit('Save', ['class' => 'btn  btn-success center-block']) !!}
                        <br>
                        @include('errors.list')


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
