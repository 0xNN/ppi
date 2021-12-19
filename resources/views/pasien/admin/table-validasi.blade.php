<div class="card shadow-lg">
  <div class="card-header">
    Data Validasi
  </div>
  <div class="card-body">
    <table class="table table-sm table-bordered table-responsive w-100 d-print-block d-print-table">
      <tr>
        <td>Ruang</td>
        <td>{{ $ruang->nama_ruang }}</td>
      </tr>
      <tr>
        <td>Tgl Masuk</td>
        <td>{{ $data['tgl_masuk'] }}</td>
      </tr>
      <tr>
        <td>Tgl Keluar</td>
        @if ($data['tgl_keluar'] == null || $data['tgl_keluar'] == "____-__-__")
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>{{ $data['tgl_keluar'] }}</td>
        @endif
      </tr>
      <tr>
        <td>Diagnosa</td>
        @if ($diagnosa == null)
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>
          @foreach ($diagnosa as $item)
            {{ $item.', ' }}
          @endforeach
        </td>
        @endif
      </tr>
      <tr>
        <td>Operasi</td>
        @if ($operasi == null)
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>
          @foreach ($operasi as $item)
            {{ $item.', ' }}
          @endforeach
        </td>
        @endif
      </tr>
      <tr>
        <td>Tgl Sensus</td>
        @if ($data['tgl_sensus'] == null || $data['tgl_sensus'] == "____-__-__ __:__:__")
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>{{ $data['tgl_sensus'] }}</td>
        @endif
      </tr>
      <tr>
        <td>Alat Digunakan</td>
        @if ($alat == null)
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>
          @foreach ($alat as $item)
            {{ $item.', ' }}
          @endforeach
        </td>
        @endif
      </tr>
      <tr>
        <td>Kegiatan Sensus</td>
        @if ($kegiatan_sensus == null)
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>{{ $kegiatan_sensus }}</td>
        @endif
      </tr>
      <tr>
        <td>Hasil Rontgen</td>
        @if ($data['hasil_rontgen'] == null)
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>{{ $data['hasil_rontgen'] }}</td>
        @endif
      </tr>
      <tr>
        <td>Tgl Rontgen</td>
        @if ($data['tgl_rontgen'] == null || $data['tgl_rontgen'] == "____-__-__")
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>{{ $data['tgl_rontgen'] }}</td>
        @endif
      </tr>
      <tr>
        <td>Infeksi RS</td>
        @if ($infeksi_rs == '')
        <td><small class="text-danger">(Kosong)</small></td>
        @else
        <td>{{ $infeksi_rs }}</td>
        @endif
      </tr>
      <tr>
        <td>Jenis Kuman</td>
        @if ($data['jenis_kumen'] == null)
        <td><small class="text-danger">(Kosong)</small></td>
        @else
        <td>{{ $data['jenis_kumen'] }}</td>
        @endif
      </tr>
      <tr>
        <td>Tgl Infeksi Kuman</td>
        @if ($data['tgl_infeksi_kuman'] == null || $data['tgl_infeksi_kuman'] == "____-__-__")
        <td><small class='text-danger'>(Kosong)</small></td>
        @else
        <td>{{ $data['tgl_infeksi_kuman'] }}</td>
        @endif
      </tr>
      <tr>
        <td>Kultur pendukung HAIs</td>
        @if ($kultur == '')
        <td><small class="text-danger">(Kosong)</small></td>
        @else
        <td>{{ $kultur }}</td>
        @endif
      </tr>
      <tr>
        <td>Antibiotik</td>
        @if ($antibiotik == null)
        <td><small class="text-danger">(Kosong)</small></td>
        @else
        <td>{{ $antibiotik }}</td>
        @endif
      </tr>
      <tr>
        <td>Transmisi</td>
        @if ($transmisi == null)
        <td><small class="text-danger">(Kosong)</small></td>
        @else
        <td>{{ $transmisi }}</td>
        @endif
      </tr>
      <tr>
        <td>Infeksi RS Lain</td>
        @if ($infeksi_rs_lain == '')
        <td><small class="text-danger">(Kosong)</small></td>
        @else
        <td>{{ $infeksi_rs_lain }}</td>
        @endif
      </tr>
    </table>
  </div>
</div>