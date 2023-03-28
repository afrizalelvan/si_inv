<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

    function __construct(){
        parent::__construct();
        if($this->session->userdata('status') != "login"){
            redirect(base_url("Login"));
        }
        $this->load->model('m_master');
        $data['setting'] = $this->stok_aman = $this->m_master->get_data("m_setting")->row();


        $this->id_toko = $this->session->userdata('id_toko');
        $this->nm_toko = $this->session->userdata('nm_toko');

        if ($this->id_toko == "-" || $this->id_toko == "") {
            $this->id_toko = "T0001";
            $this->nm_toko = "Cabang Pusat";
        }

        
    }

    public function index()
    {
        $where = "";
        if ($this->session->userdata('id_toko') != "-") {
            $where = " AND id_toko = '".$this->id_toko."' ";
        }

        $store_m = $this->input->get('m') == '' ? 'T0001' : $this->input->get('m') ;
        $store_d = $this->input->get('d') == '' ? 'T0001' : $this->input->get('d') ;
        $store_s = $this->input->get('s') == '' ? 'T0001' : $this->input->get('s') ;

        $where2 = "";
        if ($store_s != "all") {
            $where2 = " AND id_toko = '".$store_s."'";
        }

        $data = array(
                      'produk' => $this->db->query("
                                    SELECT nm_produk,SUM(qty) qty 
                                    FROM trs_invsum 
                                    WHERE qty < ".$this->stok_aman->stok_aman." $where2 
                                    GROUP BY nm_produk ORDER BY qty limit 10"
                                ),
                      'chart_sales' => json_encode($this->m_master->chart_sales($store_m)->result()),
                      'chart_sales2' => json_encode($this->m_master->chart_sales2($store_d)->result()),
                      'chart_color' => json_encode($this->m_master->chart_color($this->m_master->chart_sales($store_m)->num_rows())->result()),
                      'chart_color2' => json_encode($this->m_master->chart_color($this->m_master->chart_sales2($store_d)->num_rows())->result()),
                      'stok_aman' => $this->stok_aman,
                      'store_m' => $store_m,
                      'store_d' => $store_d,
                      'store_s' => $store_s,
                      'store' => $this->m_master->get_data("m_toko", "WHERE s_aktif ='Aktif' $where ")->result()
                    );

        $this->load->view('header');
        $this->load->view('home',$data);
        $this->load->view('footer');
    }
    function Produk(){
        
       
        $data = array(
                      'judul' => "Produk",
                      'kategori' => $this->m_master->get_data("m_kategori", "WHERE s_aktif ='Aktif' ")->result(),
                      'satuan' => $this->m_master->get_data("m_satuan", "WHERE s_aktif ='Aktif' ")->result(),
                    );


        $this->load->view('header');
        $this->load->view('Master/v_produk',$data);
        $this->load->view('footer');
    }

    function Supplier(){
        $data = array(
                      'judul' => "Supplier"
                     );

        $this->load->view('header');
        $this->load->view('Master/v_supplier',$data);
        $this->load->view('footer');
    }

    function Kategori(){
        $data = array(
                      'judul' => "Kategori"
                     );

        $this->load->view('header');
        $this->load->view('Master/v_kategori',$data);
        $this->load->view('footer');
    }

    function Satuan(){
        $data = array(
                      'judul' => "Satuan"
                     );

        $this->load->view('header');
        $this->load->view('Master/v_satuan',$data);
        $this->load->view('footer');
    }

    function Store(){
        $data = array(
                      'judul' => "Store",
                     );

        $this->load->view('header');
        $this->load->view('Master/v_toko',$data);
        $this->load->view('footer');
    }

    function User(){
        $data = array(
                      'judul' => "User",
                      'store' => $this->m_master->get_data("m_toko")->result()
                     );

        $this->load->view('header');
        $this->load->view('Master/v_user',$data);
        $this->load->view('footer');
    }

    function Pelanggan(){
        $data = array(
                      'judul' => "Pelanggan"
                     );

        $this->load->view('header');
        $this->load->view('Master/v_pelanggan',$data);
        $this->load->view('footer');
    }

    function Sistem(){
        $data = array(
                      'data' => $this->m_master->get_data("m_setting")->row(),
                     );

        $this->load->view('header');
        $this->load->view('Master/v_setting',$data);
        $this->load->view('footer');
    }

    
    function Insert(){

            $jenis      = $this->input->post('jenis');
            $status      = $this->input->post('status');
        
            $result     = $this->m_master->$jenis($jenis,$status);    
                    

            echo json_encode($result);
            
    }

    function load_data()
    {
        $jenis = $this->uri->segment(3);

        $data = array();

        if ($jenis == "Perawatan") {
            $query = $this->m_master->query("SELECT * FROM m_perawatan order by id_perawatan")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_perawatan ."'". ',' ."'detail'". ')">'.$r->id_perawatan."<a>";
                $row[] = $r->nm_perawatan;
                $row[] = $r->deskripsi;
                $row[] = number_format($r->harga,0,",",".");
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_perawatan ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_perawatan ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Supplier") {
            $query = $this->m_master->query("SELECT * FROM m_supplier order by id_supplier")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_supplier ."'". ',' ."'detail'". ')">'.$r->id_supplier."<a>";
                $row[] = $r->nm_supplier;
                $row[] = $r->alamat;
                $row[] = $r->no_telp;

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_supplier ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_supplier ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Kategori") {
            $query = $this->m_master->query("SELECT * FROM m_kategori order by id_kategori")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_kategori ."'". ',' ."'detail'". ')">'.$r->id_kategori."<a>";
                $row[] = $r->nm_kategori;

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_kategori ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_kategori ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Satuan") {
            $query = $this->m_master->query("SELECT * FROM m_satuan order by id_satuan")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_satuan ."'". ',' ."'detail'". ')">'.$r->id_satuan."<a>";
                $row[] = $r->nm_satuan;

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_satuan ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_satuan ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "Toko") {
            $query = $this->m_master->query("SELECT * FROM m_toko order by id_toko")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_toko ."'". ',' ."'detail'". ')">'.$r->id_toko."<a>";
                $row[] = $r->nm_toko;
                $row[] = $r->alamat;
                $row[] = $r->no_telp;

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_toko ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_toko ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "User") {
            $query = $this->m_master->query("SELECT * FROM tb_user order by id")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->username ."'". ',' ."'detail'". ')">'.$r->username."<a>";
                $row[] = $r->nm_user;
                $row[] = base64_decode($r->password);
                $row[] = $r->level;
                $row[] = $r->nm_toko;

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                if ($r->level == 'Administrator') {
                    $row[] = '-';
                }else{

                    $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->username ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                                   Edit
                                </button>
                                <button type="button" onclick="deleteData(' ."'".$r->username ."'". ')" class="btn btn-danger btn-xs">
                                   Hapus
                                </button> ';
                }

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "pelanggan") {
            $query = $this->m_master->query("SELECT * FROM m_pelanggan order by id_pelanggan")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_pelanggan ."'". ',' ."'detail'". ')">'.$r->id_pelanggan."<a>";
                $row[] = $r->nm_pelanggan;
                $row[] = $r->no_telp;
                $row[] = $r->alamat;
                $row[] = $r->jk;
                $row[] = $r->tempat_lahir." , ".$r->ttl;

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_pelanggan ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_pelanggan ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }else if ($jenis == "produk") {
            require 'assets/vendor/autoload.php';
            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        
            $query = $this->m_master->query("SELECT * FROM m_produk order by id_produk")->result();
            $i = 1;
            foreach ($query as $r) {
                $row = array();
                
                if ((!file_exists(getcwd().'/assets/gambar/produk/'.$r->gambar)) or $r->gambar == '' ) {
                    $foto = "noimage.png";
                }else{
                    $foto = $r->gambar;
                }

                $row[] = '<div class="product-img">
                          <img src="'.base_url('assets/gambar/produk/').$foto.'" alt="Product Image" class="img-size-50" align="center">
                        </div>';
                $row[] = '<a href="javascript:void(0)" onclick="tampil_edit(' ."'".$r->id_produk ."'". ',' ."'detail'". ')">'.$r->id_produk."<a>";
                $row[] = $r->nm_produk;
                $row[] = $r->nm_kategori;
                $row[] = $r->nm_supplier;
                $row[] = $r->deskripsi;
                $row[] = $r->satuan;
                $row[] = number_format($r->harga,0,",",".");
                $row[] = number_format($r->harga_jual,0,",",".");
                $row[] = '<a href="javascript:void(0)" onclick="print_bc(' ."'".$r->id_produk ."'". ')">'.$generator->getBarcode($r->id_produk, $generator::TYPE_CODE_128).'</a>';
                

                if ($r->s_aktif == 'Aktif') {
                    $s_aktif = '<span class="float-right badge bg-info">'.$r->s_aktif.'</span>';
                }else{
                    $s_aktif = '<span class="float-right badge bg-danger">'.$r->s_aktif.'</span>';
                }

                $row[] = $s_aktif;
                $row[] = $aksi = '<button type="button" onclick="tampil_edit(' ."'".$r->id_produk ."'". ',' ."'edit'". ')" class="btn btn-warning btn-xs">
                               Edit
                            </button>
                            <button type="button" onclick="deleteData(' ."'".$r->id_produk ."'". ')" class="btn btn-danger btn-xs">
                               Hapus
                            </button> ';

                $data[] = $row;

                $i++;
            }
        }

        

        $output = array(
            "data" => $data,
        );
        
        echo json_encode($output);
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

        $data =  $this->m_master->get_data_one($jenis, $field, $id)->row();
        echo json_encode($data);
        
    }

    function load_stok_ready(){
        $data =  $this->m_master->load_stok_ready()->result();
        echo json_encode($data);
    }

    function load_stok_detail(){
        $data =  $this->m_master->load_stok_detail()->result();
        echo json_encode($data);
    }

    function load_store(){
        $data =  $this->m_master->load_store('not in')->result();
        echo json_encode($data);
    }

    function load_store_all(){
        $data =  $this->m_master->load_store()->result();
        echo json_encode($data);
    }

    function load_supplier(){
        $data =  $this->m_master->load_supplier()->result();
        echo json_encode($data);
    }

    function load_produk(){
        $data =  $this->m_master->load_produk()->result();
        echo json_encode($data);
    }

    function load_produk_supplier(){
        $data =  $this->m_master->load_produk('sup')->result();
        echo json_encode($data);
    }
}

