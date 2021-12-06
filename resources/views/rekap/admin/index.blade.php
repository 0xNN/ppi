@extends('layouts.app', ['title' => __('Rekap PPI')])

@section('content')
  @include('header.partials.header', [
      'title' => __('Rekap PPI'),
      'description' => __('Data Rekap'),
      'class' => 'col-lg-12'
      ])

  <div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
          <div class="card bg-secondary shadow">
            <form method="POST" action="{{ route('rekap.date-range') }}">
              @csrf
              <div class="card-header bg-white border-0">
                <h3>Data Rekap</h3>
                <small class="font-italic text-danger"><b>Note: </b> Jika tanggal masuk dan tanggal keluar dikosongkan maka sistem akan menampilkan semua data!</small>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12 col-xl-6">
                    <div class="form-group">
                      <label for="tgl_masuk">Tanggal Masuk</label>
                      <input type="text" id="tgl_masuk" name="tgl_masuk" class="form-control form-control-sm" placeholder="yyyy-mm-dd" readonly>
                    </div>
                  </div>
                  <div class="col-sm-12 col-xl-6">
                    <div class="form-group">
                      <label for="tgl_keluar">Tanggal Keluar</label>
                      <input type="text" id="tgl_keluar" name="tgl_keluar" class="form-control form-control-sm" placeholder="yyyy-mm-dd" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer bg-white border-0">
                <button type="submit" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
                  Submit <i class="fas fa-arrow-right"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="mt-3 col-xl-12 order-xl-1">
          <div class="card bg-secondary shadow">
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <form method="post" action="{{ route('rekap.filter-tahun') }}">
              @csrf
              <div class="card-header bg-white border-0">
                <h3>Data Rekap HAIs</h3>
                <small class="font-italic text-danger"><b>Note: </b> Rekap Filter berdasarkan tahun!</small>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12 col-xl-6">
                    <div class="form-group">
                      <label for="tahun_filter">Tahun Filter</label>
                      <input type="text" id="tahun_filter" name="tahun_filter" class="form-control form-control-sm" placeholder="yyyy" readonly>
                    </div>
                  </div>
                  <div class="col-sm-12 col-xl-3">
                    <div class="form-group">
                      <label for="infeksi_rs">Infeksi RS</label>
                      <select type="text" id="infeksi_rs" name="infeksi_rs" class="form-control form-control-sm" placeholder="yyyy">
                        <option value="pilih">-- PILIH --</option>
                        <option value="vap">VAP</option>
                        <option value="hap">HAP</option>
                        <option value="isk">ISK</option>
                        <option value="iadp">IADP</option>
                        <option value="pleb">PLEB</option>
                        <option value="ido">IDO</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12 col-xl-3">
                    <div class="form-group">
                      <label for="tindakan_operasi_id">Tindakan Operasi</label>
                      <select type="text" id="tindakan_operasi_id" name="tindakan_operasi_id" class="form-control form-control-sm" placeholder="yyyy" disabled>
                        <option value="pilih">-- PILIH --</option>
                        @foreach ($tindakan_operasi as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_tindakan_operasi }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer bg-white border-0">
                <button type="submit" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-filter">
                  Download <i class="fas fa-download"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="mt-3 col-xl-12 order-xl-1">
          <div class="card bg-secondary shadow">
            @if ($message = Session::get('error-rekap'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <form method="post" action="{{ route('rekap.rekap-denumerator') }}">
              @csrf
              <div class="card-header bg-white border-0">
                <h3>Data Rekap Denumerator HAIs dan Pemasangan Alat</h3>
                <small class="font-italic text-danger"><b>Note: </b> Rekap Pemasangan Alat!</small>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-12 col-xl-6">
                    <div class="form-group">
                      <label for="bulan_rekap">Bulan Rekap</label>
                      <select name="bulan_rekap" id="bulan_rekap" class="form-control form-control-sm">
                        <option value="pilih">-- PILIH --</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-12 col-xl-6">
                    <div class="form-group">
                      <label for="tahun_rekap">Tahun Rekap</label>
                      <input type="text" id="tahun_rekap" name="tahun_rekap" class="form-control form-control-sm" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer bg-white border-0">
                <button type="submit" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" name="tombol-rekap" id="tombol-rekap">
                  Rekap Pemasangan Alat <i class="fas fa-download"></i>
                </button>
                <button type="submit" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" name="tombol-kultur" id="tombol-kultur">
                  Kultur <i class="fas fa-download"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
    </div>
    @include('layouts.footers.auth')
  </div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets') }}/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/dist/css/izitoast.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/jquery.datetimepicker.min.css">
{{-- <link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css" rel="stylesheet/> --}}
<link rel="stylesheet" href="{{ asset('assets') }}/css/yearpicker.css">
{{-- <link rel="stylesheet" href="{{ asset('assets') }}/css/MonthPicker.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/examples.css"> --}}
@endpush

@push('js')
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('assets') }}/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/izitoast.min.js"></script>
<script src="{{ asset('assets') }}/js/index.var.js"></script>
<script src="{{ asset('assets') }}/js/select2.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.datetimepicker.full.min.js"></script>
{{-- <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> --}}
<script src="{{ asset('assets') }}/js/yearpicker.js"></script>
{{-- <script src="{{ asset('assets') }}/js/MonthPicker.min.js"></script>
<script src="{{ asset('assets') }}/js/examples.js"></script> --}}
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).ready(function () {
      $('#tgl_masuk').datetimepicker({
        timepicker: false,
        format: 'Y-m-d'
      });

      $('#tgl_keluar').datetimepicker({
        timepicker: false,
        format: 'Y-m-d'
      });

      $('#tahun_rekap').yearpicker();
      $('#tahun_filter').yearpicker({
        
      });

      $('#infeksi_rs').change(function() {
        var infeksi = $(this).val();
        if(infeksi == 'ido') {
          $('#tindakan_operasi_id').prop('disabled', false);
        } else {
          $('#tindakan_operasi_id').prop('disabled', true);
          $('#tindakan_operasi_id').val('pilih');
        }

      })
    });
</script>
@endpush