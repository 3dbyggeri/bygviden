<?
$url = "http://localhost:8080/watermarker/Watermarker";
$params.= "stream=yes";
//$params.= "&file_name=/usr/local/tomcat/webapps/watermarker/pdf_samples/bygviden_dokument.pdf";
$params.= "&file_name=/home/autonomy/jakarta-tomcat-4.1.31/webapps/watermarker/pdf_samples/bygviden_dokument.pdf";
$params.= "&watermark_text=Hentet fra www.bygviden.dk af ";
$params.= "&user_name=Ronald Xavier";
$params.= "&new_name=test.pdf";

$ch = curl_init (); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt ($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_PORT,8080);
curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
curl_setopt ($ch, CURLOPT_URL, $url); 
curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
$content = curl_exec ($ch); 
curl_close ($ch); 
header("Content-type: application/pdf");
header('Content-Disposition: inline; filename="document.pdf"');
echo $content;
?>
