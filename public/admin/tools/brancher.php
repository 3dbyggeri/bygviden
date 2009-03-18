<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  producent *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."brancher");
$sql = "CREATE TABLE
          ".$p."brancher
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'Ny branche',
          label               VARCHAR(200) DEFAULT 'Ny branche',
          timepublish         DATETIME DEFAULT NULL,
          timeunpublish       DATETIME DEFAULT NULL
      )";
$dba->exec( $sql );
$status.= $p."branche table created <br />\n";

$sql = "INSERT INTO ". $p."brancher ( label, name ) VALUES( 'Generalt','general' )";
$dba->exec( $sql );
$sql = "INSERT INTO ". $p."brancher ( label, name ) VALUES( 'Træ','trae' )";
$dba->exec( $sql );
$sql = "INSERT INTO ". $p."brancher ( label, name ) VALUES( 'Mur','mur' )";
$dba->exec( $sql );
$sql = "INSERT INTO ". $p."brancher ( label, name ) VALUES( 'Beton','beton' )";
$dba->exec( $sql );
$sql = "INSERT INTO ". $p."brancher ( label, name ) VALUES( 'Maling','maling' )";
$dba->exec( $sql );

/*****************************  generaltree *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."general");
$sql = "CREATE TABLE
            ".$p."general
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'Ingen referance',
          parent              INTEGER,
          element_id          INTEGER,
          position            INTEGER
        )";

$dba->exec( $sql );
$status.= $p."generaltree table created<br />\n";

$sql = "INSERT INTO ". $p ."general ( name, parent ) VALUES( 'Generalt',0 )";
$dba->exec( $sql );

/*****************************  generaltree_status *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."general_state");
$sql = "CREATE TABLE
            ".$p."general_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."general_state table created<br />\n";

/*****************************  traetree *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."trae");
$sql = "CREATE TABLE
            ".$p."trae
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'Ingen referance',
          parent              INTEGER,
          element_id          INTEGER,
          position            INTEGER
        )";

$dba->exec( $sql );
$status.= $p."trae table created<br />\n";

$sql = "INSERT INTO ". $p ."trae ( name, parent ) VALUES( 'Træ',0 )";
$dba->exec( $sql );

/*****************************  traetree_state *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."trae_state");
$sql = "CREATE TABLE
            ".$p."trae_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."trae_state table created<br />\n";

/*****************************  murtree *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."mur");
$sql = "CREATE TABLE
            ".$p."mur
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'Ingen referance',
          parent              INTEGER,
          element_id          INTEGER,
          position            INTEGER
        )";

$dba->exec( $sql );
$status.= $p."mur table created<br />\n";

$sql = "INSERT INTO ". $p ."mur ( name, parent ) VALUES( 'Mur',0 )";
$dba->exec( $sql );

/*****************************  murtree_state *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."mur_state");
$sql = "CREATE TABLE
            ".$p."mur_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."mur_state table created<br />\n";

/*****************************  betontree *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."beton");
$sql = "CREATE TABLE
            ".$p."beton
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'Ingen referance',
          parent              INTEGER,
          element_id          INTEGER,
          position            INTEGER
        )";

$dba->exec( $sql );
$status.= $p."beton table created<br />\n";

$sql = "INSERT INTO ". $p ."beton ( name, parent ) VALUES( 'Beton',0 )";
$dba->exec( $sql );

/*****************************  murtree_state *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."beton_state");
$sql = "CREATE TABLE
            ".$p."beton_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."beton_state table created<br />\n";

/*****************************  malingtree *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."maling");
$sql = "CREATE TABLE
            ".$p."maling
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'Ingen referance',
          parent              INTEGER,
          element_id          INTEGER,
          position            INTEGER
        )";

$dba->exec( $sql );
$status.= $p."maling table created<br />\n";

$sql = "INSERT INTO ". $p ."maling ( name, parent ) VALUES( 'Maling',0 )";
$dba->exec( $sql );

/***************************  malingtree_state *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."maling_state");
$sql = "CREATE TABLE
            ".$p."maling_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."maling_state table created<br />\n";

/***************************  branche2category *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."branche2category");
$sql = "CREATE TABLE
            ".$p."branche2category
        (
            branche_name       VARCHAR(200),
            category_id        INTEGER,
            branche_element_id INTEGER
        )";

$dba->exec( $sql );
$status.= $p."branche2category table created<br />\n";
?>
