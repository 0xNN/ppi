@extends('layouts.app', ['title' => __('Jenis Operasi')])

@section('content')
  @include('header.partials.header', [
      'title' => __('Jenis Operasi'),
      'description' => __('Data Jenis Operasi'),
      'class' => 'col-lg-12'
      ])

  @include('jenis-operasi.admin.modal')
  <div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="text-danger font-italic">{{ __('List Jenis Operasi!') }}</div>
                    <a href="javascript:void(0)" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dt-jenis-operasi" class="table table-sm table-bordered dt-responsive nowrap" style="width:100%">
                            <thead class="thead-info">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Aksi</th>
                                    <th>Jenis Operasi</th>
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
<link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/css/jquery.datetimepicker.min.css">
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
<script>
      $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).ready(function() {
        var table = $('#dt-jenis-operasi').DataTable({
            language: {
              language: {
                url: '{{ asset('config.json') }}'
              },
            },
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('master.jenis-operasi.r-jenis-operasi.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'id', name: 'id'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'nama_jenis_operasi', name: 'nama_jenis_operasi'},
                {data: 'created_at', name: 'created_at'}
            ],
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:   [1]
            } ],
            order: [ 1, 'asc' ]
        });

        $('#tanggal_awal').datetimepicker({
          timepicker: false,
          format: 'Y-m-d'
        });

        $('#tombol-utama').click(function () {
            $('#button-simpan').val("create-post"); //valuenya menjadi create-post
            $('#id').val(''); //valuenya menjadi kosong
            $('#form-tambah-edit').trigger("reset"); //mereset semua input dll didalamnya
            $('#modal-judul').html("Tambah Bank"); 
            $('#addUtamaModal').modal('show'); //modal tampil
        });

        if ($("#form-tambah-edit").length > 0) {
            $("#form-tambah-edit").validate({
                submitHandler: function (form) {
                    var actionType = $('#tombol-simpan').val();
                    $('#tombol-simpan').html('Sending..');
                    $.ajax({
                        data: $('#form-tambah-edit')
                            .serialize(), //function yang dipakai agar value pada form-control seperti input, textarea, select dll dapat digunakan pada URL query string ketika melakukan ajax request
                        url: "{{ route('master.jenis-operasi.r-jenis-operasi.store') }}", //url simpan data
                        type: "POST", //karena simpan kita pakai method POST
                        dataType: 'json', //data tipe kita kirim berupa JSON
                        success: function (data) { //jika berhasil
                            $('#form-tambah-edit').trigger("reset"); //form reset
                            $('#addUtamaModal').modal('hide'); //modal hide
                            $('#tombol-simpan').html('Simpan'); //tombol simpan
                            var oTable = $('#dt-jenis-operasi').dataTable(); //inialisasi datatable
                            oTable.fnDraw(false); //reset datatable
                            iziToast.success({ //tampilkan iziToast dengan notif data berhasil disimpan pada posisi kanan bawah
                                title: 'Successfully',
                                message: 'Berhasil menambah data',
                                position: 'bottomRight'
                            });
                        },
                        error: function (data) { //jika error tampilkan error pada console
                            console.log('Error:', data);
                            $('#tombol-simpan').html('Simpan');
                        }
                    });
                }
            })
        }

        $('body').on('click', '.edit-post', function () {
            var data_id = $(this).data('id');
            $.get('r-jenis-operasi/'+data_id + '/edit', function (data) {
                $('#modal-judul').html("Edit Post");
                $('#tombol-simpan').val("edit-post");
                $('#addUtamaModal').modal('show');
                $('#form-tambah-edit').trigger('reset');
                //set value masing-masing id berdriskrkan data yg diperoleh dari ajax get request diatas
                $('#id').val(data.id);
                $('#nama_jenis_operasi').val(data.nama_jenis_operasi);
            })
        });

        $(document).on('click', '.delete', function () {
            dataId = $(this).attr('id');
            $('#deleteUtamaModal').modal('show');
        });

        $('#tombol-utama-hapus').click(function () {
            var url = "{{ route('master.jenis-operasi.r-jenis-operasi.destroy', ":dataId") }}";
            url = url.replace(':dataId', dataId);
            $.ajax({
            url: url, //eksekusi ajax ke url ini
            type: 'delete',
            beforeSend: function () {
                $('#tombol-utama-hapus').text('Hapus Data'); //set text untuk tombol hapus
            },
            success: function (data) { //jika sukses
                setTimeout(function () {
                    $('#deleteUtamaModal').modal('hide'); //sembunyikan konfirmasi modal
                    var oTable = $('#dt-jenis-operasi').dataTable();
                    oTable.fnDraw(false); //reset datatable
                });
                iziToast.warning({ //tampilkan izitoast warning
                    title: 'Successfully',
                    message: 'Berhasil menghapus data',
                    position: 'bottomRight'
                });
            }
            })
        });
    });
</script>
@endpush