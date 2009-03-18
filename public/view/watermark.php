<?php
/*
 // no cache headers:
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
 header("Cache-Control: no-store, no-cache, must-revalidate");
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache");

  // unique serial number:
srand(microtime()*10000);
$usnr= gmdate("Ymd-His-").rand(1000,9999).'-';
$pdf_file=$usnr.'result.pdf';
$src_file='bygviden_document.pdf';
*/
$src_file = 'bygviden_document.pdf';
$pdf = pdf_new();
pdf_open_file($pdf);
//pdf_set_parameter($pdf,'serial','if-you-have-one');
$src_doc = pdf_open_pdi($pdf,$src_file,'',0);
echo "so lang so godt...";

/*
 // fonts to embed, they are in the folder of this file:
pdf_set_parameter($pdf, 'FontAFM',     'TradeGothic=Tg______.afm');
pdf_set_parameter($pdf, 'FontOutline', 'TradeGothic=Tg______.pfb');
pdf_set_parameter($pdf, 'FontPFM',     'TradeGothic=Tg______.pfm');
$src_doc   = pdf_open_pdi($pdf,$src_file,'', 0);
$src_page  = pdf_open_pdi_page($pdf,$src_doc,1,'');
$src_width = pdf_get_pdi_value($pdf,'width' ,$src_doc,$src_page,0);
$src_height= pdf_get_pdi_value($pdf,'height',$src_doc,$src_page,0);
$numPages  = pdf_get_pdi_value($pdf,"/Root/Pages/Count",$input,0,0); 

echo "number of pages:".$numPages."<br>";
*/

/*
pdf_begin_page($pdf, $src_width, $src_height);
 {
   // place the sourcefile to the background of the actual page:
  pdf_place_pdi_page($pdf,$src_page,0,0,1,1);
  pdf_close_pdi_page($pdf,$src_page);

   // modify the page:
  pdf_set_font($pdf, 'TradeGothic', 8, 'host');
   pdf_show_xy($pdf, 'Now: '.gmdate("Y-m-d H:i:s"),50,50);
 }
*/
pdf_end_page($pdf);
pdf_close($pdf);

/*
 // prepare output:
$pdfdata = pdf_get_buffer($pdf); // to echo the pdf-data
 $pdfsize = strlen($pdfdata);     // IE requires the datasize

 // real datatype headers:
 header('Content-type: application/pdf');
header('Content-disposition: attachment; filename="'.$pdf_file.'"');
 header('Content-length: '.$pdfsize);
 echo $pdfdata;
 exit; // keep this one so no #13#10 or #32 will be written
*/
?>

