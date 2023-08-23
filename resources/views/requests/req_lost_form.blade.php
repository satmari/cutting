@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading">Request for LOST BB
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{url('/req_lost_table_history_line')}}" class="btn btn-info btn-xs ">History</a>
                </div>
                
                <div class="panel-body">

                @if (isset($msgs))
                    <p style="color:green;">{{ $msgs }}</p>
                @endif

                @if (isset($msge))
                    <p style="color:red;">{{ $msge }}</p>
                @endif  

                {!! Form::open(['method'=>'POST', 'url'=>'/req_lostconfirm']) !!}

                        {!! Form::hidden('module', $module, ['class' => 'form-control']) !!}

                        <div class="panel-body">
                        <p>SKU: <span style="color:red;">*</span></p>
                           
                            <select name="selected_sku" class="select form-control select-form chosen">
                                <option value=""><option>
                                @foreach ($skus as $s)  
                                    <option value="{{ $s->sku }}">
                                        {{ $s->sku }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="panel-body">
                            <p>Komentar: </p>
                            {!! Form::text('comment', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>

                        <div class="panel-body">
                        <p>Quantity/Kolicina: <span style="color:red;">*</span></p>
                            {!! Form::input('number', 'qty', null, ['class' => 'form-control']) !!}
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
