<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  kildestyring *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."blog");
$sql = "CREATE TABLE
          ".$p."blog
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          title               VARCHAR(255),
          post                TEXT,
          created             DATETIME,
          edited              DATETIME,
          publish             CHAR(1) DEFAULT 'n'
      )";
$dba->exec( $sql );
$status= $p."blog table created <br />\n";

echo $status
?>
