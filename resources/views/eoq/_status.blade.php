@php
    $peta = [
        'aman' => ['Aman', 'success'],
        'pesan' => ['Pesan Ulang', 'warning'],
        'kritis' => ['Kritis', 'danger'],
        'tanpa_data' => ['Belum Ada Data', 'secondary'],
    ];
    [$label, $warna] = $peta[$status] ?? ['-', 'secondary'];
@endphp
<span class="badge bg-{{ $warna }}">{{ $label }}</span>