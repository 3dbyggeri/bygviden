<?php
require("../admin/util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

//$sql = "DROP TABLE ".$prefix."banner_management ";
//$dba->exec($sql);

$sql = "CREATE TABLE ".$prefix."banner_management
        (
            id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            construction_id     INTEGER,
            construction_name   TEXT,
            sold_to             INTEGER,
            sold_by             INTEGER,
            sold_period_start   DATE,
            sold_period_end     DATE,
            sold_comment        TEXT,
            reserved_to             INTEGER,
            reserved_by             INTEGER,
            reserved_period_start   DATE,
            reserved_period_end     DATE,
            reserved_comment        TEXT
        )";
$dba->exec( $sql );
echo "banner management created<br>";
//die('');

$sql = "CREATE TABLE ".$prefix."banner_salesman
        (
            id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name                TEXT,
            email               VARCHAR(200),
            password            VARCHAR(200) 
        )";
$dba->exec( $sql );
echo "banner salesman created<br>";

$sql = "INSERT INTO ".$prefix."banner_salesman (name,password) VALUES('admin','admin')";
$dba->exec( $sql );

$sql = "CREATE TABLE ".$prefix."banner_advertiser
        (
            id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name                TEXT
        )";
$dba->exec( $sql );
echo "banner advertiser created<br>";
?>
