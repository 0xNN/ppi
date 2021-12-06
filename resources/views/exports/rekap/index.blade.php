<p>REKAP PEMASANGAN ALAT</p>
<p>{{ strtoupper($month[$bulan]).' '.$tahun }}</p>
<p></p>
<table>
  <thead>
  <tr>
    <th rowspan="2">No</th>
    <th rowspan="2" colspan="2">Pemakaian Alat Perawatan</th>
    <th rowspan="2">Online</th>
    <th colspan="{{count($ruangs)}}">Manual</th>
    <th rowspan="2">Jumlah</th>
  </tr>
  <tr>
  @foreach ($ruangs as $item)
    <th>{{ $item->nama_ruang }}</th>
  @endforeach
  </tr>
  </thead>
  <tbody>
  @foreach ($arr_data as $key => $item)
    @if ($key == "Tirah Baring")
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $key }}</td>
      <td></td>
      <td>{{ $item["online"] }}</td>
    @foreach ($ruangs as $ruang)
      @foreach ($item['ruang'] as $k => $r)
        @if ($k == $ruang->id)
          <td>{{ $r["jumlah"] }}</td>
        @endif
      @endforeach
    @endforeach
    </tr>
    @elseif($key == "Jumlah Operasi")
    <tr>
      <td></td>
      <td>{{ $key }}</td>
      <td></td>
      <td>{{ $item["online"] }}</td>
    @foreach ($ruangs as $ruang)
      @foreach ($item['ruang'] as $k => $r)
        @if ($k == $ruang->id)
          <td>{{ $r["jumlah"] }}</td>
        @endif
      @endforeach
    @endforeach
    </tr>
    @else
    <tr>
      <td rowspan="2">{{ $loop->iteration }}</td>
      <td rowspan="2">{{ $key }}</td>
      <td>Jumlah Pasien</td>
      <td>{{ $item["jumlah_pasien"] }}</td>
    @foreach ($ruangs as $ruang)
      @foreach ($item['ruang'] as $k => $r)
        @if ($k == $ruang->id)
          <td>{{ $r["jumlah_pasien"] }}</td>
        @endif
      @endforeach
    @endforeach
    </tr>
    <tr>
      <td>Jumlah Hari</td>
      <td>{{ $item["jumlah_hari"] }}</td>
    @foreach ($ruangs as $ruang)
      @foreach ($item['ruang'] as $k => $r)
        @if ($k == $ruang->id)
          <td>{{ $r["jumlah_hari"] }}</td>
        @endif
      @endforeach
    @endforeach
    </tr>
    @endif
  @endforeach
  </tbody>
</table>