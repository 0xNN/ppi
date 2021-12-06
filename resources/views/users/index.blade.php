@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('User Management'),
        'description' => __(''),
        'class' => 'col-lg-12'
    ])  
    @include('users.modal')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        {{ __('Data User') }}</h3>
                        <a href="javascript:void(0)" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dt-user-management" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
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
@endpush

@push('js')
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.min.js"></script>
<script src="{{ asset('assets') }}/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="{{ asset('assets') }}/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('assets') }}/dist/js/izitoast.min.js"></script>
<script src="{{ asset('assets') }}/js/index.var.js"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).ready(function() {
        var table = $('#dt-user-management').DataTable({
            language: {
                paginate: {
                    previous: "&lt;",
                    next: "&gt;",
                }
            },
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [ {
                className: 'dtr-control',
                orderable: false,
                targets:   0
            } ],
            order: [ 1, 'asc' ]
        });

        $('#tombol-utama').click(function () {
            $('#button-simpan').val("create-post"); //valuenya menjadi create-post
            $('#id').val(''); //valuenya menjadi kosong
            $('#form-tambah-edit').trigger("reset"); //mereset semua input dll didalamnya
            $('#modal-judul').html("Tambah User Baru"); 
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
                        url: "{{ route('user.store') }}", //url simpan data
                        type: "POST", //karena simpan kita pakai method POST
                        dataType: 'json', //data tipe kita kirim berupa JSON
                        success: function (data) { //jika berhasil
                            $('#form-tambah-edit').trigger("reset"); //form reset
                            $('#addUtamaModal').modal('hide'); //modal hide
                            $('#tombol-simpan').html('Simpan'); //tombol simpan
                            var oTable = $('#dt-user-management').dataTable(); //inialisasi datatable
                            oTable.fnDraw(false); //reset datatable
                            iziToast.success({ //tampilkan iziToast dengan notif data berhasil disimpan pada posisi kanan bawah
                                title: 'Successfully',
                                message: 'Success',
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

        $(document).on('click', '.delete', function () {
            dataId = $(this).attr('id');
            $('#deleteUtamaModal').modal('show');
        });

        $('#tombol-utama-hapus').click(function () {
            var url = "{{ route('user.destroy', ":dataId") }}";
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
                    var oTable = $('#dt-user-management').dataTable();
                    oTable.fnDraw(false); //reset datatable
                });
                iziToast.warning({ //tampilkan izitoast warning
                    title: 'Successfully',
                    message: 'User berhasil dihapus',
                    position: 'bottomRight'
                });
            }
            })
        });
    });
</script>
@endpush
