
<div id="kiri">

    <?php

        echo kategori($kategori_id);
    
    ?>

</div>

<div id="kanan">

    <div id="slides">

        <?php

            $queryBanner = mysqli_query($koneksi, "SELECT * FROM banner WHERE status='on' ORDER BY banner_id DESC LIMIT 3");
            while($rowBanner=mysqli_fetch_assoc($queryBanner)) {
                echo "<a href='".BASE_URL."$rowBanner[link]'><img src='".BASE_URL."images/slide/$rowBanner[gambar]' /></a>";
            }

        ?>

    </div>

    <div class="banner_branded">
        <div class="item">
            <ul id="content-slider" class="content-slider">
                <?php 
                $queryBannerBranded = mysqli_query($koneksi, "SELECT * FROM banner_branded WHERE status='on' ORDER BY bb_id");
                
                // while($rowBBlink=mysqli_fetch_assoc($queryBannerBranded)){
                //     $bb_id = strtolower($row['bb_id']);
                //     if($bb_id == $rowBBlink['bb_id']) {
                //         $string .= "<li><a href='".BASE_URL."$rowBBlink[bb_id]/$banner_branded.html' class='active'>$rowBBlink[banner_branded]</a></li>";
                //     }else{
                //         $string .= "<li><a href='".BASE_URL."$rowBBlink[bb_id]/$banner_branded.html'>$rowBBlink[banner_branded]</a></li>";
                //     }
                // }
                
                // while($rowBB=mysqli_fetch_array($queryBannerBranded)) {
                //     echo "<li><img src='".BASE_URL."images/bb/$rowBB[gambar]'/></li>";
                // }         
                ?>
                
                <?php while ($rowBB=mysqli_fetch_array($queryBannerBranded)) { ?>
                    <li>
                    <?php echo "<a href='".BASE_URL."index.php?bb_id=$rowBB[bb_id] '>" ?> <img src="<?php echo BASE_URL ?>/images/bb-original/<?php echo $rowBB['gambar'] ?>"> </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div id="frame-tambah">
    <div id="left">
        <form action="<?php echo BASE_URL."penulusuran.php"; ?>" method="GET">
                <input type="text" name="keyword" size="40px" placeholder="Ketikan Nama Barang dan Kategori"/>
                <button>Search</button>
        </form>
    </div>
    </div>

    <div id="frame-barang">
        <ul>
            <?php
                if($kategori_id) {
                    $kategori_id = "AND barang.kategori_id='$kategori_id'";
                }
            
                if($bb_id) {
                    $bb_id = "AND barang.bb_id='$bb_id'";
                }
            // Prepare Diskon Logic
            if ($id_member) {
                $query = mysqli_query($koneksi, "SELECT barang.*, kategori.kategori, banner_branded.banner_branded FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id WHERE barang.status='on' AND barang.stok > 0 $kategori_id $bb_id ORDER BY rand() DESC ") OR die(mysqli_error($koneksi));
            } else {
                $query = mysqli_query($koneksi, "SELECT barang.*, kategori.kategori, banner_branded.banner_branded FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id JOIN banner_branded ON barang.bb_id=banner_branded.bb_id WHERE barang.status='on' AND barang.stok > 0 $kategori_id $bb_id ORDER BY rand() DESC ");
            }
                
                $no=1;

                // Prepare Strike Style
                $stripOp = '';
                $stripEd = '';

                while($row=mysqli_fetch_assoc($query)){

                    $kategori = strtolower($row["kategori"]);
                    $barang = strtolower($row["nama_barang"]);
                    $barang = str_replace(" ", "-", $barang);
                    $brand = strtolower($row["banner_branded"]);


                    $style=false;
                    if($no == 3){
                        $style="style='margin-right:0px'";
                        $no=0;
                    }
                    
                    // Pengkondisian untuk menampilkan harga distributor
                    if ($level == "superadmin") {
                        $show_harga_dist = "<p class='priced'>".rupiah($row['harga_distributor'])."</p>";
                    } else {
                        $show_harga_dist = "";
                    }

                    if ($id_member) {
                        $hrg_asli = $row['harga'];
                        $disc = $row['diskon'];
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

                    echo "<li $style>
                            <div>
                            <p class='price'>{$stripOp}".rupiah($row['harga'])."{$stripEd}</p>
                            {$show_harga_dist}
                            {$show_harga_disc}
                            </div>
                            <a href='".BASE_URL."$row[barang_id]/$kategori/$barang.html'>
                                <img src='".BASE_URL."images/barang/$row[gambar]' />
                            </a> 
                            <div class='keterangan-gambar'> 
                                <p><a href='".BASE_URL."$row[barang_id]/$kategori/$barang.html'>$row[nama_barang]</a></p>
                                <span>Stok : $row[stok]</span>
                                <p class='brand'>".$row['banner_branded']."</p>
                            </div>
                            <div class='button-add-cart'>
                                <a href='".BASE_URL."tambah_keranjang.php?barang_id=$row[barang_id]'>+ add to cart</a>
                            </div>";

                    $no++;        
                }

            ?>
        
        </ul>
        
    </div>
</div>