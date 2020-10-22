<?php
    include("../../function/koneksi.php");   
	include("../../function/helper.php");   
	
	$button = isset($_POST['button']) ? $_POST['button'] : false;
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
    
	$nama = isset($_POST['nama']) ? $_POST['nama'] : false;
    $email = isset($_POST['email']) ? $_POST['email'] : false;
    $email_lama = isset($_POST['email_lama']) ? $_POST['email_lama'] : false;
	$phone = isset($_POST['phone']) ? $_POST['phone'] : false;
	$provinsi = isset($_POST['provinsi']) ? $_POST['provinsi'] : false;
	$kota = isset($_POST['kota']) ? $_POST['kota'] : false;
	$kode_pos = isset($_POST['kode_pos']) ? $_POST['kode_pos'] : false;
	$alamat = isset($_POST['alamat']) ? $_POST['alamat'] : false;
	$password = !empty($_POST['password']) ? md5($_POST['password']) : false;
    $repassword = !empty($_POST['repassword']) ? md5($_POST['repassword']) : false;

	if ($button == "Update") {
        // Prepare Var
        $update_email = '';
        $update_pass = '';

        // Cek apakah ada perubahan email
        if ($email_lama != $email) {
            $update_email = "email = '$email', ";
        }
        
        // Cek apakah ada perubahan password
        if ($password) {
            if ($password != $repassword) {
                header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&user_id=$user_id&notif=gagal_update&pesan_err=pass_tidak_sama");
                die();    
            }
            $update_pass = ", password = '$password' ";
        }
        $sql = mysqli_query($koneksi, "UPDATE 
                                        user 
                                        SET 
                                        nama='$nama',
                                        $update_email
                                        phone='$phone',
                                        provinsi='$provinsi',
                                        kota='$kota',
                                        kode_pos='$kode_pos',
                                        alamat='$alamat'
                                        $update_pass
                                        WHERE user_id='$user_id'") or die(mysqli_error($koneksi));
        if ($sql) {
            header("location: ".BASE_URL."index.php?page=my_profile&module=data_diri&action=form&notif=sukses_update");
            die(); 									                   
        } else {
            die('Gagal Update, Terjadi kesalahan pada Query!');
        }
	}											   
?>