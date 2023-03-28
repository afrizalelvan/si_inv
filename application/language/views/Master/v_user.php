

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
              <th style="width:25%">Username</th>
              <th style="width:20%">Nama User</th>
              <th style="width:20%">Password</th>
              <th style="width:20%">Level</th>
              <th style="width:20%">Store</th>
              <th style="width:20%">Status</th>
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
          <label class="col-sm-2 col-form-label">Nama User</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" id="id" >
            <input type="text" class="form-control" id="nm_user" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">username</label>
          <div class="col-sm-10">
            <input type="text" class="form-control"  id="username" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
            <input type="password" class="form-control"  id="password" placeholder="Masukan..">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Level</label>
          <div class="col-sm-10">
            <select class="form-control" id="level" name="level">
              <option value="">Pilih</option>
              <option value="HO">HO</option>
              <option value="Gudang">Gudang</option>
              <option value="Kasir">Kasir</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Store</label>
          <div class="col-sm-10">
            <select class="form-control" id="store" name="store">
              <option value="">Pilih</option>
              <?php foreach ($store as $r): ?>
                <option value="<?= $r->id_toko.'-'.$r->nm_toko ?>"><?= $r->nm_toko ?></option>
              <?php endforeach ?>
            </select>
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


  $('#username').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
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
        "url": '<?php echo base_url(); ?>Master/load_data/User',
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
     id = $("#id").val();
     nm_user = $("#nm_user").val();
     password = $("#password").val();
     level = $("#level").val();
     store = $("#store").val();
     s_aktif = $("#s_aktif").val();
     username = $("#username").val();

     $("#store").prop('disabled',false);
     if ((level != 'HO') && store =='') {
        toastr.info('Harap Lengkapi Form'); 
        return;
     }

     if (nm_user == '' ||s_aktif == '' || level == '' || password == '') {
      toastr.info('Harap Lengkapi Form'); 
      return
     }


      $.ajax({
          url      : '<?php echo base_url(); ?>/master/insert/'+status,
          type: "POST",
          data     : ({id,nm_user,password,username,level,store,s_aktif,jenis:'tb_user',status}),
          dataType: "JSON",
          success: function(data)
          {           
              if (data) {
                toastr.success('Berhasil Disimpan'); 
                kosong();
                $("#modalForm").modal("hide");
              }else{
                toastr.error('Gagal Simpan atau username sudah tersedia'); 
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
     $("#nm_user").val('');
     $("#password").val('');
     $("#level").val('');
     $("#store").val('');
     $("#s_aktif").val('Aktif');
     $("#username").val('');
     status = 'insert';
     $("#btn-simpan").show();
     $("#username").prop('readonly',false);
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
              data: {id: id,jenis : "tb_user",field:'username'},
              dataType: "JSON",
          })
          .done(function(data) {
              
              $("#id").val(data.id);
              $("#nm_user").val(data.nm_user);
              $("#password").val(atob(data.password));
              $("#level").val(data.level);
              $(`#store option[value='${data.id_toko}-${data.nm_toko}']`).prop('selected', true);
              
              $("#s_aktif").val(data.s_aktif);
              $("#username").val(data.username);


              $("#username").prop('readonly',true);

              if (data.level == 'GudangCabang' || data.level == 'Kasir') {
                $("#store").prop('disabled',false)
              }else{
                $("#store").prop('disabled',true)
              }
          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Master/hapus',
        data  : ({id,jenis:'tb_user',field:'username'}),
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

  $( "#level" ).change(function() {
    var level = $( this ).val();

    $("#store").val('')

    if (level != 'HO') {
      $("#store").prop('disabled',false)
       
    }else{
      $("#store").val('')
      $("#store").prop('disabled',true)
    }
  });
  
</script>