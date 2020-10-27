<?php
    include_once("function/koneksi.php");
    include_once("function/helper.php");

    session_start();

    $kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : false; 

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
    $id_member = isset($_SESSION['id_member']) ? $_SESSION['id_member'] : false;
    $nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : false;
    $level = isset($_SESSION['level']) ? $_SESSION['level'] : false;
    $keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : array();
    $totalBarang = count($keranjang);

    $keyword = $_GET["keyword"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pencarian</title>

        <link href="<?php echo BASE_URL."css/fontawesome-free-5.13.1-web/css/all.min.css"; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo BASE_URL."css/style.css"; ?>" type="text/css" rel="stylesheet" />

</head>
<body>
    
    <div id="container">
        <div id="header">
            <a href="<?php echo BASE_URL."index.php"; ?>">
                <img src="<?php echo BASE_URL."images/logo.png"; ?>"/>
            </a>

            <div id="menu">
                <div id="user">
                <?php
                    if($level == "superadmin"){
                      $laporan = "<a href='".BASE_URL."index.php?page=laporan&module=laporan_pesanan&action=list'>Laporan</a>";
                      $style = 'style="display: inline-block; padding-left: 65px;"';
                    }else if ($level == "customer") {
                      $style = 'style="display: inline-block; padding-left: 135px;"';
                      $laporan = "";
                    }else{
                      $laporan = "";
                      $style = 'style="display: inline-block; padding-left: 210px;"';

                    }
                        if($user_id){
                            echo "Hi <b>$nama</b>, 
                                  <a href='".BASE_URL."index.php?page=my_profile&module=pesanan&action=list'>My Profile</a>
                                  {$laporan}
                                  <a href='".BASE_URL."logout.php'>Logout</a>";
                        }else{
                            echo "<a href='".BASE_URL."login.html'>Login</a>
                                  <a href='".BASE_URL."register.html'>Register</a>";
                        }
                    ?>
                    
                <form action="<?php echo BASE_URL."penulusuran.php"; ?>" <?php echo $style ?> method="GET">
                <input type="text" name="keyword" size="60px" style="border: 0px; height: 18px;" value="<?php echo $keyword ?>"/>
                            <button >Search</button>
                </form>
                </div>
                <a href="<?php echo BASE_URL."keranjang.html"; ?>" id="button-keranjang">
                    <img src="<?php echo BASE_URL."images/cart.png"; ?>"/>
                    <?php
                        if($totalBarang != 0){
                            echo "<span class='total-barang'>$totalBarang</span>";
                        }
                    ?>
                </a>
            </div>        
        </div>

        <div id="content">
            <?php
                 $keyword = $_GET["keyword"];
                 $semuadata=array();
                 $ambil = $koneksi->query("SELECT barang.*, kategori.kategori, banner_branded.banner_branded FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id WHERE barang.status='on' AND barang.stok > 0 AND 
                                           nama_barang LIKE '%$keyword%' OR banner_branded LIKE '%$keyword%' OR kategori LIKE '%$keyword%'");
                
            ?>
            <div id="kanan" style="width: auto;">

                <div id="left" style="padding-left: 120px;">
                    
                    <h3>Hasil Pencarian : <?php echo $keyword ?></h3>
                  
                </div>

                <div id="frame-barang"> 
                    <?php
                        // Prepare Strike Style
                        $stripOp = '';
                        $stripEd = ''; 
                    ?>
                     <!-- foreach ($semuadata as $key => $value) {
                         echo 'Data = '. $value[''];
                     } -->
                    <ul style="padding-left: 120px;">
                        <?php 
                            if (mysqli_num_rows($ambil) == 0 ) {  
                                echo "<h4 class='notif'><u>$keyword</u> Tidak Ditemukan!</h4>";
                            } else {
                        ?>
                        <?php while($pecah = $ambil->fetch_assoc()) { 
                                {
                                    $semuadata[]=$pecah;
                                }
                                ?>
                        
                        <?php
                            // Pengkondisian untuk menampilkan harga distributor
                            if ($level == "superadmin") {
                                $show_harga_dist = "<p class='priced'>".rupiah($pecah['harga_distributor'])."</p>";
                            } else {
                                $show_harga_dist = "";
                            }

                            if ($id_member) {
                                $hrg_asli = $pecah['harga'];
                                $disc = $pecah['diskon'];
                                $hrg_disc = '';

                                // Perhitungan Diskon
                                $hrg_disc = $hrg_asli * ($disc/100);
                                $total_harga_diskon = $hrg_asli - $hrg_disc;
                                $show_harga_disc = "<p class='diskon'>"."<span>{$disc}%</span> ".rupiah($total_harga_diskon)."</p>";

                                if ($disc != 0) {
                                    $stripOp = '<del>';
                                    $stripEd = '</del>';
                                }
                            } else {
                                $show_harga_disc = '';
                            }
                        ?>
                            <li>
                                <div>
                                    <p class="brand"><?php echo $pecah['banner_branded']; ?></p>
                                    <p class="price"><?php echo $stripOp.rupiah($pecah['harga']).$stripEd ?></p>
                                    <?php
                                    echo $show_harga_dist;
                                    echo $show_harga_disc;
                                    ?>
                                </div>
                                    <a href="<?php //echo BASE_URL.''.$pecah['barang_id'].'/'.$pecah['kategori'].'/'.$pecah['nama_barang'].'html' ?>" >
                                        <img src="<?php echo BASE_URL.'images/barang/'.$pecah['gambar']; ?>" />
                                    </a> 
                                <div class="keterangan-gambar"> 
                                    <p>
                                        <a href="<?php //echo BASE_URL.'$pecah[barang_id]/$kategori/$barang.html' ?>"><?php echo $pecah['nama_barang'] ?></a>
                                    </p>
                                    <span>Stok : <?php echo $pecah['stok']; ?></span>
                                </div>
                                <div class="button-add-cart">
                                    <a href="<?php echo BASE_URL.'tambah_keranjang.php?barang_id='.$pecah['barang_id']; ?>">+ add to cart</a>
                                </div>
                        
                        <?php } } ?>
                    </ul>
                        
                </div>

            </div>
            
        </div>    
        <div id="footer">
            <p>copyright Mumtaza Jewerly 2020</p>
        </div>

    </div>

</body>
</html>