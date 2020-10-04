<?php 
// include autoloader
require_once '../../libs/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

// Style Laporan
$style = '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">';
// Header laporan default
$isiLaporan = '<center><h1>Laporan Penjualan</h1></center><br><hr></br>';
// Konten laporan
$isiLaporan .= 'Hello World';

$dompdf->loadHtml($isiLaporan);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('laporan_penjualan.pdf', ['Attachment' => 0]);

?>