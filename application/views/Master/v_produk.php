

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
          <!-- <button type="button" class="btn-cetak btn  btn-outline-success pull-right" onclick="cetak(1)">Export Excel</button> -->
          <br><br>

         

          <table id="datatable" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th style="width:10%">Gambar</th>
              <th style="width:5%">ID</th>
              <th style="width:20%">Nama Produk</th>
              <th style="width:20%">Kategori</th>
              <th style="width:20%">Supplier</th>
              <th style="width:22%">Deskripsi</th>
              <th style="width:10%">Satuan</th>
              <th style="width:7%">Harga Beli</th>
              <th style="width:7%">Harga Jual</th>
              <th style="width:5%">Barcode</th>
              <th style="width:5%">Status</th>
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
          <label class="col-sm-2 col-form-label">Nama produk</label>
          <div class="col-sm-10">
            <input type="hidden" class="form-control" id="id_produk" >
            <input type="text" class="form-control" id="nm_produk" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Kategori</label>
          <div class="col-sm-10">
            <select class="form-control" id="kategori" name="kategori">
              <option value="">Pilih</option>
              <?php foreach ($kategori as $r): ?>
                <option value="<?= $r->id_kategori."-".$r->nm_kategori ?>"><?= $r->nm_kategori ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Satuan</label>
          <div class="col-sm-10">
            
            <select class="form-control" id="satuan" name="satuan">
              <option value="">Pilih</option>
              <?php foreach ($satuan as $r): ?>
                <option value="<?= $r->nm_satuan ?>"><?= $r->nm_satuan ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Supplier</label>
          <div class="col-sm-10">
            <select class="form-control" id="supplier" name="supplier">
                        
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Deskripsi</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="deskripsi" placeholder="Masukan.."></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Harga Beli</label>
          <div class="col-sm-10">
            <input type="text" class="angka form-control"  id="harga" placeholder="Masukan..">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Harga Jual</label>
          <div class="col-sm-10">
            <input type="text" class="angka form-control"  id="harga_jual" placeholder="Masukan..">
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
        
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Gambar</label>
          <div class="col-sm-5">
            <input type="file" class="form-control"  id="gambar" name="gambar" placeholder="Masukan..">
          </div>
          <div class="col-sm-5">
            <img src="" id="get-gambar" width="40%">
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
     load_supplier()
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
        "url": '<?php echo base_url(); ?>Master/load_data/produk',
        "type": "POST",
        // data  : ({tanggal:tanggal,tanggal_akhir:tanggal_akhir,id_kategori:id_kategori1,id_sub_kategori:id_sub_kategori1}),
      },
      responsive: true,
      dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Master Produk'
            }
        ],
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

  function cek_ekstensi(file){
    var filename = file.replace(/C:\\fakepath\\/i, '');
    ekstensi = filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
    
    if (ekstensi == 'png' || ekstensi == 'PNG' || ekstensi == 'pdf' || ekstensi == 'PDF' || ekstensi == 'jpg' || ekstensi == 'JPG') {
      return true;
    }else{
      return false;
    }
  }


  function simpan(){
     id_produk = $("#id_produk").val();
     nm_produk = $("#nm_produk").val();
     harga = $("#harga").val();
     harga_jual = $("#harga_jual").val();
     satuan = $("#satuan").val();
     gambar = $("#gambar").val();
     s_aktif = $("#s_aktif").val();
     kategori = $("#kategori").val();
     supplier = $("#supplier").val();
     deskripsi = $("textarea#deskripsi").val();

     if (nm_produk == '' ||  harga == ''||  harga_jual == ''  || satuan == '' || kategori == '' || supplier == '') {
      toastr.info('Harap Lengkapi Form'); 
      return
     }

     if (gambar != '') {
      if (cek_ekstensi(gambar) == false) {
        toastr.info('File upload hanya boleh ( .jpg , .png)'); 
        return ;                 
      }
     }
     

     var data = new FormData();
        data.append('gambar', $("#gambar")[0].files[0]);  
        data.append('id_produk', id_produk);
        data.append('nm_produk', nm_produk);
        data.append('harga', harga);
        data.append('harga_jual', harga_jual);
        data.append('satuan', satuan);
        data.append('deskripsi', deskripsi);
        data.append('s_aktif', s_aktif);
        data.append('status', status);
        data.append('kategori', kategori);
        data.append('supplier', supplier);
        data.append('jenis', 'm_produk');

      $.ajax({
          url      : '<?php echo base_url(); ?>/master/insert',
          type: "POST",
          dataType: "JSON",
          data:data,  
          processData: false,
          contentType: false,  
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
     $("#nm_produk").val('');
     $("#s_aktif").val('Aktif');
     $("#harga").val('');
     $("#harga_jual").val('');
     $("textarea#deskripsi").val('');
     $("#satuan").val('');
     $("#kategori").val('');
     $("#ttl").val('');
     $("#get-gambar").attr("src", "");
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
              data: {id: id,jenis : "m_produk",field:'id_produk'},
              dataType: "JSON",
          })
          .done(function(data) {
              $("#id_produk").val(data.id_produk);
              $("#nm_produk").val(data.nm_produk);
              $("#get-gambar").attr("src", "<?= base_url('assets/gambar/produk/') ?>"+data.gambar);
              $("#harga").val(data.harga);
              $("#harga_jual").val(data.harga_jual);
              $("#s_aktif").val(data.s_aktif);
              $("textarea#deskripsi").val(data.deskripsi);
              $("#satuan").val(data.satuan);
              $("#supplier").val(data.id_supplier+'-'+data.nm_supplier);
              $("#ttl").val(data.ttl);
              $("#kategori").val(data.id_kategori+'-'+data.nm_kategori);
          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Master/hapus',
        data  : ({id:id,jenis:'m_produk',field:'id_produk'}),
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

  function load_supplier(){


     $.ajax({
          url: '<?php echo base_url('Master/load_supplier'); ?>',
          type: 'POST',
          dataType: "JSON",
      })
      .done(function(data) {
          $('#supplier').append(
            `<option value="">Pilih</option>`
          );

          $.each(data,function(index, value){
              $('#supplier').append(
                  `<option value="${value.id_supplier}-${value.nm_supplier}">${value.nm_supplier}</option>`
                );
              
          });
          
                        
                      
      }) 

  }

  function print_bc(id){

    link = "<?=base_url('Transaksi/print_bc')?>?id="+id;

    var left = (screen.width - 380) / 2;
    var top = (screen.height - 550) / 4;

    var myWindow = window.open(link, "", "width=380, height=550, top=" + top + ", left=" + left);
  }

  function cetak(ctk) {
  
    var url    = "<?php echo base_url('Laporan/Laporan_Stok'); ?>";
    window.open(url, '_blank');

  }

  
</script>