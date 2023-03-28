<?php
    $settingResult = $this->db->get_where('m_setting');
    $settingData = $settingResult->row();

    $setting_site_logo = $settingData->logo;

    $data = $this->db->query("SELECT a.* FROM tr_pembelian_detail a  where a.id_pembelian = '$id_pembelian' ")->result();

    if (count($data) == 0) {
        echo "TERJADI KESALAHAN";

        die();
    }


?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Sale No : <?php echo $id_pembelian; ?></title>
		<script src="<?=base_url()?>assets/js/jquery-1.7.2.min.js"></script>
		
<style type="text/css" media="all">
	
	
</style>
</head>

<body>
<div id="wrapper">
	<table border="0" style="border-collapse: collapse; width: 100%; height: auto;">
	    <tr>
		    <td width="30%" align="center">
			    <center>
			    	<img src="<?=base_url()?>assets/gambar/<?php echo $setting_site_logo; ?>" style="width: 100px;" />
			    </center>
		    </td>
		    <td width="40%" align="center">
			    <h2 style="padding-top: 0px; font-size: 24px;"><b><?php echo $settingData->nm_toko; ?></b></h2>
			    Alamat : <?= $settingData->alamat; ?> , No Telepon : <?= $settingData->no_telp ?>
		    </td>
		    <td width="30%" align="center">
			    
		    </td>
	    </tr>
	</table>
	<hr>
	<center>
		<font style="font-weight: bold;">PURCHASING ORDER <br> <u><?= $data[0]->id_pembelian ?></u></font >
	</center>
	<br>
	<table width="100%">
		<tr>
			<td width="40%" align="center">
				<u>Supplier</u>
				<table width="100%">
					<tr>
						<td width="40%">Nama Supplier</td>
						<td width="2%">:</td>
						<td><?= $data[0]->nm_supplier ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>:</td>
						<td><?= $data[0]->alamat_supplier ?></td>
					</tr>
					<tr>
						<td>No Telepon</td>
						<td>:</td>
						<td><?= $data[0]->no_telp_supplier ?></td>
					</tr>
					<tr>
						<td>Tgl Kirim</td>
						<td>:</td>
						<td><?= $data[0]->Kirim_time ?></td>
					</tr>
				</table>
			</td>
			<td width="20%"></td>
			<td width="40%" align="center">
				<u>Dikirim Ke Store</u>
				<table width="100%">
					<tr>
						<td width="30%">Store</td>
						<td width="2%">:</td>
						<td><?= $data[0]->nm_toko ?></td>
					</tr>
					<tr>
						<td>Alamat</td>
						<td>:</td>
						<td><?= $data[0]->alamat ?></td>
					</tr>
					<tr>
						<td>No Telepon</td>
						<td>:</td>
						<td><?= $data[0]->no_telp ?></td>
					</tr>
					<tr>
						<td>Tgl Terima</td>
						<td>:</td>
						<td><?= $data[0]->Terima_time ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>    
    <br>
    <table class="table" border="1" id="table-produk" style="width: 90%" align="center">
        <thead>
            <tr>
                <th width="2%">No</th>
                <th width="15%">Produk</th>
                <th width="10%">Harga</th>
                <th width="10%">Qty</th>
                <?php if ($data[0]->status == 'Terima'): ?>
	                <th width="10%">Qty Terima</th>
                <?php endif ?>
                <th width="10%">Value</th>
                <th width="15%">Potongan</th>
                <th width="10%">Total</th>
                <?php if ($data[0]->status == 'Terima'): ?>
	                <th width="10%">BatchNo</th>
	                <th width="15%">ED</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
          	<?php 
	          	$no = 1; 
	          	$tot_qty = $tot_qty_terima = $tot_value = $tot_potongan = $tot_total = 0;
	          	foreach ($data as $r){ ?>
	          		<tr>
	          			<td><?= $no ?></td>
	          			<td><?= $r->nm_produk ?></td>
	          			<td><?= number_format($r->harga) ?></td>
	          			<td align="center"><?= $r->qty ?></td>
	          			<?php if ($data[0]->status == 'Terima'): ?>
		          			<td align="center"><?= $r->qty_terima ?></td>
	          			<?php endif ?>
	          			<td align="right"><?= number_format($r->value) ?></td>
	          			<td align="right"><?= number_format($r->potongan) ?></td>
	          			<td align="right"><?= number_format($r->total) ?></td>
	          			<?php if ($data[0]->status == 'Terima'): ?>
		          			<td><?= $r->BatchNo ?></td>
		          			<td><?= $r->ExpDate ?></td>
	          			<?php endif ?>
	          		</tr>
          	<?php 
          		$no++;
          		$tot_qty += $r->qty;
				$tot_qty_terima += $r->qty_terima;
				$tot_value += $r->value;
				$tot_potongan += $r->potongan;
				$tot_total += $r->total;
          	} ?>
        </tbody>
        <tfoot>
        	<th colspan="3">Total</th>
        	<th ><?= $tot_qty ?></th>
            <?php if ($data[0]->status == 'Terima'): ?>
                <th ><?= $tot_qty_terima ?></th>
            <?php endif ?>
            <th align="right"><?= number_format($tot_value) ?></th>
            <th align="right"><?= number_format($tot_potongan) ?></th>
            <th align="right"><?= number_format($tot_total) ?></th>
            <?php if ($data[0]->status == 'Terima'): ?>
                <th ></th>
                <th ></th>
            <?php endif ?>
        </tfoot>
      </table>  
    
    
</div>

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){ 
		
	});

	$(window).load(function() { window.print(); });
</script>




</body>
</html>
