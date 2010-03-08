<?php
  require("../util/dba.php");
  $dba = new dba();
  $p = $dba->getPrefix();

  /*****************************  bruger *********************************/
  $sql= "DROP TABLE IF EXISTS ".$p."bruger";
  $dba->exec( $sql );

  $sql = "CREATE TABLE 
              ".$p."bruger
          (
              id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
              bruger_navn         VARCHAR(200),
              medlemsnr           INTEGER,
              parent              INTEGER      DEFAULT 0,
              active              CHAR(1)      DEFAULT 'y',
              gratist             CHAR(1)      DEFAULT 'n',
              sessid              VARCHAR(200),
              sesstart            TIMESTAMP,
              password            VARCHAR(50),
              firmanavn1          VARCHAR(50),
              firmanavn2          VARCHAR(50),
              titel          VARCHAR(50),
              gade                VARCHAR(50),
              sted                VARCHAR(50),
              postnr              VARCHAR(10),
              city                VARCHAR(50),
              land                VARCHAR(50),
              medlem              CHAR(1)      DEFAULT 'y',
              email               VARCHAR(50),
              restricted_shop     CHAR(1)      DEFAULT 'y',
              clipkort_amount     DECIMAL(10,0)
          )";

  $dba->exec( $sql );

  $status = $p."bruger table created<br>";

  /*************************  USAGE *****************************/
  $sql = "DROP TABLE IF EXISTS ".$p."usage ";
  $dba->exec( $sql );

  $sql = "CREATE TABLE
              ".$p."usage
          (
              bruger_id           INTEGER,
              publication_id      INTEGER,
              url                 VARCHAR(200),
              title               VARCHAR(200),
              pris                DECIMAL( 11,2 ) DEFAULT 0,
              abonament_periode   INTEGER,
              readed              DATETIME,
              archived            DATETIME
          )";

  $dba->exec( $sql );
  $status.=$p."usage table created<br>";

?>
