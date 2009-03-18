<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  kildestyring *********************************/
$sql = "ALTER TABLE
          ".$p."kildestyring
        ADD
          bruger_rabat        CHAR(1) DEFAULT 'n'
       ";
$dba->exec( $sql );
$status.= $p." add bruger_rabat to kildestyring <br />\n";


$dba->exec("DROP TABLE IF EXISTS ".$p."bruger_rabat");
$sql = "CREATE TABLE
          ".$p."bruger_rabat
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          kilde_id            INTEGER,
          antal_bruger        VARCHAR(200),
          pris                DECIMAL( 11,2 ) DEFAULT 0
        )";
$dba->exec( $sql );
$status.= $p." Create bruger_rabat table <br />\n";

/*
  M - admin/kildestyring/index
  M - admin/kildestyring/indstillinger
  M - admin/util/kilde.php
  M - admin/util/bruger.php
  M - view/order.php
  M - view/pricing.php
  M - view/document.php
  A - view/util.php

*/
?>
