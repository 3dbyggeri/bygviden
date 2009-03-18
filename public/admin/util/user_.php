<?php
/**
 * Class which maps the user table to an object
 * @author Ronald
 */
class user
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
     * Name of the roles constrain table
     * @type String
     */
    var $constrainTable;

    /**
     * Name of the roles table
     * @type String
     */
     var $roleTable;

    /**
     * Name of the user2roles table
     * @type String
     */
     var $user2roleTable;

    /**
     * Name of the realms table ( constrains name's and id )
     * @type String
     */
     var $realmsTable;

    /**
     * Flag. Tells if the user is currently logged
     * @type boolean
     */
    var $logged;

    /**
     * Unique user id and the table primary key
     * @type int
     */
    var $id;

    /**
     * User name
     * @type String
     */
    var $name;

    /**
     * User full name
     * @type String
     */
    var $full_name;

    /**
     * User password ( MD5 crypt )
     * @type String
     */
    var $password;

    /**
     * User mail adress
     * @type String
     */
    var $mail;

    /**
     * User current session identifier
     * @type String
     */
    var $sessid;

    /**
     * User last visited application pane
     * @type String
     */
    var $pane;


    /**
     * Roles for this user ( role name = role id )
     * @type Hashtable
     */
    var $rolesByName;

    /**
     * Roles for this user ( role id= role name  )
     * @type Hashtable
     */
    var $rolesById;

    /**
     * Constrains for this user on a document ( constrain, doc )
     * @type mixedArray
     */
    var $constrains;

    /**
     * Constructor for user class
     * @param dba dba - Database abstraction layer
     * @param id int - Unique user identifier
     */
    function user( $dba, $id = 0 )
    {
        $this->dba = $dba;
        $this->p   = $this->dba->getPrefix();

        //tables used by this class
        $this->table          = $this->p .'a_user';
        $this->constrainTable = $this->p .'roles_constrains';
        $this->roleTable      = $this->p .'role';
        $this->user2roleTable = $this->p .'user2role';
        $this->realmsTable    = $this->p .'realms';


        $this->sessid = session_id();

        if( $id )
        {
            //Retrieve the user's data by id
            $this->id     = $id;
            $this->logged = $this->loadById();
        }
        else
        {
            //Retrieve the user's data by session id
            $this->logged = $this->loadBySessionId();
        }

        //Retrieve the roles for this user
        if( $this->id ) $this->getRoles();
    }

    /**
     * Load the user data using it's id
     * @private
     * @returns boolean
     */
    function loadById( )
    {
        $sql = "SELECT
                    name,
                    full_name,
                    password,
                    mail,
		                pane
                FROM
                    ". $this->table ."
                WHERE
                    id=". $this->id;

        $result = $this->dba->exec( $sql );
        $record = $this->dba->fetchArray( $result );

        if( !$record ) return false;

        $this->name         = stripslashes ( $record["name"] );
        $this->full_name    = stripslashes ( $record["full_name"] );
        $this->password     = stripslashes ( $record["password"] );
        $this->mail         = stripslashes ( $record["mail"] );
	      $this->pane  	      = $record["pane"];

        return true;
    }

    /**
     * Load the user data using the current session id
     * @private
     * @returns boolean
     */
    function loadBySessionId( )
    {
        $sql = "SELECT
                    id,
                    name,
                    full_name,
                    password,
                    mail,
		                pane
                FROM
                    ". $this->table ."
                WHERE
                    sessid = '". $this->sessid ."'";

        $result = $this->dba->exec( $sql );
        $record = $this->dba->fetchArray( $result );

        if( !$record ) return false;

        $this->id           = $record["id"];
        $this->name         = stripslashes ( $record["name"] );
        $this->full_name    = stripslashes ( $record["full_name"] );
        $this->password     = stripslashes ( $record["password"] );
        $this->mail         = stripslashes ( $record["mail"] );
	      $this->pane         = $record["pane"];

        return true;
    }

    /**
     * Authenticate a user base on name and password
     * @param name String - User name
     * @param password String - User password
     * @returns boolean
     */
    function log( $name, $password )
    {
        if( !trim( $name ) )  return false;
        if( !trim( $password ) ) return false;

        $password = md5( $password );

        $sql = "SELECT
                    id
                FROM
                    ". $this->table ."
                WHERE
                    name = '". trim( addslashes( $name ) ) ."'
                AND
                    password = '". trim( addslashes( $password ) ) ."'";

        $id = $this->dba->singleQuery( $sql );

        if( ! $id ) return false;

        //register the user session
        $sql = "UPDATE
                    ". $this->table ."
                SET
                    sessid = '". $this->sessid ."',
                    sessionStart = NOW()
                WHERE
                   id = $id";
        $this->dba->exec( $sql );

        //load the user data
        $this->logged = $this->loadBySessionId();

        return true;
    }

    /**
     * Terminate the user session
     * @returns void
     */
    function logoff( )
    {
        if( !$this->logged ) return;

        $sql = "UPDATE
                    ". $this->table ."
                SET
                    sessid = ''
                WHERE
                   id = ". $this->id;
        $this->dba->exec( $sql );
        $this->logged = false;
    }

    /**
     * Check if the user have been authenticated
     * @returns boolean
     */
    function isLogged( )
    {
        return $this->logged;
    }

    /**
     * Register the pane last visited by the user
     * @param pane String  - Name of the pane
     * @returns void
     */
    function setPane( $pane )
    {
        if( !trim( $pane ) ) return;
        $this->pane = $pane;

        $sql = "UPDATE
                    ".$this->table ."
                SET
                    pane = '". addslashes( trim( $pane ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );
    }

    /**
     * Update the user name
     * @param name String
     * @returns boolean
     */
    function setName( $name )
    {
        if( !trim( $name ) ) return false;
        $this->name = stripslashes ( $name );

        $sql = "UPDATE
                    ". $this->table ."
                SET
                    name = '". addslashes( trim( $name ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );

        return true;
    }

    /**
     * Set the user's full name
     * @param full_name String
     * @returns boolean
     */
    function setFull_name( $full_name )
    {
        if( !trim( $full_name ) ) return false;
        $this->full_name = stripslashes ( $full_name );

        $sql = "UPDATE
                    ". $this->table ."
                SET
                    full_name = '". addslashes( trim( $full_name ) ) ."'
                WHERE
                    id =". $this->id;
        $this->dba->exec( $sql );
        return true;
    }

    /**
     * Sets the user's password
     * @param password String
     * @returns boolean
     */
    function setPassword( $password )
    {
        if( !trim( $password ) ) return false;
        $password = md5( $password );
        $this->password = stripslashes ( $password );

        $sql = "UPDATE
                    ". $this->table ."
                SET
                    password = '". addslashes( trim( $password ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );

        return true;
    }

    /**
     * Sets the user's mail
     * @param mail String
     * @returns boolean
     */
    function setMail( $mail )
    {
        if( !trim( $mail ) ) return false;
        $this->mail = stripslashes ( $mail );

        $sql = "UPDATE
                    ". $this->table ."
                SET
                    mail = '". addslashes( trim( $mail ) ) ."'
                WHERE
                    id = ".$this->id;
        $this->dba->exec( $sql );

        return true;
    }
    /**
     * Retrieve the roles this user is a member of,
     * populate the rolesById and rolesByName hastables
     */
    function getRoles()
    {
        $sql = "SELECT
              r.id,
              r.name
          FROM
              ". $this->p."role       AS r,
              ". $this->p."user2role  AS u2r,
              ". $this->table ."      AS u
          WHERE
              u2r.user = u.id
          AND
              u2r.role = r.id
          AND
              u.id = ".$this->id;

        $result = $this->dba->exec( $sql );
        $n	= $this->dba->getN( $result );
        for( $i = 0; $i < $n; $i++ )
        {
            $record = $this->dba->getRecord( $result );
            $this->rolesById[ $record[0] ] = $record[1];
            $this->rolesByName[ $record[1] ] = $record[0];
        }
    }

    /**
     * Retrieve the constrains for this user on a document
     * ( Key constrain name hold doc id )
     * @param id int - Document unique identifier
     * @returns mixedArray
     */
    function getConstrainsOnDoc( $id )
    {
    	$sql  ="SELECT
              rs.name  as realmName
                  FROM
              ". $this->constrainTable ." AS rc,
              ". $this->roleTable ."      AS r,
              ". $this->user2roleTable ." AS u2r,
              ". $this->realmsTable ."    AS rs
            WHERE
              u2r.user = ". $this->id ."
            AND
              doc = $id
            AND
              u2r.role = r.id
            AND
              rc.role = r.id
            AND
              rs.id = rc.realm";

          $result = $this->dba->exec( $sql );
          $n	= $this->dba->getN( $result );
          for( $i = 0; $i < $n; $i++ )
          {
            $record = $this->dba->fetchArray( $result );
            $this->constrains[ $record["realmName"] ][ $record["doc"] ] = 1;
          }
          return $this->constrains;
    }

    /**
     * Retrieve the constrains for this user on all documents
     * ( Key constrain name hold array of doc id's )
     * @returns mixedArray
     */
    function getConstrains( )
    {
    	$sql  ="SELECT
                rc.doc   AS doc,
                rs.name  AS realmName
              FROM
                ". $this->constrainTable ." AS rc,
                ". $this->roleTable ."      AS r,
                ". $this->user2roleTable ." AS u2r,
                ". $this->realmsTable ."    AS rs
              WHERE
                u2r.user = ". $this->id ."
              AND
                u2r.role = r.id
              AND
                rc.role = r.id
              AND
                rs.id = rc.realm";

      $result = $this->dba->exec( $sql );
      $n	= $this->dba->getN( $result );
      for( $i = 0; $i < $n; $i++ )
      {
        $record = $this->dba->fetchArray( $result );
        $this->constrains[ $record["realmName"] ][ $record["doc"] ] = 1;
      }
      return $this->constrains;
    }
}
?>
