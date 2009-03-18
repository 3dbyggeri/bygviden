<?php
Class Categori
{
  var $dba;
  var $p;
  var $table;

  function Categori( $dba )
  {
    $this->dba = $dba;
    $this->p   = $dba->getPrefix();
    $this->table = $this->p."categori";
  }
  function getCategories()
  {
    $sql = "SELECT id,name FROM ". $this->table ." ORDER BY position";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    $categories = Array();
    for( $i = 0; $i < $n; $i++ )
    {
      $categories[ count( $categories ) ] = $this->dba->fetchArray( $result );
    }
    return $categories;
  }
  function addCategori( $categori_name )
  {
    $n = $this->dba->singleQuery("SELECT MAX(position) FROM ". $this->table);
    $n++;
    $sql = "INSERT INTO ". $this->table ." ( name, position ) VALUES( '". addslashes( trim( $categori_name ) ) ."',$n )";
    $this->dba->exec( $sql );
  }
  function deleteCategori( $categori_id )
  {
    if( !is_numeric( $categori_id ) ) return;
    $sql = "DELETE FROM ". $this->table ." WHERE id=$categori_id";
    $this->dba->exec( $sql );
  }
  function moving($swap,$with)
  {
    $pos_a = $this->dba->singleQuery("SELECT position FROM ".$this->table." WHERE id=".$swap);
    $pos_b = $this->dba->singleQuery("SELECT position FROM ".$this->table." WHERE id=".$with);

    $this->dba->exec("UPDATE ".$this->table ." SET position =". $pos_a ." WHERE id=". $with);
    $this->dba->exec("UPDATE ".$this->table ." SET position =". $pos_b ." WHERE id=". $swap);
  }

  function setName( $categori_id, $categori_name )
  {
    if( !is_numeric( $categori_id ) ) return;
    
    $sql = "UPDATE ". $this->table ." SET name='". addslashes( trim( $categori_name ) ) ."'
            WHERE id=$categori_id";
    $this->dba->exec( $sql );
  }
}
?>
