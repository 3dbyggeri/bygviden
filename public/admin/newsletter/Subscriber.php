<?php
class subscriber
{
    var $dba;
    var $p;
    var $id;
    var $table;

    function subscriber( $dba )
    {
      $this->dba = $dba;
      $this->p   = $this->dba->getPrefix();
      $this->table = $this->p.'subscriber';
    }
    function addSelf($email)
    {
        $sql = "INSERT INTO ". $this->table ." ( email,active,subscribed ) VALUES ('". $email."','n',NOW())";
        $this->dba->exec( $sql );
        //send confirmation mail...
        return $this->dba->last_inserted_id();
    }
    function add($email)
    {
      $sql = "INSERT INTO ". $this->table ." ( email,active,subscribed ) VALUES ('". $email."','y',NOW())";
      $this->dba->exec( $sql );
      return $this->dba->last_inserted_id();
    }
    function update($id,$email)
    {
        $this->dba->exec("update ".$this->table." set email='". $email."' where id='".$id."'");
    }
    function remove($id)
    {
        $this->dba->exec("DELETE FROM ".$this->table." WHERE id=".$id);
    }
    function total()
    {
        return $this->dba->singleQuery($sql ="SELECT COUNT(*) FROM ".$this->table." WHERE active='y'");
    }
    function all( $offset = 0, $row_number = 20, $sorting_order ='asc' , $sorting_colum='id')
    {
        $r = array();
        $sql ="SELECT 
                    *
              FROM
                ". $this->table ."
              ORDER BY 
                $sorting_colum $sorting_order
              LIMIT $offset, $row_number";
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $r[$i] = $this->dba->fetchArray( $result );
       }
       return $r;
    }
    function find($email)
    {
      $r = array();
      $sql = "SELECT * FROM ". $this->table ." WHERE email LIKE '%".$search."%' ";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
         $r[$i] = $this->dba->fetchArray( $result );
      }
      return $r;
    }
    function setActive( $id,$state)
    {
      $sql = "UPDATE 
                ". $this->table ."
              SET
                active = '". $state ."'
              WHERE
                id = ". $id; 
      $this->dba->exec( $sql );
    }


 }
?>
