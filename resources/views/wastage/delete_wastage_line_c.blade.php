@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Please inset a passwod to delete wastage</div>
                
                @if(isset($msg))
                    <div class="alert alert-danger" role="alert">
                        {{ $msg }}
                    </div>
                @endif

                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/delete_wastage_line_g']) !!}

                        {!! Form::hidden('id', $id, ['class' => 'form-control']) !!}
                        
                        <div class="panel-body">
                        <p>Insert password: <span style="color:red;">*</span></p>
                            {!! Form::text('pass', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        
                        {!! Form::submit('Delete', ['class' => 'btn  btn-danger center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
