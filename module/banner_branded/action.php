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
        move_uploaded_file($_FILES["file"]["tmp_name"], "../../images/bb/" . $nama_file);
         
        $edit_gambar  = ", gambar='$nama_file'";
    }
     
    if($button == "Add")
    {
        mysqli_query($koneksi, "INSERT INTO banner_branded (banner, link, gambar, status) VALUES ('$banner_branded', '$nama_file', '$status')");
    }
    elseif($button == "Update"){
        mysqli_query($koneksi, "UPDATE banner_branded SET banner_branded='$banner_branded',
                                        $edit_gambar
                                        status='$status'
										$edit_gambar WHERE bb_id='$bb_id'");
    }
    else if($button == "Delete"){
		mysqli_query($koneksi, "DELETE FROM banner_branded WHERE bb_id='$bb_id'");
	}
     
     
    header("location: ".BASE_URL."index.php?page=my_profile&module=banner_branded&action=list");
?>