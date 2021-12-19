@extends('layouts.app', ['title' => __('List PPI')])

@section('content')
  @include('header.partials.header', [
      'title' => __('List PPI'),
      'description' => __('Data Input PPI'),
      'class' => 'col-lg-12'
      ])

  <div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    {{-- <div class="text-danger font-italic">{{ __('List Jenis Operasi!') }}</div> --}}
                    <a href="javascript:void(0)" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
                      Input PPI <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dt-ppi" class="table table-sm table-bordered dt-responsive nowrap" style="width:100%">
                            <thead class="thead-info">
                                <tr>
                                    <th>#</th>
                                    {{-- <th>ID</th> --}}
                                    {{-- <th>No REG</th> --}}
                                    <th>No RM</th>
                                    <th>Tgl Masuk/Keluar</th>
                                    <th>Ruang</th>
                                    <th>Operasi</th>
                                    <th>Tindakan/Jenis/Lama Operasi</th>
                                    <th>ASA/Risk Score</th>
                                    <th>Diagnosa</th>
                                    <th>Tgl/Kegiatan Sensus</th>
                                    <th>Alat</th>
                                    <th>Infeksi RS</th>
                                    <th>Kultur</th>
                                    <th>Transmisi</th>
                                    {{-- <th>Infeksi Lain</th> --}}
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
<link rel="stylesheet" href="{{ asset('assets') }}/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/dist/css/izitoast.min.css">
@endpush

@push('js')
{{-- <script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.extension.js"></script> --}}
<script src="{{ asset('assets') }}/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/izitoast.min.js"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).ready(function() {
      var table = $('#dt-ppi').DataTable({
        language: {
          language: {
            url: '{{ asset('config.json') }}'
          },
        },
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('data-ppi.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
            // {data: 'id', name: 'id'},
            // {data: 'no_registration', name: 'no_registration'},
            {data: 'no_rm', name: 'no_rm'},
            {data: 'tgl_masuk_keluar', name: 'tgl_masuk_keluar'},
            {data: 'ruang_id', name: 'ruang_id'},
            {data: 'operasi', name: 'operasi'},
            {data: 'oprs1', name: 'oprs1'},
            {data: 'oprs2', name: 'oprs2'},
            {data: 'diagnosa', name: 'diagnosa'},
            {data: 'tgl_kegiatan_sensus', name: 'tgl_kegiatan_sensus'},
            {data: 'alat', name: 'alat'},
            {data: 'infeksi_rs', name: 'infeksi_rs'},
            {data: 'kultur', name: 'kultur'},
            {data: 'transmisi_id', name: 'transmisi_id'},
            // {data: 'infeksi_lain', name: 'infeksi_lain'},
            {data: 'created_at', name: 'created_at'}
        ],
        columnDefs: [ {
            className: 'dtr-control',
            orderable: false,
            targets:   [1]
        } ],
        order: [ 1, 'asc' ]
      });
    });
</script>
@endpush