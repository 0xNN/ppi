@extends('layouts.app', ['title' => __('Pasien')])

@section('content')
  @include('pasien.partials.header', [
      'title' => __('Data Pasien'),
      'description' => __(''),
      'class' => 'col-lg-12'
  ])
  @include('pasien.admin.modal')
  <div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-4 order-xl-1">
          @include('pasien.admin.data-pasien')
        </div>
        <div class="col-xl-8 order-xl-1">
          @include('pasien.admin.data-ppi')
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
{{-- <link rel="stylesheet" href="{{ asset('assets') }}/css/select2-bootstrap.min.css"> --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css"> --}}
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

    $(document).ready(function () {
      $('#dt-total-ppi').DataTable({
        language: {
          url: "{{ asset('config.json') }}"
        },
        searching: false,
        bInfo: false
      });
      if ($("#form-tambah-edit").length > 0) {
            $("#form-tambah-edit").validate({
                submitHandler: function (form) {
                    var actionType = $('#tombol-simpan').val();
                    var form = $('#form-tambah-edit')[0];
                    var data = new FormData(form);
                    $('#tombol-simpan').html('Sending..');
                    $.ajax({
                        // data: $('#form-tambah-edit').serialize(), 
                        data: data,
                        enctype: 'multipart/form-data',
                        url: "{{ route('pasien.store') }}", //url simpan data
                        type: "POST", //karena simpan kita pakai method POST
                        // dataType: 'json', //data tipe kita kirim berupa JSON
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 600000,
                        success: function (data) { //jika berhasil
                            console.log(data);
                            if(data.success == false) {
                              Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: data.message,
                              });
                              $('#tombol-simpan').html('Simpan');
                            } else {
                              $('#form-tambah-edit').trigger("reset"); //form reset
                              $('#tindakan_operasi_id').prop('disabled', true);
                              $('#jenis_operasi_id').prop('disabled', true);
                              $('#lama_operasi_id').prop('disabled', true);
                              $('#asa_score_id').prop('disabled', true);
                              $('#risk_score_id').prop('disabled', true);
                              $('#tombol-simpan').html('Simpan'); //tombol simpan
                              $('select').val("").trigger('change');
                              iziToast.success({ //tampilkan iziToast dengan notif data berhasil disimpan pada posisi kanan bawah
                                  title: 'Successfully',
                                  message: 'Berhasil menambah data',
                                  position: 'bottomRight'
                              });
                              location.reload();
                            }
                        },
                        error: function (data) { //jika error tampilkan error pada console
                            console.log('Error:', data);
                            $('#tombol-simpan').html('Simpan');
                        }
                    });
                }
            })
      }
    });

    $(document).ready(function () {

      $('body').on('click', '#tombol-simpan-kategori', function() {
        Swal.fire({
          title: 'Apakah anda yakin ingin menyimpan data ini?',
          showCancelButton: true,
          confirmButtonText: 'Save',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            var no_rm = $('#no_rm_tmp').val();
            var form = $('#form-antibiotik-tmp').length;
            console.log(form > 0);
            var actionType = $('#tombol-simpan-kategori').val();
            $('#tombol-simpan-kategori').html('Sending..');
            $.ajax({
                data: $('#form-antibiotik-tmp')
                    .serialize(), //function yang dipakai agar value pada form-control seperti input, textarea, select dll dapat digunakan pada URL query string ketika melakukan ajax request
                url: "{{ route('master.antibiotik.simpan-antibiotik-tmp') }}", //url simpan data
                type: "POST", //karena simpan kita pakai method POST
                dataType: 'json', //data tipe kita kirim berupa JSON
                success: function (data) { //jika berhasil
                    console.log(data);
                    if(data.success == false) {
                      Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: data.message,
                      });
                    } else {
                      // $('#form-antibiotik-tmp').trigger("reset"); //form reset
                      $('#addModalAntibiotik').modal('hide');
                      $('#tombol-simpan-kategori').html('Simpan'); //tombol simpan
                      iziToast.success({ //tampilkan iziToast dengan notif data berhasil disimpan pada posisi kanan bawah
                          title: 'Successfully',
                          message: 'Berhasil menambah data',
                          position: 'bottomRight'
                      });
                      // location.reload();
                    }
                },
                error: function (data) { //jika error tampilkan error pada console
                    console.log('Error:', data);
                    $('#tombol-simpan-kategori').html('Simpan');
                }
            });
          }
        })
      });
    });

    $(document).ready(function () {
      $('#reset_kategori').click(function () {
        console.log('tes');
        Swal.fire({
          title: 'Konfirmasi Reset?',
          text: 'Mereset akan menghapus kategori antibiotik yang sudah dipilih?',
          showCancelButton: true,
          confirmButtonText: 'OK',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            var dataId = $('#no_rm').val();
            var url = "{{ route('master.antibiotik.reset-antibiotik-tmp', ":dataId") }}";
            url = url.replace(':dataId', dataId);
            $.ajax({
              url: url, //eksekusi ajax ke url ini
              type: 'delete',
              beforeSend: function () {
                  $('#reset_kategori').text('Sedang mereset...'); //set text untuk tombol hapus
              },
              success: function (data) { //jika sukses
                  $('select#antibiotik').val(null).trigger('change');
                  $('#reset_kategori').text('Reset');
                  iziToast.warning({ //tampilkan izitoast warning
                      title: 'Successfully',
                      message: 'Berhasil mereset data',
                      position: 'bottomRight'
                  });
              }
            })
          }
        });
      });

      $('#pilih_kategori').click(function() {
        var length = $('#antibiotik').val().length;
        var data = JSON.stringify($('#antibiotik').val());
        var no_rm = $('#no_rm').val();
        if(length === 0) {
          Swal.fire({
            icon: 'error',
            title: 'Antibiotik belum dipilih!'
          });
        } else {
          if(no_rm == "-") {
            Swal.fire({
              icon: 'error',
              title: 'No RM Registration ini tidak ada di Data Pasien!'
            });
          } else {
            $.ajax({
              data: {
                data: data,
                no_rm: no_rm,
              },
              url: "{{ route('master.antibiotik.simpan-antibiotik-tmp') }}", //url simpan data
              type: "POST", //karena simpan kita pakai method POST
              dataType: 'json', //data tipe kita kirim berupa JSON
              success: function (data) {
                console.log(data);
                $('#addModalAntibiotik').modal('show');
                $('#addModalAntibiotik').on('shown.bs.modal', function() {
                  var template = $(this).html();
                  $(this).html(template);
                  $('#addModalAntibiotik').find('.modal-body').html(data.table);
                });
              }
            });
          }
        }
      });
    });
    
    $('#ruang_id').select2({
      placeholder: '-- Pilih Ruang --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-ruang') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#diagnosa').select2({
      placeholder: '-- Pilih Diagnosa --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-diagnosa') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#tindakan_operasi_id').select2({
      placeholder: '-- Pilih Tindakan Operasi --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-tindakan-operasi') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#jenis_operasi_id').select2({
      placeholder: '-- Pilih Jenis Operasi --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-jenis-operasi') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#lama_operasi_id').select2({
      placeholder: '-- Pilih Lama Operasi --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-lama-operasi') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#asa_score_id').select2({
      placeholder: '-- Pilih Asa Score --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-asa-score') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#risk_score_id').select2({
      placeholder: '-- Pilih Risk Score --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-risk-score') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#alat_digunakan_id').select2({
      placeholder: '-- Pilih Alat Digunakan --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-alat-digunakan') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term, // search term
            no_rm: $('#no_rm').val()
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#kegiatan_sensus_id').select2({
      placeholder: '-- Pilih Kegiatan Sensus --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-kegiatan-sensus') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    // $('#jenis_kumen').select2({
    //   placeholder: '-- Pilih Jenis Kuman --',
    //   // theme: 'bootstrap4',
    //   ajax: {
    //     url: "{{ route('pasien.data-jenis-kuman') }}",
    //     type: "post",
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //       return {
    //         search: params.term // search term
    //       };
    //     },
    //     processResults: function (response) {
    //       return {
    //         results: response
    //       };
    //     },
    //     cache: true
    //   },
    //   allowClear: true
    // });

    $('#antibiotik').select2({
      placeholder: '-- Pilih Antibiotik --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-antibiotik') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    $('#transmisi_id').select2({
      placeholder: '-- Pilih Transmisi --',
      // theme: 'bootstrap4',
      ajax: {
        url: "{{ route('pasien.data-transmisi') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term // search term
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      },
      allowClear: true
    });

    // $('#infeksi_rs_lain').select2({
    //   placeholder: '-- Pilih Infeksi RS Lain --',
    //   // theme: 'bootstrap4',
    //   ajax: {
    //     url: "{{ route('pasien.data-infeksi-rs-lain') }}",
    //     type: "post",
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //       return {
    //         search: params.term // search term
    //       };
    //     },
    //     processResults: function (response) {
    //       return {
    //         results: response
    //       };
    //     },
    //     cache: true
    //   },
    //   allowClear: true
    // });

    $('#alat_digunakan_id').change(function() {
      var data = $(this).val();
      var no_rm = $('#no_rm').val();
      $.ajax({
        data: {
          data: data,
          no_rm: no_rm,
        },
        url: "{{ route('pasien.data-alat-digunakan') }}", //url
        type: "POST", //karena simpan kita pakai method POST
        dataType: 'json', //data tipe kita kirim berupa JSON
        success: function (data) {
          if(data.code == 203) {
            Swal.fire({
              icon: 'error',
              title: data.message
            });
          }
        }
      });
    });

    $('#is_operasi').change(function() {
      if(this.checked) {
        $('#jenis_operasi_id').addClass('required').prop('disabled', false);
        $('#lama_operasi_id').addClass('required').prop('disabled', false);
        $('#asa_score_id').addClass('required').prop('disabled', false);
        $('#risk_score_id').addClass('required').prop('disabled', false);
        $('#tindakan_operasi_id').addClass('required').prop('disabled', false);
      } else {
        $('#jenis_operasi_id').removeClass('required').prop('disabled', true);
        $('#lama_operasi_id').removeClass('required').prop('disabled', true);
        $('#asa_score_id').removeClass('required').prop('disabled', true);
        $('#risk_score_id').removeClass('required').prop('disabled', true);
        $('#tindakan_operasi_id').removeClass('required').prop('disabled', true);
      }
    });

    $('#jenis_infeksi_rs_vap').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_hap').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_isk').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_iadp').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_ido').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_pleb').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_tirah_baring').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_vap_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_hap_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_isk_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_iadp_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_ido_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_pleb_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#jenis_infeksi_rs_tirah_baring_lain').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#tgl_keluar').datetimepicker({
      timepicker: false,
      format: 'Y-m-d',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#tgl_sensus').datetimepicker({
      format: 'Y-m-d H:i:s',
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#tgl_rontgen').datetimepicker({
      format: 'Y-m-d',
      timepicker: false,
      minDate: $('#tgl_masuk').val(),
      mask: true
    });

    $('#tgl_infeksi_kuman').datetimepicker({
      format: 'Y-m-d',
      timepicker: false,
      minDate: $('#tgl_masuk').val(),
      mask: true
    });
</script>
@endpush