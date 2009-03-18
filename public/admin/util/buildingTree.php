<?php
/**
 ** Especialize agent tree
 ** @author Ronald
 **/
class buildingTree extends tree
{
  function add( $id )
  {
      global $user;
      if( !$id || !is_numeric( $id ) ) return;

      //Get the highest position among the comming siblings
      $n = $this->getHighestPositionFromChildrens( $id );
      $n++;

      //add the child
      $sql = "INSERT INTO
                  " .$this->table ."
              (
                  name,
                  parent,
                  position,
                  creator,
                  created,
                  edited
              )
              VALUES
              (
                  'Untitled',
                   $id,
                   $n,
                   ". $user->id .",
                   NOW(),
                   NOW()
              )";
      $this->dba->exec( $sql );
      
      //get the new node id
      $last_id = $this->dba->last_inserted_id();

      //open the parent node
      $this->open( $id );
  }
}
