<div class="card bg-secondary shadow">
  <div class="card-header bg-white border-0">
    <div class="font-italic">{{ __('Input PPI') }}</div>
  </div>
  <div class="card-body">
    <form name="form-tambah-edit" id="form-tambah-edit">
      <input type="hidden" name="id" id="id">
      <input type="hidden" name="no_registration" id="no_registration" value="{{ $registration->RegistrationNo }}">
      <input type="hidden" name="no_rm" id="no_rm" value="{{ $patient == null ? "-": $patient->MedicalNo }}">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="ruang_id">Ruang (*)</label>
            <select name="ruang_id" id="ruang_id" class="form-control form-control-sm">
              <option value="{{ $registration->service_room->RoomID }}" selected>{{ $registration->service_room->RoomName }}</option>
            </select>
          </div>
          <div class="form-group">
            <label for="tgl_masuk">Tgl Masuk</label>
            <input type="text" id="tgl_masuk" name="tgl_masuk" class="form-control form-control-sm" value="{{ Carbon\Carbon::parse($registration->RegistrationDateTime)->format('Y-m-d') }}" readonly>
          </div>
          <div class="form-group">
            <label for="tgl_keluar">Tgl Keluar</label>
            <input type="text" id="tgl_keluar" name="tgl_keluar" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="diagnosa">Diagnosa</label>
            <select name="diagnosa[]" id="diagnosa" class="form-control form-control-sm" multiple="multiple"></select>
          </div>
          <div class="form-group">
            <label>Operasi</label>
            <div class="form-check">
              <input type="checkbox" name="is_operasi" id="is_operasi" value="ya" class="form-check-input">
              <label for="is_operasi" class="form-check-label">Ya</label>
            </div>
          </div>
          <div class="form-group">
            <label for="tgl_operasi">Tanggal Operasi</label>
            <input type="text" id="tgl_operasi" name="tgl_operasi" class="form-control form-control-sm" disabled>
          </div>
          <div class="form-group">
            <label for="tindakan_operasi_id">Tindakan Operasi</label>
            <select name="tindakan_operasi_id" id="tindakan_operasi_id" class="form-control form-control-sm" disabled></select>
          </div>
          <div class="form-group">
            <label for="jenis_operasi_id">Jenis Operasi</label>
            <select name="jenis_operasi_id" id="jenis_operasi_id" class="form-control form-control-sm" disabled></select>
          </div>
          <div class="form-group">
            <label for="lama_operasi_id">Lama Operasi</label>
            <select name="lama_operasi_id" id="lama_operasi_id" class="form-control form-control-sm" disabled></select>
          </div>
          <div class="form-group">
            <label for="asa_score_id">ASA Score</label>
            <select name="asa_score_id" id="asa_score_id" class="form-control form-control-sm" disabled></select>
          </div>
          <div class="form-group">
            <label for="risk_score_id">Risk Score</label>
            <select name="risk_score_id" id="risk_score_id" class="form-control form-control-sm" disabled></select>
          </div>
          <div class="form-group">
            <label for="tgl_sensus">Tgl Sensus</label>
            <input type="text" id="tgl_sensus" name="tgl_sensus" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="alat_digunakan_id">Alat yang Digunakan</label>
            <select name="alat_digunakan_id[]" id="alat_digunakan_id" class="form-control form-control-sm" multiple="multiple"></select>
          </div>
          <div class="form-group">
            <label for="kegiatan_sensus_id">Kegiatan Sensus</label>
            <select name="kegiatan_sensus_id" id="kegiatan_sensus_id" class="form-control form-control-sm"></select>
          </div>
          <div class="form-group">
            <label for="hasil_rontgen">Hasil Rontgen</label>
            <textarea name="hasil_rontgen" id="hasil_rontgen" class="form-control form-control-sm" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="foto_hasil_rontgen">Foto Hasil Rontgen</label>
            <input type="file" class="form-control-file form-control-sm" id="foto_hasil_rontgen" name="foto_hasil_rontgen[]" multiple="multiple" accept="image/*">
          </div>
          <div class="form-group">
            <label for="tgl_rontgen">Tgl Rontgen</label>
            <input type="text" id="tgl_rontgen" name="tgl_rontgen" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="jenis_infeksi_rs">Jenis Infeksi Rumah Sakit</label>
            <table class="table table-responsive table-sm">
              {{-- @foreach ($jenis_infeksi_rs as $item) --}}
              <tr>
                <th>VAP</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_vap" id="jenis_infeksi_rs_vap" type="text"></th>
              </tr>
              <tr>
                <th>HAP</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_hap" id="jenis_infeksi_rs_hap" type="text"></th>
              </tr>
              <tr>
                <th>ISK</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_isk" id="jenis_infeksi_rs_isk" type="text"></th>
              </tr>
              <tr>
                <th>IADP</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_iadp" id="jenis_infeksi_rs_iadp" type="text"></th>
              </tr>
              <tr>
                <th>IDO</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_ido" id="jenis_infeksi_rs_ido" type="text"></th>
              </tr>
              <tr>
                <th>PLEB</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_pleb" id="jenis_infeksi_rs_pleb" type="text"></th>
              </tr>
              <tr>
                <th>TIRAH BARING</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_tirah_baring" id="jenis_infeksi_rs_tirah_baring" type="text"></th>
              </tr>
              {{-- @endforeach --}}
            </table>
          </div>
          {{-- <div class="form-group">
            <label for="jenis_kumen">Jenis Kuman</label>
            <select name="jenis_kumen[]" id="jenis_kumen" class="form-control form-control-sm" multiple="multiple"></select>
          </div> --}}
          <div class="form-group">
            <label for="jenis_kumen">Jenis Kuman</label>
            <input type="text" name="jenis_kumen" id="jenis_kumen" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="tgl_infeksi_kuman">Tgl Terinfeksi Kuman</label>
            <input type="text" id="tgl_infeksi_kuman" name="tgl_infeksi_kuman" class="form-control form-control-sm">
          </div>
          <div class="form-group">
            <label for="jenis_kultur_pendukun_hais">Jenis Kultur Pendukung HAIs</label>
            <div class="row">
              <div class="col">
                <div class="form-check">
                  <input type="checkbox" name="darah" id="darah" value="darah" class="form-check-input">
                  <label for="darah" class="form-check-label">Darah</label>
                </div>
              </div>
              <div class="col">
                <div class="form-check">
                  <input type="checkbox" name="sputurn" id="sputurn" value="sputurn" class="form-check-input">
                  <label for="sputurn" class="form-check-label">Sputum</label>
                </div>
              </div>
              <div class="col">
                <div class="form-check">
                  <input type="checkbox" name="swab_luka" id="swab_luka" value="swab_luka" class="form-check-input">
                  <label for="swab_luka" class="form-check-label">Swab Luka</label>
                </div>
              </div>
              <div class="col">
                <div class="form-check">
                  <input type="checkbox" name="urine" id="urine" value="urine" class="form-check-input">
                  <label for="urine" class="form-check-label">Urine</label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="antibiotik">Antibiotik</label>
            <select name="antibiotik[]" id="antibiotik" class="form-control form-control-sm" multiple="multiple"></select>
            <div class="form-group mt-2">
              <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary shadow-sm" name="pilih_kategori" id="pilih_kategori">Pilih Kategori</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger shadow-sm" name="reset_kategori" id="reset_kategori">Reset</a>
            </div>
          </div>
          <div class="form-group">
            <label for="transmisi_id">Transmisi</label>
            <select name="transmisi_id" id="transmisi_id" class="form-control form-control-sm"></select>
          </div>
          {{-- <div class="form-group">
            <label for="infeksi_rs_lain">Infeksi RS Lain</label>
            <select name="infeksi_rs_lain[]" id="infeksi_rs_lain" class="form-control form-control-sm" multiple="multiple"></select>
          </div> --}}
          <div class="form-group">
            <label for="jenis_infeksi_rs">Infeksi RS Lain</label>
            <table class="table table-responsive table-sm">
              {{-- @foreach ($jenis_infeksi_rs as $item) --}}
              <tr>
                <th>VAP</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_vap_lain" id="jenis_infeksi_rs_vap_lain" type="text"></th>
              </tr>
              <tr>
                <th>HAP</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_hap_lain" id="jenis_infeksi_rs_hap_lain" type="text"></th>
              </tr>
              <tr>
                <th>ISK</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_isk_lain" id="jenis_infeksi_rs_isk_lain" type="text"></th>
              </tr>
              <tr>
                <th>IADP</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_iadp_lain" id="jenis_infeksi_rs_iadp_lain" type="text"></th>
              </tr>
              <tr>
                <th>IDO</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_ido_lain" id="jenis_infeksi_rs_ido_lain" type="text"></th>
              </tr>
              <tr>
                <th>PLEB</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_pleb_lain" id="jenis_infeksi_rs_pleb_lain" type="text"></th>
              </tr>
              {{-- <tr>
                <th>TIRAH BARING</th>
                <th><input class="form-control form-control-sm" name="jenis_infeksi_rs_tirah_baring_lain" id="jenis_infeksi_rs_tirah_baring_lain" type="text"></th>
              </tr> --}}
              {{-- @endforeach --}}
            </table>
          </div>
          <div class="form-group">
            <button type="button" class="btn btn-sm btn-info" id="validasi-simpan" value="validasi"><i class="fas fa-info"></i> Validasi</button>
            <button type="submit" class="btn btn-sm btn-success" id="tombol-simpan" value="create"><i class="fas fa-save"></i> Simpan</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>