<?php
class role
{
    var $dba;
    var $p;
    var $id;
    var $name;
    var $description;
    var $users;

    function role( $dba, $id )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();
        $this->id  = $id;
        $this->getRole();
    }
    function getRole()
    {
        $sql = "SELECT
                    name,
                    description
                FROM
                    ".$this->p."role
                WHERE
                    id=".$this->id;
        $result = $this->dba->exec($sql);
        $record = $this->dba->fetchArray( $result );

        $this->name = stripslashes ( $record["name"] );
        $this->description = stripslashes ( $record["description"] );
    }
    function setName( $name )
    {
        if( !trim( $name ) ) return;
        $this->name = trim( $name );

        $sql = "UPDATE
                    ".$this->p."role
                SET
                    name = '". trim( addslashes( $name ) ) ."'
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }

    function setDescription( $description )
    {
        if( !trim( $description ) ) return;
        $this->description = trim( $description );

        $sql = "UPDATE
                    ".$this->p."role
                SET
                    description = '". trim( addslashes( $description ) ) ."'
                WHERE
                    id   = ". $this->id;

        $this->dba->exec( $sql );
    }
    function setUser( $users )
    {
        $this->users = $users;

        //remove all the current users for this role
        $sql = "DELETE FROM
                    ".$this->p."user2role
                WHERE
                    role = ".$this->id;
        $this->dba->exec( $sql );

        if( !array_sum( $users ) ) return;
        for( $i = 0; $i < count( $this->users ); $i++ )
        {
            $sql = "INSERT INTO
                        ".$this->p."user2role
                    (
                        role,
                        user
                    )
                    VALUES
                    (
                        ".$this->id.",
                        ".$this->users[$i]."
                    )";
            $this->dba->exec( $sql );
        }
    }


}
?>
