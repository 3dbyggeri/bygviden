<?php
require_once("../util/dba.php");
$dba = new dba();
$prefix = $p =  $dba->getPrefix();

/*****************************  kildestyring *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."kildestyring ");
$sql = "CREATE TABLE
          ".$p."kildestyring
        (
          id                  INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
          name                VARCHAR(200) DEFAULT 'new element',
          parent              INTEGER,
          position            INTEGER,
          description         TEXT,
          created             DATETIME,
          creator             INTEGER,
          edited              DATETIME,
          element_type        ENUM('root','leverandor','kategori','publikation','folder'),
          kilde_url           VARCHAR(200),
          forlag_url          VARCHAR(200),
          logo_url            VARCHAR(200),
          publish             CHAR(1) DEFAULT 'y',
          timepublish         DATETIME,
          timeunpublish       DATETIME,
          crawling            CHAR(1) DEFAULT 'y',
          brugsbetingelser    ENUM('fuldtekst_alle','fuldtekst_medlemmer','resume_alle','resume_medlemmer') DEFAULT 'fuldtekst_alle',
          betaling            CHAR(1) DEFAULT 'n',
          enkelt_betaling     CHAR(1) DEFAULT 'y',
          abonament_betaling  CHAR(1) DEFAULT 'n',
          enkelt_pris         DECIMAL( 11,2 ) DEFAULT 0,
          abonament_pris      DECIMAL( 11,2 ) DEFAULT 0,
          abonament_periode   INTEGER DEFAULT 12,
          urls_open           CHAR(1) DEFAULT 'y',
          publish_open        CHAR(1) DEFAULT 'y',
          brugs_open          CHAR(1) DEFAULT 'y',
          adresse             TEXT,
          telefon             VARCHAR(20),
          fax                 VARCHAR(20),
          mail                VARCHAR(200),
          udgivelses_dato     DATE,
          revisions_dato      DATE,
          digital_udgave      CHAR(1),
          crawling_depth      INTEGER,
          crawling_cuantitie  INTEGER,
          log_in              CHAR(1) DEFAULT 'n',
          log_name            VARCHAR(200) DEFAULT 'autonomy',
          log_password        VARCHAR(200) DEFAULT '32ReCvQa',
          log_domain          VARCHAR(200),
          db                  VARCHAR(200),
          custom_summary      CHAR(1) DEFAULT 'n',
          forbidden_words     VARCHAR(200),
          required_words      VARCHAR(200),
          indholdsfortegnelse  CHAR(1) DEFAULT 'n',
          observation         TEXT,
          betegnelse          VARCHAR(200),
          overrule_betaling   CHAR(1) DEFAULT 'n'
      )";
$dba->exec( $sql );
$status.= $p."kildestyring table created <br />\n";

/*****************************  Kildestyring status *********************************/
$dba->exec("DROP TABLE IF EXISTS ".$p."kildestyring_state");
$sql = "CREATE TABLE
            ".$p."kildestyring_state
        (
            id                 INTEGER,
            uid                VARCHAR(200),
            time               DATETIME
        )";

$dba->exec( $sql );
$status.= $p."kildestyring_state table created<br />\n";

          
//now dump some data into the kildestyring tree
//insert the root into the tree
$sql = "INSERT INTO
            ".$prefix."kildestyring
        (
            name,
            element_type,
            parent,
            created,
            creator
        )
        VALUES
        (
            'Kilder',
            'root',
            0,
            NOW(),
            1
        )";
$dba->exec( $sql );
?>
