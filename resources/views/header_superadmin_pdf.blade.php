<table style="width:100%">
    <tr>
        <td width="20%" style="text-align: center">
            <img src="http://c2.cloudkejaksaan.my.id/assets/images/logo/kejaksaan-logo.png" alt="" height="100">
        </td>
        <td width="80%" style="text-align: center">
            <h1 class="mb-2">Kejaksaan Republik Indonesia</h1>
        </td>
    </tr>
</table>
<hr style="height:2px;border-width:1;color:black;background-color:black">

<div>
    <div style="float: left">
        <h5>Laporan {{ $name }}</h4>
    </div>
    <div style="float: right">
       Tanggal Cetak: {{ Date('d M Y') }}
    </div>
    <div style="clear:left"></div>
</div>
<div class="text-left">Tanggal Filter: {{ $filter['tanggal_awal'].' sampai '.$filter['tanggal_akhir'] }}</div>
