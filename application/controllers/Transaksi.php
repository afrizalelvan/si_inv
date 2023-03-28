<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    function __construct(){
        parent::__construct();
        if($this->session->userdata('status') != "login"){
            redirect(base_url("Login"));
        }
        $this->load->model('m_master');
        $this->load->model('m_transaksi');
        $this->load->model('m_fungsi');

        $this->id_toko = $this->session->userdata('id_toko');
        $this->nm_toko = $this->session->userdata('nm_toko');

        if ($this->id_toko == "-" || $this->id_toko == "") {
            $this->id_toko = "T0001";
            $this->nm_toko = "Cabang Pusat";
        }

    }

    public function Promosi()
    {
         $data = array(
                      'judul' => "Promosi",
                      'produk' => $this->db->query("SELECT * FROM m_produk order by id_produk")->result()
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_promosi',$data);
        $this->load->view('footer');
    }

    public function Penjualan()
    {
        $where = "";

        if ($this->id_toko != "T0001") {
            $where = " AND id_toko = '".$this->id_toko."' ";
        }

         $data = array(
                      'judul' => "Penjualan",
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result(),
                       "tgl" => date("Y-m-d"),
                       "id_toko" => $this->id_toko,
                       "tgl_awal" => date('Y-m-01', strtotime(date("Y-m-d"))),
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_penjualan',$data);
        $this->load->view('footer');
    }

    public function Penjualan_detail()
    {
        $where = "";

        if ($this->id_toko != "T0001") {
            $where = " AND id_toko = '".$this->id_toko."' ";
        }
         $data = array(
                      'judul' => "Penjualan Detail",
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result(),
                       "tgl" => date("Y-m-d"),
                       "id_toko" => $this->id_toko,
                       "tgl_awal" => date('Y-m-01', strtotime(date("Y-m-d"))),
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_penjualan_detail',$data);
        $this->load->view('footer');
    }

    public function FormPembelian()
    {
         $data = array(
                      'judul' => "Form Pembelian",
                      'cek_closing' => $this->m_transaksi->cek_closing()['status'],
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_form_pembelian',$data);
        $this->load->view('footer');
    }

    public function Pembelian()
    {
        $where = "";

        if ($this->id_toko != "T0001") {
            $where = " AND id_toko = '".$this->id_toko."' ";
        }

         $data = array(
                      'judul' => "Pembelian",
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result(),
                       "tgl" => date("Y-m-d"),
                       "id_toko" => $this->id_toko,
                       "tgl_awal" => date('Y-m-01', strtotime(date("Y-m-d"))),
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_pembelian',$data);
        $this->load->view('footer');
    }

    public function FormStokTransfer()
    {
         $data = array(
                      'judul' => "Form Stok Transfer",
                      'cek_closing' => $this->m_transaksi->cek_closing()['status'],
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_form_stok_transfer',$data);
        $this->load->view('footer');
    }

    public function StokTransfer()
    {
        $where = "";
        if ($this->session->userdata('id_toko') != "-") {
            $where = " AND id_toko = '".$this->id_toko."' ";
        }

         

         $data = array(
                      'judul' => "Data Stok Transfer",
                       "tgl" => date("Y-m-d"),
                       "tgl_awal" => date('Y-m-01', strtotime(date("Y-m-d"))), 
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result()
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_StokTransfer',$data);
        $this->load->view('footer');
    }

    public function TerimaStokTransfer()
    {
         $data = array(
                      'judul' => "Terima Stok Transfer",
                       "tgl" => date("Y-m-d"),
                       "tgl_awal" => date('Y-m-01', strtotime(date("Y-m-d"))),
                      'cek_closing' => $this->m_transaksi->cek_closing()['status'],
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_TerimaStokTransfer',$data);
        $this->load->view('footer');
    }

    public function FormAdjustmentStok()
    {
         $data = array(
                      'judul' => "Form Adjustmen Stok",
                      'cek_closing' => $this->m_transaksi->cek_closing()['status'],
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_form_adjusment_stok',$data);
        $this->load->view('footer');
    }

    public function AdjustmentStok()
    {
         $data = array(
                      'judul' => "Data Adjustment Stok",
                       "tgl" => date("Y-m-d"),
                       "tgl_awal" => date('Y-m-01', strtotime(date("Y-m-d"))),
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_AdjustmentStok',$data);
        $this->load->view('footer');
    }

    public function Closing()
    {
        $a = $this->m_transaksi->cek_closing();
        
         $data = array(
                      'judul' => "Closing Bulanan",
                      'tgl_closing' => $a['tgl_closing'],
                      'status' => $a['status'],
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_closing',$data);
        $this->load->view('footer');
    }

    public function Inventory()
    {   
        $where = "";
        if ($this->session->userdata('id_toko') != "-") {
            $where = " AND id_toko = '".$this->id_toko."' ";
        }

         $data = array(
                      'judul' => "Inventory",
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result()
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_inventory',$data);
        $this->load->view('footer');
    }

    public function KartuStok()
    {
        $where = "";
         if ($this->session->userdata('id_toko') != "-") {
             $where = " AND id_toko = '".$this->id_toko."' ";
         }

         $data = array(
                      'judul' => "Kartu Stok",
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result(),
                      'produk' => $this->m_master->get_data("m_produk", "WHERE s_aktif ='Aktif' ")->result()
                     );


        $this->load->view('header');
        $this->load->view('Transaksi/v_kartu_stok',$data);
        $this->load->view('footer');
    }

    public function Kasir()
    {
         $data = array(
                      'judul' => "Kasir",
                      'cek_closing' => $this->m_transaksi->cek_closing()['status'],
                      'setting' => $this->m_master->get_data("m_setting")->row(),
                      'kategori' => $this->m_master->get_data("m_kategori", "WHERE s_aktif ='Aktif' ")->result()
                     );


        $this->load->view('header_pos');
        $this->load->view('Transaksi/v_kasir',$data);
        $this->load->view('footer');
    }

    function Insert(){

            $jenis      = $this->input->post('jenis');
            $status      = $this->input->post('status');
    
            $result     = $this->m_transaksi->$jenis($jenis,$status);    
                    

            echo json_encode($result);
                
    }

    function update_trs_beli(){
    
            $result     = $this->m_transaksi->update_trs_beli();    
                    

            echo json_encode($result);
            
    }

    function load_data()
    {
        $jenis = $this->uri->segment(3);

        $data = array();

        if ($jenis == "promosi") {
            $query = $this->m_master->query("SELECT * FROM tr_promosi order by status,id_promosi")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_promosi ."'". ',' ."'detail'". ')">'.$r->id_promosi."<a>";
                $row[] = $r->id_produk;
                $row[] = $r->nm_produk;
                $row[] = $r->tgl_mulai;
                $row[] = $r->tgl_akhir;
                $row[] = $r->diskon;

                if ($r->status == '1') {
                    $row[] = '<button type="button" class="btn btn-block bg-gradient-success btn-xs" onclick="proses('.$r->status.','."'".$r->id_promosi."'".')">Aktif</button>';
                }else{
                    $row[] = '<button type="button" class="btn btn-block bg-gradient-danger btn-xs" onclick="proses('.$r->status.','."'".$r->id_promosi."'".')">Tidak Aktif</button>';
                }

                $row[] = $aksi = '
                            <button type="button" onclick="tampil_edit(' ."'".$r->id_promosi ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_promosi ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "penjualan") {
            $store = $this->input->post('store');
            $tgl1 = $this->input->post('tgl1');
            $tgl2 = $this->input->post('tgl2');
            $where_store = "";

            if ($store != "" && $store != "all") {
                $where_store = " AND id_toko ='".$store."' ";
            }

            $query = $this->m_master->query("SELECT * FROM tr_penjualan_header  WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' 
$where_store order by id_penjualan")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_penjualan ."'". ',' ."'detail'". ')">'.$r->id_penjualan."<a>";
                $row[] = $r->nm_toko;
                $row[] = $r->tanggal;
                $row[] = $r->id_pelanggan;
                $row[] = $r->nm_pelanggan;
                $row[] = number_format($r->jumlah, 2, '.', '.');
                $row[] = $r->diskon_pelanggan;

                $potongan_member =($r->diskon_pelanggan / 100) * ($r->jumlah - $r->potongan_diskon);
                $row[] = number_format($potongan_member, 2, '.', '.');
                $row[] = number_format($r->potongan_diskon, 2, '.', '.');
                $row[] = number_format($r->total, 2, '.', '.');
                $row[] = $r->status;

                $batal = "-";

                if ($r->tanggal == date('Y-m-d') && $r->status != 'Batal') {
                    $batal = '
                            <button type="button" onclick="Batal(' ."'".$r->id_penjualan ."'". ')" class="btn btn-danger btn-xs">
                               Batal
                            </button> ';
                }

                $row[] = $batal;

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "penjualan_detail") {
             $store = $this->input->post('store');
            $tgl1 = $this->input->post('tgl1');
            $tgl2 = $this->input->post('tgl2');
            $where_store = "";

            if ($store != "" && $store != "all") {
                $where_store = " AND a.id_toko ='".$store."' ";
            }

            $query = $this->m_master->query("SELECT b.*,diskon_pelanggan FROM tr_penjualan_header a join tr_penjualan_detail b
                ON a.id_penjualan = b.id_penjualan  WHERE a.tanggal BETWEEN '$tgl1' AND '$tgl2' $where_store
             order by b.id_penjualan")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = $r->id_penjualan;
                $row[] = $r->nm_toko;
                $row[] = $r->tanggal;
                $row[] = $r->id_pelanggan;
                $row[] = $r->nm_pelanggan;
                $row[] = $r->id_produk;
                $row[] = $r->nm_produk;
                $row[] = number_format($r->qty, 2, '.', '.');
                $row[] = number_format($r->harga, 2, '.', '.');
                $potongan_member =($r->diskon_pelanggan / 100) * ($r->total);
                $row[] = number_format($r->potongan + $potongan_member, 2, '.', '.');

                $row[] = number_format($r->total - ($r->potongan + $potongan_member), 2, '.', '.');

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "pembelian") {
            $store = $this->input->post('store');
            $tgl1 = $this->input->post('tgl1');
            $tgl2 = $this->input->post('tgl2');
            $where_store = "";

            if ($store != "" && $store != "all") {
                $where_store = " AND id_toko ='".$store."' ";
            }

            $query = $this->m_master->query("SELECT * FROM tr_pembelian_header WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' $where_store order by Tanggal asc")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_pembelian ."'". ',' ."'detail'". ')">'.$r->id_pembelian."<a>";
                $row[] = $r->tanggal;
                $row[] = $r->id_supplier;
                $row[] = $r->nm_supplier;
                $row[] = $r->id_toko;
                $row[] = $r->nm_toko;
                $row[] = number_format($r->jml_qty, 2, '.', '.');
                $row[] = number_format($r->value, 2, '.', '.');
                $row[] = number_format($r->potongan, 2, '.', '.');
                $row[] = number_format($r->total, 2, '.', '.');
                $row[] = $r->status;

                if ($r->status == 'Open') {
                    $status = 'Kirim';
                    $aksi = '
                    <button type="button" class="btn btn-block bg-gradient-primary btn-xs" onclick="update_beli('."'".$status."'".','."'".$r->id_pembelian."'".')">Kirim</button>
                    <button type="button" class="btn btn-block bg-gradient-danger btn-xs" onclick="update_beli('."'Batal'".','."'".$r->id_pembelian."'".')">Batal</button>
                    ';
                }else if ($r->status == 'Kirim' && $r->id_toko == $this->id_toko) {
                    $status = 'Terima';
                    $aksi = '
                    <button type="button" class="btn btn-block bg-gradient-success btn-xs" onclick="tampil_edit(' ."'".$r->id_pembelian ."'". ',' ."'terima'". ')">Terima</button>
                    ';
                }else if ($r->status == 'Terima') {
                    $aksi = '-';
                }else{
                    $aksi = '-';
                }
                $row[] = $aksi;


                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Inventory") {

            $store = $this->input->post('store');
            $where_store = "";

            if ($store != "" || $this->session->userdata('id_toko') != "-") {
                $where_store = " WHERE id_toko ='".$store."' ";
            }



            $query = $this->m_master->query("SELECT * FROM trs_invsum  $where_store")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = $r->nm_toko;
                $row[] = $r->id_produk;
                $row[] = $r->nm_produk;
                $row[] = number_format($r->qty, 2, '.', '.');
                $row[] = '<button type="button" class="btn btn-block btn-secondary btn-xs" onclick="detail('."'".$r->id_produk."'".','."'".$r->id_toko."'".')">Detail</button>';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "kartu_stok") {
            $tgl = $_POST['tgl'];
            $id_toko = $_POST['id_toko'];
            $id_produk = $_POST['id_produk'];
            
            $saldo_akhir = $this->get_saldo_awal($id_toko,$id_produk,$tgl);

            $query = $this->m_transaksi->kartu_stok($id_toko,$id_produk,$tgl)->result();
            $i = 1;

            foreach ($query as $r) {
                $row = array();
                
                $row[] = $i;
                $row[] = $r->waktu;
                $row[] = $r->NoDokumen;
                $row[] = $r->Keterangan;
                $row[] = $r->qty_in;
                $row[] = $r->qty_out;

                if ($r->Keterangan == 'Saldo Awal') {
                    $saldo_akhir = $r->qty_in;
                }else{

                    $saldo_akhir = $saldo_akhir + $r->qty_in - $r->qty_out;
                }

                $row[] = $saldo_akhir;
                $row[] = $r->BatchNo;
                $row[] = $r->ExpDate;

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "stok_transfer") {
            $store = $this->input->post('store');
            $tgl1 = $this->input->post('tgl1');
            $tgl2 = $this->input->post('tgl2');
            $where_store = "";

            if ($store != "") {
                $where_store = " AND id_toko ='".$store."' ";
            }

            $query = $this->m_master->query("SELECT *,DATE_FORMAT(Tanggal,'%Y-%m') as periode FROM tr_relokasi_header WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' $where_store order by Tanggal asc")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_relokasi ."'". ',' ."'detail'". ')">'.$r->id_relokasi."<a>";
                $row[] = $r->tanggal;
                $row[] = $r->id_toko;
                $row[] = $r->nm_toko;
                $row[] = $r->id_toko_penerima;
                $row[] = $r->nm_toko_penerima;
                $row[] = number_format($r->jml_qty, 2, '.', '.');
                $row[] = $r->status;

                if ($r->status == 'Kirim' && $r->periode == date('Y-m')) {
                    $status = 'Kirim';
                    $aksi = '
                    <button type="button" class="btn btn-block bg-gradient-danger btn-xs" onclick="Batal('."'".$r->id_relokasi."'".')">Batal</button>
                    ';
                }else {
                    $aksi = '-';
                }
                $row[] = $aksi;


                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Terimastok_transfer") {
            $store = $this->id_toko;
            $tgl1 = $this->input->post('tgl1');
            $tgl2 = $this->input->post('tgl2');
            $where_store = "";

            if ($store != "") {
                $where_store = " AND id_toko_penerima ='".$store."' ";
            }

            $query = $this->m_master->query("SELECT *,DATE_FORMAT(Tanggal,'%Y-%m') as periode FROM tr_relokasi_header WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' AND status  in ('Kirim','Terima') $where_store order by Tanggal asc")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_relokasi ."'". ',' ."'detail'". ')">'.$r->id_relokasi."<a>";
                $row[] = $r->tanggal;
                $row[] = $r->id_toko;
                $row[] = $r->nm_toko;
                $row[] = $r->id_toko_penerima;
                $row[] = $r->nm_toko_penerima;
                $row[] = number_format($r->jml_qty, 2, '.', '.');
                $row[] = $r->status;

                if ($r->status == 'Kirim' ) {
                    $aksi = '
                    <button type="button" class="btn btn-block bg-gradient-success btn-xs" onclick="Terima('."'".$r->id_relokasi."'".')">Terima</button>
                    ';
                }else {
                    $aksi = '-';
                }
                $row[] = $aksi;


                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Adjustment") {
   
            $tgl1 = $this->input->post('tgl1');
            $tgl2 = $this->input->post('tgl2');
   
            $query = $this->m_master->query("SELECT *,DATE_FORMAT(Tanggal,'%Y-%m') as periode FROM tr_koreksi_header WHERE tanggal BETWEEN '$tgl1' AND '$tgl2' order by Tanggal asc")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_koreksi ."'". ',' ."'detail'". ')">'.$r->id_koreksi."<a>";
                $row[] = $r->tanggal;
                $row[] = $r->tipe;
                $row[] = $r->status;

                if ($r->status == 'Open' && $r->periode == date('Y-m') ) {
                    $aksi = '
                    <button type="button" class="btn btn-block bg-gradient-danger btn-xs" onclick="Batal('."'".$r->id_koreksi."'".','."'".$r->tipe."'".')">Batal</button>
                    ';
                }else {
                    $aksi = '-';
                }
                $row[] = $aksi;


                $data[] = $row;

                $i++;
            }
        }

        

        $output = array(
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    function get_saldo_awal($id_toko= '',$id_produk= '',$tgl= ''){
        
        $tahun =  date('Y', strtotime("-1 month", strtotime($tgl)));
        $bulan =  date('m', strtotime("-1 month", strtotime($tgl)));

        $cek = $this->db->query("SELECT 
                                    ifnull(SA_".$bulan.",0)saldo_awal FROM trs_invsum 
                                WHERE 
                                tahun = '".$tahun."' 
                                AND id_toko ='".$id_toko."'
                                AND id_produk ='".$id_produk."'  
                            ");

        if ($cek->num_rows() > 0) {
            return $cek->row('saldo_awal');
        }else{

            return 0 ;
        }

    }

    function hapus(){
        $jenis   = $_POST['jenis'];
        $field   = $_POST['field'];
        $id = $_POST['id'];

        $result = $this->m_master->query("DELETE FROM $jenis WHERE  $field = '$id'");
         
        echo json_encode($result);
    }

   
    function get_edit(){
        $id    = $this->input->post('id');
        $jenis    = $this->input->post('jenis');
        $field    = $this->input->post('field');

        if ($jenis== "tr_penjualan_detail") {
            $data =  $this->db->query("SELECT a.*,IFNULL(b.diskon_pelanggan,0)diskon_pelanggan FROM `tr_penjualan_detail` a JOIN `tr_penjualan_header` b
                ON a.id_penjualan = b.id_penjualan 
                WHERE a.`id_penjualan` = '$id' ")->result();
        }else if ($jenis== "tr_pembelian_detail") {
            $data =  $this->db->query("SELECT a.* FROM `tr_pembelian_detail` a JOIN `tr_pembelian_header` b
                ON a.id_pembelian = b.id_pembelian 
                WHERE a.`id_pembelian` = '$id' ")->result();
        }else if ($jenis== "getproduk") {
            $data =  $this->m_transaksi->getproduk($id)->row();
        }else if ($jenis== "tr_relokasi_detail") {
            $data =  $this->db->query("SELECT * FROM tr_relokasi_detail WHERE `id_relokasi` = '$id' ")->result();
        }else if ($jenis== "tr_koreksi_detail") {
            $data =  $this->db->query("SELECT * FROM tr_koreksi_detail WHERE `id_koreksi` = '$id' ")->result();
        }else if ($jenis== "getMember") {
            $data =  $this->db->query("SELECT * FROM m_pelanggan WHERE `id_pelanggan` = '$id' ")->row();
        }else{
            $data =  $this->m_master->get_data_one($jenis, $field, $id)->row();
        }
        echo json_encode($data);
        
    }

    function status(){
        $jenis      = $this->input->post('jenis');
        $status      = $this->input->post('status');
        $id      = $this->input->post('id');
        $field      = $this->input->post('field');
        
        $result= $this->m_master->update_status($status,$id,$jenis,$field);
          
        echo json_encode($result);
    }

    function update_beli(){
        $status      = $this->input->post('status');
        $id      = $this->input->post('id');
        
        $result= $this->m_transaksi->update_beli($status,$id);
          
        echo json_encode($result);
    }

    public function print_invoice()
    {
        $id = $this->input->get('id');

        $data['id_penjualan'] = $id;

        $this->load->view('Transaksi/print_invoice', $data);
    }

    public function print_invoice_beli()
    {
        $id = $this->input->get('id');

        $data['id_pembelian'] = $id;

        $this->load->view('Transaksi/print_invoice_beli', $data);
    }

    public function print_relokasi()
    {
        $id = $this->input->get('id');

        $data['id_relokasi'] = $id;

        $this->load->view('Transaksi/print_relokasi', $data);
    }

    public function print_bc()
    {
        $id = $this->input->get('id');

        require 'assets/vendor/autoload.php';
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();

        $data['id'] = $generator->getBarcode($id, $generator::TYPE_CODE_128);

        $this->load->view('Transaksi/print_bc', $data);
    }

    function checkout(){
        // $params =(object)$this->input->post();

        $valid = $this->m_transaksi->checkout();
        echo json_encode($valid);
    }

    function get_inv(){
        $valid = $this->m_transaksi->get_inv();
        echo json_encode($valid);
    }

    function batal_relokasi(){
        $valid = $this->m_transaksi->batal_relokasi();
        echo json_encode($valid);
    }

    function batal_adjs(){
        $valid = $this->m_transaksi->batal_adjs();
        echo json_encode($valid);
    }

    function Terima_relokasi(){
        $valid = $this->m_transaksi->Terima_relokasi();
        echo json_encode($valid);
    }

    function cek_stok_closing(){
        $valid = $this->m_transaksi->cek_stok_closing();
        echo json_encode($valid);
    }

    function prosesClosing(){
        $valid = $this->m_transaksi->prosesClosing();
        echo json_encode($valid);
    }

    function get_produk_jual(){
        $valid = $this->m_transaksi->get_produk_jual();
        echo json_encode($valid);
    }

    function Batal_penjualan(){
        $valid = $this->m_transaksi->Batal_penjualan();
        echo json_encode($valid);
    }


}