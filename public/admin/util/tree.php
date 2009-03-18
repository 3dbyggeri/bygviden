<?php
/**
 * Class which maps a relational table into a hierarchichal tree representation
 * @author Ronald
 */
class tree
{
    /** 
     * Connection object and database abstraction layer
     * @type dba 
     */
    var $dba;

    /** 
     * Prefix for tables in the current installation
     * @type String
     */
    var $p;

    /**
     * Name of the relational table to be mapped
     * @type String
     */
    var $table;

    /**
     * Array of open nodes specific to the current session
     * @type intArray
     */
    var $state;

    /** 
     * Unique session identifier for user
     * @type String
     */
    var $uid;

    /**
     * Current node
     * @type mixedArray
     */
    var $node;

    /**
     * Current moving node
     * @type int
     */
    var $movingNode;

    /**
     * Flag for node movement
     * @type boolean
     */
    var $isMoving = false;

    /**
     * Array of nodes, each node contains the following properties:
     * <ul>
     * <li>int id</li>
     * <li>int level</li>
     * <li>int parent</li>
     * <li>String name</li>
     * <li>boolean open</li>
     * <li>boolean node ( has children )</li>
     * <li>boolean moving</li>
     * </ul>
     * @type mixedArray
     */
    var $nodeArray;

    /**
     * Current hierarchy level
     * @type int
     */
    var $level= 0;

    /**
     * Flag to create a full expanded tree
     * @type boolean
     */
    var $fullOpen = false;

    /**
     * tree constructor
     * @public
     * @param dba object Database abstraction layer
     * @param uid String Unique session identifier for user
     * @param tableName String 
     */
    function tree ( $dba, $uid ,$tableName = 'tree' )
    {
        $this->dba   = $dba;
        $this->uid   = $uid;
        $this->p     = $this->dba->getPrefix();
        $this->table = $this->p . $tableName;
    }

    /**
     * Generate an array of nodes
     * @public
     * @returns mixedArray
     */
    function getNodeArray()
    {
        $this->getState();
        $this->getNodes();
        
        return $this->nodeArray;
    }

    /**
     * Change the node name to the new supplied name
     * @public
     * @param id int Node to be renamed 
     * @param newname String New name for node
     * @returns void
     */
    function rename( $id, $newname )
    {
        if( !$id || !is_numeric( $id ) ) return;
        if( !trim( $newname ) ) return $id;

        $sql = "UPDATE
                    ".$this->table."
                SET
                    name = '". addslashes( trim( $newname ) ). "'
                WHERE 
                    id = $id";
        $this->dba->exec( $sql );
    }

    /**
     * Move a tree node to a position relative to the second node id,
     * if the second node is open, move the first node inside the second node,
     * if the second node is closed, move the first node after the second node
     * @public
     * @param id  int Node to be moved
     * @param where int Move the first node after this one, or as a child to this node
     * @returns void
     **/
    function move ( $id, $where )
    {
        if( !$id || !is_numeric( $id ) ) return;
        if( !$where ) 
        {
            $this->movingNode = $id;
            return $id;
        }
        else
        {
            $this->getState();
            //is where open or closed? ( move in or move after )
            if( $this->state[ $where ]  )
            {
                //this node is open ( move the node in ),
                //set the selected node parent to be the id of 'where'
                $n = $this->getHighestPositionFromChildrens( $where );
                $n++;
                $sql = "UPDATE
                            ". $this->table ."
                        SET
                            parent   = $where,
                            position = $n
                        WHERE 
                            id = $id";
                $this->dba->exec( $sql );
            }
            else
            {
                //this node is close move the node after the 'where' node
                //start by getting the where node's parent id
                $parent = $this->getParent( $where );
                
                //this stops the root node to be selected 
                if( !$parent ) return;

                //get all the childrens order by their current position
                $sql = "SELECT 
                            id
                        FROM
                            ". $this->table ."
                        WHERE
                            parent = $parent
                        ORDER BY
                            position";
                $result = $this->dba->exec( $sql );
                $n      = $this->dba->getN( $result );
                $counter = 0;

                //*******************************************************************************
                //  loop trough the childrens,
                //  if the current node id is not the same as the moving node id
                //  update the node's position according to the counter and increase the counter
                //  if the node id is the same as the 'where to move after' node,
                //  increase the counter and update the moving node's id to the have
                //  the current counter value
                //*******************************************************************************
                for( $i = 0; $i < $n; $i++ )
                {
                    $record = $this->dba->getRecord( $result );
                    $counter++;
                    
                    if( $record[0] != $id )
                    {
                      $sql = "UPDATE
                                  ". $this->table ."
                              SET
                                  position = $counter
                              WHERE 
                                  id = ". $record[0];
                      $this->dba->exec( $sql );
                    }

                    if( $record[0] == $where )
                    {
                        $counter++;
                        $sql = "UPDATE
                                    ". $this->table ."
                                SET
                                    parent   = $parent,
                                    position = $counter
                                WHERE 
                                    id = $id";
                        $this->dba->exec( $sql );
                    }
                }
            }
        }
    }

