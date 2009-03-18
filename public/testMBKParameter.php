<? 
function getToken()
{
  $time = time();
  session_start();
  $s_id = session_id();
  $sum = 0;
  for($i=0;$i<strlen($s_id);$i++) $sum+= ord($s_id{$i});
  $time_sum = $sum + time();

  return $sum.'T'.$s_id.'I'.$time_sum;
}
$token = getToken();
?>
<a href="http://www.teknologisk.dk/byggeri/mbk/katalog/online/byg/ved/V4167B.htm?bygviden=<?=$token?>">http://www.teknologisk.dk/byggeri/mbk/katalog/online/byg/ved/V4167B.htm?bygviden=<?=$token?></a>
