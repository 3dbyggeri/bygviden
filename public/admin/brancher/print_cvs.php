<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/brancheTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$branche = $_GET['branche'];
if( !$branche ) $branche = $_POST['branche'];
if( !$branche ) $branche = 'general';

$tree = new brancheTree( $dba, session_id(), $branche );
$tree->fullOpen = true;
?>
<?php
    $nodes =  $tree->getNodeArray();
    $n = count( $nodes );
?>
<?for( $i = 0; $i < $n; $i++ ):?>
<?=$nodes[$i]["name"]."\n" ?>
<?endfor?>
