<?php
    include("../../function/koneksi.php");
    include("../../function/helper.php");

    $button = isset($_POST['button']) ? $_POST['button'] : $_GET['button'];
    $no_member = isset($_GET['no_member']) ? $_GET['no_member'] : false;

    $id_member = isset($_POST['id_member']) ? $_POST['id_member'] : false;
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : false;
	$status = isset($_POST['status']) ? $_POST['status'] : false;
     
    if($button == "Add")
    {
        $query = mysqli_query($koneksi, "SELECT * FROM member WHERE user_id='$user_id'");
        if(mysqli_num_rows($query) == 1){
            header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=form&notif=nlink&notif=gagal_add");
            die();    
        } else {
            mysqli_query($koneksi, "INSERT INTO member (id_member, user_id, status) VALUES ('$id_member', '$user_id', '$status')")OR die(mysqli_error($koneksi));
        }
    } elseif($button == "Update"){
        mysqli_query($koneksi, "UPDATE member SET id_member='$id_member',
                                        user_id='$user_id',
                                        status='$status'
                                        WHERE no_member='$no_member'")OR die(mysqli_error($koneksi));
        $status_notif = "sukses_update"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=list&notif=$status_notif");
        die();
    } else if($button == "Delete"){
        mysqli_query($koneksi, "DELETE FROM member WHERE no_member='$no_member'")OR die(mysqli_error($koneksi));
        $status_notif = "sukses_delete"; // Status Notif Handle
        header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=list&notif=$status_notif");
        die();
    }
    
     
     
    header("location: ".BASE_URL."index.php?page=my_profile&module=member&action=list&notif=sukses_add");
?>