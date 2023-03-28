

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
                <table width="100%">
                  <tr>
                    <td>Store</td>
                    <td>
                      <select class="form-control" id="store" name="store">
                        <?php if ($this->session->userdata('id_toko') == "-"): ?>
                          <option value="">Pilih</option>
                        <?php endif ?>
                        
                        <?php foreach ($store as $r): ?>
                          <option value="<?= $r->id_toko ?>"><?= $r->nm_toko ?></option>
                        <?php endforeach ?>
                      </select>
                    </td>
                    <td align="center">Tgl</td>
                    <td>
                      <input type="date" class="form-control" id="tgl" value="<?= date('Y-m-d') ?>">
                    </td>
                    <td align="center">Produk</td>
                    <td>
                      <select class="form-control" id="produk" name="produk">
                        <option value="">Pilih</option>
                        <?php foreach ($produk as $r): ?>
                          <option value="<?= $r->id_produk ?>"><?= $r->id_produk.'-'.$r->nm_produk ?></option>
                        <?php endforeach ?>
                      </select>
                    </td>
                    <td>
                      <button type="button" class="btn btn-outline-primary btn-sm" onclick="load_data()">Tampilkan</button>
                    </td>
                    <td>
                      <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                      </button>
                    </td>
                  </tr>
                </table>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="datatable" class="table table-striped" width="100%" style="font-size:13px">
                  
                  <thead>
                    <tr>  
                      <th>No</th>
                      <th>Tgl</th>
                      <th>NoDokumen</th>
                      <th>Keterangan</th>
                      <th>Qty Masuk</th>
                      <th>Qty Keluar</th>
                      <th>Saldo Akhir</th>
                      <th>BatchNo</th>
                      <th>ExpDate</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
      </div>
      

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
  
  function load_data() {
    
    var table = $('#datatable').DataTable();

    id_toko = $("#store").val()
    tgl = $("#tgl").val()
    id_produk = $("#produk").val()

    if (id_toko == "" || id_produk == "" ) {
        toastr.info('Pilih Store & Produk');
        return ;                 
    }
    table.destroy();

    tabel = $('#datatable').DataTable({

      "searching": false,
      "ordering": false,
      "processing": true,
      "pageLength": false,
      scrollY:        '50vh',
      scrollCollapse: true,
       "bInfo" : false,
      "ajax": {
        "url": '<?php echo base_url(); ?>Transaksi/load_data/kartu_stok',
        "type": "POST",
        data  : ({id_toko,tgl,id_produk}), 
      },
      responsive: false,
      dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    title: 'Data Kartu Stok : '+ id_produk+ ' , Store : '+id_toko
                }
            ],
      "pageLength": -1,
      "language": {
        "emptyTable": "Tidak ada data.."
      }
    });

  }
</script>