    /**
     *  Build a list of node id's containg childrens of the 
     *  current node, which have childrens as well
     *  by doing a hairy "self inflected join"
     *  We use the data from this list to display the nodes disclosure
     *  triangle as black ( if they have childrens ) or gray
     *  @private
     *  @param id int 
     *  @returns intArray
     */
    function getNodesWithChildrens( $id )
    {
      $nodeList = array();

        $sql = "SELECT 
                    t1.id
                FROM 
                    ". $this->table ." AS t1,
                    ". $this->table ." AS t2
                WHERE 
                    t1.parent = $id 
                AND
                    t2.parent = t1.id
                GROUP BY t1.id";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
           $record = $this->dba->getRecord( $result );
           $nodeList[ $record[0] ] = TRUE;
        }
      
      return $nodeList;
    }

    /**
     * Add all the childrens of the parent node to the nodesArray
     * If a children node is open, call this method again
     * with the child id as parameter
     * @public
     * @param id int Parent node
     * @returns void
     */
    function getNodes( $id = 0 )
    {
        //get a list of the nodes who have childrens
        $nodeList = $this->getNodesWithChildrens( $id );
        
        // **********************************************************
        // Select all the children of the current node
        // order by position
        // **********************************************************
        $sql = "SELECT
                    id        AS 'id',
                    name      AS 'name'
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id
                ORDER BY
                    position";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->node  = $this->dba->fetchArray( $result );
            $this->node["name"] = stripslashes ( $this->node["name"] );
            $this->node["parent"] = $id;
            //add the node field to the node
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

            //set the moving flag
            if( $this->node["id"] == $this->movingNode ) 
            {
               $this->node["moving"] = TRUE;
               $this->isMoving =  TRUE; 
               $tempNode = $this->node["id"];
            }
            else
            {
               $this->node["moving"] = ( $this->isMoving )? TRUE : FALSE;
            }

            //add the node to the nodeArray
            $this->nodeArray[ count( $this->nodeArray ) ] = $this->node;

            if( $this->node["open"] )
            {
                 $this->level++;
                 $this->getNodes( $this->node["id"] );
                 $this->level--;
            }

            if( $tempNode == $this->movingNode ) $this->isMoving =  FALSE; 
        }
    }

    /**
     * Create a new node under the supplied node,
     * the new node will inherit the properties of the parent node
     * @param id int Parent node
     * @returns void
     */
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
                    edited,
                    news
                )
                VALUES
                (
                    'Untitled',
                     $id,
                     $n,
                     ". $user->id .",
                     NOW(),
                     NOW(),
                     'n'
                )";
        $this->dba->exec( $sql );
        
        //get the new node id
        $last_id = $this->dba->last_inserted_id();

        //open the parent node
        $this->open( $id );

        //Inherit all end user role contreains
        $this->copyEndUserRoleConstrains( $id, $last_id );

        //Inherit all user role constrains
        $this->copyUserRoleConstrains( $id, $last_id );

        //Inherit settings from parent 
        $sql = "SELECT
                  nav,
                  timepublish,
                  timeunpublish,
                  template,
                  layout
                FROM 
                  ". $this->table ."
                WHERE
                  id=$id";
        $prop = $this->dba->singleArray( $sql );

        $prop["nav"] = ( $prop["nav"] )? $prop["nav"]:0;
        $prop["timeunpublish"] = ( $prop["timeunpublish"] )? "'".$prop["timeunpublish"]."'":"NULL"; 
        $prop["timepublish"] = ( $prop["timepublish"] )? "'".$prop["timepublish"]."'":"NULL"; 
        $prop["template"] = ( $prop["template"] )? "'".$prop["template"]."'":"NULL"; 
        $prop["layout"] = ( $prop["layout"] )? "'".$prop["layout"]."'":"NULL"; 

        $sql = "UPDATE ".$this->table ."
                SET
                  nav=". $prop["nav"] .",
                  timepublish=". $prop["timepublish"] .",
                  timeunpublish=". $prop["timeunpublish"] .",
                  template =". $prop["template"] .",
                  layout = ". $prop["layout"] ."
                WHERE
                  id=$last_id";
        $this->dba->exec( $sql );
    }

    /**
     * Set the state of a node to open
     * @private
     * @param id int Node to open
     * @returns void
     */
    function open( $id )
    {
        $sql = "SELECT id FROM
                    ". $this->table ."_state
                WHERE
                    id = $id
                AND
                    uid  = '". $this->uid ."'";
        if( $this->dba->singleQuery( $sql )  ) return;

       $sql = "INSERT INTO

                ". $this->table ."_state
               (
                    id,
                    uid,
                    time
               )
               VALUES
               (
                    $id,
                    '".$this->uid."',
                    NOW()
               )";
        $this->dba->exec( $sql );
    }

    /**
     * Toggle the state of the node ( open - close )
     * @public
     * @param id int Node to toggle
     * @returns void
     */
    function toggle( $id )
    {
      if( !$id || !is_numeric( $id ) ) return;
      $t_name = $this->table .'_state'; 
      $s = $_SESSION[ $t_name ];
      if(!is_array($_SESSION[$t_name])) $_SESSION[$t_name] = array();

      if($_SESSION[$t_name][$id])
      {
        unset($_SESSION[$t_name][$id]);
      }
      else
      {
        $_SESSION[$t_name][$id] = true;
      }
      return;

      $state = ($s)? explode(',',$s):array();

        if(in_array($id))
        {
          $t = array();
          for($i=0;$i<count($state);$i++) if($state[$i]!= $id) $t[$i]=$state[$i];
          $state = $t;
        }
        else
        {
          array_push($state,$id);
        }
        $_SESSION[$this->table.'_state'] = implode(',',$state);

        /*

        $sql = "DELETE FROM 
                    ". $this->table ."_state
                WHERE
                    id = $id
                AND
                    uid  = '". $this->uid ."'";
        $this->dba->exec( $sql );

        $deleted = $this->dba->affectedRows();

        //the node wasn't there, so insert it
        if( !$deleted )
        {
           $sql = "INSERT INTO
                    ". $this->table ."_state
                   (
                        id,
                        uid,
                        time
                   )
                   VALUES
                   (
                        $id,
                        '".$this->uid."',
                        NOW()
                   )";
            $this->dba->exec( $sql );
        }
        */
    }
   
    /**
     * Duplicate a node and all it's childrens
     * @public
     * @param id int Node to duplicate
     * @returns void
     */
    function duplicate( $id )
    {
        if( !$id || !is_numeric( $id ) ) return;
        $this->duplicating( $id, $this->getParent( $id ), TRUE );
    }

    /**
     * Remove a node and all it's childrens
     * @public
     * @param id int Node to remove
     * @returns void
     */
    function remove( $id )
    {
        if( !$id || !is_numeric( $id ) ) return;

        //recourse and remove nodes beneath this one 
        $this->removing( $id );

        //now remove the node
        $sql = "DELETE FROM 
                    ". $this->table ."
                WHERE
                    id = $id";
        $this->dba->exec( $sql );
    }
    
    /**
     * Get the highest position among the childrens of a node
     * @private
     * @param parent int Parent node
     * @returns int
     */
    function getHighestPositionFromChildrens( $parent )
    {
        $sql = "SELECT 
                    MAX( position )
                FROM 
                    ". $this->table ."
                WHERE 
                    parent = $parent";
        return $this->dba->singleQuery( $sql );
    }
   
    /**
     * Duplicate a node and all it's childrens
     * @private
     * @param id int Node to be duplicated
     * @param parent int Parent node
     * @param rename boolean Rename the new node to 'originalname_copy'
     * @returns void 
     */
    function duplicating( $id, $parent, $rename = false )
    {
        if( !$id || !$parent ) return;
        $sql = "SELECT 
                    * 
                FROM
                    ".$this->table."
                WHERE
                    id = $id";
        $record = $this->dba->singleArray( $sql );
        
        if( !$record ) return;
        
        //loop trough the field of the record and insert the values
        foreach( $record as $key => $value)
        {
            if( !is_numeric( $key ) && $key!="id" ) 
            {
                if( $fields ) $fields.=",";
                $fields.= $key;
                
                if( $values ) $values.= ",";
                if( $key == 'parent' ) 
                {
                    $values.= $parent;
                }
                else
                {
                    if( !is_numeric( $value ) )
                    {
                        if( $rename && $key == "name" ) $value.='_copy';

                        //this is a null value
                        if( !$value ) $values.= "NULL";
                        else $values.= "'". addslashes( $value ) ."'";

                    }
                    else
                    {
                        $values.= $value;
                    }
                }
            }
        }

        $sql = "INSERT INTO
                    ".$this->table."
                ( 
                    $fields 
                )
                VALUES
                (
                    $values
                )";
        
        $this->dba->exec( $sql );

        $last_id = $this->dba->last_inserted_id();

        //duplicate the user roles restrictions
        #$this->copyUserRoleConstrains( $id, $last_id );

        //duplicate the end users roles restrictions
        #$this->copyEndUserRoleConstrains( $id, $last_id );

        //loop trought the childs of the item and duplicate them as well
        $sql = "SELECT
                    id
                FROM
                    ".$this->table."
                WHERE
                    parent = $id";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->getRecord( $result );
            $this->duplicating( $record[0], $last_id );
        }
    }

    /**
     * Copy the end user role constrain from a node to another
     * @private
     * @param from int 
     * @param to int 
     * @returns void
     */
    function copyEndUserRoleConstrains( $from, $to )
    {
        $sql = "SELECT
                  role
                FROM
                  ".$this->p."end_user_role_constrains
                WHERE
                  doc = ". $from;
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i< $n; $i++ )
        {
          $record = $this->dba->getRecord( $result );
          $sql = "INSERT INTO 
                    ".$this->p."end_user_role_constrains
                  ( 
                    role,
                    doc 
                  )
                  VALUES
                  ( 
                    ". $record[0] .",
                    $to 
                  )";
          $this->dba->exec( $sql );
        }
    }

    /**
     * Copy the user role constrain from a node to another
     * @private
     * @param from int 
     * @param to int 
     * @returns void
     */
    function copyUserRoleConstrains( $from, $to )
    {
        $sql = "SELECT
                  role,
                  realm
                FROM
                  ".$this->p."roles_constrains
                WHERE
                  doc=". $from;

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i< $n; $i++ )
        {
          $record = $this->dba->getRecord( $result );
          $sql = "INSERT INTO 
                    ".$this->p."roles_constrains
                  ( 
                    role,
                    doc,
                    realm
                  )
                  VALUES
                  ( 
                    ". $record[0] .",
                    $to,
                    ". $record[1] ." 
                  )";
          $this->dba->exec( $sql );
        }
    }

    /**
     * Find the parent id for a node
     * @private
     * @param id int Node who's parent is to be retrieved
     * @returns int
     */
    function getParent( $id )
    {
        //get the nodes parent
        $sql ="SELECT
                    parent
               FROM
                    ".$this->table."
                WHERE
                    id = $id";
        
        return $this->dba->singleQuery( $sql );
    }

    /**
     * Recoursively emove all childrens of a node 
     * @private
     * @param id int Node to be removed
     * @returns void
     */
    function removing( $id )
    {
        if( !$id ) return;

        $sql = "SELECT
                    id
                FROM
                    ".$this->table."
                WHERE
                    parent = $id";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->getRecord( $result );
            $this->removing( $record[0] );
        }

        //delete all the childrens
        $sql = "DELETE FROM
                    ".$this->table."
                WHERE
                    parent = $id";
        $this->dba->exec( $sql );
    }

    /**
     * Build array of open tree specific to the current user session
     * where the array key is an open node id
     * @private
     * @returns void
     */
    function getState ( )
    {
        $s = $_SESSION[$this->table.'_state'];
        //$this->state = ($s)? explode(',',$s):array();
        if(!is_array($_SESSION[$this->table.'_state'])) $_SESSION[$this->table.'_state'] = array();
        $this->state = $_SESSION[$this->table.'_state'];
        $this->state[1] = true;
        /*
        $sql = "SELECT
                    id
                FROM
                    ".$this->table."_state
                WHERE
                    uid = '".$this->uid ."'";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->getRecord( $result );
            $this->state[ $record[0] ] = true;
        }
        $this->state[ 1 ] = true;
        */
    }
    function clearState()
    {
      $_SESSION[$this->table.'_state'] = array();
      /* 
      $sql = "DELETE FROM ". $this->table."_state WHERE uid='". $this->uid ."'";
      $this->dba->exec( $sql );
      */
    }
    function displayElement( $nodeId )
    {
      if( !is_numeric( $nodeId ) ) return;
      $this->clearState();
      //get the id of the element_id
      //$nodeId = $this->dba->singleQuery("SELECT id FROM ". $this->table ." WHERE element_id =$nodeId");
      //if(!$nodeId)return;
      $id_list = array($nodeId);
      $this->buildPath( $nodeId, $id_list ); 
      if( count($id_list)>0 )
      {
         $sql = "INSERT INTO
                  ". $this->table ."_state
                 (
                      id,
                      uid,
                      time
                 )
                 VALUES ";
          for( $i=0;$i<count($id_list);$i++)
          {
              if($values) $values.=',';
              $values.="(". $id_list[$i];
              $values.=",'".$this->uid."',NOW() )";
          }
          
          $sql.= $values;
          $this->dba->exec( $sql );
      }
    }
    function buildPath( $n_id,&$id_list )
    {
      if( !$n_id ) return;
      $sql = "SELECT parent FROM ". $this->table ." WHERE id=$n_id";
      $parent = $this->dba->singleQuery( $sql );
      if( !$parent ) return;
      $id_list[ count( $id_list ) ] = $parent;
      $this->buildPath( $parent,$id_list);
    }
}
?>
