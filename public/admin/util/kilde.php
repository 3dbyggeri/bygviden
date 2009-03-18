<?php
class kilde
{
    var $dba;
    var $p;
    var $id;
    var $name;
    var $overrule_betaling;
    var $type;
    var $kilde_url;  
    var $forlag_url;
    var $logo_url;
    var $crawling;
    var $parentId;
    var $parentName;
    var $timepublish;
    var $publishDate;
    var $timeunpublish;
    var $unpublishDate;
    var $description;
    var $observation;
    var $creatorId;
    var $creatorName;
    var $editorId;
    var $edtitorName; 
    var $created;
    var $edited;
    var $publish;
    var $brugsbetingelser; 
    var $betaling;          
    var $enkelt_betaling;   
    var $abonament_betaling;
    var $enkelt_pris;      
    var $bruger_rabat;      
    var $abonament_pris;    
    var $abonament_periode; 
    var $urls_open;
    var $publish_open;
    var $brugs_open;
    var $adresse;
    var $telefon;
    var $fax;
    var $mail;
    var $indholdsfortegnelse;
    var $forbidden_words;
    var $required_words;
    var $betegnelse;
    var $samlet_publication;

    var $cat_betaling;
    var $cat_enkelt_betaling;
    var $cat_bruger_rabat;      
    var $cat_abonament_betaling;
    var $cat_enkelt_pris;
    var $cat_abonament_pris;
    var $cat_abonament_periode;
    var $cat_overrule_betaling;
    var $lev_id;
    var $lev_betaling          ;
    var $lev_enkelt_betaling   ;
    var $lev_bruger_rabat;      
    var $lev_abonament_betaling;
    var $lev_enkelt_pris       ;
    var $lev_abonament_pris    ;
    var $lev_abonament_periode ;
    var $lev_overrule_betaling ;
    var $bruger_data_model;
    var $bruger_data_model_boundaries;

    function kilde( $dba, $id=1 )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
        $this->table = $this->p.'kildestyring';

