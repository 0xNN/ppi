@extends('layouts.app', ['title' => __('Pasien')])

@section('content')
  @include('pasien.partials.header', [
      'title' => __('Data Pasien'),
      'description' => __('Data pasien berasal dari Rawat Inap'),
      'class' => 'col-lg-12'
      ])

  <div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="text-danger font-italic">{{ __('Mohon bersabar. Proses pengambilan data membutuhkan waktu lebih lama!') }}</div>
                    {{-- <a href="javascript:void(0)" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
                        <i class="fas fa-plus"></i>
                    </a> --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dt-pasien" class="table table-sm table-bordered dt-responsive nowrap" style="width:100%">
                            <thead class="thead-info">
                                <tr>
                                    <th>ID</th>
                                    <th>Aksi</th>
                                    <th id="medrec">No.Medrec</th>
                                    <th id="noreg">No.Reg</th>
                                    <th>Nama</th>
                                    <th>J.Kelamin</th>
                                    <th>Tmp.Lahir</th>
                                    <th>Tgl.Lahir</th>
                                    <th>Tgl Masuk</th>
                                    <th>Tgl Keluar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Aksi</th>
                                    <th id="medrec">No.Medrec</th>
                                    <th id="noreg">No.Reg</th>
                                    <th>Nama</th>
                                    <th>J.Kelamin</th>
                                    <th>Tmp.Lahir</th>
                                    <th>Tgl.Lahir</th>
                                    <th>Tgl Masuk</th>
                                    <th>Tgl Keluar</th>
                                </tr>
                            </tfoot>
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
<link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/datetimepicker.min.css">
@endpush

@push('js')
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('assets') }}/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/izitoast.min.js"></script>
<script src="{{ asset('assets') }}/js/index.var.js"></script>
<script src="{{ asset('assets') }}/js/select2.min.js"></script>
<script src="{{ asset('assets') }}/js/sweetalert2.all.min.js"></script>
<script src="{{ asset('assets') }}/js/datetimepicker.full.min.js"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).ready(function() {
        // $('#dt-pasien tfoot th#noreg').each( function () {
        //     var title = $(this).text();
        //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        // } );
        
        // $('#dt-pasien thead th#noreg')
        // .clone(true)
        // .addClass('filters')
        // .appendTo('#dt-pasien thead');

        $('#dt-pasien thead th#noreg').each( function () {
            var title = $(this).text();
            $(this).html( '<input class="form-control form-control-sm" type="text" placeholder="Search '+title+'" />' );
        });

        $('#dt-pasien thead th#medrec').each( function () {
            var title = $(this).text();
            $(this).html( '<input class="form-control form-control-sm" type="text" placeholder="Search '+title+'" />' );
        });
        var table = $('#dt-pasien').DataTable({
            language: {
                url: "{{ asset('config.json') }}"
            },
            // dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
            // "<'row'<'col-xs-12't>>"+
            // "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('pasien.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'MedicalNo', name: 'MedicalNo'},
                {data: 'RegistrationNo', name: 'RegistrationNo'},
                {data: 'patient.PatientName', name: 'patient.PatientName',orderable: false, searchable: false},
                {data: 'patient.GCSex', name: 'patient.GCSex',orderable: false, searchable: false},
                {data: 'patient.CityOfBirth', name: 'patient.CityOfBirth',orderable: false, searchable: false},
                {data: 'patient.DateOfBirth', name: 'patient.DateOfBirth',orderable: false, searchable: false},
                {data: 'RegistrationDateTime', name: 'registration.RegistrationDateTime',orderable: false, searchable: false},
                {data: 'DischargeDateTime', name: "registration.DischargeDateTime",orderable: false, searchable: false}
            ],
            initComplete: function () {
                // Apply the search
                this.api().columns([2,3]).every( function () {
                    var that = this;
    
                    $( 'input', this.header() ).on( 'keyup clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            },
            deferRender: true,
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:   [1]
            } ],
            order: [ 1, 'asc' ]
        });

        $('body').on('click', '.proses-pasien', function(){ 
          var medrec = $(this).data('medrec');
          console.log(medrec);
        });

        $('#jenis_infeksi_rs').datetimepicker({
          timepicker: false,
          format: 'Y-m-d'
        });
    });

</script>
@endpush