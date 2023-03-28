<?php 
  $setting = $this->db->query("SELECT * FROM m_setting")->row();
  $level = $this->session->userdata('level');
  $nm_user = $this->session->userdata('nm_user');
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $setting->nm_aplikasi ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="<?= base_url('assets/gambar/').$setting->logo ?>">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/all.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/toastr/toastr.min.css">
  <!-- Select picker -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/selectpicker/css/bootstrap-select.css">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/customFont.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="<?= base_url('assets/') ?>plugins/jquery/jquery.min.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        Selamat Datang, <a href="#"><?= $nm_user ?></a>
      </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link"  href="<?= base_url('Login/logout') ?>">
          Logout
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
   

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="<?= base_url('assets/') ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
          <a href="<?= base_url('Master') ?>" class="d-block"><h2><?= $setting->singkatan ?></h2></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
            <a href="<?= base_url('Master') ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <?php if ($level == "Administrator" || $level == "HO" || $level == "Kasir" ){ ?>
          
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  Master
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <?php if ($level != "Kasir"){ ?>
                <li class="nav-item">
                  <a href="<?= base_url('Master/Kategori') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Kategori</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Master/Satuan') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Satuan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Master/Produk') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Produk</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Master/Supplier') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Supplier</p>
                  </a>
                </li>

                <?php } ?>

                <?php if ($level != "HO"){ ?>
                <li class="nav-item">
                  <a href="<?= base_url('Master/Pelanggan') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pelanggan</p>
                  </a>
                </li>
                <?php } ?>

                <?php if ($level != "Kasir"){ ?>
                <li class="nav-item">
                  <a href="<?= base_url('Master/Store') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Store</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Master/User') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User</p>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </li>
            
            <?php if ($level != "Kasir"){ ?>

              <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>
                    Pembelian
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="<?= base_url('Transaksi/FormPembelian') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Form Pembelian</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="<?= base_url('Transaksi/Pembelian') ?>" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Pembelian</p>
                    </a>
                  </li>
                </ul>
              </li>
            <?php } ?>

          <?php } ?>


          
          <?php if ($level == "Administrator" || $level == "Kasir"){ ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Penjualan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?= base_url('Transaksi/Penjualan') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Penjualan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('Transaksi/Penjualan_detail') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Penjualan Detail</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('Transaksi/Kasir') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kasir</p>
                </a>
              </li>
            </ul>
          </li>

          <?php } ?>

          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-dolly-flatbed"></i>
              <p>
                Inventory
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($level == "Gudang"){ ?>
                <li class="nav-item">
                  <a href="<?= base_url('Transaksi/Pembelian') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Data Terima Barang</p>
                  </a>
                </li>
              <?php } ?>
              <li class="nav-item">
                <a href="<?= base_url('Transaksi/Inventory') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Inventory</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('Transaksi/KartuStok') ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kartu Stok</p>
                </a>
              </li>
            </ul>
          </li>

          <?php if ($level == "Administrator" || $level == "Gudang"){ ?>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-exchange-alt"></i>
                <p>
                  Stok Transfer
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('Transaksi/FormStokTransfer') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Form Stok Transfer</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Transaksi/StokTransfer') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Stok Transfer</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Transaksi/TerimaStokTransfer') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Terima Stok Transfer</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-sliders-h"></i>
                <p>
                  Adjustment Stok
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('Transaksi/FormAdjustmentStok') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Form Adjustment Stok</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Transaksi/AdjustmentStok') ?>" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Adjustment Stok</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item has-treeview">
              <a href="<?= base_url('Transaksi/Closing') ?>" class="nav-link">
                <i class="nav-icon fas fa-clone"></i>
                <p>
                  Closing Bulanan
                </p>
              </a>
              
            </li>
          <?php } ?>
          
          <?php if ($level == "Administrator" || $level == "HO"){ ?>
          <li class="nav-header">Pengaturan</li>
          <li class="nav-item has-treeview">
            <a href="<?= base_url('Master/Sistem') ?>" class="nav-link">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>
                Sistem
              </p>
            </a>
            
          </li>
          <?php } ?>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>