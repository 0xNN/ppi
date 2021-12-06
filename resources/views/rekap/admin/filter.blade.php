@extends('layouts.app', ['title' => __('Rekap PPI')])

@section('content')
  @include('header.partials.header', [
      'title' => __('Rekap PPI'),
      'description' => __('Data Rekap '.$tgl_masuk.' - '.$tgl_keluar),
      'class' => 'col-lg-12'
      ])

  <div class="container-fluid mt--7">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-xl-6">
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Jenis Operasi
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered w-100">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">No</th>
                    <th class="text-uppercase">Jenis Operasi</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_jenis_operasi as $key => $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Lama Operasi
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered w-100">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">No</th>
                    <th class="text-uppercase">Lama Operasi</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_lama_operasi as $key => $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Asa Score
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered w-100">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">Asa Score</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_asa_score as $key => $item)
                    <tr>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Risk Score
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered w-100">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">Risk Score</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_risk_score as $key => $item)
                    <tr>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-12 col-xl-6">
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Pemakaian Antibiotik
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">No</th>
                    <th class="text-uppercase">Pemakaian</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_antibiotik as $key => $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Pemeriksaan Kultur
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">No</th>
                    <th class="text-uppercase">Jenis Kultur</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_jenis_pendukung_kultur as $key => $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              HAIs
            </div>
            <div class="card-body">
              <table class="table table-sm table-bordered">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">No</th>
                    <th class="text-uppercase">HAI</th>
                    <th class="text-uppercase">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_jenis_infeksi_rs as $key => $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $key }}</td>
                      <td>{{ $item }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        {{-- <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Total
            </div>
            <div class="card-body">
              <table class="table table-sm table-responsive">
                
              </table>
            </div>
          </div>
        </div> --}}
        <div class="col-md-12 col-sm-12 mt-3">
          <div class="card bg-secondary shadow">
            <div class="card-header">
              Tindakan
            </div>
            <div class="card-body">
              <table class="table table-sm table-responsive table-bordered">
                <thead class="bg-orange text-white">
                  <tr>
                    <th class="text-uppercase">No</th>
                    <th class="text-uppercase">Alat</th>
                    <th class="text-uppercase">Jumlah Pasien</th>
                    <th class="text-uppercase">Jumlah Hari</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($total_alat_digunakan as $key => $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $key }}</td>
                      <td>{{ $item["jumlah_pasien"] }}</td>
                      <td>{{ $item["jumlah_hari"] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('layouts.footers.auth')
  </div>
@endsection

@push('css')
    
@endpush

@push('js')
    
@endpush