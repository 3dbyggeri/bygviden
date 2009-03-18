<?php
class Branche 
{
    var $dba;
    var $p;
    var $id;
    var $branche_name;
    var $table;
    var $categories;

    function Branche( $dba, $id, $table )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
        $this->branche_name = $table;
        $this->table = $this->p. $table;
    }
    function loadProperties()
    {
      $sql = "SELECT name, element_id FROM ". $this->table ." WHERE id=". $this->id;
      return $this->dba->singleArray( $sql );
    }
    function setName( $name )
    {
      $sql = "UPDATE ". $this->table ." SET name='". addslashes( trim( $name ) ) ."' WHERE id=". $this->id;
      $this->dba->exec( $sql );
    }
    function setElementId( $element_id )
    {
      if( !$element_id ) $element_id = 0;
      $sql = "UPDATE ". $this->table ." SET element_id= $element_id WHERE id=". $this->id;
      $this->dba->exec( $sql );
    }
    function loadCategories()
    {
      $sql = "SELECT 
                category_id 
              FROM 
                ". $this->p . "branche2category
              WHERE
                branche_name = '". $this->branche_name ."'
              AND
                branche_element_id = ". $this->id;

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      $this->categories = Array();
      for( $i = 0; $i < $n; $i++ ) 
      {
        $rec = $this->dba->fetchArray( $result ); 
        $this->categories[$i] = $rec["category_id"]; 
      }

      return $this->categories;
    }
    function saveCategories( $category_list )
    {
      $sql = "DELETE FROM 
                ". $this->p. "branche2category
              WHERE
                branche_name = '". $this->branche_name ."'
              AND
                branche_element_id = ". $this->id;
      $this->dba->exec( $sql );

      if( !count( $category_list ) ) return;

      $sql = "INSERT INTO 
                ". $this->p ."branche2category 
                ( branche_name, branche_element_id, category_id )
              VALUES ";
      for( $i = 0; $i < count( $category_list ); $i++ )
      {
        if( $str ) $str.= ','; 
        $str.= "('". $this->branche_name ."',". $this->id .",". $category_list[$i] .")"; 
      }
      $sql .= $str;
      $this->dba->exec( $sql );
    }
}
?>
