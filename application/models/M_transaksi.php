<?php
class M_transaksi extends CI_Model{
 	
 	function __construct(){
        parent::__construct();
        
        date_default_timezone_set('Asia/Jakarta');
        $this->username = $this->session->userdata('username');
        $this->id_toko = $this->session->userdata('id_toko');
        $this->nm_toko = $this->session->userdata('nm_toko');

        if ($this->id_toko == "-" || $this->id_toko == "") {
            $this->id_toko = "T0001";
            $this->nm_toko = "Cabang Pusat";
        }
        
    }

    function get_data_max($table,$kolom){
        $query = "SELECT IFNULL(LPAD(MAX(RIGHT($kolom,4))+1,4,0),'0001')AS nomor FROM $table";
        return $this->db->query($query)->row("nomor");
    }

    function get_data_tr_max($table,$kolom){
        $query = "SELECT IFNULL(LPAD(MAX(RIGHT($kolom,4))+1,4,0),'0001')AS nomor FROM $table WHERE YEAR(tanggal) = YEAR(CURDATE)";
        return $this->db->query($query)->row("nomor");
    }

    function tr_promosi($table,$status){
        $params =(object)$this->input->post();
        

        foreach ($params->id_produk as $key => $value) 
        {
            $produk = explode("|", $params->id_produk[$key]);
            $id_produk = $produk[0];
            $nm_produk = $produk[1];
            $id = "PR".$this->get_data_max($table,"id_promosi");

            $data = array(
                'tgl_mulai'  => $params->tgl_mulai,
                'tgl_akhir'  => $params->tgl_akhir,
                'id_produk'  => $id_produk,
                'nm_produk'  => $nm_produk,
                'diskon'  => $params->diskon[$key],
                'add_user'  => $this->username
            );

             if ($status == 'insert') {
                $this->db->set("id_promosi",$id);
                $result= $this->db->insert($table,$data);
             }else{

                $this->db->set("edit_user", $this->username);
                $this->db->set("edit_time", date('Y-m-d H:i:s'));
                $result= $this->db->update($table,$data,array('id_promosi' => $params->id_promosi));
             }
        }

        return $result;
    }

    function checkout_(){
        $params =(object)$this->input->post();
        
        $id_penjualan = date("ymd").$this->get_data_max("tr_penjualan_header","id_penjualan");

        $tot_value = $tot_potongan = 0;
        foreach ($params->produk as $key => $value) 
        {
            $produk = explode("|", $params->produk[$key]);
            $id_produk = $produk[0];
            $nm_produk = $produk[1];

            $data = array(
                'id_penjualan'  => $id_penjualan,
                'tanggal'  => date('Y-m-d'),
                'id_pelanggan'  => ($params->nm_pelanggan == '') ? '' : $params->pelanggan,
                'nm_pelanggan'  => ($params->nm_pelanggan == '') ? $params->pelanggan : $params->nm_pelanggan,
                'id_produk'  => $id_produk,
                'nm_produk'  => $nm_produk,
                'harga'  => $params->harga[$key],
                'qty'  => $params->qty[$key],
                'value'  => $value = $params->harga[$key] * $params->qty[$key],
                'diskon'  => $params->dsc_produk[$key],
                'potongan'  => $potongan =  ($params->dsc_produk[$key] / 100) * $value,
                'total'  => $value - $potongan,
                'id_promosi'  => $params->id_promosi[$key],
                'add_user'  => $this->username
            );

            $result= $this->db->insert("tr_penjualan_detail",$data); //insert detail

            
            $tot_value += $value;
            $tot_potongan += $potongan;

        }

        $data = array(
            'id_penjualan'  => $id_penjualan,
            'tanggal'  => date('Y-m-d'),
            'id_pelanggan'  => ($params->nm_pelanggan == '') ? '' : $params->pelanggan,
            'nm_pelanggan'  => ($params->nm_pelanggan == '') ? $params->pelanggan : $params->nm_pelanggan,
            'jumlah'  => $tot_value,
            'diskon_pelanggan'  => $params->dsc_member1,
            'potongan_diskon'  => $tot_potongan,
            'total'  => $value - $tot_potongan - (($params->dsc_member1 / 100) * $tot_value),
            'cash'  => $params->cash,
            'add_user'  => $this->username
        );

        $result= $this->db->insert("tr_penjualan_header",$data); //insert header

        return ["status" => $result , "id_penjualan" => $id_penjualan];
    }

