<?php
class producenter
{
  var $dba;
  var $p;
  var $table;
  var $producentList;
  var $producentId;
  var $producent;
  var $produkter;

  function producenter( $dba )
  {
     $this->dba = $dba;
     $this->p   = $this->dba->getPrefix();
     $this->table = $this->p .'producent';
  }
  function addProduct()
  {
    $sql = "INSERT INTO ". $this->p ."product ( producent ) VALUES( ". $this->producentId ."  )";
    $this->dba->exec( $sql );
    return  $this->dba->last_inserted_id();
  }
  function removeProduct( $product )
  {
    if( !is_numeric( $product ) ) return;
    $sql = "DELETE FROM ". $this->p. "product WHERE id=$product";
    $this->dba->exec( $sql );
  }
  function getProducts()
  {
    $sql = "SELECT
              id,
              name,
              kilde_url,
              logo_url,
              description,
              observation
            FROM
              ". $this->p. "product
            WHERE
              producent = ". $this->producentId;
              
    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $this->produkter[ count( $this->produkter ) ] = $this->dba->fetchArray( $result );
    }
    return $this->produkter;
  }
  function toggleProduct( $state )
  {
    if( !$state ) return;
    $sql = "UPDATE
              ". $this->table ."
            SET
              product_open = '$state'
            WHERE
              id = ". $this->producentId;
    $this->dba->exec( $sql );
  }
  function toggle_publish( $publish_state )
  {
    if( $publish_state == 'y' ) $this->publish();
    else $this->unpublish();
  }
  function togglePublish( $state )
  {
    if( !$state ) return;
    $sql = "UPDATE
              ". $this->table ."
            SET
              publish_open = '$state'
            WHERE
              id = ". $this->producentId;
    $this->dba->exec( $sql );
  }
  function toggleData( $state )
  {
    if( !$state ) return;
    $sql = "UPDATE
              ". $this->table ."
            SET
              data_open = '$state'
            WHERE
              id = ". $this->producentId;
    $this->dba->exec( $sql );
  }
  function loadProperties( $id )
  {
    if( !is_numeric( $id ) ) return;
    $this->producentId = $id;
    $sql = "SELECT
              name,
              description,
              observation,
              kilde_url,
              logo_url,
              adresse,
              CVR,
              telefon,
              fax,
              mail,
              data_open,
              publish_open,
              product_open,
              YEAR(timepublish)                     as publishY,
              MONTH(timepublish)                    as publishM,
              DAYOFMONTH(timepublish)               as publishD,
              YEAR(timeunpublish)                   as unpublishY,
              MONTH(timeunpublish)                  as unpublishM,
              DAYOFMONTH(timeunpublish)             as unpublishD
            FROM
              ".$this->table ."
            WHERE
              id = ". $this->producentId;

        $this->producent = $this->dba->singleArray( $sql );
        $this->producent['publish'] = $this->isPublish();
        return $this->producent;
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
                  id=".$this->producentId;
      
      return $this->dba->singleQuery( $sql );
  }
  function getTotal()
  {
    return $this->dba->singleQuery( "SELECT count(*) FROM ". $this->table );
  }
  function getProducenter( $offset = 0, $antal= 10, $sort_order='asc' )
  {
    $sql = "SELECT 
              id,
              name
            FROM
              ". $this->table;
    $sql.=  " ORDER BY name $sort_order "; 
    $sql.=  " LIMIT $offset , $antal "; 

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    for( $i = 0; $i < $n; $i++ )
    {
      $this->producentList[ count( $this->producentList ) ] = $this->dba->fetchArray( $result );
    }
    return $this->producentList;
  }
  function addProducent()
  {
    $sql = "INSERT INTO ". $this->table ." ( name ) VALUES( 'Ny producent' )";
    $this->dba->exec( $sql );

    return $this->dba->last_inserted_id();
  }
  function removeProducent( $id )
  {
    $sql = "DELETE FROM ". $this->table ." WHERE id=". $id;
    $this->dba->exec( $sql );
  }
  function setProducent( $id )
  {
    $this->producentId = $id;
  }
  function setName( $name )
  {
    if( !trim( $name ) ) $name = 'Producent';
    $name = addslashes( $name );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              name = '$name'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setObservation( $observation )
  {
    if( !trim( $observation ) ) $observation = '';
    $observation = addslashes( $observation );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              observation = '$observation'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setDescription( $description )
  {
    if( !trim( $description ) ) $description = '';
    $description = addslashes( $description );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              description = '$description'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setKilde_url( $kilde_url )
  {
    if( !trim( $kilde_url ) ) $kilde_url = '';
    $kilde_url = addslashes( $kilde_url );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              kilde_url = '$kilde_url'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setLogo_url( $logo_url )
  {
    if( !trim( $logo_url ) ) $logo_url = '';
    $logo_url = addslashes( $logo_url );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              logo_url = '$logo_url'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setAdresse( $adresse )
  {
    if( !trim( $adresse ) ) $adresse = '';
    $adresse = addslashes( $adresse );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              adresse = '$adresse'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setCVR( $cvr )
  {
    if( !trim( $cvr ) ) $cvr = '';
    $cvr = addslashes( $cvr );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              CVR = '$cvr'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setTelefon( $telefon )
  {
    if( !trim( $telefon ) ) $telefon = '';
    $telefon = addslashes( $telefon );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              telefon = '$telefon'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setFax( $fax )
  {
    if( !trim( $fax ) ) $fax = '';
    $fax = addslashes( $fax );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              fax = '$fax'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setMail( $mail )
  {
    if( !trim( $mail ) ) $mail = '';
    $mail = addslashes( $mail );
    $sql = "UPDATE
              ".  $this->table ."
            SET 
              mail = '$mail'
            WHERE
              id = ". $this->producentId ;
    $this->dba->exec( $sql );
  }
  function setPublishDate( $d=0, $m=0, $y=0 )
  {
      $sql = "UPDATE 
                ".$this->table ."
              SET  timepublish=";

      if( $d ) 
      {
        $date = "'$y-$m-$d 00:00:00'";
        $sql.= $date; 
        $sql2 = $date;
      }
      else
      {
        $sql.= "NULL";
        $sql2 = "NULL";
      }

      $sql.= " WHERE id=". $this->producentId;

      $this->dba->exec( $sql );

      //update all the products
      $sqlp = "UPDATE
                ".$this->p."product
              SET 
                timepublish = $sql2
              WHERE
                producent = ". $this->producentId;
      $this->dba->exec( $sqlp );
  }
  function setUnPublishDate( $d=0, $m=0, $y=0 )
  {
      $sql = "UPDATE 
                ".$this->table ."
              SET  timeunpublish=";

      if( $d )
      {
        $date = "'$y-$m-$d 00:00:00'";
        $sql.= $date; 
        $sql2 = $date;
      }
      else
      {
        $sql.= "NULL";
        $sql2 = "NULL";
      }

      $sql.= " WHERE id=". $this->producentId;
      $this->dba->exec( $sql );

      //update all the products
      $sqlp = "UPDATE
                ".$this->p."product
              SET 
                timeunpublish = $sql2
              WHERE
                producent = ". $this->producentId;
      $this->dba->exec( $sqlp );
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

  function publish()
  {
      $sql = "UPDATE 
                  ".$this->table ."
              SET
                  timepublish   = NULL,
                  timeunpublish = NULL
              WHERE 
                  id=". $this->producentId;

      $this->dba->exec($sql);

      $sql = "UPDATE
                 ".$this->p."product
               SET
                  timepublish   = NULL,
                  timeunpublish = NULL
              WHERE 
                  producent =". $this->producentId;
      $this->dba->exec($sql);
  }

  function unPublish()
  {
      $sql = "UPDATE 
                  ".$this->table ."
              SET 
                  timepublish   = NULL,
                  timeunpublish = NOW()
              WHERE 
                  id=". $this->producentId;

      $this->dba->exec($sql);

      $sql = "UPDATE
                 ".$this->p."product
               SET
                  timepublish   = NULL,
                  timeunpublish = NOW() 
              WHERE 
                  producent =". $this->producentId;
      $this->dba->exec($sql);
  }
}
?>
