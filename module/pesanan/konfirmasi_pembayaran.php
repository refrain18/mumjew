<?php

    $pesanan_id = $_GET["pesanan_id"];

    $queryKonfirmasiPembayaran = mysqli_query($koneksi, "SELECT p.status, k.nomor_rekening, k.nama_account, k.bukti_pembayaran, k.tanggal_transfer FROM konfirmasi_pembayaran k JOIN pesanan p ON k.pesanan_id = p.pesanan_id WHERE p.pesanan_id LIKE '$pesanan_id'") OR die(mysqli_error($koneksi));

    

?>

<table class="table-list">
    <?php if( $data = mysqli_fetch_array($queryKonfirmasiPembayaran)) :?>
        <form action=<?php echo BASE_URL."index.php?page=my_profile&module=pesanan&action=list"; ?> method="POST">

            <div class="element-form">
                <label>Nomor Rekening</label>
                <span><input type="text" name="nomor_rekening" value="<?php echo $data['nomor_rekening'] ?>" readonly/></span>
            </div>

            <div class="element-form">
                <label>Nama Account</label>
                <span><input type="text" name="nama_account" value="<?php echo $data['nama_account'] ?>" readonly/></span>
            </div>

            <div class="element-form">
                <label>Bukti Pembayaran</label>
                <span><input type="file" name="bukti_pembayaran"  value="<?php echo $data['bukti_pembayaran'] ?>" readonly/></span>
            </div>

            <div class="element-form">
                <label>Tanggal Transfer (format: yyyy-mm-dd)</label>
                <span><input type="date" name="tanggal_transfer" value="<?php echo $data['tanggal_transfer'] ?>" readonly/></span>
            </div>

            <div class="element-form">
                <span><input type="submit" value="Kembali" name="button" /></span>
            </div>
        </form> 
    <?php else : ?>
        <form action=<?php echo BASE_URL."module/pesanan/action.php?pesanan_id=$pesanan_id"; ?> method="POST">

            <div class="element-form">
                <label>Nomor Rekening</label>
                <span><input type="text" name="nomor_rekening" /></span>
            </div>

            <div class="element-form">
                <label>Nama Account</label>
                <span><input type="text" name="nama_account" /></span>
            </div>

            <div class="element-form">
                <label>Bukti Pembayaran</label>
                <span><input type="file" name="bukti_pembayaran"  /></span>
            </div>

            <div class="element-form">
                <label>Tanggal Transfer (format: mm/dd/yyyy)</label>
                <span><input type="date" name="tanggal_transfer" /></span>
            </div>

            <div class="element-form">
                <span><input type="submit" value="Konfirmasi" name="button" /></span>
            </div>
        </form> 
    <?php endif; ?>
</table>    