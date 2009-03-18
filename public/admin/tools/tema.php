<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  TEMA  *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."tema");
$sql = "CREATE TABLE
          ".$p."tema
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(255),
          resume              TEXT,
          forside_name        VARCHAR(255),
          forside_resume      TEXT,
          icon                VARCHAR(255),
          created             DATETIME,
          edited              DATETIME,
          private             CHAR(1) DEFAULT 'n',
          publish             CHAR(1) DEFAULT 'n',
          editor_id           INTEGER
      )";
$dba->exec( $sql );
$status= $p."tema table created <br />\n";

/*****************************  TEMA_EDITOR *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."tema_editor");
$sql = "CREATE TABLE
          ".$p."tema_editor
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          titel               VARCHAR(255),
          name                VARCHAR(255),
          email               VARCHAR(255),
          resume              TEXT,
          photo               VARCHAR(255)
        )";
$dba->exec( $sql );
$status.= $p."tema_editor table created <br />\n";


/*****************************  TEMASIDER  *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."tema_page");
$sql = "CREATE TABLE
          ".$p."tema_page
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          tema_id             INTEGER,
          name                VARCHAR(255),
          body                TEXT,
          created             DATETIME,
          edited              DATETIME,
          position            INTEGER
        )";
$dba->exec( $sql );
$status.= $p."tema_page table created <br />\n";

/*****************************  TEMA_KILDE *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."tema_kilde");
$sql = "CREATE TABLE
          ".$p."tema_kilde
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          tema_id             INTEGER,
          name                VARCHAR(255),
          kilde_id            INTEGER,
          url                 VARCHAR(255),
          comment             TEXT,
          is_bibliotek        CHAR(1) DEFAULT 'n',
          position            INTEGER
        )";
$dba->exec( $sql );
$status.= $p."tema_kilde table created <br />\n";


/*****************************  TEMA2BUILDING *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."tema_bygning");
$sql = "CREATE TABLE
          ".$p."tema_bygning
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          tema_id             INTEGER,
          node_id             INTEGER,
          position            INTEGER
        )";
$dba->exec( $sql );
$status.= $p."tema_bygning table created <br />\n";

/*****************************  PARAGRAPH *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."paragraph");
$sql = "CREATE TABLE
          ".$p."paragraph
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(255),
          body                TEXT
         )";
$dba->exec( $sql );
$status= $p."paragraph table created <br />\n";

/**************************** UPDATE USER TABLE *************************/
$dba->exec("ALTER TABLE ".$p."bruger ADD temaeditor CHAR(1) DEFAULT 'n'");

echo $status
?>
