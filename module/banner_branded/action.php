<?php
    include("../../function/koneksi.php");
    include("../../function/helper.php");

    $button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
    $bb_id = isset($_GET['bb_id']) ? $_GET['bb_id'] : "";
    
	$banner_branded = isset($_POST['banner_branded']) ? $_POST['banner_branded'] : false;
	$status = isset($_POST['status']) ? $_POST['status'] : false;
     
    $edit_gambar = "";
	
 
    if($_FILES["file"]["name"] != "")
    {
        $nama_file = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"], "../../images/bb-original/" . $nama_file);
         
        $edit_gambar  = ", gambar='$nama_file'";
    }
     
    if($button == "Add")
    {
        $query = mysqli_query($koneksi, "SELECT * FROM banner_branded WHERE banner_branded='$banner_branded'");
        if(mysqli_num_rows($query) == 1){
            header("location: ".BASE_URL."index.php?page=my_profile&module=banner_branded&action=form&notif=nlink&notif=gagal_add");
            die();    
        } else {
            mysqli_query($koneksi, "INSERT INTO banner_branded (banner_branded, gambar, status) VALUES ('$banner_branded', '$nama_file', '$status')")OR die(mysqli_error($koneksi));
        }
    } elseif($button == "Update"){
        mysqli_query($koneksi, "UPDATE banner_branded SET banner_branded='$banner_branded'
                                        $edit_gambar,
                                        status='$status'
                                        WHERE bb_id='$bb_id'")OR die(mysqli_error($koneksi));
        $status_notif = "sukses_update"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=banner_branded&action=list&notif=$status_notif");
        die();
    } else if($button == "Delete"){
        mysqli_query($koneksi, "DELETE FROM banner_branded WHERE bb_id='$bb_id'")OR die(mysqli_error($koneksi));
        $status_notif = "sukses_delete"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=banner_branded&action=list&notif=$status_notif");
        die();
    }
    
     
     
    header("location: ".BASE_URL."index.php?page=my_profile&module=banner_branded&action=list&notif=sukses_add");
?>