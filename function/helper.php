<?php
    // define("BASE_URL", "http://localhost/mumtaza/mumjew/");
    define("BASE_URL", "http://localhost/My%20File/Kuliah/aplikasi-abka/mumjew/");

    $arrayStatusPesanan[1] = "Menunggu Pembayaran";
    $arrayStatusPesanan[2] = "Pembayaran Sedang Di Validasi";
    $arrayStatusPesanan[3] = "Lunas";
    $arrayStatusPesanan[4] = "Pembayaran Di Tolak";

    function rupiah($nilai = 0) {
        $string = "Rp," . number_format($nilai);
        return $string;
    }


function kategori($kategori_id = false){
    global $koneksi;

    $string = "<div id='menu-kategori'>";

        $string .= "<ul>";

                $query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE status='on'");

                while($row=mysqli_fetch_assoc($query)){
                    $kategori = strtolower($row['kategori']);
                    if($kategori_id == $row['kategori_id']) {
                        $string .= "<li><a href='".BASE_URL."$row[kategori_id]/$kategori.html' class='active'>$row[kategori]</a></li>";
                    }else{
                        $string .= "<li><a href='".BASE_URL."$row[kategori_id]/$kategori.html'>$row[kategori]</a></li>";
                    }
                }
            
        $string .= "</ul>";
    $string .="</div>";

    return $string;

} 

function admin_only($module, $level){
    if($level != "superadmin"){
        $admin_pages = array("kategori", "barang", "kota", "user", "banner","banner branded");
        if(in_array($module, $admin_pages)){
            header("location: ".BASE_URL);
        }    
    }
}


function pagination($query, $data_per_halaman, $pagination, $url){
    $total_data = mysqli_num_rows($query);
    $total_halaman = ceil($total_data / $data_per_halaman);

    $batasPosisiNomor = 6;
    $batasJumlahHalaman = 10;
    $mulaiPagination = 1;
    $batasAkhirPagination = $total_halaman;

    echo "<ul class='pagination'>";

    if($pagination > 1){
        $prev = $pagination - 1;
        echo "<li><a href='".BASE_URL."$url&pagination=$prev'><< Prev</a></li>";
    }
    
    if($total_halaman >= $batasJumlahHalaman){
        if($pagination > $batasPosisiNomor){
            $mulaiPagination = $pagination - ($batasPosisiNomor - 1);
        }

        $batasAkhirPagination = ($mulaiPagination - 1) + $batasJumlahHalaman;
        if($batasAkhirPagination > $total_halaman){
            $batasAkhirPagination = $total_halaman;
        }
    }
    for($i = $mulaiPagination; $i<=$batasAkhirPagination;$i++){
        if($pagination == $i){
            echo "<li><a class='active' href='".BASE_URL."$url&pagination=$i'>$i</a></li>";
        }else{
            echo "<li><a href='".BASE_URL."$url&pagination=$i'>$i</a></li>";
        }
    }    

    if($pagination < $total_halaman){
        $next = $pagination + 1;
        echo "<li><a href='".BASE_URL."$url&pagination=$next'>Next >></a></li>";
    }

    echo "</ul>";
}

// Fungsi Untuk Notifikasi Setelah Melakukan CRUD
function notifTransaksi($notifStatus = false, $ket = "Data") {
    
    if (!$notifStatus || $notifStatus === null) {
        return;
    }

    // Cek jika var notif pada url tersedia
    if ($notifStatus) {

        $hasil ="";
        $kondisi = array(
            "sukses_add" => "Berhasil menginput ".$ket,
            "gagal_add" => "Gagal menginput ".$ket.", ".$ket." tidak boleh sama!",
            "sukses_update" => "Berhasil memperbaharui ".$ket,
            "gagal_update" => "Gagal memperbaharui ".$ket.", ".$ket." tidak boleh sama!",
            "sukses_delete" => "Berhasil menghapus ".$ket
        );
        
        foreach ($kondisi as $key => $value) {
            // Pengkondisian untuk menampilkan tipe notif
            if ($notifStatus == $key) {
                $hasil = "<div class='notif' id='notif'>".$value."</div>";
            }
        }

    }

    return $hasil;
}

// Fungsi untuk GET Request data pada web service dengan metode curl
function curl_get($url) {
  // Create curl
  $curl = curl_init();

  // Set Curl Opt
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "key: aeec1b4683f661b22e3702de11e30fe9"
    ),
  ));

  // Eksekusi Curl
  $response = curl_exec($curl);
  // Catch Error
  $err = curl_error($curl);

  // Mentutup curl
  curl_close($curl);

  // Cek jika request Error
  if ($err) {
    $response = "cURL Error #:" . $err;
  }

  // Mengembalikan Response
  return json_decode($response, true);
}

// Fungsi untuk POST request data pada web service dengan metode curl
function curl_post($url, $params) {
  // Create curl
  $curl = curl_init();

  // Set Curl Opt
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin={$params[0]}&destination={$params[1]}&weight=1700&courier={$params[2]}",
    CURLOPT_HTTPHEADER => array(
      "content-type: application/x-www-form-urlencoded",
      "key: aeec1b4683f661b22e3702de11e30fe9"
    ),
  ));

  // Eksekusi Curl
  $response = curl_exec($curl);
  // Catch Error
  $err = curl_error($curl);

  // Mentutup curl
  curl_close($curl);

  // Cek jika request Error
  if ($err) {
    $response = "cURL Error #:" . $err;
  }

  // Mengembalikan Response
  return json_decode($response, true);
}
