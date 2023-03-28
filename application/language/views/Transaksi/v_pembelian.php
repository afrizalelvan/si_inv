

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
              <th style="width:10%">ID Supplier</th>
              <th style="width:22%">Nama Supplier</th>
              <th style="width:10%">ID Store</th>
              <th style="width:22%">Nama Store</th>
              <th style="width:10%">Jml Qty</th>
              <th style="width:5%">Value</th>
              <th style="width:5%">Potongan</th>
              <th style="width:5%">Total</th>
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
                      <td width="20%">ID Pembelian</td>
                      <td width="2%">:</td>
                      <td width="20%" id="txt-id_pembelian">
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
                      <td width="20%">Supplier</td>
                      <td width="2%">:</td>
                      <td width="20%" id="id_supplier"></td>
                    </tr>
                    <tr>
                      <td width="20%">Store</td>
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
                    <th width="10%">Harga</th>
                    <th width="10%">Qty</th>
                    <th width="10%">Qty Terima</th>
                    <th width="10%">Value</th>
                    <th width="15%">Potongan</th>
                    <th width="10%">Total</th>
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
        <button type="button" onclick="simpan()" style="text-decoration: none; cursor: pointer;" class="btn btn-outline-primary" id="btn-simpan">Update </button>
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
  var id_pembelian = '';
  $(document).ready(function () {
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

    store = $("#store").val()
    tgl1 = $("#tgl1").val()
    tgl2 = $("#tgl2").val()

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/pembelian',
        "type": "POST",
        data  : ({store,tgl1,tgl2}), 
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
     $("#btn-simpan").show();
     clearRow();
  }

  var bucket = 0

  function tampil_edit(id,act){
    kosong();
    status = 'update';
    $("#modalForm").modal("show");
    if (act =='detail') {
      $("#judul").html('<h3> Detail Data</h3>');
      // $("#btn-simpan").hide();
    }else{
      $("#judul").html('<h3> Form Edit Data</h3>');
      $("#btn-simpan").show();
    }
    $("#jenis").val('Update');

    status = "update";

         $.ajax({
              url: '<?php echo base_url('Transaksi/get_edit'); ?>',
              type: 'POST',
              data: {id: id,jenis : "tr_pembelian_detail",field:'id_pembelian'},
              dataType: "JSON",
          })
          .done(function(data) {
            x= 0 ;
              id_pembelian = data[0].id_pembelian;

              $("#txt-id_pembelian").html(data[0].id_pembelian);
              
              $("#tanggal").html(data[0].add_time);
              $("#id_supplier").html(data[0].id_supplier+'|'+data[0].nm_supplier);
              $("#store_").html(data[0].id_toko+'|'+data[0].nm_toko);

              var tot_qty = 0;
              var tot_qty_terima = 0;
              var tot_value = 0;
              var tot_potongan = 0;
              var tot_total = 0;

              s_td_terima = ''
              s_td_qty = ''
              s_td_batch = ''
              aksi = ''
              BatchNo = ''
              ExpDate = ''
              $("#btn-simpan").show()

              for (var i = 0; i < data.length; i++) {


                if (data[0].status == 'Open') {
                  s_td_terima = 'readonly'
                  aksi = "Kirim"
                  s_td_batch = 'readonly'
                  $("#btn-simpan").html("Update")
                } else if (data[0].status == 'Kirim'){                
                  s_td_qty = 'readonly'
                  aksi = "Terima"
                  data[i].qty_terima = data[i].qty
                  $("#btn-simpan").html("Terima")
                  BatchNo = "<?= date('YmdH') ?>"
                }else{
                  s_td_terima = 'readonly'
                  s_td_batch = 'readonly'
                  s_td_qty = 'readonly'
                  data[i].qty_terima = data[i].qty_terima
                  BatchNo = data[i].BatchNo
                  ExpDate = data[i].ExpDate
                  $("#btn-simpan").hide()
                }

                x = i+1;
                 $('#table-produk tbody').append(
                    `<tr id="itemRow${rowNum}">
                        <td>
                          ${x}
                          
                          <input type="hidden" name="id_pembelian" id="id_pembelian" value="${data[0].id_pembelian}">
                          <input type="hidden" name="aksi" id="aksi" value="${aksi}">
                          <input type="hidden" value="${data[i].id_produk}" name="id_produk[]" id="id_produk${bucket}">
                          <input type="hidden" value="${data[i].nm_produk}" name="nm_produk[]" id="nm_produk${bucket}">
                          <input type="hidden" value="${data[i].harga}" name="harga[]" id="harga${bucket}">
                        </td>
                        <td>${data[i].id_produk} | ${data[i].nm_produk}</td>
                        <td align="right" >${formatMoney(data[i].harga)}</td>
                        <td><input type="number" class="form-control" value="${data[i].qty}" ${s_td_qty} name="qty[]" id="qty${bucket}" onkeyup="hitung('qty')" onchange="hitung('qty')"></td>
                        <td><input type="number" class="form-control" value="${data[i].qty_terima}" ${s_td_terima} name="qty_terima[]" id="qty_terima${bucket}" onkeyup="hitung()" onchange="hitung()"></td>
                        <td align="right" id="txt-value${bucket}">${formatMoney(data[i].value)}</td>
                        <td><input type="number" class="form-control" value="${data[i].potongan}" ${s_td_terima} name="potongan[]" id="potongan${bucket}" onkeyup="hitung()" onchange="hitung()"></td>
                        <td align="right" id="txt-total${bucket}">${formatMoney(data[i].total)}</td>
                        <td><input type="text" class="form-control" name="BatchNo[]" id="BatchNo${bucket}" ${s_td_batch} value="${BatchNo}"></td>
                        <td><input type="date" class="form-control" name="ExpDate[]" id="ExpDate${bucket}" ${s_td_batch} value="${ExpDate}"></td>
                    '</tr>`
                  ); 

                 
                bucket++;

                 tot_qty += parseInt(data[i].qty);
                 tot_qty_terima += parseInt(data[i].qty_terima);
                 tot_value += parseInt(data[i].value);
                 tot_potongan += parseInt(data[i].potongan);
                 tot_total += parseInt(data[i].total);

              }
              var diskon = 0;
              $('#table-produk tbody').append(''+
                  '<tr id="itemRow999">'+
                      '<td colspan="3" align="right">Total</td>'+
                      '<td align="right" id="txt-tot_qty">'+formatMoney(tot_qty)+'</td>'+
                      '<td align="right" id="txt-tot_qty_terima">'+formatMoney(tot_qty_terima)+'</td>'+
                      '<td align="right" id="txt-tot_value">'+formatMoney(tot_value)+'</td>'+
                      '<td align="right" id="txt-tot_potongan">'+formatMoney(tot_potongan)+'</td>'+
                      '<td align="right" id="txt-tot_total">'+formatMoney(tot_total)+'</td>'+
                      '<td align="right" colspan="2"></td>'+
                  '</tr>'); 

          }) 

  }


  function deleteData(id){
    let cek = confirm("Apakah Anda Yakin?");

    if (cek) {
      $.ajax({
        url   : '<?php echo base_url(); ?>Transaksi/hapus',
        data  : ({id:id,jenis:'tr_penjualan',field:'id_pembelian'}),
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

  function update_beli(status,id){
    $.ajax({
          type     : "POST",
          url      : '<?php echo base_url(); ?>Transaksi/update_beli',
          data     : ({status,id}),
          dataType : "json",
          success  : function(data){
            if (status) {
              
              toastr.success('Berhasil di '+status); 
            
            }else{
              toastr.error('Terjadi Kesalahan'); 
            }
            reloadTable();
    
          }
      });
  }

  function hitung(s = ''){
    tot_qty = 0 
    tot_qty_terima = 0
    tot_value = 0
    tot_potongan = 0
    tot_total = 0

    for (var i = 0; i < bucket; i++) {
      harga = parseFloat($(`#harga${i}`).val())
      qty = parseFloat($(`#qty${i}`).val())
      qty_terima = parseFloat($(`#qty_terima${i}`).val())
      potongan = parseFloat($(`#potongan${i}`).val())
      // value = parseFloat($(`#value${i}`).val())

      
      if (qty_terima >= qty) {
        qty_terima = qty
        $(`#qty_terima${i}`).val(qty_terima)
      }

      if (qty_terima == 0) {
        potongan = 0
        $(`#potongan${i}`).val(potongan)
        $(`#BatchNo${i}`).val('')
        $(`#ExpDate${i}`).val('')

        $(`#potongan${i}`).prop("readonly",true)
        $(`#BatchNo${i}`).prop("readonly",true)
        $(`#ExpDate${i}`).prop("readonly",true)
      }else{
        $(`#potongan${i}`).prop("readonly",false)
        $(`#BatchNo${i}`).prop("readonly",false)
        $(`#ExpDate${i}`).prop("readonly",false)
      }

      if (s == '') {
        s_qty = qty_terima
      }else{
        s_qty = qty
      }
      total = s_qty * harga - potongan
      value = s_qty * harga
      

      $(`#txt-value${i}`).html(formatMoney(value))
      $(`#txt-total${i}`).html(formatMoney(total))

      tot_qty += qty
      tot_qty_terima += qty_terima
      tot_value += value
      tot_potongan += potongan
      tot_total += total
    }

    $(`#txt-tot_qty`).html(formatMoney(tot_qty))
    $(`#txt-tot_qty_terima`).html(formatMoney(tot_qty_terima))
    $(`#txt-tot_value`).html(formatMoney(tot_value))
    $(`#txt-tot_potongan`).html(formatMoney(tot_potongan))
    $(`#txt-tot_total`).html(formatMoney(tot_total))
  }

  function clearRow() 
    {
        bucket = 0
        $('#table-produk tbody').empty()
    }

    function simpan(){
      toastr.clear()

      tot_qty  = parseInt($(`#txt-tot_total`).html())
      supplier = $(`#supplier`).val()
      store = $(`#store`).val()
      aksi = $("#aksi").val()

      if (tot_qty == 0) {
        toastr.info('Total tidak boleh 0')
        return
      }
      if (aksi == 'Terima') {
        for (var i = 0; i < bucket; i++) {
          qty_terima = parseInt($(`#qty_terima${i}`).val())
          BatchNo = $(`#BatchNo${i}`).val()
          ExpDate = $(`#ExpDate${i}`).val()
          id_produk = $(`#id_produk${i}`).val()
          

          if (qty_terima > 0 && (BatchNo == "" || ExpDate == "")) {
            toastr.info(`Harap Isi BatchNo & ED pada Produk ${id_produk}`)
            return
          }
        }
      }


      
        $.ajax({
            url      : '<?php echo base_url(); ?>/Transaksi/update_trs_beli',
            type     : "POST",
            data     : $('#myForm').serialize(),
            dataType: "JSON",
            success: function(data)
            {           
                if (data) {
                  toastr.success('Berhasil Disimpan'); 
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
    link = "<?=base_url('Transaksi/print_invoice_beli')?>?id="+id_pembelian;

    var left = (screen.width - 980) / 2;
    var top = (screen.height - 1050) / 4;

    var myWindow = window.open(link, "", "width=980, height=1050, top=" + top + ", left=" + left);
  } 
 
  
</script>