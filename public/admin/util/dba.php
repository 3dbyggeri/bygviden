<?php
    /*********************************************************************/
    /*   dba.php                                                         */
    /*   database abstraction layer that talks to mysql                  */
    /*                                                                   */
    /*********************************************************************/
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   Ronald Jaramillo   -   10 JULI 2001                             */
    /*                                                                   */
    /*   V I Z I O N   F A C T O R Y   N E W M E D I A                   */
    /*   Vermundsgade 40C - 2100 København Ø - Danmark                   */
    /*   Tel : +45 39 29  25 11 - Fax: +45 39 29 80 11                   */
    /*   ronald@vizionfactory.dk - www.vizionfactory.dk                  */
    /*                                                                   */
    /*********************************************************************/

    class dba
    {
    	var $host;
        var $user;
        var $db;
        var $password;
        var $link;
        var $prefix;


function dba( $db ="bygviden",$host="localhost",$user="bygviden",$password="32ReCvQa",$prefix="dev_")
{
		$this->host = $host;
		$this->user= $user;
		$this->db= $db;
		$this->password= $password;
		$this->prefix = $prefix;

		//connect
		$this->link= mysql_connect( $this->host,$this->user,$this->password );

		//verify connection
		if(!$this->link)
		{
			$this->error();
			die("Error while trying to connect to db");
		}

		//select db
	    	if(!mysql_select_db( $this->db, $this->link)){
			$this->error();
			die("Error whilte trying to select db");
	    	}
	}
    	function exec($sql){
        	if(!($result=mysql_query($sql,$this->link))){
			$this->error();
            		die("Error whild trying to query <b>$sql</b>");
        	}
        	return $result; 
    	}
    	function getN($result){
        	return mysql_num_rows($result);
    	}
    	function num_field($result){
        	return mysql_num_fields($result);
    	}
    	function fetch_array($result){
        	return mysql_fetch_array($result);
    	}
    	function fetchArray($result){
        	return mysql_fetch_array($result);
    	}
    	function getRecord($result){
        	return mysql_fetch_row($result);
    	}
    	function field_type($result,$index){
        	return mysql_field_type($result,$index);
    	}
    	function free_result($result){
        	mysql_free_result($result);
    	}
    	function last_inserted_id()
        {
        	return mysql_insert_id();
    	}
        function getPrefix()
        {
            return $this->prefix;
        }
        function error()
        {
            echo mysql_error($this->link);
        }
    	function list_fields($table)
    	{
    		return mysql_list_fields($this->db,$table,$this->link);
    	}
    	function field_name($result,$index)
        {
        	return mysql_field_name($result,$index);
        }
    	function singleQuery( $sql )
        {
            $result = $this->exec( $sql );
            $record = $this->getRecord( $result );
            if( count( $record ) == 1 ) return $record[0];
            else return $record;
        }
        function singleArray( $sql )
        {
            $result = $this->exec( $sql );
            return $this->fetch_array( $result );
        }
        function affectedRows( )
        {
            return mysql_affected_rows();
        }

    }
?>