    function checkout(){
        $params =(object)$this->input->post();
        
        $id_penjualan = date("ymd").$this->get_data_max("tr_penjualan_header","id_penjualan");

        $tot_value = $tot_potongan = 0;

        foreach ($params->produk as $key => $value) 
        {
            $produk = explode("|", $params->produk[$key]);
            $id_produk = $produk[0];
            $nm_produk = $produk[1];
            $batch = $this->Batch($id_produk);

           foreach ($batch as $b) {
                
                if ($params->qty[$key] <= $b->qty) {
                    $data = array(
                        'id_penjualan'  => $id_penjualan,
                        'tanggal'  => date('Y-m-d'),
                        'id_pelanggan'  => ($params->nm_pelanggan == '') ? '' : $params->pelanggan,
                        'nm_pelanggan'  => ($params->nm_pelanggan == '') ? $params->pelanggan : $params->nm_pelanggan,
                        'id_produk'  => $id_produk,
                        'nm_produk'  => $nm_produk,
                        'harga'  => $params->harga[$key],
                        'qty'  => $params->qty[$key],
                        'value'  => $value = $params->harga[$key] * $params->qty[$key],
                        'diskon'  => $params->dsc_produk[$key],
                        'potongan'  => $potongan =  ($params->dsc_produk[$key] / 100) * $value,
                        'total'  => $value - $potongan,
                        'BatchNo'  => $b->BatchNo,
                        'ExpDate'  => $b->ExpDate,
                        'NoDokumen'  => $b->NoDokumen,
                        'id_toko'  => $this->id_toko,
                        'nm_toko'  => $this->nm_toko,
                        'add_user'  => $this->username
                    );

                    $result= $this->db->insert("tr_penjualan_detail",$data); 

                    $tot_value += $value;
                    $tot_potongan += $potongan;
                    
                    $this->stok_out($id_produk,$this->id_toko,$b->BatchNo,$b->ExpDate,$b->NoDokumen,$params->qty[$key]);
                    break;
                }

                if (($params->qty[$key] > $b->qty)) {
                    

                    if ($params->qty[$key] > $b->qty) {
                        $jml = $b->qty;
                    } elseif ($params->qty[$key] <= $b->qty) {
                        $jml = $params->qty[$key];
                    } else {
                        $jml = 0;
                    }

                    

                    $data = array(
                        'id_penjualan'  => $id_penjualan,
                        'tanggal'  => date('Y-m-d'),
                        'id_pelanggan'  => ($params->nm_pelanggan == '') ? '' : $params->pelanggan,
                        'nm_pelanggan'  => ($params->nm_pelanggan == '') ? $params->pelanggan : $params->nm_pelanggan,
                        'id_produk'  => $id_produk,
                        'nm_produk'  => $nm_produk,
                        'harga'  => $params->harga[$key],
                        'qty'  => $jml,
                        'value'  => $value = $params->harga[$key] * $jml,
                        'diskon'  => $params->dsc_produk[$key],
                        'potongan'  => $potongan =  ($params->dsc_produk[$key] / 100) * $value,
                        'total'  => $value - $potongan,
                        'BatchNo'  => $b->BatchNo,
                        'ExpDate'  => $b->ExpDate,
                        'id_toko'  => $this->id_toko,
                        'nm_toko'  => $this->nm_toko,
                        'add_user'  => $this->username
                    );

                    $result= $this->db->insert("tr_penjualan_detail",$data); 

                    $this->stok_out($id_produk,$this->id_toko,$b->BatchNo,$b->ExpDate,$b->NoDokumen,$jml);

                    $tot_value += $value;
                    $tot_potongan += $potongan;
                }
           }

        }


        $data = array(
            'id_penjualan'  => $id_penjualan,
            'tanggal'  => date('Y-m-d'),
            'id_pelanggan'  => ($params->nm_pelanggan == '') ? '' : $params->pelanggan,
            'nm_pelanggan'  => ($params->nm_pelanggan == '') ? $params->pelanggan : $params->nm_pelanggan,
            'jumlah'  => $tot_value,
            'diskon_pelanggan'  => $params->dsc_member1,
            'potongan_diskon'  => $tot_potongan,
            'total'  => $tot_value - $tot_potongan - (($params->dsc_member1 / 100) * $tot_value),
            'cash'  => $params->cash,
            'id_toko'  => $this->id_toko,
            'nm_toko'  => $this->nm_toko,
            'add_user'  => $this->username
        );

        $result= $this->db->insert("tr_penjualan_header",$data); //insert header

        return ["status" => $result , "id_penjualan" => $id_penjualan];
    }

