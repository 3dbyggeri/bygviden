<?
$path = '/home/autonomy/autonomy/httpfetch/';

if($_GET['clear'])
{
    $log = $path . 'spider_'.$_GET['clear'] .'.log';
    $status = $path . 'spider_'.$_GET['clear'] .'.status';
    $idx = $path . 'spider_'.$_GET['clear'] .'.idx';
    $cache = $path . 'spider_'.$_GET['clear'];
    
    $msg = '';
    if( file_exists( $log) ) 
    {
        unlink($log);
        $msg ='<li>'.$log.'</li>';
    }
    if( file_exists( $status ) ) 
    {
        unlink($status);
        $msg ='<li>'.$status.'</li>';
    }
    if( file_exists( $idx ) ) 
    {
        unlink($idx);
        $msg ='<li>'.$idx.'</li>';
    }

    if( file_exists($cache) )
    {
        $last_line = system($path.'/clearchache.py spider_'.$_GET['clear'], $retval);
        /*
        $d = dir($cache); 
        while($entry = $d->read()) 
        { 
            if ($entry!= "." && $entry!= "..")  unlink($cache.'/'.$entry); 
        } 
        $d->close(); 
        rmdir($cache); 
        */
        $msg ='<li>'.$last_line.' - '.$retval.'</li>';
    }

    die('Spider cache cleared:<ul>'.$msg.'</ul>');
}
if( !$_GET['id'] ) die('Missing parameter:ID');

//build the path
$log = $path . 'spider_'.$_GET['id'] .'.log';
$status = $path . 'spider_'.$_GET['id'] .'.status';
$idx = $path . 'spider_'.$_GET['id'] .'.idx';

if($_GET['id']=='fetch') $log = $path .'HTTPFetch.log';
if($_GET['id']=='spider') $log = $path .'HTTPFetchspider.log';

//check if there is log file
if( !file_exists( $log) ) die('No log file available:'. $log );
$fp = fopen( $log,'r' );
echo "<xmp>";
fpassthru($fp);
echo "</xmp>";
?>
