@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9 col-md-offset-1">
            <div class="panel panel-danger">
                <div class="panel-heading">2. Da li ste sigurni da zelite da obrisete sav paspul skede: <big><b>{{ $skeda }}</b></big> na lokaciji: <b>{{ $location_from }}</b> :
                    <p></br>Paznja: svi paspuli za izabranu skedu ce biti obrisani</p></div>
                
                @if (isset($msge))
                    <small><i>&nbsp &nbsp &nbsp <span style="color:red"><b>{{ $msge }}</b></span></i></small>
                    <audio autoplay="true" style="display:none;">
                        <!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
                    </audio>
                @endif
                @if (isset($msgs))
                    <small><i>&nbsp &nbsp &nbsp <span style="color:green"><b>{{ $msgs }}</b></span></i></small>
                    <audio autoplay="true" style="display:none;">
                        <!-- <source src="{{ asset('/css/2.wav') }}" type="audio/wav"> -->
                    </audio>
                @endif

                <div class="panel-body">

                {!! Form::open(['method'=>'POST', 'url'=>'/paspul_remove_valy_remove']) !!}
                    
                    {!! Form::hidden('location_from', $location_from) !!}
                    {!! Form::hidden('skeda', $skeda) !!}
                    
                    <div class="panel-body">
                    </div>

                        <table class="table table-striped table-bordered tableFixHead" id="table-draggable2" id="sort" 
                        >
                            <thead>
                               <tr>
                                    <th >Skeda</th>
                                    <th >Paspul key</th>
                                    <th >Dye lot</th>
                                    <th >Created</th>
                                    <th >Kotur qty</th>                                   

                                </tr>
                            </thead> 
                            <tbody class="connectedSortable_t able searchable">
                            @foreach ($data as $req)
                                <tr class="ss">
                                    <td>{{ $req->skeda}}</td>
                                    <td>{{ $req->pas_key}}</td>
                                    <td>{{ $req->dye_lot}}</td>
                                    <td>{{ substr($req->created_at,0,16)}}</td>
                                    <td>{{ $req->kotur_qty}}</td>
                                  
                                </tr>
                            @endforeach
                            </tbody>
                          </table>


                    <br>
                    {!! Form::submit('Confirm', ['class' => 'btn  btn-success center-block']) !!}

                    @include('errors.list')

                {!! Form::close() !!}


                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