        $this->bruger_data_model = array('1_5_pris'=>'1-5 bruger',
                                         '6_15_pris'=>'6-15 bruger',
                                         '16_50_pris'=>'16-50 bruger',
                                         '51_100_pris'=>'51-100 bruger',
                                         '101_200_pris'=>'101-200 bruger',
                                         'over_200_pris'=>'Over 200 bruger');
        $this->bruger_data_model_boundaries = array('5'=>'1_5_pris',
                                                    '15'=> '6_15_pris',
                                                    '50'=>'16_50_pris',
                                                    '100'=>'51_100_pris',
                                                    '101'=>'101_200_pris',
                                                    '200'=>'over_200_pris');
                                          
    }
    function getBrancheRelevans()
    {
      $sql = "SELECT branche_id FROM ". $this->p ."branche_relevans
              WHERE publikations_id = ". $this->id;
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $relevanteBrancher = array();
      for( $i = 0; $i < $n; $i++ )


      {
        $rec = $this->dba->fetchArray( $result );
        $relevanteBrancher[ $rec[0] ] = 1;
      }

      $sql = "SELECT id, name,label FROM ".  $this->p ."brancher";
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $brancher = array();
      for( $i = 0; $i < $n; $i++ )
      {
        $brancher[$i] = $this->dba->fetchArray( $result );
        $brancher[$i]['checked'] = ( $relevanteBrancher[ $brancher[$i]['id'] ] )? 'checked':''; 
      }
      return $brancher;
    }
    function setBrancheRelevans( $relevanceArray )
    {
       //first remove all
       $sql ="DELETE FROM ". $this->p ."branche_relevans WHERE publikations_id = ". $this->id;
       $this->dba->exec( $sql );

       //now insert selected
       for( $i = 0; $i < count( $relevanceArray ); $i++ )
       {
          $str = "INSERT INTO ". $this->p."branche_relevans (  publikations_id, branche_id )";
          $str.= " VALUES( ". $this->id .",". $relevanceArray[$i] .")";
          $this->dba->exec( $str );
       }
    }
    function toggle( $state, $section )
    {
      $sql = "UPDATE
                ". $this->table ."
              SET
                ". $section ."_open = '$state'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }

    function overrule_betaling( $state )
    {
      $sql = "UPDATE
                ". $this->table ."
              SET
                overrule_betaling = '$state'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function toggle_payment( $state )
    {
      $sql = "UPDATE
                ". $this->table ."
              SET
                betaling = '$state'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function getPublications()
    {
      #Kilder banzai
      $sql = "SELECT
          
                k.id                 AS kildeid,
                k.name               AS kildename,
                k.logo_url           AS kilde_logo,
                k.description        AS kilde_description,
                k.observation        AS kilde_observation,
                k.forlag_url         AS kilde_forlag,

                k.betaling           AS betaling,
                k.enkelt_betaling    AS enkelt_betaling,
                k.abonament_betaling AS abonament_betaling,
                k.overrule_betaling  AS overrule,

                k.digital_udgave     AS digital_udgave,
                k.brugsbetingelser   AS brugsbetingelser,
                k.betegnelse         AS betegnelse,

                kb.id         AS kilde_kategory_id,
                kb.name       AS kilde_kategory_name,
                kb.logo_url   AS kilde_kategory_logo,
                kb.forlag_url AS kilde_kategory_forlag,
                kb.betegnelse AS kilde_kategory_betegnelse,
                kb.brugsbetingelser  AS cat_brugsbetingelser,

                kb.betaling           AS cat_betaling,
                kb.enkelt_betaling    AS cat_enkelt_betaling,
                kb.abonament_betaling AS cat_abonament_betaling,
                kb.overrule_betaling  AS cat_overrule,

                kc.id         AS kilde_leverandor_id,
                kc.name       AS kilde_leverandor_name,
                kc.logo_url   AS kilde_leverandor_logo,
                kc.forlag_url AS kilde_leverandor_forlag,
                kc.betegnelse AS kilde_leverandor_betegnelse,
                kc.brugsbetingelser  AS lev_brugsbetingelser,

                kc.betaling           AS lev_betaling,
                kc.enkelt_betaling    AS lev_enkelt_betaling,
                kc.abonament_betaling AS lev_abonament_betaling,
                kc.overrule_betaling  AS lev_overrule

              FROM
                ". $this->p ."kildestyring as k,
                ". $this->p ."kildestyring as kb,
                ". $this->p ."kildestyring as kc
              WHERE
                k.parent = kb.id
              AND
                kb.parent = kc.id
              AND
                k.parent = ". $this->id ."
              AND
                  ( k.timepublish < NOW() OR k.timepublish IS NULL )
              AND
                  ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) 
              ORDER BY k.position";

        $publikationer = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );

      $publications = array();
      for( $i = 0; $i < $n; $i++ ) $publications[$i] = $this->dba->fetchArray( $result );
      return $publications;
      
      /*
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $publikationer[ $rec['category'] ][ count( $publikationer[$rec['category'] ] ) ] = $rec;
        }
      
      $sql = "SELECT 
                id,
                name,
                forlag_url,
                description,
                observation,
                betaling,
                digital_udgave,
                brugsbetingelser
              FROM
                ". $this->table ."
              WHERE
                parent = ". $this->id ."
              AND
                  ( timepublish < NOW() OR timepublish IS NULL )
              AND
                  ( timeunpublish > NOW() OR timeunpublish IS NULL )
              ORDER BY
                position";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $publications = array();
      for( $i = 0; $i < $n; $i++ ) $publications[$i] = $this->dba->fetchArray( $result );
      return $publications;
      */
    }
    function getKategories( )
    {
      $sql = "SELECT 
                id,
                name
              FROM
                ". $this->table ."
              WHERE
                parent = ". $this->id ."
              AND
                  ( timepublish < NOW() OR timepublish IS NULL )
              AND
                  ( timeunpublish > NOW() OR timeunpublish IS NULL )
              ORDER BY
                position";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $kategories = array();
      for( $i = 0; $i < $n; $i++ ) $kategories[$i] = $this->dba->fetchArray( $result );
      return $kategories;
    }
    function getChildrensList( $parent )
    {
      $sql = "SELECT id FROM ".$this->table." WHERE parent = $parent ";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $rec  = $this->dba->getRecord( $result );
        $this->childrens[ count( $this->childrens ) ] = $rec[0];
        $this->getChildrensList( $rec[0] );
      }
    }
    function toggle_spider( $spider_state )
    {
      $state = ( $spider_state == 'y' )? 'n':'y';
      $sql = "UPDATE
                ". $this->table ."
              SET
                crawling = '$state'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );

      //now update the childrens
      if( !$this->getChildrensList ) $this->getChildrensList( $this->id );
      $str = $this->flattenChildrens();
      if( $str )
      {
        $sql = "UPDATE
                  ". $this->table ."
                SET
                  crawling = '$state'
                WHERE
                  id IN( $str )";
        $this->dba->exec( $sql );
      }
    }
    function flattenChildrens()
    {
      for( $i = 0; $i < count( $this->childrens ); $i++ )
      {
        if( $str )  $str.= ',';
        $str.= $this->childrens[$i];
      }
      return $str;
    }
    function toggle_publish( $publish_state )
    {
      if( $publish_state == 'y' ) $this->publish();
      else $this->unpublish();
    }
    function edited()
    {
        $sql = "UPDATE
                ".$this->table."
               SET
                edited = NOW()
               WHERE
                id=".$this->id;
	    $this->dba->exec( $sql );
    }
    function loadProperties()
    {
        $sql = "SELECT
                    k.name            AS name,
                    k.description     AS description,
                    k.observation     AS observation,
                    k.element_type    AS element_type,
                    k.kilde_url       AS kilde_url,
                    k.forlag_url      AS forlag_url,
                    k.logo_url        AS logo_url,
                    DATE_FORMAT(k.timepublish,'%d %m %Y')   as timepublish,
                    DATE_FORMAT(k.timeunpublish,'%d %m %Y') as timeunpublish,
                    YEAR(k.timepublish)                     as publishY,
                    MONTH(k.timepublish)                    as publishM,
                    DAYOFMONTH(k.timepublish)               as publishD,
                    YEAR(k.timeunpublish)                   as unpublishY,
                    MONTH(k.timeunpublish)                  as unpublishM,
                    DAYOFMONTH(k.timeunpublish)             as unpublishD,
                    k.crawling            AS crawling,
                    k.brugsbetingelser    AS brugsbetingelser, 

                    k.urls_open           AS urls_open,
                    k.publish_open        AS publish_open,
                    k.brugs_open          AS brugs_open,
                    k.adresse             AS adresse,
                    k.telefon             AS telefon,
                    k.fax                 AS fax,
                    k.mail                AS mail,
                    k.digital_udgave      AS digital_udgave,
                    k.crawling_depth      AS crawling_depth,
                    k.crawling_cuantitie  AS crawling_cuantitie,
                    k.log_in              AS log_in,
                    k.log_name            AS log_name,
                    k.log_password        AS log_password,
                    k.custom_summary      AS custom_summary,
                    k.indholdsfortegnelse AS indholdsfortegnelse,
                    k.required_words      AS required_words,
                    k.forbidden_words     AS forbidden_words,
                    k.db                  AS db,
                    k.betegnelse          AS betegnelse,
                    k2.id                 AS parent_id,
                    k2.name               AS parent_name,

                    k.betaling            AS betaling,          
                    k.enkelt_betaling     AS enkelt_betaling,   
                    k.abonament_betaling  AS abonament_betaling,
                    k.enkelt_pris         AS enkelt_pris,      
                    k.bruger_rabat        AS bruger_rabat,      
                    k.abonament_pris      AS abonament_pris,    
                    k.abonament_periode   AS abonament_periode, 
                    k.overrule_betaling   AS overrule,


                    k2.betaling            AS cat_betaling,          
                    k2.enkelt_betaling     AS cat_enkelt_betaling,   
                    k2.abonament_betaling  AS cat_abonament_betaling,
                    k2.enkelt_pris         AS cat_enkelt_pris,      
                    k2.bruger_rabat        AS cat_bruger_rabat,      
                    k2.abonament_pris      AS cat_abonament_pris,    
                    k2.abonament_periode   AS cat_abonament_periode, 
                    k2.overrule_betaling   AS cat_overrule,
                    k2.brugsbetingelser    AS cat_brugsbetingelser, 
                    k2.indholdsfortegnelse AS samlet_publication,
                    
                    k3.id                  AS lev_id,
                    k3.betaling            AS lev_betaling,          
                    k3.enkelt_betaling     AS lev_enkelt_betaling,   
                    k3.abonament_betaling  AS lev_abonament_betaling,
                    k3.enkelt_pris         AS lev_enkelt_pris,      
                    k3.bruger_rabat        AS lev_bruger_rabat,      
                    k3.abonament_pris      AS lev_abonament_pris,    
                    k3.abonament_periode   AS lev_abonament_periode, 
                    k3.brugsbetingelser    AS lev_brugsbetingelser, 
                    k3.overrule_betaling   AS lev_overrule
                FROM
                    ".$this->table."  AS k,
                    ".$this->table."  AS k2
                LEFT JOIN
                    ".$this->table."  AS k3
                ON
                    k3.id = k2.parent
                WHERE
                    k.id=". $this->id ."
                AND
                    k2.id = k.parent";

        $record             = $this->dba->singleArray( $sql );
        
        $this->name                = stripslashes ( $record["name"] );
        $this->type                = stripslashes ( $record["element_type"] );
        $this->kilde_url           = stripslashes ( $record['kilde_url'] );
        $this->forlag_url          = stripslashes ( $record['forlag_url'] );
        $this->logo_url            = stripslashes ( $record['logo_url'] );
        $this->crawling            = $record['crawling'];
        $this->description         = stripslashes ( $record["description"] );
        $this->observation         = stripslashes ( $record["observation"] );
        $this->brugsbetingelser    = stripslashes( $record['brugsbetingelser'] );
        $this->cat_brugsbetingelser    = stripslashes( $record['cat_brugsbetingelser'] );
        $this->lev_brugsbetingelser    = stripslashes( $record['lev_brugsbetingelser'] );

        $this->urls_open           = $record['urls_open'];
        $this->publish_open        = $record['publish_open'];
        $this->brugs_open          = $record['brugs_open'];
        $this->adresse             = stripslashes( $record['adresse'] );
        $this->telefon             = stripslashes( $record['telefon'] );
        $this->fax                 = stripslashes( $record['fax'] );
        $this->mail                = stripslashes( $record['mail'] );
        $this->log_in              = $record['log_in'];
        $this->log_name            = stripslashes( $record['log_name'] );
        $this->log_password        = stripslashes( $record['log_password'] );
        $this->crawling_depth      = $record['crawling_depth'];
        $this->crawling_cuantitie  = $record['crawling_cuantitie'];
        $this->digital_udgave      = $record['digital_udgave'];
        $this->timepublish         = $record["timepublish"];
        $this->timeunpublish       = $record["timeunpublish"];
        $this->custom_summary      = $record['custom_summary'];
        $this->indholdsfortegnelse = $record['indholdsfortegnelse'];
        $this->forbidden_words     = stripslashes( $record['forbidden_words'] );
        $this->required_words      = stripslashes( $record['required_words'] );
        $this->betegnelse          = stripslashes( $record['betegnelse'] );
        $this->db                  = stripslashes( $record['db'] );
        $this->parent_id           = $record['parent_id'];
        $this->parent_name         = $record['parent_name'];
        $this->samlet_publication  = $record['samlet_publication'];

        $this->publishDate["y"] = $record["publishY"];
        $this->publishDate["m"] = $record["publishM"];
        $this->publishDate["d"] = $record["publishD"];

        $this->unpublishDate["y"] = $record["unpublishY"];
        $this->unpublishDate["m"] = $record["unpublishM"];
        $this->unpublishDate["d"] = $record["unpublishD"];

        $this->publish  = $this->isPublish();

        $this->betaling            = $record['betaling'];
        $this->enkelt_betaling     = $record['enkelt_betaling'];
        $this->abonament_betaling  = $record['abonament_betaling'];
        $this->enkelt_pris         = $record['enkelt_pris'];
        $this->bruger_rabat        = $record['bruger_rabat'];
        $this->abonament_pris      = $record['abonament_pris'];
        $this->abonament_periode   = $record['abonament_periode'];
        $this->overrule_betaling   = $record['overrule'];

        $this->cat_betaling            = $record['cat_betaling'];
        $this->cat_enkelt_betaling     = $record['cat_enkelt_betaling'];
        $this->cat_abonament_betaling  = $record['cat_abonament_betaling'];
        $this->cat_enkelt_pris         = $record['cat_enkelt_pris'];
        $this->cat_bruger_rabat        = $record['cat_bruger_rabat'];
        $this->cat_abonament_pris      = $record['cat_abonament_pris'];
        $this->cat_abonament_periode   = $record['cat_abonament_periode'];
        $this->cat_overrule_betaling   = $record['cat_overrule'];

        $this->lev_id                  = $record['lev_id'];
        $this->lev_betaling            = $record['lev_betaling'];
        $this->lev_bruger_rabat        = $record['lev_bruger_rabat'];
        $this->lev_enkelt_betaling     = $record['lev_enkelt_betaling'];
        $this->lev_abonament_betaling  = $record['lev_abonament_betaling'];
        $this->lev_enkelt_pris         = $record['lev_enkelt_pris'];
        $this->lev_abonament_pris      = $record['lev_abonament_pris'];
        $this->lev_abonament_periode   = $record['lev_abonament_periode'];
        $this->lev_overrule_betaling   = $record['lev_overrule'];
    }
    function hasBrugerRabat()
    {
      if($this->bruger_rabat == 'y')  return true;
      if($this->cat_bruger_rabat == 'y')  return true;
      if($this->lev_bruger_rabat == 'y')  return true;
      return false;
    }
    function isPublish()
    {
        $sql = "SELECT 
                    id
                FROM 
                    ". $this->table ."
                WHERE
                    ( timepublish < NOW() OR timepublish IS NULL )
                AND
                    ( timeunpublish > NOW() OR timeunpublish IS NULL )
                AND 
                    id=".$this->id;
        
        return $this->dba->singleQuery( $sql );
    }
    function unPublish()
    {
        $sql = "UPDATE 
                    ".$this->table."
                SET 
                    timepublish   = NULL,
                    timeunpublish = NOW()
                WHERE 
                    id=". $this->id;

        $this->dba->exec($sql);

        //now update the childrens
        if( !$this->getChildrensList ) $this->getChildrensList( $this->id );
        $str = $this->flattenChildrens();
        if( $str )
        {
          $sql = "UPDATE
                    ". $this->table ."
                  SET
                    timepublish   = NULL,
                    timeunpublish = NOW()
                  WHERE
                    id IN( $str )";
          $this->dba->exec( $sql );
        }
    }
    function publish()
    {
        $sql = "UPDATE 
                    ".$this->table."
                SET
                    timepublish   = NULL,
                    timeunpublish = NULL
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);

        //now update the childrens
        if( !$this->getChildrensList ) $this->getChildrensList( $this->id );
        $str = $this->flattenChildrens();
        if( $str )
        {
          $sql = "UPDATE
                    ". $this->table ."
                  SET
                    timepublish   = NULL,
                    timeunpublish = NULL
                  WHERE
                    id IN( $str )";
          $this->dba->exec( $sql );
        }
    }
    function setDB( $db )
    {
        $sql = "UPDATE 
                    ".$this->table."
                SET
                    db = '$db'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setForbiddenWords( $words )
    {
        $sql = "UPDATE 
                    ".$this->table."
                SET
                    forbidden_words = '$words'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setRequiredWords( $words )
    {
        $sql = "UPDATE 
                    ".$this->table."
                SET
                    required_words = '$words'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setObservation( $observation )
    {
        $this->observation = trim( $observation );
        
        $sql = "UPDATE
                    ".$this->table."
                SET
                    observation ='". addslashes( $this->observation ) ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setDescription( $description )
    {
        $this->description = trim( $description );
        
        $sql = "UPDATE
                    ".$this->table."
                SET
                    description ='". addslashes( $this->description ) ."'
                WHERE 
                    id=". $this->id;
        $this->dba->exec($sql);
    }
    function setUrl( $url )
    {
      $this->kilde_url  = trim( $url );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  kilde_url ='". addslashes( $this->kilde_url ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }

    function updatePayment()
    {
      $bruger_rabat = ($_POST['bruger_rabat'])? 'y':'n';
      $enkelt_betaling = ($_POST['enkelt_betaling'])? 'y':'n';
      $abonament_periode = (is_numeric($_POST['abonament_periode']))? ",abonament_periode = ". $_POST['abonament_periode']:'';
      $abonament_betaling = ($_POST['abonament_betaling'])?'y':'n';
      $this->brugsbetingelser = trim($_POST['brugsbetingelser']);
      $enkelt_pris =(is_numeric($_POST['enkelt_pris']))? ",enkelt_pris=".$_POST['enkelt_pris']:'';
      $abonament_pris = (is_numeric($_POST['abonament_pris']))? ",abonament_pris=".$_POST['abonament_pris']:'';

      $sql = "UPDATE
                  ".$this->table."
              SET
                  bruger_rabat ='$bruger_rabat' 
                  ,enkelt_betaling ='$enkelt_betaling' 
                  ". $abonament_periode ."
                  ". $abonament_pris."
                  ". $enkelt_pris ."
                  ,abonament_betaling = '$abonament_betaling'
                  ,brugsbetingelser = '".addslashes($this->brugsbetingelser)."'
              WHERE 
                  id=". $this->id;

      $this->dba->exec($sql);

      if($bruger_rabat == 'y') $this->updateBrugerRabat();
    }
    function getBrugerDataForID($id)
    {
      $sql ="SELECT 
                antal_bruger,pris 
             FROM 
                ".$this->p."bruger_rabat 
              WHERE
                kilde_id = ".$id;

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $bd = array();
      for( $i = 0; $i < $n; $i++ )
      {
        $rec = $this->dba->fetchArray( $result );
        $bd[ $rec[0] ] = $rec[1];
      }
      return $bd;
    }
    function findRelevantBrugerRabatKildeId()
    {
      $kid = 0;
      if ($this->lev_bruger_rabat == 'y' && $this->lev_overrule_betaling == 'y') $kid = $this->lev_id;
      if ($this->cat_bruger_rabat == 'y' && $this->cat_overrule_betaling == 'y') $kid = $this->parent_id;
      //if ($this->samlet_publication == 'y') $kid = $this->parent_id;
      if ($this->bruger_rabat == 'y' && $this->overrule_betaling =='y') $kid = $this->id;
      return $kid;
    }
    function getBrugerRabatPriser()
    {
      //find
      $kid = $this->findRelevantBrugerRabatKildeId();
      if(!$kid) return;
      return $this->getBrugerDataForID($kid); 
    }
    function getBrugerData()
    {
      return $this->getBrugerDataForID($this->id);
    }
    function updateBrugerRabat()
    {
      $sql = "DELETE FROM 
                ".$this->p."bruger_rabat 
              WHERE
                kilde_id = ".$this->id;
      $this->dba->exec($sql);

      foreach($this->bruger_data_model as $k=>$v)
      {
        if(!is_numeric($_POST[$k]) || !$_POST[trim($k)]) continue;
        $sql = "INSERT INTO
                ".$this->p."bruger_rabat 
               ( kilde_id,antal_bruger,pris)
               VALUES
               (".$this->id.",
                '".$k."',
                ".$_POST[$k].")";
        $this->dba->exec($sql);
      }
    }
    function setCustomSummary( $state )
    {
      $state = ( $state )?'y':'n';
      $sql = "UPDATE
                  ".$this->table."
              SET
                  custom_summary='$state'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setIndholdsfortegnelse( $indholdsfortegnelse )
    {
      $state = ( $indholdsfortegnelse )?'y':'n';
      $sql = "UPDATE
                  ".$this->table."
              SET
                  indholdsfortegnelse ='$state'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setDigitalUdgave( $state )
    {
      $state = ( $state )?'n':'y';
      $sql = "UPDATE
                  ".$this->table."
              SET
                  digital_udgave ='$state'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setLogin( $state, $name = '', $password = '' )
    {
      $state = ( $state )?'y':'n';
      $name = addslashes( trim( $name ) );
      $password = addslashes( trim( $password ) );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  log_in ='$state',
                  log_name = '$name',
                  log_password = '$password'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setCrawlingCuantitie( $cuantitie )
    {
      if( !is_numeric( $cuantitie ) ) return;
      $sql = "UPDATE
                  ".$this->table."
              SET
                  crawling_cuantitie = $cuantitie
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setCrawlingDepth( $depth )
    {
      if( !is_numeric( $depth ) ) return;
      $sql = "UPDATE
                  ".$this->table."
              SET
                  crawling_depth = $depth
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function isBrugs_open()
    {
      $sql = "SELECT
                id
              FROM
                ".$this->table ."
              WHERE
                id =". $this->id ."
              AND
                brugs_open = 'y'";
      return $this->dba->singleQuery( $sql );
    }
    function isPublish_open()
    {
      $sql = "SELECT
                id
              FROM
                ".$this->table ."
              WHERE
                id =". $this->id ."
              AND
                publish_open = 'y'";
      return $this->dba->singleQuery( $sql );
    }
    function isUrls_open()
    {
      $sql = "SELECT
                id
              FROM
                ".$this->table ."
              WHERE
                id =". $this->id ."
              AND
                urls_open = 'y'";
      return $this->dba->singleQuery( $sql );
    }
    function setForlag( $forlag )
    {
      $this->forlag_url = trim( $forlag );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  forlag_url ='". addslashes( $this->forlag_url ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setMail( $mail )
    {
      $this->mail = trim( $mail );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  mail ='". addslashes( $this->mail ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setFax( $fax )
    {
      $this->fax = trim( $fax );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  fax ='". addslashes( $this->fax ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setTelefon( $telefon )
    {
      $this->telefon  = trim( $telefon );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  telefon ='". addslashes( $this->telefon ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setAdresse( $adresse )
    {
      $this->adresse = trim( $adresse );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  adresse ='". addslashes( $this->adresse ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setBetegnelse( $betegnelse )
    {
      $this->betegnelse = trim( $betegnelse );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  betegnelse ='". addslashes( $this->betegnelse ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }
    function setLogo( $logo )
    {
      $this->logo_url = trim( $logo );
      $sql = "UPDATE
                  ".$this->table."
              SET
                  logo_url ='". addslashes( $this->logo_url ) ."'
              WHERE 
                  id=". $this->id;
      $this->dba->exec($sql);
    }

    function setRevisionDate( $d=0, $m=0, $y=0 )
    {
        $sql = "UPDATE 
                  ".$this->table."
                SET  revision=";
        if( $d ) $sql.= "'$y-$m-$d 00:00:00'";
        else $sql.= "NULL";
        $sql.= " WHERE id=". $this->id;
        
        $this->dba->exec( $sql );
    }
    function setUdgivelseDate( $d=0, $m=0, $y=0 )
    {
        $sql = "UPDATE 
                  ".$this->table."
                SET  udgivelse =";
        if( $d ) $sql.= "'$y-$m-$d 00:00:00'";
        else $sql.= "NULL";
        $sql.= " WHERE id=". $this->id;
        
        $this->dba->exec( $sql );
    }
    function setPublishDate( $d=0, $m=0, $y=0 )
    {

        $sql = "UPDATE 
                  ".$this->table."
                SET  timepublish=";
        if( $d )
        {
            $this->publishDate["d"] = $d;
            $this->publishDate["m"] = $m;
            $this->publishDate["y"] = $y;


            $sql.= "'$y-$m-$d 00:00:00'";
        }
        else
        {
            $sql.= "NULL";
        }
        $sql2 = $sql;
        $sql.= " WHERE id=". $this->id;
        
        $this->dba->exec( $sql );

        //now update the childrens
        if( !$this->getChildrensList ) $this->getChildrensList( $this->id );
        $str = $this->flattenChildrens();
        if( $str )
        {
          $sql2.= " WHERE id IN( $str )";
          $this->dba->exec( $sql2 );
        }
    }
    function setUnPublishDate( $d=0, $m=0, $y=0 )
    {
        $sql = "UPDATE 
                  ".$this->table."
                SET  timeunpublish=";
        if( $d )
        {
            $this->unpublishDate["d"] = $d;
            $this->unpublishDate["m"] = $m;
            $this->unpublishDate["y"] = $y;

            $sql.= "'$y-$m-$d 00:00:00'";
        }
        else
        {
            $sql.= "NULL";
        }
        $sql2 = $sql;
        $sql.= " WHERE id=". $this->id;

        $this->dba->exec( $sql );

        //now update the childrens
        if( !$this->getChildrensList ) $this->getChildrensList( $this->id );
        $str = $this->flattenChildrens();
        if( $str )
        {
          $sql2.= " WHERE id IN( $str )";
          $this->dba->exec( $sql2 );
        }

    }
    function setPublish( $publish )
    {
        $this->publish  = $this->isPublish();
        if( $publish )
        {
            //check is the publish settings has been changed
            if( $this->publish ) return;

            //check if publishish has been schedule
            if( $this->publishDate  ) return;
            $this->publish();
        }
        else
        {
            //check is the publish settings has been changed
            if( !$this->publish ) return;

            //check if unpublishing har been scheduled
            if( $this->unpublishDate  ) return;

            $this->unPublish();
        }
    }
}
?>
