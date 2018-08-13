<?php

require_once './mpdf60/mpdf.php';
require_once 'php/PDF.php';

$mode = 0;


$pdf = new PDF("Some data for the logo", "", "", "", "", "", "");

$filePath = "pdftest.pdf";
$content = '<body style="margin: 0; padding: 0;"><div style="margin: 0; padding: 0;background:red;"><h1>Hello</h1><p>Vlad</p></div></body>';

$mpdf=new mPDF('c'); 
$mpdf->WriteHTML($content);

if($mode == 0){
    $mpdf->Output($filePath, "F");
}else{
    echo $content;
}
