<table>
  <thead>
    <tr>
      <th></th>
      <th>REKAP PASIEN</th>
    </tr>
    <tr>
      <th></th>
      <th>{{ strtoupper($month[$bulan]).' '.$tahun }}</th>
    </tr>
  </thead>
</table>
<table>
  <thead>
  <tr>
    <th>No</th>
    <th>Nama Pasien</th>
    <th>Tanggal Lahir</th>
    <th>Medrec</th>
    <th>Asal Kultur</th>
    <th>Tanggal Kultur</th>
    <th>Hasil Kultur Kuman</th>
    <th>Status</th>
    <th>Ruang</th>
  </tr>
  </thead>
  <tbody>
  @foreach ($datas as $item)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $item["nama_pasien"] }}</td>
      <td>{{ $item["tanggal_lahir"] }}</td>
      <td>{{ $item["no_rm"] }}</td>
      <td>{{ $item["asal_kultur"] }}</td>
      <td>{{ $item["tanggal_kultur"] }}</td>
      <td>{{ $item["hasil_kultur_kuman"] }}</td>
      <td>{{ $item["status"] }}</td>
      <td>{{ $item["ruang"] }}</td>
    </tr>
  @endforeach
  </tbody>
</table>