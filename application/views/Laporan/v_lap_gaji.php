 <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="page-title">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <!-- <h3>Plain Page</h3> -->
                          <?php echo $this->session->flashdata('msg'); ?>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h3>Laporan <small> <?= $judul ?></small></h3>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Periode
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <select class="form-control" id="periode" >
                          <?php foreach ($periode as $r): ?>
                            <option value="<?= $r->periode ?>"><?= $r->periode ?></option>
                          <?php endforeach ?>
                        </select>
                      </div>
                    </div>
                   </div>
                    <br><br>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        
                        <button onclick="cetak(0)"  class="btn btn-default"><i class="fa fa-desktop"></i> Layar</button>
                        <button onclick="cetak(1)"  class="btn btn-default"><i class="fa fa-file-pdf-o"></i> PDF</button>
                        <button onclick="cetak(2)"  class="btn btn-default"><i class="fa fa-file-excel-o"></i> Excel</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<script>

  function cetak(ctk){
    periode = $("#periode").val();
    var url    = "<?php echo base_url('Laporan/Cetak_Lap_Gaji'); ?>";
    window.open(url+'?periode='+periode+'&ctk='+ctk, '_blank');   
  }

  
</script>
