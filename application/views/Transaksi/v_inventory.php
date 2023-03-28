

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
                        <?php if ($this->session->userdata('id_toko') == "-"): ?>
                          <option value="">Pilih</option>
                        <?php endif ?>

                        <?php foreach ($store as $r): ?>
                          <option value="<?= $r->id_toko ?>"><?= $r->nm_toko ?></option>
                        <?php endforeach ?>
                      </select>
                    </td>
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
              <th style="width:10%">Nama Store</th>
              <th style="width:22%">ID Produk</th>
              <th style="width:22%">Nama Produk</th>
              <th style="width:10%">Qty</th>
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

<div class="modal fade" id="MyModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="judul_detail_produk"></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <table id="table_detail" class="table table-bordered table-striped" width="100%" style="font-size:13px">
            <thead>
            <tr>  
              <th>BatchNo</th>
              <th>ExpDate</th>
              <th>NoDokumen</th>
              <th>Qty</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        
      </div>
      
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

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/Inventory',
        "type": "POST",
        data  : ({store}), 
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

  tabel1 = ''

  function detail(id_produk,id_toko){
    
    $("#MyModal").modal("show");
    

     $.ajax({
          url: '<?php echo base_url('Transaksi/get_inv'); ?>',
          type: 'POST',
          data: {id_produk,id_toko},
          dataType: "JSON",
      })
      .done(function(data) {
          if ($.fn.dataTable.isDataTable('#table_detail')) {
              tabel1.destroy();
              $('#table_detail tbody').empty();
          }
          $("#judul_detail_produk").html(data[0].id_produk+ '-'+data[0].nm_produk)
          $.each(data,function(index, value){
            $('#table_detail tbody').append(
                `
                  <tr>
                    <td>${value.BatchNo}</td>
                    <td>${value.ExpDate}</td>
                    <td>${value.NoDokumen}</td>
                    <td>${value.qty}</td>
                  </tr>
                `
            )
          })

          tabel1 = $('#table_detail').DataTable({
              
              "ordering": false,
              "paging":  false,
              dom: 'Bfrtip',
              buttons: [
                  {
                      extend: 'excel',
                  },
                  'pageLength'
              ],
          });
      }) 

  }

 
  
</script>