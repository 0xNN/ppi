<div class="modal fade" id="addUtamaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="form-tambah-edit" name="form-tambah-edit">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="id">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <input type="text" name="nama_antibiotik" id="nama_antibiotik" class="form-control" placeholder="Nama Antibiotik">
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Jumlah">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <input type="text" name="tanggal_awal" id="tanggal_awal" class="form-control" placeholder="Tanggal Awal" readonly>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <input type="text" name="tanggal_akhir" id="tanggal_akhir" class="form-control" placeholder="Tanggal Akhir" readonly>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <select name="kategori_antibiotik_id" id="kategori_antibiotik_id" class="form-control">
              @foreach ($kategori_antibiotiks as $item)
                <option value="{{ $item->id }}">{{ $item->nama_kategori_antibiotik }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active">
              <label class="form-check-label" for="is_active">
                Aktif ?
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-success" id="tombol-simpan" value="create">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteUtamaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda Yakin Akan di Hapus?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" name="tombol-utama-hapus" id="tombol-utama-hapus" class="btn btn-danger">Hapus</button>
      </div>
    </div>
  </div>
</div>