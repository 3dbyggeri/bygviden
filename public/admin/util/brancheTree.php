<?php
/**
 ** Especialize agent tree
 ** @author Ronald
 **/
class brancheTree extends tree
{
  var $ancestors;

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
                  position
              )
              VALUES
              (
                   'Referance',
                   $id,
                   $n
              )";
      $this->dba->exec( $sql );
      
      //get the new node id
      $last_id = $this->dba->last_inserted_id();

      //open the parent node
      $this->open( $id );
  }
  function getBrancher()
  {
    $sql = "SELECT id, name, label FROM ". $this->p ."brancher ";

    $result = $this->dba->exec( $sql );
    $n      = $this->dba->getN( $result );

    $brancher = Array();
    for( $i = 0; $i < $n; $i++ )
    {
      $brancher[ count( $brancher) ] = $this->dba->fetchArray( $result );
    }
    return $brancher;
  }
  function getNodes( $id = 0 )
  {
      //get a list of the nodes who have childrens
      $nodeList = $this->getNodesWithChildrens( $id );
      
      // **********************************************************
      // Select all the children of the current node
      // order by position
      // **********************************************************
      if( $id > 0 && !stristr($_SERVER['PHP_SELF'],'admin/branchetree/') )
      {
        $sql = "SELECT
                    btree.id         AS 'id',
                    BE.name       AS 'name',
                    btree.element_id AS 'element_id'
                FROM
                    ". $this->table ."  AS btree,
                    ". $this->p ."buildingelements AS BE
                WHERE
                    btree.parent = $id
                AND
                    btree.element_id = BE.id
                ORDER BY
                    btree.position";
      }
      else
      {
        $sql = "SELECT
                    btree.id         AS 'id',
                    btree.name       AS 'name',
                    btree.element_id AS 'element_id'
                FROM
                    ". $this->table ." AS btree
                WHERE
                    btree.parent = $id
                ORDER BY
                    btree.position";
      }

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
          $this->node  = $this->dba->fetchArray( $result );
          $this->node["name"] = stripslashes ( $this->node["name"] );
          $this->node["parent"] = $id;
          $this->node["node"] = ( $nodeList[ $this->node["id"] ] )? TRUE : FALSE;
          
          //add the open field to the node 
          if( $this->fullOpen )
          {
            $this->node["open"] = TRUE;
          }
          else
          {
            $this->node["open"] = ( $this->state[ $this->node["id"] ]  )? TRUE : FALSE;
          }

          //add the indentation field to the node
          $this->node["level"] = $this->level;

          //add the node to the nodeArray
          $this->nodeArray[ count( $this->nodeArray ) ] = $this->node;

          if( $this->node["open"] )
          {
               $this->level++;
               $this->getNodes( $this->node["id"] );
               $this->level--;
          }
      }
  }
  function brancheHop( $element_id )
  {
    # check if the element is there
    $sql = "SELECT
              id
            FROM
              ".$this->table."
            WHERE
              element_id = $element_id
            ORDER BY
              position
            LIMIT 1";
    $id = $this->dba->singleQuery( $sql );
    
    if( !$id ) return;

    #recursively open all nodes up to root
    #first flush the state table
    $this->ancestors = array( $id );
    $this->getAllAncestors( $id );
    #echo "<xmp>";
    #print_r( $this->ancestors );
    #echo "</xmp>";
    $this->openAllAncestors( );

    return $id;
  }
  function getAllAncestors( $id )
  {
    $sql = "SELECT 
              parent
            FROM
              ". $this->table ."
            WHERE
              id = $id";
    $parent = $this->dba->singleQuery( $sql );
    $this->ancestors[ count( $this->ancestors ) ] = $parent;
    if( $parent != 1 ) $this->getAllAncestors( $parent );
  }
  function flushStateTable()
  {
     $sql = "DELETE FROM
              ". $this->table ."_state
            WHERE
              uid = '". $this->uid ."'";
     $this->dba->exec( $sql );
  }
  function openAllAncestors( )
  {
    $sql = "INSERT INTO
              ". $this->table ."_state
            ( 
              id,
              uid,
              time
            )
            VALUES ";

    for( $i = 0; $i < count( $this->ancestors ); $i++ )
    {
      if( $insert ) $insert.= ',';
      $insert.= '('. $this->ancestors[$i] .",'". $this->uid ."',NOW() ) ";
    }

    if( $insert )
    {
      $this->flushStateTable();
      $this->dba->exec( $sql . $insert );
    }
  }
}
