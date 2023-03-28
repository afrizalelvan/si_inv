

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Laporan Penjualan </h1> -->
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
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Jenis Produk</label>
            <div class="col-sm-5">
              <select class="form-control" id="produk" onchange="set_produk(this.value)">
                <option value="-">Semua</option>
                <option value="Produk">Produk</option>
                <option value="Perawatan">Perawatan</option>
              </select>
            </div>
            <div class="col-sm-5">
              <select class="form-control select2" style="width: 100%;" id="id_produk" disabled>
                
              </select>

              
            </div>
          </div>
          
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode</label>
            <div class="col-sm-4">
              <input type="date" name="tgl1" id="tgl1" value="<?= date('Y-m-d') ?>" class="form-control">
            </div>
            <div class="col-sm-1">
              Sampai
            </div>
            <div class="col-sm-4">
              <input type="date" name="tgl2" id="tgl2" value="<?= date('Y-m-d') ?>" class="form-control">
            </div>
          </div>
          
          <button type="button" class="btn-cetak btn  btn-outline-primary pull-right" onclick="load_data()">Tampilkan</button>
          <!-- <button type="button" class="btn-cetak btn  btn-outline-primary pull-right" disabled="true" onclick="cetak(0)">Cetak Layar</button> -->
          <button type="button" class="btn-cetak btn  btn-outline-success pull-right" onclick="cetak(1)">Cetak Excel</button>
          <br><br>
          <table id="datatable" class="table table-bordered table-striped-responsive" width="100%">
            <thead>
            <tr>
              <th style="width:%">ID</th>
              <th style="width:%">Tanggal</th>
              <th style="width:%">ID Pelanggan</th>
              <th style="width:%">Nama Pelanggan</th>
              <th style="width:%">ID Produk</th>
              <th style="width:%">Nama Produk</th>
              <th style="width:%">Harga</th>
              <th style="width:%">Qty</th>
              <th style="width:%">Value</th>
              <th style="width:%">Disc</th>
              <th style="width:%">Potongan Disc</th>
              <th style="width:%">ID Promosi</th>
              <th style="width:%">Disc Member</th>
              <th style="width:%">Potongan Member</th>
              <th style="width:%">Total</th>
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

<script type="text/javascript">

  $(document).ready(function () {
     $('.select2').select2();
  });

 function set_produk(e){
  var newOption = new Option("", "", false, false);
  $('#id_produk').append(newOption).trigger('change');

    if (e == '-' || e =='') {
      $("#id_produk").prop("disabled",true);
      $("#id_produk").val("");
    }else{
      $("#id_produk").prop("disabled",false);
      $("#id_produk").val("");

      produk = $("#produk").val();

      $('#id_produk').select2({
           // minimumInputLength: 3,
           allowClear: true,
           placeholder: '--Pilih--',
           ajax: {
              dataType: 'json',
              url      : '<?php echo base_url(); ?>Laporan/get_produk/'+produk,
              delay: 800,
              data: function(params) {
                if (params.term == undefined) {
                  return {
                    search: ""
                  }  
                }else{
                  return {
                    search: params.term
                  }
                }
                
              },
              processResults: function (data, page) {
              return {
                results: data
              };
            },
          }
      });
    }
    
      
  }

  function load_data() {
    jenis = $("#produk").val();
    produk = $("#id_produk").val();
    tgl1 = $("#tgl1").val();
    tgl2 = $("#tgl2").val();

    if (jenis == 'Produk' || jenis == 'Perawatan') {
      if (produk == 'null' || produk == '') {
        toastr.info('Silah Kan Pilih Id Produk'); 
        return;
      }
    }

    var table = $('#datatable').DataTable();

    table.destroy();

    tabel = $('#datatable').DataTable({

      "processing": true,
      "pageLength": true,
      "paging": true,
      "ajax": {
        "url": '<?php echo base_url(); ?>Laporan/getLaporan',
        "type": "POST",
        data  : ({jenis:jenis,produk:produk,tgl1:tgl1,tgl2:tgl2}),
      },
      responsive: true,
      "pageLength": 25,
      "language": {
        "emptyTable": "Tidak ada data.."
      }
    });

  }

  function cetak(ctk) {
    jenis = $("#produk").val();
    produk = $("#id_produk").val();
    tgl1 = $("#tgl1").val();
    tgl2 = $("#tgl2").val();

    if (jenis == 'Produk' || jenis == 'Perawatan') {
      if (produk == 'null' || produk == '') {
        toastr.info('Silah Kan Pilih Id Produk'); 
        return;
      }
    }

    var url    = "<?php echo base_url('Laporan/Cetak_Laporan'); ?>";
    window.open(url+'?jenis='+jenis+'&produk='+produk+'&tgl1='+tgl1+'&tgl2='+tgl2, '_blank');

  }
  
</script>