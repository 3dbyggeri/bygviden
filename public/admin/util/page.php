<?php
class Page
{
    var $dba;
    var $p;
    var $element_id;
    var $branche_element_id;
    var $element_name;
    var $properties;
    var $branche;
    var $categories;
    var $categoriesId;
    var $path;

    function Page( $dba, $element_id = 0 , $branche_element_id = 0, $branche = '' )
    {
        $this->dba        = $dba;
        $this->p          = $this->dba->getPrefix();
        $this->element_id = $element_id;
        $this->branche    = $branche;
        $this->branche_element_id = $branche_element_id;
    }
    function path($node_id,$branche)
    {
      $this->path = array();
      if(!$branche) return;
      $branche= $this->dba->getPrefix().$branche;
      $this->getParent($node_id,$branche);
      return $this->path;
    }
    function getParent($node_id,$branche)
    {
      if(!$node_id) return;
      if(!$branche) return;
      $sql = "SELECT
                  btree.id         AS 'id',
                  btree.name       AS 'name',
                  btree.element_id AS 'element_id'
              FROM
                  ". $branche." AS btree,
                  ". $branche." AS btreeChild
              WHERE
                  btreeChild.id = $node_id
              AND
                  btreeChild.parent = btree.id
              ";
       $rec = $this->dba->singleArray($sql);
       if($rec) 
       {  
        if($rec['id'] > 1) array_push($this->path,$rec);
        $this->getParent($rec['id'],$branche);
       }
    }

    function load()
    {
      if( !$this->element_id ) return;
      $sql = "SELECT name as element_name, goderaad FROM ". $this->p ."buildingelements
              WHERE id = ". $this->element_id;

      $this->properties = $this->dba->singleArray( $sql );
      $this->properties['goderaad'] = stripslashes( $this->properties['goderaad'] );

      return $this->properties;
    }

    function loadCategories()
    {
      if( !$this->branche || !$this->branche_element_id ) return;

      $sql = "SELECT 
                c.id    AS category_id,
                c.name  AS category_name
              FROM 
                ". $this->p ."branche2category AS b2c,
                ". $this->p ."categori         AS c
              WHERE
                b2c.branche_name = '". $this->branche ."'

              AND
                b2c.branche_element_id = ". $this->branche_element_id ."
              AND
                b2c.category_id = c.id
              ORDER BY
                c.position";
      
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $this->categories = Array();
      $this->categoriesId = '';

      for( $i = 0; $i < $n; $i++ ) 
      {
        $this->categories[$i] = $this->dba->fetchArray( $result ); 
        if( $this->categoriesId ) $this->categoriesId.= ',';
        $this->categoriesId.=  $this->categories[$i]['category_id'];
      }

      return $this->categories;
    }

    function loadAgents()
    {
      if( !$this->categoriesId ) return array();
      if( !$this->element_id   ) return array();
      $sql = "SELECT
                a.*, 
                a2e.category as category
              FROM
                ". $this->p ."agentstyring as a,
                ". $this->p ."agent2element as a2e
              WHERE
                a2e.agent = a.id
              AND
                a2e.element = ". $this->element_id ."
              AND 
                a2e.category IN ( ". $this->categoriesId ." )";
	
        $agenter = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
        $aci = new ACI();

        for( $i = 0; $i < $n; $i++ )
        {
		
          $rec = $this->dba->fetchArray( $result );
	  $agent = new Agent( $this->dba, $rec['id'] );
	  $agent->number_of_results = $rec['results'];
	  $agent->id = $rec['id'];
	  $agentResults = $agent->getResults();
	  $hits = $aci->getHits($agentResults);
	  $idString = $aci->getPublicationIDString($hits);
	  $rec['publications'] = $this->loadAgentPublications($idString);
	  $rec['resultsObj'] = $agentResults;

          $agenter[ $rec['category'] ][ count( $agenter[$rec['category'] ] ) ] = $rec;
        }

        # agents should cache:
        # the returned url, title, summary, autonomy_id and publication id's list from autonomy
        # this should be implemented in 'agent_cache_table'
        return $agenter;
    }
    function loadAgentPublications( $pub_id_str )
    {
        if( !$pub_id_str ) return array();

        $sql = "SELECT
                  k.id                 AS kildeid,
                  k.name               AS title,
                  k.logo_url           AS kilde_logo,
                  k.description        AS kilde_description,
                  k.observation        AS kilde_observation,
                  k.forlag_url         AS kilde_forlag,
                  k.digital_udgave     AS digital_udgave,
                  k.brugsbetingelser   AS brugsbetingelser,

                  k.betaling           AS betaling,
                  k.enkelt_betaling    AS enkelt_betaling,
                  k.abonament_betaling AS abonament_betaling,
                  k.overrule_betaling  AS overrule,

                  kb.id         AS kilde_kategory_id,
                  kb.name       AS kilde_kategory_name,
                  kb.logo_url   AS kilde_kategory_logo,
                  kb.forlag_url AS kilde_kategory_forlag,

                  kb.betaling           AS cat_betaling,
                  kb.enkelt_betaling    AS cat_enkelt_betaling,
                  kb.abonament_betaling AS cat_abonament_betaling,
                  kb.overrule_betaling  AS cat_overrule,

                  kc.id         AS kilde_leverandor_id,
                  kc.name       AS kilde_leverandor_name,
                  kc.logo_url   AS kilde_leverandor_logo,
                  kc.forlag_url AS kilde_leverandor_forlag,

                  kc.betaling           AS lev_betaling,
                  kc.enkelt_betaling    AS lev_enkelt_betaling,
                  kc.abonament_betaling AS lev_abonament_betaling,
                  kc.overrule_betaling  AS lev_overrule

                FROM
                  ". $this->dba->getPrefix() ."kildestyring as k,
                  ". $this->dba->getPrefix() ."kildestyring as kb,
                  ". $this->dba->getPrefix() ."kildestyring as kc
                WHERE
                  k.parent = kb.id
                AND
                  kb.parent = kc.id
                AND 
                  k.id IN ( ". $pub_id_str ." )
                AND
                    ( k.timepublish < NOW() OR k.timepublish IS NULL )
                AND
                    ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL )
                LIMIT 4";

