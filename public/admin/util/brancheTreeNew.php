<?php
class brancheTree2 extends tree
{
  var $ancestors;
  var $brancher = array();
  var $branche_id;
  var $branche;

  function brancheTree2( $dba, $uid, $branche)
  {
        $this->dba   = $dba;
        $this->uid   = $uid;
        $this->p     = $this->dba->getPrefix();
        $this->table = $this->p .'branche_tree';
        $this->branche = $branche;
        $this->branche_id = $this->currentBrancheId();
  }
  function openElement($id)
  {
    $this->clearState();
    if(!is_numeric($id)) return;

    $this->open($id);
    $path = $this->dba->singleQuery("SELECT path FROM ".$this->table." WHERE id=$id");
    if(!$path) return;
    $path = explode(',',$path);

    for($i=0;$i<count($path);$i++) 
    {
        $this->open($path[$i]);
    }
  }
  
  function currentBrancheId()
  {
    $bs = $this->getBrancher();
    for($i=0;$i<count($bs);$i++)
    {
        if($bs[$i]['name'] == $this->branche) return $bs[$i]['id'];
    }
    return 0;
  }

  function editBygningsElement($parent_id,$element_id)
  {
      if( !$parent_id || !is_numeric( $parent_id ) ) return;
      if( !$element_id || !is_numeric( $element_id) ) return;

      $this->dba->exec("UPDATE ".$this->table." SET element_id=$element_id WHERE id=$parent_id");
  }
  function addBygningsElement( $parent_id,$element_id )
  {
      if( !$parent_id || !is_numeric( $parent_id ) ) return;
      if( !$element_id || !is_numeric( $element_id) ) return;

      //Get the highest position among the comming siblings
      $n = $this->getHighestPositionFromChildrens( $parent_id );
      $n++;

      $parent = $this->dba->singleArray("SELECT * FROM ". $this->table ." WHERE id=$parent_id");
      $branche_id = $parent['branche_id'];
      $level = intval($parent['level']) + 1;
      $path = $parent['path'];
      if($path !='') $path.=',';
      $path.= $parent_id;

      //add the child
      $sql = "INSERT INTO
                  " .$this->table ."
              (
                  parent,
                  element_id,
                  branche_id,
                  position,
                  level,
                  path
              )
              VALUES
              (
                   $parent_id,
                   $element_id,
                   $branche_id,
                   $n,
                   $level,
                   '$path'
              )";
      $this->dba->exec( $sql );
      
      //get the new node id
      $last_id = $this->dba->last_inserted_id();

      //open the parent node
      $this->open( $parent_id );
  }
  function open($id)
  {
      if( !$id || !is_numeric( $id ) ) return;
      $t_name = $this->table .'_state'; 
      $s = $_SESSION[ $t_name ];
      if(!is_array($_SESSION[$t_name])) $_SESSION[$t_name] = array();
      $_SESSION[$t_name][$id] = true;
  }

  function getBrancher()
  {
    if(!$this->brancher)
    {
        $sql = "SELECT id, name, label FROM ". $this->p ."brancher ";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
          $this->brancher[$i] = $this->dba->fetchArray( $result );
        }
    }
    return $this->brancher;
  }
  function getNodes()
  {
    $node = array();
    $node['id']= $this->dba->singleQuery('SELECT id FROM dev_branche_tree WHERE branche_id='.$this->branche_id.' AND parent=0');
    $node['path'] = '';
    $node['level'] = 0;
    $node['name'] = $this->branche;
    $node['element_id'] =0;
    $node['parent'] = 0;
    $node['node'] = TRUE;
    $node['open'] = TRUE;

    $this->nodeArray[ count( $this->nodeArray ) ] = $node;
    $this->level++;
    $this->getChildNodes( $node["id"] );
    $this->level--;
  }
  function getChildNodes( $id = 0 )
  {
      //get a list of the nodes who have childrens
      $nodeList = $this->getNodesWithChildrens( $id );

      $sql = "SELECT
                dev_branche_tree.id AS 'id',
                dev_branche_tree.path AS 'path',
                dev_branche_tree.level AS 'level',
                dev_buildingelements.name AS 'name',
                dev_buildingelements.id AS 'element_id'
             FROM
                dev_branche_tree,
                dev_buildingelements
             WHERE
                dev_branche_tree.parent = $id
             AND
                dev_branche_tree.element_id = dev_buildingelements.id
             ORDER BY
                dev_branche_tree.position";
      
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );

      for( $i = 0; $i < $n; $i++ )
      {
          $node  = $this->dba->fetchArray( $result );
          $node["name"] = stripslashes ( $node["name"] );
          $node["parent"] = $id;
          $node["node"] = ( $nodeList[ $node["id"] ] )? TRUE : FALSE;
          
          //add the open field to the node 
          if( $this->fullOpen )
          {
            $node["open"] = TRUE;
          }
          else
          {
            $node["open"] = ( $this->state[ $node["id"] ]  )? TRUE : FALSE;
          }
          
          //add the indentation field to the node
          $node["level"] = $this->level;

          //add the node to the nodeArray
          $this->nodeArray[ count( $this->nodeArray ) ] = $node;

          if( !$node["open"] ) continue;
          $this->level++;
          $this->getChildNodes( $node["id"] );
          $this->level--;
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
