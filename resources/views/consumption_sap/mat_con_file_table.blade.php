@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="text-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    MAT CONS
                </div>

                <div class="input-group">
                    <span class="input-group-addon">Filter</span>
                    <input id="filter" type="text" class="form-control" placeholder="Type here...">
                </div>

                <!-- Export Button -->
                <div class="text-right" style="margin: 10px;">
                    <button id="exportBtn" class="btn btn-success">Export to Excel</button>
                </div>

                <table class="table table-striped table-bordered" id="matTable">
                    <thead>
                        <tr>
                            <th><b>g bin</b></th>
                            <th><b>pro</b></th>
                            <th><b>qty</b></th>
                        </tr>
                    </thead>
                    <tbody class="searchable">
                        @foreach ($data as $req)
                            <tr>
                                <td>{{ $req->g_bin }}</td>
                                <td>{{ $req->pro }}</td>
                                <td>{{ round($req->qty, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SheetJS Library -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.getElementById('exportBtn').addEventListener('click', function () {
        let table = document.getElementById('matTable');
        let workbook = XLSX.utils.table_to_book(table, { sheet: "MAT_CONS" });
        XLSX.writeFile(workbook, 'mat_cons.xlsx');
    });
</script>
@endsection
