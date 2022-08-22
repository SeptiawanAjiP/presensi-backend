@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Rekap Presensi</div>
                
                <div class="card-body">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Waktu</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                            <th>Lokasi (Latitude, Longitude)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presensis as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{$item->tanggal}}</td>
                            <td>{{$item->masuk}}</td>
                            <td>{{$item->pulang}}</td>
                            <td>{{$item->latitude}}, {{$item->longitude}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
       
        
    </div>
</div>
<script type="text/javascript" class="init">
	

$(document).ready(function () {
	var table = $('#example').DataTable( {
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true
    } );
});

</script>

@endsection



