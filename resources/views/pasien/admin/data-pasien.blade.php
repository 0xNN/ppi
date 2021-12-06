<div class="card bg-secondary shadow">
  <div class="card-header bg-white border-0">
    <div class="font-italic">{{ __('Data Pasien') }}</div>
    {{-- <a href="javascript:void(0)" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
        <i class="fas fa-plus"></i>
    </a> --}}
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-4"></div>
      <div class="col-4">
        <div class="text-center">
          <img class="img-fluid mx-auto" src="{{ asset('assets') }}/img/user-healthy.png" alt="">
        </div>
      </div>
      <div class="col-4"></div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="text-center font-weight-bold">
          {{ $patient == null ? "-": $patient->PatientName }}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="text-center font-weight-bold">
          <small>No.RM : {{ $patient == null ? "-": $patient->MedicalNo }}</small>
        </div>
      </div>
    </div>

    <div class="row pt-4">
      <div class="col-sm-12 col-xl-12">
        <div class="table-responsive">
          <table class="table table-sm table-bordered w-100 d-block d-md-table">
            <tr>
              <th>No Register</th>
              <th class="text-success">{{ $registration->RegistrationNo }}</th>
            </tr>
            <tr>
              <th>Jenis Kelamin</th>
              <th class="text-success">{{ $patient == null ? "-": ($patient->GCSex == '0001^F' ? 'Perempuan': 'Laki-Laki') }}</th>
            </tr>
            <tr>
              <th>Umur</th>
              <th class="text-success">{{ $patient == null ? "-": Carbon\Carbon::parse($patient->DateOfBirth)->age }}</th>
            </tr>
            <tr>
              <th>Tanggal Lahir</th>
              <th class="text-success">{{ $patient == null ? "-": Carbon\Carbon::parse($patient->DateOfBirth)->isoFormat('D MMMM Y') }}</th>
            </tr>
            <tr>
              <th>Tempat Lahir</th>
              <th class="text-success">{{ $patient == null ? "-": $patient->CityOfBirth }}</th>
            </tr>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="card bg-secondary shadow mt-4">
  <div class="card-header bg-white border-0">
    <div class="font-italic">{{ __('Data Input PPI') }}</div>
    {{-- <a href="javascript:void(0)" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" id="tombol-utama">
        <i class="fas fa-plus"></i>
    </a> --}}
  </div>
  <div class="card-body">
      <div class="card card-stats mb-4 mb-xl-0">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total PPI</h5>
                    <span class="h1 font-weight-bold mb-0">{{ $count_data_ppi_detail }}</span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive mt-2">
      <table id="dt-total-ppi" class="table table-sm table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>
            <th class="text-center">Tanggal Sensus</th>
          </tr>
        </thead>
        <tbody>
          @if($data_ppi_detail != null)
            @foreach ($data_ppi_detail as $item)
              <tr>
                {{-- <th class="text-center"><a class="btn btn-info btn-sm" href="{{ route('pasien.detail-with-id', ['id'=>$item->id]) }}">{{ $item->tgl_sensus }}</a></th> --}}
                <th class="text-center"><button class="btn btn-info btn-sm">{{ $item->tgl_sensus }}</button></th>
              </tr>
            @endforeach
          @else
            <tr>
              <th class="text-center"><div class="badge badge-danger">Tidak ada data PPI!</div></th>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>