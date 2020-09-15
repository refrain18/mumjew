<?php
    if($user_id == false){
        $_SESSION["proses_pesanan"] = true;

        header("location: ".BASE_URL."login.html");
        exit;
    }

    
    if (isset($_GET['edit']) && $_GET['edit'] == 'on') {
        $edit = 'on';
        $button = "Update";        
    } else {
        $edit = 'off';
        $button = "Edit";
    }
    
    // Cek Jika Data Diri Di Ubah
    if (!empty($_POST)) {
        // Update Data
        $data_diri_baru = [
            'nama' => $_POST['nama'],
            'phone' => $_POST['phone'],
            'provinsi' => $_POST['provinsi'],
            'kota' => $_POST['kota'],
            'kode_pos' => $_POST['kode_pos'],
            'alamat' => $_POST['alamat']
        ];
    }

    // var_dump($post_data_diri, $data_diri_baru);

    //Query Select 
    $query = mysqli_query($koneksi, "SELECT user.nama, user.phone, user.provinsi, user.kota, user.kode_pos, user.alamat FROM user WHERE user_id = $user_id");
    $row=mysqli_fetch_array($query);

    $nama = $row["nama"];
    $phone = $row["phone"];
    $provinsi = $row["provinsi"];
    $kota = $row["kota"];
    $kode_pos = $row["kode_pos"];
    $alamat = $row["alamat"];

    // var_dump($query,$user_id ,$row['alamat']);
    // die();

    if($edit == 'on') {
        $akses = '';
        $hidden = '';
        $form_act = 'index.php?page=data-pemesan';
    } else {
        $akses = 'readonly';
        $hidden = 'hidden';
        $form_act = 'index.php?page=proses_pemesanan';
    }
?>

<div id="frame-data-pengiriman">

    <h3 class="label-data-pengiriman">Alamat Pengiriman Barang</h3>

    <div id="frame-form-pengiriman">

        <form action="<?php echo BASE_URL.$form_act; ?>" method="POST">

            <div class="element-form">
                <label>Nama Penerima</label>	
                <span><input type="text" name="nama_penerima" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['nama'] : $nama; ?>" <?php echo $akses?>/></span>
            </div>

            <div class="element-form">
                <label>Nomor Telepon</label>	
                <span><input type="text" name="nomor_telepon" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['phone'] : $phone; ?>" <?php echo $akses?>/></span>
            </div>	

            <div class="element-form">
                <label>Provinsi</label>
                <span><input type="text" id="input_provinsi" name="provinsi" list="data-provinsi" onchange="update_list_kota(this.value);" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['provinsi'] : $provinsi; ?>" <?php echo $akses?>/></span>
            </div>
            
            <?php if(isset($list_provinsi) && is_array($list_provinsi)) : ?>
                <datalist id="data-provinsi">
                    <?php foreach ($list_provinsi['rajaongkir']['results'] as $key => $value) : ?>
                        <option><?php echo $value['province'] ?></option>
                    <?php endforeach; ?>
                </datalist>
            <?php endif; ?>

            <div class="element-form">
                <label>Kota</label>
                <span><input type="text" id="input_kota" name="kota" list="data-kota" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['kota'] : $kota; ?>" <?php echo $akses?>/></span>
                
                <datalist id="data-kota">
                    <!-- Data Kota -->
                </datalist>
            </div>

            <div class="element-form">
                <label>Kode Pos</label>
                <span><input type="text" name="kode_pos" value="<?php echo !empty($data_diri_baru) ? $data_diri_baru['kode_pos'] : $kode_pos; ?>" <?php echo $akses?>/></span>
            </div>

            <div class="element-form">
                <label>Alamat Pengiriman</label>	
                <span><textarea name="alamat" id="" cols="30" rows="5" <?php echo $akses?>><?php echo !empty($data_diri_baru) ? $data_diri_baru['alamat'] : $alamat; ?></textarea></span>
            </div>

            <div class="element-form">
            <?php if($edit == "on") : ?>
                <span style="text-align: right">
                    <input type="submit" name="button" value="<?php echo $button; ?>" class="submit-my-profile" />
                    <!-- <a href="<?php //echo BASE_URL."index.php?page=data-pemesan&edit=off" ;?>" class="submit-my-profile"><?php //echo $button; ?></a> -->
                </span>
            <?php else : ?>
                <span style="text-align: right">
                    <a href="<?php echo BASE_URL."index.php?page=data-pemesan&edit=on" ;?>" class="submit-my-profile"><?php echo $button; ?></a>
                </span>
            <?php endif; ?>
            </div>

            <span><hr></span>
 
            <div class="element-form">
                <label>Metode Pembayaran : </label>
                <span>
                    <input type="radio" id="cod" name="metode_pembayaran" value="cod" onclick="cek_ongkir(this.value)">
                    <span style="display: inline-block;">
                        <label for="cod">COD</label>
                    </span>
                    <input type="radio" id="tf" name="metode_pembayaran" value="tf" onclick="cek_ongkir(this.value)">
                    <span style="display: inline-block;">
                        <label for="tf">Transfer Bank</label>
                    </span>
                </span>
            </div>

            <div class="element-form">
                <label>Metode Pengiriman : </label>
                <span>
                    <select name="metode_pengiriman" id="daftar_metode_pengiriman">
                        <option selected>-Pilih-</option>
                        <!-- Opsi akan muncul setelah memilih Metode Pembayaran -->
                    </select>
                </span>
            </div>

            <div class="element-form">
                <span><input type="submit" value="submit"/></span>
            </div>

        </form>
        
        <!-- Menghapus data POST -->
        <?php if (isset($_POST)) {
            $_POST = array();
        } ?>

    </div>

</div>

<div id="frame-data-detail">
    <h3 class="label-data-pengiriman">Detail Order</h3>
    
    <div id="frame-detail-order">
        
        <table class="table-list">
            <tr>
                <th class='kiri'>Nama Barang</th>
                <th class='tengah'>Qty</th>
                <th class='kanan'>Total</th>
            </tr>
            <?php
                $subtotal = 0;
                foreach($keranjang AS $key => $value){

                    $barang_id = $key;

                    $nama_barang = $value['nama_barang'];
                    $harga = $value['harga'];
                    $quantity = $value['quantity'];

                    $total = $quantity * $harga;
                    $subtotal = $subtotal + $total;

                    echo "<tr>
                            <td class='kiri'>$nama_barang</td>
                            <td class='tengah'>$quantity</td>
                            <td class='kanan'>".rupiah($total)."</td>
                          </tr>";
                }
                echo "<tr>
                        <td colspan='2' class='kanan'><b>Sub Total</b></td>
                        <td class='kanan'><b>".rupiah($subtotal)."</b></td>
                     </tr>"; 
            ?>


        </table>

    </div>

</div>