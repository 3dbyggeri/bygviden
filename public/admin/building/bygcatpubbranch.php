<?php
  require_once("../util/dba.php");
  
  if($_REQUEST['turn'])
  {
    	$dba  = new dba();
  	if($_REQUEST['turn'] == 'off')
	{
		$sql="DELETE 
			FROM ". $dba->getPrefix() ."bygcatpubbranch 
		      WHERE
			element_id = ". $_REQUEST['byg'] ."
		     AND
		     	category_id = ". $_REQUEST['cat'] ."
		     AND
		     	publication_id = ". $_REQUEST['pub'] ."
		     AND
		     	branche = '". $_REQUEST['branch'] ."' ";
	}
	else
	{
		$sql.="INSERT INTO ". $dba->getPrefix() ."bygcatpubbranch 
		       ( element_id, category_id, publication_id, branche )
		       VALUES
		       (".
		          $_REQUEST['byg'] .",".
		          $_REQUEST['cat'] .",". 
			  $_REQUEST['pub'] .",'".
			  $_REQUEST['branch'] ."')";
	}
	$dba->exec($sql);
  }
?>
