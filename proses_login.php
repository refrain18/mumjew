<?php

    include_once("function/koneksi.php");
    include_once("function/helper.php");

    $kode_member = isset($_POST['kode_member']) ? $_POST['kode_member'] : "";
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email='$email' AND password='$password' AND status='on'") OR die(mysqli_error($koneksi));

    if(mysqli_num_rows($query) == 0){
        header("location:".BASE_URL . "index.php?page=login&notif=true");
    }else{

        $row = mysqli_fetch_assoc($query);

        session_start();

        // Get User Data
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['level'] = $row['level'];
        
        // Get ID Member
        $queryMember = mysqli_query($koneksi, "SELECT * FROM member WHERE id_member='$id_member' AND status='on' AND user_id='$row[user_id]'") OR die(mysqli_error($koneksi));
        $rowMember = mysqli_fetch_assoc($queryMember);
        $_SESSION['id_member'] = $rowMember['id_member'];
        
        if(isset($_SESSION["proses_pesanan"])){
            unset($_SESSION["proses_pesanan"]);
            header("location: ".BASE_URL."data-pemesan.html");
        }else{    
            header("location: ".BASE_URL."index.php?page=my_profile&module=pesanan&action=list");
        }
    }
?>    