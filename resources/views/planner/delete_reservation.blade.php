@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-danger">
                <div class="panel-heading"><big><b>Are you sure to delete reservation</b></big></div>
                    
                
                
                <div class="panel-body">
                    <p></p>
                    <p>
                        Document No : {{ $document_no }} </p>
                        Skeda : {{ $skeda }} </p>
                        Material : {{ $material }} </p>
                        Reserved qty : {{ round($qty_reserved_m ,1)}} </p>

                        <hr>
                        <br>
                <a href="{{ url('delete_reservation/'.$id) }}" class="btn btn-danger btn-xs">Delete</a>

                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
