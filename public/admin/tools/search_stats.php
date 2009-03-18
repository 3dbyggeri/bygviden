<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  search_stats *********************************/
$sql = "CREATE TABLE
          ".$p."search_stats
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          requested           DATETIME, 
          results_count       INTEGER,
          query               TEXT
      )";
$dba->exec( $sql );
$status.= $p."search_stats table created <br />\n";

/*****************************  search_branche_stats *********************************/
$sql = "CREATE TABLE
            ".$p."search_branche_stats
        (
          search_id           INTEGER,
          branche             VARCHAR(250) 
        )";

$dba->exec( $sql );
$status.= $p."search_branche_stats table created<br />\n";

/*****************************  search_category_stats *********************************/
$sql = "CREATE TABLE
            ".$p."search_category_stats
        (
            search_id          INTEGER,
            category           VARCHAR(250)
        )";

$dba->exec( $sql );
$status.= $p."search_branche_stats table created<br />\n";
?>
