<?php 
    require("../util/dba.php");

    session_start();
    $dba  = new dba();
    $p =  $dba->getPrefix();

    $dba->exec("DROP TABLE IF EXISTS ".$p."subscriber");
    $sql = "CREATE TABLE
            ".$p."subscriber
            (
                id  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(255),
                active CHAR(1) DEFAULT 'y',
                subscribed DATETIME,
                icontact_id VARCHAR(255)
            )
           "; 
    $dba->exec($sql);
    echo 'subscriber table created<br>';

    $dba->exec("DROP TABLE IF EXISTS ".$p."newsletter");
    $sql = "CREATE TABLE
            ".$p."newsletter
            (
                id  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255),
                created DATETIME,
                mailed DATETIME,
                numberofrecipients INT
            )";

    $dba->exec($sql);
    echo 'newsletter table created<br>';


    $dba->exec("DROP TABLE IF EXISTS ".$p."newsletterparagraph");
    $sql = "CREATE TABLE
            ".$p."newsletterparagraph
            (
                id  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                created DATETIME,
                body TEXT,
                reference INT,
                referencetype CHAR(1) DEFAULT 'T'
            )";
    //referencetype can be T(ext), N(ews) or P(rofile)
    $dba->exec($sql);
    echo 'newsletterparagraph table created<br>';
    
    $dba->exec("DROP TABLE IF EXISTS ".$p."newsletter2paragraph");
    $sql = "CREATE TABLE
            ".$p."newsletter2paragraph
            (
                newsletter INT,
                paragraph INT,
                position INT,
                col INT
            )";
    $dba->exec($sql);
    echo 'newsletter2paragraph table created<br>';

    $dba->exec("DROP TABLE IF EXISTS ".$p."advertiser");


    $dba->exec("DROP TABLE IF EXISTS ".$p."adnews");
    $sql = "CREATE TABLE
            ".$p."adnews
            (
                id  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(200),
                producent INT,
                body TEXT,
                published CHAR(1) DEFAULT 'y',
                website VARCHAR(200),
                image VARCHAR(200),
                created DATETIME
            )";
    $dba->exec($sql);
    echo 'adproduct table created<br>';

    $dba->exec("ALTER TABLE ".$p."producer ADD admin_name VARCHAR(200)");
    $dba->exec("ALTER TABLE ".$p."producer ADD admin_telefon VARCHAR(200)");
    $dba->exec("ALTER TABLE ".$p."producer ADD advertise_signup DATETIME");
    $dba->exec("ALTER TABLE ".$p."producer ADD advertise_deal VARCHAR(200) DEFAULT 'none'");
    echo 'producent table edited<br>';
?>
