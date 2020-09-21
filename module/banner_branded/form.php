<?php
       
    $bb_id = isset($_GET['bb_id']) ? $_GET['bb_id'] : "";
       
    $banner_branded = "";
    $gambar = "";
	$keterangan_gambar = "";
    $status = "";
       
    $button = "Add";
       
    if($bb_id != "")
    {
        $button = "Update";
		
        $queryBannerBranded = mysqli_query($koneksi, "SELECT * FROM banner_branded WHERE bb_id='$bb_id'");
        $row=mysqli_fetch_array($queryBannerBranded);
           
		$banner_branded = $row["banner_branded"];
		$gambar = "<img src='". BASE_URL."images/slide_bb/$row[gambar]' style='width: 200px;vertical-align: middle;' />";
		$keterangan_gambar = "(klik 'Pilih Gambar' hanya jika tidak ingin mengganti gambar)";
		$status = $row["status"];
    }   
?>

<form action="<?php echo BASE_URL."module/banner_branded/action.php?bb_id=$bb_id"?>" method="post" enctype="multipart/form-data">
	
	<div class="element-form">
		<label>Banner Branded</label>	
		<span><input type="text" name="banner_branded" value="<?php echo $banner_branded; ?>" /></span>
	</div>	

	<div class="element-form">
		<label>Gambar <?php echo $keterangan_gambar; ?></label>	
		<span><input type="file" name="file" /><?php echo $gambar; ?></span>
	</div>	  

	<div class="element-form">
		<label>Status</label>	
		<span>
			<input type="radio" value="on" name="status" <?php if($status == "on"){ echo "checked"; } ?> /> On
			<input type="radio" value="off" name="status" <?php if($status == "off"){ echo "checked"; } ?> /> Off		
		</span>
	</div>	   
	   
	<div class="element-form">
		<span><input type="submit" name="button" value="<?php echo $button; ?>" class="submit-my-profile" /></span>
	</div>	
</form>