<?php

    session_start();

    include_once("function/koneksi.php");
    include_once("function/helper.php");


    $page = isset($_GET['page']) ? $_GET['page'] : false; 
    $kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : false; 

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : false;
    $nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : false;
    $level = isset($_SESSION['level']) ? $_SESSION['level'] : false;
    $keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : array();
    $totalBarang = count($keranjang);

    // Menarik data API
    $list_provinsi = curl_get('https://api.rajaongkir.com/starter/province');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Mumtaza Jewerly | Toko Emas</title>

    <link href="<?php echo BASE_URL."css/fontawesome-free-5.13.1-web/css/all.min.css"; ?>" type="text/css" rel="stylesheet" />
    <link href="<?php echo BASE_URL."css/style.css"; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo BASE_URL."css/banner.css"; ?>" type="text/css" rel="stylesheet" />
		
		<script src="<?php echo BASE_URL."js/jquery-3.1.1.min.js"; ?>"></script>
		<script src="<?php echo BASE_URL."js/Slides-SlidesJS-3/source/jquery.slides.min.js"; ?>"></script>
		<script src="<?php echo BASE_URL."js/script.js"; ?>"></script>
		
		<script>
		$(function() {
			$('#slides').slidesjs({
				height: 350,
				play: { auto : true,
					    interval : 3000
					  },
				navigation : false
			});
		});
		</script>		

</head>
<body>
    
    <div id="container">
        <div id="header">
            <a href="<?php echo BASE_URL."index.php"; ?>">
                <img src="<?php echo BASE_URL."images/logo.png"; ?>"/>
            </a>

            <div id="menu">
                <div id="user">
                    <?php
                        if($user_id){
                            echo "Hi <b>$nama</b>, 
                                  <a href='".BASE_URL."index.php?page=my_profile&module=pesanan&action=list'>My Profile</a>
                                  <a href='".BASE_URL."logout.php'>Logout</a>";
                        }else{
                            echo "<a href='".BASE_URL."login.html'>Login</a>
                                  <a href='".BASE_URL."register.html'>Register</a>";
                        }
                    ?>
                    
                </div>
                <a href="<?php echo BASE_URL."keranjang.html"; ?>" id="button-keranjang">
                    <img src="<?php echo BASE_URL."images/cart.png"; ?>"/>
                    <?php
                        if($totalBarang != 0){
                            echo "<span class='total-barang'>$totalBarang</span>";
                        }
                    ?>
                </a>
            </div>        
        </div>

        <div id="content">
            <?php
                $filename = "$page.php";

                if(file_exists($filename)){
                    include_once($filename);
                }else{
                    include_once("main.php");
                }
            ?>
        </div>
        
        <div id="datacont">

        </div>
        
        <div id="footer">
            <p>Mumtaza Jewerly by Abka Zailani</p>
        </div>

    </div>

    <!-- library sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <?php
        if (isset($_GET['add_status'])) {
            echo "
            <script>
            $(document).ready(function(){
                swal({
                  text: 'Berhasil memasukan barang ke keranjang',
                  button: 'Oke'
                })
                window.history.replaceState({}, document.title, '/' + 'index.php')
            })
            </script>";
        }
    ?>

<!-- JS Manual -->

