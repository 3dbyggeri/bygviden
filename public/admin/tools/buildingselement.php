<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  buildingelements *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."buildingelements");
$sql = "CREATE TABLE
          ".$p."buildingelements
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'new element',
          parent              INTEGER,
          position            INTEGER,
          goderaad            TEXT,
          created             DATETIME,
          creator             INTEGER,
          edited              DATETIME
      )";
$dba->exec( $sql );
$status.= $p."buildingelements table created <br />\n";

/*****************************  buildingelements_state *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."buildingelements_state");
$sql = "CREATE TABLE
            ".$p."buildingelements_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."buildingelements_state table created<br />\n";

          
//now dump some data into the kildestyring tree
//insert the root into the tree
$sql = "INSERT INTO
            ".$prefix."buildingelements
        (
            name,
            parent,
            created,
            creator
        )
        VALUES
        (
            'Bygningsdele',
            0,
            NOW(),
            1
        )";
$dba->exec( $sql );

/*****************************  produkt2element *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."produkt2element");
$sql = "CREATE TABLE
            ".$p."produkt2element
        (
            produkt            INTEGER,
            element            INTEGER,
            category           INTEGER
        )";

$dba->exec( $sql );
$status.= $p."produkt2element table created<br />\n";

/*****************************  kilde2element *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."kilde2element");
$sql = "CREATE TABLE
            ".$p."kilde2element
        (
            kilde              INTEGER,
            element            INTEGER,
            category           INTEGER
        )";

$dba->exec( $sql );
$status.= $p."kilde2element table created<br />\n";

/*****************************  agent2element *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."agent2element");
$sql = "CREATE TABLE
            ".$p."agent2element
        (
            agent              INTEGER,
            element            INTEGER,
            category           INTEGER
        )";

$dba->exec( $sql );
$status.= $p."agent2element table created<br />\n";

/******************************* categories *****************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."categori");
$sql = "CREATE TABLE
            ".$p."categori
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'new element',
          position            INTEGER
        )";
$dba->exec( $sql );
$status.= $p."categori table created<br />\n";

/******************************** categori2element ****************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."categori2element");
$sql = "CREATE TABLE
            ".$p."categori2element
        (
            categori           INTEGER,
            element            INTEGER,
            open               CHAR(1) DEFAULT 'y'
        )";

$dba->exec( $sql );
$status.= $p."agent2element table created<br />\n";
?>
