<?
require_once('tema.inc.php');
require_once('pagetema/bygningsdel.php');
session_start();        

$byg = new Bygningsdel();
$byg->branche = $_GET['branche'];
?>
<html>
    <head>
    <title>hus</title>
    <meta name="distribution" content="Global" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <link href="tema/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="tema/script.js"></script>
    <style>
        body { background-image:none; }
    </style>
    </head>
<body bgcolor="#FFFFFF">
<?=$byg->housing();?>
</body>
</html>