<script>

    // Menghilangkan Notif dalam interval waktu tertentu
    $('#notif').delay(3000).fadeOut(300);

    // Sub Func untuk menarik list kota berdasarkan provinsi tujuan
    function get_list_kota(id_provinsi) {
      const xhr = new XMLHttpRequest();
      var params = `?id_province=${id_provinsi}`;
      var res;

      // Response Blok - Get City List
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Clear <Option> from Data List of data-kota
            document.querySelector('#data-kota').innerHTML = '';

            // Deploy Response
            for (key in res.data.rajaongkir.results) {
              // Create  Element Data List City by Province
              var datalist_kota = document.querySelector('#data-kota');
              var opt = document.createElement("OPTION");
              opt.setAttribute("value", res.data.rajaongkir.results[key]['city_name']);
              datalist_kota.appendChild(opt);
            }
          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // debugg
          console.log(
            res.data.rajaongkir.results[key]['city_id'],
            res.data.rajaongkir.results[key]['city_name']
          );
        }
      };

      // Send Request
      xhr.open("GET", "request/get_list_kota_by_provinsi.php" + params, true);
      xhr.send();
    }

    // Func untuk update list kota tujuan
    function update_list_kota(nama_provinsi_tujuan) {
      alert("Menjalankan Fungsi Update List Kota! Tunggu beberapa detik sampai list pada input kota terupdate...");
      const xhr = new XMLHttpRequest();
      var res;
      var id_provinsi_tujuan;

      // Response Blok
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Deploy Response
            for (key in res.data.rajaongkir.results) {
              // Get Province ID by given Province NAME
              if (res.data.rajaongkir.results[key]['province'] == nama_provinsi_tujuan) {
                // Get Province ID
                id_provinsi_tujuan = res.data.rajaongkir.results[key]['province_id'];
              }
            }

            // Get List City by Province ID
            if (id_provinsi_tujuan) {
              // Jalankan Fungsi
              get_list_kota(id_provinsi_tujuan);
            }
          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // Debug
          // console.log(
          //   res.data.rajaongkir.results[key]['province_id'],
          //   res.data.rajaongkir.results[key]['province']
          // );
        }
      };

      // Send Request
      xhr.open("GET", "request/get_list_provinsi.php", true);
      xhr.send();
    }

    // Sub Func untuk mengecek jangkauan Kurir Toko Mumtaza
    function cek_jangkauan_kurir_toko(kota_tujuan, metode_pembayaran) {
      const xhr = new XMLHttpRequest();
      const params = `?kota_tujuan=${kota_tujuan}`;
      var res;

      // Response Blok
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Persiapan Parent
            var parent_kurir = document.querySelector('#daftar_metode_pengiriman');

            // Response
            if (res.data != '') {
              if (metode_pembayaran == 'cod') {
                if (parent_kurir.querySelectorAll('.kurir_tambahan')) {
                  var opt_grp = parent_kurir.querySelectorAll(".kurir_tambahan");
                  opt_grp.forEach(function(el) {
                    el.remove();
                  });
                }
              }
              // Info Kurir Mumtaza
              alert(res.message);
            } else {
              if (metode_pembayaran == 'tf') {
                if (parent_kurir.querySelector('#kurir_mumtaza')) {
                  var kurir_opt = document.querySelector("#kurir_mumtaza");
                  kurir_opt.parentNode.removeChild(kurir_opt);
                }
              } else if (metode_pembayaran == 'cod') {
                // Membersihkan Opsi Metode Pengiriman
                if (parent_kurir.querySelector('#kurir_mumtaza')) {
                  // Hapus Semua Opsi Metode Pengiriman
                  parent_kurir.innerHTML = '';

                  // Membuat Option Default
                  var new_opt = document.createElement("OPTION");
                  new_opt.innerHTML = '-Pilih-';
                  parent_kurir.appendChild(new_opt);
                }
              }

              // Info Kurir Mumtaza
              alert(res.message);
            }
          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // Debug
          console.log(res);
        }
      };

      // Send Request
      xhr.open("GET", "request/cek_jangkauan_kurir_mumtaza.php" + params, true);
      xhr.send();
    }

    // Func untuk mengecek ongkir berdasarkan kota asal & tujuan
    function cek_ongkir(metode_pembayaran) {
      alert("Menjalankan Fungsi Cek Ongkir! Tunggu beberapa detik sampai list Metode Pengiriman terupdate...");
      const xhr = new XMLHttpRequest();
      var kota_tujuan = document.querySelector('#input_kota').value ? document.querySelector('#input_kota').value : '';
      const params = `?kota_tujuan=${kota_tujuan}`;
      var res;

      // Response Blok
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Convert Json String to JSON
          res = JSON.parse(this.responseText);

          // Response Handler
          if (res.status) {
            // Persiapan Parent
            var parent_kurir = document.querySelector('#daftar_metode_pengiriman');

            // Bersihkan Option Metode Pengiriman
            if (parent_kurir.querySelector('#kurir_mumtaza')) {
              var kurir_opt = document.querySelector("#kurir_mumtaza");
              kurir_opt.parentNode.removeChild(kurir_opt);
            }
            if (parent_kurir.querySelectorAll('.kurir_tambahan')) {
              var opt_grp = parent_kurir.querySelectorAll(".kurir_tambahan");
              opt_grp.forEach(function(el) {
                el.remove();
              });
            }

            // Membuat Option Group Default
            var new_opt_group = document.createElement("OPTGROUP");
            new_opt_group.setAttribute("label", "MUMTAZA");
            new_opt_group.setAttribute("id", "kurir_mumtaza");
            // Membuat Daftar Paket Kurir
            var new_opt = document.createElement("OPTION");
            new_opt.setAttribute("value", 'mumtaza_express_0');
            new_opt.innerHTML = `Paket Express (Free)`;
            new_opt_group.appendChild(new_opt);
            // Menambahkan Opt Group ke Parent
            parent_kurir.appendChild(new_opt_group);

            // Deploy Response for JNE
            if (res.data.jne) {
              // Membuat Option Group
              var new_opt_group = document.createElement("OPTGROUP");
              new_opt_group.setAttribute("label", "JNE");
              new_opt_group.setAttribute("class", "kurir_tambahan");

              // Membuat Daftar Paket Kurir
              for (key in res.data.jne) {
                var new_opt = document.createElement("OPTION");
                new_opt.setAttribute("value", `jne_${res.data.jne[key]['service']}_${res.data.jne[key].cost[0].value}`);
                new_opt.innerHTML = `Paket ${res.data.jne[key]['service']} (${res.data.jne[key].cost[0].value})`;
                new_opt_group.appendChild(new_opt);
              }

              // Menambahkan Opt Group ke Parent
              parent_kurir.appendChild(new_opt_group);
            }

            // Deploy Response for TIKI
            if (res.data.tiki) {
              // Membuat Option Group
              var new_opt_group = document.createElement("OPTGROUP");
              new_opt_group.setAttribute("label", "TIKI");
              new_opt_group.setAttribute("class", "kurir_tambahan");

              // Membuat Daftar Paket Kurir
              for (key in res.data.tiki) {
                var new_opt = document.createElement("OPTION");
                new_opt.setAttribute("value", `tiki_${res.data.tiki[key]['service']}_${res.data.tiki[key].cost[0].value}`);
                new_opt.innerHTML = `Paket ${res.data.tiki[key]['service']} (${res.data.tiki[key].cost[0].value})`;
                new_opt_group.appendChild(new_opt);
              }

              // Menambahkan Opt Group ke Parent
              parent_kurir.appendChild(new_opt_group);
            }

            // Cek Metode Pembayaran
            if (metode_pembayaran == 'tf') {
              // Cek Jangkauan Kurir Mumtaza
              cek_jangkauan_kurir_toko(kota_tujuan, metode_pembayaran);
            } else if (metode_pembayaran == 'cod') {
              // Cek Jangkauan Kurir Mumtaza
              cek_jangkauan_kurir_toko(kota_tujuan, metode_pembayaran);
            }

          } else {
            // Error Response
            alert(`Terjadi kesalahan pada Server ${res.message}`);
          }

          // Debug
          console.log(res);
        }
      };

      // Send Request
      xhr.open("GET", "request/get_ongkir.php" + params, true);
      xhr.send();
    }

</script>

</body>
</html>