<?php
    $settingResult = $this->db->get_where('m_setting');
    $settingData = $settingResult->row();

    $setting_site_logo = $settingData->logo;

    $orderData = $this->db->query("
	    		SELECT
				  a.id_produk,
				  a.`harga`,
				  a.nm_produk,
				  a.diskon,
				  SUM(a.`qty`) AS qty,
				  SUM(a.`total`) AS total,
				  b.`add_time`,
				  b.id_pelanggan,
				  b.`nm_pelanggan`,
				  b.diskon_pelanggan,
				  b.cash
				FROM
				  tr_penjualan_detail a
				  JOIN tr_penjualan_header b
				    ON a.id_penjualan = b.id_penjualan
				WHERE a.id_penjualan = '$id_penjualan'
				GROUP BY a.`id_produk`
			")->result();

    if (count($orderData) == 0) {
        $this->session->set_flashdata('alert_msg', array('success', 'Error', 'Something Wrong!'));
        redirect(base_url().'pos');

        die();
    }

    

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Sale No : <?php echo $id_penjualan; ?></title>
		<script src="<?=base_url()?>assets/js/jquery-1.7.2.min.js"></script>
		
<style type="text/css" media="all">
	body { 
		max-width: 300px; 
		margin:0 auto; 
		text-align:center; 
		color:#000; 
		font-family: Arial, Helvetica, sans-serif; 
		font-size:12px; 
	}
	#wrapper { 
		min-width: 250px; 
		margin: 0px auto; 
	}
	#wrapper img { 
		max-width: 300px; 
		width: auto; 
	}

	h2, h3, p { 
		margin: 5px 0;
	}
	.left { 
		width:100%; 
		float:right; 
		text-align:right; 
		margin-bottom: 3px;
		margin-top: 3px;
	}
	.right { 
		width:40%; 
		float:right; 
		text-align:right; 
		margin-bottom: 3px; 
	}
	.table, .totals { 
		width: 100%; 
		margin:10px 0; 
	}
	.table th { 
		border-top: 1px solid #000; 
		border-bottom: 1px solid #000; 
		padding-top: 4px;
		padding-bottom: 4px;
	}
	.table td { 
		padding:0; 
	}
	.totals td { 
		width: 24%; 
		padding:0; 
	}
	.table td:nth-child(2) { 
		overflow:hidden; 
	}

	@media print {
		body { text-transform: uppercase; }
		#buttons { display: none; }
		#wrapper { width: 100%; margin: 0; font-size:9px; }
		#wrapper img { max-width:300px; width: 80%; }
		#bkpos_wrp{
			display: none;
		}
	}
</style>
</head>

