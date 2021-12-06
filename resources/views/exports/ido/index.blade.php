<p>NUMERATOR : Jumlah Infeksi Rumah sakit (Tindakan Operasi)</p>
<p>DENUMERATOR : Jumlah tindakan operasi (Tindakan Operasi)</p>
<p>STANDARD : 2%</p>
<p></p>
<table>
  <thead>
  <tr>
    <th>No</th>
    <th>Bulan</th>
    <th>Numerator</th>
    <th>Denumerator</th>
    <th>Capaian</th>
    <th>Target</th>
    <th>Rata-Rata</th>
    <th>Analisis</th>
    <th>Tindak Lanjut</th>
  </tr>
  </thead>
  <tbody>
  @php
    $numerator = 0;
    $denumerator = 0;
    $capaian = 0.0;
    $total_isi = 0;
  @endphp
  @foreach($ido as $data)
  @php
      $numerator = $numerator + $data->numerator;
      $denumerator = $denumerator + $data->denumerator;
      $capaian = $capaian + $data->capaian;
      if($data->capaian > 0) {
        $total_isi = $total_isi + 1;
      }
  @endphp
  <tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $data->bulan }}</td>
    <td>{{ $data->numerator }}</td>
    <td>{{ $data->denumerator }}</td>
    <td>{{ $data->capaian }}</td>
    <td>{{ $data->target == null ? "-": $data->target }}</td>
    <td>{{ $data->rata_rata }}</td>
    <td>{{ $data->analisis }}</td>
    <td>{{ $data->tindak_lanjut }}</td>
  </tr>
  @endforeach
  <tr>
    <td colspan="2">Tahun {{ $tahun }}</td>
    <td>{{ $numerator }}</td>
    <td>{{ $denumerator }}</td>
    <td>{{ $capaian && $total_isi == 0 ? 0: ($total_isi == 0 ? 0: $capaian / $total_isi) }}</td>
    <td>2</td>
    <td>2.32</td>
  </tr>
  </tbody>
</table>