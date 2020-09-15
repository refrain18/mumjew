<?php

    include_once("function/koneksi.php");
    include_once("function/helper.php");

    session_start();

    $nama_penerima = $_POST["nama_penerima"];
    $nomor_telepon = $_POST["nomor_telepon"];
    $alamat = $_POST["alamat"];
    $kota = $_POST["kota"];
    $mtd_bayar = $_POST["metode_pembayaran"];
    $mtd_kirim = $_POST["metode_pengiriman"];

    $user_id = $_SESSION['user_id'];
    $waktu_saat_ini = date("Y-m-d H:i:s");

    $query = mysqli_query($koneksi, "INSERT INTO pesanan (nama_penerima, user_id, nomor_telepon, kota_id, alamat, tanggal_pemesanan ,metode_pembayaran, metode_pengiriman status)
                                                 VALUES ('$nama_penerima', '$user_id', '$nomor_telepon', '$kota', '$alamat', '$waktu_saat_ini', '$mtd_bayar', '$mtd_kirim', '1')");

    if($query){
        $last_pesanan_id = mysqli_insert_id($koneksi);

        $keranjang = $_SESSION['keranjang'];

        foreach($keranjang AS $key => $value){
            $barang_id = $key;
            $quantity = $value['quantity'];
            $harga = $value['harga'];

            mysqli_query($koneksi, "INSERT INTO pesanan_detail(pesanan_id, barang_id, quantity, harga)
                                                    VALUES ('$last_pesanan_id', '$barang_id', '$quantity', '$harga')");
        }

        unset($_SESSION["keranjang"]);

        header("location:".BASE_URL."index.php?page=my_profile&module=pesanan&action=detail&pesanan_id=$last_pesanan_id");
    }                                             