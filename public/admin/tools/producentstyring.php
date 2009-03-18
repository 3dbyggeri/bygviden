<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  producent *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."producent");
$sql = "CREATE TABLE
          ".$p."producent
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'ny producent',
          description         TEXT,
          observation         TEXT,
          timepublish         DATETIME DEFAULT NULL,
          timeunpublish       DATETIME DEFAULT NULL,
          kilde_url           VARCHAR(200),
          logo_url            VARCHAR(200),
          adresse             TEXT,
          CVR                 VARCHAR(20),
          telefon             VARCHAR(20),
          fax                 VARCHAR(20),
          mail                VARCHAR(200),
          data_open           CHAR(1) DEFAULT 'y',
          publish_open        CHAR(1) DEFAULT 'y',
          product_open        CHAR(1) DEFAULT 'y'
      )";
$dba->exec( $sql );
$status.= $p."producent table created <br />\n";

/*****************************  product *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."product");
$sql = "CREATE TABLE
            ".$p."product
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          producent           INTEGER,
          name                VARCHAR(200) DEFAULT 'Nyt produkt',
          description         TEXT,
          observation         TEXT,
          kilde_url           VARCHAR(200),
          logo_url            VARCHAR(200),
          timepublish         DATETIME,
          timeunpublish       DATETIME,
          data_open           CHAR(1) DEFAULT 'y',
          publish_open        CHAR(1) DEFAULT 'y'
        )";

$dba->exec( $sql );
$status.= $p."product table created<br />\n";
?>
