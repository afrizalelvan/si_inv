

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
          <table border="0" width="50%">
            <tr>
              <td width="20%">Store</td>
              <td width="2%">:</td>
              <td colspan="2">
                <select class="form-control" id="store" name="store">
                  <?php if ($id_toko == "T0001"): ?>
                    <option value="all">Semua</option>  
                  <?php endif ?>
                  
                  <?php foreach ($store as $r): ?>
                    <option value="<?= $r->id_toko ?>"><?= $r->nm_toko ?></option>
                  <?php endforeach ?>
                </select>
              </td>
            </tr>
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
          <br>
          <table id="datatable" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
              <th style="width:5%">ID</th>
              <th style="width:15%">Store</th>
              <th style="width:10%">Tanggal</th>
              <th style="width:10%">ID Pelanggan</th>
              <th style="width:22%">Nama Pelanggan</th>
              <th style="width:10%">ID Produk</th>
              <th style="width:5%">Nama Produk</th>
              <th style="width:5%">Qty</th>
              <th style="width:5%">Harga</th>
              <th style="width:5%">Potongan</th>
              <th style="width:5%">Total</th>
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

<!-- /.modal -->

<script type="text/javascript">
  rowNum = 0;
  var id_penjualan = '';
  $(document).ready(function () {
     load_data();
     $('.select2').select2();
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
    store = $("#store").val()
    tgl1 = $("#tgl1").val()
    tgl2 = $("#tgl2").val()

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/penjualan_detail',
        "type": "POST",
        data  : ({store,tgl1,tgl2}), 
      },
      responsive: false,
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
     $("#btn-simpan").show();
     clearRow();
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
              url: '<?php echo base_url('Transaksi/get_edit'); ?>',
              type: 'POST',
              data: {id: id,jenis : "tr_penjualan_detail",field:'id_penjualan'},
              dataType: "JSON",
          })
          .done(function(data) {
            x= 0 ;
              id_penjualan = data[0].id_penjualan;
              $("#id_penjualan").html(data[0].id_penjualan);
              $("#tanggal").html(data[0].add_time);
              $("#id_pelanggan").html(data[0].id_pelanggan+'|'+data[0].nm_pelanggan);
              $("#kasir").html(data[0].add_user);
              var total = 0;
              for (var i = 0; i < data.length; i++) {
                rowNum ++;
                x = i+1;
                 $('#table-produk').append(''+
                  '<tr id="itemRow'+rowNum+'">'+
                      '<td>'+x+'</td>'+
                      '<td>'+ data[i].id_produk+' | '+data[i].nm_produk+'</td>'+
                      '<td align="right">'+ formatMoney(data[i].harga)+'</td>'+
                      '<td>'+ data[i].qty+'</td>'+
                      '<td align="right">'+ formatMoney(data[i].value)+'</td>'+
                      '<td>'+ data[i].diskon+'</td>'+
                      '<td align="right">'+ formatMoney(data[i].total)+'</td>'+
                      '<td>'+ data[i].id_promosi+'</td>'+
                  '</tr>)'); 

                 $('#bucket').val(rowNum);
                 total += parseInt(data[i].total);
              }
              var diskon = 0;
              $('#table-produk').append(''+
                  '<tr id="itemRow999">'+
                      '<td colspan="6" align="right">Sub Total</td>'+
                      '<td align="right">'+formatMoney(total)+'</td>'+
                      '<td align="right"></td>'+
                  '</tr>'+
                  '<tr id="itemRow9991">'+
                      '<td colspan="6" align="right">Disc. Member ('+data[0].diskon_pelanggan+'%)</td>'+
                      '<td align="right">'+formatMoney(diskon = (data[0].diskon_pelanggan / 100)* total )+'</td>'+
                      '<td align="right"></td>'+
                  '</tr>'+
                  '<tr id="itemRow9992">'+
                      '<td colspan="6" align="right">Total</td>'+
                      '<td align="right">'+formatMoney(total - diskon)+'</td>'+
                      '<td align="right"></td>'+
                  '</tr>'); 

          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Transaksi/hapus',
        data  : ({id:id,jenis:'tr_penjualan',field:'id_penjualan'}),
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
          data     : ({status:status,id:id,jenis:'tr_penjualan',field:'id_penjualan'}),
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

  function clearRow() 
    {
        var bucket = $('#bucket').val();
        for (var e = bucket; e > 0; e--) {            
            jQuery('#itemRow'+e).remove();            
            rowNum--;
        }
        jQuery('#itemRow999').remove(); 
        jQuery('#itemRow9991').remove(); 
        jQuery('#itemRow9992').remove(); 

        $('#removeRow').hide();
        $('#bucket').val(rowNum);
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
    link = "<?=base_url('Transaksi/print_invoice')?>?id="+id_penjualan;

    var left = (screen.width - 380) / 2;
    var top = (screen.height - 550) / 4;

    var myWindow = window.open(link, "", "width=380, height=550, top=" + top + ", left=" + left);
  } 
 
  
</script>