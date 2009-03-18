<?php
class product
{
  var $dba;
  var $p;
  var $table;
  var $id;
  var $properties;

  function product( $dba, $id )
  {
     $this->dba = $dba;
     $this->p   = $this->dba->getPrefix();
     $this->table = $this->p .'product';
     $this->id = $id;
  }
  function loadProperties()
  {
    $sql = "SELECT
              name,
              description,
              observation,
              kilde_url,
              logo_url,
              YEAR(timepublish)                     as publishY,
              MONTH(timepublish)                    as publishM,
              DAYOFMONTH(timepublish)               as publishD,
              YEAR(timeunpublish)                   as unpublishY,
              MONTH(timeunpublish)                  as unpublishM,
              DAYOFMONTH(timeunpublish)             as unpublishD,
              data_open,
              publish_open
            FROM
              ".$this->table ."
            WHERE
              id = ". $this->id;
      $this->properties = $this->dba->singleArray( $sql );
      $this->properties['publish'] = $this->isPublish();
      return $this->properties;
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

  function toggle_publish( $publish_state )
  {
    if( $publish_state == 'y' ) $this->publish();
    else $this->unpublish();
  }
  function setLogo_url( $logo_url )
  {
    $sql = "UPDATE ". $this->table ." 
           SET logo_url = '". addslashes( $logo_url ) ."'
           WHERE id = ". $this->id;
    $this->dba->exec( $sql );
  }
  function setKilde_url( $kilde_url )
  {
    $sql = "UPDATE ". $this->table ." 
           SET kilde_url = '". addslashes( $kilde_url ) ."'
           WHERE id = ". $this->id;
    $this->dba->exec( $sql );
  }
  function setObservation( $observation )
  {
    $sql = "UPDATE ". $this->table ." 
           SET observation= '". addslashes( $observation ) ."'
           WHERE id = ". $this->id;
    $this->dba->exec( $sql );
  }
  function setDescription( $description )
  {
    $sql = "UPDATE ". $this->table ." 
           SET description = '". addslashes( $description ) ."'
           WHERE id = ". $this->id;
    $this->dba->exec( $sql );
  }
  function setName( $name )
  {
    $sql = "UPDATE ". $this->table ." 
           SET name = '". addslashes( $name ) ."'
           WHERE id = ". $this->id;
    $this->dba->exec( $sql );
  }
  function setPublishDate( $d=0, $m=0, $y=0 )
  {
      $sql = "UPDATE 
                ".$this->table ."
              SET  timepublish=";

      if( $d ) $sql.= "'$y-$m-$d 00:00:00'";
      else $sql.= "NULL";

      $sql.= " WHERE id=". $this->id;

      $this->dba->exec( $sql );
  }
  function setUnPublishDate( $d=0, $m=0, $y=0 )
  {
      $sql = "UPDATE 
                ".$this->table ."
              SET  timeunpublish=";

      if( $d ) $sql.= "'$y-$m-$d 00:00:00'";
      else $sql.= "NULL";

      $sql.= " WHERE id=". $this->id;
      $this->dba->exec( $sql );
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
                  id=". $this->id;
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
                  id=". $this->id;

      $this->dba->exec($sql);
  }
  function togglePublish( $state )
  {
    if( !$state ) return;
    $sql = "UPDATE
              ". $this->table ."
            SET
              publish_open = '$state'
            WHERE
              id = ". $this->id;
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
              id = ". $this->id;
    $this->dba->exec( $sql );
  }
}
?>
