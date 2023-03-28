

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
      <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h3 class="card-title">Filter</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table border="0" width="50%">
                  
                  <tr>
                    <td width="20%">Tanggal</td>
                    <td width="2%">:</td>
                    <td><input type="date" class="form-control" id="tgl1" value="<?= $tgl_awal ?>"></td>
                    <td><input type="date" class="form-control" id="tgl2" value="<?= $tgl ?>"></td>
                  </tr>
                  <tr>
                    <td width="20%"><button type="button" class="btn btn-block btn-outline-primary btn-sm" onclick="load_data()">Tampilkan</button></td>
                    <td width="2%" colspan="3"></td>
                  </tr>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
      </div>
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $judul ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </button>
          </div>

        </div>
        <div class="card-body">
          
          <table id="datatable" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th style="width:5%">ID</th>
              <th style="width:10%">Tanggal</th>
              <th style="width:10%">ID Store</th>
              <th style="width:22%">Nama Store</th>
              <th style="width:10%">ID Store Penerima</th>
              <th style="width:22%">Nama Store Penerima</th>
              <th style="width:10%">Jml Qty</th>
              <th style="width:5%">Status</th>
              <th style="width:15%">#</th>
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
          <table width="100%">
            <tr>
              <td width="40%">
                <table width="100%">
                    <tr>
                      <td width="20%">ID Relokasi</td>
                      <td width="2%">:</td>
                      <td width="20%" id="txt-id_relokasi">
                      </td>
                    </tr>
                    <tr>
                      <td width="20%">Tanggal</td>
                      <td width="2%">:</td>
                      <td width="20%" id="tanggal"></td>
                    </tr>
                  </table>
              </td>
              <td></td>
              <td width="40%">
                <table width="100%">
                    <tr>
                      <td width="20%">Store Pengirim</td>
                      <td width="2%">:</td>
                      <td width="20%" id="storePengirim_"></td>
                    </tr>
                    <tr>
                      <td width="20%">Store Penerima</td>
                      <td width="2%">:</td>
                      <td width="20%" id="store_"></td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>
        <br><br>

        <div class="form-group row">
          <table class="table" id="table-produk" style="width: 90%" align="center">
            <thead>
                <tr>
                    <th width="2%">No</th>
                    <th width="15%">Produk</th>
                    <th width="10%">Qty</th>
                    <th width="10%">BatchNo</th>
                    <th width="10%">ED</th>
                </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>  

          
          
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label"></label>
          <div class="col-sm-4">
           
          </div>
        </div>
          

      </div>
      <div class="modal-footer justify-content-between">
       
        <button type="button" onclick="printContent()" style="text-decoration: none; cursor: pointer;" title="Print Receipt" class="btn btn-outline-secondary">Print </button>
      </div>
      </form>
        
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
  rowNum = 0;
  var id_relokasi = '';
  $(document).ready(function () {
    cek_closing = "<?= $cek_closing ?>"
    if (cek_closing == 'N') {
      alert('Belum closing bulanan')
      window.location.href = '<?= base_url() ?>';
    }
    
     load_data();
     
     var rowNum = 0;
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

    tgl1 = $("#tgl1").val()
    tgl2 = $("#tgl2").val()

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/Terimastok_transfer',
        "type": "POST",
        data  : ({tgl1,tgl2}), 
      },
      responsive: true,
      dom: 'Bfrtip',
            buttons: [
                'pdf', 'excel', 'pageLength', 'colvis'
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

  function kosong(){
     status = 'insert';
     
     
  }

  var bucket = 0

  function tampil_edit(id,act){
    kosong();
    status = 'update';
    $("#modalForm").modal("show");
    if (act =='detail') {
      $("#judul").html('<h3> Detail Data</h3>');
      
    }else{
      $("#judul").html('<h3> Form Edit Data</h3>');
      
    }
    $("#jenis").val('Update');
    
    $('#table-produk tbody').empty()

    status = "update";

         $.ajax({
              url: '<?php echo base_url('Transaksi/get_edit'); ?>',
              type: 'POST',
              data: {id,jenis : "tr_relokasi_detail",field:'id_relokasi'},
              dataType: "JSON",
          })
          .done(function(data) {
            x= 0 ;
              id_relokasi = data[0].id_relokasi;

              $("#txt-id_relokasi").html(data[0].id_relokasi);
              
              $("#tanggal").html(data[0].add_time);
              $("#storePengirim_").html(data[0].id_toko+'|'+data[0].nm_toko);
              $("#store_").html(data[0].id_toko_penerima+'|'+data[0].nm_toko_penerima);

              var tot_qty = 0;

              for (var i = 0; i < data.length; i++) {


                x = i+1;
                 $('#table-produk tbody').append(
                    `<tr id="itemRow${rowNum}">
                        <td>
                          ${x}
                          
                        </td>
                        <td>${data[i].id_produk} | ${data[i].nm_produk}</td>
                        <td>${data[i].qty}</td>
                        <td>${data[i].BatchNo}</td>
                        <td>${data[i].ExpDate}</td>
                    '</tr>`
                  ); 

                 
                bucket++;

                 tot_qty += parseInt(data[i].qty);

              }
              var diskon = 0;
              $('#table-produk tbody').append(''+
                  '<tr id="itemRow999">'+
                      '<td colspan="2" align="right">Total</td>'+
                      '<td align="right" id="txt-tot_qty">'+formatMoney(tot_qty)+'</td>'+
                      '<td align="right" colspan="2"></td>'+
                  '</tr>'); 

          }) 

  }

  function Terima(id){
    toastr.clear()

     $.ajax({
          url: '<?php echo base_url('Transaksi/Terima_relokasi'); ?>',
          type: 'POST',
          data: {id},
          dataType: "JSON",
      })
      .done(function(data) {
        if (data) {
          toastr.success('Data berhasil Diterima')
          reloadTable()
        }else{
          toastr.danger('Terjadi Kesalahan')
        }

      }) 

  }


  
  function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

      const negativeSign = amount < 0 ? "-" : "";

      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;

      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  };


  function printContent(){
    link = "<?=base_url('Transaksi/print_relokasi')?>?id="+id_relokasi;

    var left = (screen.width - 980) / 2;
    var top = (screen.height - 1050) / 4;

    var myWindow = window.open(link, "", "width=980, height=1050, top=" + top + ", left=" + left);
  } 
 
  
</script>