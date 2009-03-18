<?php
class roles
{
    var $dba;
    var $p;
    var $roleList;
    var $itemList;
    function roles( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
    }
    function getRoles()
    {
        $sql = "SELECT
                    id,
                    name,
                    description
                FROM
                    ".$this->p."role";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ ) 
        {
            $this->roleList[ count( $this->roleList ) ] = $this->dba->fetchArray( $result );
						$this->roleList[ count( $this->roleList ) - 1 ]["name"] = stripslashes ( $this->roleList[ count( $this->roleList ) - 1 ]["name"] );
						$this->roleList[ count( $this->roleList ) - 1 ]["description"] = stripslashes ( $this->roleList[ count( $this->roleList ) - 1 ]["description"] );
        }
        return $this->roleList;            
    }
    function user2roles( $id = 0 )
    {
        $sql = "SELECT 
                    user.id                AS 'id',
                    user.name              AS 'name',
                    user2role.role         AS 'selected'
                FROM 
                    ".$this->p."a_user AS user 
                LEFT JOIN 
                    ".$this->p."user2role  AS user2role 
                ON
                    user2role.user = user.id
                AND 
                    user2role.role = $id ";
        
       $result = $this->dba->exec( $sql );
       $n      = $this->dba->getN( $result );
       for( $i = 0; $i < $n; $i++ )
       {
          $this->itemList[count($this->itemList)] = $this->dba->fetchArray( $result );
					$this->itemList[ count( $this->itemList ) - 1 ]["name"] = stripslashes ( $this->itemList[ count( $this->itemList ) - 1 ]["name"] );
       }
       return $this->itemList;
    }
    function addRole()
    {
        $sql = "INSERT INTO
                    ".$this->p."role
                (
                    name
                )
                VALUES
                (
                    'new role'
                )";
        $this->dba->exec( $sql );

        return $this->dba->last_inserted_id();
    }
    function deleteRole( $id )
    {
        if( !is_numeric( $id ) ) return;
        $sql = "DELETE FROM
                    ".$this->p."role
                WHERE
                    id= $id";
        $this->dba->exec( $sql );
    }
}
?>
