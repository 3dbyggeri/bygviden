<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/buildingTree.php" );
include_once('../util/brancheTree.php');
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
//if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
#$tree = new buildingTree( $dba, session_id(), 'buildingelements' );
$tree = new brancheTree( $dba,session_id(),'general');
$tree->fullOpen = true;

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];


?>
<?php

    $nodes =  $tree->getNodeArray();
    $n = count( $nodes );
?>
<?for( $i = 0; $i < $n; $i++ ):?>
    <?

      $link = 'http://www.bygviden.dk/index.php?action=bygningsdel&element='.$nodes[$i]['element_id'];
      $link.= '&node='.$nodes[$i]['id'];
      $link.= '&level='.$nodes[$i]['level'];
      $link.= '&toggle='.$nodes[$i]['id'];
    ?>
"<?=$nodes[$i]["name"]?>","<?=$link?>"<?="\n"?>
<?endfor?>
