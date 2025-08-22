<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<xml>
    @foreach ($satker as $v)
        <satker>
            @if($v->id) <id>{{ $v->id }}</id> @else @endif
            @if($v->kode_satker) <kode>{{ $v->kode_satker }}</kode> @else @endif
            @if($v->name) <nama>{{ $v->name }}</nama> @else @endif
            @if($v->provinsi_id) <provinsi>{{ $v->provinsis->name ?? '' }}</provinsi> @else @endif
            @if($v->address) <alamat>{{ $v->address }}</alamat> @else @endif
        </satker>
    @endforeach
</xml>
