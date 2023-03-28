

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Transaksi </h1>
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
              <th style="width:15%">ID Produk</th>
              <th style="width:10%">Nama Produk</th>
              <th style="width:22%">Tanggal Mulai</th>
              <th style="width:15%">Tanggal Akhir</th>
              <th style="width:5%">Diskon</th>
              <th style="width:10%">Status</th>
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
          <label class="col-sm-2 col-form-label">Tanggal Awal</label>
          <div class="col-sm-4">
            <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" name="tgl_mulai" id="tgl_mulai" >
            <input type="hidden" class="form-control" value="tr_promosi" name="jenis" id="jenis" >
            <input type="hidden" class="form-control" value="" name="status" id="status" >
            <input type="hidden" class="form-control" value="" name="id_promosi" id="id_promosi" >
          </div>
          <div class="col-sm-2">
            Sampai
          </div>
          <div class="col-sm-4">
            <input type="date" class="form-control" value="<?= date('Y-m-d') ?>" name="tgl_akhir" id="tgl_akhir" >
          </div>
        </div>

        <div class="form-group row">
          <table class="table" id="table-produk" style="width: 70%" align="center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Diskon</th>
                </tr>
            </thead>
            <tbody>
              <tr id="itemRow0">
                  <td><a class="btn btn-danger" id="btn-hapus-0" onclick="removeRow(0)"><i class="fa fa-trash"></i> </a></td>
                  <td>1</td>
                  <td>
                    <select class="form-control select2" name="id_produk[0]" id="id_produk0" style="width: 100%;">
                      <option value="">Pilih</option>
                      <?php foreach ($produk as $r): ?>
                        <option value="<?= $r->id_produk."|".$r->nm_produk ?>"><?= $r->id_produk . " | ".$r->nm_produk ?></option>
                      <?php endforeach ?>
                    </select>

                      <!-- <input type="text" name="id_produk_edit[0]" id="id_produk_edit0" style="width: 300px" class="delRow form-control" onkeyup="cariproduk(0)" autocomplete="off"> -->
                  </td>
                  <td>
                      <input type="text" name="diskon[0]" id="diskon0" class="angka form-control" value='0' onkeyup="cek_diskon(0)" onchange="cek_diskon(0)">
                  </td>

                  
              </tr>
            </tbody>
          </table>   
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label"></label>
          <div class="col-sm-4">
            <button type="button" onclick="addRow()" class="btn-tambah-produk btn  btn-outline-primary">Tambah Produk</button>
          <input type="hidden" name="bucket" id="bucket" value="0">  
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
     $('.select2').select2();
  });

  status ="insert";
  $(".tambah_data").click(function(event) {
    kosong();
    $("#modalForm").modal("show");
    $("#judul").html('<h3> Form Tambah Data</h3>');
    status = "insert";
    $("#status").val("insert");
  });


