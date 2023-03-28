

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
          
          <div class="row">
            <div class="col-md-6">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Server</h3>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="display: block;">
                <table width="100%">
                  <tr>
                    <td width="30%">Tgl Server</td>
                    <td width="2%">:</td>
                    <td>
                      <input type="text" class="form-control" id="tgl_server" value="<?= date('Y-m-d') ?>" readonly>
                        
                    </td>
                  </tr>
                  <tr>
                    <td width="30%">Tgl Closing</td>
                    <td width="2%">:</td>
                    <td>
                      <input type="text" class="form-control" id="tgl_closing" value="<?= $tgl_closing ?>" readonly>
                    </td>
                  </tr>
                  <tr>
                    <td width="30%">Status</td>
                    <td width="2%">:</td>
                    <td>
                      <input type="text" class="form-control" id="status" value="<?= ($status == 'Y') ? 'Sudah Closing' : 'Belum Closing' ?>" readonly style="background-color: <?= ($status == 'Y') ? '#00800052' : '#ff00006b' ?>;">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="3">
                      <br>
                      <button type="button" class="btn btn-block btn-outline-success btn-sm" onclick="proses()" <?= ($status == 'Y') ? 'disabled' : '' ?>>Proses</button>
                    </td>
                  </tr>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>

         
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-outline card-success">
              <div class="card-header">
                

                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body" style="height: 200px;overflow-y: scroll;">
                <table id="datatable" class="table table-bordered table-striped" width="100%">
                  <thead>
                  <tr>
                    <th>Id Produk</th>
                    <th>Stok Akhir</th>
                    <th>Stok Sum</th>
                    <th>Stok Detail</th>
                    <th>Fix</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="overlay" style="display:none">
              <i class="fas fa-2x fa-sync-alt fa-spin"></i>
              </div>
              
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
        </div>
        </div>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<script type="text/javascript">
  function proses(){
    toastr.clear()
    $(".overlay").show()
    $("#datatable tbody").empty()

     $.ajax({
          url: '<?php echo base_url('Transaksi/cek_stok_closing'); ?>',
          type: 'POST',
          // data: {id,tipe},
          dataType: "JSON",
      success: function(data)
      {
        $(".overlay").hide()
        if (data) {
          if (data.length == 0) {
            prosesClosing()
            return
          }

          $.each(data,function(index, value){
            $("#datatable tbody").append(
                `
                  <tr>
                    <td>${value.id_produk}</td>
                    <td>${value.qty_akhir}</td>
                    <td>${value.qty_sum}</td>
                    <td>${value.qty_det}</td>
                    <td>-</td>
                  </tr>
                `
            )
          });
        }

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        $(".overlay").hide()
         toastr.error('Terjadi Kesalahan')
      }
    })

  }

  function prosesClosing(){
    toastr.clear()
    $(".overlay").show()

     $.ajax({
          url: '<?php echo base_url('Transaksi/prosesClosing'); ?>',
          type: 'POST',
          // data: {id_produk},
          dataType: "JSON",
      success: function(data)
      {
        toastr.success('Closing Berhasil')

        setTimeout(function(){ location.reload(); }, 1000);
        
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        $(".overlay").hide()
         toastr.danger('Terjadi Kesalahan')
      }
    })
  }

  function fixStok(id_produk){
    toastr.clear()
    $(".overlay").show()

     $.ajax({
          url: '<?php echo base_url('Transaksi/fixStok'); ?>',
          type: 'POST',
          data: {id_produk},
          dataType: "JSON",
      success: function(data)
      {
        $(".overlay").hide()
        
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
        $(".overlay").hide()
         toastr.danger('Terjadi Kesalahan')
      }
    })
  }
  
</script>