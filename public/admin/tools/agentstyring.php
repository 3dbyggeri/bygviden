<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  kildestyring *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."agentstyring");
$sql = "CREATE TABLE
          ".$p."agentstyring
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'new element',
          parent              INTEGER,
          position            INTEGER,
          description         TEXT,
          start_text          TEXT,
          created             DATETIME,
          creator             INTEGER,
          edited              DATETIME,
          threshold           INTEGER DEFAULT 70,
          results             INTEGER DEFAULT 5,
          autonomy            CHAR(1) DEFAULT 'n',
          cache               TEXT
      )";
$dba->exec( $sql );
$status.= $p."agentstyring table created <br />\n";

/*****************************  agentstyring status *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."agentstyring_state");
$sql = "CREATE TABLE
            ".$p."agentstyring_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."agentstyring_status table created<br />\n";

          
//now dump some data into the kildestyring tree
//insert the root into the tree
$sql = "INSERT INTO
            ".$prefix."agentstyring
        (
            name,
            parent,
            created,
            creator
        )
        VALUES
        (
            'Agenter',
            0,
            NOW(),
            1
        )";
$dba->exec( $sql );
?>
