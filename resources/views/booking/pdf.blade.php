<!DOCTYPE html>
<html>
<head>
	<title>{{ $name }} PDF</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body style="font-size:12px">
    <title>{{ $name }} PDF</title>

    <div class="mb-2 center text-center">
        @if (!isset($satker->id))
            @include('header_superadmin_pdf')
        @else
            @include('header_pdf')
        @endif
    </div>

    @if (!$datas->isEmpty())
        <table class='table table-bordered' style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Layanan</th>
                    <th>Tanggal Hadir</th>
                    <th>Nomor Antrian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datas as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->user }}</td>
                    <td>{{ $d->layanan }}</td>
                    <td>{{ $d->tanggal_hadir }}</td>
                    <td>{{ $d->nomor_antrian }}</td>
                    <td>{{ $d->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div center><label class="align-center h3">Data Tidak ada</label></div>
    @endif
</body>
</html>
