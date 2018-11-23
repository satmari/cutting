@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add new Po</div>
                
                <div class="panel-body">

                    <div class="alert alert-warning">
                      <strong>Warning!</strong> Application will suggest released PO from Navision, but you don't have any limitation on po name.
                    </div>
                
                {!! Form::open(['method'=>'POST', 'url'=>'/add_new_po_cons']) !!}

                        <div class="panel-body">
                        <p>Komesa: <span style="color:red;">*</span></p>
                            {!! Form::text('po', null, ['id' => 'po','class' => 'form-control', 'autofocus' => 'autofocus']) !!}
                        </div>
                        
                        
                        {!! Form::submit('Add', ['class' => 'btn  btn-success center-block']) !!}

                        @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
