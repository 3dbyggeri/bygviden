<?php
/**
 ** Especialize kilde tree
 ** @author Ronald
 **/
class kildeTree extends tree
{
    /**
     * Create a new node under the supplied node,
     * the new node will inherit the properties of the parent node
     * @param id int Parent node
     * @returns void
     */
    var $types = array('root'=>'leverandor','folder'=>'leverandor','leverandor'=>'kategori','kategori'=>'publikation' );
    
    function addFolder( $id )
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
                    element_type
                )
                VALUES
                (
                     'Folder $n',
                     $id,
                     $n,
                     ". $user->id .",
                     NOW(),
                     NOW(),
                     'folder'
                )";
        $this->dba->exec( $sql );
        
        //get the new node id
        $last_id = $this->dba->last_inserted_id();

        //open the parent node
        $this->open( $id );

        //inherit the settings from the parent
        $this->inheritParentProperties( $id, $last_id );
    }
    function inheritParentProperties( $id, $last_id )
    {
        //Inherit settings from parent 
        $sql = "SELECT
                  timepublish,
                  timeunpublish,
                  crawling,
                  brugsbetingelser,
                  betaling,
                  enkelt_betaling,
                  abonament_betaling,
                  enkelt_pris,
                  abonament_pris,
                  abonament_periode
                FROM 
                  ". $this->table ."
                WHERE
                  id=$id";
        $prop = $this->dba->singleArray( $sql );

        $prop["timeunpublish"] = ( $prop["timeunpublish"] )? "'".$prop["timeunpublish"]."'":"NULL"; 
        $prop["timepublish"] = ( $prop["timepublish"] )? "'".$prop["timepublish"]."'":"NULL"; 

        $sql = "UPDATE ".$this->table ."
                SET
                  timepublish=". $prop["timepublish"] .",
                  timeunpublish=". $prop["timeunpublish"] .",
                  crawling = '". $prop['crawling'] ."',
                  brugsbetingelser = '". $prop['brugsbetingelser'] ."',
                  betaling = '". $prop['betaling'] ."',
                  enkelt_betaling = '". $prop['enkelt_betaling'] ."',
                  abonament_betaling = '". $prop['abonament_betaling'] ."',
                  enkelt_pris = ". $prop['enkelt_pris'] .",
                  abonament_pris = ". $prop['abonament_pris'] .",
                  abonament_periode = ". $prop['abonament_periode'] ."
                WHERE
                  id=$last_id";
        $this->dba->exec( $sql );
    }
    
    function add( $id , $type = '')
    {
        global $user;
        if( !$id || !is_numeric( $id ) ) return;
        if( !trim( $type ) ) return;
        $type = $this->types[ $type ];


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
                    element_type
                )
                VALUES
                (
                     '$type $n',
                     $id,
                     $n,
                     ". $user->id .",
                     NOW(),
                     NOW(),
                     '$type'
                )";
        $this->dba->exec( $sql );
        
        //get the new node id
        $last_id = $this->dba->last_inserted_id();

        //open the parent node
        $this->open( $id );

        //inherit the settings from the parent
        $this->inheritParentProperties( $id, $last_id );
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
                    name      AS 'name',
                    element_type AS 'type'
                FROM
                    ". $this->table ."
                WHERE
                    parent = $id
                ORDER BY ";
        $sql.= ( $_SESSION['sort_order'] == 'alfabetical' )?'name':'position';

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
            if( $this->state[ $where ] )
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

                //open the parent node
                $this->open( $where );
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
    function getSpiders()
    {
      $sql = "SELECT 
                id,
                kilde_url,
                log_in,
                log_name,
                log_password,
                log_domain,
                db,
                forbidden_words,
                required_words,
                crawling_depth,
                crawling_cuantitie,
                parent
              FROM
                ". $this->table ."
              WHERE
                element_type = 'publikation'
              AND
                crawling = 'y'";

        $spiders = array();
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ ) 
        {
          $rec = $this->dba->fetchArray( $result );
          $rec['brancher'] =  $this->getBrancheRelevans( $rec['id'] );
          $spiders[ count( $spiders ) ] = $rec;
        }
        return $spiders;
    }
    function getBrancheRelevans( $pubId )
    {
      $sql = "SELECT branche_id FROM ". $this->p."branche_relevans WHERE publikations_id = ". $pubId;
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      
      $brancher = array();
      for( $i = 0; $i < $n; $i++ ) 
      {
        $rec = $this->dba->fetchArray( $result );
        $brancher[$i] = $rec[0];
      }

      return $brancher;
    }
    function getBrancher()
    {
      $sql ="SELECT id, name FROM ". $this->p. "brancher";
      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      
      $brancher = array();
      for( $i = 0; $i < $n; $i++ ) $brancher[$i] = $this->dba->fetchArray( $result );

      return $brancher;
    }
    function getBundetPublikationer( &$publications, &$videnslev_category )
    {
      $sql = "SELECT
                k.id                 AS kildeid,
                k.name               AS name,
                k.logo_url           AS logo_url,
                k.description        AS description,
                k.observation        AS observation,
                k.forlag_url         AS kilde_forlag,

                k.betaling           AS betaling,
                k.enkelt_betaling    AS enkelt_betaling,
                k.abonament_betaling AS abonament_betaling,
                k.overrule_betaling  AS overrule,

                k.digital_udgave     AS digital_udgave,
                k.brugsbetingelser   AS brugsbetingelser,
                k.betegnelse         AS betegnelse,

                kb.id         AS kilde_kategory_id,
                kb.name       AS kilde_kategory_name,
                kb.logo_url   AS kilde_kategory_logo,
                kb.forlag_url AS kilde_kategory_forlag,
                kb.betegnelse AS kilde_kategory_betegnelse,

                kb.betaling           AS cat_betaling,
                kb.enkelt_betaling    AS cat_enkelt_betaling,
                kb.abonament_betaling AS cat_abonament_betaling,
                kb.overrule_betaling  AS cat_overrule,

                kc.id         AS kilde_leverandor_id,
                kc.name       AS kilde_leverandor_name,
                kc.logo_url   AS kilde_leverandor_logo,
                kc.forlag_url AS kilde_leverandor_forlag,
                kc.betegnelse AS kilde_leverandor_betegnelse,

                kc.betaling           AS lev_betaling,
                kc.enkelt_betaling    AS lev_enkelt_betaling,
                kc.abonament_betaling AS lev_abonament_betaling,
                kc.overrule_betaling  AS lev_overrule

              FROM
                ". $this->p ."kildestyring as k,
                ". $this->p ."kildestyring as kb,
                ". $this->p ."kildestyring as kc
              WHERE
                k.parent = kb.id
              AND
                kb.parent = kc.id
              AND
                k.indholdsfortegnelse = 'y'
              AND
                  ( k.timepublish < NOW() OR k.timepublish IS NULL )
              AND
                  ( k.timeunpublish > NOW() OR k.timeunpublish IS NULL ) 
              ORDER BY
                kc.name,kb.position,k.position ";

      $result = $this->dba->exec( $sql );
      $n      = $this->dba->getN( $result );
      
      //group by videns leverandor and kategory
      for( $i = 0; $i < $n; $i++ ) 
      {
        $rec = $this->dba->fetchArray( $result );
        $m = count( $publications[ $rec['kilde_leverandor_id'] ][ $rec['kilde_kategory_id'] ] ); 
        $publications[ $rec['kilde_leverandor_id'] ][ $rec['kilde_kategory_id'] ][$m] = $rec;

        $lev_betegnelse =($rec['kilde_leverandor_betegnelse'])?$rec['kilde_leverandor_betegnelse']:'Vidensleverandor';
        $lev_navn       = $rec['kilde_leverandor_name'];

        $cat_betegnelse =($rec['kilde_kategory_betegnelse'])?$rec['kilde_kategory_betegnelse']:'Kategori';
        $cat_navn       = $rec['kilde_kategory_name'];

        $videnslev_category['lev_'.$rec['kilde_leverandor_id']]['betegnelse'] = $lev_betegnelse;
        $videnslev_category['lev_'.$rec['kilde_leverandor_id']]['navn'] = $lev_navn;
        $videnslev_category['kat_'.$rec['kilde_kategory_id']]['betegnelse'] = $cat_betegnelse;
        $videnslev_category['kat_'.$rec['kilde_kategory_id']]['navn'] = $cat_navn;
      }
    }
}
