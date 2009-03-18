<?php
class users
{
    var $dba;
    var $p;
    var $list;

    function users( $dba )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
    }
    function getUsers( )
    {
        $sql = "SELECT
                    id,
                    name,
                    full_name
                FROM
                    ".$this->p."a_user";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );

        for( $i = 0; $i < $n; $i++ )
				{
					 $this->list[ count( $this->list ) ] = $this->dba->fetchArray( $result );
					 $this->list[ count( $this->list ) - 1 ]["name"] = stripslashes ( $this->list[ count( $this->list ) - 1 ]["name"] );
					 $this->list[ count( $this->list ) - 1 ]["full_name"] = stripslashes ( $this->list[ count( $this->list ) - 1 ]["full_name"] );
				}

        return $this->list;
    }
    function addUser( )
    {
        $sql= "INSERT INTO 
                ".$this->p."a_user
               ( 
                    name,
                    password,
                    language,
                    warning
                )
                VALUES
                (
                    'New user',
                    'change password',
                    'uk',
                    1
                )";
        $this->dba->exec( $sql );
        $new_id = $this->dba->last_inserted_id();

        //add user to admin role 
        $sql = "INSERT INTO
                    ".$this->p."user2role
                (
                    role,
                    user
                )
                VALUES
                (
                    1,
                    $new_id
                )";
        $this->dba->exec( $sql );
        return $new_id;
    }
    function deleteUser( $id )
    {
        if( !$id || !is_numeric( $id ) ) return false;

        $sql = "DELETE FROM
                    ".$this->p."a_user
                WHERE
                    id=$id";
        $this->dba->exec( $sql );
        return true;
    }
}
?>