/* $('.tambah_data').click(function() {
      toastr.success('Berhasil');
    });*/

  function load_data() {
    

    var table = $('#datatable').DataTable();

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/promosi',
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
     
     bucket = $('#bucket').val();

     var arr_batch=[];

     for (var e = 0; e <= bucket; e++) {
        id_produk = $('#id_produk'+e).val();
        diskon = $('#diskon'+e).val();

        if (id_produk == '' || diskon == '' || diskon == '0') {
          toastr.info('Harap Lengkapi Form');
          return;
        }

        var jj = arr_batch.findIndex(x => x.id_produk==id_produk);
         if(jj === -1){
              arr_batch.push({
                  id_produk : id_produk
              });
         }else{
              toastr.error('Ada Pemilihan Produk yg sama'); 
              return;
         }

         $('#id_produk0').prop("disabled",false);
     }

      $.ajax({
          url      : '<?php echo base_url(); ?>Transaksi/insert',
          type: "POST",
          data: $('#myForm').serialize(),
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
     $("#tgl_mulai").val("<?= date('Y-m-d') ?>");
     $("#tgl_akhir").val("<?= date('Y-m-d') ?>");
     $("#id_promosi").val("");
     status = 'insert';
     $("#btn-simpan").show();
     clearRow();
     $(".btn-tambah-produk").show();
     $("#btn-hapus-0").show();
     $('#id_produk0').prop("disabled",false);
  }


  function tampil_edit(id,act){
    kosong();
    $(".btn-tambah-produk").hide();
    $("#btn-hapus-0").hide();

    $("#status").val("update");
    status = 'update';
    $("#modalForm").modal("show");
    if (act =='detail') {
      $("#judul").html('<h3> Detail Data</h3>');
      $("#btn-simpan").hide();
    }else{
      $("#judul").html('<h3> Form Edit Data</h3>');
      $("#btn-simpan").show();
    }

    status = "update";

         $.ajax({
              url: '<?php echo base_url('Transaksi/get_edit'); ?>',
              type: 'POST',
              data: {id: id,jenis : "tr_promosi",field:'id_promosi'},
              dataType: "JSON",
          })
          .done(function(data) {
              $("#tgl_mulai").val(data.tgl_mulai);
              $("#tgl_akhir").val(data.tgl_akhir);
              $("#id_promosi").val(data.id_promosi);
              $('#id_produk0').val(data.id_produk+'|'+data.nm_produk).trigger('change');
              $('#id_produk0').prop("disabled",true);
              $("#diskon0").val(data.diskon);
          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Transaksi/hapus',
        data  : ({id:id,jenis:'tr_promosi',field:'id_promosi'}),
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

  function proses(status,id){
    $.ajax({
          type     : "POST",
          url      : '<?php echo base_url(); ?>Transaksi/status',
          data     : ({status:status,id:id,jenis:'tr_promosi',field:'id_promosi'}),
          dataType : "json",
          success  : function(data){
            if (status) {
              
              toastr.error('Data Tidak Aktif'); 
            
            }else{
              toastr.success('Data Aktif'); 
            }
            reloadTable();
    
          }
      });
  }

  var rowNum = 0;
    function addRow(frm)
    {
        var b = $('#bucket').val();
        
        if(b == -1){
            b = 0;
            rowNum = 0;
        }
        var s = $('#diskon'+b).val();
        var ss = $('#id_produk'+b).val();
        if (s != '0' && s != '' && ss != '' ) {
            $('#removeRow').show();
            rowNum ++;

            if (rowNum <= 4 ) {


              var x = rowNum + 1;
              $('#table-produk').append(''+
                  '<tr id="itemRow'+rowNum+'">'+
                      '<td><a class="btn btn-danger" onclick="removeRow('+rowNum+')"><i class="fa fa-trash"></i> </a></td>'+
                      '<td>'+x+'</td>'+
                      '<td>'+
                          '<select class="form-control select2" name="id_produk['+rowNum+']" id="id_produk'+rowNum+'" style="width: 100%;">'+
                          '<option value="">Pilih</option>'+
                          '<?php foreach ($produk as $r): ?>'+
                            '<option value="<?= $r->id_produk."|".$r->nm_produk ?>"><?= $r->id_produk . " | ".$r->nm_produk ?></option>'+
                          '<?php endforeach ?>'+
                        '</select>'+
                      '</td>'+
                      '<td>'+
                         ' <input type="text" name="diskon['+rowNum+']" id="diskon'+rowNum+'"  class="angka form-control" value="0" onkeyup="cek_diskon('+rowNum+')" onchange="cek_diskon('+rowNum+')">'+
                      '</td>'+
                  '</tr>)');

              $('#bucket').val(rowNum);
              $('#diskon'+rowNum).focus();
            }else{
              toastr.info('Maksimal 5 Produk');      
            }
        }
        else
        {
            toastr.info('Isi form diatas terlebih dahulu'); 
        }
    }

    function cek_diskon(e){
      diskon = $("#diskon"+e).val();
      if (parseInt(diskon) > 100) {
        toastr.error('Maksimal Diskon 100%'); 
        $("#diskon"+e).val("100");
      }
    }

    function removeRow(e) 
    {        
        if (rowNum>0) {
            jQuery('#itemRow'+e).remove();
            rowNum--;
        }
        else{
            toastr.error('Baris pertama tidak bisa dihapus'); return;
        }
        $('#bucket').val(rowNum);
    }

    function clearRow() 
    {
        var bucket = $('#bucket').val();
        for (var e = bucket; e > 0; e--) {            
            jQuery('#itemRow'+e).remove();            
            rowNum--;
        }

        $('#removeRow').hide();
        $('#bucket').val(rowNum);
        $('#id_produk0').val('').trigger('change');
        $('#diskon0').val('0');
    }
  
</script>