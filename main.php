
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
                // while($rowBB=mysqli_fetch_array($queryBannerBranded)) {
                //     echo "<li><img src='".BASE_URL."images/bb/$rowBB[gambar]'/></li>";
                // }         
                ?>
                
                <?php while ($rowBB=mysqli_fetch_array($queryBannerBranded)) { ?>
                    <li>
                    <img src="<?php echo BASE_URL ?>/images/bb-original/<?php echo $rowBB['gambar'] ?>">
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
                
            $query = mysqli_query($koneksi, "SELECT barang.*, kategori.kategori FROM barang JOIN kategori ON barang.kategori_id=kategori.kategori_id WHERE barang.status='on' AND barang.stok > 0 $kategori_id ORDER BY rand() DESC ");
                
                $no=1;
                while($row=mysqli_fetch_assoc($query)){

                    $kategori = strtolower($row["kategori"]);
                    $barang = strtolower($row["nama_barang"]);
                    $barang = str_replace(" ", "-", $barang);
                    $brand = strtolower($row["brand"]);


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

                    echo "<li $style>
                            <div>
                            <p class='brand'>".$row['brand']."</p>
                            <p class='price'>".rupiah($row['harga'])."</p>
                            {$show_harga_dist}
                            </div>
                            <a href='".BASE_URL."$row[barang_id]/$kategori/$barang.html'>
                                <img src='".BASE_URL."images/barang/$row[gambar]' />
                            </a> 
                            <div class='keterangan-gambar'> 
                                <p><a href='".BASE_URL."$row[barang_id]/$kategori/$barang.html'>$row[nama_barang]</a></p>
                                <span>Stok : $row[stok]</span>
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