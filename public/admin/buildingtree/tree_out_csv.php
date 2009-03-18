<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/buildingTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
//if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$tree = new buildingTree( $dba, session_id(), 'buildingelements' );
$tree->fullOpen = true;

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$newNodeName ) $newNodeName = $_POST["newNodeName"];
if( !$movingNode ) $movingNode = $_POST["movingNode"];
if( !$toggle ) $toggle = $_POST["toggle"];
if( !$add ) $add = $_GET["add"];
if( !$remove ) $remove = $_GET["remove"];
if( !$remove ) $remove = $_POST["remove"];
if( !$duplicate ) $duplicate = $_GET["duplicate"];
if( !$move ) $move = $_GET["move"];
if( !$where ) $where = $_GET["where"];
if( !$rename ) $rename = $_POST["rename"];
if( !$newname ) $newname = $_POST["newname"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];

$tree->toggle( $toggle );
$tree->add( $add );
$tree->remove( $remove );
$tree->duplicate( $duplicate );
$move   = $tree->move( $move, $where );
$rename = $tree->rename( $rename, $newname );
?>
<?php
    $nodes =  $tree->getNodeArray();
    $n = count( $nodes );
?>
<?for( $i = 0; $i < $n; $i++ ):?>
<?=$nodes[$i]["name"]."\n" ?>
<?endfor?>