<body>
<div id="wrapper">
	<table border="0" style="border-collapse: collapse; width: 100%; height: auto;">
	    <tr>
		    <td width="100%" align="center">
			    <center>
			    	<img src="<?=base_url()?>assets/gambar/<?php echo $setting_site_logo; ?>" style="width: 60px;" />
			    </center>
		    </td>
	    </tr>
	    <tr>
		    <td width="100%" align="center">
			    <h2 style="padding-top: 0px; font-size: 24px;"><strong><?php echo $settingData->nm_toko; ?></strong></h2>
		    </td>
	    </tr>
		<tr>
			<td width="100%">
				<span class="left" style="text-align: left;">Alamat : <?php echo $settingData->alamat; ?></span>	
				<span class="left" style="text-align: left;">No Telepon : <?php echo $settingData->no_telp; ?></span>
				<span class="left" style="text-align: left;">Waktu : <?= $orderData[0]->add_time ?></span>
				<span class="left" style="text-align: left;">Pelanggan&nbsp; : <?= $orderData[0]->id_pelanggan." | ".$orderData[0]->nm_pelanggan ?></span> 
				
			</td>
		</tr>   
    </table>
    
	
	    
	<div style="clear:both;"></div>
    
	<table class="table" cellspacing="0"  border="0"> 
		<thead> 
			<tr> 
				<th width="10%"><em>#</em></th> 
				<th width="35%" align="left">Produk</th>
				<th width="10%">Qty</th>
				<th width="25%">Harga</th>
				<th width="20%" align="right">Total</th> 
			</tr> 
		</thead> 
		<tbody> 
		<?php
            $total_item_amt = 0;
            $total_item_qty = 0;

            // $orderItemResult = $this->db->query("SELECT * FROM order_items WHERE order_id = '$id_penjualan' ORDER BY id ");
            $orderItemData = $orderData;
            for ($i = 0; $i < count($orderData); ++$i) {
                $pcode = $orderItemData[$i]->id_produk;
                $name = $orderItemData[$i]->nm_produk;

                if ($orderItemData[$i]->diskon > 0 ) {
                	$name = $orderItemData[$i]->nm_produk . " (".$orderItemData[$i]->diskon."%)";
                }
                $qty = $orderItemData[$i]->qty;
                $price = $orderItemData[$i]->harga;

                $each_row_price = 0;
                $each_row_price = $orderItemData[$i]->total;

                $total_item_amt += $each_row_price; ?>
				<tr>
	            	<td style="text-align:center; width:30px;" valign="top"><?php echo $i + 1; ?></td>
	                <td style="text-align:left; width:130px; padding-bottom: 10px" valign="top"><?php echo $name; ?><br /></td>
	                <td style="text-align:center; width:50px;" valign="top"><?php echo $qty; ?></td>
	                <td style="text-align:center; width:50px;" valign="top"><?php echo number_format($price, 0, '.', '.'); ?></td>
	                <td style="text-align:right; width:70px;" valign="top"><?php echo number_format($each_row_price, 0, '.', '.'); ?></td>
				</tr>	
		<?php
                $total_item_qty += $qty;

                unset($pcode);
                unset($name);
                unset($qty);
                unset($price);
            }
            unset($orderItemResult);
            unset($orderItemData);

            $potongan_pelanggan = ($orderData[0]->diskon_pelanggan / 100) * $total_item_amt;
            $grand_total = $total_item_amt - $potongan_pelanggan;
            $kembalian = $orderData[0]->cash - $grand_total;
        ?>
			 
    	</tbody> 
	</table> 
	
    
    <table class="totals" cellspacing="0" border="0" style="margin-bottom:5px; border-top: 1px solid #000; border-collapse: collapse;">
    	<tbody>
			<tr>
				<td style="text-align:left; padding-top: 5px;">Total Item</td>
				<td style="text-align:right; padding-right:1.5%; border-right: 1px solid #000;font-weight:bold;"><?php echo $total_item_qty; ?></td>
				<td style="text-align:left; padding-left:1.5%;">Sub Total</td>
				<td style="text-align:right;font-weight:bold;"><?php echo number_format($total_item_amt, 0, '.', '.'); ?></td>
			</tr>
    
			
			<tr>
				<td style="text-align:left; padding-top: 5px;">&nbsp;</td>
				<td style="text-align:right; padding-right:1.5%; border-right: 1px solid #000;font-weight:bold;">&nbsp;</td>
				<td style="text-align:left; padding-left:1.5%;">Member(<?= $orderData[0]->diskon_pelanggan ?>%)</td>
				<td style="text-align:right;font-weight:bold;">- <?php echo number_format($potongan_pelanggan, 0, '.', '.'); ?></td>
			</tr>
			<tr>
				<td style="text-align:left; padding-top: 5px;">&nbsp;</td>
				<td style="text-align:right; padding-right:1.5%; border-right: 1px solid #000;font-weight:bold;">&nbsp;</td>
				<td style="text-align:left; padding-left:1.5%;">Grand Total</td>
				<td style="text-align:right;font-weight:bold;"><?php echo number_format($grand_total, 0, '.', '.'); ?></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:left; font-weight:bold; border-top:1px solid #000; padding-top:5px;">Cash</td>
				<td colspan="2" style="border-top:1px solid #000; padding-top:5px; text-align:right; font-weight:bold;"><?php echo number_format($orderData[0]->cash,0, '.', '.'); ?></td>
    		</tr>
    		
			<tr>    
				<td colspan="2" style="text-align:left; font-weight:bold; padding-top:5px;">Kembali</td>
				<td colspan="2" style="padding-top:5px; text-align:right; font-weight:bold;"><?php echo number_format($kembalian, 0, '.', '.'); ?></td>
    		</tr>
    		
           
	    	
    </tbody>
    </table>
    
    <div style="border-top:1px solid #000; padding-top:10px;">
    	Terimakasih Sudah Berlangganan
    </div>
<!--
        <div id="buttons" style="padding-top:10px; text-transform:uppercase;">
    <span class="left"><a href="#" style="width:90%; display:block; font-size:12px; text-decoration: none; text-align:center; color:#000; background-color:#4FA950; border:2px solid #4FA950; padding: 10px 1px; font-weight:bold;" id="email">Email</a></span>
    <span class="right"><button type="button" onClick="window.print();return false;" style="width:100%; cursor:pointer; font-size:12px; background-color:#FFA93C; color:#000; text-align: center; border:1px solid #FFA93C; padding: 10px 1px; font-weight:bold;">Print</button></span>
    <div style="clear:both;"></div>
-->
   
   
    <input type="hidden" id="id" value="<?php echo $id_penjualan; ?>" />
    
</div>

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){ 
		$('#email').click( function(){
			var email 	= prompt("Please enter email address","test@mail.com");	
			var id 		= document.getElementById("id").value;
			
			$.ajax({
				type: "POST",
				url: "<?=base_url()?>pos/send_invoice",
				data: { email: email, id: id}
			}).done(function( msg ) {
			      alert( "Successfully Sent Receipt to "+email);
			});
			
		});
	});

	$(window).load(function() { window.print(); });
</script>




</body>
</html>