    function stok_out($id_produk = '',$id_toko = '',$BatchNo = '',$ExpDate = '',$NoDokumen = '',$qty = 0){

        $query = $this->db->query("
                UPDATE trs_invdet
                SET 
                    qty = qty - $qty
                WHERE 
                    id_toko = '$id_toko'
                    AND id_produk = '$id_produk'
                    AND BatchNo = '$BatchNo'
                    AND ExpDate = '$ExpDate'
                    AND NoDokumen = '$NoDokumen'
                    AND tahun = YEAR(CURDATE())
            ");

        if ($query) {
            $query = $this->db->query("
                UPDATE trs_invsum
                SET 
                    qty = qty - $qty
                WHERE 
                    id_toko = '$id_toko'
                    AND id_produk = '$id_produk'
                    AND tahun = YEAR(CURDATE())
            ");
        }

        return $query;

    }

    function Batch($id_produk){
        $query = $this->db->query("
            SELECT id_produk,qty,BatchNo,ExpDate,NoDokumen
            FROM trs_invdet 
            WHERE 
                id_toko='".$this->id_toko."'
                AND id_produk='".$id_produk."'
                AND Qty > 0
            ORDER BY ExpDate ")->result();
        return $query;
    }

    function pembelian(){
        $result = false;
        $params = (object)$this->input->post();

        $periode = date('ym');
        $tanggal = date('Y-m-d');
        $id = "PO".$periode.$this->get_data_max("tr_pembelian_header","id_pembelian");

        $supplier = explode("-", $params->supplier);
        $store = explode("-", $params->store);
        $tot_qty = $tot_total = 0;

        foreach ($params->id_produk as $key => $value) 
        {
            if ($params->status_hapus[$key] != 'ya') {
                $value = $params->harga[$key] * $params->qty[$key];

                $data = array(
                    'id_pembelian'  => $id,
                    'tanggal'  => $tanggal,
                    'id_supplier'  => $supplier[0],
                    'nm_supplier'  => $supplier[1],
                    'alamat_supplier'  => $supplier[2],
                    'no_telp_supplier'  => $supplier[3],
                    'id_produk'  => $params->id_produk[$key],
                    'nm_produk'  => $params->nm_produk[$key],
                    'harga'  => $params->harga[$key],
                    'qty'  => $params->qty[$key],
                    'value'  => $value,
                    'potongan'  => 0,
                    'total'  => $value,
                    'id_toko'  => $store[0],
                    'nm_toko'  => $store[1],
                    'alamat'  => $store[2],
                    'no_telp'  => $store[3],
                    'status'  => "Open",
                );

                $result= $this->db->insert("tr_pembelian_detail",$data);

                $tot_qty += $params->qty[$key];
                $tot_total += $value;
            }
        }

        $data = array(
            'id_pembelian'  => $id,
            'tanggal'  => $tanggal,
            'id_supplier'  => $supplier[0],
            'nm_supplier'  => $supplier[1],
            'alamat_supplier'  => $supplier[2],
            'jml_qty'  => $tot_qty,
            'value'  => $tot_total,
            'potongan'  => 0,
            'total'  => $tot_total,
            'id_toko'  => $store[0],
            'nm_toko'  => $store[1],
            'alamat'  => $store[2],
            'no_telp'  => $store[3],
            'status'  => "Open",
        );

        $result= $this->db->insert("tr_pembelian_header",$data);


        return $result;
    }

    function stok_transfer(){
        $result = false;
        $params = (object)$this->input->post();

        $periode = date('ym');
        $tanggal = date('Y-m-d');
        $id = "RK".$periode.$this->get_data_max("tr_relokasi_header","id_relokasi");

        $store = explode("-", $params->store);
        $tot_qty = $tot_total = 0;

        $store_pengirim = $this->db->query("SELECT * FROM m_toko WHERE id_toko = '".$this->id_toko."' ")->row();

        foreach ($params->id_produk as $key => $value) 
        {
            if ($params->status_hapus[$key] != 'ya') {

                $data = array(
                    'id_relokasi'  => $id,
                    'tanggal'  => $tanggal,
                    'id_produk'  => $params->id_produk[$key],
                    'nm_produk'  => $params->nm_produk[$key],
                    'qty'  => $params->qty[$key],
                    'BatchNo'  => $params->BatchNo[$key],
                    'ExpDate'  => $params->ExpDate[$key],
                    'NoDokumen'  => $params->NoDokumen[$key],
                    'id_toko_penerima'  => $store[0],
                    'nm_toko_penerima'  => $store[1],
                    'alamat_penerima'  => $store[2],
                    'no_telp_penerima'  => $store[3],
                    'id_toko'  => $store_pengirim->id_toko,
                    'nm_toko'  => $store_pengirim->nm_toko,
                    'alamat'  => $store_pengirim->alamat,
                    'no_telp'  => $store_pengirim->no_telp,
                    'status'  => "Kirim",
                );

                $result= $this->db->insert("tr_relokasi_detail",$data);

                $this->stok_out(
                    $params->id_produk[$key],
                    $store_pengirim->id_toko,
                    $params->BatchNo[$key],
                    $params->ExpDate[$key],
                    $params->NoDokumen[$key],
                    $params->qty[$key]
                );

                $tot_qty += $params->qty[$key];
            }
        }

        $data = array(
            'id_relokasi'  => $id,
            'tanggal'  => $tanggal,
            'jml_qty'  => $tot_qty,
            'id_toko_penerima'  => $store[0],
            'nm_toko_penerima'  => $store[1],
            'alamat_penerima'  => $store[2],
            'no_telp_penerima'  => $store[3],
            'id_toko'  => $store_pengirim->id_toko,
            'nm_toko'  => $store_pengirim->nm_toko,
            'alamat'  => $store_pengirim->alamat,
            'no_telp'  => $store_pengirim->no_telp,
            'status'  => "Kirim",
        );

        $result= $this->db->insert("tr_relokasi_header",$data);


        return $result;
    }

    function update_trs_beli(){
        $params = (object)$this->input->post();
        $tot_qty = $tot_value = $tot_potongan = $tot_total = 0;

        foreach ($params->id_produk as $key => $value) 
        {
            
            if ($params->aksi == 'Kirim') {
                $value = $params->harga[$key] * $params->qty[$key];
                $tot_qty += $params->qty[$key];
            }else{
                $value = $params->harga[$key] * $params->qty_terima[$key];
                $tot_qty += $params->qty_terima[$key];
            }

            $potongan = $params->potongan[$key];
            $total = $value - $potongan;

            $data = array(
                'qty'  => $params->qty[$key],
                'qty_terima'  => $params->qty_terima[$key],
                'value'  => $value,
                'potongan'  => $potongan,
                'total'  => $total,
                'BatchNo'  => $params->BatchNo[$key],
                'ExpDate'  => $params->ExpDate[$key],
            );

            $this->db->where("id_pembelian", $params->id_pembelian);
            $this->db->where("id_produk", $params->id_produk[$key]);
            $result= $this->db->update("tr_pembelian_detail",$data);

            
            $tot_value += $value;
            $tot_potongan += $potongan;
            $tot_total += $total;
            
        }

        $this->db->set("jml_qty", $tot_qty);
        $this->db->set("value", $tot_value);
        $this->db->set("potongan", $tot_potongan);
        $this->db->set("total", $tot_total);

        $this->db->where("id_pembelian", $params->id_pembelian);
        $result= $this->db->update("tr_pembelian_header");

        if ($params->aksi == 'Terima') {
            $this->update_beli("Terima",$params->id_pembelian);
            $this->insert_stok("tr_pembelian_detail",$params->id_pembelian,"qty_terima","id_pembelian");
        }

        return $result;
    }

    function update_beli($status,$id){
        if ($status != 'Batal') {
            
            $this->db->set($status."_time", date('Y-m-d H:i:s'));
            $this->db->set($status."_user", $this->username);
        }
        $this->db->set("status", $status);
        $this->db->where("id_pembelian", $id);

        $this->db->update("tr_pembelian_header");


        if ($status != 'Batal') {
            
            $this->db->set($status."_time", date('Y-m-d H:i:s'));
            $this->db->set($status."_user", $this->username);
        }
        $this->db->set("status", $status);
        $this->db->where("id_pembelian", $id);

        return $this->db->update("tr_pembelian_detail");

    }

    function insert_stok($table,$id,$field_qty,$where_field,$opt = ''){

        if ($opt == "Relokasi Terima") {
            $data = $this->db->query("SELECT id_toko_penerima as id_toko,nm_toko_penerima as nm_toko,id_produk,nm_produk,BatchNo,ExpDate,$field_qty as Qty FROM $table WHERE $where_field = '".$id."'  ")->result();
        }else if ($opt == "Batal Relokasi") {
            $data = $this->db->query("SELECT id_toko as id_toko,nm_toko as nm_toko,id_produk,nm_produk,BatchNo,ExpDate,NoDokumen,$field_qty as Qty FROM $table WHERE $where_field = '".$id."'  ")->result();
        }else if ($opt == "Batal Koreksi") {
            $data = $this->db->query("SELECT id_toko as id_toko,nm_toko as nm_toko,id_produk,nm_produk,BatchNo,ExpDate,NoDokumen,$field_qty as Qty FROM $table WHERE $where_field = '".$id."'  ")->result();
        }else if ($opt == "Batal Penjualan") {
            $data = $this->db->query("SELECT id_toko as id_toko,nm_toko as nm_toko,id_produk,nm_produk,BatchNo,ExpDate,NoDokumen,$field_qty as Qty FROM $table WHERE $where_field = '".$id."'  ")->result();

            $id = $data[0]->NoDokumen; // balik ke Dokumen awal
        }else{

            $data = $this->db->query("SELECT id_toko,nm_toko,id_produk,nm_produk,BatchNo,ExpDate,$field_qty as Qty FROM $table WHERE $where_field = '".$id."'  ")->result();
        }
        
        if ($opt == "Batal Relokasi" || $opt == "Batal Koreksi") {
            $id = $data[0]->NoDokumen;
        }
        
        foreach ($data as $r) {
            // detail
            $cek = $this->db->query(
                        "SELECT * FROM trs_invdet 
                         WHERE  
                            id_produk = '".$r->id_produk."'
                            AND BatchNo = '".$r->BatchNo."'
                            AND ExpDate = '".$r->ExpDate."'
                            AND NoDokumen = '".$id."'
                            AND id_toko = '".$r->id_toko."'
                            AND tahun = YEAR(CURDATE())
                    ");

            if ($cek->num_rows() == 0) {
                $data = array(
                    'id_toko'  => $r->id_toko,
                    'nm_toko'  => $r->nm_toko,
                    'id_produk'  => $r->id_produk,
                    'nm_produk'  => $r->nm_produk,
                    'BatchNo'  => $r->BatchNo,
                    'ExpDate'  => $r->ExpDate,
                    'NoDokumen'  => $id,
                    'qty'  => $r->Qty,
                    'tahun'  => date('Y'),
                );

                $result= $this->db->insert("trs_invdet",$data);
            }else{
                $this->db->set("qty",'qty+'.$r->Qty,FALSE);
                $this->db->where("id_toko",$r->id_toko);
                $this->db->where("id_produk",$r->id_produk);
                $this->db->where("BatchNo",$r->BatchNo);
                $this->db->where("ExpDate",$r->ExpDate);
                $this->db->where("NoDokumen",$id);
                $this->db->where("tahun",date('Y'));
                $result= $this->db->update("trs_invdet");
            }

            // summary
            $cek = $this->db->query(
                        "SELECT * FROM trs_invsum 
                         WHERE  
                            id_produk = '".$r->id_produk."'
                            AND id_toko = '".$r->id_toko."'
                    ");

            if ($cek->num_rows() == 0) {
                $data = array(
                    'id_toko'  => $r->id_toko,
                    'nm_toko'  => $r->nm_toko,
                    'id_produk'  => $r->id_produk,
                    'nm_produk'  => $r->nm_produk,
                    'qty'  => $r->Qty,
                    'tahun'  => date('Y'),
                );

                $result= $this->db->insert("trs_invsum",$data);
            }else{
                $this->db->set("qty",'qty+'.$r->Qty,FALSE);
                $this->db->where("id_toko",$r->id_toko);
                $this->db->where("id_produk",$r->id_produk);
                $this->db->where("tahun",date('Y'));
                $result= $this->db->update("trs_invsum");
            }
        }

    }

    function get_inv(){
        $params =(object)$this->input->post();

        $query = $this->db->query("SELECT * FROM trs_invdet WHERE id_toko = '".$params->id_toko."' AND id_produk = '".$params->id_produk."'
		 ORDER BY ExpDate")->result();

        return $query;
    }

    function kartu_stok($id_toko = '',$id_produk = '',$tgl = ''){
        $tgl_awal =  date('Y-m-01', strtotime($tgl));
        $tahun =  date('Y', strtotime($tgl));
        $bulan =  date('m', strtotime($tgl));

        $query = $this->db->query("
                SELECT 
                    '' Tanggal,'' as waktu ,'' as NoDokumen,CONCAT('Saldo Awal') as Keterangan,
                    ifnull(SA_".$bulan.",0) as qty_in,
                    0 as qty_out,
                    '' BatchNo,'' ExpDate
               FROM trs_invsum 
                    WHERE 
                    tahun = '$tahun' 
                    AND id_toko ='$id_toko'
                    AND id_produk ='$id_produk'  
                UNION ALL
                SELECT * FROM 
                (
                    SELECT 
                        Tanggal,add_time as waktu,id_pembelian as NoDokumen, CONCAT('Pembelian : ',nm_supplier) as Keterangan,
                        qty_terima as qty_in,
                        0 as qty_out,
                        BatchNo,ExpDate
                    FROM tr_pembelian_detail 
                    WHERE 
                        status='Terima' 
                        AND id_produk = '$id_produk'
                        AND id_toko = '$id_toko'
                        AND DATE_FORMAT(Tanggal,'%Y-%m') = DATE_FORMAT('$tgl','%Y-%m')
                        AND Tanggal <= '$tgl'

                    UNION ALL
                    SELECT 
                        Tanggal,add_time as waktu,id_penjualan as NoDokumen, CONCAT('Penjualan : ',nm_pelanggan) as Keterangan,
                        0 as qty_in,
                        qty as qty_out,
                        BatchNo,ExpDate
                    FROM tr_penjualan_detail 
                    WHERE 
                        status in ('Closed','Batal')
                        AND id_produk = '$id_produk'
                        AND id_toko = '$id_toko'
                        AND DATE_FORMAT(Tanggal,'%Y-%m') = DATE_FORMAT('$tgl','%Y-%m')
                        AND Tanggal <= '$tgl'

                    UNION ALL
                    SELECT 
                        Tanggal,add_time as waktu,id_penjualan as NoDokumen, CONCAT('Batal Penjualan : ',nm_pelanggan) as Keterangan,
                        qty as qty_in,
                        0 as qty_out,
                        BatchNo,ExpDate
                    FROM tr_penjualan_detail 
                    WHERE 
                        status in ('Batal')
                        AND id_produk = '$id_produk'
                        AND id_toko = '$id_toko'
                        AND DATE_FORMAT(Tanggal,'%Y-%m') = DATE_FORMAT('$tgl','%Y-%m')
                        AND Tanggal <= '$tgl'

                    UNION ALL
                    SELECT 
                        Tanggal,add_time as waktu,id_relokasi as NoDokumen, CONCAT('Relokasi ke : ',nm_toko_penerima) as Keterangan,
                        0 as qty_in,
                        qty as qty_out,
                        BatchNo,ExpDate
                    FROM tr_relokasi_detail 
                    WHERE 
                        status IN ('Kirim','Terima') 
                        AND id_produk = '$id_produk'
                        AND id_toko = '$id_toko'
                        AND DATE_FORMAT(Tanggal,'%Y-%m') = DATE_FORMAT('$tgl','%Y-%m')
                        AND Tanggal <= '$tgl'

                    UNION ALL
                    SELECT 
                        date(Terima_time) as Tanggal,Terima_time as waktu,id_relokasi as NoDokumen, CONCAT('Relokasi Dari : ',nm_toko) as Keterangan,
                        qty as qty_in,
                        0 as qty_out,
                        BatchNo,ExpDate
                    FROM tr_relokasi_detail 
                    WHERE 
                        status IN ('Terima') 
                        AND id_produk = '$id_produk'
                        AND id_toko_penerima = '$id_toko'
                        AND DATE_FORMAT(date(Terima_time),'%Y-%m') = DATE_FORMAT('$tgl','%Y-%m')
                        AND Tanggal <= '$tgl'
                    UNION ALL
                    SELECT 
                        Tanggal,add_time as waktu,id_koreksi as NoDokumen, CONCAT('Koreksi ',tipe , ' : ',keterangan) as Keterangan,
                        if(tipe = 'Plus',qty,0) as qty_in,
                        if(tipe = 'Minus',qty,0) as qty_out,
                        BatchNo,ExpDate
                    FROM tr_koreksi_detail 
                    WHERE 
                        status IN ('Open') 
                        AND id_produk = '$id_produk'
                        AND id_toko = '$id_toko'
                        AND DATE_FORMAT(Tanggal,'%Y-%m') = DATE_FORMAT('$tgl','%Y-%m')
                        AND Tanggal <= '$tgl'
                )z
                order by waktu
                ");
        
        return $query;
    }

    function get_produk_jual(){
        
        $query = $this->db->query("
            SELECT harga,a.id_produk,nm_produk,id_kategori,nm_kategori,gambar,qty AS stok FROM `m_produk` a
                JOIN 
                (
                SELECT id_produk,id_toko,qty 
                FROM `trs_invsum` 
                WHERE 
                    tahun = YEAR(CURDATE())
                    AND id_toko = '".$this->id_toko."'
                )b
                ON a.id_produk = b.id_produk
                WHERE 
                s_aktif ='Aktif'
                AND b.qty > 0
                order by id_kategori,id_produk

            ")->result();

        return $query;
    }

    function batal_relokasi(){
        $id = $this->input->post('id');

        $this->insert_stok("tr_relokasi_detail",$id,"qty","id_relokasi","Batal Relokasi");
        
        $this->db->set("Status", "Batal");
        $this->db->set("Batal_user", $this->username);
        $this->db->set("Batal_time", date('Y-m-d H:i:s'));
        $this->db->where("id_relokasi", $id);
        $result= $this->db->update("tr_relokasi_detail");

        $this->db->set("Status", "Batal");
        $this->db->set("Batal_user", $this->username);
        $this->db->set("Batal_time", date('Y-m-d H:i:s'));
        $this->db->where("id_relokasi", $id);
        $result= $this->db->update("tr_relokasi_header");

        return $result;
    }

    function batal_adjs(){
        $id = $this->input->post('id');
        $tipe = $this->input->post('tipe');

        if ($tipe == "Minus") {
            $this->insert_stok("tr_koreksi_detail",$id,"qty","id_koreksi","Batal Koreksi");
        }else{
            $data = $this->db->query("SELECT * FROM tr_koreksi_detail WHERE id_koreksi = '$id' ")->result();

            foreach ($data as $r) {
                $this->stok_out($r->id_produk,$r->id_toko,$r->BatchNo,$r->ExpDate,$id,$r->qty);
            }
        }

        
        $this->db->set("Status", "Batal");
        $this->db->set("Batal_user", $this->username);
        $this->db->set("Batal_time", date('Y-m-d H:i:s'));
        $this->db->where("id_koreksi", $id);
        $result= $this->db->update("tr_koreksi_detail");

        $this->db->set("Status", "Batal");
        $this->db->set("Batal_user", $this->username);
        $this->db->set("Batal_time", date('Y-m-d H:i:s'));
        $this->db->where("id_koreksi", $id);
        $result= $this->db->update("tr_koreksi_header");

        return $result;
    }

    function Terima_relokasi(){
        $id = $this->input->post('id');

        $this->insert_stok("tr_relokasi_detail",$id,"qty","id_relokasi","Relokasi Terima");
        
        $this->db->set("Status", "Terima");
        $this->db->set("Terima_user", $this->username);
        $this->db->set("Terima_time", date('Y-m-d H:i:s'));
        $this->db->where("id_relokasi", $id);

        $result= $this->db->update("tr_relokasi_detail");

        $this->db->set("Status", "Terima");
        $this->db->set("Terima_user", $this->username);
        $this->db->set("Terima_time", date('Y-m-d H:i:s'));
        $this->db->where("id_relokasi", $id);
        $result= $this->db->update("tr_relokasi_header");

        return $result;
    }

    function adjusment(){
        $result = false;
        $params = (object)$this->input->post();

        $periode = date('ym');
        $tanggal = date('Y-m-d');
        $id = "KR".$periode.$this->get_data_max("tr_koreksi_header","id_koreksi");
        
        $store_pengirim = $this->db->query("SELECT * FROM m_toko WHERE id_toko = '".$this->id_toko."' ")->row();

        foreach ($params->id_produk as $key => $value) 
        {
            if ($params->status_hapus[$key] != 'ya') {

                $data = array(
                    'id_koreksi'  => $id,
                    'tanggal'  => $tanggal,
                    'tipe'  => $params->tipe,
                    'keterangan'  => $params->keterangan[$key],
                    'id_produk'  => $params->id_produk[$key],
                    'nm_produk'  => $params->nm_produk[$key],
                    'qty'  => $params->qty[$key],
                    'BatchNo'  => $params->BatchNo[$key],
                    'ExpDate'  => $params->ExpDate[$key],
                    'NoDokumen'  => $params->NoDokumen[$key],
                    'id_toko'  => $store_pengirim->id_toko,
                    'nm_toko'  => $store_pengirim->nm_toko,
                    'alamat'  => $store_pengirim->alamat,
                    'no_telp'  => $store_pengirim->no_telp,
                    'status'  => "Open",
                );

                $result= $this->db->insert("tr_koreksi_detail",$data);

                if ($params->tipe == "Minus") {
                    $this->stok_out(
                        $params->id_produk[$key],
                        $store_pengirim->id_toko,
                        $params->BatchNo[$key],
                        $params->ExpDate[$key],
                        $params->NoDokumen[$key],
                        $params->qty[$key]
                    );
                }else{
                    $this->insert_stok("tr_koreksi_detail",$id,"qty","id_koreksi");
                }

            }
        }

        $data = array(
            'id_koreksi'  => $id,
            'tanggal'  => $tanggal,
            'tipe'  => $params->tipe,
            'id_toko'  => $store_pengirim->id_toko,
            'nm_toko'  => $store_pengirim->nm_toko,
            'alamat'  => $store_pengirim->alamat,
            'no_telp'  => $store_pengirim->no_telp,
            'status'  => "Open",
        );

        $result= $this->db->insert("tr_koreksi_header",$data);


        return $result;
    }

    function cek_closing(){
        $cek_closing = $this->db->query("SELECT * FROM m_closing WHERE id_toko ='".$this->id_toko."' ");

        if ($cek_closing->num_rows() == 0) {
            $tgl_closing = "";
            $status = "N";
        }else{
            $tgl_closing = $cek_closing->row('tgl_closing');

            $a =  date('Y-m-d', strtotime("-1 month", strtotime(date('Y-m-d'))));
            $tgl_akhir =  date('Y-m-t', strtotime($a));

            if ($tgl_akhir == $tgl_closing) {
                $status ="Y";
            }else{
                $status ="N";
            }
        }
        
        return array(
            "tgl_closing" => $tgl_closing,
            "status" => $status,
        );
    }

    function cek_stok_closing(){
        $bulan =  date('m', strtotime("-1 month", strtotime(date('Y-m-d'))));
        $periode =  date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));

        $query = $this->db->query("
                    SELECT * FROM(
                        SELECT t_stok_akhir.id_produk,SUM(qty_in) - SUM(qty_out) AS qty_akhir,IFNULL(a.qty,0) AS qty_sum,IFNULL(b.qty,0) AS qty_det FROM(
                                  SELECT 
                                            id_produk,'' Tanggal,'' AS NoDokumen, CONCAT('Saldo Awal') AS Keterangan,
                                            IFNULL(SA_".$bulan.",0) AS qty_in,
                                            0 AS qty_out,
                                            '' BatchNo,'' ExpDate
                                       FROM trs_invsum 
                                            WHERE 
                                            tahun = 'YEAR(CURDATE())' 
                                            AND id_toko ='".$this->id_toko."'

                                        UNION ALL
                                        SELECT * FROM 
                                        (
                                            SELECT 
                                                id_produk,Tanggal,id_pembelian AS NoDokumen, CONCAT('Pembelian : ',nm_supplier) AS Keterangan,
                                                qty_terima AS qty_in,
                                                0 AS qty_out,
                                                BatchNo,ExpDate
                                            FROM tr_pembelian_detail 
                                            WHERE 
                                                STATUS='Terima' 
                                                AND id_toko = '".$this->id_toko."'
                                                AND DATE_FORMAT(Tanggal,'%Y-%m') = '$periode'

                                            UNION ALL
                                            SELECT 
                                                id_produk,Tanggal,id_penjualan AS NoDokumen, CONCAT('Penjualan : ',nm_pelanggan) AS Keterangan,
                                                0 AS qty_in,
                                                qty AS qty_out,
                                                BatchNo,ExpDate
                                            FROM tr_penjualan_detail 
                                            WHERE 
                                                STATUS='Closed' 
                                                AND id_toko = '".$this->id_toko."'
                                                AND DATE_FORMAT(Tanggal,'%Y-%m') = '$periode'

                                            UNION ALL
                                            SELECT 
                                                id_produk,Tanggal,id_relokasi AS NoDokumen, CONCAT('Relokasi ke : ',nm_toko_penerima) AS Keterangan,
                                                0 AS qty_in,
                                                qty AS qty_out,
                                                BatchNo,ExpDate
                                            FROM tr_relokasi_detail 
                                            WHERE 
                                                STATUS IN ('Kirim','Terima') 
                                                AND id_toko = '".$this->id_toko."'
                                                AND DATE_FORMAT(Tanggal,'%Y-%m') = '$periode'

                                            UNION ALL
                                            SELECT 
                                                id_produk,DATE(Terima_time) AS Tanggal,id_relokasi AS NoDokumen, CONCAT('Relokasi Dari : ',nm_toko_penerima) AS Keterangan,
                                                qty AS qty_in,
                                                0 AS qty_out,
                                                BatchNo,ExpDate
                                            FROM tr_relokasi_detail 
                                            WHERE 
                                                STATUS IN ('Terima') 
                                                AND id_toko_penerima = '".$this->id_toko."'
                                                AND DATE_FORMAT(date(Terima_time),'%Y-%m') = '$periode'
                                            UNION ALL
                                            SELECT 
                                                id_produk,Tanggal,id_koreksi AS NoDokumen, CONCAT('Koreksi ',tipe , ' : ',keterangan) AS Keterangan,
                                                IF(tipe = 'Plus',qty,0) AS qty_in,
                                                IF(tipe = 'Minus',qty,0) AS qty_out,
                                                BatchNo,ExpDate
                                            FROM tr_koreksi_detail 
                                            WHERE 
                                                STATUS IN ('Open') 
                                                AND id_toko = '".$this->id_toko."'
                                                AND DATE_FORMAT(Tanggal,'%Y-%m') = '$periode'
                                        )z
                                        ORDER BY Tanggal
                        )t_stok_akhir
                        LEFT JOIN 
                        (SELECT id_produk,id_toko,qty FROM trs_invsum WHERE tahun = YEAR(CURDATE()) AND id_toko = '".$this->id_toko."') a
                        ON a.id_produk = t_stok_akhir.id_produk
                        LEFT JOIN
                        (SELECT id_produk,id_toko,SUM(qty)qty FROM trs_invdet WHERE tahun = YEAR(CURDATE()) AND id_toko = '".$this->id_toko."' GROUP BY id_produk) b
                        ON b.id_produk = t_stok_akhir.id_produk

                        GROUP BY t_stok_akhir.id_produk
                    )z WHERE qty_akhir <> qty_sum OR qty_akhir <> qty_det

                ")->result();

        return $query;
    }

    function prosesClosing(){
        $tgl =  date('Y-m-d', strtotime("-1 month", strtotime(date('Y-m-d'))));
        $tgl_akhir =  date('Y-m-t', strtotime($tgl));
        $bulan = date('m');

        $this->db->set("id_toko",$this->id_toko);
        $this->db->set("nm_toko",$this->nm_toko);
        $this->db->set("tgl_closing",$tgl_akhir);
        $result= $this->db->replace("m_closing");
        
        if ($result) {
            if ($bulan != "01") {
                $result = $this->db->query("UPDATE `trs_invsum` SET SA_".$bulan." = qty WHERE id_toko = '".$this->id_toko."' ");
                $result = $this->db->query("UPDATE `trs_invdet` SET SA_".$bulan." = qty WHERE id_toko = '".$this->id_toko."' ");
            }else{
                $this->db->query("INSERT INTO `trs_invsum`
                                    (`Tahun`,`id_produk`,`nm_produk`,`id_toko`,`nm_toko`,
                                     `qty`)
                                    SELECT YEAR(CURDATE()),`id_produk`,`nm_produk`,`id_toko`,`nm_toko`,
                                     `qty`
                                    FROM trs_invsum WHERE Tahun = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))
                                    and qty > 0");

                $this->db->query("INSERT INTO `trs_invdet`
                                    (`Tahun`,`id_produk`,`nm_produk`,`id_toko`,`nm_toko`,
                                     `qty`,`BatchNo`,`ExpDate`,`NoDokumen`)
                                    SELECT YEAR(CURDATE()),`id_produk`,`nm_produk`,`id_toko`,`nm_toko`,
                                     `qty`,`BatchNo`,`ExpDate`,`NoDokumen`
                                    FROM trs_invsum WHERE Tahun = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))
                                    and qty > 0");
            }
        }

        return $result;
    }

    function getproduk($id){
        $query = $this->db->query("
                    SELECT 
                        a.id_produk,a.nm_produk,0 as diskon, a.harga_jual as harga, '' as id_promosi,b.qty as stok
                    FROM m_produk a 
                        LEFT join (
                            SELECT id_produk,qty FROM trs_invsum
                            WHERE 
                            id_toko = '".$this->id_toko."' 
                            AND tahun = YEAR(CURDATE()) 
                        )b
                    on a.id_produk = b.id_produk
                    WHERE 
                         a.id_produk = '$id'
                ");
        return $query;
    }

    function Batal_penjualan(){
        $id = $this->input->post('id');

        $valid = $this->insert_stok("tr_penjualan_detail",$id,"qty","id_penjualan","Batal Penjualan");

        $this->db->set("status", "Batal");
        $this->db->where("id_penjualan", $id);
        $valid= $this->db->update("tr_penjualan_header");

        $this->db->set("status", "Batal");
        $this->db->where("id_penjualan", $id);
        $valid= $this->db->update("tr_penjualan_detail");
        

        return $valid;


    }

}