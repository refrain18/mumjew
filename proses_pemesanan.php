<?php

    include_once("function/koneksi.php");
    include_once("function/helper.php");

    // Jalankan Session Jika session blm dijalankan
    if (!isset($_SESSION)) {
        session_start();
    }

    $nama_penerima = $_POST["nama_penerima"];
    $nomor_telepon = $_POST["nomor_telepon"];
    $provinsi = $_POST["provinsi"];
    $kota = $_POST["kota"];
    $kode_pos = $_POST["kode_pos"];
    $alamat = $_POST["alamat"];
    $mtd_bayar = $_POST["metode_pembayaran"];
    $mtd_kirim = $_POST["metode_pengiriman"];


    // Memecah value dari metode pengiriman
    $mtd_kirim = explode("_", $mtd_kirim);

    // Pecahan data metode kirim
    $jasa_kurir = strtoupper($mtd_kirim[0])." ".$mtd_kirim[1];
    $biaya_kurir = $mtd_kirim[2];

    $user_id = $_SESSION['user_id'];
    $waktu_saat_ini = date("Y-m-d H:i:s");

    $query = mysqli_query($koneksi, "INSERT INTO pesanan (nama_penerima, user_id, nomor_telepon, provinsi, kota, kode_pos, alamat, tanggal_pemesanan ,metode_pembayaran, metode_pengiriman, status)
                                                 VALUES ('$nama_penerima', '$user_id', '$nomor_telepon', '$provinsi', '$kota', '$kode_pos', '$alamat', '$waktu_saat_ini', '$mtd_bayar', '$jasa_kurir', '1')");

    if($query){
        $last_pesanan_id = mysqli_insert_id($koneksi);

        $keranjang = $_SESSION['keranjang'];

        foreach($keranjang AS $key => $value){
            $barang_id = $key;
            $quantity = $value['quantity'];
            $diskon = $value["diskon"];
            $harga = $value['harga'];

            $harga_diskon = $harga * ($diskon/100);
            $total_harga_diskon = $harga - $harga_diskon;

            if ($id_member) {
                $harga = $total_harga_diskon;
            }else{
                $harga = $value['harga'];
            }    

            // var_dump($harga);
            // die();

            mysqli_query($koneksi, "INSERT INTO pesanan_detail(pesanan_id, barang_id, quantity, harga, biaya_pengiriman)
                                                    VALUES ('$last_pesanan_id', '$barang_id', '$quantity', '$harga', '$biaya_kurir')");
        }

        unset($_SESSION["keranjang"]);

        header("location:".BASE_URL."index.php?page=my_profile&module=pesanan&action=detail&pesanan_id=$last_pesanan_id");
    } else {
        echo "Query Error".mysqli_error($koneksi); 
    }                                       