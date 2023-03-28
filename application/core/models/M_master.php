<?php
class M_master extends CI_Model{
 	
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

    public function upload($file,$nama){
        // $file = 'foto';
        // unlink('../assets/images/member/'.$nama);
        $config['upload_path'] = './assets/gambar/produk/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        // $config['max_size'] = '20480';
        // $config['remove_space'] = TRUE;
        $config['file_name'] = $nama;
    
        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        if($this->upload->do_upload($file)){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }

    public function upload2($file,$nama){
        // $file = 'foto';
        // unlink('../assets/images/member/'.$nama);
        $config['upload_path'] = './assets/gambar/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        // $config['max_size'] = '20480';
        // $config['remove_space'] = TRUE;
        $config['file_name'] = $nama;
    
        $this->load->library('upload', $config); // Load konfigurasi uploadnya
        if($this->upload->do_upload($file)){ // Lakukan upload dan Cek jika proses upload berhasil
            // Jika berhasil :
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }
   
    function get_data($table,$opt = null){
        $query = "SELECT * FROM $table $opt";
        return $this->db->query($query);
    }

    function get_count($table){
        $query = "SELECT count(*) as jumlah FROM $table";
        return $this->db->query($query);
    }



    function get_data_one($table,$kolom,$id){
        
        $query = "SELECT * FROM $table WHERE $kolom='$id'";
        return $this->db->query($query);
    }


    function query($query1){
        
        $query = $query1;
        return $this->db->query($query);
    }


    function get_data_max($table,$kolom){
        $query = "SELECT IFNULL(LPAD(MAX(RIGHT($kolom,4))+1,4,0),'0001')AS nomor FROM $table";
        return $this->db->query($query)->row("nomor");
    }

    function delete($tabel,$kolom,$id){
        
        $query = "DELETE FROM $tabel WHERE $kolom = '$id' ";
        $result =  $this->db->query($query);
        return $result;
    }
	
    function m_perawatan($table,$status){
        
        if ($status == 'insert') {
            $id = "R".$this->get_data_max($table,"id_perawatan");
        }else{
            $id = $this->input->post('id_perawatan');
        }

        $data = array(
                'id_perawatan'  => $id,
                'nm_perawatan'  => $this->input->post('nm_perawatan'),
                'deskripsi'  => $this->input->post('deskripsi'),
                'harga'  => $this->input->post('harga'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_perawatan' => $id));
        }
        

        return $result;
    }
    
    function m_supplier($table,$status){
        
        if ($status == 'insert') {
            $id = "S".$this->get_data_max($table,"id_supplier");
        }else{
            $id = $this->input->post('id_supplier');
        }

        $data = array(
                'id_supplier'  => $id,
                'nm_supplier'  => $this->input->post('nm_supplier'),
                'alamat'  => $this->input->post('alamat'),
                'no_telp'  => $this->input->post('no_telp'),
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_supplier' => $id));
        }
        

        return $result;
    }
    
    function m_kategori($table,$status){
        
        if ($status == 'insert') {
            $id = "K".$this->get_data_max($table,"id_kategori");
        }else{
            $id = $this->input->post('id_kategori');
        }

        $data = array(
                'id_kategori'  => $id,
                'nm_kategori'  => $this->input->post('nm_kategori'),
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_kategori' => $id));
        }
        

        return $result;
    }
    
    function m_satuan($table,$status){
        
        if ($status == 'insert') {
            $id = "ST".$this->get_data_max($table,"id_satuan");
        }else{
            $id = $this->input->post('id_satuan');
        }

        $data = array(
                'id_satuan'  => $id,
                'nm_satuan'  => $this->input->post('nm_satuan'),
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_satuan' => $id));
        }
        

        return $result;
    }
    
    function tb_user($table,$status){
        
        if ($status == 'insert') {

            $cek = $this->get_data_one($table,'username',$this->input->post('username'))->num_rows();

            if ($cek > 0) {
                return false;
            }
        }else{
            $id = $this->input->post('id');
        }

        if ($this->input->post('store') == '') {
            $id_toko = "-";
            $nm_toko = "-";
        }else{

            $store = explode("-", $this->input->post('store'));
            $id_toko = $store[0];
            $nm_toko = $store[1];
        }

        $data = array(
                'nm_user'  => $this->input->post('nm_user'),
                'username'  => $this->input->post('username'),
                'password'  => base64_encode($this->input->post('password')),
                'level'  => $this->input->post('level'),
                'id_toko'  => $id_toko,
                'nm_toko'  => $nm_toko,
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id' => $id));
        }
        

        return $result;
    }
    
    function m_toko($table,$status){
        
        if ($status == 'insert') {
            $id = "T".$this->get_data_max($table,"id_toko");
        }else{
            $id = $this->input->post('id_toko');
        }

        $data = array(
                'id_toko'  => $id,
                'nm_toko'  => $this->input->post('nm_toko'),
                'alamat'  => $this->input->post('alamat'),
                'no_telp'  => $this->input->post('no_telp'),
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_toko' => $id));
        }
        

        return $result;
    }
    
    function m_pelanggan($table,$status){
        
        if ($status == 'insert') {
            $id = "M".$this->get_data_max($table,"id_pelanggan");
        }else{
            $id = $this->input->post('id_pelanggan');
        }

        $data = array(
                'id_pelanggan'  => $id,
                'nm_pelanggan'  => $this->input->post('nm_pelanggan'),
                'alamat'  => $this->input->post('alamat'),
                'no_telp'  => $this->input->post('no_telp'),
                'jk'  => $this->input->post('jk'),
                'tempat_lahir'  => $this->input->post('tempat_lahir'),
                'ttl'  => $this->input->post('ttl'),
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $result= $this->db->insert($table,$data);
        }else{
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_pelanggan' => $id));
        }
        

        return $result;
    }
    
    function m_produk($table,$status){
        
        if ($status == 'insert') {
            $id = "P".$this->get_data_max($table,"id_produk");
        }else{
            $id = $this->input->post('id_produk');
        }

        $kategori = explode("-", $this->input->post('kategori'));
        $supplier = explode("-", $this->input->post('supplier'));

        $data = array(
                'id_produk'  => $id,
                'nm_produk'  => $this->input->post('nm_produk'),
                'id_kategori'  => $kategori[0],
                'nm_kategori'  => $kategori[1],
                'id_supplier'  => $supplier[0],
                'nm_supplier'  => $supplier[1],
                'deskripsi'  => $this->input->post('deskripsi'),
                'harga'  => $this->input->post('harga'),
                'harga_jual'  => $this->input->post('harga_jual'),
                'satuan'  => $this->input->post('satuan'),
                's_aktif'  => $this->input->post('s_aktif'),
                'add_user'  => $this->username
            );

        if ($status == 'insert') {
            $upload = $this->m_master->upload('gambar',$id);
            $this->db->set("gambar", (($upload['result'] == 'success') ? $upload['file']['file_name'] : null));

            $result= $this->db->insert($table,$data);
        }else{
            $upload = $this->m_master->upload('gambar',$id);

            if ($upload['result'] == 'success') {
                $this->db->set("gambar", $upload['file']['file_name'] );
            }
            $this->db->set("edit_user", $this->username);
            $this->db->set("edit_time", date('Y-m-d H:i:s'));
            $result= $this->db->update($table,$data,array('id_produk' => $id));
        }
        

        return $result;
    }

    function m_setting($table,$status){
        
       

        $data = array(
            'nm_aplikasi'  => $this->input->post('nm_aplikasi'),
            'singkatan'  => $this->input->post('singkatan'),
            'nm_toko'  => $this->input->post('nm_toko'),
            'alamat'  => $this->input->post('alamat'),
            'no_telp'  => $this->input->post('no_telp'),
            'stok_aman'  => $this->input->post('stok_aman'),
            'diskon_member'  => $this->input->post('diskon_member')
        );

   
        $upload = $this->m_master->upload2('logo','logo');

        if ($upload['result'] == 'success') {
            $this->db->set("logo", $upload['file']['file_name'] );
        }
        $result= $this->db->update($table,$data);
        
        return $result;
    }

    function update_status($status,$id,$table,$field){
        if ($status == '1') {
            $ubah = '0';
        }else{
            $ubah = '1';
        }
        $this->db->set("status", $ubah);
        $this->db->where($field, $id);

        return $this->db->update($table);

    }

    function load_stok_ready(){
        /*$id_supplier = $this->input->post('id_supplier');

        $where = " AND id_supplier = '$id_supplier' ";*/

        $query = $this->db->query("
            SELECT 
                id_toko,a.id_produk,a.nm_produk,qty FROM trs_invsum a
            JOIN m_produk b
            ON a.`id_produk` = b.`id_produk`

            WHERE 
                b.`s_aktif` = 'Aktif'
                AND a.`tahun` = YEAR(CURDATE())
                AND a.`qty` > 0
                AND id_toko = '".$this->id_toko."'
                
            ");

        return $query;
    }

    function load_stok_detail(){
        
        $id_produk = $this->input->post('id_produk');

        $query = $this->db->query("
            SELECT 
                id_toko,a.id_produk,a.nm_produk,qty,BatchNo,ExpDate,NoDokumen FROM trs_invdet a
            

            WHERE 
                 a.`tahun` = YEAR(CURDATE())
                AND a.`qty` > 0
                AND a.id_produk = '$id_produk'
                AND id_toko = '".$this->id_toko."'
            ");

        return $query;
    }

    function load_store($not_in =""){
        $where = "";
        if ($not_in != "") {
            $where = " AND id_toko <> '".$this->id_toko."' ";
        }

        $query = $this->db->query("SELECT * FROM m_toko WHERE s_aktif = 'Aktif' $where ");

        return $query;
    }

    function load_supplier(){
        
        $query = $this->db->query("SELECT * FROM m_supplier WHERE s_aktif = 'Aktif' ");

        return $query;
    }

    function load_produk($sup = ""){
        $where = "";

        if ($sup != "") {
            $id_supplier = $this->input->post('id_supplier');

            $where = " AND id_supplier = '$id_supplier' ";
        }
        
        $query = $this->db->query("SELECT * FROM m_produk WHERE s_aktif = 'Aktif' $where ");

        return $query;
    }

    function chart_sales($store = ''){
        $where_str = "";

        if ($store != "all") {
            if ($store != '') {
                $where = $store;
            }else{
                $where = $this->id_toko;
            }

            $where_str = " AND a.`id_toko` = '".$where."'";
        }

        $query = $this->db->query("
            SELECT b.`nm_kategori`,SUM(a.total) total FROM  `tr_penjualan_detail` a
            LEFT JOIN `m_produk` b
            ON a.`id_produk` = b.`id_produk`

            WHERE 
                DATE_FORMAT(a.`tanggal`,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')
                $where_str

            GROUP BY b.`nm_kategori`");

        return $query;
    }

    function chart_sales2($store = ''){
         $where_str = "";

        if ($store != "all") {
            if ($store != '') {
                $where = $store;
            }else{
                $where = $this->id_toko;
            }

            $where_str = " AND a.`id_toko` = '".$where."'";
        }

        $query = $this->db->query("
            SELECT b.`nm_kategori`,SUM(a.total) total FROM  `tr_penjualan_detail` a
            LEFT JOIN `m_produk` b
            ON a.`id_produk` = b.`id_produk`

            WHERE 
                a.`tanggal` = CURDATE()
                $where_str
            GROUP BY b.`nm_kategori`");

        return $query;
    }

    function chart_color($limit){
        
        $query = $this->db->query("
            SELECT * FROM m_color LIMIT $limit
            ");

        return $query;
    }



}

?>
