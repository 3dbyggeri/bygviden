<?php
$url = 'http://vot.teknologisk.dk/4497?username=byg&password=si355';

$fp = @fopen( $url, "r" );

if( !$fp )
{
    die("The page you requested doesn't seam to be available");
}
$content =  fread( $fp, 100000 );
$content = str_replace('<head>','<head><base href="http://vot.teknologisk.dk">',$content);

//fill the user name
$content = str_replace('<b>Brugernavn</b><br>','',$content );
$content = str_replace('<input type=text name=Brugernavn size=40><br>',
                       '<input type="hidden" name="Brugernavn" value="byg">',$content );

//fill the password
$content = str_replace('<b>Password</b><br>','',$content );
$content = str_replace('<input type=text name=Password size=40><br>',
                       '<input type="hidden" name="Password" value="si355">',$content );
print $content;
fclose( $fp );
?>
