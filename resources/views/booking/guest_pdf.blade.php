<!DOCTYPE html>
<html>

<head>
    <title>{{ $name }} PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body style="border-style: outset;" class="p-3">
    <title>{{ $name }} PDF</title>

    <div class="mb-2 center text-center">
        <table style="width:100%">
            <tr>
                <td width="20%" style="text-align: center">
                    <img src="http://c2.cloudkejaksaan.my.id/assets/images/logo/kejaksaan-logo.png" alt=""
                        height="100">
                </td>
                <td width="60%" style="text-align: center">
                    <h3 class="mb-2">Kejaksaan Republik Indonesia</h3>
                    <h4>{{ $satker['name'] }}</h4>
                    <label>{{ $satker['address'] }}</label>
                </td>
                <td width="20%" style="text-align: center">
                    <img src="{{ "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=$datas->qrcode" }}"
                        alt="" height="100">
                </td>
            </tr>
        </table>
        <hr style="height:2px; border-width:1; color:black; background-color:black">
    </div>

    <div class="mb-3" style="text-align: center; font-weight: bold; font-size:20px; text-decoration: underline;">BUKTI
        PENDAFTARAN LAYANAN</div>
    <div style="text-align: center;">Nomor Antrian :</div>
    <div style="text-align: center; font-weight: bold; font-size:28px;" class="mb-2">
        {{ $datas->layanans->kode . $datas->nomor_antrian }}</div>

    @php $user = explode('|',$datas->user) @endphp
    <table style="width:80%" class="mb-3">
        <tr>
            <td width="20%">
                <div>Nama</div>
                <div>Email</div>
                <div>Provinsi</div>
                <div>Satker</div>
                <div>Layanan</div>
                <div>Nomor OTP</div>
                <div>Tanggal Pendaftaran</div>
                <div>Tanggal Kehadiran</div>
            </td>
            <td width="40%">
                <div>: {{ $user[1] }} </div>
                <div>: {{ $user[2] }} </div>
                <div>: {{ $datas->provinsi }} </div>
                <div>: {{ $datas->satker }} </div>
                <div style="font-weight: bold;">: {{ $datas->layanan }} </div>
                <div>: {{ $datas->otp }} </div>
                <div>: {{ $datas->created_at }} </div>
                <div>: {{ $datas->tanggal_hadir }} </div>
            </td>
        </tr>
    </table>
    <table style="width:80%; border:dotted; font-size:12px;">
        <tr>
            <td>
                <div style="text-indent: 0px;text-decoration: underline; font-weight: bold;" class="mb-1">Keterangan:
                </div>
                <ul>
                    <li>Simpan Bukti Pendaftaran ini</li>
                    <li>Bawa bukti pendaftaran ini ke Kejaksaan {{ $satker['name'] }}</li>
                </ul>
            </td>
        </tr>
    </table>
</body>

</html>
