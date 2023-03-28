<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    function __construct(){
        parent::__construct();
        if($this->session->userdata('status') != "login"){
            redirect(base_url("Login"));
        }
        $this->load->model('m_master');
        $this->load->model('m_laporan');
        $this->load->model('m_fungsi');

    }

    public function index()
    {
        $data = array(
                      'judul' => "Laporan Penjualan",
                    );

        $this->load->view('header');
        $this->load->view('Laporan/v_laporan',$data);
        $this->load->view('footer');
    }

    function get_produk($jenis = null){
        // Search term
      // $searchTerm
      $searchTerm = $_GET['search'];

      // Get users
      $response = $this->m_laporan->get_produk($searchTerm,$jenis);

      echo json_encode($response);

    }

    function getLaporan(){
      $jenis  = $_POST['jenis'];
      $produk = $_POST['produk'];
      $tgl1 = $_POST['tgl1'];
      $tgl2 = $_POST['tgl2'];
      $where_produk = $where_jenis = "";

      if ($produk != '-' && $jenis != "-") {
        $where_produk = " AND id_produk = '$produk' ";
      }

      if ($jenis != "-") {
        $kode = "R";
        if ($jenis == "Produk") {
          $kode = "P";
        }
        $where_jenis = " AND left(id_produk,1) = '$kode'";
      }

      $query = $this->m_master->query("SELECT a.*,b.diskon_pelanggan FROM `tr_penjualan_detail` a  join `tr_penjualan_header` b on a.id_penjualan = b.id_penjualan  where a.tanggal BETWEEN '$tgl1' AND '$tgl2' $where_produk $where_jenis ORDER BY a.tanggal ")->result();
        $i = 1;
        $data = array();

        foreach ($query as $r) {
            $row = array();
            
            $row[] = $r->id_penjualan;
            $row[] = $r->tanggal;
            $row[] = $r->id_pelanggan;
            $row[] = $r->nm_pelanggan;
            $row[] = $r->id_produk;
            $row[] = $r->nm_produk;
            $row[] = number_format($r->harga,0,",",".");
            $row[] = $r->qty;
            $row[] = number_format($r->value,0,",",".");
            $row[] = $r->diskon."%";
            $row[] = number_format($r->potongan,0,",",".");
            $row[] = $r->id_promosi;
            $row[] = $r->diskon_pelanggan."%";
            $row[] = number_format($potongan_member = ($r->diskon_pelanggan / 100 ) * $r->total,0,",",".");
            $row[] = number_format($r->total - $potongan_member,0,",",".");

            $data[] = $row;

            $i++;
        }

      $output = array(
            "data" => $data,
        );
        echo json_encode($output);
    }

    function Cetak_Laporan(){
        $jenis  = $_GET['jenis'];
        $produk = $_GET['produk'];
        $tgl1 = $_GET['tgl1'];
        $tgl2 = $_GET['tgl2'];
        $where_produk = $where_jenis = "";

        if ($produk != '-' && $jenis != "-") {
          $where_produk = " AND id_produk = '$produk' ";
        }

        if ($jenis != "-") {
          $kode = "R";
          if ($jenis == "Produk") {
            $kode = "P";
          }
          $where_jenis = " AND left(id_produk,1) = '$kode'";
        }

        $query = $this->m_master->query("SELECT a.*,b.diskon_pelanggan FROM `tr_penjualan_detail` a  join `tr_penjualan_header` b on a.id_penjualan = b.id_penjualan  where a.tanggal BETWEEN '$tgl1' AND '$tgl2' $where_produk $where_jenis ORDER BY a.tanggal ");

        $html = '';


        if ($query->num_rows() > 0) {

            $html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td colspan="15" align="center">
                              <h1> Laporan Penjualan';
                              if ($jenis != '-') {
                                $html .='<br><h3>'.$jenis.'';
                                if ($produk != '-') {
                                  $html .=' : '.$query->row("nm_produk") .'</h3>';
                                }
                              }
                              $html .='<br> Periode '.$this->m_fungsi->tanggal_format_indonesia($tgl1).' Sampai '.$this->m_fungsi->tanggal_format_indonesia($tgl2).'
                            </td>
                        </tr>
                 </table><br>';

            $html .= '<table width="100%" border="1" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr >
                            <th align="center">ID</th>
                            <th align="center">Tanggal</th>
                            <th align="center">ID Pelanggan</th>
                            <th align="center">Nama Pelanggan</th>
                            <th align="center">ID Produk</th>
                            <th align="center">Nama Produk</th>
                            <th align="center">Harga</th>
                            <th align="center">Qty</th>
                            <th align="center">Value</th>
                            <th align="center">Disc</th>
                            <th align="center">Potongan Disc</th>
                            <th align="center">ID Promosi</th>
                            <th align="center">Disc Member</th>
                            <th align="center">Potongan Member</th>
                            <th align="center">Total</th>
                        </tr>';
                        $tot_qty = $tot_value= $tot_total = 0;
                        foreach ($query->result() as $r) {
                          $html .= '
                            <tr >
                                <td align="center">'.$r->id_penjualan.'</td>
                                <td align="center">'.$r->tanggal.'</td>
                                <td align="center">'.$r->id_pelanggan.'</td>
                                <td align="left">'.$r->nm_pelanggan.'</td>
                                <td align="center">'.$r->id_produk.'</td>
                                <td align="left">'.$r->nm_produk.'</td>
                                <td align="center">'.number_format($r->harga,0,",",".").'</td>
                                <td align="center">'.$r->qty.'</td>
                                <td align="right">'.number_format($r->value,0,",",".").'</td>
                                <td align="center">'.$r->diskon."%".'</td>
                                <td align="right">'.number_format($r->potongan,0,",",".").'</td>
                                <td align="center">'.$r->id_promosi.'</td>
                                <td align="center">'.$r->diskon_pelanggan."%".'</td>
                                <td align="right">'.number_format($potongan_member = ($r->diskon_pelanggan / 100 ) * $r->total,0,",",".").'</td>
                                <td align="right">'.number_format($total = $r->total - $potongan_member,0,",",".").'</td>
                            </tr>';

                            $tot_qty += $r->qty; 
                            $tot_value += $r->value; 
                            $tot_total += $total;
                        }
                        $html .= '
                            <tr style="background-color: #959a9a">
                                <td align="right" colspan="7">Total</td>
                                <td align="center">'.$tot_qty.'</td>
                                <td align="right">'.number_format($tot_value,0,",",".").'</td>
                                <td align="right" colspan="6">'.number_format($tot_total,0,",",".").'</td>
                            </tr>';                        
                        $html .='
                 </table>';

           
        }else{
             $html .= '<h1> Data Kosong </h1>';
        }

        $judul = "Laporan Penjualan";

        if (/*$ctk*/'1' == '0') {
            echo $html;
        }else{
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$judul.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            $data['prev']= $html;
            $this->load->view('view_excel', $data);
        }
        
    }

    function Laporan_Stok(){
        
        $query = $this->m_master->query("SELECT * FROM m_produk ORDER BY id_produk ");

        $html = '';


        if ($query->num_rows() > 0) {

            $html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td colspan="4" align="center">
                              <h1> Laporan Stok</h1>
                              
                            </td>
                        </tr>
                 </table><br>';

            $html .= '<table width="100%" border="1" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr>
                            <th align="center">ID Produk</th>
                            <th align="center">Nama Produk</th>
                            <th align="center">Satuan</th>
                            <th align="center">Stok</th>
                        </tr>';
                        $tot_stok = 0;
                        foreach ($query->result() as $r) {
                          $html .= '
                            <tr>
                                <td align="center">'.$r->id_produk.'</td>
                                <td align="center">'.$r->nm_produk.'</td>
                                <td align="center">'.$r->satuan.'</td>
                                <td align="center">'.number_format($r->stok,0,",",".").'</td>
                            </tr>';

                            $tot_stok += $r->stok; 
                        }
                        $html .= '
                            <tr style="background-color: #959a9a">
                                <td align="right" colspan="3">Total</td>
                                <td align="center">'.number_format($tot_stok,0,",",".").'</td>
                            </tr>';                        
                        $html .='
                 </table>';

           
        }else{
             $html .= '<h1> Data Kosong </h1>';
        }

        $judul = "Laporan Stok";

        if (/*$ctk*/'1' == '0') {
            echo $html;
        }else{
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$judul.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            $data['prev']= $html;
            $this->load->view('view_excel', $data);
        }
        
    }

    function lap_cost($periode,$id_kategori,$ctk){
        $data_tgl = $this->db->query("SELECT DATE_FORMAT(tanggal,'%d') day,tanggal FROM `tr_pemakaian` WHERE id_kategori='$id_kategori' AND DATE_FORMAT(tanggal,'%Y-%m') = '$periode' GROUP BY tanggal");
       
        $nm_kategori = $this->db->query("SELECT nm_kategori from m_kategori where id = '$id_kategori' ")->row("nm_kategori");
        $html = '';


        if ($data_tgl->num_rows() > 0) {
            $colspan = $data_tgl->num_rows()+2;
            $tot_cost = 0;

            $html .= '<table width="100%" border="0" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td colspan="'.$colspan.'" align="center"><h1> COST '.$nm_kategori.'</h1></td>
                        </tr>
                        <tr style="font-weight: bold;">
                            <td style="color:blue" colspan="2"><h2>'.$this->m_fungsi->periode_indonesia($periode."-01").'</h2>
                            <td colspan="'.$data_tgl->num_rows().'" align="center"></td>
                        </tr>
                 </table>';

            $html .='<table width="100%" border="1" cellspacing="0" style="font-size:14px;font-family: ;">
                        <tr style="font-weight: bold;">
                            <td>Tanggal</td>
                            <td>Harga Satuan</td>';
                            foreach ($data_tgl->result() as $r) {
                                $html.='<td>'.$r->day.'</td>';
                            }
                            $html .='
                             <td>Total Cost</td>
                        </tr>
                        <tr style="background-color:yellow;font-weight: bold;">
                            <td>Hasil Rewinder</td>
                            <td></td>';
                            $tot_rewinder = 0;
                            foreach ($data_tgl->result() as $r) {
                                $rewinder =  $this->db->query("SELECT nominal FROM `m_rewinder` WHERE  tanggal = '".$r->tanggal."' ")->row();
                                $html.='<td align="right">'.number_format($rewinder->nominal,2,",",".").'</td>';
                                $tot_rewinder += $rewinder->nominal;
                            }
                            $html .='
                            <td align="right">'.number_format($tot_rewinder,2,",",".").'</td>
                        </tr>';

                        $sub_kategori = $this->db->query("SELECT * FROM `m_sub_kategori` WHERE id_kategori='$id_kategori' and status='1' ORDER BY nm_sub_kategori ")->result();

                        $tot_pemakaian = 0;
                        foreach ($sub_kategori as $sub) {
                            $html .='
                             <tr style="font-weight: bold;">
                                <td>'.$sub->nm_sub_kategori.'</td>
                                <td></td>';
                                foreach ($data_tgl->result() as $r) {
                                   
                                    $html.='<td></td>';
                                }
                                $html .='
                                <td></td>
                             </tr>';


                             $produk = $this->db->query("SELECT * FROM `m_produk` WHERE id_kategori='$id_kategori' AND id_sub_kategori='".$sub->id."' and status='1' ORDER BY nm_produk ")->result();

                             foreach ($produk as $pro) {
                                $harga = $this->db->query("SELECT ifnull(harga,0) harga FROM `m_harga` WHERE id_produk='".$pro->id."' AND DATE_FORMAT(tanggal,'%Y-%m') = '$periode' ORDER BY id DESC limit 1");

                                 $html .='
                                     <tr>
                                        <td style="padding-left:10px"> * '.$pro->nm_produk.'</td>
                                        <td>'.($harga->num_rows() > 0 ? number_format($harga->row()->harga,2,",",".") : 0) .'</td>';
                                        
                                        $tot_cost_samping = 0;
                                        foreach ($data_tgl->result() as $r) {
                                           $pemakaian = $this->db->query("SELECT ifnull(nominal,0) nominal,ifnull(harga,0) harga FROM `tr_pemakaian` WHERE id_produk='".$pro->id."' AND tanggal = '".$r->tanggal."' limit 1");

                                           

                                           if ($pro->id == '55') {
                                               $jum_pemakaian = 0;
                                           }else if ($pro->id == '56') {
                                            $pemakaian = $this->db->query("SELECT sum(ifnull(nominal,0)) nominal,ifnull(harga,0) harga FROM `tr_pemakaian` WHERE id_produk in (53) AND tanggal = '".$r->tanggal."' limit 1");


                                            $pemakaian1 = $this->db->query("SELECT sum(ifnull(nominal,0)) nominal,ifnull(harga,0) harga FROM `tr_pemakaian` WHERE id_produk in (54) AND tanggal = '".$r->tanggal."' limit 1");


                                               $jum_pemakaian = ($pemakaian->num_rows() > 0 ? $pemakaian->row()->harga : 0) * ($pemakaian->num_rows() > 0 ? $pemakaian->row()->nominal : 0);

                                               $jum_pemakaian1 = ($pemakaian1->num_rows() > 0 ? $pemakaian1->row()->harga : 0) * ($pemakaian1->num_rows() > 0 ? $pemakaian1->row()->nominal : 0);

                                               $jum_pemakaian = ($jum_pemakaian + $jum_pemakaian1) * 0.03;
                                           }else{

                                               $jum_pemakaian = ($pemakaian->num_rows() > 0 ? $pemakaian->row()->harga : 0) * ($pemakaian->num_rows() > 0 ? $pemakaian->row()->nominal : 0);
                                           }

                                            $html.='<td align="right">'.number_format(round($jum_pemakaian),2,",",".") .'</td>';

                                            $tot_cost_samping += $jum_pemakaian;
                                        }

                                       
                                        $html .='
                                        <td align="right">'.number_format($tot_cost_samping,2,",",".") .'</td>
                                     </tr>';
                             }

                             if ($id_kategori == '3') {
                                 
                                 $html .='
                                 <tr style="font-weight: bold;">
                                    <td>Total </td>
                                    <td></td>';
                                    foreach ($data_tgl->result() as $r) {
                                        $pemakaian = $this->db->query("SELECT SUM(nominal) nominal FROM ( SELECT  ((nominal) * (harga)) nominal FROM tr_pemakaian a WHERE id_sub_kategori = '".$sub->id."' AND tanggal ='".$r->tanggal."' and id_produk <> 55)z")->row("nominal");

                                        $pemakaian1 = $this->db->query("SELECT SUM(nominal) nominal FROM ( SELECT  ((nominal) * (harga))nominal FROM tr_pemakaian a WHERE id_sub_kategori = '".$sub->id."' AND tanggal ='".$r->tanggal."' and id_produk in (53,54) )z")->row("nominal");

                                        if ($sub->id == '17') {
                                            $pemakaian = $pemakaian + ($pemakaian1 * 0.03);

                                        }
                                        $html.='<td align="right">'.number_format(round($pemakaian),2,",",".") .'</td>';
                                    }
                                    $html .='
                                    <td></td>
                                 </tr>
                                 <tr style="background-color:#3cd7ea;font-weight: bold;">
                                    <td>BIAYA '.$sub->nm_sub_kategori.' / KG PAPER</td>
                                    <td></td>';
                                    foreach ($data_tgl->result() as $r) {
                                       $pemakaian = $this->db->query("SELECT SUM(nominal) nominal FROM ( SELECT  ((nominal) * (harga)) nominal FROM tr_pemakaian a WHERE id_sub_kategori = '".$sub->id."' AND tanggal ='".$r->tanggal."' and id_produk <> '55' )z")->row("nominal");

                                       $rewinder = $this->db->query("SELECT * FROM m_rewinder WHERE  tanggal ='".$r->tanggal."'")->row("nominal");

                                       if ($sub->id == '17') {
                                           # code...
                                        $biaya =  ($pemakaian /$rewinder) + (($pemakaian * 0.03)/$rewinder);
                                       }else{
                                        $biaya =  ($pemakaian /$rewinder) ;
                                       }

                                        $html.='<td align="right">'.number_format($biaya,2,",",".") .'</td>';
                                    }
                                    $html .='
                                    <td></td>
                                 </tr>';
                             }

                        }
                        if ($id_kategori != '3') {
                            $html .='
                         <tr style="font-weight: bold;">
                            <td>Total '.$nm_kategori.'</td>
                            <td></td>';
                            $tot_cost = 0;
                            foreach ($data_tgl->result() as $r) {
                                
                                 $tot_pemakaian = $this->db->query("SELECT  SUM(pemakaian) pemakaian FROM (
                                      SELECT (nominal * harga) pemakaian FROM tr_pemakaian a WHERE tanggal ='".$r->tanggal."' AND id_kategori = '$id_kategori' )z
                                        ")->row("pemakaian");

                                

                                $html.='<td align="right">'.number_format($tot_pemakaian,2,",",".") .'</td>';
                                $tot_cost += $tot_pemakaian;
                            }

                            //total cost samping
                            $tot_cost_samping_bawah = $this->db->query("SELECT sum(total) total FROM ( SELECT ((nominal) * (harga)) total  FROM `tr_pemakaian` 
                                 WHERE DATE_FORMAT(tanggal,'%Y-%m') = '$periode' AND id_kategori = '$id_kategori' AND id_produk NOT IN (28,29,30,31,32,33) )z")->row("total");
                            $html .='
                            <td align="right">'.number_format($tot_cost_samping_bawah,2,",",".") .'</td>
                        </tr>
                         <tr style="font-weight: bold;background-color:#3cd7ea">
                            <td>BIAYA '.$nm_kategori.' /KG PAPER </td>
                            <td></td>';
                            foreach ($data_tgl->result() as $r) {
                                $rewinder =  $this->db->query("SELECT nominal FROM `m_rewinder` WHERE  tanggal = '".$r->tanggal."' ")->row();
                                $tot_bahan_baku =  $this->db->query("SELECT  SUM(pemakaian) nominal FROM (
                                      SELECT (nominal * harga) pemakaian FROM tr_pemakaian a WHERE tanggal ='".$r->tanggal."' AND id_kategori = '$id_kategori' )z
                                        ")->row("nominal");
                                

                                $biaya = $tot_bahan_baku / $rewinder->nominal;
                                $html.='<td align="right">'. number_format($biaya,2,",",".") .'</td>';
                            }
                            $html .='
                            <td></td>
                        </tr>';
                        }
                        
                       $html .='
                    </table>';
        }else{
             $html .= '<h1> Data Kosong </h1>';
        }

        $kategori = $this->db->query("SELECT nm_kategori from m_kategori WHERE id='$id_kategori' ")->row("nm_kategori");

        $kategori = str_replace(",", "", $kategori);
        $judul = "Laporan Cost ".$kategori." periode ".$periode;

        if ($ctk == '0') {
            echo $html;
        }else{
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=$judul.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            $data['prev']= $html;
            $this->load->view('view_excel', $data);
        }
    }

    

   
}