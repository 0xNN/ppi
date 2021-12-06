<div class="card shadow-lg">
  <div class="card-header">
    <small class="text-danger font-italic"><b>Note: </b>Jika anda men-ceklis keduanya dari setiap Antibiotik maka sistem secara otomatis akan menyimpan Antibiotik tersebut dengan 2 kategori (profilaksis & terapi). Jika anda hanya ingin satu kategori dari setiap Antibiotik hendaknya jangan men-ceklis keduanya.</small>
  </div>
  <div class="card-body">
    <table class="table table-sm table-bordered">
      <tr>
        <th class="align-middle">Medical Record</th>
        <th><input type="text" class="form-control form-control-sm" name="no_rm_tmp" id="no_rm_tmp" value="{{ $antibiotik_tmp[0]->no_rm }}" aria-describedby="btnGroupAddon" readonly></th>
      </tr>
    </table>
    <input type="hidden" name="is_input_kategori" value="ok">
    <table class="mt-2 table table-sm table-bordered">
      <thead class="bg-indigo text-white">
        <tr>
          <th>Nama Antibiotik</th>
          <th>Kategori</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($antibiotik_tmp as $item)
        <tr>
          <td>{{ $item->nama_antibiotik }}</td>
          <td>
            <div class="form-check">
              <input class="form-check-input" type="radio" value="profilaksis" name="kategori_antibiotik_{{ $item->no_rm }}_{{ $item->antibiotik_id }}" id="profilaksis_{{ $item->no_rm }}_{{ $item->antibiotik_id }}" {{ $item->kategori == null ? '': ($item->kategori == 'profilaksis' ? 'checked': '') }}>
              <label class="form-check-label" for="profilaksis_{{ $item->no_rm }}_{{ $item->antibiotik_id }}">
                Profilaksis
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" value="terapi" name="kategori_antibiotik_{{ $item->no_rm }}_{{ $item->antibiotik_id }}" id="terapi_{{ $item->no_rm }}_{{ $item->antibiotik_id }}" {{ $item->kategori == null ? '': ($item->kategori == 'terapi' ? 'checked': '') }}>
              <label class="form-check-label" for="terapi_{{ $item->no_rm }}_{{ $item->antibiotik_id }}">
                Terapi
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="keduanya" name="kategori_antibiotik_{{ $item->no_rm }}_{{ $item->antibiotik_id }}_keduanya" id="keduanya_{{ $item->no_rm }}_{{ $item->antibiotik_id }}" {{ $item->keduanya == null ? '': ($item->keduanya == 1 ? 'checked': '') }}>
              <label class="form-check-label" for="keduanya_{{ $item->no_rm }}_{{ $item->antibiotik_id }}">
                Keduanya (Profilaksis & Terapi)
              </label>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>