          $publikationer = array();
          $result = $this->dba->exec( $sql );
          $n = $this->dba->getN( $result );
          for( $i = 0; $i < $n; $i++ )
          {
            $rec = $this->dba->fetchArray( $result );
            $publikationer[ $rec['kildeid'] ] = $rec;
          }

          return $publikationer;
    }
    function loadKilder()
    {
      if( !$this->categoriesId ) return array();
      if( !$this->element_id   ) return array();

      #Kilder banzai
      $sql = "SELECT
          
                k.id                 AS kildeid,
                k.name               AS title,
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
                k.bruger_rabat       AS bruger_rabat,      

                k2e.category  AS category,

                kb.id         AS kat_id,
                kb.id         AS kilde_kategory_id,
                kb.name       AS kilde_kategory_name,
                kb.logo_url   AS kilde_kategory_logo,
                kb.forlag_url AS kilde_kategory_forlag,
                kb.betegnelse AS kilde_kategory_betegnelse,
                kb.indholdsfortegnelse AS samlet_publication,

                kb.betaling           AS cat_betaling,
                kb.enkelt_betaling    AS cat_enkelt_betaling,
                kb.abonament_betaling AS cat_abonament_betaling,
                kb.overrule_betaling  AS cat_overrule,
                kb.bruger_rabat       AS cat_bruger_rabat,      

                kc.id         AS kid,
                kc.id         AS kilde_leverandor_id,
                kc.kilde_url  AS kilde_leverandor_url,
                kc.name       AS kilde_leverandor_name,
                kc.logo_url   AS kilde_leverandor_logo,
                kc.forlag_url AS kilde_leverandor_forlag,
                kc.betegnelse AS kilde_leverandor_betegnelse,

                kc.betaling           AS lev_betaling,
                kc.enkelt_betaling    AS lev_enkelt_betaling,
                kc.abonament_betaling AS lev_abonament_betaling,
                kc.overrule_betaling  AS lev_overrule,
                kc.bruger_rabat       AS lev_bruger_rabat      

              FROM
                ". $this->p ."kildestyring as k,
                ". $this->p ."kildestyring as kb,
                ". $this->p ."kildestyring as kc,
                ". $this->p ."kilde2element as k2e
              WHERE
                k2e.kilde = k.id
              AND
                k.parent = kb.id
              AND
                kb.parent = kc.id
              AND
                k2e.element = ". $this->element_id ."
              AND 
                k2e.category IN ( ". $this->categoriesId ." )
              AND
                  ( k.timepublish < NOW() OR k.timepublish IS NULL )
              AND
                  ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) ";

        $publikationer = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $publikationer[ $rec['category'] ][ count( $publikationer[$rec['category'] ] ) ] = $rec;
        }
        return $publikationer;
    }
    function loadVaregrupper()
    {
      $sql = "SELECT 
                v.id    as id,
                v.name  as name,

                v2e.category as category
              FROM
                ". $this->p. "varegrupper as v,
                ". $this->p. "varegruppe2element as v2e
              WHERE
                v.id = v2e.varegruppe
              AND
                v2e.element = ".  $this->element_id;
        
        $varegrupper = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $varegrupper[ count( $varegrupper ) ] = $this->dba->fetchArray( $result );
        }
        return $varegrupper;
    }
    function loadProdukts()
    {
      if( !$this->categoriesId ) return array();
      if( !$this->branche_element_id ) return array();

      $sql = "SELECT 
                pr.id          AS produktid,
                pr.name        AS produktname,
                pr.kilde_url   AS produkt_side,
                pr.logo_url    AS produkt_logo,
                pr.description AS description,
                pr.observation AS observation,

                prd.id         AS producentid,
                prd.name       AS producentname,
                prd.kilde_url  AS producentpage,
                prd.logo_url   AS producent_logo,
                p2e.category   AS category
              FROM
                ". $this->p. "product as pr,
                ". $this->p. "producent as prd,
                ". $this->p. "produkt2element as p2e
              WHERE
                pr.producent = prd.id
              AND
                pr.id = p2e.produkt
              AND
                ". $this->branche_element_id ." = p2e.element
              AND 
                p2e.category IN ( ". $this->categoriesId ." )
              AND
                  ( pr.timepublish < NOW() OR pr.timepublish IS NULL )
              AND
                  ( pr.timeunpublish > NOW() OR pr.timeunpublish IS NULL ) ";
        
        $produkt = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $produkt[ $rec['category'] ][ count( $produkt[$rec['category'] ] ) ] = $rec;
        }
        return $produkt;
    }
}
