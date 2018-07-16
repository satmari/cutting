@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create new Po</div>

                <div class="panel-body">
                
                {!! Form::open(['method'=>'POST', 'url'=>'/post_new_po']) !!}

                        <div class="panel-body">
                        <p>Komesa: <span style="color:red;">*</span></p>
                            {!! Form::text('po', null, ['class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        
                        {!! Form::submit('Create', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
