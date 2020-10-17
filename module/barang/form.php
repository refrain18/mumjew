<?php

    $barang_id = isset($_GET['barang_id']) ? $_GET['barang_id'] : false;

    $nama_barang = "";
    $kategori_id = "";
    $spesifikasi = "";
    $gambar = "";
    $stok = "";
    $harga = "";
    $harga_distributor = "";
    $diskon = "";
    $bb_id = "";
    $status = "";
    $keterangan_gambar = "";
    $button = "Add";

    if($barang_id){
        $queryKategori = mysqli_query($koneksi, "SELECT * FROM barang WHERE barang_id='$barang_id'");
        $row = mysqli_fetch_assoc($queryKategori);

        $nama_barang = $row['nama_barang'];
        $kategori_id = $row['kategori_id'];
        $spesifikasi = $row['spesifikasi'];
        $gambar = $row['gambar'];
        $harga = $row['harga'];
        $harga_distributor = $row['harga_distributor'];
        $diskon = $row['diskon'];
        $bb_id = $row['bb_id'];
        $stok = $row['stok'];
        $status = $row['status'];
        $button = "Update";


        $keterangan_gambar = "(Klik pilih gambar jika ingin mengganti gambar disamping)";
        $gambar = "<img src='".BASE_URL."images/barang/$gambar' style='width: 200px; vertical-align: middle;'/>";
    }
    // var_dump($bb_id,$gambar); die;

?>

<script src="<?php echo BASE_URL."js/ckeditor/ckeditor.js"; ?>"></script>

<form action="<?php echo BASE_URL."module/barang/action.php?barang_id=$barang_id";?>" method="POST" enctype="multipart/form-data">

    <?php

        if ( isset($_GET['notif']) ) {
            echo notifTransaksi($_GET['notif']);
        }

    ?>

    <div class="element-form">
        <label>Kategori</label>
        <span>
        
            <select name="kategori_id">
                <?php
                    $query = mysqli_query($koneksi, "SELECT kategori_id, kategori FROM kategori WHERE status='on' ORDER BY kategori ASC");
                    while($row=mysqli_fetch_assoc($query)){
                        if($kategori_id == $row['kategori_id']) {
                            echo "<option value='$row[kategori_id]' selected='true'>$row[kategori]</option>";
                        }else{
                            echo "<option value='$row[kategori_id]'>$row[kategori]</option>";
                        }
                    }
                ?>
            </select>
        
        </span>
    </div>

    <div class="element-form">
        <label>Nama Barang</label>
        <span><input type="text" name="nama_barang" value="<?php echo $nama_barang; ?>" /></span>
    </div>

    <div style="margin-bottom:10px">
        <label style="font-weight:bold">Spesifikasi</label>
        <span><textarea name="spesifikasi" id="editor"><?php echo $spesifikasi; ?></textarea></span>
    </div>

    <div class="element-form">
        <label>Stok</label>
        <span><input type="text" name="stok" value="<?php echo $stok; ?>" /></span>
    </div>

    <div class="element-form">
        <label>Harga Distributor</label>
        <span><input type="text" name="harga_distributor" value="<?php echo $harga_distributor; ?>" /></span>
    </div>

    <div class="element-form">
        <label>Harga</label>
        <span><input type="text" name="harga" value="<?php echo $harga; ?>" /></span>
    </div>

    <div class="element-form">
        <label>Diskon</label>
        <span><input type="number" name="diskon" value="<?php echo $diskon; ?>" /> % </span>
    </div>

    <div class="element-form">
        <label>Brand</label>
        <span>
        
            <select name="bb_id">
                <?php
                    $query = mysqli_query($koneksi, "SELECT bb_id, banner_branded FROM banner_branded WHERE status='on' ORDER BY banner_branded ASC");
                    while($row=mysqli_fetch_assoc($query)){
                        if($bb_id == $row['bb_id']) {
                            echo "<option value='$row[bb_id]' selected='true'>$row[banner_branded]</option>";
                        }else{
                            echo "<option value='$row[bb_id]'>$row[banner_branded]</option>";
                        }
                    }
                ?>
            </select>
        
        </span>
    </div>

    <div class="element-form">
        <label>Gambar Produk <?php echo $keterangan_gambar; ?></label>
        <span>
            <input type="file" name="file">  <?php echo $gambar; ?> 
        </span>
    </div>

    <div class="element-form">
        <label>Status</label>
        <span>
              <input type="radio" name="status" value="on" <?php if($status == "on"){ echo "checked='true'"; } ?> />On
              <input type="radio" name="status" value="off" <?php if($status == "off"){ echo "checked='true'"; } ?> />Off
        </span>
    </div>

    <div class="element-form">
        <span><input type="submit" name="button" value="<?php echo $button; ?>" /></span>
    </div>

</form>

<script>
    CKEDITOR.replace("editor");
</script>