<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  producent *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."branche_relevans");
$sql = "CREATE TABLE
          ".$p."branche_relevans
        (
          publikations_id     INTEGER,
          branche_id          INTEGER
        )"; 
$dba->exec( $sql );
$status.= $p."branche_relevans table created <br />\n";

echo  $status;
?>
