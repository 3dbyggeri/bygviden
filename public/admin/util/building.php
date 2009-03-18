<?php
class Building
{
    var $dba;
    var $p;
    var $id;
    var $table;

    function Building( $dba, $id )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
        $this->table = $this->p.'buildingelements';
    }
    function loadProperties()
    {
      $sql = "SELECT
                name,
                goderaad
              FROM
                ". $this->table ."
              WHERE
                id=". $this->id;
      $props = $this->dba->singleArray( $sql );
      $props['goderaad'] = stripslashes( $props['goderaad'] );
      $props['products'] = $this->loadProdukts();
      $props['publikationer'] = $this->loadKilder();
      $props['agenter'] = $this->loadAgents();
      $props['varegrupper'] = $this->loadVaregrupper();
      $props['categories'] = $this->loadCategories();

      return $props;
    }
    function loadCategories()
    {
      $sql = "SELECT id,name FROM ". $this->p ."categori ORDER BY position";

      $categories = array();
      $result = $this->dba->exec( $sql );
      $n = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $categories[ count( $categories) ] = $this->dba->fetchArray( $result );
      }

      return $categories;
    }
    function loadAgents()
    {
      $sql = "SELECT
                a.id    as id,
                a.name  as name,
                a2e.category as category
              FROM
                ". $this->p ."agentstyring as a,
                ". $this->p ."agent2element as a2e
              WHERE
                a2e.agent = a.id
              AND
                a2e.element = ". $this->id;

        $agenter = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          $agenter[ $rec['category'] ][ count( $agenter[$rec['category'] ] ) ] = $rec;
        }
        return $agenter;
    }
    function loadKilder()
    {
      $sql = "SELECT
                k.id    as kildeid,
                k.name  as kildename,
                k2e.category as category,

                kb.id         AS kat_id,
                kb.name       AS kat_name,

                kc.id         AS kid,
                kc.name       AS kilde_leverandor_name
                
              FROM
                ". $this->p ."kildestyring as k,
                ". $this->p ."kilde2element as k2e,
                ". $this->p ."kildestyring as kb,
                ". $this->p ."kildestyring as kc
              WHERE
                k2e.kilde = k.id
              AND
                k.parent = kb.id
              AND
                kb.parent = kc.id
              AND
                k2e.element = ". $this->id;

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
                ". $this->id ." = v2e.element";
        
        $varegrupper = array();
        $result = $this->dba->exec( $sql );
        $n = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
          $rec = $this->dba->fetchArray( $result );
          //$varegrupper[ $rec['category'] ][ count( $varegrupper[$rec['category'] ] ) ] = $rec;
          $varegrupper[ count( $varegrupper ) ] = $rec;

        }
        return $varegrupper;
    
    }
    function loadProdukts()
    {
      $sql = "SELECT 
                pr.id    as produktid,
                pr.name  as produktname,
                prd.id   as producentid,
                prd.name as producentname,
                p2e.category as category
              FROM
                ". $this->p. "product as pr,
                ". $this->p. "producent as prd,
                ". $this->p. "produkt2element as p2e
              WHERE
                pr.producent = prd.id
              AND
                pr.id = p2e.produkt
              AND
                ". $this->id ." = p2e.element";
        
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
    function setGodeRaad( $goderaad )
    {
      $sql = "UPDATE
                ". $this->table . "
              SET
                goderaad = '". addslashes( trim( $goderaad ) ) . "'
              WHERE
                id = ". $this->id;
      $this->dba->exec( $sql );
    }
    function addKilde( $kilde, $category )
    {
      if( !is_numeric( $kilde ) ) return;
      $sql = "INSERT INTO 
                ". $this->p ."kilde2element
              (
                kilde,
                element,
                category
              )
              VALUES
              (
                $kilde,
                ". $this->id .",
                $category
              )";
      $this->dba->exec( $sql );
    }
    function removeKilde( $kilde, $category )
    {
      if( !is_numeric( $kilde ) ) return;
      $sql = "DELETE FROM
                ". $this->p. "kilde2element
              WHERE
                kilde = $kilde
              AND
                category = $category
              AND
                element = ". $this->id;
      $this->dba->exec( $sql );
    }
    function addAgent( $agent, $category )
    {
      if( !is_numeric( $agent ) ) return;
      $sql = "INSERT INTO 
                ". $this->p ."agent2element
              (
                agent,
                element,
                category
              )
              VALUES
              (
                $agent,
                ". $this->id .",
                $category
              )";
      $this->dba->exec( $sql );
    }
    function removeAgent( $agent, $category )
    {
      if( !is_numeric( $agent ) ) return;
      $sql = "DELETE FROM
                ". $this->p. "agent2element
              WHERE
                agent = $agent
              AND
                category = $category
              AND
                element = ". $this->id;
      $this->dba->exec( $sql );
    }
    function addVaregruppe($varegruppe)
    {
      if( !is_numeric( $varegruppe ) ) return;
      $sql = "INSERT INTO 
                ". $this->p ."varegruppe2element
              (
                varegruppe,
                element
              )
              VALUES
              (
                $varegruppe,
                ". $this->id ."
              )";
      $this->dba->exec( $sql );
    }
    function addProdukt( $produkt, $category )
    {
      if( !is_numeric( $produkt ) ) return;
      $sql = "INSERT INTO 
                ". $this->p ."produkt2element
              (
                produkt,
                element,
                category
              )
              VALUES
              (
                $produkt,
                ". $this->id .",
                $category
              )";
      $this->dba->exec( $sql );
    }
    function removeVaregruppe($varegruppe,$category)
    {
      if( !is_numeric( $varegruppe) ) return;
      $sql = "DELETE FROM
                ". $this->p. "varegruppe2element 
              WHERE
                varegruppe = $varegruppe
              AND
                element = ". $this->id;
      $this->dba->exec( $sql );
    }
    function removeProdukt( $produkt, $category )
    {
      if( !is_numeric( $produkt ) ) return;
      $sql = "DELETE FROM
                ". $this->p. "produkt2element
              WHERE
                produkt = $produkt
              AND
                category = $category
              AND
                element = ". $this->id;
      $this->dba->exec( $sql );
    }
}
?>
