

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Master </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active" ><a href="#"><?= $judul ?></a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $judul ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body">

          <button type="button" class="tambah_data btn  btn-outline-primary pull-right" >Tambah Data</button>
          <br><br>

         

          <table id="datatable" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th style="width:5%">ID</th>
              <th style="width:15%">Nama pelanggan</th>
              <th style="width:10%">No Telepon</th>
              <th style="width:22%">Alamat</th>
              <th style="width:15%">Jenis Kelamin</th>
              <th style="width:15%">TTL</th>
              <th style="width:15%">Status</th>
              <th style="width:10%">Aksi</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<div class="modal fade" id="modalForm">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="judul"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" method="post" id="myForm">
        
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Nama pelanggan</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" id="id_pelanggan" >
            <input type="text" class="form-control" id="nm_pelanggan" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
          <div class="col-sm-10">
            <select class="form-control" id="jk" name="jk">
              <option value="">Pilih</option>
              <option value="Laki - laki">Laki - laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Alamat</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="alamat" placeholder="Masukan.."></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">No Telepon</label>
          <div class="col-sm-10">
            <input type="text" class="angka form-control"  id="no_telp" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Tempat Lahir</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  id="tempat_lahir" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">TTL</label>
          <div class="col-sm-10">
            <input type="date" class="form-control"  id="ttl" placeholder="Masukan..">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Status</label>
          <div class="col-sm-10">
            <select class="form-control" id="s_aktif" name="s_aktif">
              <option value="Aktif">Aktif</option>
              <option value="Non Aktif">Non Aktif</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" id="btn-simpan" onclick="simpan()">Simpan</button>
      </div>
      </form>
        <input type="hidden" name="bucket" id="bucket" value="0">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
  rowNum = 0;
  $(document).ready(function () {
     load_data();
  });

  status ="insert";
  $(".tambah_data").click(function(event) {
    kosong();
    $("#modalForm").modal("show");
    $("#judul").html('<h3> Form Tambah Data</h3>');
    status = "insert";
  });


/* $('.tambah_data').click(function() {
      toastr.success('Berhasil');
    });*/

  function load_data() {
    

    var table = $('#datatable').DataTable();

    table.destroy();

    tabel = $('#datatable').DataTable({

      "ordering": false,
      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Master/load_data/pelanggan',
        "type": "POST",
        // data  : ({tanggal:tanggal,tanggal_akhir:tanggal_akhir,id_kategori:id_kategori1,id_sub_kategori:id_sub_kategori1}),
      },
      responsive: true,
      "pageLength": 25,
      "language": {
        "emptyTable": "Tidak ada data.."
      }
    });

  }

  function reloadTable() {
    table = $('#datatable').DataTable();
    tabel.ajax.reload(null, false);
  }

  function simpan(){
     id_pelanggan = $("#id_pelanggan").val();
     nm_pelanggan = $("#nm_pelanggan").val();
     no_telp = $("#no_telp").val();
     s_aktif = $("#s_aktif").val();
     jk = $("#jk").val();
     tempat_lahir = $("#tempat_lahir").val();
     ttl = $("#ttl").val();
     alamat = $("textarea#alamat").val();

     if (nm_pelanggan == '' || tempat_lahir == '' || no_telp == '' || ttl == '' || jk == '' || s_aktif == '') {
      toastr.info('Harap Lengkapi Form'); return;
     }


      $.ajax({
          url      : '<?php echo base_url(); ?>/master/insert/'+status,
          type: "POST",
          data     : ({id_pelanggan,nm_pelanggan,no_telp,alamat,jk,tempat_lahir,ttl,s_aktif,jenis:'m_pelanggan',status}),
          dataType: "JSON",
          success: function(data)
          {           
              if (data) {
                toastr.success('Berhasil Disimpan'); 
                kosong();
                $("#modalForm").modal("hide");
              }else{
                toastr.error('Gagal Simpan'); 
              }
              reloadTable();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
             toastr.error('Terjadi Kesalahan'); 
          }
      });
     
  }

  function kosong(){
     $("#nm_pelanggan").val('');
     $("#no_telp").val('');
     $("textarea#alamat").val('');
     $("#jk").val('');
     $("#s_aktif").val('Aktif');
     $("#tempat_lahir").val('');
     $("#ttl").val('');
     status = 'insert';
     $("#btn-simpan").show();
  }


  function tampil_edit(id,act){
    kosong();
    status = 'update';
    $("#modalForm").modal("show");
    if (act =='detail') {
      $("#judul").html('<h3> Detail Data</h3>');
      $("#btn-simpan").hide();
    }else{
      $("#judul").html('<h3> Form Edit Data</h3>');
      $("#btn-simpan").show();
    }
    $("#jenis").val('Update');

    status = "update";

         $.ajax({
              url: '<?php echo base_url('Master/get_edit'); ?>',
              type: 'POST',
              data: {id: id,jenis : "m_pelanggan",field:'id_pelanggan'},
              dataType: "JSON",
          })
          .done(function(data) {
              $("#id_pelanggan").val(data.id_pelanggan);
              $("#nm_pelanggan").val(data.nm_pelanggan);
              $("#no_telp").val(data.no_telp);
              $("textarea#alamat").val(data.alamat);
              $("#jk").val(data.jk);
              $("#tempat_lahir").val(data.tempat_lahir);
              $("#ttl").val(data.ttl);
              $("#s_aktif").val(data.s_aktif);
          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Master/hapus',
        data  : ({id:id,jenis:'m_pelanggan',field:'id_pelanggan'}),
        type  : "POST",
        success : function(data){
          toastr.success('Data Berhasil Di Hapus'); 
          reloadTable();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           toastr.error('Terjadi Kesalahan'); 
        }
      });
    }
    
   
  }

  
</script>