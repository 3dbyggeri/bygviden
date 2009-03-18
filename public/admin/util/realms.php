<?php
class realm
{
    var $dba;
    var $p;
    var $realms;
    var $realms4role;
    var $realm;
    var $role;

    function realm( $dba, $role, $realm )
    {
        $this->dba   = $dba;
        $this->p     = $dba->getPrefix();
        $this->realm = $realm;
        $this->role  = $role;
    }
    function getRealms()
    {
        $sql = "SELECT
                    id,
                    name
                FROM
                    ".$this->p."realms";
        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $this->realms[ $i ] = $this->dba->fetchArray( $result );
        }
        return $this->realms;
    }
    function getDocConstrainsForRoleAndRealm( )
    {
        $sql = "SELECT
                    doc
                FROM
                    ".$this->p."roles_constrains
                WHERE
                    role = ". $this->role ."
                AND
                    realm = ". $this->realm ;

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $doc = $this->dba->fetchArray( $result );
            $this->realms4role[ $doc["doc"] ] = 1;
        }
        return $this->realms4role;
    }
    function toogleConstrainsForDoc( $docId )
    {
        if( !$docId ) return;

        $sql = "DELETE FROM
                    ".$this->p."roles_constrains
                WHERE
                    role = ". $this->role ."
                AND
                    realm = ". $this->realm ."
                AND
                    doc = ". $docId;
        $this->dba->exec( $sql );
        
        if( $this->dba->affectedRows() ) 
        {
            $this->recourseRemoveConstrain( $docId );
            return;
        }
        
        $sql = "INSERT INTO
                    ".$this->p."roles_constrains
                (
                    role,
                    realm,
                    doc
                )
                VALUES
                (
                    ". $this->role .",
                    ". $this->realm .",
                    ". $docId ."
                )";
        $this->dba->exec( $sql );
        $this->recourseAddConstrain( $docId );
    }
    function recourseAddConstrain( $docId )
    {
        if( !$docId ) return;
        $sql = "SELECT 
                    id
                FROM
                    ".$this->p."tree
                WHERE
                    parent = $docId";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i  < $n; $i++ )
        {
            $rec = $this->dba->getRecord( $result );

            $sql = "INSERT INTO
                        ".$this->p."roles_constrains
                    (
                        role,
                        realm,
                        doc
                    )
                    VALUES
                    (
                        ". $this->role .",
                        ". $this->realm .",
                        ". $rec[0] ."
                    )";
            $this->dba->exec( $sql );
            $this->recourseAddConstrain( $rec[0] );
        }
    }
    function recourseRemoveConstrain( $docId )
    {
        if( !$docId ) return;
        $sql = "SELECT 
                    id
                FROM
                    ".$this->p."tree
                WHERE
                    parent = $docId";

        $result = $this->dba->exec( $sql );
        $n      = $this->dba->getN( $result );
        for( $i = 0; $i  < $n; $i++ )
        {
            $rec = $this->dba->getRecord( $result );

            $sql = "DELETE FROM
                        ".$this->p."roles_constrains
                    WHERE
                        role = ". $this->role ."
                    AND
                        realm = ". $this->realm ."
                    AND
                        doc = ". $rec[0]; 
            $this->dba->exec( $sql );
            $this->recourseRemoveConstrain( $rec[0] );
        }
    }
}
?>
